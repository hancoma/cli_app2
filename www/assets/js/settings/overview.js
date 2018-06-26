(function(){
    var domainIndex = sitePath.getPathIndex(3),
        changeIPs, changeCname, changeBypassMode, changeWebSeal, overviewActions;

    changeIPs = (function() {
        function initialize() {
            add();
            remove();
            save();
        }

        function add() {
            var d = document,
                button = d.getElementById('itemAddButton'),
                item = d.createElement('li'),
                itemInput = d.createElement('input'),
                itemButton = d.createElement('button');

            item.className = 'ip_item';
            itemInput.className = 'ip_input';
            itemInput.setAttribute('type', 'text');
            itemButton.className = 'ip_remove_button';
            itemButton.setAttribute('type', 'button');
            itemButton.innerText = 'X';

            item.appendChild(itemInput);
            item.appendChild(itemButton);

            button.addEventListener('click', function(){
                var list = d.getElementById('ipList'),
                    newItem = item.cloneNode(true);

                list.appendChild(newItem);
            });
        }

        function remove() {
            var list = document.getElementById('ipList');

            list.addEventListener('click', function(e){
                var target = e.target,
                    nodeName = target.nodeName.toLowerCase(),
                    isRemoveButton = target.className.indexOf('ip_remove_button') > -1;

                !(!(nodeName === 'button' && isRemoveButton) || !(function() {
                    var parent = target.parentElement,
                        parentItem = parent.parentElement,
                        itemLength = parentItem.children.length;

                    itemLength > 1 && (function() {
                        parentItem.removeChild(parent);

                        return true;
                    }()) || (function() {
                        var input = target.previousElementSibling;
                        input.value = '';

                        return true;
                    }());
                }()));
            });
        }

        function save() {
            var button = document.getElementById('ipSaveButton'),
                inputValueArray = [];

            button.addEventListener('click', function(){
                var input = document.getElementsByClassName('ip_input');

                for(var i = 0, inputLength = input.length; i < inputLength; i++) {
                    var value = input[i].value;

                    value !== '' && inputValueArray.push(value);
                }

                inputValueArray.length > 0 && sendData(inputValueArray);
                inputValueArray = [];
            });
        }

        function sendData(data) {
            var xhr = new XMLHttpRequest(),
                restURL = '/settings/changeip/',
                responseResult;

            xhr.open('PUT', restURL+domainIndex);
            xhr.setRequestHeader("Content-type", "application/json");

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

            xhr.onloadend = function() {
                responseResult || (function(){
                    makeModalAlert(c2ms('modal_title_notice'), c2ms('ip_value_invalid'));
                }());
            };

            xhr.send(JSON.stringify({ips: data}));
        }

        return {
            init: initialize
        };
    }());

    changeCname = (function(){
        var restURL = '/settings/changecname/';

        function initialize() {
            var editButton = document.getElementById('setCname');
            editButton.addEventListener('click', clickHandler);
        }

        function clickHandler() {
            var xhr = new XMLHttpRequest(),
                editButton = this,
                cnameInput = document.getElementsByName('cname')[0],
                cnameValue = cnameInput.value,
                responseResult;

            xhr.open('PUT', restURL + domainIndex);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                editButton.disabled = true;
                cnameInput.disabled = true;
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

            xhr.onloadend = function() {
                editButton.disabled = false;
                cnameInput.disabled = false;
            };

            xhr.send(JSON.stringify({ip_cname: cnameValue}));
        }

        return {
            init: initialize
        };
    }());

    changeBypassMode = (function(){
        var restURL = '/settings/changebypassmode/';

        function initialize() {
            var switchButton = document.getElementById('bypassChange');
            $(switchButton).on('switchChange.bootstrapSwitch', switchHandler);
        }

        function switchHandler(e, state) {
            var xhr = new XMLHttpRequest(),
                switchButton = this,
                toggleValue = state && 'on' || 'off',
                responseResult;

            xhr.open('PUT', restURL+domainIndex);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                $(switchButton).bootstrapSwitch('toggleDisabled');
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

            xhr.onloadend = function() {
                $(switchButton).bootstrapSwitch('toggleDisabled');

                return responseResult || (function(){
                    $(switchButton).bootstrapSwitch('state', !state, true);
                }());
            };

            xhr.send(JSON.stringify({mode: toggleValue}));
        }

        return {
            init: initialize
        };
    }());

    changeWebSeal = (function(){
        var restURL = '/settings/changeWebSeal/',
            previousIndex;

        function initialize() {
            var webSealPosition = document.getElementById('webSealSelect');

            previousIndex = webSealPosition.selectedIndex;
            webSealPosition.addEventListener('change', selectHandler);
        }

        function selectHandler() {
            var xhr = new XMLHttpRequest(),
                selectBox = this,
                changeValue = selectBox.value,
                responseResult;

            xhr.open('PUT', restURL + domainIndex);
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
                        previousIndex = selectBox.selectedIndex;

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
                selectBox.disabled = false;

                return responseResult || (function(){
                    selectBox[previousIndex].selected = true;
                }());
            };

            xhr.send(JSON.stringify({web_seal: changeValue}));
        }

        return {
            init: initialize
        };
    }());

    overviewActions = (function(){
        function initialize() {
            webServerFormChange();
            setBypassSwitch();
        }

        function webServerFormChange() {
            var select = document.getElementById('formChangeSelect');
            select.addEventListener('change', selectFormChangeHandler);
        }

        function selectFormChangeHandler() {
            var selectedFormId = this.value,
                selectedForm = document.getElementById(selectedFormId),
                otherFormId = selectedFormId === 'changeCNAME' && 'changeIP' || 'changeCNAME',
                otherForm = document.getElementById(otherFormId);

            selectedForm.style.display = '';
            otherForm.style.display = 'none';
        }

        function setBypassSwitch() {
            $(".btn_switch").bootstrapSwitch();
        }

        return {
            init: initialize
        };
    }());

    return (function(){
        changeIPs.init();
        changeCname.init();
        changeBypassMode.init();
        changeWebSeal.init();
        overviewActions.init();
    }());
}());