/*
 * Sign up, Login, Sign out, Reset Password 를 위한 javascript 
 * author		| Hyunjin Lee 
 * email		| hyunjinlee@pentasecurity.com
 * Released under Penta Security Systems. 2015-2016
 *
 * sign up, login, reset password 각각의 페이지에서 
 * 페이지 별 함수를 호출하는 형식으로 만들어짐.
 */

/* 
 * 회원 가입 
 */

var error_cnt = 0; //첫번째 에러인지 검사하는 변수
var text = ""; //tooltip

function setSignup() {
    $(function(){
        $btnCoupon = $("#btn_coupon");
        $formCoupon = $("#form_coupon");
        $inputCoupon = $("input[name='coupon']");
        $btnVerifyCoupon = $("#btn_verify_coupon");

        /**********
         * Coupon *
         **********/

        $btnCoupon.click(function(){
            $(this).hide();
            $formCoupon.show();
        });

        $("#btn_close_coupon").click(function(){
            setInputError($inputCoupon, false);

            $formCoupon.hide();
            $btnCoupon.show();
        });

        $btnVerifyCoupon.click(function(e){
            if($inputCoupon.val() == "") {
                setInputError($inputCoupon, true);
                return false;
            }

            //쿠폰 검증 ajax
            $.ajax({
                url : '/sign/verify_coupon',
                type: "POST",
                data: {
                    "coupon" : $inputCoupon.val(),
                },
                dataType:"json",
                beforeSend:function()
                {
                    $btnVerifyCoupon.attr("disabled", true);
                },
                success: function(res)
                {
                    //쿠폰 있을 때
                    if(res.result === true) {
                        //modal message 출력
                        makeModalAlert("Coupon", c2ms('coupon_success'));

                        //$btnVerifyCoupon.attr("value", c2ms('B_verifyCoupon'));

                        $("input:hidden[name='coupon_verify_yn']").val('Y');
                        $("input:hidden[name='coupon_idx']").val(res.coupon_idx);
                    }
                    //쿠폰 없을 때
                    else {
                        //modal message 출력
                        makeModalAlert("Coupon", c2ms("W_notCoupon"));

                        $btnVerifyCoupon.attr("disabled", false);
                    }
                },
                error: function(e)
                {
                    makeModalAlert(c2ms('modal_title_notice'), c2ms('W_hasError1'));
                    console.log(e);
                }
            });
        });

        /***************
         * Sign up 클릭
         ***************/
        var $signUp 		= $('#signup'),
            $signForm 		= $('#signup_form'),
            $submitButton 	= $('#btn_signup'),
            $inputName 		= $signUp.find('input:text[name="user_name"]'),
            $inputEmail 	= $signUp.find('input:text[name="user_id"]'),
            $inputPW 		= $signUp.find('input:password[name="user_password"]'),
            $inputConfirmPW = $signUp.find('input:password[name="repassword"]'),
            $inputCoupon 	= $signUp.find('input:text[name="coupon"]'),
            $checkPolicy1 	= $signUp.find('input:checkbox[name="policy1"]'),
            $checkPolicy2 	= $signUp.find('input:checkbox[name="policy2"]');

        $submitButton.on('click', function(){
            error_cnt = 1;

            // 1. Name Check
            if($inputName.val() === '') {
                setInputError($inputName, true, c2ms('in_input_empty_1'));
            } else if(verifyName($inputName.val()) === false) {
                setInputError($inputName, true, c2ms('in_input_name_verify'));
            }

            // 2. Email Check
            if($inputEmail.val() === '') {
                setInputError($inputEmail, true, c2ms('in_input_empty_2'));
            } else if(verifyEmail($inputEmail.val()) === false) {
                setInputError($inputEmail, true, c2ms('in_input_empty_5'));
            }

            // 3. Password Check
            if($inputPW.val() === '') {
                setInputError($inputPW, true, c2ms('in_input_empty_3'));
            } else if(verifyPassword($inputPW.val()) === false) {
                setInputError($inputPW, true, c2ms("in_input_empty_7"));
            }

            // 4. Confirm Password
            if($inputConfirmPW.val() === '') {
                setInputError($inputConfirmPW, true, c2ms('in_input_empty_4'));
            } else if($inputConfirmPW.val() !== $inputPW.val()) {
                setInputError($inputConfirmPW, true, c2ms("in_input_empty_8"));
            }

            // 5. Checked Agreement
            $('.check_agree').each(function(){
                if(!$(this).is(':checked')) setInputError($(this), true, c2ms('in_input_empty_9'));
            });

            error_cnt = 0;

            //$("form[name='form_signup']").submit();

            //ajax로 값 받아와서 처리
            $.ajax({
                url: "/sign/up_process", // up_process 메소드 없음. 테스트용
                type: "POST",
                data: {
                    "user_name"			: $inputName.val(),
                    "user_id"			: $inputEmail.val(),
                    "user_password"		: $inputPW.val(),
                    "coupon_verify_yn"	: $signUp.find('input:hidden[name="coupon_verify_yn"]').val(),
                    "coupon_idx"		: $signUp.find('input:hidden[name="coupon_idx"]').val(),
                    "coupon_idx"		: $signUp.find('input:hidden[name="coupon_idx"]').val()
                },
                dataType: "json",
                success: function(res)
                {
                    if(res.result == "success") {
                        window.location.href = '/sign/zendeskin';
                    }
                }
            });
        });

        $signForm
            .on('keyup', '.sign_input', function(e){
                // $(e.delegateTarget) => 제어 대상($signForm) :: $(e.delegateTarget).find('.sign_input')과 동일, 하지만 캐싱된 $signForm 오브젝트 활용.
                var _oInput = $signForm.find(".sign_input"),
                    _index = _oInput.index(this);

                setInputError($(this), false); // 1. Error 없애기

                if(e.keyCode == 13) { // 2. Input(Text) 엔터 시 다음 항목으로 넘어가기 위한 분기
                    e.preventDefault();

                    if(_index == 3) { // 3. 마지막 항목이면 회원가입 버튼 클릭 이벤트 적용 (Repeat Password 항목일 경우)
                        $submitButton.click();
                    } else if(_index != _oInput.length - 1) {  // 4. 다음 항목으로 포커스 적용
                        _oInput.eq(_index + 1).focus();
                    }
                }
            })
            .on('click', '.check_agree', function(){
                if($(this).is(':checked')) setInputError($(this), false);
            });
    });
}

