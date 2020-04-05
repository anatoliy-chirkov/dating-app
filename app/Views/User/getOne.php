<?php
/**
 * @var array $user
 * @var bool $isAuthorized
 * @var bool $isMe
 * @var array $images
 */
?>
<div class="profile-page">
    <div class="main-info">
        <div class="avatar-box">
            <div
                    class="avatar"
                    style="background-image: url('<?=$user['clientPath']?>')"
            ></div>
        </div>
        <div class="title">
            <div style="display: flex; position: relative">
                <h1 style="margin-right: 10px"><?=$user['name']?>, <?=$user['age']?></h1>
                <?php if ($isMe): ?>
                <form method="post" action="/logout" style="margin: 0; display: flex; justify-content: center; flex-direction: column; position: absolute; right: 15px">
                    <button style="background: white; padding: 12px; margin: 0; border: none; border-radius: 50px" type="submit"><img height="16" src="/svg/logout.svg"></button>
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
        <?php foreach ($images as $image): ?>
            <div class="image" style="background-image: url('<?=$image['clientPath']?>')"></div>
        <?php endforeach; ?>
    </div>
    <div class="about">
        <?php if (!empty($user['about'])): ?>
            <strong>О себе</strong>
            <br>
            <span><?=$user['about']?></span>
        <?php endif; ?>
    </div>
    <div id="image-view" style="display: none">
        <img class="content" src="" alt="Image full view" height="80%"></div>
    </div>
</div>
<script>
    $('.profile-page').find('.images').find('.image').on('click', function () {
        const backgroundImageVal = $(this).css('background-image');
        const backgroundImageUrl = backgroundImageVal.replace('url(','').replace(')','').replace(/\"/gi, "");
        const imageView = $('#image-view');
        imageView.fadeIn();
        imageView.find('.content').attr('src', backgroundImageUrl);

        $(document).on('click', function(eDocument) {
            const target = $(eDocument.target);

            if (target.attr('id') === 'image-view' && target.closest('#image-view .content').length !== 1) {
                imageView.fadeOut();
            }
        })
    });
</script>
