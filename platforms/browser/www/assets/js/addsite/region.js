(function(){
    var originDomainIp = [],
        originItemList = '',
        originCname = '',
        changeModal = $('#changeRequestModal'),
        initRegion,
        resetDomainInfo;

    initRegion = (function(){
        var changeButton = document.getElementById('changeButton'),
            applyButton = document.getElementById('applyButton');

        function initialize() {
            addEvents();
            setOptions();
            closeReset();
        }

        function addEvents() {
            changeButton.addEventListener('click', showModal);
            applyButton.addEventListener('click', applyChange);
            //resetButton.addEventListener('click', reload);

        }

        function setOptions() {
            setAddOption();
            setRemoveOption();
        }

        function setAddOption() {
            var addOption = document.getElementsByClassName('option_add')[0];
            addOption.addEventListener('click', addInput);

            var selectBox = document.getElementById('webServerIPs');
            selectBox.addEventListener('change', function(){
                var selectedIP = selectBox.options[0].value;
                webserver_reload(selectedIP);
            });

        }

        function setRemoveOption() {
            var _r,
                removeOption = document.getElementsByClassName('option_remove'),
                removeOptionCount = removeOption.length;

            for(_r = 0; _r<removeOptionCount; _r++) {
                removeOption[_r].addEventListener('click', removeInput);
            }
        }

        function addInput() {
            var addInputButton = this,
                lastItem = addInputButton.parentElement,
                ipsList = document.getElementById('inputList'),
                cloneNode = this.parentElement.cloneNode(true);

            lastItem.className = 'item_ips';
            addInputButton.className = 'option_button option_remove';
            addInputButton.innerText = '-';

            cloneNode.getElementsByClassName('ips_input')[0].value = '';
            ipsList.appendChild(cloneNode);

            setAreaScroll();
            setOptions();

            addInputButton.removeEventListener('click', addInput);
        }

        function removeInput() {
            var parentItem = this.parentElement;
            parentItem.parentElement.removeChild(parentItem);
        }

        function setAreaScroll() {
            var listArea = document.getElementsByClassName('type_ips')[0],
                listAreaHeight = listArea.scrollHeight;

            return listArea.scrollTop = listAreaHeight < 178 || listAreaHeight - 178;
        }

        function showModal() {
            changeModal.modal({'keyboard': true}).modal('show');
        }

        function applyChange() {
            var isCnameChecked = document.getElementById('checkCname').checked,
                checkedType = isCnameChecked && 'cname' || 'ip';

            checkedType === 'ip' && (function(){
                var isValid = true,
                    getIPs = document.getElementsByClassName('ips_input'),
                    getIPsCount = getIPs.length,
                    getIPsArray = [],
                    _g;



                for(_g = 0; _g < getIPsCount; _g++) {
                    if(getIPs[_g].value !== '') {
                        if(checkIp(getIPs[_g].value)) {
                            getIPsArray.push(getIPs[_g].value);
                        }else{
                            getIPs[_g].value = '';
                            getIPs[_g].focus();
                            return false;
                        }
                    }else{
                        if(_g != (getIPsCount-1)){
                            getIPs[0].focus();
                            return false;
                        }
                    }
                }

                if(getIPsCount < 2){
                    if(getIPs[0].value == '') {
                        getIPs[0].focus();
                        return false;
                    }
                }

                isValid = getIPsArray.indexOf('127.0.0.1') < 0 && getIPsArray.indexOf('0.0.0.0') < 0 && isValid;

                !isValid || (function(){
                    changeModal.modal('hide');
                    setWebServer(getIPsArray);
                    webserver_reload(getIPsArray, checkedType);
                    resetDomainInfo.setInfo();

                    return true;
                }());

                return true;
            }()) || checkedType === 'cname' && (function(){
                var getCnameValue = document.getElementsByClassName('cname_input')[0].value,
                    cnameBox = document.getElementById('cnameBox'),
                    selectBox = document.getElementById('webServerIPs');


                if(getCnameValue == '') {
                    document.getElementById('inputCname').focus();
                    return false;
                }

                changeModal.modal('hide');
                selectBox.style.display = 'none';
                cnameBox.style.display = 'inline-block';
                cnameBox.value = getCnameValue;
                originCname = getCnameValue;
                webserver_reload([], checkedType);

                return true;
            }());
        }

        function webserver_reload(ip, type){

            var selectBox = document.getElementById('webServerIPs');
            var selectValue = selectBox.options[selectBox.selectedIndex].value;
            var cnameValue = document.getElementById('inputCname').value;

            var user_email = document.getElementById('user_email').textContent.trim();

            var url = "/addsite/closest_afc_reload";
            var data = {
                "type": type,
                "ip": selectValue,
                "email": user_email,
                "cname": cnameValue
            }

            $.ajax({
                type: "POST",
                url: url,
                data: data,
                dataType: "json",
                success: function (data) {

                    var resultIp = data.result_info.ip,
                        regionData = data.result_info.cloudbric_idc[0],
                        fast_afc_code = regionData.afc_code,
                        fast_afc_country = regionData.afc_country,
                        fast_continent = regionData.continent,
                        fastestItem = document.getElementById('fastestCity'),
                        fastestItemText = fastestItem.getElementsByClassName('choose-idc-name')[0],
                        typeText = (type === 'ip' || data.result_info.ip === '0.0.0.0') && 'IP' || 'CNAME';

                   if(type === 'cname'){
                       //type = 'ip';

                       //makeModalAlert(c2ms('modal_title_warning'), c2ms('addsite_region_ip'));
                       document.getElementById('cnameBox').value = resultIp === '0.0.0.0' && '0.0.0.0' || cnameValue;
                       //document.getElementById('cnameBox').value = resultIp;
                       //document.getElementById('inputCname').value = '';

                       if(resultIp === '0.0.0.0') {
                           makeTooltip($(changeButton), "right", "show", c2ms('not_facing_cname'));
                       } else {
                           $(changeButton).popover('destroy');
                       }
                   }

                   document.getElementById('serverTypeText').innerText = typeText;
                   document.getElementById('cname_ip').value = resultIp;
                   document.getElementById('webserver_type').value = type;

                    //console.log(document.getElementById('cname_ip').value);
                    //console.log(document.getElementById('webserver_type').value);

                    $("#text-choose-idc").text(fast_afc_country);

                    fastestItem.setAttribute('data-city', fast_afc_code.toLowerCase());
                    fastestItem.setAttribute('data-afc_code', fast_afc_code);
                    fastestItemText.innerText = fast_afc_country;

                    $("#choose-idc-map").find(".map-pin.selected").removeClass("selected");
                    $("#choose-idc-map").find("[data-city='"+fast_afc_code.toLowerCase()+"']").addClass("selected");

                    $("#regionList").slimscroll({ scrollTo: 0 });
                },
                complete: function () {
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }



        function setWebServer(ips) {
            var setIPsValue = document.getElementsByName('ips')[0],
                selectBox = document.getElementById('webServerIPs'),
                cnameBox = document.getElementById('cnameBox'),
                ipsLength = ips.length,
                appendHTML, i;

            setIPsValue.value = ips + '';
            selectBox.innerHTML = '';

            for(i = 0; i < ipsLength; i++) {
                appendHTML += '<option value="' + ips[i] + '">' + ips[i] + '</option>';
            }

            cnameBox.style.display = '';
            selectBox.style.display = '';
            selectBox.innerHTML = appendHTML;
        }

        function closeReset() {
            var closeButton = document.getElementById('modalCloseButton');

            closeButton.addEventListener('click', function(){
                resetDomainInfo.reset();
            });
        }

        return {
            init: initialize,
            initOption: setOptions
        };
    }());

    resetDomainInfo = (function(){
        var button = document.getElementById('resetButton'),
            cnameBox = document.getElementById('cnameBox'),
            inputCname = document.getElementById('inputCname');

        function initialize() {
            initOriginData();
            button.addEventListener('click', resetClickHandler);
        }

        function initOriginData() {
            var select = document.getElementById('webServerIPs'),
                inputList = document.getElementById('inputList'),
                inputs = document.getElementsByClassName('ips_input'),
                getData = [];

            for(var i = 0, seletLength = select.length; i < seletLength; i++) {
                getData[i] = select[i].value;
            }

            for(var j = 0, inputLength = inputs.length; j < inputLength; j++) {
                inputs[j].setAttribute('value', inputs[j].value);
            }

            originDomainIp = Array.prototype.slice.call(getData);
            originItemList = inputList.innerHTML;
            originCname = inputCname.value;
        }

        function resetClickHandler() {
            var addOptions = '',
                select = document.getElementById('webServerIPs'),
                inputList = document.getElementById('inputList'),
                ipRadio = document.getElementsByName('type')[0];

            for(var i = 0, length = select.length; i < length; i++) {
                var data = originDomainIp[i];

                addOptions += '<option value="' + data + '">' + data + '</option>';
            }

            select.innerHTML = '';
            cnameBox.style.display = '';
            select.style.display = '';
            select.innerHTML = addOptions;
            inputCname.value = originCname;
            inputList.innerHTML = originItemList;

            ipRadio.checked = true;
            initRegion.initOption();
            changeModal.modal('hide');
        }

        return {
            init: initialize,
            setInfo: initOriginData,
            reset: resetClickHandler
        };
    }());

    return (function(){
        initRegion.init();
        resetDomainInfo.init();
    }());
}());