<script>
    <?php // TODO 스크립트 파일로.. ?>
    $(function(){
        var progress_width = ($(".progress").width() === 0)? 784 : $(".progress").width(); //progress bar
        $(".progress-bar").animate({'width': progress_width * 1}, 1000); //progress step width

        $('#btn-done').on('click', function(){
            location.href = '/mysites';
        });
    });
</script>
<div id="addsite-wrap">
    <?php require_once(__DIR__.'/addsite_progress.php'); ?>

    <section id="addsite_dnsinfo" class="content">
        <div class="title_text text_blue"><?=c2ms('addsite_complete_title');?></div>
        <br/><br/>
        <div class="sub_text text_gray">

            <?if(get_cookie('lang') == 'en'){?>
                <?=$domain?><?=c2ms('addsite_complete_description1');?><?=$naked_domain?><?=c2ms('addsite_complete_description2');?>
            <?}else{?>
                <?=$naked_domain?><?=c2ms('addsite_complete_description1');?><?=$domain?><?=c2ms('addsite_complete_description2');?>
            <?}?>

        </div>
        <button id="btn-done" class="btn btn-yellow btn-lg"><?=c2ms('go_to_my_sites');?></button>
    </section>
</div>

