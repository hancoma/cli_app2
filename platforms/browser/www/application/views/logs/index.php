<!--CONTENT-->
<!-- Date Picker -->
<div id="daterangepicker_wrap">
    <div id="daterangepicker" class="inline_right">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
        <span></span> <b class="caret"></b>
    </div>
</div>
<div id="event_download" class="inline_left">
    <button type="button" id="downloadButton" class="btn btn-gray btn-sm"><?=c2ms('download_excel');?></button>
</div>
<section id="event" class="content inline_clear">
    <div class="event_control inline_left">
        <!-- FILTER -->
        <div id="filter_attack" class="filter_wrap">
            <div class="form-wrap">
                <select class="form-control">
                    <option disabled selected><p><?=c2ms('attack');?></p></option>
                </select>
                <div class="over_select"></div>
            </div>
            <div class="filter_options_wrap">
                <div id="option_attack" class="checkbox checkbox-info filter_options">
                    <div><?=c2ms('no_data');?></div>
                </div>
            </div>
        </div>

        <div id="filter_ip" class="filter_wrap">
            <div class="form-wrap">
                <select class="form-control">
                    <option disabled selected><?=c2ms('address');?></option>
                </select>
                <div class="over_select"></div>
            </div>
            <div class="filter_options_wrap">
                <div id="option_ip" class="checkbox checkbox-info filter_options">
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

        <div id="filter_uri" class="filter_wrap">
            <div class="form-wrap">
                <select class="form-control">
                    <option disabled selected><?=c2ms('url');?></option>
                </select>
                <div class="over_select"></div>
            </div>
            <div class="filter_options_wrap">
                <div id="option_uri" class="checkbox checkbox-info filter_options">
                    <div><?=c2ms('no_data');?></div>
                </div>
            </div>
        </div>
    </div>
    <div id="event_search" class="inline_right">
        <input type="text" name="event_search" id="searchInput" placeholder="<?=c2ms('ph_search');?>" class="form-control">
    </div>
    <ul id="event_tagit" class="inline_clear"></ul>
    <div id="event_tooltip" class="btn-hover">
        <img src="/assets/img/dashboard/help_01.png"/>
    </div>
    <div id="event_data"></div>
</section>