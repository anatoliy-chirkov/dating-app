<?php

namespace Controllers;

use Core\Controllers\BaseController;
use Core\Http\Request;
use Core\ServiceContainer;
use Core\Validation\Validator;
use Repositories\GoogleGeoRepository;

class GeoController extends BaseController
{
    public function search(Request $request)
    {
        /** @var Validator $validator */
        $validator = ServiceContainer::getInstance()->get('validator');
        if (!$validator->isValid($request->get(), ['name' => 'required']) || strlen($request->get('name')) < 3) {
            $this->renderJson([
                'data'  => [],
                'error' => true,
            ]);
        }

        /** @var GoogleGeoRepository $googleGeoRepository */
        $googleGeoRepository = ServiceContainer::getInstance()->get('google_geo_repository');

        $this->renderJson([
            'data'  => $googleGeoRepository->search($request->get('name')),
            'error' => false,
        ]);
    }
}
