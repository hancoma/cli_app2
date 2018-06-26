<?php require_once(__DIR__.'/addsite_progress.php'); ?>
<form id="addsiteForm" name="checkDomainForm" method="post" target="_self" action="/addsite/region">
    <div id="addsite-wrap" class="content_center">
        <section id="addsite1" class="content">
            <div id="addsite1_img"></div>
            <div class="content_addsite">
                <div class="title_text text_blue"><?=c2ms('addsite_title');?></div>
                <div class="sub_text"><?=c2ms('addsite_description');?></div>
                <div id="addsite_url_wrap" class="form-group">
                    <label class="form-label control-label" for="addsite_url" cb-action="url_check">
                        <span id="statusImage" class="addsite_status_img"></span>
                    </label>
                    <input type="text" name="user_domain" cb-action="url" class="form-control" id="addsite_url" placeholder="<?=c2ms('ph_domain');?>" value="<?php echo $add_domain;?>">
                </div>
            </div>
        </section>
    </div><!-- /.content_wrapper -->
    <div class="content_center">
        <button id="nextStepButton" class="btn btn-block btn-blue btn-lg" cb-action="domain_check"><?=c2ms('add_site_btn');?></button>
    </div>

    <div class="modal request_modal fade" id="wwwCheckContinueModal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div id="requestModalBody" class="modal-body">
                    <div class="close_button">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    </div>
                    <div class="request_description www_description">
                        <?=c2ms('addsite_index_www_description');?>
                    </div>
                    <div id="ssl_manual_btn_wrap">
                        <button class="btn btn-yellow btn-lg" id="btn_naked_continue" type="button"><?=c2ms('add_www_naked_btn');?></button>
                        <button class="btn btn-gray btn-lg" id="btn_naked_cancel" type="button"><?=c2ms('add_www_btn');?></button>
                    </div>

                </div>
            </div>
        </div>
    </div>

</form>