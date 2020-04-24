<?php
/**
 * @var array $me
 * @var array $advantages
 */
?>

<div class="shop-page">
    <div class="finances">
        <h2>💳 Кошелек</h2>
        <div class="balance">
            <div><?=$me['money']?> руб</div>
        </div>
        <div class="put-money">
            <h3>Пополнить</h3>
            <form method="POST" action="/shop/put-money">
                <div class="form-group" style="margin-top: 0;">
                    <input name="amount" placeholder="Сумма" type="number">
                </div>
                <div class="button-group">
                    <button type="submit">Пополнить</button>
                </div>
            </form>
        </div>
    </div>

<!--    <div class="active-products">-->
<!--        <h3>Активные продукты</h3>-->
<!--        <div></div>-->
<!--    </div>-->

    <div class="products">
        <form method="POST" action="/shop/buy">
                <?php foreach ($advantages as $advantage): ?>
                    <div class="group">
                        <div class="name"><?=$advantage['name']?></div>
                        <div class="about"><?=$advantage['about']?></div>
                        <div class="products-list">
                            <?php foreach ($advantage['products'] as $product): ?>
                                <div class="product">
                                    <input type="radio" name="product[]" value="<?=$advantage['id']?>" hidden>
                                    <div class="name"><?=$product['name']?></div>
                                    <div class="price">Цена: <?=$product['price']?> рублей</div>
                                    <a class="button" href="/shop/buy/<?=$product['type']?>/<?=$product['id']?>">Активировать</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
    </div>

<!--    <div class="history">-->
<!--        <h3>История</h3>-->
<!--        <div style="display: flex">-->
<!--            <div>Пополнения счета</div>-->
<!--            <div>Покупки</div>-->
<!--        </div>-->
<!--    </div>-->
</div>
