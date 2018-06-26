<section id="myaccount" class="content">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li id="tab_profile" class="tab_item active">
                <a href="#profile" data-toggle="tab"><?=c2ms('tab_1');?></a>
            </li>
            <li id="tab_password" class="tab_item">
                <a href="#tabChangePassword" data-toggle="tab"><?=c2ms('tab_2');?></a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- TAB : Profile  -->
            <div class="tab-pane active" id="profile">
                <div class="myaccount_content">

                    <!-- oauth 유저인 경우 프로필을 수정할 수 없다는 문구 출력 -->
                    <?php
                    if($user_info["oauth"] !== "cb") {
                        ?>
                        <p id="notice_oauth" class="text_blue sub_text">
                            <?=($user_info["oauth"] == "fb") ? c2ms('is_oauth_fb') : c2ms('is_oauth_gp');?>
                        </p>
                        <?php
                    } ?>

                    <div class="form-group">
                        <div class="form-wrap">
                            <label for="user_name" class="form_label label_tab1"><?=c2ms('user_name');?></label>
                            <input type="text" id="user_name" class="form-control profile_input" name="user_name" value="<?=$user_info["user_name"]?>" placeholder="<?=c2ms('ph_full_name');?>" <?=($user_info["oauth"] == "cb") ? "" : "disabled"?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-wrap">
                            <label for="user_id" class="form_label label_tab1"><?=c2ms('user_mail');?></label>
                            <input type="text" id="user_id" class="form-control profile_input" name="user_id" value="<?=$user_info["user_id"]?>" placeholder="<?=c2ms('ph_email');?>" disabled>
                        </div>
                    </div>

                    <!-- Coupon 등록 가능한 경우-->
                    <div id="register_coupon" class="form-wrap form-group" <?=($user_info["coupon_number"] == "") ? "" : "style='display:none;'"?>>
                        <a id="enterCouponButton" class="coupon_enter_message a_hoverred"><?=c2ms('enter_coupon_msg');?></a>
                        <div id="couponForm" class="coupon_form">
                            <label id="closeCouponButton" class="control-label coupon_close">X</label>

                            <div class="form-group">
                                <label for="coupon_number" class="form_label label_tab1">Coupon</label>
                                <input type="text" id="coupon_number" class="form-control profile_input coupon_input" name="coupon" placeholder="<?=c2ms('ph_coupon');?>">
                                <span class="fa form-control-feedback" style="right:8px"></span>
                                <input type="button" id="btn_verify_coupon" class="btn btn-gray btn-sm verify_button" value="<?=c2ms('verify_btn');?>">
                            </div>
                        </div>
                    </div>

                    <!-- Coupon 이미 등록했거나 등록 할 쿠폰 verify 통과한 경우 -->
                    <div id="pre_register_coupon"
                         class="form-group" <?=($user_info["coupon_number"] == "") ? "style='display:none;'" : ""?>>
                        <div class="form-wrap">
                            <label for="pre_coupon_number" class="form_label label_tab1">Coupon</label>
                            <input type="text" id="pre_coupon_number" class="form-control profile_input" name="coupon_number" value="<?=($user_info["coupon_number"] != "") ? $user_info["coupon_number"] : ""?>" disabled>
                        </div>
                    </div>

                    <div class="button_wrap">
                        <input id="btn_modify_profile" type="button" class="btn btn-block btn-lineblue btn-lg" value="<?=c2ms('save_btn');?>">
                    </div>
                </div>
            </div>

            <!-- TAB : Password -->
            <div class="tab-pane" id="tabChangePassword">
                <div class="myaccount_content">

                    <!-- oauth 유저인 경우 프로필을 수정할 수 없다는 문구 출력 -->
                    <?php
                    if($user_info["oauth"] !== "cb") {
                        ?>
                        <p id="notice_oauth" class="text_blue sub_text">
                            <?=($user_info["oauth"] == "fb") ? c2ms('is_oauth_fb') : c2ms('is_oauth_gp');?>
                        </p>
                        <?php
                    } ?>

                    <div class="form-group">
                        <div class="form-wrap">
                            <label for="current_pwd" class="form_label label_tab2"><?=c2ms('password_label_1');?></label>
                            <input type="password" class="form-control password_input" name="current_pwd" data-verify="input_current_password" <?=($user_info["oauth"] == "cb") ? "" : "disabled"?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-wrap">
                            <label for="new_pwd" class="form_label label_tab2"><?=c2ms('password_label_2');?></label>
                            <input type="password" class="form-control password_input" name="new_pwd" data-verify="input_new_password" <?=($user_info["oauth"] == "cb") ? "" : "disabled"?>>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-wrap">
                            <label for="new_repwd" class="form_label label_tab2"><?=c2ms('password_label_3');?></label>
                            <input type="password" class="form-control password_input" name="new_repwd" data-verify="input_confirm_password" <?=($user_info["oauth"] == "cb") ? "" : "disabled"?>>
                        </div>
                    </div>
                    <div class="button_wrap">
                        <input id="modifyButton" type="button" class="btn btn-block btn-lineblue btn-lg" value="<?=c2ms('save_btn');?>" <?=($user_info["oauth"] == "cb") ? "" : "disabled"?>>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>