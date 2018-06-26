<section id="settings" class="content">
    <?php require_once(__DIR__ . '/settings_nav.php'); ?>
    <div class="settings_content" id="settings_ddos">
        <div class="title_text text_grey"><?=c2ms('ddos');?></div>
        <div class="settings_content_main">
            <div class="settings_content_main_table">
                <div class="settings_content_main_title vertical_top ddos_title"><?php echo c2ms('ddos_content_title_1');?></div>
                <div class="settings_content_main_value ddos_value">
                    <ul class="control_list inline_right">
                        <li>
                            <label for="httpConnection" class="control_label"><?php echo c2ms('ddos_limit_type_1');?></label>
                            <select id="httpConnection" class="form-control control_limit inline_right" data-limit-target="http_limit_conn">
                                <option value="20"<?php if($selected_domain_info['http_limit_conn'] == '20'):?> selected="selected"<?php endif?>>20</option>
                                <option value="40"<?php if($selected_domain_info['http_limit_conn'] == '40'):?> selected="selected"<?php endif?>>40</option>
                                <option value="80"<?php if($selected_domain_info['http_limit_conn'] == '80'):?> selected="selected"<?php endif?>>80</option>
                                <option value="160"<?php if($selected_domain_info['http_limit_conn'] == '160'):?> selected="selected"<?php endif?>>160</option>
                                <option value="320"<?php if($selected_domain_info['http_limit_conn'] == '320'):?> selected="selected"<?php endif?>>320</option>
                            </select>
                            <div class="inline_clear"></div>
                        </li>
                        <li>
                            <label for="httpConnection" class="control_label"><?php echo c2ms('ddos_limit_type_2');?></label>
                            <select id="httpRequest" class="form-control control_limit inline_right" data-limit-target="http_limit_req">
                                <option value="400"<?php if($selected_domain_info['http_limit_req'] == '400'):?> selected="selected"<?php endif?>>400</option>
                                <option value="800"<?php if($selected_domain_info['http_limit_req'] == '800'):?> selected="selected"<?php endif?>>800</option>
                                <option value="1600"<?php if($selected_domain_info['http_limit_req'] == '1600'):?> selected="selected"<?php endif?>>1600</option>
                                <option value="3200"<?php if($selected_domain_info['http_limit_req'] == '3200'):?> selected="selected"<?php endif?>>3200</option>
                                <option value="6400"<?php if($selected_domain_info['http_limit_req'] == '6400'):?> selected="selected"<?php endif?>>6400</option>
                            </select>
                            <div class="inline_clear"></div>
                        </li>
                    </ul>
                </div>
                <div class="settings_content_description ddos_text">
                    <p><?=c2ms('ddos_http_description');?></p>
                </div>
            </div>
        </div>
        <div class="settings_content_main">
            <div class="settings_content_main_table">
                <div class="settings_content_main_title vertical_top ddos_title"><?php echo c2ms('ddos_content_title_2');?></div>
                <div class="settings_content_main_value ddos_value">
                    <ul class="control_list inline_right">
                        <li>
                            <label for="httpConnection" class="control_label"><?php echo c2ms('ddos_limit_type_1');?></label>
                            <select id="httpsConnection" class="form-control control_limit inline_right" data-limit-target="https_limit_conn">
                                <option value="200"<?php if($selected_domain_info['https_limit_conn'] == '200'):?> selected="selected"<?php endif?>>200</option>
                                <option value="400"<?php if($selected_domain_info['https_limit_conn'] == '400'):?> selected="selected"<?php endif?>>400</option>
                                <option value="800"<?php if($selected_domain_info['https_limit_conn'] == '800'):?> selected="selected"<?php endif?>>800</option>
                                <option value="1600"<?php if($selected_domain_info['https_limit_conn'] == '1600'):?> selected="selected"<?php endif?>>1600</option>
                                <option value="3200"<?php if($selected_domain_info['https_limit_conn'] == '3200'):?> selected="selected"<?php endif?>>3200</option>
                            </select>
                            <div class="inline_clear"></div>
                        </li>
                        <li>
                            <label for="httpConnection" class="control_label"><?php echo c2ms('ddos_limit_type_2');?></label>
                            <select id="httpsRequest" class="form-control control_limit inline_right" data-limit-target="https_limit_req">
                                <option value="600"<?php if($selected_domain_info['https_limit_req'] == '600'):?> selected="selected"<?php endif?>>600</option>
                                <option value="1200"<?php if($selected_domain_info['https_limit_req'] == '1200'):?> selected="selected"<?php endif?>>1200</option>
                                <option value="2400"<?php if($selected_domain_info['https_limit_req'] == '2400'):?> selected="selected"<?php endif?>>2400</option>
                                <option value="4800"<?php if($selected_domain_info['https_limit_req'] == '4800'):?> selected="selected"<?php endif?>>4800</option>
                                <option value="9600"<?php if($selected_domain_info['https_limit_req'] == '9600'):?> selected="selected"<?php endif?>>9600</option>
                            </select>
                            <div class="inline_clear"></div>
                        </li>
                    </ul>
                </div>
                <div class="settings_content_description ddos_text">
                    <p><?=c2ms('ddos_https_description');?></p>
                </div>
            </div>
        </div>
    </div>
</section>