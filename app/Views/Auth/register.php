<div class="register">
    <form method="POST" action="/register">
        <h1>Регистрация</h1>

        <div class="form-title">Общее</div>
        <div class="form-group">
            <label>
                Ваш пол<br>
                <input type="radio" id="sexChoice1" name="sex" value="man">
                <label for="sexChoice1">👨 Мужчина</label>
                <br>
                <input type="radio" id="sexChoice2" name="sex" value="women">
                <label for="sexChoice2">👩 Женщина</label>
            </label>
        </div>
        <div class="form-group">
            <label>
                Ваше имя <br>
                <input type="text" name="name">
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
                <input type="text" name="city">
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
