<?php

namespace Controllers;

use Controllers\Shared\SiteController;
use Core\Http\Request;
use Core\ServiceContainer;
use Repositories\GoogleGeoRepository;
use Services\GoogleGeoService\IGoogleGeoType;

class GeoController extends SiteController
{
    public function search(Request $request)
    {
        /** @var GoogleGeoRepository $googleGeoRepository */
        $googleGeoRepository = ServiceContainer::getInstance()->get('google_geo_repository');

        if (empty($request->get('name'))) {
            $this->renderJson([
                'data'  => $googleGeoRepository->batch(1, 10, IGoogleGeoType::CITY),
                'error' => false,
            ]);
        }

        $this->renderJson([
            'data'  => $googleGeoRepository->search($request->get('name'), IGoogleGeoType::CITY),
            'error' => false,
        ]);
    }
}
