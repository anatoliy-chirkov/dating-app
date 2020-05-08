<?php
/**
 * @var $socketUrl string
 * @var $title string
 * @var $description string
 * @var $innerViewPath string
 * @var $notification Client\Services\NotificationService\Notification
 * @var $isAuthorized bool
 * @var array $me
 * @var int $countNotReadMessages
 * @var int $countNotSeenVisits
 */
use Client\Services\LangService\Text;
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
    <link rel="stylesheet" href="/node_modules/select2/dist/css/select2.css">

    <link rel="stylesheet" href="/css/publicLayout.css">
</head>
<body>
<script type="application/javascript" src="/node_modules/jquery/dist/jquery.min.js"></script>
<div class="main-background <?php if ($_SERVER['REQUEST_URI'] === '/login' || $_SERVER['REQUEST_URI'] === '/register'): ?>entry<?php endif; ?>">
    <div class="inner"></div>
</div>
<div class="nav">
    <div class="nav-container">
        <div class="brand">
            <a aria-current="page" class="" href="/">
                <div style="background-image: url('/img/ankira-shortcut.png');
            height: 37px;
width: 37px;
background-size: contain;
background-repeat: no-repeat;"></div>
            </a>
        </div>
        <?php if ($_SERVER['REQUEST_URI'] !== '/login'): ?>
        <div class="links">
            <a href="/login" class="login-button"><?=Text::get('signIn')?></a>
        </div>
        <?php endif; ?>
    </div>
</div>
<main id="main-content">
    <div class="container" style="position: relative;">
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
