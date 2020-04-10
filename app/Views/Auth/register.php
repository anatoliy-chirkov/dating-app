<?php
/**
 * @var string $googleApiKey
 */
?>
<div class="register">
    <form method="POST" action="/register" enctype="multipart/form-data">
        <h1>Регистрация</h1>

        <div class="form-title">Общее</div>
        <div class="form-group">
            <label>
                Ваш пол<br>
                <input type="radio" id="sexChoice1" name="sex" value="man">
                <label for="sexChoice1">👨 Мужчина</label>
                <br>
                <input type="radio" id="sexChoice2" name="sex" value="women">
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
            <button type="submit">Продолжить</button>
        </div>
    </form>
</div>
<script>
    let geoAutocomplete;
    let cityInput = $('#city-hidden-input');
    let cityValue = '';

    function initGeoSearch() {
        const input = document.getElementById('google-location-search');
        geoAutocomplete = new google.maps.places.Autocomplete(input, {
            'fields': ['address_components', 'geometry', 'place_id', 'formatted_address'],
            'types':  ['(cities)'],
        });
        geoAutocomplete.addListener('place_changed', parseGeoData);
        input.onchange = () => {
            input.value = '';
            cityValue = '';
            cityInput.val(cityValue);
        }
    }

    function parseGeoData() {
        const place = geoAutocomplete.getPlace();
        place.address_components.forEach((item) => {
            const type = item.types.shift();
            if (['locality', 'administrative_area_level_1', 'country'].includes(type)) {
                cityValue += type + '=' + item.long_name + ';';
            }
        });
        cityValue += 'placeId=' + place.place_id + ';';
        cityValue += 'lat=' + place.geometry.location.lat() + ';';
        cityValue += 'lng=' + place.geometry.location.lng() + ';';
        cityValue += 'fullName=' + place.formatted_address + ';';
        cityInput.val(cityValue);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?=$googleApiKey?>&libraries=places&language=ru&callback=initGeoSearch" async defer></script>
