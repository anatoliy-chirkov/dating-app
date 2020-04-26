<?php
/**
 * @var array $me
 * @var string $googleApiKey
 * @var string $cityString
 * @var array $goals
 * @var array $userGoalsId
 * @var \Services\NotificationService\Notification $notification
 */
?>
<div class="profile-settings">
    <h1><span class="mobile-back"></span>Настройки профиля</h1>

    <div class="settings-view">
        <div class="setting-list">
            <a href="/profile#content" class="setting">
                <div class="title-wrap">
                    <div class="title">Мои фото</div>
                </div>
            </a>
            <a href="/profile/edit" class="setting active">
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

            <h4>Изменить информацию о себе</h4>
            <form action="/profile/edit" method="POST">
                <div class="form-group">
                    <label>
                        <div>Имя</div>
                        <input type="text" name="name" value="<?=$me['name']?>">
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <div>Город</div>
                        <input autocomplete="off" id="google-location-search" value="<?=$me['city']?>" type="text" placeholder="Выберите город из списка">
                        <input type="text" name="city" id="city-hidden-input" value="<?=$cityString?>" hidden>
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        Цели<br>
                        <select id="goals-select" name="goalId[]" multiple="">
                            <?php foreach ($goals as $goal): ?>
                                <option value="<?=$goal['id']?>" <?=in_array($goal['id'], $userGoalsId) ? 'selected' : ''?>><?=$goal['name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <div>Возраст</div>
                        <input type="number" name="age" value="<?=$me['age']?>">
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <div>Рост</div>
                        <input type="number" name="height" value="<?=$me['height']?>">
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <div>Вес</div>
                        <input type="number" name="weight" value="<?=$me['weight']?>">
                    </label>
                </div>
                <div class="form-group">
                    <label>
                        <div>О себе</div>
                        <input type="text" name="about" value="<?=$me['about']?>">
                        <!--            <textarea rows="3" name="about">--><?//=$me['about']?><!--</textarea>-->
                    </label>
                </div>
                <div class="button-group">
                    <button type="submit">Обновить информацию</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="application/javascript" src="/js/profileSettingsMobile.js"></script>
<script type="application/javascript" src="/js/googleGeo.js"></script>
<script>
    hideMenuAndShowContent();
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=$googleApiKey?>&libraries=places&language=ru&callback=initGeoSearch" async defer></script>
<script src="/node_modules/select2/dist/js/select2.full.js" type="application/javascript"></script>
<script>
    $("#goals-select").select2({
        width: '200px',
        placeholder: "Выберите цели",
    });
</script>
