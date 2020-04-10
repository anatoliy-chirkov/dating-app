<?php

namespace Services\GoogleGeoService;

use Core\ServiceContainer;
use Repositories\GoogleGeoRepository;
use Services\GoogleGeoService\Exceptions\GoogleResponseError;
use Services\GoogleGeoService\Exceptions\NoBindRegionOrCountryException;
use Services\GoogleGeoService\Exceptions\NoGoogleApiKeyException;

class GoogleGeoService
{
    public function save(string $placeId)
    {
        $placeData = $this->getDataFromGoogle($placeId);

        /** @var GoogleGeoRepository $googleGeoRepository */
        $googleGeoRepository = ServiceContainer::getInstance()->get('google_geo_repository');

        if (!$googleGeoRepository->isExistByPlaceId($placeId)) {
            foreach ($placeData->address_components as $component) {
                switch (array_shift($component->types)) {
                    case IGoogleGeoType::COUNTRY:
                        if (!$googleGeoRepository->isExistByNameType($component->long_name, IGoogleGeoType::COUNTRY)) {
                            $googleGeoRepository->create($component->long_name, IGoogleGeoType::COUNTRY);
                        }
                        $countryId = $googleGeoRepository->getIdByNameType(
                            $component->long_name,
                            IGoogleGeoType::COUNTRY
                        );
                        break;
                    case IGoogleGeoType::REGION:
                        if (!$googleGeoRepository->isExistByNameType($component->long_name, IGoogleGeoType::REGION)) {
                            $googleGeoRepository->create($component->long_name, IGoogleGeoType::REGION);
                        }
                        $regionId = $googleGeoRepository->getIdByNameType(
                            $component->long_name,
                            IGoogleGeoType::COUNTRY
                        );
                        break;
                    case IGoogleGeoType::CITY:
                        $googleGeoRepository->create(
                            $component->long_name,
                            IGoogleGeoType::CITY,
                            $placeId,
                            $placeData->location->lat,
                            $placeData->location->lng
                        );
                        break;
                }
            }

            $cityId = $googleGeoRepository->getIdByPlaceId($placeId);

            if (!empty($countryId) && !empty($regionId)) {
                $googleGeoRepository->setParentId($cityId, $regionId);
                $googleGeoRepository->setParentId($regionId, $countryId);
            } else {
                throw new NoBindRegionOrCountryException();
            }
        }
    }

    private const
        GOOGLE_PLACE_API_SETTINGS = [
            'language' => 'ru',
            'fields'   => 'address_component,geometry',
        ]
    ;

    private function getDataFromGoogle(string $placeId)
    {
        $googleApiKey = ServiceContainer::getInstance()->get('env')->get('GOOGLE_API_KEY');

        if (empty($googleApiKey)) {
            throw new NoGoogleApiKeyException();
        }

        $url = 'https://maps.googleapis.com/maps/api/place/details/json';
        $url .= '?' . 'key' .      '=' . $googleApiKey;
        $url .= '&' . 'place_id' . '=' . $placeId;
        $url .= '&' . 'fields' .   '=' . self::GOOGLE_PLACE_API_SETTINGS['fields'];
        $url .= '&' . 'language' . '=' . self::GOOGLE_PLACE_API_SETTINGS['language'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            throw new GoogleResponseError();
        }

        return @json_decode($response);
    }
}
