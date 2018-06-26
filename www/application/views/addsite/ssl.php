<script>
    <?php // TODO 스크립트 파일로 합쳐야함.. ?>
    $(function() {
        //progress bar
        //progress
        var progress_width = ($(".progress").width() === 0)? 784 : $(".progress").width();
        $(".progress-bar").animate({'width': progress_width * 0.50}, 1000);

        //SSL 사용 여부
        $(".choose-idc-ssl-using-answer").click(function(){
            //checked 된 경우만
            if($(this).find("input:radio").is(":checked")) {
                if ($(this).find(".radio").data('answer') === "yes") {
                    $("#choose-ssl-using-no-padding").animate({"height": 0}, 500);
                    $("#choose-ssl-using-yes-padding").stop();

                    $(this).addClass("selected");
                    //$('label[for="choose-ssl-using-no"]').removeClass("selected");
                    $("#choose-idc-ssl-no").slideUp(function () {
                        $('label[for="choose-ssl-using-no"]').removeClass("selected");
                    });
                    $("#choose-ssl-using-yes-padding").animate({"height": 25}, 500);
                    $("#choose-idc-ssl-mode").slideDown();
                    $("#choose-idc-ssl-yes-button").css("display", "inline");
                    $("#choose-idc-ssl-no-button").css("display", "none");

                } else {
                    $("#choose-ssl-using-yes-padding").animate({"height": 0}, 500);
                    $("#choose-ssl-using-no-padding").stop();

                    $(this).addClass("selected");

                    $("#choose-idc-ssl-mode").slideUp(function () {
                        $('label[for="choose-ssl-using-yes"]').removeClass("selected");
                    });
                    //clear radio value
                    $("input[name='choose-ssl-mode']").attr('checked', false);

                    $("#choose-ssl-using-no-padding").animate({"height": 25}, 500);
                    $("#choose-idc-ssl-no").slideDown();

                    $("#choose-idc-ssl-yes-button").css("display", "none");
                    $("#choose-idc-ssl-no-button").css("display", "inline");

                }
            }
        });

        //SSL 모드 선택
        $(".choose-idc-ssl-mode-answer").click(function(){
            $(this).addClass("selected");
            if($(this).find(".radio").data('answer') === "automatic"){
                $('label[for="choose-ssl-mode-manual"]').removeClass("selected");
            }else{
                $('label[for="choose-ssl-mode-automatic"]').removeClass("selected");
            }
        });
    });
</script>

<div id="addsite-wrap">
    <?php require_once(__DIR__.'/addsite_progress.php'); ?>
    <section id="addsite_ssl">
        <div class="title_text">
            <?=c2ms('addsite_ssl_title');?>
        </div>
        <div class="sub_text text_gray"><?=c2ms('addsite_ssl_description');?></div>

        <div id="choose-idc-ssl-mode-wrap">
            <div id="choose-idc-ssl-using">
                <?=c2ms('is_my_ssl');?> (<?= $user_domain ?>)<br/>
                <div id="choose-idc-ssl-using-answer-wrap">
                    <label for="choose-ssl-using-no" class="choose-idc-ssl-using-answer pull-left">
                        <div data-answer="no" class="radio">
                            <input type="radio" value="no" name="choose-ssl-using" id="choose-ssl-using-no">
                            <label></label>
                            <?=c2ms('check_my_ssl_1');?>
                            <div id="choose-ssl-using-no-padding"></div>
                        </div>
                    </label>
                    <label for="choose-ssl-using-yes" class="choose-idc-ssl-using-answer pull-right">
                        <div data-answer="yes" class="radio">
                            <input type="radio" value="yes" name="choose-ssl-using" id="choose-ssl-using-yes">
                            <label></label>
                            <?=c2ms('check_my_ssl_2');?>
                            <div id="choose-ssl-using-yes-padding"></div>
                        </div>
                    </label>
                </div>
            </div>
            <div id="choose-idc-ssl-mode" class="inline_clear">
                <?=c2ms('own_ssl_desc_1');?> <?=c2ms('own_ssl_desc_2');?>
                <div id="choose-idc-ssl-mode-answer-wrap">
                    <label for="choose-ssl-mode-automatic" class="choose-idc-ssl-mode-answer pull-left">
                        <div data-answer="automatic" class="radio">
                            <input type="radio" value="no" name="choose-ssl-mode" id="choose-ssl-mode-automatic">
                            <label></label>
                            <string><?=c2ms('own_ssl_install_title_1');?></string> <br/>
                            <?=c2ms('own_ssl_install_desc_1');?>
                        </div>
                    </label>
                    <label for="choose-ssl-mode-manual" class="choose-idc-ssl-mode-answer pull-right">
                        <div data-answer="manual" class="radio">
                            <input type="radio" value="yes" name="choose-ssl-mode" id="choose-ssl-mode-manual">
                            <label></label>
                            &nbsp;
                            <string><?=c2ms('own_ssl_install_title_2');?></string> <br/>
                            <?=c2ms('own_ssl_install_desc_2');?>
                        </div>
                    </label>
                </div>

            </div>
            <div id="choose-idc-ssl-no" class="inline_clear">
                <?=c2ms('ssl_no_desc1');?></br>
                <?=c2ms('ssl_no_desc2');?>
                <?=c2ms('ssl_no_desc3');?>
            </div>

        </div>
        <div id="choose-idc-ssl-yes-button" style="display: none">
            <button id="nextStepButton" class="btn btn-block btn-lg btn-yellow" cb-action="ssl_next"><?=c2ms('next_btn');?></button>
        </div>
        <div id="choose-idc-ssl-no-button" style="display: none">
            <!--<button id="btn_previous" class="btn btn-lg btn-yellow" cb-action="ssl_previous"><?/*=c2ms('Previous');*/?></button>-->
            <button id="nextStepButton" class="btn btn-block btn-lg btn-yellow" cb-action="ssl_no_next"><?=c2ms('next_btn');?></button>
        </div>
    </section>

    <!--<div class="modal request_modal fade" id="sslUsingNoNextRequestModal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog" >
            <div class="modal-content">
                <div id="requestModalBody" class="modal-body" style="text-align: center;" >
                    <div class="close_button">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    </div>
                    <div class="request_description">
                        <?/*=c2ms('ssl_no_desc1');*/?>
                        </br></br>
                        <?/*=c2ms('ssl_no_desc2');*/?></br>
                        <?/*=c2ms('ssl_no_desc3');*/?></br>
                        </br></br>
                    </div>
                    <div id="ssl_manual_btn_wrap">
                        <button class="btn btn-yellow btn-sm" id="btn_previous" type="button"><?/*=c2ms('Previous');*/?></button>
                        <button class="btn btn-gray btn-sm" id="btn_next" type="button"><?/*=c2ms('Next');*/?></button>
                    </div>

                </div>
            </div>
        </div>
    </div>-->

</div>