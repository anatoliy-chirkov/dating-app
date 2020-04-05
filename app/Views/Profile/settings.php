<?php
/**
 * @var array $images
 * @var array $me
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

<h4>Изменить информацию о себе</h4>
<form action="/profile/update" method="POST">
    <div class="form-group">
        <label>
            <div>Имя</div>
            <input type="text" name="name" value="<?=$me['name']?>">
        </label>
    </div>
    <div class="form-group">
        <label>
            <div>Город</div>
            <input type="text" name="city" value="<?=$me['city']?>">
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

<h4>Изменить пароль</h4>
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
