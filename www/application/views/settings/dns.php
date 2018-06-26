<section id="settings" class="content dns_modal">
    <?php require_once(__DIR__ . '/settings_nav.php'); ?>
    <div class="settings_content" id="settings_dns">
        <div class="title_text text_grey"><?=c2ms('dns');?></div>
        <div id="contentDNS" class="settings_content_main">
            <div id="settings_dns_table_wrap">
                <table id="recordTable" class="record_table">
                    <caption>DNS Record Table</caption>
                    <colgroup>
                        <col class="column_type">
                        <col class="column_name">
                        <col class="column_value">
                        <col class="column_status">
                        <col class="column_delete">
                    </colgroup>
                    <thead>
                        <tr class="header_row">
                            <th><?php echo c2ms('column_type');?></th>
                            <th><?php echo c2ms('column_name');?></th>
                            <th><?php echo c2ms('column_value');?></th>
                            <th><?php echo c2ms('column_status');?></th>
                            <th><?php echo c2ms('column_options');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dns_records as $record):?>
                            <tr id="record<?php echo $record['record_idx'];?>" class="record_row record_data" data-record-id="<?php echo $record['record_idx'];?>">
                                <td><?php echo $record['type'];?></td>
                                <td class="data_host"><?php echo $record['host'];?></td>
                                <td class="data_value">
                                    <div class="value_point">
                                        <ul id="valueList" class="value_list">
                                            <?php if($record['type'] == 'A' || $record['type'] == 'CNAME' ):?>
                                                <li class="value_item point_label"><?php echo c2ms('label_points_to');?></li>
                                            <?php elseif($record['type'] == 'MX'):?>
                                                <li class="value_item point_value"><sapn class="point_label"><?php echo c2ms('label_priority');?></sapn> <?php echo $record['pri'];?></li>
                                                <li class="value_item point_label"><?php echo c2ms('label_points_to');?></li>
                                            <?php elseif($record['type'] == 'SRV'):?>
                                                <li class="value_item point_value"><sapn class="point_label"><?php echo c2ms('label_priority');?></sapn> <?php echo $record['pri'];?></li>
                                                <li class="value_item point_value"><sapn class="point_label"><?php echo c2ms('label_weight');?></sapn> <?php echo $record['weight'];?></li>
                                                <li class="value_item point_value"><sapn class="point_label"><?php echo c2ms('label_port');?></sapn> <?php echo $record['port'];?></li>
                                                <li class="value_item point_label"><?php echo c2ms('label_target');?></li>
                                            <?php endif?>
                                            <li class="value_item"><?php echo $record['target'];?></li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    <?php if($record['using_type'] != 'N'):?>
                                        <?php if($record['status'] == 'Protected'):?>
                                            <img src="/assets/img/header/protected.png" alt="protected">
                                        <?php else:?>
                                            <img src="/assets/img/header/bypass.png" alt="bypass">
                                        <?php endif ?>
                                    <?php elseif($record['using_type'] == 'N' && ($record['type'] == 'A' || $record['type'] == 'CNAME')):?>
                                        <a href="/addsite/add/<?php echo $record['host'];?>" target="_self" class="btn btn-linegray btn-xs"><img src="/assets/img/settings/dns_addcb.png" alt="addcb"></a>
                                    <?php endif ?>
                                </td>
                                <!--<td><button class="btn btn-linegray btn-xs delete_button"<?php /*echo $record['using_type'] != 'N' ? 'data-using="true" data-domain-id="' . $record['domain_idx'] . '"' : '';*/?> data-record-id="<?php /*echo $record['record_idx'];*/?>"><img src="/assets/img/settings/DNS_Delete_01.png" class="delete_image" alt="delete"></button></td>-->
                                <td>
                                <?php if($record['using_type'] == 'N'): ?>
                                    <button class="btn btn-linegray btn-xs delete_button" data-record-id="<?php echo $record['record_idx'];?>"><img src="/assets/img/settings/DNS_Delete_01.png" class="delete_image" alt="delete"></button>
                                <?php endif ?>
                                </td>
                            </tr>
                        <?php endforeach?>
                        <tr class="record_row">
                            <td>
                                <select name="type" id="addType" class="form-control">
                                    <option value="A" selected="selected">A</option>
                                    <option value="TXT">TXT</option>
                                    <option value="MX">MX</option>
                                    <option value="CNAME">CNAME</option>
                                    <option value="SRV">SRV</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="name" id="addName" class="form-control value_input" placeholder="ex: sample.<?php echo $selected_domain_info['domain'];?>">
                            </td>
                            <td colspan="2">
                                <input type="text" name="pri" id="addPriority" class="form-control value_input" placeholder="<?php echo c2ms('placeholder_priority');?>" style="display: none">
                                <input type="text" name="weight" id="addWeight" class="form-control value_input" placeholder="<?php echo c2ms('placeholder_weight');?>" style="display: none">
                                <input type="text" name="port" id="addPort" class="form-control value_input" placeholder="<?php echo c2ms('placeholder_port');?>" style="display: none">
                                <input type="text" name="target" id="addValue" class="form-control value_input" placeholder="<?php echo c2ms('placeholder_a');?>">
                            </td>
                            <td>
                                <button type="button" id="addRecordButton" class="btn btn-gray btn-xs" disabled="disabled"><?php echo c2ms('button_add_record');?></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="modals">
        <div id="redirectModal" class="modal fade" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div id="redirectModalBody" class="modal-body">
                        <div class="close_button">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        </div>
                        <div class="modal_description"><?php echo c2ms('modal_title_redirect'); ?></div>
                        <ul class="modal_button_list">
                            <li class="item_button"><a id="redirectButtonLink" class="btn btn-gray btn-xs" href="/settings/delete/<?php echo $selected_domain_info['domain_idx'] ?>"><?php echo c2ms('button_yes'); ?></a>
                            </li class="item_button">
                            <li class="item_button">
                                <button class="btn btn-gray btn-xs" type="button" data-dismiss="modal"><?php echo c2ms('button_no'); ?></button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="deleteModal" class="modal fade" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div id="deleteModalBody" class="modal-body">
                        <div class="close_button">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        </div>
                        <div class="modal_description"><?php echo c2ms('modal_title_delete'); ?></div>
                        <ul class="modal_button_list">
                            <li class="item_button">
                                <button id="deleteRecord" class="btn btn-gray btn-xs delete_record" type="button"><?php echo c2ms('button_yes'); ?></button>
                            </li>
                            <li class="item_button">
                                <button class="btn btn-gray btn-xs" type="button" data-dismiss="modal"><?php echo c2ms('button_no'); ?></button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>