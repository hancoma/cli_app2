<style>
#addsite-progress-text li {
    width: 16.7% !important;
}
</style>
<script>
    <?php // TODO 스크립트 파일로 합쳐야함.. ?>
    $(function(){
        //progress bar
        var progress_width = ($(".progress").width() === 0)? 784 : $(".progress").width();
        $(".progress-bar").animate({'width': progress_width * 0.61}, 1000);
    });
</script>
<div id="addsite-wrap">
    <?php require_once(__DIR__.'/addsite_progress.php'); ?>

    <section id="addsite_ssl_manual" class="content">
        <div class="title_text text_blue"><?=c2ms('addsite_manual_title');?></div>
        <div class="sub_text text_gray">
            <?if(get_cookie('lang') == 'en'){?>
                <?=c2ms('addsite_manual_desc1');?><?=$domain?><?=c2ms('addsite_manual_desc2');?>https://<?=$domain?><?=c2ms('addsite_manual_desc3');?>
            <?}else if(get_cookie('lang') == 'ko'){?>
                <?=$domain?><?=c2ms('addsite_manual_desc1');?>https://<?=$domain?><?=c2ms('addsite_manual_desc2');?>
            <?}else if(get_cookie('lang') == 'ja'){?>
                <?=c2ms('addsite_manual_desc1');?><?=$domain?><?=c2ms('addsite_manual_desc2');?>https://<?=$domain?><?=c2ms('addsite_manual_desc3');?>
            <?}else{?>
                <?=c2ms('addsite_manual_description');?>
            <?}?>
        </div>

        <form id="setManualForm" name="goNextPage" method="post" action="">
            <input type="hidden" name="step" value="manual">

            <div id="addsite_ssl_manual_txt_list">
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <th>TYPE</th>
                        <th>NAME</th>
                        <th>VALUE</th>
                    </tr>
                    <?php
                    foreach($txt_list as $val){
                    ?>
                    <tr>
                        <td>TXT</td>
                        <td><?=@$val['name']?></td>
                        <td><?=@$val['value']?></td>
                    </tr>
                    <?php
                    }?>
                </table>
            </div>



            <div class="modal request_modal fade" id="txtCheckRequestModal" aria-hidden="true" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title" id="cb_alert_header"><?php echo c2ms('modal_title_warning');?></h4>
                        </div>
                        <div id="cb_alert_body" class="modal-body"><?=c2ms('ssl_manual_modal_try');?></div>
                    </div>
                </div>
            </div>

            <div class="modal request_modal fade" id="txtCheckNextRequestModal" aria-hidden="true" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="requestModalBody" class="modal-body">
                            <div class="close_button">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            </div>
                            <div class="request_description">
                                <?=c2ms('ssl_manual_modal_success');?>
                            </div>
                            <div id="ssl_manual_btn_wrap">
                                <button class="btn btn-yellow btn-lg" id="btn_txt_check_next" type="button"><?=c2ms('Next');?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal request_modal fade" id="txtCheckContinueRequestModal" aria-hidden="true" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="requestModalBody" class="modal-body">
                            <div class="close_button">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            </div>
                            <div class="request_description">
                                <?=c2ms('ssl_manual_modal_continue');?>
                            </div>
                            <div id="ssl_manual_btn_wrap">
                                <button class="btn btn-yellow btn-lg" id="btn_txt_check_continue" type="button"><?=c2ms('Continue');?></button>
                                <button class="btn btn-gray btn-lg" id="btn_txt_check_cancel" type="button"><?=c2ms('Cancel');?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </form>

        <div id="addsite_ssl_manual_btn_wrap">
            <button class="btn btn-yellow btn-lg" id="btn_txt_record_added" type="button"><?=c2ms('add_txt_btn');?></button>
            <button class="btn btn-gray btn-lg" id="btn_switch_ssl_auto"><?=c2ms('switch_auto_btn');?></button>
        </div>
    </section>
    <script>
        var domain_idx = "<?php echo $domain_idx;?>";
    </script>
</div>

