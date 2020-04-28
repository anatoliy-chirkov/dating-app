<?php
/**
 * @var string $googleApiKey
 * @var array $goals
 */
use Client\Services\LangService\Text;
?>
<div class="register">
    <form method="POST" action="/register" enctype="multipart/form-data">
        <h1><?=Text::get('signUp')?></h1>

        <div class="form-title">Общее</div>
        <div class="form-group">
            <label>
                Ваш пол<br>
                <input type="radio" id="sexChoice1" name="sex" value="man">
                <label for="sexChoice1">👨 Мужчина</label>
                <br>
                <input type="radio" id="sexChoice2" name="sex" value="woman">
                <label for="sexChoice2">👩 Девушка</label>
            </label>
        </div>
        <div class="form-group">
            <label>
                Ваше имя <br>
                <input type="text" name="name" style="width: 200px">
            </label>
        </div>
        <div class="form-group">
            <label>
                Возраст<br>
                <input type="number" name="age">
            </label>
        </div>
        <div class="form-group">
            <label>
                Город<br>
                <input autocomplete="off" id="google-location-search" type="text" placeholder="Выберите город из списка" style="width: 200px">
                <input type="text" name="city" id="city-hidden-input" hidden>
            </label>
        </div>
        <div class="form-group">
            <label>
                Цели<br>
                <select id="goals-select" name="goalId[]" multiple="">
                    <?php foreach ($goals as $goal): ?>
                        <option value="<?=$goal['id']?>"><?=$goal['name']?></option>
                    <?php endforeach; ?>
                </select>
            </label>
        </div>

        <div class="form-title">Данные для входа</div>
        <div class="form-group">
            <label>
                Email <br>
                <input type="email" name="email">
            </label>
        </div>
        <div class="form-group">
            <label>
                Пароль<br>
                <input type="password" name="password" autocomplete="new-password">
            </label>
        </div>
        <div class="form-group">
            <label>
                Повторите пароль<br>
                <input type="password" name="repeatPassword">
            </label>
        </div>

        <div class="form-title">Доп. информация (по желанию)</div>
        <div class="form-group">
            <label>
                Изображение профиля<br>
                <input type="file" name="main_photo">
            </label>
        </div>
        <div class="form-group">
            <label>
                Ваш рост<br>
                <input type="number" name="height">
            </label>
        </div>
        <div class="form-group">
            <label>
                Ваш вес<br>
                <input type="number" name="weight">
            </label>
        </div>

        <div class="form-group button-group">
            <button type="submit"><?=Text::get('next')?></button>
        </div>
    </form>
</div>
<script type="application/javascript" src="/js/googleGeo.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=$googleApiKey?>&libraries=places&language=ru&callback=initGeoSearch" async defer></script>
<script src="/node_modules/select2/dist/js/select2.full.js" type="application/javascript"></script>
<script>
    $("#goals-select").select2({
        width: '200px',
        placeholder: "Выберите цели",
    });
</script>
