(function(){
    var _c2ms           = {};
    _c2ms.notice 		= c2ms('modal_title_notice');
    _c2ms.valueEmpty 	= c2ms('ip_value_empty');
    _c2ms.valueExists 	= c2ms('ip_value_exists');
    _c2ms.valueInvalid	= c2ms('ip_value_invalid');

    var controlIps = (function(){
        var restURL     = '/settings/updateipcontrol/',
            domainIndex = sitePath.getPathIndex(3),
            blackList = document.getElementById('blackListIps'),
            whiteList = document.getElementById('whiteListIps'),
            addInput = document.getElementsByClassName('add_input'),
            addInputLength = addInput.length,
            addButton = document.getElementsByClassName('add_button'),
            addButtonLength = addButton.length;

        function initialize() {
            setTagit();
            setAddRecord();
        }

        function setTagit() {
            $(blackList).tagit({
                onTagExists: hasTag,
                beforeTagRemoved: removeTag
            });

            $(whiteList).tagit({
                onTagExists: hasTag,
                beforeTagRemoved: removeTag
            });
        }

        function hasTag() {
            makeModalAlert(_c2ms.notice, _c2ms.valueExists);
        }

        function setAddRecord() {
            var i;

            for(i = 0; i < addButtonLength; i++) {
                addButton[i].addEventListener('click', addClickTag);
            }

            for(i = 0; i < addInputLength; i++) {
                addInput[i].addEventListener('keypress', addEnterTag);
            }
        }

        function addClickTag() {
            var controlButton = this,
                controlType = this.getAttribute('data-control-type'),
                controlTarget = controlType === 'black' && blackList || whiteList,
                otherList = controlType === 'black' && whiteList || blackList,
                getInput = document.getElementsByName(controlType)[0],
                tagValue = getInput.value,
                tagData = $(otherList).data('ui-tagit'),
                isValidIp = checkIp_or_CIDR(tagValue),
                isNewTag = tagData._isNew(tagValue),
                responseResult;

            tagValue === '' && (function(){
                makeModalAlert(_c2ms.notice, _c2ms.valueEmpty);
                return true;
            }()) || !isValidIp && (function(){
                makeModalAlert(_c2ms.notice, _c2ms.valueInvalid);
                return true;
            }()) || !isNewTag && (function(){
                makeModalAlert(_c2ms.notice, _c2ms.valueExists);
                return true;
            }()) || (function(){
                var xhr = new XMLHttpRequest();

                xhr.open('POST', restURL + domainIndex);
                xhr.setRequestHeader("Content-type", "application/json");

                xhr.onloadstart = function(){
                    getInput.disabled = true;
                    controlButton.disabled = true;
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
                    getInput.disabled = false;
                    controlButton.disabled = false;

                    return responseResult && (function(){
                        getInput.value = '';
                        $(controlTarget).tagit("createTag", tagValue);
                    }());
                };

                xhr.send(JSON.stringify({type: controlType, ip: [tagValue]}));
                return true;
            }());
        }

        function addEnterTag(e) {
            var targetId = this.getAttribute('data-target-button'),
                targetButton = document.getElementById(targetId);

            e.keyCode === 13 && targetButton.click();
        }

        function removeTag(e, data) {
            e.preventDefault();

            var xhr = new XMLHttpRequest(),
                tag = data.tag,
                tagPrevClassList = tag[0].className,
                controlType = this.getAttribute('data-control-type'),
                tagValue = data.tagLabel,
                responseResult;

            xhr.open('DELETE', restURL + domainIndex);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function(){
                tag[0].className += ' wait_removed';
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
                tag[0].className = tagPrevClassList;

                return responseResult && (function(){
                    tag.remove();
                }());
            };

            xhr.send(JSON.stringify({type: controlType, ip: [tagValue]}));
        }

        return {
            init: initialize
        };
    }());

    return controlIps.init();
}());