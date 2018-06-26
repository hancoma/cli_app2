(function(){
    // excluded url c2ms 필요
    var _c2ms           = {};
    _c2ms.notice 		= c2ms('modal_title_notice');
    _c2ms.valueEmpty 	= c2ms('url_value_emtpy');
    _c2ms.valueExists 	= c2ms('url_value_exists');
    _c2ms.valueInvalid	= c2ms('url_value_invalid');

    var extraUrl = (function(){
        var restURL     = '/settings/updateextraurl/',
            domainIndex = sitePath.getPathIndex(3),
            urlList = document.getElementById('extraUrlList'),
            addInput = document.getElementsByClassName('add_input')[0],
            addButton = document.getElementsByClassName('add_button')[0];

        function initialize() {
            $(urlList).tagit({
                onTagExists: function(){
                    makeModalAlert(_c2ms.notice, _c2ms.valueExists);
                },
                beforeTagRemoved: removeTag
            });

            addInput.addEventListener('keypress', inputEnterHandler);
            addButton.addEventListener('click', addTagHandler);
        }

        function addTagHandler() {
            var tagValue = addInput.value;

            return tagValue && (function(){
                var xhr = new XMLHttpRequest(),
                    responseResult;

                xhr.open('POST', restURL + domainIndex);
                xhr.setRequestHeader("Content-type", "application/json");

                xhr.onloadstart = function(){
                    addInput.disabled = true;
                    addButton.disabled = true;
                };

                xhr.onload = function(){
                    var status = this.status,
                        response = JSON.parse(this.responseText);

                    return status === 200 && (function(){
                        responseResult = response.result;

                        return responseResult && (function(){
                            console.log('ok');
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

                xhr.onloadend = function(){
                    addInput.disabled = false;
                    addButton.disabled = false;

                    return responseResult && (function(){
                        var replaceValue = tagValue.charAt(0) === '/' && tagValue || '/' + tagValue;

                        addInput.value = '';
                        $(urlList).tagit("createTag", replaceValue);
                    }());
                };

                xhr.send(JSON.stringify({url: [tagValue]}));
            }()) || (function(){
                return tagValue || makeModalAlert(_c2ms.notice, _c2ms.valueEmpty);
            }());
        }

        function inputEnterHandler(e) {
            return e.keyCode === 13 && addButton.click();
        }

        function removeTag(e, data) {
            e.preventDefault();

            var xhr = new XMLHttpRequest(),
                tag = data.tag,
                tagValue = data.tagLabel,
                responseResult;

            xhr.open('DELETE', restURL + domainIndex);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function(){
                tag[0].className += ' wait_removed';
            };

            xhr.onload = function(){
                if(xhr.status === 200) {
                    responseResult = (xhr.responseText === 'true');

                    if(responseResult) {
                        console.log('ok')
                    } else {
                        console.log('error')
                    }
                } else {
                    alert('error');
                }
            };

            xhr.onloadend = function(){
                tag.remove();
            };

            xhr.send(JSON.stringify({url: [tagValue]}));
        }

        return {
            init: initialize
        }
    }());

    return extraUrl.init();
}());