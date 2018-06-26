(function(){
    var events = (function(){
        function initialize() {
            toggleCouponform();
        }

        function toggleCouponform() {
        	var form = document.getElementById('couponForm'),
				openButton = document.getElementById('enterCouponButton'),
				closeButton = document.getElementById('closeCouponButton');

        	openButton.addEventListener('click', function(){
                this.style.display = 'none';
                form.style.display = 'block';
        	});

            closeButton.addEventListener('click', function(){
                form.style.display = 'none';
                openButton.style.display = 'block';
        	});
		}

        return {
            init: initialize
        }
    }());

    return (function(){
        events.init();
    }());
}());


$(function(){
	/**************************
     * Coupon 등록 hide & show
     ***************************/
	var $buttonVerifyProfile	= $("#btn_modify_profile"),
		$buttonVerifyCoupon		= $("#btn_verify_coupon");

	//--Coupon 등록
	$buttonVerifyCoupon.click(function(){
		var coupon_number = $("#coupon_number").val().trim().toUpperCase();

		if(!coupon_number || coupon_number === '') {
            makeModalAlert(c2ms('modal_title_notice'), c2ms('coupon_value_empty'));
            return false;
		}
		
		$.ajax({
			url: '/myaccount/verify_coupon',
			type: 'post',
			data: {
				"coupon_number" : coupon_number
			},
			beforeSend: function(){
				$buttonVerifyCoupon.prop("disabled", true);
			},
			success: function(res){
				$buttonVerifyCoupon.prop("disabled", false);
				res = JSON.parse(res);

				if(res.result == "success"){

					//coupon 등록 상태로 변경
					$("#register_coupon").hide();
					$("#pre_coupon_number").val(coupon_number);
					$("#pre_register_coupon").show();
	
				}else if(res.result == "fail"){
					makeModalAlert(c2ms('modal_title_notice'), c2ms('coupon_value_valid'));
				}
			},
			error: function(e){
				console.log(e);
				makeModalAlert(c2ms('modal_title_notice'), c2ms('coupon_error'));
			}
		});
	});

	/**************************
	 * Update User Profile
	 *************************/
	$buttonVerifyProfile.on('click', function(){
		var user_name = $("#user_name").val().trim();
        var coupon_number = $("#coupon_number").val().trim().toUpperCase();
		
		if(!user_name || user_name === '') {
            makeModalAlert(c2ms('modal_title_notice'), c2ms('user_name_empty'));
            return false;
		} else {
            $.ajax({
                url	 : '/myaccount/update_user',
                type : 'post',
                data : {
                    "user_name"	: user_name,
                    "coupon_number" : coupon_number
                },
                beforeSend: function(){
                    $buttonVerifyProfile.prop("disabled", true).val("Load...");
                },
                success: function(res){
                    res = JSON.parse(res);

                    $("#user_name").val(res.db_user_name);
                    $buttonVerifyProfile.prop("disabled", false).val(c2ms('save_btn'));
                    if(res.result == "success") {
                        makeModalAlert(c2ms('modal_title_notice'), c2ms('update_user_success'));
                    }
                },
                error: function(e){
                    console.log(e);
                }
            });
        }
	});
	
	/**************************
	 * Change Password
	 *************************/
    var $tabChangePassword 	= $('#tabChangePassword'),
        $inputPassword 		= $tabChangePassword.find('input[type="password"]'),
        $_inCurrent		 	= $inputPassword.filter('[name="current_pwd"]'),
        $_inNew		 		= $inputPassword.filter('[name="new_pwd"]'),
        $_inConfirm 		= $inputPassword.filter('[name="new_repwd"]');

    $('#modifyButton').on('click', function(){
        var _inpurError = false;

        $inputPassword.each(function(){
            if(!_inpurError && (!this.value || this.value === '')) {
                _inpurError = true;
                makeModalAlert(c2ms('modal_title_notice'), c2ms(this.dataset.verify));
                return false;
            }
        })

        if($_inNew.val() && !verifyPassword($_inNew.val())) {
            makeModalAlert(c2ms('modal_title_notice'), c2ms('modal_invalid_password'));
            makePopover($_inNew.parent(), "right", "show", c2ms('invalid_password_tip'));
        } else {
            $("#" + $_inNew.parent().attr("aria-describedby")).remove();
        }

        if(!_inpurError && ($_inNew.val() !== $_inConfirm.val())) {
            makeModalAlert(c2ms('modal_title_notice'), c2ms('not_match_password'));
            return false;
        }

        if(!_inpurError) {
            $.ajax({
                url	 : '/myaccount/change_pw',
                type : 'post',
                data : {
                    "cur_pw" : $_inCurrent.val(),
                    "new_pw" : $_inNew.val()
                },
                beforeSend: function(){
                    $("#modifyButton").prop("disabled", true).val("Load...");
                },
                success: function(res){
                    res = JSON.parse(res);

                    $("#modifyButton").prop("disabled", false).val(c2ms('save_btn'));
                    if(res.result == "success") {
                        makeModalAlert(c2ms('modal_title_notice'), c2ms('update_user_success'));
                        $("input:password").val("");
                    }else if(res.result == "fail") {
                        makeModalAlert(c2ms('modal_title_notice'), c2ms('password_incorrect'));
                    }
                },
                error: function(e){
                    console.log(e);
                }
            });
        }
    });

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
});
