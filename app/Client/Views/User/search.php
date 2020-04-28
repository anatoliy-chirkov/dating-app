<?php
/**
 * @var array $users
 * @var array $sex
 * @var string $ageFrom
 * @var string $ageTo
 * @var array $googleGeo
 * @var int $page
 * @var int $pages
 * @var array $goals
 * @var array $selectedGoalsId
 */
use Client\Services\LangService\Text;
?>

<div class="main search-page">
    <form method="GET" action="/search">
        <h4><?=Text::get('search')?></h4>

        <div class="search-wrap">
            <div class="search">
                <div class="form-group sex-group">
                    <label><?=Text::get('show')?><br>
                        <input type="checkbox" id="sexChoice1" name="sex[]" value="man" <?php if(array_search('man', $sex) !== false): echo 'checked'; endif; ?>>
                        <label for="sexChoice1"><?=Text::get('ISearchMan')?></label>
                        <br>
                        <input type="checkbox" id="sexChoice2" name="sex[]" value="woman" <?php if(array_search('woman', $sex) !== false): echo 'checked'; endif; ?>>
                        <label for="sexChoice2"><?=Text::get('ISearchWoman')?></label>
                    </label>
                </div>
            </div>
            <div class="form-group age-group">
                <div><?=Text::get('age')?></div>
                <div class="range">
                    <label><input type="number" name="ageFrom" value="<?=$ageFrom?>" placeholder="<?=Text::get('from')?>"></label>
                    <label>-<input type="number" name="ageTo" value="<?=$ageTo?>" placeholder="<?=Text::get('to')?>" style="margin-left: 10px"></label>
                </div>
            </div>
            <div class="form-group city-group">
                <label>
                    <?=Text::get('city')?> <br>
                    <select name="googleGeoId[]" class="google-geo-select" multiple="">
                        <?php foreach ($googleGeo as $googleGeoSingle): ?>
                            <option value="<?=$googleGeoSingle['id']?>" selected><?=$googleGeoSingle['fullName']?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <div class="form-group city-group">
                <label>
                    <?=Text::get('partnerGoals')?> <br>
                    <select name="goalId[]" class="goals-select" multiple="">
                        <?php foreach ($goals as $goal): ?>
                            <option value="<?=$goal['id']?>" <?=in_array($goal['id'], $selectedGoalsId) ? 'selected' : ''?>><?=$goal['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
        </div>

        <div class="button-group">
            <button type="submit"><?=Text::get('find')?></button>
        </div>
    </form>

    <div class="results">
        <?php foreach ($users as $user): ?>
                <a href="/user/<?=$user['id']?>" class="profile">
                    <?php if ($user['isTop']): ?>
                        <div class="profile-top">TOP</div>
                    <?php endif; ?>
                    <div class="image" style="background-image: url('<?=$user['clientPath']?>')"></div>
                    <div class="about-wrap">
                        <div class="about"><span class="name"><?=$user['name']?></span>, <?=$user['age']?></div>
                        <?=Shared\Core\App::get('onlineService')->getViewElement($user['sex'], $user['isConnected'], $user['lastConnected'])?>
                        <div class="city"><?=$user['city']?></div>
                    </div>
                </a>
        <?php endforeach; ?>
    </div>
    <div class="pagination">
        <? for($i = 1; $i <= $pages; $i++): ?>
            <? if ($i != $page): ?>
                <a class="page-button" href="?page=<?=$i?>&ageFrom=<?=$ageFrom?>&ageTo=<?=$ageTo?><?php foreach($googleGeo as $googleGeoSingle): echo '&googleGeoId[]=' . $googleGeoSingle['id']; endforeach; ?><?php foreach($selectedGoalsId as $goalId): echo '&goalId[]=' . $goalId; endforeach; ?><?php foreach($sex as $sexItem): echo '&sex[]=' . $sexItem; endforeach; ?>"><?=$i?></a>
            <? else: ?>
                <div class="page-button active"><?=$page?></div>
            <? endif; ?>
        <? endfor; ?>
    </div>
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

    $(".goals-select").select2({
        width: '100%',
        placeholder: "Выберите цели",
    });
</script>