/********
 * 로그인
 ********/
function setLogin() {
    $(function(){

        //일본어 (jp)일 때, sign in 버튼 좀 더 넓게하기
        if(getCookie("lang") == "ja") {
            $("#btn_signup").css("width", "155px");
        }

        var in_email = $('#signin').find('input:text[name="useremail"]'),
            in_pw = $('#signin').find('input:password[name="userpw"]'),
            in_remember = $('#signin').find('input:checkbox[name="autocomplete"]');

        $('#btn_signin').click(function(){
            /*$("#notice_construction").modal({keyboard:false}).modal('show');
             return false;*/

            //--유효성 검사
            // 1)빈값
            if(in_email.val() == "") {
                setInputError(in_email, true);
                //위치가 안맞아서 여기에 Popover을 넣는다.
                makePopover(in_email, "right", "show", c2ms('in_input_email_empty'));
                return false;
            }
            else {
                setInputError(in_email, false);
                $("#"+in_email.attr("aria-describedby")).remove();
            }

            if(in_pw.val() == "") {
                setInputError(in_pw, true);
                makePopover(in_pw, "right", "show", c2ms('in_input_pw_empty'));
                return false;
            }
            else {
                setInputError(in_pw, false);
                $("#"+in_pw.attr("aria-describedby")).remove();
            }

            //2) ID가 없거나 비밀번호가 틀림
            $.ajax({
                url : '/sign/in_process',
                type: "POST",
                data: {
                    "user_email" : in_email.val(),
                    "user_pwd" : in_pw.val(),
                    "user_remember" : in_remember.is(':checked') ? 'auto' : 'none' // Remember Me 체크박스가 활성화 되었을 경우의 분기
                },
                dataType:"json",
                beforeSend: function(){
                    $("#btn_signin").prop("disabled", true);
                },
                success: function(res)
                {
                    //error난 경우
                    if(res.result == 'error'){
                        $("#signInModal").modal({"keyboard": true}).modal('show');
                    }
                    //success
                    else{
                        window.location.href= res.landing;
                    }
                },
                complete:function(){
                    $("#btn_signin").prop("disabled", false);
                },
                error: function(e)
                {
                    makeModalAlert(c2ms('modal_title_notice'), c2ms('W_hasError3'));
                    console.log(e);
                }
            });

            //-- 브라우저에 ID/PW 기억. (IE는 환경 설정에서 관련 설정을 수정하지 않으면 사용하지 못함.)


            //$("#form_signin").submit();
        });

        in_email.keyup(function(e){ if(e.which == 13) in_pw.focus();});
        in_pw.keyup(function(e){ if(e.which == 13) $('#btn_signin').click();});
    });
}

