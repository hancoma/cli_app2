<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<form role="form" name="form_signup" method="POST" target="_self" action="/sign/up_process">
    <section id="signup" class="content">
        <div class="cb_img"></div>
        <div class="content_auth">
            <p class="title_text text_blue"><?=c2ms('sign_up_title');?></p>
            <p class="sub_text"><?=c2ms('sign_up_msg');?></p>
            <div id="signup_form">
                <div class="form-group has-feedback">
                    <input type="text" id="inputName" class="form-control sign_input" name="user_name" placeholder="<?=c2ms('ph_full_name');?>">
                    <span class="fa form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback ddde">
                    <input type="text" id="inputEmail" class="form-control sign_input" name="user_id" placeholder="<?=c2ms('ph_email');?>">
                    <span class="fa form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" id="inputPassword" class="form-control sign_input" name="user_password" placeholder="<?=c2ms('ph_password');?>">
                    <span class="fa form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" id="inputRepeat" class="form-control sign_input" name="repassword" placeholder="<?=c2ms('ph_password_confirm');?>">
                    <span class="fa form-control-feedback"></span>
                </div>

                <div id="coupon_wrap" class="form-wrap form-group">
                    <button id="couponButton" class="coupon_button a_hoverred" type="button"><?=c2ms('enter_coupon_msg');?></button>
                    <div id="form_coupon">
                        <input type='hidden' name='coupon_verify_yn' value='N'/>
                        <input type='hidden' name='coupon_idx' value=''/>

                        <button id="couponCloseButton" class="coupon_close control-label" type="button">X</button>
                        <div class="form-group inline_left">
                            <input type="text" class="form-control pull-left" name="coupon" placeholder="<?=c2ms('ph_coupon');?>">
                            <span class="fa form-control-feedback" style="right:8px"></span>
                        </div>
                        <input type="button" id="btn_verify_coupon" class="btn btn-grey btn-sm" value="<?=c2ms('verify_btn');?>">
                    </div>
                </div>

                <div class="checkbox checkbox-info form-group">
                    <input type="checkbox" id="signup_policy1" class="check_agree" name="policy1" value="policy1">
                    <label for="signup_policy1">
                        <?php echo c2ms('sign_up_policy_link1');?>
                    </label>
                    <span class="fa"></span>
                </div>
                <div class="checkbox checkbox-info">

                    <input type="checkbox" id="signup_policy2" class="check_agree" name="policy2" value="policy2">
                    <label for="signup_policy2">
                        <?php echo c2ms('sign_up_policy_link2');?>
                    </label>
                    <span class="fa"></span>
                </div>
                <div class="auth_button inline_clear">
                    <input id="btn_signup" type="button" class="btn btn-block btn-lineblue btn-lg" value="<?=c2ms('creat_account_btn');?>">
                    <a href="/sign/in" target="_self" class="back_page a_blue">&lt; <?=c2ms('go_to_sign');?></a>
                </div>
            </div>
        </div>
    </section>
</form>