(function(window, undefined){
	function initialize() {
		var setSitePath = (function(window, sitePath){
            var path = window.location.pathname,
				pathArray = path.split( '/' );

            sitePath.getPath = function() {
                return path;
            };

            sitePath.getPathArray = function() {
                return pathArray;
            };

            sitePath.getPathIndex = function(arrayIndex) {
                return pathArray[arrayIndex];
            };
        }(window, window.sitePath = window.sitePath || {}));

		var setLoadingSpinner = (function(){
		    var requestXhr = false;

            sitePath.getPathIndex(1) !== 'dashboard' && !function(){
                var XMLHttpRequestOpen = window.XMLHttpRequest.prototype.open,
                    XMLHttpRequestSend = window.XMLHttpRequest.prototype.send;

                function openReplacement(method, url, async, user, password) {
                    requestXhr = true;
                    return XMLHttpRequestOpen.apply(this, arguments);
                }

                function sendReplacement(data) {
                    var thisLoadStart = this.onloadstart || false,
                        thisLoadend = this.onloadend || false,
                        loadSpinner = document.getElementById('loading');

                    this.onloadstart = function() {
                        thisLoadStart && thisLoadStart.call(this);
                        loadSpinner.className = 'content_loading';
                    };

                    this.onloadend = function() {
                        thisLoadend && thisLoadend.call(this);
                        loadSpinner.className = 'content_loading hide_loading';
                    };

                    return XMLHttpRequestSend.apply(this, arguments);
                }

                window.XMLHttpRequest.prototype.open = openReplacement;
                window.XMLHttpRequest.prototype.send = sendReplacement;
            }();

            window.onload = function() {
                !requestXhr && (function() {
                    var loadSpinner = document.getElementById('loading');
                    loadSpinner.className = 'content_loading hide_loading';
                }());
            };
        }());
	}

	initialize();
}(window));


/*demo용 사이트*/
//var demo_list = ["585", "586", "588", "589", "592", "593"];

$(window).on('load', function(){
	// 테스트 중.
    // Less 컴파일로 DOM 트리가 생성되자 마자 스타일 적용에 딜레이가 생기기 때문에 footer의 높이 값을 바로 구할 수 없으므로 window.onload를 통해 모든 DOM이 호출된 후에 높이 값 적용.
    // $(window).on('load', handler) > $(document).on('ready', handler)

    //$(".content_wrapper").css('position',"relative"); .content_wrapper의 속성 position에 relative를 적용할 경우 /dashboard 페이지에서 데이터들의 툴팁이 가려지는 현상 발견으로 임시 주석.
    $(".footer").css('position',"relative");

    var main = $(window).height() - $(".footer").height();
    $(".content_wrapper").css('min-height', main);

    $(window).on('resize', function(){
        var main = $(window).height() - $(".footer").height();
        $(".content_wrapper").css('min-height', main);
	});

	/* 테스트 중

    $(".content_wrapper").css('position',"relative");
    $(".footer").css('position',"relative");

    function windowHeightConfig() {
    	var ddd = $('.wrap').height() - $(window).height();
    	var main = ($(window).height()+5) - $(".footer").height();

    	if(ddd > 0 && $('.wrap').height() !== $(window).height()) main = main - ddd;

    	$(".content_wrapper").css('min-height', main);

    	console.log($('.wrap').height() + ' ' + $(window).height() + ' ' + main + ' ' + ddd)
    }

    //windowHeightConfig();
    $(window).on('resize', windowHeightConfig);
    */
});


