<nav id="header_top">
    <div class="content_center">
        <div id="header_top_left" class="inline_left">
        <?php
        if(PARTNER_CONSOLE == 'no') { //파트너 콘솔 : 로고
            ?>
            <a href="<?php echo base_url();?>"><img src="/assets/img/logo.png"></a>
        <?php } else {
            if(file_exists('./assets/partner/'.c2ms('partner_console_PARTNER_IDX').'/logo.png')) {
                $logo = '/assets/partner/'.c2ms('partner_console_PARTNER_IDX').'/logo.png';
            } else {
                $logo = '/assets/img/logo.png';
            }
            ?>
            <a href="<?php echo base_url();?>"><img src="<?=$logo?>" style="max-width: 350px; max-height: 42px;"></a>
        <?php }?>
        </div>
        <div id="header_top_right" class="inline_right">
            <ul>
                <li class="header">
                    <a href="/mysites" class="a_gray btn-hover">
                        <img src="/assets/img/header/mysite_<?php echo $isActive['mysites']?'02':'01';?>.png" class="header_img<?php echo $isActive['mysites']?' not_hover':'';?>">
                        <span class="header_top_text<?php echo $isActive['mysites']?' active':'';?>"><?=c2ms('my_sites')?></span>
                    </a>
                </li>
                <?php
                //파트너 콘솔 사이트 등록 불가일 때 노출 안 함, 파트너 콘솔이라도 관리자 강제로그인은 가능
                if(PARTNER_CONSOLE == 'no' || $this->session->userdata('manager') == true || c2ms('partner_console_ADD_WEBSITES') == 'yes') {
                ?>
                <li>
                    <a href="/addsite" class="a_gray btn-hover">
                        <img src="/assets/img/header/addsite_<?php echo $isActive['addsite']?'02':'01';?>.png" class="header_img<?php echo $isActive['addsite']?' not_hover':'';?>">
                        <span class="header_top_text<?php echo $isActive['addsite']?' active':'';?>"><?=c2ms('add_site')?></span>
                    </a>
                </li>
                <?php }?>
                <li>
                    <a href="https://cloudbric.zendesk.com/hc/en-us" class="a_gray btn-hover" target="_blank">
                        <img src="/assets/img/header/faq_01.png" class="header_img">
                        <span class="header_top_text"><?=c2ms('faq')?></span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle a_gray btn-hover" data-toggle="dropdown">
                        <img src="/assets/img/header/user_<?php echo $isActive['myaccount']?'02':'01';?>.png" class="header_img<?php echo $isActive['myaccount']?' not_hover':'';?>">
                        <span id="user_email" class="header_top_text<?php echo $isActive['myaccount']?' active':'';?>">
                            <?php echo $this->session->userdata('user_id')?>
                        </span>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <ul class="menu">
                                <!--<li>
                                <a href="/billing" class="a_gray btn-hover">
                                    <img src='/assets/img/header/billing_01.png'>
                                    Billing
                                </a>
                            </li>-->
                                <li>
                                    <a href="/myaccount" class="a_gray btn-hover<?php echo $isActive['myaccount']?' active':'';?>">
                                        <img src='/assets/img/header/my_account_<?php echo $isActive['myaccount']?'02':'01';?>.png'<?php echo $isActive['myaccount']?' class="not_hover"':'';?>>
                                        <?=c2ms('my_account')?>
                                    </a>
                                </li>
                                <li>
                                    <a href="/sign/out" class="a_gray btn-hover">
                                        <img src='/assets/img/header/sign_out_01.png'>
                                        <?=c2ms('sign_out')?>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div><!-- ./content_center -->
</nav><!-- ./header_top -->

