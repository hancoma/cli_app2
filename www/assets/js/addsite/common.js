/*
 * Add Site를 위한 javascript 
 * author		| Hyunjin Lee 
 * email		| hyunjinlee@pentasecurity.com
 * Released under Penta Security Systems. 2015-2016
 */
$(function(){


	var count = 0;
	
	var $btn_addsite = $("#btn_addsite");
	var $addsite_url_wrap = $("#addsite_url_wrap");
	var $site_url = $("input[name='user_domain']");
	var $lb_site_url = $("label[cb-action='url_check']");

	var using_https;
	var using_ssl;

    $(document).on("click", "[cb-action='domain_check']", function(){
        var reg_domain = $.trim( $site_url.val().toLowerCase() );

        if(!checkFormField(reg_domain)) return false;
        if(!addsite_www_check(reg_domain)) return false;

        // 폼값 셋팅
        $("form[name='checkDomainForm']").submit();

    });

    $("#user_domain").keydown(function (e){
        if (e.keyCode == 13){
            var reg_domain = $.trim( $site_url.val().toLowerCase() );

            if(!checkFormField(reg_domain)) return false;
            if(!addsite_www_check(reg_domain)) return false;

            // 폼값 셋팅
            $("form[name='checkDomainForm']").submit();

        }
    });

    // 폼필드 유효성 검사
    function checkFormField(domain){

        if( !domain || domain == ""){ //빈 값

            makeModalAlert(c2ms('modal_title_warning'), c2ms('domain_empty'));
            return false;

        } else if( !confirmValidUrl(domain) ){

            makeModalAlert(c2ms('modal_title_warning'), c2ms('domain_invalid'));
            return false;
        } else {

            //URL 제대로 표시. protocol이 들어가 있으면 삭제하기
            if(domain.match("http\:\/\/") != null) domain = domain.replace("http://", "");
            else if(domain.match("https\:\/\/") != null) domain = domain.replace("https://", "");

            //마지막에 / 가 들어가 있으면 삭제하기
            if(domain.charAt(domain.length -1) == '/') domain = domain.slice(0, -1);

            //input:text에 적용
            $site_url.val(domain);
            $("input:hidden[name='register_domain']").val(domain);


            return true;
        } // end if
    }

    /**
     * www 도메인 체크
     * @param domain
     */
    function addsite_www_check(reg_domain) {
        // www 도메인 인지 확인 후에 맞다면 모달창 띄운다 아니면 바로 addsite_process 진행
        // 모달창에서 네이키드까지 같이 등록하겠다고 하면 dom value를 www 제외한 네이키드 도메인으로 바꿈
        // www 도메인만 등록한다면 그대로 진행

        var check_www_pattern = /^www\./;
        var checkWWW = check_www_pattern.test(reg_domain);

        if (checkWWW) {
            var replace_description = $(".www_description").html().replace(/www\.cloudbric\.com/, reg_domain);
            $(".www_description").html(replace_description);
            $("#wwwCheckContinueModal").modal({'backdrop':'static'}).modal('show');
            $('#btn_naked_continue').click(function(){
                $("input[name='user_domain']").val(reg_domain.replace(check_www_pattern, ""));
                $("#wwwCheckContinueModal").modal('hide');
                return false;
            });

            $('#btn_naked_cancel').click(function(){
                $("#wwwCheckContinueModal").modal('hide');
                addsite_process(reg_domain);
            });
        } else {
            addsite_process(reg_domain);
        }
    }

    /**
     * 도메인 체크
     * @param domain
     */
    function addsite_process(reg_domain) {
        var result = "";
        var $c2ms_key = "";
        var url = "/addsite/domainCheck";
        $.ajax({
            type:"POST",
            url:url,
            data:{
                "user_domain" : reg_domain
            },
            dataType:"json",
            beforeSend:function(){

                $lb_site_url.find(".addsite_status_img").html("<img src='/assets/img/loading.gif'/>");
                $btn_addsite.attr("value", c2ms("B_checkUrl")+"...").prop("disabled", true);
            },
            success:function(res){
                result = res.result;
                $c2ms_key = res.c2ms_key;

                //error난 경우
                if(res.result == 'error'){
                    makeModalAlert(c2ms('modal_title_warning'), c2ms($c2ms_key));
                    return false;
                }
                //success
                else{
                    //alert(c2ms(res.message));
                    window.location.href='/addsite/region';
                }

            },
            complete:function(){
                //loading image 없애기
                $lb_site_url.find(".addsite_status_img").html('');
                //버튼 다시 원래대로 해놓기
                $btn_addsite.attr("disabled", false).val(c2ms("B_addSite"));
            },
            error:function(e){
                //loading image 없애기
                $lb_site_url.find(".addsite_status_img").html("");
                //버튼 다시 원래대로 해놓기
                $btn_addsite.attr("disabled", false).val(c2ms("B_addSite"));
                console.log(e);
            }

        });	// end ajax

        return (result == "success")? true:false;
    }



    $(document).on("click", "[cb-action='region_next']", function(){
        regionProcess();
    });

    function regionProcess(){

        var selectBox = document.getElementById('webServerIPs');
        if(selectBox.selectedIndex < 0){
            makeModalAlert(c2ms('modal_title_warning'), c2ms('addsite_region_ip'));
            return false;
        }
        var selected_ip = selectBox.options[selectBox.selectedIndex].value,
            i;
        var ips = [];
        for(i = 0; i < selectBox.options.length; i++) {
            ips[i] = selectBox.options[i].value;
        }


        if(selected_ip == ''){
            makeModalAlert(c2ms('modal_title_warning'), c2ms('addsite_region_ip'));
            return false;
        }

        //ip가 1개 초과일 경우만 multi_ip를 사용
        if( selectBox.options.length > 1 ){
            selected_ip = selectBox.options[selectBox.selectedIndex].value;
        }else{
            ips = '';
        }

        var selected_afc_code = $('[class="selected"]').attr('afc_code');
        var cname = $("input[name='cname']").val();
        var webserver_type = document.getElementById('webserver_type').value;
        if(webserver_type == 'cname'){
            selected_ip = document.getElementById('cname_ip').value;

            var cnameBox = document.getElementById('cnameBox').value;
            if(cnameBox == ''){
                makeModalAlert(c2ms('modal_title_warning'), c2ms('addsite_region_ip'));
                return false;
            }
            if(selected_ip == ''){
                makeModalAlert(c2ms('modal_title_warning'), c2ms('addsite_region_ip'));
                return false;
            }
        }

        var url = "/addsite/region_process";
        var data = {
            "selected_ip": selected_ip,
            "ip_multi": ips,
            "cname": cname,
            "webserver_type": webserver_type,
            "afc_code": selected_afc_code
        }

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function (data) {
                window.location.href='/addsite/ssl';
            },
            complete: function () {
            },
            error: function (e) {
                console.log(e);
            }
        });

    }


    $(document).on("click", "[cb-action='ssl_next']", function(){
        sslProcess();
    });

    $(document).on("click", "[cb-action='ssl_no_next']", function(){
        sslUsingNoProcess();
    });


    function sslUsingNoProcess(){
        var $ssl_status, $ssl_type, $ssl_termination;
        $ssl_status = 'E';
        $ssl_type = 'LH01';
        $ssl_termination = 'E';

        var url = "/addsite/ssl_process";
        var data = {
            "ssl_status": $ssl_status,
            "ssl_type": $ssl_type,
            "ssl_termination": $ssl_termination
        }
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function (res) {
                if(res.result == 'error'){
                    makeModalAlert(c2ms('modal_title_warning'), c2ms(res.c2ms_key));
                    //window.location.href = "/mysites";
                    return false;
                }else{
                    if(res.url == 'complete'){
                        window.location.href = "/addsite/complete";
                        return;
                    }
                    if($ssl_type == 'LH01'){
                        window.location.href = "/addsite/dns";
                    }
                }

            },
            complete: function () {
            },
            error: function (e) {
                console.log(e);
            }
        });


    }


    function sslProcess(){

        var $ssl_status, $ssl_type, $checked_ssl_mode;
        var $checked_ssl_using = $(":input[name='choose-ssl-using']:radio:checked").val();

        if($checked_ssl_using ==null){
            makeModalAlert(c2ms('modal_title_warning'), c2ms('is_my_ssl'));
            return;
        }

        if($checked_ssl_using == 'no'){
            $ssl_status = 'E';
            $ssl_type = 'LH01';
        }else if($checked_ssl_using == 'yes'){
            $ssl_status = 'N';
        }else{
            //makeModalAlert('Notice', c2ms("?"));
            return;
        }


        $checked_ssl_mode = $(":input[name='choose-ssl-mode']:radio:checked").val();
        if($checked_ssl_using == 'yes'){
            if($checked_ssl_mode == null){
                makeModalAlert(c2ms('modal_title_warning'), c2ms('addsite_ssl_title'));
                return;
            }
        }else{
            var modal = $('#sslUsingNoNextRequestModal');
            modal.modal('show');

            return;
        }

        if($checked_ssl_mode == 'no'){
            $ssl_type = 'LH01';
        }else if($checked_ssl_mode == 'yes'){
            $ssl_type = 'LD01';
        }

        var url = "/addsite/ssl_process";
        var data = {
            "ssl_status": $ssl_status,
            "ssl_type": $ssl_type
        }
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType: "json",
            success: function (res) {
                if(res.result == 'error'){
                    makeModalAlert(c2ms('modal_title_warning'), c2ms(res.c2ms_key));
                    //window.location.href = "/mysites";
                    return false;
                }else{
                    if(res.url == 'complete'){
                        window.location.href = "/addsite/complete";
                        return;
                    }

                    if($ssl_type == 'LD01') {
                        window.location.href = "/addsite/manual";
                    }else if($ssl_type == 'LH01'){
                        window.location.href = "/addsite/dns";
                    }
                }

            },
            complete: function () {
            },
            error: function (e) {
                console.log(e);
            }
        });

    }

});

