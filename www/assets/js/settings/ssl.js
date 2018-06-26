(function(){
    var domainIndex = sitePath.getPathIndex(3),
        changeSSLMode, toggleRedirectHttps, toggleRedirectWww, sslActions;

    changeSSLMode = (function(){
        var restURL     = '/settings/changesslmode/',
            previousIndex;

        function initialize() {
            var sslModeList = document.getElementById('sslSelect');

            previousIndex = sslModeList.selectedIndex;
            sslModeList.addEventListener('change', selectHandler);
        }

        function selectHandler() {
            var selectBox = this,
                changeValue = selectBox.value;

            var xhr = new XMLHttpRequest(),
                responseResult;

            xhr.open('PUT', restURL + domainIndex);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function(){
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
                    alert('connected error');
                    return false;
                }());
            };

            xhr.onloadend = function(){
                selectBox.disabled = false;

                return responseResult || (function(){
                    selectBox[previousIndex].selected = true;
                }());
            };

            xhr.send(JSON.stringify({mode: changeValue}));
        }

        return {
            init: initialize
        }
    }());

    toggleRedirectHttps = (function(){
        var restURL = '/settings/toggleredirecthttps/';

        function initialize() {
            var switchButton = document.getElementById('redirectionHttps');
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

    toggleRedirectWww = (function(){
        var restURL = '/settings/toggleredirectwww/';

        function initialize() {
            var switchButton = document.getElementById('redirectionWww');
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

    sslActions = (function(){
        function initialize() {
            showHelpImage();
            setHttpsSwitch();
            setWWWSwitch();
        }

        function showHelpImage() {
            var button = document.getElementById('sslHelpButton');
            button.addEventListener('click', helpButtonClickHandler);
        }
        
        function helpButtonClickHandler() {
            $("#helpModal").modal('show');
        }

        function setHttpsSwitch() {
            $(".btn_switch_https").bootstrapSwitch();
        }

        function setWWWSwitch() {
            $(".btn_switch_www").bootstrapSwitch();
        }

        return {
            init: initialize
        }
    }());

    return (function(){
        changeSSLMode.init();
        toggleRedirectHttps.init();
        toggleRedirectWww.init();
        sslActions.init();
    }());
}());