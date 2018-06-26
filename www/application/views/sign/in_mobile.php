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
</head>
<body>
<div id="app">
    <div class="views">
        <div class="view view-main">
            <div class="login-screen">
                <div class="page">
                    <div class="page-content login-screen-content">
                        <div class="login-image">
                            <img src="/assets-mobile/img/main-auth.png" alt="Cloudbric auth logo">
                        </div>
                        <div class="login-screen-title">Welcome to Cloudbric!</div>
                        <form action="/sign/in_process_mobile" method="post" id="login-form">
                            <div class="list">
                                <ul>
                                    <li class="item-content item-input">
                                        <div class="item-inner">
                                            <div class="item-input-wrap">
                                                <input type="email" name="username" placeholder="E-mail Address" value="staff@greenolivetree.net" required="required" validate="validate">
                                                <span class="input-clear-button"></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="item-content item-input">
                                        <div class="item-inner">
                                            <div class="item-input-wrap">
                                                <input type="password" name="password" placeholder="Password" required="required">
                                                <span class="input-clear-button"></span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="list">
                                <ul>
                                    <li>
                                        <input type="submit" class="button button-fill color-blue" value="Log In">
                                    </li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets-mobile/framework7/js/framework7.min.js"></script>
<script src="/assets-mobile/js/routes_signin.js?<?php echo LIB_CACHE_DATE?>"></script>
<script src="/assets-mobile/js/app_signin.js?<?php echo LIB_CACHE_DATE?>"></script>
</body>
</html>