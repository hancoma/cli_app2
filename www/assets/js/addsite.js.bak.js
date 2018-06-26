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
	var $site_url = $("input[name='domain']");
	var $lb_site_url = $("label[cb-action='url_check']");

	var using_https;
	var using_ssl;

	$(document).on("click", "[cb-action='check']", function(){
		var reg_domain = $.trim( $site_url.val() );

		/*
		 * url 유효성 검사
		 */
		if( !reg_domain || reg_domain == ""){ //빈 값
			
			//error 표시
			$lb_site_url.find("i").addClass("fa-warning");
			$lb_site_url.addClass("has-error");
			makePopover($lb_site_url.find("i"), "right", "show", c2ms("W_emptyUrl"));
			return false;

		}else if( !confirmValidUrl(reg_domain) ){

			//error 표시 
			$lb_site_url.find("i").addClass("fa-warning");
			$lb_site_url.addClass("has-error");
			makePopover($lb_site_url.find("i"), "right", "show", c2ms("W_notUrl"));
			return false;

		}else{

			//error 표시 삭제
			$lb_site_url.removeClass("has-error");
			$lb_site_url.find("i").removeClass("fa-warning");
			//popover 없애기 (destroy는 적절하지 못하다.)
			$("#"+$lb_site_url.find("i").attr("aria-describedby")).remove();

			/*
			 * URL 제대로 표시
			 */
			//protocol이 들어가 있으면 삭제하기
			if(reg_domain.match("http\:\/\/") != null) reg_domain = reg_domain.replace("http://", "");
			else if(reg_domain.match("https\:\/\/") != null) reg_domain = reg_domain.replace("https://", "");

			//마지막에 / 가 들어가 있으면 삭제하기
			if(reg_domain.charAt(reg_domain.length -1) == '/') reg_domain = reg_domain.slice(0, -1);

			//input에 적용
			$site_url.val(reg_domain);
			$("input:hidden[name='register_domain']").val(reg_domain);
			
			/*
			 * ip 확인 후, 정보 띄우기
			 */
		    var url = "/domain/register_backend/getDomainInfo";
			$.ajax({
				type:"POST",
				url:url,
				data:{
					"domain" : reg_domain
				},
				dataType:"json",
				beforeSend:function(){
					using_https = false;

					$lb_site_url.find("img").attr("src","/assets/images/loading.gif");
					$btn_addsite.attr("value", c2ms("B_checkUrl")+"...").prop("disabled", true);
				}, 
				success:function(res){ console.log(res);

					if(res["result"] == "error")
					{
						var error_message = res["message"];

						//modal message 출력
						makeModalAlert('Notice', c2ms(error_message));

						//loading image 없애기
						$lb_site_url.find("img").attr("src", "");

						//버튼 다시 원래대로 해놓기
						$btn_addsite.attr("disabled", false).val(c2ms("B_addSite"));
					}
					else
					{
						//https 사용 여부 true false로 변수에 넣기
						using_https = res.using_https;

						/* ssl 사용 정보 변수에 넣기 
						 * - already		: true,false (이미 ssl에 등록되었는가)
						 * - expire_date	: 2016-04-01 (무료 ssl 만료일)
						 * - d-day			: 27 (남은 잔여일 수) */
						using_ssl = res.using_ssl;

						//url 못바꾸게 하기 (x 버튼 누를 때만 바꿀 수 있게 하기)
						$site_url.attr("disabled", true);
						$lb_site_url
							.find("i")
							.addClass("fa-times-circle")
							.click(function(){ 
								//*** x 버튼 누르면 사이트 검사 전으로 원상 복귀
								$site_url.val("").attr("disabled", false).focus(); //site_url 초기화
								$(this).removeClass("fa-times-circle"); // x 버튼 없애기
								$lb_site_url.find("img").attr("src", ""); //label 이미지 초기화
								$btn_addsite.val(c2ms("B_addSite")); //add site 버튼 초기화
								$("#check_site").slideUp(500); // ip 정보 초기화
								$btn_addsite.attr("cb-action", "check"); //cb-action 에서 add를 check로 변경
							});

						$lb_site_url.find("img").attr("src", "/assets/images/addsite/check.png");

						var target_ip 		= res["records"]["A"]["0"]["ip"];
						var is_root_domain 	= res["records"]["is_root_domain"];
						var root_domain 	= res["records"]["root_domain"];

						var $check_ip 		= $("input[cb-action='addsite_ip']");

						var hidden_value 	= "<input type='hidden' name='is_root_domain' value='"+is_root_domain+"'>";
						hidden_value 		+= "<input type='hidden' name='root_domain' value='"+root_domain+"'>";

						$check_ip.val(target_ip);
						$("#check_site").append(hidden_value).slideDown(1000);

						//btn -> text 를 NEXT 로 바꾸고, cb-action을 add 로 바꾸기
						$btn_addsite.prop("disabled", false).val(c2ms("B_next")).attr("cb-action", "add");

						// reset ip
						$("[cb-action='reset']").click(function(){
							$check_ip.val(target_ip);
						});
					}

				},

				error:function(e){
					console.log(e);
					//loading image 없애기
					$lb_site_url.find("img").attr("src", "");

					//버튼 다시 원래대로 해놓기
					$btn_addsite.attr("disabled", false).val(c2ms("B_addSite"));
				}

			});	// end ajax
		} // end if 
	});	// end click

	/**********************
	 * SSL 사용 여부 물어보기
	 **********************/ 
	$(document).on("click", "[cb-action='add']" ,function(){

		var reg_domain = $.trim( $site_url.val() );
		var ssl_text = "";

		/*이미 ssl이 등록된 고객인 경우*/
		if(using_ssl != "" && using_ssl.already == true){
			$("form[name='reg_form']")
				.attr("action", "/domain/register/register_setdomain")
				.submit();
		}
		/*** http 고객인 경우 ***/
		else if(!using_https)
		{
			ssl_text =  c2ms('C_httpTitle') + "<br/><br/>" + c2ms("C_httpSubtitle");

			$("#modal_ssl_body").html(ssl_text);
			$("#modal_ssl").modal('show');

		}
		/*** https 사용하는 고객인 경우 ***/
		else if(using_https)
		{
			ssl_text_top =  c2ms('C_httpsTitle')+"<br/><br/>"+c2ms("C_httpsSub1")+"<br/><br/>";
			ssl_text_img = "<img src='/assets/images/settings/ssl_help_full.gif' style='display:block;margin:0 auto;'/><br/><br/>";
			ssl_text_mid = c2ms("C_httpsSub2")+"<br/><br/>";
			ssl_text_btm = "<a href='#' id='btn_ssl_email' class='a_hovergrey' style='text-decoration:underline; font-weight:bold;'>> "+c2ms('C_httpsEmail')+"</a><br/><br/>"
						 + c2ms('C_httpsSub3');

			$("#modal_ssl_body").html(ssl_text_top + ssl_text_img + ssl_text_btm);
			$("#modal_ssl").modal('show');

			/***email로 등록하기 눌렀을 때***/
			$("#btn_ssl_email").click(function(){

				$("#modal_ssl").modal('hide');
				$("#modal_email").modal('show');

				//-- SSL 인증할 email
				$.ajax({
					url: "/domain/register_backend/get_san_verification",
					type: "POST",
		            data: {
		            	"process"			: "1",
						"host_name"			: reg_domain,
						"ssl_verify_mode" 	: "e"
		            },
		            dataType:"json",
		            beforeSend: function(){
		            	//loading 이미지 보여주기
		            	$("#modal_email_body_checking").show();
		                $("select[name='ssl_email']").hide();

		                //"다음" 버튼 비활성화 및 초기화
		                $("#btn_modal_email_okay").prop("disabled", true).text(c2ms("B_next"));
		            },
					success: function(res)
		            { console.log(res);
		            	//"다음"버튼 활성화
		                $("#btn_modal_email_okay").prop("disabled", false);
		                //loading 이미지 없애고 select 보여주기
		                $("#modal_email_body_checking").hide();
		                $("select[name='ssl_email']").show();

		                if(res.result === "success") {
		                	//select 초기화 및 이메일 리스트 출력
		                	$("select[name='ssl_email']").html('');
		                	for(var i=0; i<res.item.length; i++){

		                		var ssl_email = res.item[i].ApproverEmail;

		                		$("select[name='ssl_email']")
									.append( $("<option />").val(ssl_email).text(ssl_email) );
		                	}

		                }
		                else {
		                	$("#modal_email").modal('hide');
		                	console.log(res);
		            		makeModalAlert('Notice', c2ms("W_hasError"));
		                }
		            },
		            error: function(e)
		            {
		            	//"다음"버튼 활성화
		                $("#btn_modal_email_okay").prop("disabled", false);
		                
		            	$("#modal_email").modal('hide');
		            	makeModalAlert('Notice', c2ms("W_hasError"));
		                console.log(e);
		            }
				});

				//-- [ Prev 버튼 ] 이전 단계 (meta tag 선택으로 돌아가기)
				$("#btn_modal_email_prev").click(function(){
					$("#modal_email").modal('hide');
					$("#modal_ssl").modal('show');
				})

				//-- [ Okay 버튼 ] email 보내기
				$("#btn_modal_email_okay").click(function(){

					$.ajax({
						url: "/domain/register_backend/req_san_email",
						type: "POST",
			            data: {
							"host_name"		: reg_domain,
							"target_email" 	: $("select[name='ssl_email']").val()
			            },
			            dataType:"json",
			            beforeSend: function(){
			            	//"다음" 버튼 비활성화 및 확인 중으로 변경
			                $("#btn_modal_email_okay")
			                	.prop("disabled", true)
			                	.text(c2ms('B_checkUrl')+"..");
			            },
						success: function(res)
			            { 
			            	if(res.result == "success") {

			            		// email 방식으로 SSL 등록 => Animation으로 바로 이동
								$("form[name='reg_form']")
									.attr("action", "/domain/register/register_setdomain")
									.submit();

			            	}else{
			            		$("#modal_email").modal('hide');
			            		console.log(res);
			            		makeModalAlert('Notice', c2ms("W_hasError"));
			            	}
			            },
			            error: function(e)
			            {
			            	$("#modal_email").modal('hide');
			            	makeModalAlert('Notice', c2ms("W_hasError"));
			                console.log(e);
			            }
					});
				});
			});
		}


		/***************************************
		 * META Tag 전달 => Animation으로 바로 이동
		 ***************************************/
		$("#btn_modal_ssl_okay").click(function(){ 

    		$("form[name='reg_form']")
				.attr("action", "/domain/register/register_setdomain")
				.submit();

		});
	});
	

	$site_url.keyup(function(e){ 

		//error 표시 있으면 삭제
		$lb_site_url.removeClass("has-error");
		$lb_site_url.find("i").removeClass("fa-warning");
		//popover 없애기 (destroy는 적절하지 못하다.)
		$("#"+$lb_site_url.find("i").attr("aria-describedby")).remove();

		//site url 넣은 후, Enter
		if(e.which == 13) $btn_addsite.click(); 
	});

	
}); // [cb-action='add'] --> SSL 등록 여부 물어보기

/*
 * Url인지 아닌지 검사 
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