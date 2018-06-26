<section id="settings" class="content">
    <?php require_once(__DIR__ . '/settings_nav.php'); ?>
    <div class="settings_content" id="set_extra_url">
        <div class="title_text text_grey"><?=c2ms('excluded_url');?></div>
        <div class="settings_content_main">
            <div class="settings_content_main_wrap">
                <p><?=c2ms('excluded_url_description');?></p>
                <div class="settings_content_main_value">
                    <input type="hidden" name="process" value="">
                    <input type="hidden" name="ip_type" value="black">
                    <input type="hidden" name="del_ip" value="">
                    <div class="settings_content_form_wrap">
                        <div class="form-group inline_clear">
                            <input type="text" name="url" class="form-control inline_left add_input" placeholder="<?=c2ms('settings_ph_excluded_url');?>">
                            <button class="btn btn-gray btn-sm add_button"><?=c2ms('add_url');?></button>
                        </div>
                        <div class="tagit_wrapper">
                            <ul id="extraUrlList" class="set_url_tagit">
                                <?php foreach($extraUrlList as $url):?>
                                    <li><?php echo htmlspecialchars($url);?></li>
                                <?php endforeach;?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>