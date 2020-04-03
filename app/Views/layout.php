<?php
/**
 * @var $title string
 * @var $description string
 * @var $innerViewPath string
 * @var $notification \Services\NotificationService\Notification
 * @var $isAuthorized bool
 * @var array $me
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
            <a aria-current="page" class="" href="/">Ankira</a>
        </div>
        <div class="links">
            <a href="/">
                <?php if ($_SERVER['REQUEST_URI'] === '/'): ?>
                    <svg aria-label="Главная" fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path d="M45.5 48H30.1c-.8 0-1.5-.7-1.5-1.5V34.2c0-2.6-2.1-4.6-4.6-4.6s-4.6 2.1-4.6 4.6v12.3c0 .8-.7 1.5-1.5 1.5H2.5c-.8 0-1.5-.7-1.5-1.5V23c0-.4.2-.8.4-1.1L22.9.4c.6-.6 1.6-.6 2.1 0l21.5 21.5c.3.3.4.7.4 1.1v23.5c.1.8-.6 1.5-1.4 1.5z"></path></svg>
                <?php else: ?>
                    <svg aria-label="Главная" fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path d="M45.3 48H30c-.8 0-1.5-.7-1.5-1.5V34.2c0-2.6-2-4.6-4.6-4.6s-4.6 2-4.6 4.6v12.3c0 .8-.7 1.5-1.5 1.5H2.5c-.8 0-1.5-.7-1.5-1.5V23c0-.4.2-.8.4-1.1L22.9.4c.6-.6 1.5-.6 2.1 0l21.5 21.5c.4.4.6 1.1.3 1.6 0 .1-.1.1-.1.2v22.8c.1.8-.6 1.5-1.4 1.5zm-13.8-3h12.3V23.4L24 3.6l-20 20V45h12.3V34.2c0-4.3 3.3-7.6 7.6-7.6s7.6 3.3 7.6 7.6V45z"></path></svg>
                <?php endif; ?>
            </a>
            <a href="/search">
                <?php if (strpos($_SERVER['REQUEST_URI'], '/search') !== false): ?>
                    <svg aria-label="Поиск" fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path d="M47.6 44L35.8 32.2C38.4 28.9 40 24.6 40 20 40 9 31 0 20 0S0 9 0 20s9 20 20 20c4.6 0 8.9-1.6 12.2-4.2L44 47.6c.6.6 1.5.6 2.1 0l1.4-1.4c.6-.6.6-1.6.1-2.2zM20 35c-8.3 0-15-6.7-15-15S11.7 5 20 5s15 6.7 15 15-6.7 15-15 15z"></path></svg>
                <?php else: ?>
                    <svg aria-label="Поиск" fill="#262626" height="24" viewBox="0 0 48 48" width="24"><path d="M20 40C9 40 0 31 0 20S9 0 20 0s20 9 20 20-9 20-20 20zm0-37C10.6 3 3 10.6 3 20s7.6 17 17 17 17-7.6 17-17S29.4 3 20 3z"></path><path d="M46.6 48.1c-.4 0-.8-.1-1.1-.4L32 34.2c-.6-.6-.6-1.5 0-2.1s1.5-.6 2.1 0l13.5 13.5c.6.6.6 1.5 0 2.1-.2.3-.6.4-1 .4z"></path></svg>
                <?php endif; ?>
            </a>
            <? if ($isAuthorized): ?>
            <a href="/chats">
                <?php if (strpos($_SERVER['REQUEST_URI'], 'chat') !== false): ?>
                    <?php require APP_PATH . '/FrontendAssets/svg/mail_.svg'; ?>
                <?php else: ?>
                    <?php require APP_PATH . '/FrontendAssets/svg/mail.svg'; ?>
                <?php endif; ?>
            </a>
            <a href="/user/<?=$me['id']?>">
                <div style="background-image: url('<?=$me['path']?>'); height: 24px; width: 24px; background-size: cover; background-position: center center; border-radius: 24px;"></div>
            </a>
            <? else: ?>
                <a href="/login" class="login-button">Вход</a>
                <a href="/register" class="register-button">Регистрация</a>
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
