<section id="settings" class="content">
    <?php require_once(__DIR__ . '/settings_nav.php'); ?>

    <div class="settings_content" id="settings_overview">
        <div class="title_text text_grey"><?=c2ms('overview');?></div>
        <div class="settings_content_main">
            <div class="settings_content_main_table">
                <div class="settings_content_main_title">
                    <span><?php echo c2ms('title_webserver_change');?></span>
                    <select name="webserver_change" id="formChangeSelect" class="form-control form_change_select">
                        <option value="changeIP"<?=(!empty($selected_domain_info['ip_cname']))?'':' selected="selected"';?>><?php echo c2ms('option_ip');?></option>
                        <option value="changeCNAME"<?=(!empty($selected_domain_info['ip_cname']))?' selected="selected"':'';?>><?php echo c2ms('option_cname');?></option>
                    </select>
                </div>
                <div id="changeIP" class="settings_content_main_value ip_form"<?=(!empty($selected_domain_info['ip_cname']))?' style="display: none"':'';?>>
                    <div class="inline_right">
                        <ul id="ipList" class="ip_list">
                            <?php if(count($webServerIpMulti) > 1):?>
                                <?php foreach($webServerIpMulti as $v):?>
                                    <li class="ip_item">
                                        <input type="text" class="ip_input" value="<?php echo $v?>">
                                        <button type="button" class="ip_remove_button">X</button>
                                    </li>
                                <?php endforeach?>
                            <?php elseif($webServerIp != ''):?>
                                <li class="ip_item">
                                    <input type="text" class="ip_input e" value="<?php echo $webServerIp?>">
                                    <button type="button" class="ip_remove_button">X</button>
                                </li>
                            <?php else:?>
                                <li class="ip_item">
                                    <input type="text" class="ip_input d" value="">
                                    <button type="button" class="ip_remove_button">X</button>
                                </li>
                            <?php endif?>
                        </ul>
                        <div class="ip_button_wrap">
                            <button id="itemAddButton" class="btn btn-gray btn-sm inline_left ip_button" type="button"><?php echo c2ms('button_add');?></button>
                            <button id="ipSaveButton" class="btn btn-gray btn-sm inline_right ip_button" type="button"><?php echo c2ms('button_save');?></button>
                            <div class="inline_clear"></div>
                        </div>
                    </div>
                </div>
                <div id="changeCNAME" class="settings_content_main_value"<?=(!empty($selected_domain_info['ip_cname']))?'':' style="display: none"';?>>
                    <div class="cnames">
                        <input type="text" name="cname" class="form-control facing_cname" value="<?php echo $selected_domain_info['ip_cname'];?>">
                    </div>
                    <div class="inline_clear edit_button">
                        <button id="setCname" class="btn btn-gray btn-sm cname_save" type="button"><?php echo c2ms('button_save');?></button>
                    </div>
                </div>
            </div>
            <div class="settings_content_main_table">
                <div class="settings_content_main_title"><?=c2ms('overview_content_title_2');?></div>
                <div class="settings_content_main_value">
                    <input type="checkbox" id="bypassChange" class="btn_switch"<?php if($selected_domain_info['status'] == 'Bypass'):?> checked="checked"<?php endif?>>
                </div>
                <div class="settings_content_description">
                    <p><?=c2ms('overview_bypass_mode_desc');?></p>
                </div>
            </div>
            <div class="settings_content_main">
                <div class="settings_content_main_table">
                    <div class="settings_content_main_title"><?=c2ms('overview_content_title_3');?></div>
                    <div class="settings_content_main_value">
                        <div class="inline_right">
                            <select name="web_seal" id="webSealSelect" class="form-control">
                                <option value="none"<?php if($selected_domain_info['web_seal'] == 'none'):?> selected="selected"<?php endif?>><?=c2ms('none');?></option>
                                <option value="left"<?php if($selected_domain_info['web_seal'] == 'left'):?> selected="selected"<?php endif?>><?=c2ms('bottom_left');?></option>
                                <option value="right"<?php if($selected_domain_info['web_seal'] == 'right'):?> selected="selected"<?php endif?>><?=c2ms('bottom_right');?></option>
                            </select>
                        </div>
                    </div>
                    <div class="settings_content_description">
                        <p><?=c2ms('overview_web_seal_desc');?></p>
                    </div>
                </div>
            </div>
            <div class="settings_content_main_table">
                <div class="overview_dns_title"><?=c2ms('overview_content_title_4');?></div>
                <div class="overview_dns_value">
                    <table class="table table-bordered overview_dns_table">
                        <?php if($selected_domain_info['registration_method'] === 'Nameserver'):?>
                            <tr>
                                <td class="data_subdomain">@</td>
                                <td>NS</td>
                                <td class="data_dns">
                                    <ul class="nameserver_list">
                                        <?php foreach($selected_domain_info['cb_nameserver'] as $v):?>
                                            <li><?php echo $v?></li>
                                        <?php endforeach?>
                                    </ul>
                                </td>
                            </tr>
                        <?php else:?>
                            <?php if(array_key_exists('root_ip', $selected_domain_info['cb_dns'])):?>
                                <tr>
                                    <td class="data_subdomain">@</td>
                                    <td>A</td>
                                    <td class="data_dns"><?php echo $selected_domain_info['cb_dns']['root_ip'];?></td>
                                </tr>
                                <tr>
                                    <td class="data_subdomain">www</td>
                                    <td>CNAME</td>
                                    <td class="data_dns"><?php echo $selected_domain_info['cb_dns']['sub_cname'];?></td>
                                </tr>
                            <?php else:?>
                                <tr>
                                    <td class="data_subdomain"><?php echo str_replace('.'.$nakedDomain, '', $selected_domain_info['domain']);?></td>
                                    <td>CNAME</td>
                                    <td class="data_dns"><?php echo $selected_domain_info['cb_dns']['sub_cname'];?></td>
                                </tr>
                            <?php endif?>
                        <?php endif?>
                    </table>
                </div>
                <div class="settings_content_description overview_dns_text">
                    <p><?=($selected_domain_info['registration_method'] === 'Nameserver') ? c2ms('overview_ns_description') : c2ms('overview_record_description')?></p>
                </div>
            </div>
        </div>
    </div>
</section>