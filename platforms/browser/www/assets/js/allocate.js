$(function(){
	/*layout*/
	$(".header").hide();
	$(".footer").hide();
	$(".content_wrapper").css("minHeight", $(window).outerHeight());
	$(window).resize(function(){
		$(".content_wrapper").css("minHeight", $(window).outerHeight());
	});

	//변수 선언
	bar = $(".progress-bar");
	max_width = $("#allocate_wrapper").width();	

	$.init_allocate();

});

function setPercentage(percentage){
	bar.width( Math.round(max_width*(percentage/100)));
	bar.text(percentage + "%");

	return percentage;
}
function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}

$.extend({

	/**********************************
	* init
	*	- proximateIDC
	*	- allocateToService
	*		- afc, pron, route53
	**********************************/
	"init_allocate" : function(){
		//1) Check the domain
		setPercentage(getRandomInt(0, 5));
		$("#reg_domain_name")
			.find(".reg_domain_check").css("background", "url('/assets/img/addsite/check.png')");
		
		var cnt = 0;
		setInterval(function(){
			switch( ++cnt ) {
				case 1 : 
					$("#reg_domain_ip")
						.find(".reg_domain_check")
						.css("background", "url('/assets/img/addsite/check.png')");
					setPercentage(getRandomInt(6, 10)); 
					break;
				case 2 : 
					$.proximateIDC(); 
					clearInterval(); 
					break;
			}
		},1000);

	},


	/**********************************
	* Search proximate IDC
	**********************************/
	"proximateIDC" : function(){
		var url = "/domain/register_backend/proximateIdc";
		var json_data = {
			"host_name"	: host_name,
			"target_ip"	: target_ip,
			"user_idx"	: user_idx
		}; 
		$.ajax({
			type:"POST",
			url:url,
			data:json_data,
			dataType:"json",
			beforeSend:function(){
				$("#allocate_loader1").fadeOut(500, function(){
					$("#allocate_loader2").fadeIn(500);
					setTimeout(setPercentage(getRandomInt(11, 25)), 1000);
				});
			}, 

			success:function(res){

				//--- idc 없을때 - 도메인 정보 이상하다 판단됨.
				if(res.proximate_region == undefined || res.proximate_region == null || !res.proximate_region){

					//modal message 출력
					//makeModalAlert('Notice', c2ms("W_error"));
					alert(c2ms("W_error") + "[idc]");
					
					window.history.back(-1);

				//--- success
				}else{

					var AFC = "IRD";
					var country = "";
					var left = 0, top = 0;

					proximate_idc = res.proximate_region;

					switch(proximate_idc){
						case "TKO" : left = 702; top = 150; country=c2ms('C_japan'); break;
						case "CFN" : left = 229; top = 162; country=c2ms('C_california'); break;
						case "ORG" : left = 229; top = 140; country=c2ms('C_oregon'); break;
						case "SGP" : left = 638; top = 227; country=c2ms('C_singapore'); break;
						case "IRD" : left = 405; top = 85; country=c2ms('C_island'); break;
					}

					$(".pin").css({ "left" : left+"px", "top" : top+"px" }).show();
					$(".pulse").css({ "left" : left+"px", "top" : top+"px" }).show();

					$("#allocate_text").html(
                        "<span style='color:yellow;'><b>'"
                        + country
                        + "'</b></span> "
                        + c2ms('C_selectRegion')
                     );

					var cnt = 0;
					var time = 3000;
					setInterval(function(){
						switch( ++cnt ){
						case 1 : 
							setPercentage(getRandomInt(26, 30)); 
							$("#allocate_text").html(
								c2ms('C_selectRegion_traffic')
								+" <span style='color:yellow;'><b>["
								+ country
								+ "]</b></span>"
							);
							break;
						case 2 : 
							setPercentage(getRandomInt(31, 50)); 
							// go to next - allocate to service
							$.allocateToService();
							clearInterval();
							break;
						}
					},3000);
				}

			},

			error:function(e){
				//console.log(e);
				//makeModalAlert('Notice', c2ms('W_error'));
				alert(c2ms("W_error") + " [idc - connection]");
				window.history.back(-1);
			}
		}); 
	}, // --- end proximateIDC


	/**********************************
	* Allocate host_name to AFC & route53
	**********************************/
	"allocateToService" : function(){
		var url = "/domain/register_backend/allocateToService";
		var json_data = {
			"host_name"	: host_name,
			"target_ip"	: target_ip,
			"user_idx"	: user_idx,
			"proximate_idc"	: proximate_idc,
		};
		$.ajax({
			type:"POST",
			url:url,
			data:json_data,
			dataType:"json",
			beforeSend:function(){
				$("#allocate_loader2").fadeOut(500, function(){
					$("#allocate_loader3").fadeIn(500);
					$("#allocate_text").html(c2ms("C_setWAF"));
				});
			}, 

			success:function(res){ //console.log(res);

				//--- success
				if(res["result"]){

					var cnt = 0;
					setInterval(function(){
						switch (++cnt) {
							case 1 :
								$("#allocate_text").html(c2ms("C_setDDoS"));
								setPercentage(getRandomInt(51, 70));
								break;
							case 2 :
								$("#allocate_text").html(c2ms("C_setDB"));
								setPercentage(getRandomInt(71, 85));
								break;
							case 3 :
								$("#allocate_text").html(c2ms("C_complete"));
								setPercentage(100);
								location.href='/domain/register/complete';
								clearInterval();
								break;
						}
					},1000);

					//complete 으로 이동
					//window.location.replace("/domain/register/complete"); 
				
				//--- fail
				}else{
					//modal message 출력
					//makeModalAlert('Notice', c2ms("W_error"));
					alert(c2ms("W_error") + " [allocate]");

					window.history.back(-1);
				}

			},

			error:function(e){
				console.log(e);
				//makeModalAlert('Notice', c2ms("W_error"));
				alert(c2ms("W_error") + " [allocate-connection]");

				window.history.back(-1);
			}
		}); 

	}, //--- end allocateToService


	/**********************************
	* Route53 할당
	**********************************/
	"allocateToRoute53" : function(){

	} //--- end allocateToRoute53

}); //--- end extend
