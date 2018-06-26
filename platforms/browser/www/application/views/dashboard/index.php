<!--CONTENT-->
<div class="dashboard_wrap">
    <!-- Date Picker -->
    <div id="daterangepicker_wrap">
        <div id="daterangepicker" class="inline_right">
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
            <span></span> <b class="caret"></b>
        </div>
    </div>

    <!-- Download PDF (나중에 개발) -->
    <div id="dash_download" class="inline_right">
        <!--<input id="download_pdf" type="button" class="btn btn-gray btn-sm inline_right" value="Download PDF">-->
    </div>

    <!-- Dashboard 본문 -->
    <section id="dashboard" class="content inline_clear">
        <!-- Overview -->
        <div id="dash_overview">
            <p class="title_text"><?=c2ms('overview_title');?></p>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs nav-stacked inline_left">
                    <li id="overview_tab1" class="active">
                        <a href="#overview_1" data-toggle="tab">
                            <div class="inline_left">
                                <p class="text_left text_red"><?=c2ms('attacks');?></p>
                                <p class="overview_num" id="overview_num_attacks">-</p>
                            </div>
                            <div class="inline_right">
                                <p class="text_right text_blue"><?=c2ms('visits');?></p>
                                <p class="overview_num" id="overview_num_visits">-</p>
                            </div>
                        </a>
                    </li>
                    <li id="overview_tab2" class="">
                        <a href="#overview_2" data-toggle="tab">
                            <div id="overview_dash_1" class="inline_left">
                                <p class="text_left text_red"><?=c2ms('hackers');?></p>
                                <p class="overview_num" id="overview_num_hackers">-</p>
                            </div>
                            <div id="overview_dash_2" class="inline_right">
                                <p class="text_right text_blue"><?=c2ms('visitors');?></p>
                                <p class="overview_num" id="overview_num_visitors">-</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="tab-content inline_right">
                    <div class="tab-pane active" id="overview_1">
                        <!-- No data용 -->
                        <p class="overview_nodata"><?=c2ms('no_data');?></p>
                        <!-- Graph -->
                        <div id="overview_graph1" style="border-left:0;">
                            <div class="data_loading hide_loading"></div>
                        </div>
                    </div>
                    <div class="tab-pane" id="overview_2">
                        <!-- No data용 -->
                        <p class="overview_nodata">No Data</p>
                        <!-- Graph -->
                        <div id="overview_graph2">
                            <div class="data_loading hide_loading"></div>
                        </div>
                    </div>
                </div>
            </div> <!-- ./nav-tabs-custom -->
        </div><!-- ./#dash_overview -->

        <!-- DASHBOARD CONTENT -->
        <div id="dash_tabs">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#dash_attack" id="tab_dash_attack" data-toggle="tab"><?=c2ms('attacks');?></a></li>
                    <li class=""><a href="#dash_visit" id="tab_dash_visit" data-toggle="tab"><?=c2ms('visits');?></a></li>
                </ul>
                <div class="tab-content">

                    <!--
                       TAB : ATTACKS
                     -->
                    <div class="tab-pane active" id="dash_attack">
                        <!-- Reports worldwide (Attack) -->
                        <div id="dash_attack_map" class="dash_map">
                            <p class="title_text">
                                <?=c2ms('attack_map');?>
                                <span class="btn-hover" id="dash_attack_map_title">
									<img src='/assets/img/dashboard/help_01.png' style="margin-top:-4px">
								</span>
                            </p>

                            <div id="attackMapWrap" class="graph_wrap">
                                <div class="data_loading hide_loading"></div>
                                <!-- Reset Map Position -->
                                <!-- <input class="btn_map_center btn btn-linegray btn-xs" type="button" value="Center"/> -->

                                <!-- Attack Maps -->
                                <div id="dash_attack_map_ip" class="dash_map_vector"></div>
                                <div id="dash_attack_map_country" class="dash_map_vector" style="display:none;"></div>
                                <p class="dash_attack_ip_nodata"><?=c2ms('no_data');?></p>
                                <p class="dash_attack_country_nodata"><?=c2ms('no_data');?></p>

                                <!-- Attack Map Information -->
                                <div class="dash_map_info">
                                    <div class="dash_map_info_top">
                                        <div class="round_tabs">
                                            <ul>
                                                <li id="round_tabs_ip" class="attacks_toggle active" data-status="active" data-target-table="dash_attack_ip_table" data-target-map="dash_attack_map_ip"><?=c2ms('ip');?></li>
                                                <li id="round_tabs_country" class="attacks_toggle" data-status="hidden" data-target-table="dash_attack_country_table" data-target-map="dash_attack_map_country"><?=c2ms('country');?></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- attack ip table-->
                                    <table id="dash_attack_ip_table" class="table table-striped">
                                        <?php for($i=0;$i<5;$i++):?>
                                            <tr>
                                                <td><span class="dash_map_order_num">-</span></td>
                                                <td><span class="attack_map_ip_value"></span></td>
                                                <td><button class="btn btn-gray btn-xs btn_map_action attack_toggle_ip" type="button">-</button></td>
                                            </tr>
                                        <?php endfor;?>
                                    </table>
                                    <!-- attack country table-->
                                    <table id="dash_attack_country_table" class="table table-striped" style="display:none;">
                                        <?php for($i=0;$i<5;$i++):?>
                                            <tr>
                                                <td><span class="dash_map_order_num">-</span></td>
                                                <td><span class="attack_map_country_value"></span></td>
                                                <td><button class="btn btn-gray btn-xs btn_map_action attack_toggle_country" type="button">-</button></td>
                                            </tr>
                                        <?php endfor;?>
                                    </table>

                                </div> <!-- ./dash_map_info -->
                            </div>
                        </div> <!-- ./#dash_attack_map -->

                        <!-- Top Attack Purpose (LEFT) -->
                        <div id="dash_purpose" class="pie_graph">
                            <p class="title_text">
                                <?=c2ms('attack_purpose');?>
                            </p>
                            <!-- No data용 -->
                            <p class="pie_graph_nodata"><?=c2ms('no_data');?></p>
                            <!-- Graph -->
                            <div id="purposeGraphWrap" class="graph_wrap">
                                <div class="data_loading hide_loading"></div>
                                <div id="dash_purpose_graph">
                                </div>
                                <div class="pie_graph_info">
                                    <table id="attackPurposeTable" class="table table-striped">
                                        <?php for($i = 0; $i < 5; $i++): ?>
                                            <tr>
                                                <td>
                                                    <svg width="30" height="30">
                                                        <rect width="30" height="30" fill="#c3c3c3"></rect>
                                                    </svg>
                                                </td>
                                                <td class="pie_graph_info_text">-</td>
                                                <td><span class="pie_graph_info_img"></span></td>
                                            </tr>
                                        <?php endfor; ?>
                                    </table>
                                </div><!-- ./pie_graph_info -->
                            </div>
                        </div><!-- ./#dash_purpose -->

                        <!-- Top Attack URLs (RIGHT) -->
                        <div id="dash_urls" class="pie_graph inline_right">
                            <p class="title_text">
                                <?=c2ms('attack_urls');?>
                            </p>
                            <!-- No data용 -->
                            <p class="pie_graph_nodata"><?=c2ms('no_data');?></p>
                            <!-- Graph -->
                            <div id="urlsGraphWrap" class="graph_wrap">
                                <div class="data_loading hide_loading"></div>
                                <div id="dash_urls_graph"></div>
                                <div class="pie_graph_info">
                                    <table id="attackUrlsTable" class="table table-striped">
                                        <?php for($i=0;$i<5;$i++):?>
                                            <tr>
                                                <td>
                                                    <svg width="30" height="30" alignment-baseline='middle'>
                                                        <rect width="30" height="30" fill="#c3c3c3"></rect>
                                                    </svg>
                                                </td>
                                                <td class="pie_graph_info_text">-</td>
                                            </tr>
                                        <?php endfor;?>
                                    </table>
                                </div><!-- ./pie_graph_info -->
                            </div>
                        </div><!-- ./#dash_urls -->

                        <!-- Recent Attacks -->
                        <div id="dash_recent">
                            <p class="title_text"><?=c2ms('recent_attacks');?></p>
                            <div id="recentWrap" class="dash_recent_wrapper">
                                <div class="dash_recent_top">
                                    <a href="/logs/a/<?php echo $this->uri->segment(3);?>">
                                        <input type="button" id="btn_gotoevent" class="btn btn-gray btn-xs" value="<?=c2ms('go_to_logs');?>">
                                    </a>
                                </div>
                                <div class="data_loading hide_loading"></div>
                                <table class="table table-striped dash_recent_table">
                                    <thead>
                                    <tr>
                                        <th><?=c2ms('time');?></th>
                                        <th><?=c2ms('attack');?></th>
                                        <th><?=c2ms('address');?></th>
                                        <th><?=c2ms('country');?></th>
                                        <th><?=c2ms('url');?></th>
                                    </tr>
                                    </thead>
                                    <tbody id="recent_attack_data">
                                        <tr>
                                            <td colspan="5">-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> <!-- dash_recent_wrapper -->
                        </div> <!-- #dash_recent -->
                    </div><!-- ./#dash_attack -->

                    <!--
                       TAB : VISIT
                     -->
                    <div class="tab-pane" id="dash_visit">
                        <!-- Reports worldwide (Visit) -->
                        <div id="dash_visit_map" class="dash_map">
                            <p class="title_text">
                                <?=c2ms('visit_map');?>
                                <span class="btn-hover" id="dash_visit_map_title">
									<img src='/assets/img/dashboard/help_01.png' style="margin-top:-4px">
								</span>
                            </p>
                            <div id="visitMapWrap" class="graph_wrap">
                                <div class="data_loading hide_loading"></div>
                                <p class="dash_visit_map_nodata"><?=c2ms('no_data');?></p>
                                <div id="dash_visit_map_vector" class="dash_map_vector"></div>
                                <div class="dash_map_info">
                                    <div class="dash_map_info_top">
                                        <div class="round_tabs">
                                            <ul><li class="active"><?=c2ms('country');?></li></ul>
                                        </div>
                                        <p class="sub_text text_red">　<!-- Click on a row to add white country. --></p>
                                    </div>
                                    <table id="dash_visit_map_table" class="table table-striped">
                                        <?php for($i=0;$i<5;$i++):?>
                                            <tr>
                                                <td><span class="dash_map_order_num">-</span></td>
                                                <td><span class="attack_map_country_value"></span></td>
                                                <td><input class="btn btn-gray btn-xs btn_map_action" value="-" type="button"></td>
                                            </tr>
                                        <?php endfor;?>
                                    </table>
                                </div><!-- ./dash_map_info -->
                            </div>
                        </div>

                            <!-- Accumulated Bandwidth -->
                        <div id="dash_bandwidth" class="pie_graph">
                            <p class="title_text">
                                <?=c2ms('accumulated_andwidth');?>
                                (<span id='dash_bandwidth_month'></span>)
                            </p>
                            <div id="bandwidthGraphWrap" class="graph_wrap">
                                <div class="data_loading hide_loading"></div>
                                <p class="bandwidth_nodata"><?=c2ms('no_data');?></p>
                                <div id="dash_bandwidth_graph"></div>
                            </div>

                        </div>

                        <!-- throughput -->
                        <div id="dash_throughput" class="pie_graph inline_left">
                            <p class="title_text">
                                <?=c2ms('throughput_logs');?>
                            </p>
                            <div id="throughputGraphWrap" class="graph_wrap">
                                <div class="data_loading hide_loading"></div>
                                <!-- No data용 -->
                                <p class="throughput_nodata"><?=c2ms('no_data');?></p>
                                <!-- Graph -->
                                <div id="dash_throughput_graph"></div>
                            </div>
                        </div>

                        <!-- Traffic by day -->
                        <div id="dash_traffic_day" class="pie_graph inline_right">
                            <p class="title_text">
                                <?=c2ms('traffic_by_day');?>
                            </p>

                            <div id="trafficGraphWrap" class="graph_wrap">
                                <div class="data_loading hide_loading"></div>
                                <!-- No data용 -->
                                <p class="traffic_day_nodata"><?=c2ms('no_data');?></p>
                                <!-- Graph -->
                                <div id="dash_traffic_day_graph"></div>
                            </div>
                        </div>

                        <div class="inline_clear"></div>
                    </div> <!-- ./#dash_visit -->
                </div> <!-- ./tab-content -->
            </div> <!-- ./nav-tabs-custom -->
        </div> <!-- ./#dash_tabs -->
    </section>
</div>