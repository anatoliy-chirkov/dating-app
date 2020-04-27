<?php
/**
 * @var string $innerViewPath
 * @var \Services\NotificationService\Notification $notification
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Ankira</title>
    <link rel="stylesheet" type="text/css" href="/assets/lib/perfect-scrollbar/css/perfect-scrollbar.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/lib/material-design-icons/css/material-design-iconic-font.min.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/lib/jquery.vectormap/jquery-jvectormap-1.2.2.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/lib/jqvmap/jqvmap.min.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/lib/datetimepicker/css/bootstrap-datetimepicker.min.css"/>
    <link rel="stylesheet" type="text/css" href="/assets/lib/select2/css/select2.min.css"/>
    <link rel="stylesheet" href="/assets/css/app.css" type="text/css"/>
</head>
<body>
<script src="/assets/lib/jquery/jquery.min.js" type="text/javascript"></script>
<script src="/assets/lib/perfect-scrollbar/js/perfect-scrollbar.min.js" type="text/javascript"></script>
<script src="/assets/lib/bootstrap/dist/js/bootstrap.bundle.min.js" type="text/javascript"></script>
<script src="/assets/js/app.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        App.init();
    });
</script>
<div class="be-wrapper be-fixed-sidebar">
    <nav class="navbar navbar-expand fixed-top be-top-header">
        <div class="container-fluid">
            <div class="be-navbar-header">
                <a class="navbar-brand" href="/">Ankira Admin</a>
            </div>
            <div class="be-right-navbar">
                <ul class="nav navbar-nav float-right be-user-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" role="button" aria-expanded="false">
                            <img src="/assets/img/avatar.png" alt="Avatar"><span class="user-name">Admin</span>
                        </a>
                        <div class="dropdown-menu" role="menu">
                            <div class="user-info">
                                <div class="user-name">Admin</div>
                            </div>
                            <a class="dropdown-item" href="/logout"><span class="icon mdi mdi-power"></span>Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="be-left-sidebar">
        <div class="left-sidebar-wrapper"><a class="left-sidebar-toggle" href="#">Menu</a>
            <div class="left-sidebar-spacer">
                <div class="left-sidebar-scroll">
                    <div class="left-sidebar-content">
                        <ul class="sidebar-elements">
                            <li <?=$_SERVER['REQUEST_URI'] === '/' ? 'class="active"' : ''?>><a href="/"><i class="icon mdi mdi-case"></i><span>Dashboard</span></a>
                            </li>
                            <li class="parent"><a href="#"><i class="icon mdi mdi-card"></i><span>Payments</span></a>
                                <ul class="sub-menu">
                                    <li <?=strpos($_SERVER['REQUEST_URI'], 'bill') !== false ? 'class="active"' : ''?>>
                                        <a href="/payments/bills">Bills</a>
                                    </li>
                                    <li <?=strpos($_SERVER['REQUEST_URI'], 'purchase') !== false ? 'class="active"' : ''?>>
                                        <a href="/payments/purchases">Purchases</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="parent"><a href="#"><i class="icon mdi mdi-labels"></i><span>Products</span></a>
                                <ul class="sub-menu">
                                    <li <?=strpos($_SERVER['REQUEST_URI'], 'product-group') !== false ? 'class="active"' : ''?>>
                                        <a href="/product-groups">Product Groups</a>
                                    </li>
                                    <li <?=strpos($_SERVER['REQUEST_URI'], 'products') !== false ? 'class="active"' : ''?>>
                                        <a href="/products">Products</a>
                                    </li>
                                    <li <?=strpos($_SERVER['REQUEST_URI'], 'counter') !== false ? 'class="active"' : ''?>>
                                        <a href="/counters">Counters</a>
                                    </li>
                                </ul>
                            </li>
                            <li <?=$_SERVER['REQUEST_URI'] === '/users' ? 'class="active"' : ''?>>
                                <a href="/users"><i class="icon mdi mdi-accounts"></i><span>Users</span></a>
                            </li>
                            <li <?=$_SERVER['REQUEST_URI'] === '/bots' ? 'class="active"' : ''?>>
                                <a href="/bots"><i class="icon mdi mdi-devices"></i><span>Bots</span></a>
                            </li>
                            <li <?=$_SERVER['REQUEST_URI'] === '/logs' ? 'class="active"' : ''?>>
                                <a href="/logs"><i class="icon mdi mdi-file-text"></i><span>Logs</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="be-content">
        <div class="main-content container-fluid">
        <? if (!isset($LAYOUT_NOTIFICATION_OFF) && $notification->isset()): ?>
            <div class="alert alert-<?=$notification->type === 'error' ? 'danger' : 'success'?> alert-dismissible" role="alert">
                <button class="close" type="button" data-dismiss="alert" aria-label="Close"><span class="mdi mdi-close" aria-hidden="true"></span></button>
                <div class="icon"> <span class="mdi mdi-<?=$notification->type === 'error' ? 'close-circle-o' : 'check'?>"></span></div>
                <div class="message"><strong><?=ucfirst($notification->type)?></strong> <?=$notification->message?></div>
            </div>
        <? endif; ?>

        <? require_once $innerViewPath; ?>
        </div>
    </div>
    <nav class="be-right-sidebar">
        <div class="sb-content">
            <div class="tab-navigation">
                <ul class="nav nav-tabs nav-justified" role="tablist">
                    <li class="nav-item" role="presentation"><a class="nav-link active" href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">Chat</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">Todo</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">Settings</a></li>
                </ul>
            </div>
            <div class="tab-panel">
                <div class="tab-content">
                </div>
            </div>
        </div>
    </nav>
</div>
</body>
</html>
