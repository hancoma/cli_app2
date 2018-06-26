<script src="/assets/js/report.js?<?php echo LIB_CACHE_DATE?>"></script>
<style>
    /* 스타일 테스트 */
    .wrap { background-color: #FFF } /* 기존의 콘솔과 배경 색이 달라 임시로 수정 */
    .report_options { margin-top: 20px; margin-bottom: 20px }
    .option_style { height: 40px; color: #7f7f7f; background-color: #f2f2f2; border-color: #7f7f7f; border-radius: 10px }
    .select_month { float: left }
    .back_dashboard { float: right }
    .select_month select { padding: 0 40px; font-size: 18px; border-color: #e4e4e4; cursor: pointer }
    .back_dashboard .btn { padding: 0 20px; font-size: 16px }
    .float_clear { clear: both }
</style>

<div class="wrap">
    <div id="dashboard">
        <div class="report_options">
            <div class="select_month">
                <select name="months" id="mothsSelect" class="option_style">
                    <?php foreach($range_reports_month as $v):?>
                        <?php $sliceRange = explode('/', $v);?>
                        <option value="<?php echo $v?>"><?php echo date("F Y", strtotime($sliceRange[0] . '-' . $sliceRange[1] . "-01"));?></option>
                    <?php endforeach?>
                </select>
            </div>
            <div class="back_dashboard">
                <button type="button" class="btn btn-default option_style"><?=c2ms('back_to_dashboard');?></button>
            </div>
            <div class="float_clear"></div>

            <script>
                var thisDomain = '<?php echo $thisDomain;?>';
                var targetURI = '<?php echo $baseURI.'/';?>';

                $('select[name="months"]').on('change', function() {
                    var _uri = targetURI + $(this).val() + '/' + thisDomain;
                    $('#monthlyReportPage').attr('src', _uri);
                });

                $('.back_dashboard').children('.btn').on('click', function() {
                    $(location).attr('href', '/dashboard/a/<?=$selected_domain_info['domain_idx']?>');
                });
            </script>
        </div>
        <div class="report_wrap">
            <iframe id="monthlyReportPage" src="<?php echo $iframeURI;?>" frameborder="0" width="100%" height="2900" marginwidth="0" marginheight="0" scrolling="no"></iframe>
        </div>
    </div>
    <!--div id="ajaxTest">
        <select name="taegwang">
            <option value="1">No. 1</option>
            <option value="2">No. 2</option>
            <option value="3">No. 3</option>
        </select>
    </div-->
</div>