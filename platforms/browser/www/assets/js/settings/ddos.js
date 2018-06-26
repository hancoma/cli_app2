(function(){
    // ddos limit c2ms 필요
    var _c2ms 				= {};
    _c2ms.notice 		= c2ms('modal_title_notice');
    _c2ms.invalidFormat	= c2ms('not Number Format');
    _c2ms.invalidRange 	= c2ms('Check Limit Count');

    var controlLimit = (function(){
        var restURL = '/settings/changeddoslimit/',
            domainIndex = sitePath.getPathIndex(3),
            controlLists = document.getElementsByClassName('control_limit'),
            controlLength = controlLists.length,
            previousIndexArray = [];

        function initialize() {
            for(var i = 0; i < controlLength; i++) {
                (function(index){
                    previousIndexArray[index] = controlLists[index].selectedIndex;

                    controlLists[index].addEventListener('change', function(){
                        selectChangeHandler(this, previousIndexArray[index], index);
                    });
                }(i));
            }
        }

        function selectChangeHandler(el, previousIndex, idx) {
            var xhr = new XMLHttpRequest(),
                selectBox = el,
                controlTarget = selectBox.getAttribute('data-limit-target'),
                controlValue = selectBox.value,
                responseResult;

            xhr.open('POST', restURL + domainIndex);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                selectBox.disabled = true;
            };

            xhr.onload = function(){
                var status = this.status,
                    response = JSON.parse(this.responseText);

                return status === 200 && (function(){
                    responseResult = response.result;

                    return responseResult && (function(){
                        previousIndexArray[idx] = selectBox.selectedIndex;

                        console.log('ok');
                        return true;
                    }()) || (function(){
                        console.log('result error');
                        return true;
                    }());
                }()) || (function(){
                    alert('connected error');
                    return false;
                }());
            };

            xhr.onloadend = function() {
                selectBox.disabled = false;

                return responseResult || (function(){
                    selectBox[previousIndex].selected = true;
                }());
            };

            xhr.send('{"' + controlTarget + '":"' + controlValue + '"}');
        }

        return {
            init: initialize
        }
    }());

    return controlLimit.init();
}());