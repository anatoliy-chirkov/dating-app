<?php
/**
 * @var array $me
 * @var array $groupsWithProducts
 * @var array $boughtProducts
 * @var array $counters
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
            <div>
                <?php require CLIENT_PATH . '/FrontendAssets/svg/icons.svg'; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($counters)): ?>
        <div class="counters">
            <div>
                <?php foreach ($counters as $counter): ?>
                    <div class="counter">
                        <div class="title">
                            <div class="name"><?=$counter['name']?></div>
                            <div class="count"><?=$counter['count']?></div>
                            <div class="about-toggle">Что это такое?</div>
                        </div>
                        <div class="about"><?=$counter['about']?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($boughtProducts)): ?>
    <div class="active-products">
        <h3>Активные продукты</h3>
        <div>
            <div class="table-row table-head">
                <div>Название</div>
                <div>Цена</div>
                <div>Куплено</div>
                <div>Активно до</div>
            </div>
            <?php foreach ($boughtProducts as $boughtProduct): ?>
                <div class="table-row">
                    <div><?=$boughtProduct['groupName']?>, <?=$boughtProduct['name']?></div>
                    <div><?=$boughtProduct['price']?> руб</div>
                    <div><?=$boughtProduct['createdAt']?></div>
                    <div><?=$boughtProduct['expiredAt']?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="products">
        <?php foreach ($groupsWithProducts as $groupWithProducts): ?>
            <div class="group">
                <div class="name"><?=$groupWithProducts['name']?></div>
                <div class="about"><?=$groupWithProducts['about']?></div>
                <div class="products-list">
                    <?php foreach ($groupWithProducts['products'] as $product): ?>
                        <div class="product">
                            <div class="name"><?=$product['name']?></div>
                            <div class="price">Цена: <?=$product['price']?> руб</div>
                            <a class="button" href="/shop/buy/<?=$product['id']?>">Активировать</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<!--    <div class="history">-->
<!--        <h3>История</h3>-->
<!--        <div style="display: flex">-->
<!--            <div>Пополнения счета</div>-->
<!--            <div>Покупки</div>-->
<!--        </div>-->
<!--    </div>-->
</div>
<script>
    $('.counter .about-toggle').on('click', function () {
        $('.counter .about').slideToggle();
    });
</script>
