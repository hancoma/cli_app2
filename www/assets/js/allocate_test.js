/*로딩 바 애니메이션*/
$(function(){

	$(".header").hide();

	var percentage = 0; 
	var AFC = "SGP";
	var country = "";
	
	var progress = setInterval(function(){
		var bar = $(".progress-bar");
		var max_width = $("#allocate_wrapper").width();

		if (bar.width() >= max_width) {
			clearInterval(progress);
		}
		else {
			bar.width( Math.round(bar.width() + (max_width/10)) );
		}
		percentage = Math.round(bar.width()/(max_width/100));
		if(percentage >= 50) clearInterval(progress);

		//1) Check the domain
		if(percentage == 10) 
			$("#reg_domain_name").find(".reg_domain_check").css("background", "url('/assets/img/addsite/check.png')");
		else if(percentage == 20) 
			$("#reg_domain_ip").find(".reg_domain_check").css("background", "url('/assets/img/addsite/check.png')");
		
		//2) IDC 할당
		//---> AFC 갯수가 더 늘어나면 
		//		- background image 수정 (afc 위치 넣기) 
		//		- left,top 위치 찾아서 아래 switch문에 추가해야만 합니다.

		else if(percentage == 30) {
			$("#allocate_loader1").fadeOut(500, function(){
				$("#allocate_loader2").fadeIn(500);
				var left = 0, top = 0;

				switch(AFC){
					case "TKO" : left = 702; top = 150; country=c2ms('C_japan'); break;
					case "CFN" : left = 229; top = 162; country=c2ms('C_california'); break;
					case "ORG" : left = 229; top = 140; country=c2ms('C_oregon'); break;
					case "SGP" : left = 638; top = 227; country=c2ms('C_singapore'); break;
					case "IRD" : left = 405; top = 85; country=c2ms('C_island'); break;
				}

				$(".pin").css({ "left" : left+"px", "top" : top+"px" }).show();
				$(".pulse").css({ "left" : left+"px", "top" : top+"px" }).show();

				$("#allocate_text").html(
                    "<span style='color:yellow;'><b>"
                    + country
                    + "</b></span> "
                    + c2ms('C_selectRegion')
                );
			});
		}

		else if(percentage == 50){
			$("#allocate_text").html(
				c2ms('C_selectRegion_traffic')
				+" <span style='color:yellow;'><b>["
				+ country
				+ "]</b></span>"
			);
		}

		//3) Set Server, DB (WAF, DDoS, Access Control, DB)
		else if(percentage == 70) {
			$("#allocate_loader2").fadeOut(500, function(){
				$("#allocate_loader3").fadeIn(500);
				$("#allocate_text").html(c2ms("C_setWAF"));
			});
		}
		else if(percentage == 80) {
			$("#allocate_text").html(c2ms("C_setDDoS"));
		}
		else if(percentage == 90) {
			$("#allocate_text").html(c2ms("C_setDB"));
		}

		//4) Complete
		else if(percentage == 100) {
			$("#allocate_text").html(c2ms("C_complete"));
			clearInterval(progress);
			//location.href='/domain/register/complete';
		}

		bar.text(percentage + "%");
	}, 1500);
});

/*var min = 1;
var max = 10;
setInterval(function(){
	var last_percentage = setPercentage(getRandomInt(min,max));
	min += 10;
	max += 10;

	if(last_percentage > 90){
		setPercentage(100);
		clearInterval();
	}
}, 1000);*//*
setPercentage(getRandomInt(0,10));
setPercentage(getRandomInt(11,20));
setPercentage(getRandomInt(21,30));
setPercentage(getRandomInt(31,40));
setPercentage(getRandomInt(41,50));
setPercentage(getRandomInt(51,60));
setPercentage(getRandomInt(61,70));
setPercentage(getRandomInt(71,80));
setPercentage(getRandomInt(81,90));
sleep(5000);
setPercentage(100);
})*/