<!-- header bottom -->
<nav id="header_bottom" class="inline_clear">
    <?php $bottomFirstSegment = $this->uri->segment(1);?>
    <?php //if((($bottomFirstSegment != 'addsite' || $bottomFirstSegment != 'myaccount' ) && !empty($selected_domain_info)) || $bottomFirstSegment == 'mysites'):?>
    <?php if($bottomFirstSegment == 'dashboard' || $bottomFirstSegment == 'logs' || $bottomFirstSegment == 'report' || $bottomFirstSegment == 'settings'):?>
    <div class="content_center">
        <ul id="header_bottom_left" class="nav inline_left" >
            <li class="dropdown">
                <?php
                $firstSegment = $this->uri->segment(1);
                $domainIndex = $this->uri->segment(3);
                ?>
                <?php if($firstSegment != 'mysites'):?>
                    <a id="header_mainsite" href="#" class="dropdown-toggle text_gray" data-toggle="dropdown"><?=$selected_domain_info['domain']?></a>
                    <ul class="dropdown-menu">
                        <li>
                            <ul class="menu">
                                <?php $i = 0;?>
                                <?php if($user_sites != NULL):?>
                                    <?php foreach($user_sites as $naked_domain_name => $child_domains):?>
                                        <?php foreach($child_domains as $domain_info):?>
                                            <?php
                                            $uri = explode('/', $this->input->server('REQUEST_URI'));
                                            $menu = ($uri[1] == 'logs' || $uri[1] == 'settings' || $uri[1] == 'report') ? '/'.$uri[1].'/'.$uri[2].'/'.$domain_info['domain_idx'] : '/dashboard/a/'.$domain_info['domain_idx'];

                                            $status = $domain_info['status'];

                                            switch($status) {
                                                case 'Protected':
                                                    $status_class = 'header_status_protected';
                                                    $status_text = c2ms('protected');
                                                    break;
                                                case 'Bypass':
                                                    $status_class = 'header_status_bypass';
                                                    $status_text = c2ms('bypass');
                                                    break;
                                                case 'Pending':
                                                    $status_class = 'header_status_pending';
                                                    $status_text = c2ms('pending');
                                                    break;
                                                case 'Deleting':
                                                    $status_class = 'header_status_deleting';
                                                    $delete_date = date('Y-m-d', $domain_info['delete_request_date']);
                                                    break;
                                                case 'Disconnected':
                                                    $status_class = 'header_status_disconnected';
                                                    $delete_date = date('Y-m-d', $domain_info['disconnect_date']);
                                                    break;
                                            }
                                            ?>

                                            <li class="group-border-left-color-1<?php echo $domain_info['domain_idx'] == $domainIndex ? ' active' : '';?>">
                                                <a href="<?php echo $menu?>" class="a_gray">
                                                    <div class="header_site_domain pull-left"><?php echo $domain_info['domain']?></div>
                                                    <div class="header_site_status inline_right">
                                                        <div class="header_site_status_img <?php echo $status_class;?>"></div>
                                                        <?php if($status == 'Disconnected' || $status == 'Deleting'): ?>
                                                            <div class="header_site_status_text"><?php echo $delete_date;?></div>
                                                        <?php else: ?>
                                                            <div class="header_site_status_text"><?php echo $status_text;?></div>
                                                        <?php endif ?>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php endforeach?>
                                        <li class="divider"></li>
                                    <?php endforeach?>
                                <?php else:?>
                                    <li>
                                        <a href="#" class="text_gray" style="cursor:Default; pointer-events: none; border-left:1px solid #7f94a0;"><?php echo c2ms("you_dont_have");?></a>
                                    </li>
                                <?php endif?>
                            </ul>
                        </li>
                    </ul>
                <?php else:?>
                    <a id="header_mainsite" href="#" class="dropdown-toggle text_gray" data-toggle="dropdown"><?=c2ms('select_your_website')?></a>
                <?php endif?>
            </li>
        </ul>
        <?php if(isset($selected_domain_info)):?>
            <ul id="header_bottom_right" class="inline_right">
                <li>
                    <a href="/dashboard/a/<?=$selected_domain_info['domain_idx']?>" class="<?=($this->uri->segment(1) === "dashboard")?"active":""?>"><?=c2ms('dashboard')?></a>
                </li>
                <li>
                    <a href="/logs/a/<?=$selected_domain_info['domain_idx']?>" class="<?=($this->uri->segment(1) === "logs")?"active":""?>"><?=c2ms('logs')?></a>
                </li>
                <li>
                    <a href="/report/view/<?=$selected_domain_info['domain_idx']?>" class="<?=($this->uri->segment(1) === "report")?"active":""?>"><?=c2ms('report');?></a>
                </li>
                <li>
                    <a href="/settings/overview/<?=$selected_domain_info['domain_idx']?>" class="<?=($this->uri->segment(1) === "settings")?"active":""?>"><?=c2ms('settings')?></a>
                </li>
            </ul>
        <?php endif?>
    </div><!-- ./content_center -->
        <?php endif?>
</nav>
