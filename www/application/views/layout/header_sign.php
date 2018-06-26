<style>#header_bottom {height:5px !important; padding:0 !important;}</style>
<nav id="header_top">
    <div class="content_center">
        <?php
        if(PARTNER_CONSOLE == 'no') { //파트너 콘솔 : 로고
            ?>
            <a href="https://www.cloudbric.com"><img src="/assets/img/logo.png" style="display:block; margin:0 auto;"></a>
        <?php } else {
            if(file_exists('./assets/partner/'.c2ms('partner_console_PARTNER_IDX').'/logo.png')) {
                $logo = '/assets/partner/'.c2ms('partner_console_PARTNER_IDX').'/logo.png';
            } else {
                $logo = '/assets/img/logo.png';
            }
        ?>
            <a href="<?php echo base_url();?>"><img src="<?=$logo?>" style="display:block; margin:0 auto; max-width: 350px; max-height: 42px;"></a>
        <?php }?>
    </div><!-- ./content_center -->
</nav><!-- ./header_top -->
<nav id="header_bottom">
    <div class="content_center">
    </div><!-- ./content_center -->
</nav><!-- ./header_bottom -->