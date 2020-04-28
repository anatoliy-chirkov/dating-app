<?php
use Client\Services\LangService\Text;
?>
<div class="login">
    <input type="email" name="email" hidden>
    <input type="password" name="password" hidden>

    <form method="POST" action="/login">
        <h1>Войдите в свой аккаунт</h1>
        <div>или <a href="/register">зарегестрируйтесь</a></div>

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
        <div class="form-group button-group">
            <button type="submit"><?=Text::get('next')?></button>
        </div>
    </form>
</div>
