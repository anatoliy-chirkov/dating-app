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
            <div style="display: flex;">
                <h1 style="margin-right: 10px"><?=$user['name']?>, <?=$user['age']?></h1>
                <?php if ($isMe): ?>
                <form method="post" action="/logout" style="margin: 0; display: flex; justify-content: center; flex-direction: column">
                    <button style="background: none; padding: 0; margin: 0; border: none" type="submit"><img height="24" src="/svg/logout.svg"></button>
                </form>
                <?php endif; ?>
            </div>
            <div class="city"><?=$user['city']?></div>
            <div class="button-group">
                <?php if ($isAuthorized): ?>
                    <?php if (!$isMe): ?>
                        <a class="button" href="/user/<?=$user['id']?>/chat#last-message">Написать сообщение</a>
                    <?php else: ?>
                        <a class="button button-white" href="/profile">Настройки профиля</a>
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
