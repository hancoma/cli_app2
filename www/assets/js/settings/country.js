(function(){
    var _c2ms = {};
    _c2ms.notice 		    = c2ms('modal_title_notice');
    _c2ms.typeEmpty 	    = c2ms('country_type_empty');
    _c2ms.countryEmpty 	    = c2ms('country_value_empty');
    _c2ms.valueExists 	    = c2ms('country_value_exists');
    _c2ms.phSetCountry 	    = c2ms('ph_set_country');
    _c2ms.phSelectCountry   = c2ms('ph_select_country');

    var countryRadio = $('input[name="country_block_white"]'),
        countryRadioBlack = document.getElementById('country_block'),
        countryRadioWhite = document.getElementById('country_white'),
        countryList = document.getElementById('countriesSelect'),
        domainIndex = sitePath.getPathIndex(3),
        tagList = document.getElementById('tagList'),
        addButton = document.getElementsByClassName('add_button')[0];

    if ($('#countryControl').is(":checked") == true) {
        $('#countryOffDescription').css('display','none');
        $('#setCountryArea').css('display','block');

    } else {
        $('#countryOffDescription').css('display','block');
        $('#setCountryArea').css('display','none');
    }

    var settingCountry = (function(){
        function initialize() {
            countryRadio.on("click", SelectBlockWhiteHandler);

            if ($('#country_block').is(":checked") == true) {
                $('#country_block').trigger('click');
            } else {
                $('#country_white').trigger('click');
            }

            $(countryList).select2({
                placeholder: _c2ms.phSelectCountry,
                sorter: function(data) {
                    var query = $('.select2-search__field').val().toLowerCase();
                    
                    return data.sort(function(a, b) {
                        return a.text.toLowerCase().indexOf(query) - b.text.toLowerCase().indexOf(query);
                    });
                }
            });

            addButton.addEventListener('click', addClickHandler);
        }

        function SelectBlockWhiteHandler() {
            var xhr = new XMLHttpRequest(),
                countryType = this.value,
                restURL = '/settings/changeCountryAccessType/',
                parameters = domainIndex,
                fullUrl = restURL + parameters,
                responseResult;

            xhr.open('PUT', fullUrl);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                toggleInput();
            };

            xhr.onload = function(){
                var status = this.status,
                    response = JSON.parse(this.responseText);
                return status === 200 && (function(){
                    responseResult = response.result;

                    return responseResult && (function(){
                        console.log('ok');
                        $(this).prop("checked", true);
                        if (countryType == 'black') {
                            $('#country_white').prop("checked", false);
                        } else {
                            $('#country_block').prop("checked", false);
                        }
                        getSelectedCountryTypeData(countryType);
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
                toggleInput();
            };

            xhr.send(JSON.stringify({type: countryType}));
        }

        function getSelectedCountryTypeData(countryType) {
            var xhr = new XMLHttpRequest(),
                countryType = countryType,
                restURL = '/settings/selectcountries/',
                parameters = domainIndex + '/' + (countryType == 'black' && 'block' || 'white'),
                fullUrl = restURL + parameters,
                responseResult;

            xhr.open('GET', fullUrl);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                toggleInput();

                countryList.selectedIndex = 0;
                $(countryList).trigger('change');

                return tagList.innerText === '' || (function() {
                    tagList.innerHTML = '';
                    $(tagList).tagit('destroy');
                }());
            };

            xhr.onload = function() {
                var status = this.status,
                    response = JSON.parse(this.responseText);
                return status === 200 && (function() {
                    responseResult = response.result;

                    return responseResult && (function() {
                        var info = response.result_info,
                            countries = info.country_code,
                            countriesLength = countries.length,
                            i;

                        $(tagList).tagit({
                            beforeTagRemoved: removeTag
                        });

                        for(i = 0; i < countriesLength; i++) {
                            $(tagList).tagit('createTag', countries[i], countryType);
                        }

                        return true;
                    }()) || (function() {
                        console.log('result error');
                        return false;
                    }());
                }()) || (function() {
                    alert('connected error');
                    return false;
                }());
            };

            xhr.onloadend = function(){
                toggleInput();
            };

            xhr.send();
        }

        function addClickHandler() {
            var xhr = new XMLHttpRequest(),
                setTypeValue = $('#country_block').is(":checked") == true && 'block' || 'white',
                countrySelectIndex = countryList.selectedIndex,
                tagLabel = countryList[countrySelectIndex].text,
                tagValue = countryList.value,
                tagitData, isNewTag, restURL, responseResult;
/*
            console.log('setTypeValue:'+setTypeValue);
            console.log('countrySelectIndex:'+countryList.selectedIndex);
            console.log('tagLabel:'+countryList[countrySelectIndex].text);
            console.log('tagValue:'+countryList.value);
*/
            return setTypeValue === '' && (function(){
                makeModalAlert(_c2ms.notice, _c2ms.typeEmpty);
                return true;
            }()) || tagValue === 'none' && (function(){
                makeModalAlert(_c2ms.notice, _c2ms.countryEmpty);
                return true;
            }()) || (function(){
                tagitData = $(tagList).data('ui-tagit');
                isNewTag = tagitData._isNew(tagLabel);

                return !isNewTag && (function(){
                    makeModalAlert(_c2ms.notice, _c2ms.valueExists);
                    return true;
                }()) || (function(){
                    restURL = setTypeValue === 'block' && '/settings/updateblockcountry/' || '/settings/updatewhitecountry/';

                    xhr.open('POST', restURL + domainIndex);
                    xhr.setRequestHeader("Content-type", "application/json");

                    xhr.onloadstart = toggleInput;

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
                        toggleInput();

                        return responseResult && (function(){
                            $(tagList).tagit("createTag", tagLabel, setTypeValue);
                        }());
                    };

                    xhr.send(JSON.stringify({country_code: [tagValue]}));
                }());
            }());
        }

        function removeTag(e, data) {
            var xhr = new XMLHttpRequest(),
                tag = data.tag,
                tagValue = data.tagLabel,
                tagPrevClassList = tag[0].className,
                setTypeValue = $('#country_block').is(":checked") == true && 'block' || 'white',
                restURL = setTypeValue === 'block' && '/settings/updateblockcountry/' || '/settings/updatewhitecountry/',
                responseResult;
/*
            console.log(setTypeValue);
            console.log(restURL);
*/
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

            xhr.onloadend = function() {
                tag[0].className = tagPrevClassList;

                return responseResult && (function(){
                    tag.remove();
                }());
            };

            xhr.send(JSON.stringify({country_code: [tagValue]}));
        }

        function toggleInput() {
            countryRadioBlack.disabled = countryRadioBlack.disabled === false;
            countryRadioWhite.disabled = countryRadioWhite.disabled === false;
            countryList.disabled = countryList.disabled === false;
            addButton.disabled = addButton.disabled === false;
        }

        return {
            init: initialize
        }
    }());

    var toggleCountryOnOff = (function(){
        var restURL = '/settings/toggleCountryAccess/';

        function initialize() {
            var switchButton = document.getElementById('countryControl');
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
                        if (toggleValue == 'on') {
                            $('#countryOffDescription').css('display','none');
                            $('#setCountryArea').css('display','block');
                        } else {
                            $('#countryOffDescription').css('display','block');
                            $('#setCountryArea').css('display','none');
                        }
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

    var countryOnOffAction = (function (){
        function initialize() {
            setCountrySwitch();
        }

        function setCountrySwitch() {
            $(".btn_switch").bootstrapSwitch();
        }

        return {
            init: initialize
        }
    })();

    return (function(){
        settingCountry.init();
        countryOnOffAction.init();
        toggleCountryOnOff.init();
    })();
}());