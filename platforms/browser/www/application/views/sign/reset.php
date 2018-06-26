<script>setResetPassword();</script>

<section id="reset_password" class="content">
    <div class="content_auth">
        <p class="title_text text_blue"><?=c2ms('sign_reset_title');?></p>
        <p class="sub_text">
            <?=c2ms('sign_reset_msg_1');?><br><?=c2ms('sign_reset_msg_2');?>
        </p>

        <form id="form_reset" name="reset" method="post" target="_self" action="/sign/reset_process">

            <div class="form-group">
                <input type="text" id="useremail" class="form-control" name="useremail" placeholder="<?=c2ms('ph_email');?>">
                <span class="fa form-control-feedback"></span>
            </div>

            <input id="btn_reset" type="button" class="btn btn-blue btn-lg" value="<?=c2ms('password_reset_btn');?>">
            <a href="/sign/in" target="_self" class="back_page a_blue">&lt; <?=c2ms('go_to_sign');?></a>

        </form>
    </div>
</section>