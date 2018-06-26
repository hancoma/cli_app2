<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap: content:">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#2196f3">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <title>Cloudbric App</title>
    <link rel="stylesheet" href="/assets-mobile/framework7/css/framework7.min.css">
    <link rel="stylesheet" href="/assets-mobile/css/icons.css">
    <link rel="stylesheet" href="/assets-mobile/css/app.css?<?php echo LIB_CACHE_DATE?>">
    <link rel="stylesheet" href="/assets-mobile/css/c3.min.css">
    <script src="/assets-mobile/js/d3.v4.min.js" charset="utf-8"></script>
    <script src="/assets-mobile/js/c3.min.js" charset="utf-8"></script>
</head>
<body>
<div id="app">
    <div class="views tabs ios-edges">
        <div id="view-home" class="view view-main tab tab-active">
            <div class="page" data-name="home">
                <div class="navbar">
                    <div class="navbar-inner">
                        <div class="title sliding">My Sites</div>
                    </div>
                </div>
                <div class="page-content">
                    <?php foreach($mysite_info as $naked_domain_name => $child_domains):?>
                    <div class="block-title"><?php echo $naked_domain_name;?></div>
                    <div class="list">
                        <ul>
                            <?php foreach($child_domains as $domain_info):?>
                                <?php
                                    $status = $domain_info['status'];

                                switch($status) {
                                    case 'Protected':
                                        $status_image = 'protected';
                                        break;
                                    case 'Bypass':
                                        $status_image = 'bypass';
                                        break;
                                    case 'Pending':
                                        $status_image = 'pending';
                                        break;
                                    case 'Deleting':
                                        $status_image = 'deleting';
                                        break;
                                    case 'Disconnected':
                                        $status_image = 'disconnected';
                                        break;
                                }
                                ?>
                                <li>
                                    <a href="/dashboard/a/<?php echo $domain_info['domain_idx'];?>" class="item-link item-content">
                                        <div class="item-media justify-center">
                                            <img src="/assets-mobile/img/<?php echo $status_image;?>.png" alt="<?php echo $status_image;?>">
                                        </div>
                                        <div class="item-inner">
                                            <div class="item-title"><?php echo $domain_info['domain'];?></div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        <div id="view-alarm" class="view tab"></div>
        <div id="view-myaccount" class="view tab"></div>

        <div id="view-settings" class="view tab"></div>

        <div class="toolbar tabbar toolbar-bottom-md">
            <div class="toolbar-inner">
                <a href="/mysites" class="tab-link external">
                    <i class="icon f7-icons ios-only">list</i>
                    <i class="icon material-icons md-only">home</i>
                </a>
                <a href="#view-home" class="tab-link">
                    <i class="icon material-icons">dashboard</i>
                </a>
                <a href="#view-alarm" class="tab-link">
                    <i class="icon f7-icons">bell<span class="badge color-red">3</span></i>
                </a>
                <a href="#view-myaccount" class="tab-link">
                    <i class="icon f7-icons ios-only">person</i>
                    <i class="icon material-icons md-only">person</i>
                </a>
            </div>
        </div>
    </div>
</div>

<script src="/assets-mobile/framework7/js/framework7.min.js"></script>
<script src="/assets-mobile/js/routes.js?<?php echo LIB_CACHE_DATE?>"></script>
<script src="/assets-mobile/js/app.js?<?php echo LIB_CACHE_DATE?>"></script>
</body>
</html>