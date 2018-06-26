<section id="settings" class="content">
    <?php require_once(__DIR__ . '/settings_nav.php'); ?>
    <div class="settings_content" id="settings_delete">
        <div class="title_text text_grey"><?=c2ms('delete');?></div>
        <div class="settings_content_main">
            <div class="settings_content_main_wrap">
                <h4><?=c2ms('delete_question_1');?></h4>
                <p>
                <ul style="list-style:disc;font-size:1rem;padding-left:18px;">
                    <li><?=c2ms('delete_answer_1');?></li>
                </ul>
                <br/>
                </p>
                <h4><?=c2ms('delete_question_2');?></h4>
                <p>
                <ul style="list-style:disc;font-size:1rem; line-height:2rem;padding-left:18px;">
                    <li><?=c2ms('delete_answer_2_1');?></li>
                    <li><?=c2ms('delete_answer_2_2');?></li>
                    <li><?=c2ms('delete_answer_2_3');?></li>
                    <li><?=c2ms('delete_answer_2_4');?></li>
                </ul>
                </p>
                <div class="settings_content_main_value">
                    <div class="inline_right" style="padding-top: 30px;">
                        <?php if($selected_domain_info['delete_request_date'] > 943920000):?>
                            <input id="btn_delete" type="button" class="btn btn-sm btn-gray btn_delete_site button_deleted" value="<?=c2ms('delete_btn');?>" disabled="disabled">
                        <?php else:?>
                            <input id="btn_delete" type="button" class="btn btn-sm btn-gray btn_delete_site <?php echo (get_cookie('lang') == 'ko') ? 'lang_ko' : ''?>" value="<?=c2ms('delete_btn');?>">
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
