<?php

namespace Client\Controllers;

use Client\Controllers\Shared\SiteController;
use Shared\Core\Http\Request;
use Shared\Core\App;
use Client\Repositories\UserRepository\UserRepository;

class MainController extends SiteController
{
    public function main(Request $request)
    {
        /** @var UserRepository $userRepository */
        $userRepository = App::get('user');
        $men = $userRepository->search($request->get('sex', ['man']));
        $women = $userRepository->search($request->get('sex', ['woman']));

        return $this->render([
            'men' => $men,
            'women' => $women,
        ]);
    }
}
