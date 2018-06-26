<section id="settings" class="content">
    <?php require_once(__DIR__ . '/settings_nav.php'); ?>
    <div class="settings_content" id="settings_ip_control">
        <div class="title_text text_grey"><?=c2ms('ip_control');?></div>
        <div class="settings_content_main">
            <div class="settings_content_main_wrap">
                <div class="settings_content_main_title"><?=c2ms('ip_control_blacklist');?></div>
                <div class="sub_text"><?=c2ms('ip_control_blacklist_desc');?></div>
                <div class="settings_content_main_value">
                    <input type="hidden" name="process" value="">
                    <input type="hidden" name="ip_type" value="black">
                    <input type="hidden" name="del_ip" value="">
                    <div class="settings_content_form_wrap">
                        <div class="form-group inline_clear">
                            <input type="text" name="black" class="form-control inline_left add_input" data-target-button="addBlackList" placeholder="<?=c2ms('settings_ph_ip');?>">
                            <button id="addBlackList" class="btn btn-gray btn-sm add_button" data-control-type="black" type="button"><?=c2ms('add_record');?></button>
                            <p class="settiing_tip"><?=c2ms('settings_ip_example');?></p>
                        </div>
                        <div class="tagit_wrapper">
                            <ul id="blackListIps" class="set_tagit_ip" data-control-type="black">
                                <?php foreach($blackList as $ip):?>
                                    <li><?php echo $ip?></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="settings_content_main_wrap">
                <div class="settings_content_main_title"><?=c2ms('ip_control_whitelist');?></div>
                <div class="sub_text"><?=c2ms('ip_control_whitelist_desc');?></div>
                <div class="settings_content_main_value">
                    <input type="hidden" name="process" value="">
                    <input type="hidden" name="ip_type" value="white">
                    <input type="hidden" name="del_ip" value="">
                    <div class="settings_content_form_wrap">
                        <div class="form-group inline_clear">
                            <input type="text" name="white" class="form-control inline_left add_input" data-target-button="addWhiteList" placeholder="<?=c2ms('settings_ph_ip');?>">
                            <button id="addWhiteList" class="btn btn-gray btn-sm add_button" data-control-type="white" type="button"><?=c2ms('add_record');?></button>
                            <p class="settiing_tip"><?=c2ms('settings_ip_example');?></p>
                        </div>
                        <div class="tagit_wrapper">
                            <ul id="whiteListIps" class="set_tagit_ip tagit_white" data-control-type="white">
                                <?php foreach($whiteList as $ip):?>
                                    <li><?php echo $ip?></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>