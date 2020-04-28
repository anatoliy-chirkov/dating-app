<?php
/**
 * @var array $me
 * @var \Services\NotificationService\Notification $notification
 */
use Client\Services\LangService\Text;
?>
<div class="profile-settings">
    <h1><span class="mobile-back"></span><?=Text::get('profileSettings')?></h1>

    <div class="settings-view">
        <div class="setting-list">
            <a href="/profile#content" class="setting">
                <div class="title-wrap">
                    <div class="title"><?=Text::get('myPhotos')?></div>
                </div>
            </a>
            <a href="/profile/edit" class="setting">
                <div class="title-wrap">
                    <div class="title"><?=Text::get('personalInfo')?></div>
                </div>
            </a>
            <a href="/profile/change-password" class="setting active">
                <div class="title-wrap">
                    <div class="title"><?=Text::get('changePassword')?></div>
                </div>
            </a>
        </div>

        <div class="window">
            <? if ($notification->isset()): ?>
                <div class="notification <?=$notification->type?>"><?= $notification->message ?></div>
            <? endif; ?>

            <h4>Сменить пароль</h4>
            <form action="/profile/change-password" method="POST">
                <div class="form-group">
                    <label>
                        <div>Старый пароль</div>
                        <input type="password" name="oldPassword">
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <div>Новый пароль</div>
                        <input type="password" name="newPassword">
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <div>Повторите новый пароль</div>
                        <input type="password" name="newPasswordRepeat">
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="logoutEverywhere">
                        Выйти из аккаунта на других устройствах
                    </label>
                </div>
                <div class="button-group">
                    <button type="submit">Изменить</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="application/javascript" src="/js/profileSettingsMobile.js"></script>
<script>
    hideMenuAndShowContent();
</script>
