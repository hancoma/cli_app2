<script>
    <?php // TODO 스크립트 파일로 합쳐야함..?>
    $(function(){


        //progress
        var progress_width = ($(".progress").width() === 0)? 784 : $(".progress").width();
        $(".progress-bar").animate({'width': progress_width * 0.25}, 1000);

        setScroll($("#choose-idc-list").find("ul"), "440px");

        $("#choose-idc-list").find("li").click(function(){
            if(!$(this).hasClass("group-country") && !$(this).hasClass("selected")){
                //selected class 제거 및 fa-check 제거 및 map-pin에 selected class 제거
                $("#choose-idc-list").find("li").removeClass("selected").find(".fa-check").remove();
                $("#choose-idc-map").find(".map-pin.selected").removeClass("selected");
                //클릭한 li selected class 추가 및 fa-check 추가 및 지도 map-pin 에 selected class 추가
                $(this).addClass("selected").append($("<i class='fa fa-check' />"));
                $("#choose-idc-map").find("[data-city='"+$(this).data("city")+"']").addClass("selected");
                //#text-choose-idc text 수정
                $("#text-choose-idc").text($(this).find(".choose-idc-name").text());
            }
        });

        //list 마우스 오버 시, active class 추가 및 map-pin active class 추가
        $("#choose-idc-list").find("li").hover(function(){
            $(this).addClass("active");
            $("#idc-"+$(this).data('city')).addClass("active");
        }, function(){
            $(this).removeClass("active");
            $("#idc-"+$(this).data('city')).removeClass("active");
        });

        //Map pin 마우스 오버 시, active class 추가 및 list active class 추가
        $("#choose-idc-map").find(".map-pin").hover(function(){
            $(this).addClass("active");
            $("#choose-idc-list").find("[data-city='"+$(this).data("city")+"']").addClass("active");
        }, function(){
            $(this).removeClass("active");
            $("#choose-idc-list").find("[data-city='"+$(this).data("city")+"']").removeClass("active");
        });

        // 지도에서 Map pin 클릭 시 지역 선택
        $("#choose-idc-map").find(".map-pin").click(function(){
            var el = $(this),
                cityFilter = "[data-city='"+el.data("city")+"']",
                $getLists =  $("#choose-idc-list"),
                $getFilterItem = $getLists.find(cityFilter),
                $getFilterItemLength = $getFilterItem.not('[data-is-fastest]').length;

            $getFilterItemLength > 1 || (function(){
                $getLists.find("li").removeClass("selected").find(".fa-check").remove();
                el.siblings('.map-pin').removeClass("selected");

                $getFilterItem.addClass("selected").append($("<i class='fa fa-check' />"));
                el.addClass("selected");

                $("#text-choose-idc").text(el.data('country'));
            }());
        });
    });
</script>

