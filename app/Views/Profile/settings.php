<?php
/**
 * @var array $images
 */
?>

<h1>Настройки профиля</h1>

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
                    <input type="radio" name="mainPhoto" value="<?=$image['id']?>" <?php if ($image['isMain'] === 1): ?>checked<?php endif; ?>>
                </label>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="button-group">
        <button type="submit">Сохранить выбор</button>
    </div>
</form>
<?php endif; ?>

<h4>Изменить пароль</h4>
<form action="/profile/change-password" method="POST">
    <div class="form-group">
        <label>
            Старый пароль
            <input type="password" name="oldPassword">
        </label>
    </div>
    <div class="form-group">
        <label>
            Новый пароль
            <input type="password" name="newPassword">
        </label>
    </div>
    <div class="form-group">
        <label>
            Повторите новый пароль
            <input type="password" name="newPasswordRepeat">
        </label>
    </div>
    <div class="form-group">
        <label>
            Выйти из аккаунта на других устройствах?
            <input type="checkbox" name="logoutEverywhere">
        </label>
    </div>
    <div class="button-group">
        <button type="submit">Изменить</button>
    </div>
</form>
