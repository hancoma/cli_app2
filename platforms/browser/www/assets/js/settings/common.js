$(function() {

    /**********
     * HEADER *
     **********/
    $("[cb-menu='settings']").addClass("active");

    /************************
     * Settings Button MENU *
     ************************/
        //-- ACTIVE MENU
    var menu = location.pathname.split("/")[2];
    var btn_img = $("[data-url='"+menu+"']").removeClass("btn-hover").addClass("active").find("img");
    btn_img && btn_img.attr("src", btn_img.attr("src").replace("01", "02"));

    setImageHover($(".content_wrapper"));

    //-- 변수 설정
    var default_menu = 8;
    var total_menu = $("#settings_nav_group").find(".btn-app").length; //실제 메뉴 개수

    var set_menu = $("#settings_nav"); //btn-app GROUP
    var arrow_left = $("#btn_arrow_l");
    var arrow_right = $("#btn_arrow_r");

    //-- MENU POSITION (1: LEFT 얼마나 갈 것인지, 2: 화살표 ACTIVE인가 아닌가)
    // 1) 현재 메뉴의 index & left 얼마나 갈 것인지.

    //var cur_index = 0;
    //$(".btn-app").each(function(i){ if($(this).attr("id") == "btn_"+menu) cur_index = i});

    var cur_index = set_menu.children('.btn-app.active').index()+1;

    var pos_left = cur_index-default_menu;
    if(pos_left<0) pos_left = 0;

    set_menu.css("left", "-"+((pos_left)*112)+"px");

    // 2) 화살표가 active인가 아닌가
    var rest = 0; // rest는 오른쪽에 남은 btn 개수

    if(cur_index<=default_menu) rest = total_menu-default_menu;
    else rest = total_menu-cur_index;

    if(total_menu>default_menu) { //전체 menu 개수가 default_menu 개수를 넘는 경우
        if(rest>0) { //오른쪽에 남은 메뉴가 있는 경우
            var img = arrow_right.addClass("active").find("img");
            img.attr("src", img.attr("src").replace("01", "02"));
        }

        if(cur_index>default_menu) { //왼쪽에 남은 메뉴가 있는 경우
            var img = arrow_left.addClass("active").find("img");
            img.attr("src", img.attr("src").replace("01", "02"));
        }
    }


    /******************************
     * Settings Button Pagination *
     ******************************/
    //-- 화살표 클릭 시.
    $(document).on("click", ".btn-arrow.active", function() {
        if(set_menu.is(':animated')) return false;

        var $this = $(this);
        var direction = $this.attr("id").replace("btn_arrow_", ""),
            width = set_menu.find(".btn-app").outerWidth();

        if(direction=="r") {
            set_menu.animate({
                left: '-='+width
            }, 250, "linear", function() {
                --rest;
                //오른쪽에 남은 메뉴가 없는 경우
                if(rest==0) {
                    $this.removeClass("active");
                    arrow_right.find("img").attr("src", arrow_right.find("img").attr("src").replace("02", "01"));
                }

                //다른 arrow 활성화가 되어 있지 않은 경우
                if(!arrow_left.hasClass("active")) {
                    arrow_left.addClass("active");
                    arrow_left.find("img").attr("src", arrow_left.find("img").attr("src").replace("01", "02"));
                }
            });
        }
        else if(direction=="l") {
            set_menu.animate({
                left: '+='+width
            }, 350, "linear", function() {
                ++rest;
                //왼쪽에 남은 메뉴가 없는 경우
                if(rest==(total_menu-default_menu)) {
                    $this.removeClass("active");
                    arrow_left.find("img").attr("src", arrow_left.find("img").attr("src").replace("02", "01"));
                }

                //다른 arrow 활성화가 되어 있지 않은 경우
                if(!arrow_right.hasClass("active")) {
                    arrow_right.addClass("active");
                    arrow_right.find("img").attr("src", arrow_right.find("img").attr("src").replace("01", "02"));
                }
            });
        }
    });
});