<div id="addsite-wrap" class="content_center">
    <?php require_once(__DIR__.'/addsite_progress.php');  ?>

    <section id="addsite_region">
        <div class="title_text">
            <?=c2ms('addsite_region_title');?>
        </div>
        <div class="sub_text text_gray">
            <?if(get_cookie('lang') == 'en'){?>
                <?=c2ms('addsite_region_description1');?><?=$user_domain?><?=c2ms('addsite_region_description2');?>
            <?}else{?>
                <?=c2ms('addsite_region_description');?>
            <?}?>
            <!-- Cloudbric에서는 고객님의 웹서버 IP와 가장 빠른 속도로 통신하는 IDC를 추천해드립니다. 다른 IDC를 선택하실 수도 <br/>
            있으니 편하게 선택해주세요. 우선, 웹 서버 IP가 맞는지 아래에서 확인해주세요. IP가 다르면 올바르게 변경해주시기 바랍니다. -->
        </div>


        <form id="setRegionForm" name="goNextPage" method="post" action="/addsite/ssl">
            <input type="hidden" name="step" value="region">
            <input type="hidden" name="ips" value="">
            <input type="hidden" id="cname_ip" value="">
            <input type="hidden" id="webserver_type" value="">
            <div id="choose-idc-list-wrap">
                <div id="choose-idc-list-top">
                    <div id="check-ip" class="form-group">
                        <label for="domainIPs"><?=$user_domain?>'s <span id="serverTypeText">IP</span> <span class="between_space">:</span></label> <?php // TODO cname 선택 시 IP대신에 CNAME으로 변경 ?>
                        <div id="infoFormWrap" class="data_box">
                            <? if($user_domain_ip_multi == null){ ?>
                                <select name="select_ip" id="webServerIPs" class="form-control select_ips">
                                    <option value="<?=$user_domain_ip?>"><?=$user_domain_ip?></option>
                                </select>
                            <? }else{ ?>
                                <select name="select_ip" id="webServerIPs" class="form-control select_ips">
                                    <? for($i=0;$i<count($user_domain_ip_multi); $i++){ ?>
                                        <option value="<?=$user_domain_ip_multi[$i]?>"><?=$user_domain_ip_multi[$i]?></option>
                                    <? }?>
                                </select>
                            <? } ?>
                            <input type="text" id="cnameBox" class="form-control cname_box" name="apply_cname" readonly="readonly">
                        </div>
                        <button id="changeButton" class="btn btn-gray btn-sm" type="button"><?=c2ms('change_btn');?></button>
                    </div>
                    <?

                        $afc_list = json_decode($closest_afc);

                    ?>
                    <div id="text-choose-idc" class="text_blue"><?= $afc_list[0]->afc_country?></div>
                </div>
                <div id="choose-idc-list-bottom">
                    <div id="choose-idc-list" class="pull-left">
                        <ul id="regionList">
                            <li id="fastestCity" class="selected" data-is-fastest="true" data-city="<?= str_replace(" ", "_", strtolower($afc_list[0]->afc_code)) ?>" afc_code="<?= $afc_list[0]->afc_code?>">
                                <span class="choose-idc-name"><?= $afc_list[0]->afc_country?></span> <span class="label label-pill label-blue"><?=c2ms('fastest');?></span>
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </li>



                            <li class="group-country">North America</li>

                            <? for( $i=0;$i<count($afc_list);$i++ ){
                                $data_city = str_replace(" ", "_", strtolower($afc_list[$i]->afc_code));?>
                                <? if( $afc_list[$i]->continent == "North America"){ ?>
                                    <li data-city="<?= $data_city ?>" afc_code="<?= $afc_list[$i]->afc_code?>">
                                        <span class="choose-idc-name"><?= $afc_list[$i]->afc_country?></span>
                                    </li>
                                <? } ?>
                            <?} ?>

                            <li class="group-country">South America</li>
                            <? for( $i=0;$i<count($afc_list);$i++ ){
                                $data_city = str_replace(" ", "_", strtolower($afc_list[$i]->afc_code));?>
                                <? if( $afc_list[$i]->continent == "South America"){ ?>
                                    <li data-city="<?= $data_city ?>" afc_code="<?= $afc_list[$i]->afc_code?>">
                                        <span class="choose-idc-name"><?= $afc_list[$i]->afc_country?></span>
                                    </li>
                                <? } ?>
                            <?} ?>

                            <li class="group-country">Asia</li>
                            <? for( $i=0;$i<count($afc_list);$i++ ){
                                $data_city = str_replace(" ", "_", strtolower($afc_list[$i]->afc_code));?>
                                <? if( $afc_list[$i]->continent == "Asia") { ?>
                                    <li data-city="<?= $data_city ?>" afc_code="<?= $afc_list[$i]->afc_code?>">
                                        <span class="choose-idc-name"><?= $afc_list[$i]->afc_country?></span>
                                    </li>
                                <? } ?>
                            <?} ?>

                            <li class="group-country">Europe</li>
                            <? for( $i=0;$i<count($afc_list);$i++ ){
                                $data_city = str_replace(" ", "_", strtolower($afc_list[$i]->afc_code));?>
                                <? if( $afc_list[$i]->continent == "Europe"){ ?>
                                    <li data-city="<?= $data_city ?>" afc_code="<?= $afc_list[$i]->afc_code?>">
                                        <span class="choose-idc-name"><?= $afc_list[$i]->afc_country?></span>
                                    </li>
                                <? } ?>
                            <?} ?>

                            <li class="group-country">Oceania</li>
                            <? for( $i=0;$i<count($afc_list);$i++ ){
                                $data_city = str_replace(" ", "_", strtolower($afc_list[$i]->afc_code));?>
                                <? if( $afc_list[$i]->continent == "Oceania"){ ?>
                                    <li data-city="<?= $data_city ?>" afc_code="<?= $afc_list[$i]->afc_code?>">
                                        <span class="choose-idc-name"><?= $afc_list[$i]->afc_country?></span>
                                    </li>
                                <? } ?>
                            <?} ?>

                            <li class="group-country">Africa</li>
                            <? for( $i=0;$i<count($afc_list);$i++ ){
                                $data_city = str_replace(" ", "_", strtolower($afc_list[$i]->afc_code));?>
                                <? if( $afc_list[$i]->continent == "Africa"){ ?>
                                    <li data-city="<?= $data_city ?>" afc_code="<?= $afc_list[$i]->afc_code?>">
                                        <span class="choose-idc-name"><?= $afc_list[$i]->afc_country?></span>
                                    </li>
                                <? } ?>
                            <?} ?>


                        </ul>
                    </div>
                    <div id="choose-idc-map" class="pull-right">
                        <div id="choose-idc-map-img"></div>
                        <div id="choose-idc-map-pin">
                            <? for( $i=0;$i<count($afc_list);$i++ ){
                                $data_city = str_replace(" ", "_", strtolower($afc_list[$i]->afc_code));
                                if (isset($afc_list[$i]->speed) &&  $afc_list[$i]->speed=="fastest") { ?>
                                    <div id="idc-<?= $data_city?>" class="map-pin selected" data-city="<?= $data_city?>" data-country="<?= $afc_list[0]->afc_country?>" data-dummy="1"></div>
                                <?}else{ ?>
                                    <div id="idc-<?= $data_city?>" class="map-pin" data-city="<?= $data_city?>" data-country="<?= $afc_list[$i]->afc_country?>" data-dummy="2"></div>
                                <?} ?>
                            <?} ?>


                        </div>
                    </div>
                </div>
            </div>

            <div class="modal request_modal fade" id="changeRequestModal" aria-hidden="true" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div id="requestModalBody" class="modal-body">
                            <div class="close_button">
                                <button type="button" id="modalCloseButton" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                            </div>
                            <div class="request_description"><?= c2ms('region_modal') ?></div>
                            <div class="request_select">
                                <div class="select_area">
                                    <input type="radio" id="checkIp" class="request_type" name="type" value="ips" checked="checked">
                                    <label for="checkIp" class="type_label">Web Server IPs</label>
                                    <div class="select_body type_ips">
                                        <ul id="inputList" class="input_list">
                                            <? if(isset($user_domain_ip_multi)){ ?>
                                                <? for($i=0;$i<count($user_domain_ip_multi); $i++){ ?>
                                                    <li class="item_ips">
                                                        <button class="option_button option_remove" type="button">-</button>
                                                        <input type="text" class="form-control ips_input" value="<?=$user_domain_ip_multi[$i]?>">
                                                    </li>
                                                <? }?>
                                            <?}else{?>
                                                <li class="item_ips">
                                                    <button class="option_button option_remove" type="button">-</button>
                                                    <input type="text" class="form-control ips_input" value="<?=$user_domain_ip?>">
                                                </li>
                                            <? }?>
                                            <li class="item_ips last">
                                                <button class="option_button option_add" type="button">+</button>
                                                <input type="text" class="form-control ips_input">
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="select_area">
                                    <input type="radio" id="checkCname" class="request_type" name="type" value="cname">
                                    <label for="checkCname" class="type_label">Web Server CNAME</label>
                                    <div class="select_body type_cname">
                                        <input type="text" id="inputCname" class="form-control cname_input" name="cname">
                                    </div>
                                </div>
                            </div>
                            <div class="request_apply">
                                <button id="applyButton" class="btn btn-yellow btn-sm" type="button">Apply Changes</button>
                                <button id="resetButton" class="btn btn-gray btn-sm" type="button">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <button id="nextStepButton" class="btn btn-block btn-lg btn-yellow" cb-action="region_next"><?=c2ms('next_btn');?></button>
    </section>
</div>