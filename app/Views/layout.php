<?php
/**
 * @var $title string
 * @var $description string
 * @var $innerViewPath string
 * @var $notification \Services\NotificationService\Notification
 * @var $isAuthorized bool
 */
?>
<html lang="en">
<head>
    <title><?= $title ?></title>
    <meta name="description" content="<?= $description ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, minimum-scale=1">

    <!--    <link rel="stylesheet" href="/css/main.css">-->
    <link rel="stylesheet" href="/css/general/base.css">
    <link rel="stylesheet" href="/css/general/nav.css">
    <link rel="stylesheet" href="/css/general/main-content.css">
    <link rel="stylesheet" href="/css/general/notification.css">

    <link rel="stylesheet" href="/css/my.css">

</head>
<body>
<nav class="nav">
    <div class="nav-container">
        <div class="brand">
            <a aria-current="page" class="" href="/">
<!--                <img src="/img/heart-with-ribbon.png" class="favicon" alt="Heart with ribbon">-->
                <span class="text">Ankira</span>
            </a>
        </div>
        <div class="links-left">
            <? if ($isAuthorized): ?>
                <a href="/chats">Диалоги</a>
                <a href="/profile">Мой профиль</a>
            <? endif; ?>
        </div>
        <div class="links">
            <? if (!$isAuthorized): ?>
                <a href="/login">Вход</a>
                <a href="/register">Регистрация</a>
            <? else: ?>
                <form method="post" action="/logout" style="margin-bottom: 0">
                    <button class="button-regular" type="submit">Выйти</button>
                </form>
            <? endif; ?>
        </div>
    </div>
</nav>
<main id="main-content">
    <div class="container">
        <? if ($notification->isset()): ?>
            <div class="notification <?=$notification->type?>"><?= $notification->message ?></div>
        <? endif; ?>

        <? require_once $innerViewPath; ?>
    </div>
</main>
<footer>
    <div class="container">
        <div class="bottom-block">
            <span>© Ankira Dating App 2020</span>
        </div>
    </div>
</footer>
<script type="application/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    // window.onscroll(() => {
    //     const scroll = window.scrollY;
    //     const nav = document.getElementsByClassName('.nav')[0];
    //
    //     if (scroll === 0) {
    //         nav.classList
    //     }
    // });

    $(window).scroll(function() {
        const scroll = $(window).scrollTop();

        if (scroll === 0) {
            $(".nav").removeClass("scroll");
        } else if (!$(".nav").hasClass("scroll")) {
            $(".nav").addClass("scroll");
        }
    });
</script>
</body>
</html>
