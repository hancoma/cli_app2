<?php
if(!isset($selected_domain_info) || empty($selected_domain_info)){
    redirect('/mysites');
}
?>
<script>
    // TODO 정리 필요함
    var selected_domain_status = '<?=$selected_domain_info['status']?>';

    $(function(){
        //arrow (메뉴가 8개보다 적으면 화살표를 보여주지 않는다.)
        if($("#settings_nav").find(".btn-app").length > 8) {
            $(".btn-arrow").css("visibility", "visible");
            $("#settings_nav_group").addClass("arrow_active");
        }
    });
</script>

<div id="settings_nav_group_wrap">
    <button class="btn btn-app btn-arrow inline_left" id="btn_arrow_l">
        <img src='/assets/img/settings/l_arrow_01.png'>
        <!--<i class="fa fa-angle-left"></i>-->
    </button>

    <div id="settings_nav_group">
        <div id="settings_nav" class="btn-group btn-group-justified" role="group">
            <a href="/settings/overview/<?=$selected_domain_info['domain_idx']?>" class="btn btn-app btn-hover" data-url="overview">
                <img src='/assets/img/settings/Origin_IP_01.png'>
                <!--<i class="fa fa-sliders fa-2x"></i>-->
                <?=c2ms('overview');?>
            </a>
            <?php
            if(count($selected_domain_info['cb_nameserver']) > 0){?>
                <a href="/settings/dns/<?=$selected_domain_info['domain_idx']?>" class="btn btn-app btn-hover" data-url="dns">
                    <img src='/assets/img/settings/DNS_01.png'>
                    <!--<i class="fa fa-server fa-2x"></i>-->
                    <?=c2ms('dns');?>
                </a>
                <?php
            }?>
            <a href="/settings/ip_control/<?=$selected_domain_info['domain_idx']?>" class="btn btn-app btn-hover" data-url="ip_control">
                <img src='/assets/img/settings/IP_Control_01.png'>
                <!--<i class="fa fa-user fa-2x" aria-hidden="true"></i>-->
                <?=c2ms('ip_control');?>
            </a>
            <a href="/settings/country/<?=$selected_domain_info['domain_idx']?>" class="btn btn-app btn-hover" data-url="country">
                <img src='/assets/img/settings/Country_01.png'>
                <!--<i class="fa fa-map-o fa-2x"></i>-->
                <?=c2ms('country');?>
            </a>
            <a href="/settings/excluded_url/<?=$selected_domain_info['domain_idx']?>" class="btn btn-app btn-hover" data-url="excluded_url">
                <img src='/assets/img/settings/Extra_URL_01.png'>
                <!--<i class="fa fa-external-link fa-2x"></i>-->
                <?=c2ms('excluded_url');?>
            </a>
            <a href="/settings/ddos/<?=$selected_domain_info['domain_idx']?>" class="btn btn-app btn-hover" data-url="ddos">
                <img src='/assets/img/settings/DDoS_01.png'>
                <!--<i class="fa fa-bomb fa-2x"></i>-->
                <?=c2ms('ddos');?>
            </a>
            <!-- <a href="/settings/web_seal/<?=$selected_domain_info['domain_idx']?>" class="btn btn-app btn-hover" data-url="web_seal">
                <img src='/assets/img/settings/DDoS_01.png'>
                <i class="fa fa-bomb fa-2x"></i>
                Web Seal
            </a> -->
            <a href="/settings/ssl_redirect/<?=$selected_domain_info['domain_idx']?>" class="btn btn-app btn-hover" data-url="ssl_redirect">
                <img src='/assets/img/settings/SSL_01.png'>
                <!--<i class="fa fa-lock fa-2x"></i>-->
                <?=c2ms('ssl_redirect');?>
            </a>
            <a href="/settings/delete/<?=$selected_domain_info['domain_idx']?>" class="btn btn-app btn-hover" data-url="delete">
                <img src='/assets/img/settings/Delete_01.png'>
                <!--<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>-->
                <div class="btn-app-text"><?=c2ms('delete');?></div>
            </a>
        </div>
    </div>
    <button class="btn btn-app btn-arrow inline_right" id="btn_arrow_r">
        <img src='/assets/img/settings/r_arrow_01.png'>
        <!--<i class="fa fa-angle-right"></i>-->
    </button>
</div>