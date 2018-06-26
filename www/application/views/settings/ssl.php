<section id="settings" class="content">
    <?php require_once(__DIR__ . '/settings_nav.php'); ?>

    <div class="settings_content" id="settings_ssl_redirect">
        <div class="title_text text_grey"><?=c2ms('ssl_redirect');?></div>
        <div class="settings_content_main">
            <div class="settings_content_main_table">
                <div class="settings_content_main_title">
                    <?=c2ms('ssl_content_title_1');?>
                    <button id="sslHelpButton" class="btn btn-linegray btn-xs" style="margin-top:-3px; margin-left:5px;"><?=c2ms('ssl_see_btn');?></button>
                </div>
                <div class="settings_content_main_value">
                    <div class="inline_right">
                        <select id="sslSelect" name="ssl" class="form-control">
                            <option value="none"<?php if($selected_domain_info['ssl_mode'] == 'None'):?> selected="selected"<?php endif?>><?=c2ms('ssl_mode_1');?></option>
                            <option value="basic"<?php if($selected_domain_info['ssl_mode'] == 'Basic'):?> selected="selected"<?php endif?>><?=c2ms('ssl_mode_2');?> <?=($selected_domain_info["ssl_status"] === "N") ? c2ms('ssl_mode_un') : ""; // SSL이 준비 되지 않은 경우에 (인증 전) 태그를 붙임?></option>
                            <option value="full"<?php if($selected_domain_info['ssl_mode'] == 'Full'):?> selected="selected"<?php endif?>><?=c2ms('ssl_mode_3');?> <?=($selected_domain_info["ssl_status"] === "N") ? c2ms('ssl_mode_un') : ""; // SSL이 준비 되지 않은 경우에 (인증 전) 태그를 붙임?>
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="settings_content_main_table">
                <div class="settings_content_main_title"><?=c2ms('ssl_content_title_2');?></div>
                <div class="settings_content_main_value">
                    <div class="inline_right">
                        <input type="checkbox" class="btn_switch btn_switch_https" id="redirectionHttps"<?php if($selected_domain_info['redirect_https'] == 'On'):?> checked="checked"<?php endif?>>
                    </div>
                </div>
                <div class="settings_content_description">
                    <p><?=c2ms('ssl_redirection_https_desc');?></p>
                </div>
            </div>
        </div>
        <?php
        foreach ($user_sites as $root => $root_value) {
            if ($root == $selected_domain_info['domain']) {
        ?>
            <div class="settings_content_main">
                <div class="settings_content_main_table">
                    <div class="settings_content_main_title"><?= c2ms('ssl_content_title_3'); ?></div>
                    <div class="inline_right">
                        <input type="checkbox" class="btn_switch btn_switch_www"
                               id="redirectionWww"<?php if ($selected_domain_info['redirect_www'] == 'On'): ?> checked="checked"<?php endif ?>>
                    </div>
                    <div class="settings_content_description">
                        <p><?= str_replace('test.com', $selected_domain_info['domain'] ,str_replace('www.test.com', 'www.'.$selected_domain_info['domain'], c2ms('ssl_redirection_www_desc'))); ?></p>
                    </div>
                </div>
            </div>
        <?
            }
        }
        ?>

    </div>
    <div id="helpModal" class="modal fade" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel"><?php echo c2ms('C_sslHelpTitle');?></h4>
                </div>
                <div class="modal-body">
                    <p><?php echo c2ms('C_sslHelp');?></p>
                    <img src="/assets/img/settings/ssl_help.png" width="400px" height="462px" alt="SSL Guide Image" class="ssl_guide_image">
                </div>
            </div>
        </div>
    </div>
</section>