$(function() {
	_init();

	function _init()
	{
        //--IE 버전 체크
        // (8 아래일 때는 접속 못하게- 문제 : 7 아래는 출력하지 못함)
        if(get_version_of_IE() !== "N/A"){
            var verIE = get_version_of_IE().substring(0, 1) * 1;
            //8 버전 아래 체크
            if( verIE < 9 && verIE > 4){
                location.href='https://www.cloudbric.com';
                return false;
            }
        }

		//--header
		setFixedHeader();
		setHeaderScroll();
		setImageHover($(".header"));

		//--Top button
		setTopButton();

		//--footer
		setImageHover($(".footer"));

		//--Animation
		setAnimation();
	}

	/**********
	 * Header *
	 **********/
	function setHeaderScroll()
	{
		/*var $this = $("#header_bottom_left").find(".dropdown-menu");
		if ($this.length > 0){
			var siteHeight = $this.outerHeight();
			var siteMaxHeight = $this.find(".menu").css("max-height").replace("px", "") * 1;
			console.log(siteHeight); console.log(siteMaxHeight);
			if (siteHeight >= siteMaxHeight)
				setScroll($this.find(".menu"), "225px");
		}*/
		//less 때문에 바꿈
        var $this = $("#header_bottom_left").find(".menu").find("li");
        if ($this.length > 0){
            var siteCount = $this.length;
            var siteMaxLength = 7;
            if (siteCount >= siteMaxLength)
                setScroll($("#header_bottom_left").find(".menu"), "225px");
        }
	}

	/*******************************************
	 * Top Button 							   *
	 * @ http://codepen.io/rdallaire/pen/apoyx *
	 *******************************************/
	function setTopButton(){
		// ===== Scroll to Top ====
		$(window).scroll(function() {
		    if ($(this).scrollTop() >= 50) {
		        $('#return-to-top').fadeIn(200);
		    } else {
		        $('#return-to-top').fadeOut(200);
		    }
		});
		$('#return-to-top').click(function() {
		    $('body,html').animate({
		        scrollTop : 0
		    }, 500);
		});
	}

	//-- scroll 시, bottom of header 을 fixed로
	function setFixedHeader()
	{
		$(window).scroll(function(){
			var headerBottom = $("#header_bottom"),
				scroll = $(window).scrollTop();

			if(scroll >= $("#header_top").outerHeight()){
				headerBottom.addClass("header_fixed");
				$(".content_wrapper").css("margin-top",$("#header_bottom").outerHeight());
			}
			else{
				headerBottom.removeClass("header_fixed");
				$(".content_wrapper").css("margin-top", "0px");
			}
		});
	}

	/*************
	 * Animation *
	 *************/
	function setAnimation()
	{
		//-- Dropdown
		setDropdown();

		//-- Tooltip
		$('[data-toggle="tooltip"]').tooltip();
	}

	function setDropdown(){
		$('.dropdown').on('show.bs.dropdown', function(e){
			var _dropdown = $(this).find('.dropdown-menu');
			if(_dropdown.is(':animated')) return false;

			var orig_margin_top = parseInt(_dropdown.css('margin-top'));
			_dropdown.css({'margin-top': (orig_margin_top + 10) + 'px', opacity: 0}).animate({'margin-top': orig_margin_top + 'px', opacity: 1}, 300, function(){
				$(this).css({'margin-top':''});
			});
		});
	   $('.dropdown').on('hide.bs.dropdown', function(e){
		   var _dropdown = $(this).find('.dropdown-menu');
           if(_dropdown.is(':animated')) return false;

		   var orig_margin_top = parseInt(_dropdown.css('margin-top'));
		   _dropdown.css({'margin-top': orig_margin_top + 'px', opacity: 1, display: 'block'}).animate({'margin-top': (orig_margin_top + 10) + 'px', opacity: 0}, 300, function(){
			   $(this).css({'margin-top':'', display:''});
			});
		});
	}

});

/********************
 * Custom Scrollbar *
 * (slimScroll)     *
 ********************/
function setScroll(obj, height){
    //$(".custom_scroll").slimscroll({
    obj.slimscroll({
        height: height,
        alwaysVisible: true,
        size: "3px",
    }).css("width", "100%");
}

/*************************
 * Image Hover           *
 * "obj" 내부 이미지에만 적용 *
 *************************/
function setImageHover(obj){
    obj.find(".btn-hover").mouseover(function(){
        if(!$(this).find("img").hasClass("not_hover")) $(this).find("img").attr("src", $(this).find("img").attr("src").replace("_01", "_02"));
    }).mouseleave(function(){
        if(!$(this).find("img").hasClass("not_hover")) $(this).find("img").attr("src", $(this).find("img").attr("src").replace("_02", "_01"));
    });
}

/********************
 * Date Picker      *
 * Dashboard, Event *
 *
 * startdate의 mindate는 3달 전까지만.
 ********************/
