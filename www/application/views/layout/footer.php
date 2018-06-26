<div id="footer_top">
    <!--<span id="footer_policy_master" class="a_hoverdark">Master Service Agreement</span>　　|　　-->
   <div class="footer_wrap">
       <?php
       if(PARTNER_CONSOLE == 'no') { //파트너 콘솔 : 언어 선택 기능 막음 (설정 언어가 디폴트)
       ?>
       <div class="footer_top_sns">
           <ul>
               <li><a href='https://www.linkedin.com/company/cloudbric' target='_blank' class="fa fa-linkedin"></a></li>
               <li><a href='https://plus.google.com/111416050177022730879/posts' target='_blank' class="fa fa-google-plus"></a></li>
               <li><a href='https://twitter.com/cloudbric' target='_blank' class="fa fa-twitter"></a></li>
               <li><a href='http://facebook.com/cloudbric' target='_blank' class="fa fa-facebook"></a></li>
           </ul>
       </div>
       <div class="footer_policy">
           <a href="<?=c2ms('policy_msa_link')?>" target="_blank" class="a_hoverdark"><?=c2ms('policy_msa')?></a>　　|　
           <a href="<?=c2ms('policy_sla_link')?>" target="_blank" class="a_hoverdark"><?=c2ms('policy_sla')?></a>　　|　　
           <a href="<?=c2ms('policy_aup_link')?>" target="_blank" class="a_hoverdark"><?=c2ms('policy_aup')?></a>　　|　　
           <a href="<?=c2ms('policy_privacy_link')?>" target="_blank" class="a_hoverdark"><?=c2ms('policy_privacy')?></a>
       </div>
       <?php } ?>
   </div>
</div>
<div id="footer_bottom">
    <div class="footer_wrap">
        <?php
        if(PARTNER_CONSOLE == 'no') { //파트너 콘솔 : 언어 선택 기능 막음 (설정 언어가 디폴트), 카피라이트
        ?>
        <div class="footer_copyright">&copy; <? echo date('Y');?> Cloudbric Corp. All Rights Reserved.</div>
        <div class="footer_language_box">
            <select name="footer" class="form-control">
                <option value="en"<?php if(get_cookie('lang') == 'en' || empty(get_cookie('lang'))) echo 'selected="selected"';?>>English</option>
                <option value="ko"<?php if(get_cookie('lang') == 'ko') echo 'selected="selected"';?>>한국어</option>
                <?php if(ENVIRONMENT !== 'production') { //실서비스 아직 노출 안 함?>
                <option value="ja"<?php if(get_cookie('lang') == 'ja') echo 'selected="selected"';?>>日本語</option>
                <?php } ?>
            </select>
        </div>
        <?php } else { ?>
        <div class="footer_copyright">&copy; <? echo date('Y');?> <?=c2ms('partner_console_PARTNER_NAME_VIEW')?></div>
        <?php }?>
    </div>
</div>

<script>
    <?php // TODO 스크립트 파일로 합쳐야함 ?>
    $(function(){
        // - Langage Select Box Changed
        $('select[name="footer"]').on('change', function(){
            var _langCode       = this.value;
                _locationURL    = '/' + _langCode;

            window.location.href = _locationURL;
        });
    });
</script>