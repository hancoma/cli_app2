<div id="site-list-search-box">
    <div class="col-lg-3 pull-right">
        <input type="text" id="input-search-text" placeholder="<?=c2ms('search_for_your_website')?>" class="form-control"/>
    </div>
</div>
<section id="mysites" class="content">
    <?php if($mysite_info == null):?>
        <div class="panel-group add-website-panel">
            <div rold="tabpanel">
                <div class="panel panel-default">
                    <div class="panel-heading group-border-left-color-1">
                        <ul class="nav-justified">
                            <li></li>
                            <li class="add-website" id="addSite">
                                <img class="mysites_group_img" src="/assets/img/header/addsite_01.png">Add Website
                            </li>
                            <li></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php else:?>
        <?php $i = 0;?>
        <?php foreach($mysite_info as $naked_domain_name => $child_domains):?>
            <div id="domain_group<?php echo $i;?>" class="panel-group">
                <div rold="tabpanel">
                    <div class="panel panel-default">
                        <div class="panel-heading group-border-left-color-1" data-toggle="collapse" data-parent="#accordion" data-target="#domain_group_list<?php echo $i;?>">
                            <ul class="nav-justified">
                                <li><img class="mysites_group_img" src="/assets/img/mysite/group_02.png"></li>
                                <li class="domain-name"><?php echo $naked_domain_name;?></li>
                                <?php if(array_key_exists($naked_domain_name, $child_domains)):?>
                                    <?php if(array_key_exists('root_ip', $child_domains[$naked_domain_name]['cb_dns']) && $child_domains[$naked_domain_name]['registration_method'] == 'Nameserver'): ?>
                                        <li>
                                            <a class="pull-right" href="/settings/dns/<?php echo $child_domains[$naked_domain_name]['domain_idx'] ?>">
                                                <button class="btn btn-gray btn-xs dns_button">DNS</button>
                                            </a>
                                        </li>
                                    <?php endif ?>
                                <?php endif?>
                            </ul>
                        </div>
                    </div>
                    <div id="domain_group_list<?php echo $i;?>" class="collapse in">
                        <div class="panel-body">
                            <?php foreach($child_domains as $domain_info):?>
                                <?php
                                $domainIndex = $domain_info['domain_idx'];
                                $status = $domain_info['status'];
                                $traffic = $domain_info['traffic'];
                                $trafficFloat = round($traffic / 1073741824, 2);
                                $limitTraffic = $domain_info['limit_traffic'];
                                $limitTrafficFloat = round($limitTraffic / 1073741824, 2);
                                $trafficPercent = $limitTrafficFloat > 0 ? round(($traffic / $limitTraffic) * 100) : 100;
                                $forcedBypass = $domain_info['forced_bypass'];
                                $useCoupon = $domain_info['coupon_use'];
                                $sslStatus = $domain_info['ssl_status'];
                                $nsStatus = $domain_info['ns_status'];
                                $sslType = '';
                                $nsType = '';

                                if($domain_info['registration_method'] == 'Nameserver') {
                                    $nsType = 'NS';
                                } elseif($domain_info['registration_method'] == 'DNS records') {
                                    $nsType = 'A';
                                }

                                if($domain_info['ssl_type'] == 'Lets Encrypt HTTP Method') {
                                    $sslType = 'LH01';
                                } elseif($domain_info['ssl_type'] == 'Lets Encrypt DNS Method') {
                                    $sslType = 'LD01';
                                }

                                switch($status) {
                                    case 'Protected':
                                        $status_image = 'protected';
                                        $status_text = c2ms('protected');
                                        break;
                                    case 'Bypass':
                                        $status_image = 'bypass';
                                        $status_text = c2ms('bypass');
                                        break;
                                    case 'Pending':
                                        $status_image = 'sand_clock';
                                        $status_text = c2ms('pending');
                                        break;
                                    case 'Deleting':
                                        $status_image = 'deleting';
                                        $status_text = c2ms('deleting');
                                        $delete_date = date('Y-m-d', strtotime("+3 days", $domain_info['delete_request_date']));
                                        break;
                                    case 'Disconnected':
                                        $status_image = 'disconnected';
                                        $status_text = c2ms('disconnect');
                                        $delete_date = date('Y-m-d', strtotime("+7 days", $domain_info['disconnect_date']));
                                        break;
                                }
                                ?>
                                <ul class="nav-justified domain-group" data-domain-name="<?php echo $domain_info['domain'];?>">
                                    <li class="group-border-left-sub-color-1"><img src="/assets/img/mysite/group_03.png"></li>
                                    <li class="domain-name">
                                        <a href="/dashboard/a/<?php echo $domainIndex;?>" class="a_hovergray"><?php echo $domain_info['domain'];?></a>
                                        <?php if($useCoupon == 'Y'):?>
                                            <span class="label label-pill label-linegray">coupon</span>
                                        <?php endif;?>
                                    </li>
                                    <?php if($forcedBypass == 'ON'):?>
                                        <li class="domain-status-img"><img src="/assets/img/header/disconnected.png"></li>
                                    <?php else:?>
                                        <li class="domain-status-img"><img src="/assets/img/header/<?php echo $status_image;?>.png"></li>
                                    <?php endif?>
                                        <?php if($forcedBypass == 'ON'):?>
                                            <li class="domain-status-text domain-status-date domain-status-deleting"><?php echo c2ms('payment');?></li>
                                        <?php elseif($status == 'Deleting' || $status == 'Disconnected'):?>
                                            <li class="domain-status-text domain-status-date domain-status-<?php echo strtolower($status);?>" data-date="<?php echo $delete_date;?>">
                                                <?php echo $status_text;?>
                                            </li>
                                        <?php else:?>
                                            <li class="domain-status-text domain-status-<?php echo strtolower($status);?>">
                                                <?php echo $status_text;?>
                                            </li>
                                        <?php endif;?>
                                    <?php if($forcedBypass == 'ON'):?>
                                        <li class="domain-status-pending-info waiting_payment">
                                            <span class="pending-info-text"><?php echo c2ms('payment_message');?></span>
                                        </li>
                                    <?php else:?>
                                        <?php if($status == 'Pending'):?>
                                            <li class="domain-status-pending-info">
                                                <?php if($nsStatus == 'N'):?>
                                                    <?php if($sslStatus == 'Pending' && $sslType == 'LD01'):?>
                                                        <div class="pending-info-text">
                                                            <span class="pending-info-3"><?php echo c2ms('pending_txt_msg');?></span>
                                                            <a href="/addsite/manual/<?php echo $domainIndex;?>" class="btn_dnsinfo a_hoverblue"><?php echo c2ms('txt_info');?></a>
                                                        </div>
                                                    <?php else:?>
                                                        <div class="pending-info-text">
                                                            <span class="pending-info-1"><?php echo c2ms('pending_dns_msg');?></span>
                                                            <a href="/addsite/dns/<?php echo $domainIndex;?>" class="btn_dnsinfo a_hoverblue"><?php echo c2ms('dns_info');?></a>
                                                        </div>
                                                    <?php endif?>
                                                <?php else:?>
                                                    <div class="pending-info-text">
                                                        <span class="pending-info-2"><?php echo c2ms('pending_last_msg'); // modifying?></span>
                                                    </div>
                                                <?php endif?>
                                            </li>
                                        <?php else:?>
                                            <li>
                                                <div class="progress domain-traffic">
                                                    <div class="progress-bar" style="width: <?php echo $trafficPercent;?>%"></div>
                                                    <div class="progress-text">
                                                        <?php if($limitTraffic > 0):?>
                                                            <span><?php echo $trafficFloat;?>GB / <?php echo $limitTrafficFloat;?>GB</span>
                                                        <?php else:?>
                                                            <span><?php echo $trafficFloat;?>GB / <?php echo c2ms('traffic_unlimited');?></span>
                                                        <?php endif;?>
                                                        <div class="pull-right"></div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endif?>
                                    <li class="li-options-wrap go-to-settings">
                                        <a href="/settings/overview/<?php echo $domainIndex;?>" class="a_blue btn-hover"><img class="mysites_list_img" src="/assets/img/mysite/settings_01.png"></a>
                                    </li>
                                    <li class="li-options-wrap go-to-report"><a href="/report/view/<?php echo $domainIndex;?>" class="fa fa-file-text-o"></a></li>
                                    <?php endif?>
                                </ul>
                                <?php if ($domain_info['domain'] == $naked_domain_name) { ?>
                                    <ul class="nav-justified domain-group" data-domain-name="www.<?php echo $domain_info['domain'];?>">
                                        <li class="group-border-left-sub-color-1"><img src="/assets/img/mysite/group_03.png"></li>
                                        <li class="domain-name">
                                            <a href="/dashboard/a/<?php echo $domainIndex;?>" class="a_hovergray"><?php echo 'www.'.$domain_info['domain'];?></a>
                                        </li>
                                    </ul>
                                <?php }?>
                            <?php endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
            <?php $i++;?>
        <?php endforeach;?>
    <?php endif;?>
</section>