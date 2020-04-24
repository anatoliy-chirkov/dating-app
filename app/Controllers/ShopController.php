<?php

namespace Controllers;

use Admin\Repositories\ProductRepository;
use Controllers\Shared\SiteController;
use Core\Controllers\IProtected;
use Core\ServiceContainer;

class ShopController extends SiteController implements IProtected
{
    public function getProtectedMethods()
    {
        return ['main', 'buy', 'putMoney'];
    }

    public function main()
    {
        /** @var ProductRepository $productRepository */
        $productRepository = ServiceContainer::getInstance()->get('product_repository');

        $groupsWithProducts = [
            [
                'name' => '💬 Премиум',
                'about' => "Для общения с новыми девушками необходимо оплачивать премиум-аккаунт.
Без премиум аккаунта ваша анкета остаётся в поиске, вы можете получать сообщения, но отвечать не сможете, также контактная информация на вашей странице будет скрыта.
Эта услуга в том числе и для вашего удобства. Мы отсекаем праздных посетителей и спамеров.",
                'products' => [
                    [
                        'id' => 1,
                        'name' => '3 дня',
                        'price' => 990,
                        'about' => ''
                    ],
                    [
                        'id' => 2,
                        'name' => '2 недели',
                        'price' => 2690,
                        'about' => ''
                    ],
                    [
                        'id' => 3,
                        'name' => '30 дней',
                        'price' => 4530,
                        'about' => ''
                    ],
                ]
            ],
            [
                'name' => '🕶️ Невидимка',
                'about' => "Вы можете скрыть свое присутствие на сайте, для всех будет выглядеть, что вы на сайт не заходите.
Как работает функция. Прочитайте, пожалуйста.
При включении режима «Инкогнито» для всех посетителей вашей анкеты и собеседников временем последнего вашего посещения будет показываться момент включения этой функции.
Вы не будете отображаться в «Кто смотрел?» у пользователей. Но имейте ввиду –если вы отвечаете на рассылку или создаете ее – дата последнего будет показываться «Инкогнито», поэтому для тех кто смотрит рассылку или ваши ответы на нее - вы можете «попасться».
Если вы во время действия будете общаться с кем-либо, именно для этого собеседника датой последнего посещения будет дата вашего последнего сообщения ему.
Т.е. последнее ваше посещение для всех, с кем вы не общались – момент включения режима «Инкогнито», для тех, с кем общались после включения – дата вашего последнего сообщения.
Это сделано чтобы не выдать активный режим «Инкогнито», иначе будет видно, что дата вашего последнего посещения – фальшивая.",
                'products' => [
                    [
                        'id' => 4,
                        'name' => '30 дней',
                        'price' => 690,
                        'about' => ''
                    ],
                ]
            ],
            [
                'name' => '📌 ТОП',
                'about' => "При закреплении в ТОП ваша анкета будет всегда вверху поиска.",
                'products' => [
                    [
                        'id' => 5,
                        'name' => '2 недели',
                        'price' => 290,
                        'about' => ''
                    ],
                    [
                        'id' => 6,
                        'name' => '30 дней',
                        'price' => 490,
                        'about' => ''
                    ],
                ]
            ],
            [
                'name' => '⬆️ Поднятие анкеты',
                'about' => "Поднятие сделает вашу анкету в поиске первой после анкет из раздела \"ТОП\".
Также поднятие анкеты сделает вашу анкету в категории \"ТОП\" первой, если вы уже оплатили \"ТОП\"",
                'products' => [
                    [
                        'id' => 7,
                        'name' => 'Разовое поднятие анкеты',
                        'price' => 190,
                        'about' => ''
                    ],
                ]
            ],
        ];

        return $this->render([
            'groupsWithProducts' => $groupsWithProducts, //$productRepository->notFreeProducts(),
        ]);
    }

    public function buy()
    {

    }

    public function putMoney()
    {

    }
}