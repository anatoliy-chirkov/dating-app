<?php
/**
 * @var array $men
 * @var array $women
 */
?>

<div class="main">
    <div class="heading">
        <h1>Сайт для знакомства богатых мужчин и содержанок</h1>
        <form method="GET" action="/search">
            <h4>Поиск</h4>

            <div class="search">
                <div class="form-group">
                    <label>Показать<br>
                        <input type="checkbox" id="sexChoice1" name="sex[]" value="man">
                        <label for="sexChoice1">👨 Мужчину</label>
                        <br>
                        <input type="checkbox" id="sexChoice2" name="sex[]" value="woman">
                        <label for="sexChoice2">👩 Женщину</label>
                    </label>
                </div>

                <div class="form-group">
                    <label>
                        Город <br>
                        <input type="text" name="city">
                    </label>
                </div>
            </div>
            <div class="form-group age-group">
                <div>Возраст</div>
                <div class="range">
                    <label>от<input type="number" name="ageFrom"></label>
                    <label>до<input type="number" name="ageTo"></label>
                </div>
            </div>
            <div class="button-group">
                <button type="submit">Найти</button>
            </div>
        </form>
    </div>

    <h3>Содержанки</h3>
    <div class="results">
        <?php
        for ($i = 0; $i <= 3; $i++):
            ?>
            <a href="/user/<?=$women[$i]['id']?>" class="profile">
                <div class="image" style="background-image: url('<?=$women[$i]['clientPath']?>')"></div>
                <div class="about-wrap">
                    <div class="about"><span class="name"><?=$women[$i]['name']?></span>, <?=$women[$i]['age']?></div>
                    <div class="city"><?=$women[$i]['city']?></div>
                </div>
            </a>
        <?php
        endfor;
        ?>
    </div>

    <h3>Спонсоры</h3>
    <div class="results">
        <?php
        for ($i = 0; $i <= 3; $i++):
            ?>
            <a href="/user/<?=$men[$i]['id']?>" class="profile">
                <div class="image" style="background-image: url('<?=$men[$i]['clientPath']?>')"></div>
                <div class="about-wrap">
                    <div class="about"><span class="name"><?=$men[$i]['name']?></span>, <?=$men[$i]['age']?></div>
                    <div class="city"><?=$men[$i]['city']?></div>
                </div>
            </a>
        <?php
        endfor;
        ?>
    </div>
</div>
