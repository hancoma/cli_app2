(function(){
    var deleteActions = (function(){
        var deleteButton = document.getElementById('btn_delete'),
            isDeleting = deleteButton.disabled;

        function initialize() {
        	setButton();
        }

        function setButton() {
            !isDeleting && (function(){
                deleteButton.addEventListener('click', buttonClickHandler);
            }());
		}

		function buttonClickHandler() {
            var thisButton = this,
                buttonWrap = document.getElementsByClassName('button_wrap'),
                isButtonWrap = buttonWrap.length;

            thisButton.style.display = 'none';

            isButtonWrap < 1 && (function(){
                var parentElement = thisButton.parentElement,
                    wrap = document.createElement('div'),
                    button = document.createElement('button'),
                    noButton = button.cloneNode(),
                    yesButton = button.cloneNode();

                wrap.className = 'button_wrap';

                noButton.className = 'btn btn-sm btn_delete_site btn-linegray';
                noButton.innerText = 'NO';

                yesButton.className = 'btn btn-sm btn_delete_site btn-gray';
                yesButton.innerText = 'YES';

                noButton.addEventListener('click', function(){
                    wrap.style.display = 'none';
                    thisButton.style.display = 'block';
                });

                yesButton.addEventListener('click', deleteDomain);

                wrap.appendChild(noButton);
                wrap.appendChild(yesButton);

                parentElement.appendChild(wrap);

                return true;
            }()) || (function(){
                buttonWrap[0].style.display = 'block';
            }());
		}

		function deleteDomain() {
            var xhr = new XMLHttpRequest(),
                restURL = '/settings/deleteDomain/',
                domainIndex = sitePath.getPathIndex(3),
                responseResult;

            xhr.open('DELETE', restURL + domainIndex);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
            	console.log('start')
            };

            xhr.onload = function(){
                var status = this.status,
                    response = JSON.parse(this.responseText),
                    responseMessage;

                return status === 200 && (function(){
                    responseResult = response.result;

                    return responseResult && (function(){
                        responseMessage = response.result_info.message;

                        console.log(responseMessage);
                        return true;
                    }()) || (function(){
                        console.log('result error');
                        return true;
                    }());
                }()) || (function(){
                    console.log('connected error');
                    return false;
                }());
            };

            xhr.onloadend = function() {
                console.log('end');

                return responseResult && (function(){
                    location.replace('/mysites');
                    return true;
                }());
            };

            xhr.send();
		}

        return {
            init: initialize
        }
    }());

    return deleteActions.init();
}());

/*$(function(){

	//1) Yes or No 버튼 나오게
	$(document).on("click", "#btn_delete", function(){
		setBtnYn($(this), "gray", "48px", "sm");
	});

	//2)Yes/No 바깥쪽 클릭 시, 사라지게 하기. (보이는 Yes/No 버튼 모두)
	$(document).on("click", function(e){ 
		var classList = $(e.target).parents("[class*='btn_delete_site']").context.classList;
		classList = $.makeArray(classList);

		if((classList.indexOf("btn_delete_site") == -1)
			 && (classList.indexOf("btn-yes") == -1)
			 && (classList.indexOf("btn-no") == -1)) {
				
			$(this).find(".btn-yes").each(function(){ 
				unsetBtnYn($(this), "btn btn-gray btn-sm btn_delete_site", "DELETE"); 
			});
		}
	});

	//3) No 버튼 클릭 시, 버튼 사라지게 하기
	$(document).on("click", ".btn-no", function(){
		unsetBtnYn($(".btn-no"), "btn btn-gray btn-sm btn_delete_site", "DELETE");
	});

	//4) Yes 버튼 클릭 시, "IP 차단 혹은 차단 풀기" 프로세스 실행
	$(document).on("click", ".btn-yes", function(){

		//한번 더 삭제할 건지 물어보기?
		
		//삭제 진행
		$.ajax({
            url : '/settings/async/delete/'+selected_domain+'/'+selected_domain_idx,
            type: "POST",
            data: {
            	"deleting" : "yes",
            },
            dataType:"json",
            beforeSend:function()
            {
                $(".btn-yes").attr("disabled", true);
            },
            success: function(res)
            {
                if(res.result === true) {

                	//삭제 완료 후, My Site로 이동
                	window.location.href="/mysite/home/lists";

                }
            },
            complete: function()
            {
            	$(".btn-yes").attr("disabled", false);
            },
			error: function(e)
			{
				console.log(e);
			}
			

        });

	});

});*/