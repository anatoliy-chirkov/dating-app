<?php
/**
 * @var array $visits
 * @var int $page
 * @var int $pages
 */
?>

<div id="visits-page">
    <h1>Просмотры Вашего Профиля</h1>
    <div class="results">
        <?php if (empty($visits)): ?>
            <div>Пока что никто не заходил на Вашу страницу</div>
        <?php endif; ?>
        <?php foreach ($visits as $visit): ?>
            <a href="/user/<?=$visit['userId']?>" class="visit">
                <div class="image" style="background-image: url('<?=$visit['clientPath']?>')"></div>
                <div class="about-wrap">
                    <div class="about"><span class="name"><?=$visit['name']?></span>, <?=$visit['age']?></div>
                    <div style="font-size: 14px"><?=$visit['time']?></div>
                    <?=\Core\ServiceContainer::getInstance()->get('is_user_online_service')->getViewElement($visit['sex'], $visit['isConnected'], $visit['lastConnected'])?>
                    <div class="city"><?=$visit['city']?></div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="pagination">
        <?php if ($pages > 1): ?>
        <? for($i = 1; $i <= $pages; $i++): ?>
            <? if ($i != $page): ?>
                <a class="page-button" href="/visits?page=<?=$i?>"><?=$i?></a>
            <? else: ?>
                <div class="page-button active"><?=$page?></div>
            <? endif; ?>
        <? endfor; ?>
        <?php endif; ?>
    </div>
</div>
