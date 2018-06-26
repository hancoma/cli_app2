<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="/assets/js/auth_facebook.js"></script>
<script src="/assets/js/auth_google.js"></script>
<script>setLogin();</script>
<form id="form_signin" name="signin" method="post" target="_self" action="/sign/in_process">
    <section id="signin" class="content">
        <div class="cb_img"></div>
        <div class="content_auth">
            <p class="title_text text_blue"><?=c2ms('sign_in_title');?></p>
            <p class="sub_text"><?=c2ms('enter_info_msg');?></p>
            <div class="form-wrap">
                <div class="form-group">
                    <input type="text" id="useremail" class="form-control" name="useremail" value="<?php echo $isRememberId; ?>" placeholder="<?=c2ms('ph_email');?>">
                    <span class="fa form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <input type="password" id="userpw" class="form-control" name="userpw" value="" placeholder="<?=c2ms('ph_password');?>">
                    <span class="fa form-control-feedback"></span>
                </div>
                <div id="checkbox_wrap" class="form-wrap">
                    <div class="checkbox checkbox-info inline_left">
                        <input type="checkbox" id="signin_autocomplete" name="autocomplete" value="autocomplete"<?php if($isRememberId) echo ' checked="checked"'; ?>>
                        <label for="signin_autocomplete"><?=c2ms('check_remember');?></label>
                    </div>
                    <?php
                    //파트너 콘솔에선 패스워드 리셋 금지
                    if(PARTNER_CONSOLE == 'no') {?>
                    <div class="inline_right">
                        <a class="a_lineblue" href="/sign/reset"><?=c2ms('reset_password_msg');?></a>
                    </div>
                    <?php } ?>
                </div>
                <div class="auth_button inline_clear">
                <?php
                //파트너 콘솔에선 회원가입 금지
                if(PARTNER_CONSOLE == 'no' && ENVIRONMENT == 'development') {?>
                    <input type="button" class="btn btn-blue btn-lg inline_left" value="<?=c2ms('sign_in');?>" id="btn_signin">
                    <a href="/sign/up"><input type="button" class="btn btn-lineblue btn-lg inline_right" value="<?=c2ms('sign_up');?>" id="btn_signup"></a>
                <?php
                }?>
                    <input type="button" class="btn btn-blue btn-lg center-block" value="<?=c2ms('sign_in');?>" id="btn_signin">
                </div>
            </div>
            <?php
            //파트너 콘솔에선 회원가입 금지
            if(PARTNER_CONSOLE == 'no' && ENVIRONMENT == 'development') {?>
                <hr/>
                <div id="oauth">
                    <div id="fb_login">
                        <fb:login-button scope="email" onlogin="checkLoginState();" data-max-rows="10" data-size="xlarge">
                            <div style="opacity:0;">facebookloginsize</div>
                        </fb:login-button>
                    </div>
                    <div id="gg_login">
                        <div class="g-signin2" data-onsuccess="onSignIn" data-width="250" data-theme="dark"></div>
                    </div>
                </div>
            <?php
            }?>
        </div>
        <div class="modal fade" id="signInModal" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="invalid_sign"><?php echo c2ms('res_message');?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</form>