function setDatePicker(obj){
    var date = new Date();
    //var start = moment().subtract(29, 'days');
    var start = moment().subtract(1, "month").date(1, 'days');
    var end = moment();

    obj.daterangepicker({
        locale: {
            format: 'YYYY-MM-DD'
        },
        "startDate": start,
        "endDate": end,
        minDate: start,
        maxDate: end
    }, function(start, end) {
        obj.find('span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
    });
    obj.find('span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
}

/******************************
 * Button Y/N (inline)
 * Dashboard, Events, Settings
 * Yes 버튼을 위한(action) data는 id와 data(attribution)에 담겨져있어야만 한다.

 * Dashboard/Setting 에서는 40px, Event에서는 53px (추가해도 된다.)

 * setBtnYn
    - _this     : button object
    - _color    : button color
    - _btnWidth : width (단위: px)
    - _btnSize  : size (xs, sm, lg)

 * unsetBtnYn
    - _this : button object
    - _class : all class (e.g. btn btn-xs btn-gray ~~)
    - _value : button text

 ******************************/
function setBtnYn(_this, _color, _btnWidth, _btnSize) {

    var color = "";
    if(_color != "") color = _color;

    var btn_width = 0;
    var btn_size = "";

    var btn_n = "<input type='button'"
            + " class='btn btn-"+_btnSize+" btn-line" + color + " btn-no'"
            + " value='"+c2ms('NO')+"'"
            + " id=" + _this.attr("id")
            + " style='width:"+_btnWidth+"'"
            + " data='" + _this.attr("data") + "' "
            + " pre-add='" + _this.attr("pre-add") + "'/>";

    var btn_y = "<input type='button'"
            + " class='btn btn-"+_btnSize+" btn-" + color + " btn-yes' "
            + " value='"+c2ms('YES')+"' "
            + " id=" + _this.attr("id")
            + " style='width:"+_btnWidth+"'"
            + " data='" + _this.attr("data") + "'"
            + " pre-add='" + _this.attr("pre-add") + "'/>";

    _this.parent().html(btn_n + btn_y);
}

function unsetBtnYn(_this, _class, _value) {
    var btn = "<input type='button'"
            + " id='" + _this.attr("id") + "'"
            + " data='" + _this.attr("data") + "'"
            + " class='"+_class+"'"
            + " value='" + _value + "'"
            + " pre-add='"+_this.attr("pre-add")+"'/>"
            //+ " yn-type='"+_this.attr("yn-type")+"'/>";

    _this.parent().html(btn);
}

/*************************************
 * Modal을 이용한 Alert 창 생성
 *
 * obj는 #cb_alert
 * - header : #cb_alert_header
 * - body	: #cb_alert_body
 *************************************/
function makeModalAlert(header_text, body_text) {

    $("#cb_alert_header").text(header_text);
    $("#cb_alert_body").html(body_text);
    $("#cb_alert").modal({"keyboard": true}).modal('show');

}

/*************************************
 * Alert 창 생성 (direction은 나중에)
 *
 * obj - alert을 붙일 object
 * type - success, info, warning, danger
 * position - window 상에서 위치 : left, right, center
 * showhide - alert을 사락지게 할 것인가 ("show", "hide")
 * text - 문구
 *************************************/
function makeAlert(obj, type, position, showhide, text) {

    //type
    var type_info = "";
    switch(type){
        case "success" : type_info = '<i class="fa fa-check-circle"></i>&nbsp;Success!'; break;
        case "info" : type_info = '<i class="fa fa-info-circle"></i>&nbsp;Information!'; break;
        case "warning" : type_info = '<i class="fa fa-exclamation-triangle"></i>&nbsp;Warning!'; break;
        case "danger" : type_info = '<i class="fa fa-ban"></i>&nbsp;Danger!'; break;
    }

    var text = type_info+"<br/>"+text;


    var alert = $("<div />")
                    .addClass("alert alert-"+type)
                    .fadeIn(500)
    //position
    var width = 350;

    if (position == "right")
        alert.css({
            "float" : position,
            "right" : "15px",
        });
    else if (position == "left")
        alert.css({
            "float" : position,
            "left" : "15px",
        });
    else if (position == "center")
        alert.css({
            "position" : "absolute",
            "left" : ($(window).width()/2 - width/2) + "px",
            "top" : $(".content_wrapper").height()/3 + "px",
        });

    //hide
    if(showhide == "hide") {
        alert
            .fadeTo(2000, 500)
            .fadeOut(500, function(){ $(".alert").alert('close'); })
            .html(text);
    }else{
        alert.html('<span class="close" data-dismiss="alert">×</span>' + text);
    }

    obj.append(alert);

}

/*************************************************************
 * Tooltip 생성
 *
 * obj : tooltip을 붙일 object
 * position: left, right, top, bottom
 * showhide : 페이지 로딩되자 마자 보이게 할 것인지 아닌지 ("show", "hide")
 * text - 문구 (html 태그가 들어가도 됩니다.)
 *************************************************************/
function makeTooltip(obj, position, showhide, text) {
    obj.css({
        //"text-decoration" : "underline",
        "cursor" : "pointer"
    });
    /*var tooltip = obj.tooltip({
                        placement: position,
                        container: 'body',
                        title: text,
                        html: true,
                        delay: { show: 200, hide: 100 },
                    });

    if(showhide == "show") tooltip.tooltip("show");*/

    var popover
        = obj.popover({
            placement: position,
            container: 'body',
            content: text,
            html: true,
            trigger: 'hover',
        });

    if(showhide == "show") popover.popover("show");
}

/*************************************************************
 * Popover 생성
 *
 * obj : popover을 붙일 object
 * position: left, right, top, bottom
 * showhide : 페이지 로딩되자 마자 보이게 할 것인지 아닌지 ("show", "hide")
 * text - 문구 (html 태그가 들어가도 됩니다.)
 *************************************************************/

function makePopover(obj, position, showhide, text) {
    if($("#"+obj.attr("aria-describedby")).is(":visible")){
        $("#"+obj.attr("aria-describedby")).find(".popover-content").text(text);
    }else{
        var popover
            = obj.popover({
            placement: position,
            container: 'body',
            content: text,
            html: true,
            trigger: 'click',
        });
        if(showhide == "show") popover.popover("show");
    }
}

/**********************
 * 숫자 세자리마다 콤마 찍기
 **********************/
function commifyNumber(n){
    var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
    n += '';                          // 숫자를 문자열로 변환

    while (reg.test(n))
        n = n.replace(reg, '$1' + ',' + '$2');

    return n;
}


/**********************
 * cookie 생성함수
 **********************/
function setCookie(c_name,value,exdays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}


/**********************
 * cookie 가져오기
 **********************/
function getCookie(c_name)
{
    var i,x,y,ARRcookies=document.cookie.split(";");
    for (i=0;i<ARRcookies.length;i++){
        x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
        y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
        x=x.replace(/^\s+|\s+$/g,"");

        if (x==c_name){
            return unescape(y);
        }
    }
}

/**********************
 * IE Version 체크
 * IE v.8 아래는 alert으로 접속 불가 알려주기
 **********************/
function get_version_of_IE () {

    var word;
    var version = "N/A";

    var agent = navigator.userAgent.toLowerCase();
    var name = navigator.appName;

    // IE old version ( IE 10 or Lower )
    if ( name == "Microsoft Internet Explorer" ) word = "msie ";

    else {
        // IE 11
        if ( agent.search("trident") > -1 ) word = "trident/.*rv:";

        // Microsoft Edge
        else if ( agent.search("edge/") > -1 ) word = "edge/";
    }

    var reg = new RegExp( word + "([0-9]{1,})(\\.{0,}[0-9]{0,1})" );

    if (  reg.exec( agent ) != null  ) version = RegExp.$1 + RegExp.$2;

    return version;
}


/**********
 * IP 검증
 **********/
function checkIp(ip){
    //var filter = /^(1|2)?\d?\d([.](1|2)?\d?\d){3}$/;
    var filter = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/;

    if (filter.test(ip) == true ) return true;
    else return false;
}

/***********************************
 * IPv4 또는 CIDR IPv4 range 검증
 ***********************************/
function checkIp_or_CIDR(ip){
    var filter_ip = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/;
    var filter_cidr = /^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])(\/([0-9]|[1-2][0-9]|3[0-2]))$/;

    if (filter_ip.test(ip) == true || filter_cidr.test(ip) == true) return true;
    else return false;
}
