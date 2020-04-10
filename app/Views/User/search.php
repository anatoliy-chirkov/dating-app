<?php
/**
 * @var array $users
 * @var array $sex
 * @var string $ageFrom
 * @var string $ageTo
 * @var string $city
 * @var int $page
 * @var int $pages
 */
?>

<div class="main search-page">
    <form method="GET" action="/search">
        <h4>Поиск</h4>

        <div class="search">
            <div class="form-group sex-group">
                <label>Показать<br>
                    <input type="checkbox" id="sexChoice1" name="sex[]" value="man" <?php if(array_search('man', $sex) !== false): echo 'checked'; endif; ?>>
                    <label for="sexChoice1">👨 Мужчину</label>
                    <br>
                    <input type="checkbox" id="sexChoice2" name="sex[]" value="woman" <?php if(array_search('woman', $sex) !== false): echo 'checked'; endif; ?>>
                    <label for="sexChoice2">👩 Девушку</label>
                </label>
            </div>

            <div class="form-group">
                <label>
                    Город <br>
                    <input type="text" name="city" value="<?=$city?>">
                </label>
            </div>
        </div>
        <div class="form-group age-group">
            <div>Возраст</div>
            <div class="range">
                <label>от<input type="number" name="ageFrom" value="<?=$ageFrom?>"></label>
                <label>до<input type="number" name="ageTo" value="<?=$ageTo?>"></label>
            </div>
        </div>
        <div class="button-group">
            <button type="submit">Найти</button>
        </div>
    </form>

    <div class="results">
        <?php
            foreach ($users as $user):
        ?>
                <a href="/user/<?=$user['id']?>" class="profile">
                    <div class="image" style="background-image: url('<?=$user['clientPath']?>')"></div>
                    <div class="about-wrap">
                        <div class="about"><span class="name"><?=$user['name']?></span>, <?=$user['age']?></div>
                        <?=\Core\ServiceContainer::getInstance()->get('is_user_online_service')->getViewElement($user['sex'], $user['isConnected'], $user['lastConnected'])?>
                        <div class="city"><?=$user['city']?></div>
                    </div>
                </a>
        <?php
            endforeach;
        ?>
    </div>
    <div class="pagination">
        <? for($i = 1; $i <= $pages; $i++): ?>
            <? if ($i != $page): ?>
                <a class="page-button" href="?page=<?=$i?>&ageFrom=<?=$ageFrom?>&ageTo=<?=$ageTo?>&city=<?=$city?><?php foreach($sex as $sexItem): echo '&sex[]=' . $sexItem; endforeach; ?>"><?=$i?></a>
            <? else: ?>
                <div class="page-button active"><?=$page?></div>
            <? endif; ?>
        <? endfor; ?>
    </div>
</div>
