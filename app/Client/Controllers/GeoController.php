<?php

namespace Client\Controllers;

use Client\Controllers\Shared\SiteController;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Client\Repositories\GoogleGeoRepository;
use Client\Services\GoogleGeoService\IGoogleGeoType;

class GeoController extends SiteController
{
    public function search(Request $request)
    {
        /** @var GoogleGeoRepository $googleGeoRepository */
        $googleGeoRepository = App::get('googleGeo');

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
