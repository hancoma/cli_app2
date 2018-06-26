<link rel="stylesheet" type="text/css" href="/assets/plugins/dx/dx.common.css" />
<link rel="stylesheet" type="text/css" href="/assets/plugins/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" type="text/css" href="/assets/plugins/dx/dx.light.compact.css" />
<?php
$country_code_to_name = file_get_contents(__DIR__."/../../../config/country_code_to_name.json");
$rule_code_to_name = file_get_contents(__DIR__."/../../../config/rule_code_to_name.json");
?>
<script>
    var country_code_to_name = <?=$country_code_to_name?>;
    var rule_code_to_name = <?=$rule_code_to_name?>;
</script>
<!--CONTENT-->
<!-- Date Picker -->
<div id="daterangepicker_wrap">
    <div id="daterangepicker" class="inline_right">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        <span></span> <b class="caret"></b>
    </div>
</div>
<div id="event_download" class="inline_left">
    <input id="download_excel" type="button" class="btn btn-gray btn-sm" value="<?=c2ms('download_excel');?>">
</div>
<section id="event" class="content inline_clear">
    <div class="event_control inline_left">
        <!-- FILTER -->
        <div id="filter_purpose" class="filter_wrap">
            <div class="form-wrap">
                <select class="form-control">
                    <option disabled selected><p><?=c2ms('purpose');?></p></option>
                </select>
                <div class="over_select"></div>
            </div>
            <div class="filter_options_wrap">
                <div id="option_purpose" class="checkbox checkbox-info filter_options">
                    <div><?=c2ms('no_data');?></div>
                </div>
            </div>
        </div>

        <div id="filter_address" class="filter_wrap">
            <div class="form-wrap">
                <select class="form-control">
                    <option disabled selected><?=c2ms('address');?></option>
                </select>
                <div class="over_select"></div>
            </div>
            <div class="filter_options_wrap">
                <div id="option_address" class="checkbox checkbox-info filter_options">
                    <div><?=c2ms('no_data');?></div>
                </div>
            </div>
        </div>

        <div id="filter_country" class="filter_wrap">
            <div class="form-wrap">
                <select class="form-control">
                    <option disabled selected><?=c2ms('country');?></option>
                </select>
                <div class="over_select"></div>
            </div>
            <div class="filter_options_wrap">
                <div id="option_country" class="checkbox checkbox-info filter_options">
                    <div><?=c2ms('no_data');?></div>
                </div>
            </div>
        </div>

        <div id="filter_url" class="filter_wrap">
            <div class="form-wrap">
                <select class="form-control">
                    <option disabled selected><?=c2ms('url');?></option>
                </select>
                <div class="over_select"></div>
            </div>
            <div class="filter_options_wrap">
                <div id="option_url" class="checkbox checkbox-info filter_options">
                    <div><?=c2ms('no_data');?></div>
                </div>
            </div>
        </div>
    </div>
    <div id="event_search" class="inline_right">
        <input type="text" name="event_search" placeholder="<?=c2ms('ph_search');?>" class="form-control">
    </div>


    <ul id="event_tagit" class="inline_clear"></ul>
    <div id="event_tooltip" class='btn-hover'>
        <img src='/assets/img/dashboard/help_01.png'/>
    </div>

    <div id="event_data"></div>
</section>

<script src="/assets/plugins/daterangepicker/moment.js"></script>
<script src="/assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="/assets/plugins/jquery/jquery-ui.min.js"></script>
<script src="/assets/plugins/tagit/tag-it.min.js"></script>
<script src="/assets/plugins/dx/globalize.min.js"></script>
<script src="/assets/plugins/dx/dx.webappjs.debug.js"></script>
<script src="/assets/plugins/dx/dx.all.js"></script>
<script src="/assets/plugins/dx/jszip.min.js"></script>
<script src="/assets/js/events.js?<?php echo LIB_CACHE_DATE?>"></script>