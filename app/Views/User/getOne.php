<?php
/**
 * @var array $user
 * @var bool $isAuthorized
 * @var bool $isMe
 */
?>
<div class="profile-page">
    <div class="main-info">
        <div class="avatar-box">
            <div
                    class="avatar"
                    style="background-image: url('<?=str_replace('FrontendAssets', '', $user['path'])?>')"
            ></div>
        </div>
        <div class="title">
            <h1><?=$user['name']?>, <?=$user['age']?></h1>
            <div class="city"><?=$user['city']?></div>
            <div class="button-group">
                <?php if ($isAuthorized): ?>
                    <?php if (!$isMe): ?>
                        <a class="button" href="/user/<?=$user['id']?>/chat">Написать сообщение</a>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="message-need-auth">Чтобы написать сообщение <a href="/login">войдите</a> или <a href="/register">зарегестрируйтесь</a></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="images">
    </div>
    <div class="about">
        <?php if (!empty($user['about'])): ?>
            <strong>О себе</strong>
            <br>
            <span><?=$user['about']?></span>
        <?php endif; ?>
    </div>

</div>
