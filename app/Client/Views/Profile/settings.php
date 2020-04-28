<?php
/**
 * @var array $images
 * @var array $me
 * @var \Services\NotificationService\Notification $notification
 */
?>
<div class="profile-settings">
    <h1><span class="mobile-back"></span>Настройки профиля</h1>

    <div class="settings-view">
        <div class="setting-list">
            <a href="#content" class="setting active" id="current-setting">
                <div class="title-wrap">
                    <div class="title">Мои фото</div>
                </div>
            </a>
            <a href="/profile/edit" class="setting">
                <div class="title-wrap">
                    <div class="title">Личная информация</div>
                </div>
            </a>
            <a href="/profile/change-password" class="setting">
                <div class="title-wrap">
                    <div class="title">Сменить пароль</div>
                </div>
            </a>
        </div>

        <div class="window">
            <? if ($notification->isset()): ?>
                <div class="notification <?=$notification->type?>"><?= $notification->message ?></div>
            <? endif; ?>

            <h4>Добавить новое фото</h4>
            <form action="/profile/add-photo" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="photo">
                </div>
                <div class="button-group">
                    <button type="submit">Добавить</button>
                </div>
            </form>

            <?php if (!empty($images)): ?>
                <h4>Выбрать главное фото профиля</h4>
                <form action="/profile/choose-main-photo" method="POST" class="choose-main-photo-form">
                    <div class="image-list">
                        <?php foreach ($images as $image): ?>
                            <div class="form-group">
                                <label>
                                    <div class="image" style="background-image: url('<?=$image['clientPath']?>')"></div>
                                    <input type="radio" name="mainPhoto" value="<?=$image['id']?>" <?php if ($image['isMain'] === '1'): ?>checked<?php endif; ?>>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="button-group">
                        <button type="submit">Сохранить выбор</button>
                    </div>
                </form>
            <?php endif; ?>

            <?php if (!empty($images)): ?>
                <h4>Удалить фото</h4>
                <form action="/profile/delete-photo" method="POST" class="choose-main-photo-form">
                    <div class="image-list">
                        <?php foreach ($images as $image): ?>
                            <div class="form-group">
                                <label>
                                    <div class="image" style="background-image: url('<?=$image['clientPath']?>')"></div>
                                    <input type="checkbox" name="photo[]" value="<?=$image['id']?>">
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="button-group">
                        <button type="submit">Удалить выбранные</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="application/javascript" src="/js/profileSettingsMobile.js"></script>
<script>
    if (window.location.hash === '#content') {
        hideMenuAndShowContent();
    } else {
        showMenuAndHideContent();

        if (window.innerWidth <= 680) {
            currentLink.removeClass('active');
        }
    }
</script>
