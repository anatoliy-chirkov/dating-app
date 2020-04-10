<?php

namespace Services\GoogleGeoService;

use Core\ServiceContainer;
use Repositories\GoogleGeoRepository;

class GoogleGeoService
{
    /** @var GoogleGeoRepository $googleGeoRepository */
    private $googleGeoRepository;

    public function __construct()
    {
        $this->googleGeoRepository = ServiceContainer::getInstance()->get('google_geo_repository');
    }

    public function isValidCityString(string $cityString)
    {
        $cityData = $this->fillArrayFromString($cityString);

        return array_diff(
                [
                    IGoogleGeoType::COUNTRY,
                    IGoogleGeoType::CITY,
                    'lat',
                    'lng',
                    'placeId',
                    'fullName',
                ],
                array_keys($cityData)
            ) === [];
    }

    public function saveIfNotExistAndGetId(string $cityString)
    {
        $cityData = $this->fillArrayFromString($cityString);

        if (!$this->googleGeoRepository->isExistByPlaceId($cityData['placeId'])) {
            $countryId = $this->saveCountryIfNotExistAndGetId($cityData[IGoogleGeoType::COUNTRY]);
            $cityParentId = $countryId;

            if (isset($cityData[IGoogleGeoType::REGION])) {
                $cityParentId = $this->saveRegionIfNotExistAndGetId(
                    $cityData[IGoogleGeoType::REGION], $cityData[IGoogleGeoType::COUNTRY], $countryId
                );
            }

            $this->googleGeoRepository->create(
                $cityData[IGoogleGeoType::CITY],
                $cityData['fullName'],
                IGoogleGeoType::CITY,
                $cityParentId,
                $cityData['placeId'],
                $cityData['lat'],
                $cityData['lng']
            );
        }

        return $this->googleGeoRepository->getIdByPlaceId($cityData['placeId']);
    }

    private function saveCountryIfNotExistAndGetId($countryName)
    {
        if (!$this->googleGeoRepository->isExistByNameType($countryName, IGoogleGeoType::COUNTRY)) {
            $this->googleGeoRepository->create($countryName, $countryName, IGoogleGeoType::COUNTRY);
        }
        return $this->googleGeoRepository->getIdByNameType($countryName, IGoogleGeoType::COUNTRY);
    }

    private function saveRegionIfNotExistAndGetId($regionName, $countryName, $countryId)
    {
        if (!$this->googleGeoRepository->isExistByNameType($regionName, IGoogleGeoType::REGION)) {
            $this->googleGeoRepository->create(
                $regionName,
                "{$regionName}, {$countryName}",
                IGoogleGeoType::REGION,
                $countryId
            );
        }
        return $this->googleGeoRepository->getIdByNameType($regionName, IGoogleGeoType::REGION);
    }

    private function fillArrayFromString(string $cityString)
    {
        $cityData = [];
        foreach (explode(';', $cityString) as $row) {
            list($key, $value) = explode('=', $row);
            $cityData[$key] = $value;
        }
        return $cityData;
    }
}
