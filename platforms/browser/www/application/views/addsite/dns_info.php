<script>
    <?php // TODO 스크립트 파일로.. ?>
    $(function(){
        //progress bar
        var progress_width = ($(".progress").width() === 0)? 784 : $(".progress").width();
        //progress step width
        if($("#progress-step-manual").length === 0){ // LH01
            $(".progress-bar").animate({'width': progress_width * 0.75}, 1000);
        }else{ // LD01
            $(".progress-bar").animate({'width': progress_width * 0.81}, 1000);
        }
    });
</script>
<div id="addsite-wrap">
    <?php require_once(__DIR__.'/addsite_progress.php'); ?>

    <section id="addsite_dnsinfo" class="content">
        <div class="title_text text_blue"><?=c2ms('addsite_dns_title');?></div>
        <div class="sub_text text_gray">
            <?if(get_cookie('lang') == 'en'){?>
                <?=c2ms('addsite_dns_description_1');?><?=$domain?><?=c2ms('addsite_dns_description_2');?>
            <?}else if(get_cookie('lang') == 'ko'){?>
                <?=$domain?><?=c2ms('addsite_dns_description_1');?>
            <?}else if(get_cookie('lang') == 'ja'){?>
                <?=c2ms('addsite_dns_description_1');?><?=$domain?><?=c2ms('addsite_dns_description_2');?>
            <?}else{?>
                <?=c2ms('addsite_manual_description');?>
            <?}?>
            <a class="a_blue" href="https://cloudbric.zendesk.com/hc/en-us/articles/115001539583-A-beginner-s-guide-to-DNS" target="_blank" style='display: inline-block;'>
                <?=c2ms('addsite_dns_description2');?>
            </a>
            <!-- 고객님 웹사이트 DNS를 Cloudbric DNS로 변경해주세요!<br/>
            DNS 변경이 완료되면 Cloudbric이 고객님의 웹사이트를 안전하게 보호해드립니다.(<a href="http://<?=$host_name?>" class="a_lineblue" target="_black"><?=$host_name?></a>)
            <br/>
            -->
            <!--<a class="a_blue" href="https://www.youtube.com/watch?v=c7g5dSI6_4c" target="_blank" style='display: inline-block;'>
                <button id="btn_whatisns" type="button" class="btn btn-block btn-gray btn-sm">DNS Information Guide</button>
            </a>-->
        </div>

        <div id="addsite_reg_info" class="nav-tabs-custom">

            <ul class="nav nav-tabs">
                <? if(!$using_nameserver|| $is_subdomain){ ?>
                <? }else{ ?>
                    <li class="reg_info_title active">
                        <a href="#addsite_reg_ns" data-toggle="tab" aria-expanded="true"><?=c2ms('tab_1');?></a>
                    </li>
                    <li class=""><a href="#addsite_reg_a" data-toggle="tab" aria-expanded="false"><?=c2ms('tab_2');?></a></li>
                <? } ?>
            </ul>
            <div class="tab-content">
                <? if(!$using_nameserver || $is_subdomain){ ?>
                    <div class="tab-pane active" id="addsite_reg_a">
                        <div class="reg_info">
                            <div class="reg_info_list">
                                <ul class="reg_info_list_current">
                                    <li><?=c2ms('info_current_title_2');?></li>
                                    <li class="reg_info_table_li">
                                        <table class="reg_info_table">
                                            <?php if(!$is_subdomain):?>
                                                <tr>
                                                    <td>A</td>
                                                    <td>@</td>
                                                    <td><?=$origin_ip?></td>
                                                </tr>
                                            <?php endif?>
                                            <tr>
                                                <td>A</td>
                                                <td><?=$domain?></td>
                                                <td><?=$origin_ip?></td>
                                            </tr>
                                        </table>
                                    </li>
                                </ul>
                                <div class="reg_info_list_arrow">
                                    <i class="fa fa-angle-right fa-2x"></i>
                                </div>
                                <ul class="reg_info_list_change">
                                    <li><?=c2ms('info_new_title_2');?></li>
                                    <li class="reg_info_table_li">
                                        <table class="reg_info_table">
                                            <?php if(!$is_subdomain):?>
                                                <tr>
                                                    <td>A</td>
                                                    <td>@</td>
                                                    <td><?=$redirect_server_ip?></td>
                                                </tr>
                                            <?php endif?>
                                            <tr>
                                                <td>CNAME</td>
                                                <td><?=$domain?></td>
                                                <td><?=$cname?></td>
                                            </tr>
                                        </table>
                                    </li>
                                </ul>
                            </div>
                            <ul class="reg_info_comment">
                                <li>
                                    <i class="fa fa-circle"></i>
                                    <?=c2ms('addsite_dns_comment_4');?>
                                </li>
                                <? if(!$is_subdomain){ ?>
                                <li>
                                    <i class="fa fa-circle"></i>
                                    <?=c2ms('addsite_dns_comment_5');?>
                                </li>
                                <? } ?>
                                <li>
                                    <i class="fa fa-circle"></i>
                                    <?=c2ms('addsite_dns_comment_6');?>
                                </li>
                            </ul>
                        </div> <!--./reg_info-->
                    </div><!--tab pane-->
                <? }else{ ?>
                    <div class="tab-pane active" id="addsite_reg_ns">
                        <div class="reg_info">
                            <div class="reg_info_list">
                                <ul class="reg_info_list_current inline_left">
                                    <li><?=c2ms('info_current_title_1');?></li>
                                    <li class="reg_info_table_li">
                                        <table class="reg_info_table">
                                            <?php if(isset($origin_ns)):?>
                                                <?php foreach($origin_ns as $i => $ns):?>
                                                    <tr>
                                                        <td><?php echo $ns?></td>
                                                    </tr>
                                                <?php endforeach?>
                                            <?php else:?>
                                                <tr>
                                                    <td>-</td>
                                                </tr>
                                            <?php endif ?>
                                        </table>
                                    </li>
                                </ul>
                                <div class="reg_info_list_arrow inline_left">
                                    <i class="fa fa-angle-right fa-2x"></i>
                                </div>
                                <ul class="reg_info_list_change inline_right">
                                    <li><?=c2ms('info_new_title_1');?></li>
                                    <li class="reg_info_table_li">
                                        <table class="reg_info_table">
                                            <?php if(isset($ns_info)):?>
                                                <?php foreach($ns_info as $val):?>
                                                    <tr>
                                                        <td><?php echo $val?></td>
                                                    </tr>
                                                <?php endforeach?>
                                            <?php else:?>
                                                <tr>
                                                    <td>-</td>
                                                </tr>
                                            <?php endif ?>
                                        </table>
                                    </li>
                                </ul>
                            </div>
                            <ul class="reg_info_comment">
                                <li>
                                    <i class="fa fa-circle"></i>
                                    <?=c2ms('addsite_dns_comment_1');?>
                                </li>
                                <li>
                                    <i class="fa fa-circle"></i>
                                    <?=c2ms('addsite_dns_comment_2');?>
                                    <?php
                                    //root domain에 www 붙여주기.
                                    if(substr($host_name, 0, strpos($host_name, ".")) != "www")$host_name = "www.".$host_name;
                                    ?>
                                    <a class="a_hovergrey" href="/settings/dns/<?=$domain_idx?>" target="_blank" style='margin-left:5px;font-weight: bold;text-decoration: underline;'>
                                        <a class="a_hovergrey" href="/settings/dns/<?=$domain_idx?>" target="_blank" style='margin-left:5px;font-weight: bold;text-decoration: underline;'>
                                            <?=c2ms('add_dns_info');?>
                                        </a>
                                </li>
                                <li>
                                    <i class="fa fa-circle"></i>
                                    <?=c2ms('addsite_dns_comment_3');?>
                                </li>
                            </ul>
                        </div>
                    </div><!--tab pane-->

                    <div class="tab-pane" id="addsite_reg_a">
                        <div class="reg_info">

                            <div class="reg_info_list">

                                <ul class="reg_info_list_current">
                                    <li><?=c2ms('info_current_title_2');?></li>
                                    <!-- A-record -->
                                    <li class="reg_info_table_li">
                                        <table class="reg_info_table">
                                            <tr>
                                                <td>A</td>
                                                <td>@</td>
                                                <td><?=$origin_ip?></td>
                                            </tr>
                                            <tr>
                                                <td>A</td>
                                                <td>www</td>
                                                <td><?=$origin_ip?></td>
                                            </tr>
                                        </table>
                                    </li>
                                </ul>
                                <div class="reg_info_list_arrow">
                                    <i class="fa fa-angle-right fa-2x"></i>
                                </div>
                                <ul class="reg_info_list_change">
                                    <li><?=c2ms('info_new_title_2');?></li>
                                    <li class="reg_info_table_li">
                                        <table class="reg_info_table">
                                            <tr>
                                                <td>A</td>
                                                <td>@</td>
                                                <td><?=$redirect_server_ip?></td>
                                            </tr>
                                            <tr>
                                                <td>CNAME</td>
                                                <td>www</td>
                                                <td><?=$cname?></td>
                                            </tr>
                                        </table>
                                    </li>
                                </ul>
                            </div>
                            <ul class="reg_info_comment">
                                <li>
                                    <i class="fa fa-circle"></i>
                                    <?=c2ms('addsite_dns_comment_4');?>
                                </li>
                                <li>
                                    <i class="fa fa-circle"></i>
                                    <?=c2ms('addsite_dns_comment_5');?>
                                </li>
                                <li>
                                    <i class="fa fa-circle"></i>
                                    <?=c2ms('addsite_dns_comment_6');?>
                                </li>
                            </ul>
                        </div> <!--./reg_info-->
                    </div><!--tab pane-->
                <? } ?>

            </div><!--tab content-->
        </div>

        <button id="btn-done" class="btn btn-yellow btn-lg" onclick="location.href='/mysites'"><?=c2ms('done_btn');?></button>
    </section>
</div><!-- /.content_center -->