<?php exit(); //비정상 접근 차단 (PC용 웹) ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php
if(PARTNER_CONSOLE == 'no') { //파트너 콘솔 : 파비콘
    ?>
    <link rel="shortcut icon" href="/assets/img/favicon.ico">
    <title>Cloudbric Console</title>
<?php } else {
    if(file_exists('./assets/partner/'.c2ms('partner_console_PARTNER_IDX').'/favicon.png')) {
        $partner_console_favicon = '/assets/partner/'.c2ms('partner_console_PARTNER_IDX').'/favicon.png';?>
    <link rel="shortcut icon" href="<?=$partner_console_favicon?>">
<?php
    } else {?>
    <link rel="icon" type="image/x-icon" href="data:image/x-icon;base64,AAABAAEAEBAQAAAAAAAoAQAAFgAAACgAAAAQAAAAIAAAAAEABAAAAAAAgAAAAAAAAAAAAAAAEAAAAAAAAAAAAAAA////AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEREREREREREREQEBEREAAQAAAAAAAAABAAAAAAAAAAEAAAAAAAAAAAAAAAAAAAAAAAABABEQAREBAAAAERAAEQAAAAAREAAREAAAAAAAAAAREAAAAAABERAAAAAAABERABAAAAAAEREQAQAAAAAREREQEAABERERERERERERERH//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA//8AAP//AAD//wAA">
<?php }?>
    <title>Console | <?=c2ms('partner_console_PARTNER_NAME_VIEW')?></title>
<?php }?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/plugins/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/common.css?<?php echo LIB_CACHE_DATE?>">
    {load_css}
    <script src="/assets/plugins/jquery/jquery-2.2.4.min.js"></script>
    <script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="/assets/js/common.js?<?php echo LIB_CACHE_DATE?>"></script>
    <script>var c2ms_list = <?php echo json_encode(c2ms("__all"));?></script>
    <script src="/assets/js/c2ms.js?<?php echo LIB_CACHE_DATE?>"></script>
    {load_head_script}
    <?php if($this->uri->uri_string() == "/sign/in"): // [Sign In] Google+ ?>
    <meta name="google-signin-scope" content="profile email">
    <meta name="google-signin-client_id" content="366192252710-87jumkmcqoril8rtpdss6nrnaps8l0ia.apps.googleusercontent.com">
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <?php endif?>
    <?php if($this->uri->segment(1) == "sign"): ?>
    <link rel="stylesheet" type="text/css" href="/assets/css/auth.css?<?php echo LIB_CACHE_DATE?>">
    <script src="/assets/js/auth.js?<?php echo LIB_CACHE_DATE?>"></script>
    <?php endif?>
    <?php if(PARTNER_CONSOLE == 'yes' && file_exists('./assets/partner/'.c2ms('partner_console_PARTNER_IDX').'/partner_style.css')): ?>
    <link rel="stylesheet" type="text/css" href="/assets/partner/<?=c2ms('partner_console_PARTNER_IDX')?>/partner_style.css?<?php echo LIB_CACHE_DATE?>">
    <?php endif?>
</head>
<body>
<div class="wrap">
    <!-- Header -->
    <header class="header">
        <?php
            if ($this->uri->segment(1) == "sign") require_once(__DIR__."/header_sign.php");
            else {
                $isActive = ['mysites', 'addsite', 'myaccount'];
                foreach($isActive as $segment) $isActive[$segment] = ($this->uri->segment(1) == $segment) ? true : false;

                require_once(__DIR__.'/header_console.php');
            }
        ?>
    </header>

    <!-- Contents -->
    <section class="content_wrapper">
        <div id="loading" class="content_loading"></div>
        <div class="content_center">
            {contents}
            <?php if($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == 'logs' || $this->uri->segment(1) == 'report' || $this->uri->segment(1) == 'settings'):?>
                <?php if($selected_domain_info['status'] == 'Pending' ) { ?>
                <div id="site_status_alert">
                    <div class="alert_child is_pending">
                        <p><?=c2ms('alert_is_pending');?></p>
                    </div>
                    <!--div class="alert_child confirm_email">
                        <p><?=c2ms('alert_confirm_email');?></p>
                    </div-->
                </div>
               <?php } ?>
            <?php endif?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <?php
            require_once(__DIR__."/footer.php");
        ?>
    </footer>
</div>

<!-- Modal -->
<div class="modal fade" id="cb_alert" aria-hidden="true" tabindex='-1'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="cb_alert_header"></h4>
            </div>
            <div id="cb_alert_body" class="modal-body"></div>
        </div>
    </div>
</div>
{load_end_script}
</body>
</html>