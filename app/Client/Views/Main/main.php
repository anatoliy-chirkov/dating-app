<?php
/**
 * @var array $men
 * @var array $women
 */
use Client\Services\LangService\Text;
?>

<div class="main">
    <div class="heading">
        <div class="title">
            <h1><?=Text::get('mainTitle')?></h1>
            <div><?=Text::get('mainSubtitle')?></div>
            <div style="font-size: 16px;margin-top: 20px;">
                <?=Text::get('mainDesc')?>
            </div>
        </div>

        <form method="GET" action="/search">
            <h4><?=Text::get('search')?></h4>

            <div class="search-wrap">
                <div class="search">
                    <div class="form-group sex-group">
                        <label><?=Text::get('show')?><br>
                            <input type="checkbox" id="sexChoice1" name="sex[]" value="man">
                            <label for="sexChoice1"><?=Text::get('ISearchMan')?></label>
                            <br>
                            <input type="checkbox" id="sexChoice2" name="sex[]" value="woman">
                            <label for="sexChoice2"><?=Text::get('ISearchWoman')?></label>
                        </label>
                    </div>
                    <div class="form-group age-group">
                        <div><?=Text::get('age')?></div>
                        <div class="range">
                            <label><input type="number" name="ageFrom" placeholder="<?=Text::get('from')?>"></label>
                            <label>-<input type="number" name="ageTo" placeholder="<?=Text::get('to')?>" style="margin-left: 10px"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group city-group">
                    <label>
                        <?=Text::get('city')?> <br>
                        <select name="googleGeoId[]" class="google-geo-select" multiple=""></select>
                    </label>
                </div>
            </div>

            <div class="button-group">
                <button type="submit"><?=Text::get('find')?></button>
            </div>
        </form>
    </div>

    <?php if (!empty($women)): ?>
    <h3><?=Text::get('women')?></h3>
    <div class="results">
        <?php for ($i = 0; count($women) < 4 ? $i < count($women) : $i < 4; $i++): ?>
            <a href="/user/<?=$women[$i]['id']?>" class="profile">
                <?php if ($women[$i]['isTop']): ?>
                    <div class="profile-top">TOP</div>
                <?php endif; ?>
                <div class="image" style="background-image: url('<?=$women[$i]['clientPath']?>')"></div>
                <div class="about-wrap">
                    <div class="about"><span class="name"><?=$women[$i]['name']?></span>, <?=$women[$i]['age']?></div>
                    <?=Shared\Core\App::get('onlineService')->getViewElement($women[$i]['sex'], $women[$i]['isConnected'], $women[$i]['lastConnected'])?>
                    <div class="city"><?=$women[$i]['city']?></div>
                </div>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($men)): ?>
    <h3><?=Text::get('men')?></h3>
    <div class="results">
        <?php for ($i = 0; count($men) < 4 ? $i < count($men) : $i < 4; $i++): ?>
            <a href="/user/<?=$men[$i]['id']?>" class="profile">
                <?php if ($men[$i]['isTop']): ?>
                    <div class="profile-top">TOP</div>
                <?php endif; ?>
                <div class="image" style="background-image: url('<?=$men[$i]['clientPath']?>')"></div>
                <div class="about-wrap">
                    <div class="about"><span class="name"><?=$men[$i]['name']?></span>, <?=$men[$i]['age']?></div>
                    <?=Shared\Core\App::get('onlineService')->getViewElement($men[$i]['sex'], $men[$i]['isConnected'], $men[$i]['lastConnected'])?>
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

    if (window.innerWidth > 855) {
        const first = $('.results').first();
        const second = $('.results').last();

        if (first.find('.profile').length === 2) {
            first.append('<a class="profile"></a>');
        }

        if (second.find('.profile').length === 2) {
            second.append('<a class="profile"></a>');
        }
    }
</script>