/******************
 * Reset Password
 ******************/
function setResetPassword() {
    $(function(){
        var reset_email = $('#reset_password').find('input:text[name="useremail"]');

        $("#btn_reset").click(function(){

            if(reset_email.val() == ""){
                setInputError(reset_email, true);
                makePopover(reset_email, "right", "show", c2ms('reset_tip_empty'));
                return false;
            }
            //이메일 유효성 검사
            else if(verifyEmail(reset_email.val()) === false){
                setInputError(reset_email, true);
                makePopover(reset_email, "right", "show", c2ms('W_notEmail'));
                return false;
            }else{
                $("#"+reset_email.attr("aria-describedby")).remove();
            }

            //비밀번호 reset 하기
            $.ajax({
                url : '/auth/reset/new_password',
                type: "POST",
                data: {
                    "useremail" : reset_email.val(),
                },
                dataType:"json",
                beforeSend:function()
                {
                    $("#btn_reset").attr("disabled", true);
                },
                success: function(res)
                {
                    if(res.reset === "success") {

                        //발급이 되었다는 모달창 보여주기
                        $("#modal_reset").modal('show');

                        $("#btn_modal_reset_okay").click(function(){
                            window.location.href='/sign/in';
                        });
                    }
                    else {
                        setInputError(reset_email, true);
                        makePopover(reset_email, "right", "show", c2ms(res.message));
                    }
                },
                complete: function()
                {
                    $("#btn_reset").attr("disabled", false);
                },
                error: function(e)
                {
                    makeModalAlert(c2ms('modal_title_notice'), c2ms('W_hasError4'));
                    console.log(e);
                }
            });

        });

        //-- user email 넣는 부분에 에 key 입력 시
        // 1) Enter
        // 2) 단순 글자
        reset_email.keypress(function(e){
            //Enter
            if(e.keyCode == 13){
                e.preventDefault();
                $("#btn_reset").click();
            }

        });
    });
}

/**************************
 * input Error 표시
 * set = true : Error 표시  / false : Error 없앰
 * tooltipText = tooltip에 표시될 text
 **************************/
function setInputError(input, set, tooltipText) {
    if(set == true) {
        input.parent().addClass("has-error");
        input.siblings("span").addClass("fa-warning");

        if(error_cnt == 1) {
            if(input.parent().data('bs.popover'))
                input.parent().data('bs.popover').options.content = tooltipText;
            makePopover(input.parent(), "right", "show", tooltipText);

            //무조건 보여주기 (toggle 때문에)
            input.parent().popover("show");
        }
    } else {
        input.parent().removeClass("has-error");
        input.siblings("span").removeClass("fa-warning");

        $("#"+input.parent().attr("aria-describedby")).remove();
    }
}

/********************
 * 이메일 정규 표현식 검사
 ********************/
function verifyName(text) {
    var oReg =  /^[a-zA-Z0-9+]{2,40}$/;

    return oReg.test(text);
}

function verifyEmail(email) {
    var regex=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

    return regex.test(email);
}

function verifyPassword(pw) {
    var num = pw.search(/[0-9]/g);
    var eng = pw.search(/[a-z]/ig);
    var spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);
    var result = true;

    if(pw.length < 8 || pw.length > 20){
        result = false;
    }

    if(pw.search(/₩s/) != -1){
        result = false;
    }

    if(num < 0 || eng < 0 || spe < 0 ){
        result = false;
    }

    return result;
}