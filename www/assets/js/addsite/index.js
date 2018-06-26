/*
(function(){
    var checkDomain = (function(){
        var form = document.getElementById('addsiteForm'),
            input = document.getElementById('addsite_url'),
            button = document.getElementById('addButton'),
            imageWrap = document.getElementById('statusImage');

        function initialize() {
            input.focus();
            button.addEventListener('click', buttonClickHandler);
        }

        function buttonClickHandler(e) {
            e.preventDefault();

            var value = input.value,
                isValidUrl = confirmValidUrl(value);

            value === '' && (function(){
                makeModalAlert(c2ms('modal_domain_empty_title'), c2ms('domain_empty'));
                return true;
            }()) || !isValidUrl && (function(){
                makeModalAlert(c2ms('modal_title_warning'), c2ms('domain_invalid'));
                return true;
            }()) || (function(){
                var xhr = new XMLHttpRequest(),
                    responseResult;

                xhr.open('POST', '/addsite/domainCheck');
                xhr.setRequestHeader("Content-type", "application/json");

                xhr.onloadstart = function() {
                    imageWrap.innerHTML = '<img src="/assets/img/loading.gif">';
                    button.innerText = c2ms('B_checkUrl');
                    button.disabled = true;
                };

                xhr.onload = function(){
                    var status = this.status,
                        response = JSON.parse(this.responseText);

                    return status === 200 && (function(){
                        var returnKey = response.c2ms_key;
                        responseResult = response.result === 'success' && true || false;

                        return responseResult && (function(){
                            form.submit();
                            return true;
                        }()) || (function(){
                            makeModalAlert(c2ms('modal_title_warning'), c2ms(returnKey));
                            return true;
                        }());
                    }()) || (function(){
                        console.log('connected error');
                        return false;
                    }());
                };

                xhr.onloadend = function(){
                    imageWrap.innerHTML = '';
                    button.innerText = c2ms('add_site_btn');
                    button.disabled = false;

                    return responseResult || (function(){
                        //window.location.href='/addsite/region';
                    }());
                };

                xhr.send(JSON.stringify({user_domain: value}));
                return true;
            }());
        }

        function confirmValidUrl(str) {
            var pattern = new RegExp('^(https?:\\/\\/)?'+ // 프로토콜
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // 도메인명 <-이부분만 수정됨
                '((\\d{1,3}\\.){3}\\d{1,3}))'+ // 아이피
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // 포트번호
                '(\\?[;&a-z\\d%_.~+=-]*)?'+ // 쿼리스트링
                '(\\#[-a-z\\d_]*)?$','i'), // 해쉬테그들
                isValid = pattern.test(str);

            return isValid;
        }

        return {
            init: initialize
        }
    }());

    return checkDomain.init();
}());
*/