/*
 * Url인지 아닌지 검사
 * 일단 무조건 true로 보내고 추후 유효성검사 추가가 필요하면 이 함수를 이용하자
 */
function confirmValidUrl(str) {
    var pattern = new RegExp('^(https?:\\/\\/)?'+ // 프로토콜
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // 도메인명 <-이부분만 수정됨
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // 아이피
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // 포트번호
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // 쿼리스트링
        '(\\#[-a-z\\d_]*)?$','i'); // 해쉬테그들

    if(!pattern.test(str)) return false;
    else return true;
}

$("#btn_txt_record_added").click(function(){
    $('#txtCheckRequestModal').modal('show');
    //makeModalAlert('Info', c2ms('아직 TXT정보가 업데이트 되지 않았습니다 확인해주세요'));
    //window.location.href = "/mysites";
    //txt_record_check();
});

$("#btn_switch_ssl_auto").click(function(){
    $("#txtCheckContinueRequestModal").modal('show');
});

$("#btn_txt_check_continue").click(function(){
    window.location.href = "/addsite/dns/"+domain_idx;
});

$("#btn_txt_check_cancel").click(function(){
    $("#txtCheckContinueRequestModal").modal('hide');
});

$("#btn_txt_check_next").click(function(){

    window.location.href = "/addsite/dns";
});

$("#btn_txt_okay").click(function(){

    window.location.href = "/mysites";
});




