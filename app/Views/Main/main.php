<?php
/**
 * @var array $men
 * @var array $women
 */
?>

<div class="main">
    <div class="heading">
        <div class="title">
            <h1>Знакомства</h1>
            <div>Для разного формата отношений</div>
        </div>

        <form method="GET" action="/search">
            <h4>Поиск</h4>

            <div class="search-wrap">
                <div class="search">
                    <div class="form-group sex-group">
                        <label>Показать<br>
                            <input type="checkbox" id="sexChoice1" name="sex[]" value="man">
                            <label for="sexChoice1">👨 Мужчину</label>
                            <br>
                            <input type="checkbox" id="sexChoice2" name="sex[]" value="woman">
                            <label for="sexChoice2">👩 Девушку</label>
                        </label>
                    </div>
                    <div class="form-group age-group">
                        <div>Возраст</div>
                        <div class="range">
                            <label>от<input type="number" name="ageFrom"></label>
                            <label>до<input type="number" name="ageTo"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group city-group">
                    <label>
                        Город <br>
                        <select name="googleGeoId[]" class="google-geo-select" multiple=""></select>
                    </label>
                </div>
            </div>

            <div class="button-group">
                <button type="submit">Найти</button>
            </div>
        </form>
    </div>

    <?php if (!empty($women)): ?>
    <h3>Девушки</h3>
    <div class="results">
        <?php
        for ($i = 0; count($women) < 3 ? $i < count($women) : $i <= 3; $i++):
            ?>
            <a href="/user/<?=$women[$i]['id']?>" class="profile">
                <div class="image" style="background-image: url('<?=$women[$i]['clientPath']?>')"></div>
                <div class="about-wrap">
                    <div class="about"><span class="name"><?=$women[$i]['name']?></span>, <?=$women[$i]['age']?></div>
                    <?=\Core\ServiceContainer::getInstance()->get('is_user_online_service')->getViewElement($women[$i]['sex'], $women[$i]['isConnected'], $women[$i]['lastConnected'])?>
                    <div class="city"><?=$women[$i]['city']?></div>
                </div>
            </a>
        <?php
        endfor;
        ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($men)): ?>
    <h3>Мужчины</h3>
    <div class="results">
        <?php
        for ($i = 0; count($men) < 3 ? $i < count($men) : $i <= 3; $i++):
            ?>
            <a href="/user/<?=$men[$i]['id']?>" class="profile">
                <div class="image" style="background-image: url('<?=$men[$i]['clientPath']?>')"></div>
                <div class="about-wrap">
                    <div class="about"><span class="name"><?=$men[$i]['name']?></span>, <?=$men[$i]['age']?></div>
                    <?=\Core\ServiceContainer::getInstance()->get('is_user_online_service')->getViewElement($men[$i]['sex'], $men[$i]['isConnected'], $men[$i]['lastConnected'])?>
                    <div class="city"><?=$men[$i]['city']?></div>
                </div>
            </a>
        <?php
        endfor;
        ?>
    </div>
    <?php endif; ?>
</div>
<script src="/node_modules/select2/dist/js/select2.full.js" type="application/javascript"></script>
<script>
    $(".google-geo-select").select2({
        width: '100%',
        placeholder: "Выберите город",
        ajax: {
            url: '/geo-search',
            dataType: 'json',
            type: "GET",
            quietMillis: 50,
            data: function (term) {
                return {
                    name: term.term
                };
            },
            processResults: function (data) {
                return {
                    results: $.map(data.data, function (item) {
                        return {
                            text: item.fullName,
                            id: item.id
                        }
                    })
                };
            }
        }
    });
</script>
