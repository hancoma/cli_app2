(function() {
    var logActions, logListLoad, gridObject,
        filterSelect = {attack: [], ip: [], country: [], uri: []};

    logListLoad = (function() {
        var restURL = '/logs/attackLogs/',
            domainIndex = sitePath.getPathIndex(3),
            responseData;

        function initialize() {
            loadData();
        }

        function loadData(from, to) {
            var xhr = new XMLHttpRequest(),
                date = (from !== undefined && to !== undefined) && '?from=' + from + '&to=' + to || '',
                fullUrl = restURL + domainIndex + date;

            xhr.open('GET', fullUrl);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onload = function() {
                var response = this,
                    status = response.status;

                return status === 200 && (function() {
                    responseData = JSON.parse(response.responseText);
                    return setLogTable();
                }()) || (function() {
                    console.log('connected error');
                    return false;
                }());
            };

            xhr.send();
        }

        function setLogTable() {
            var result = responseData.result;

            return result && (function() {
                var attackLogData = responseData.result_info[0].attack_logs,
                    userActionData = responseData.result_info[0].user_action,
                    userBlockIps = userActionData.block_ips,
                    userBlockUrls = userActionData.attack_uris;

                return attackLogData !== null && (function() {
                    filterSelect = {attack: [], ip: [], country: [], uri: []};

                    gridObject = $("#event_data").dxDataGrid({
                        dataSource: attackLogData,
                        noDataText: c2ms('no_data'),
                        height: 500,
                        searchPanel: {
                            highlightSearchText: false
                        },
                        export: {
                            enabled: true,
                            fileName: (function(){
                                var year = new Date().getFullYear() + '',
                                    month = (new Date().getMonth() + 1) + '',
                                    day = new Date().getDay() + '',
                                    fileDate;

                                month = month < 10 && '0' + month;
                                day = day < 10 && '0' + day;

                                fileDate = year + month + day;

                               return '[Cloudbric] Logs__' + fileDate;
                               //return '[Cloudbric] Logs_' + selected_domain + '_' + fileDate;
                            }())
                        },
                        scrolling: {
                            mode: "virtual"
                        },
                        sorting: {
                            mode: "multiple"
                        },
                        allowColumnResizing: true,
                        showColumnLines: true,
                        columns: [
                            {
                                dataField: 'log_time',
                                caption: c2ms('time')
                            },
                            {
                                dataType: 'string',
                                dataField: 'attack',
                                caption: c2ms('attack'),
                                cellTemplate: function(container, options){
                                    var attack = options.data.attack,
                                        filter = filterSelect.attack,
                                        filterLength = filterSelect.attack.length,
                                        isAddedFilter = filter.indexOf(attack),
                                        select = $(document.getElementById('option_attack'));

                                    isAddedFilter < 0 && (function(){
                                        filter[filterLength] = attack;
                                    }()), logActions.setFilter('attack', select);

                                    container.text(attack);
                                }
                            },
                            {
                                dataField: 'ip',
                                caption: c2ms('address'),
                                cssClass: 'option_column',
                                cellTemplate: function(container, options){
                                    var ip = options.data.ip;

                                    (function(){
                                        var cell = container.context,
                                            button = document.createElement('button'),
                                            status = userBlockIps.indexOf(ip) > -1 && 'blocked' || '';

                                        button.className = 'btn btn-linered btn-xs event_pre_address option_button';
                                        button.setAttribute('type', 'button');
                                        button.setAttribute('data-type', 'ip');
                                        button.setAttribute('data-status', status);
                                        button.setAttribute('data-value', ip);
                                        button.innerText = ip;

                                        cell.appendChild(button);
                                    }()), (function(){
                                        var filter = filterSelect.ip,
                                            filterLength = filterSelect.ip.length,
                                            isAddedFilter = filter.indexOf(ip),
                                            select = $(document.getElementById('option_ip'));

                                        isAddedFilter < 0 && (function(){
                                            filter[filterLength] = ip;
                                        }());

                                        logActions.setFilter('ip', select);
                                    }());
                                }
                            },
                            {
                                dataField: 'country',
                                caption: c2ms('country'),
                                cellTemplate: function(container, options){
                                    var country = options.data.country;

                                    (function(){
                                        var filter = filterSelect.country,
                                            filterLength = filterSelect.country.length,
                                            isAddedFilter = filter.indexOf(country),
                                            select = $(document.getElementById('option_country'));

                                        isAddedFilter < 0 && (function(){
                                            filter[filterLength] = country;
                                        }()), logActions.setFilter('country', select);

                                        container.text(country);
                                    }()), (function(){
                                        var filter = filterSelect.country,
                                            filterLength = filterSelect.country.length,
                                            isAddedFilter = filter.indexOf(country),
                                            select = $(document.getElementById('option_country'));

                                        isAddedFilter < 0 && (function(){
                                            filter[filterLength] = country;
                                        }());

                                        logActions.setFilter('country', select);
                                    }());
                                }
                            },
                            {
                                dataField: 'uri',
                                caption: c2ms('url'),
                                cssClass: 'option_column',
                                cellTemplate: function(container, options){
                                    var url = options.data.uri;

                                    (function(){
                                        var cell = container.context,
                                            button = document.createElement('button'),
                                            status = userBlockUrls.indexOf(url) > -1 && 'blocked' || '';

                                        button.className = 'btn btn-lineblue btn-xs event_pre_url option_button';
                                        button.setAttribute('type', 'button');
                                        button.setAttribute('data-type', 'url');
                                        button.setAttribute('data-status', status);
                                        button.setAttribute('data-value', url);
                                        button.innerText = url;

                                        cell.appendChild(button);
                                        //logActions.toggleButton(cell, button, 'url');
                                    }()), (function(){
                                        var filter = filterSelect.uri,
                                            filterLength = filterSelect.uri.length,
                                            isAddedFilter = filter.indexOf(url),
                                            select = $(document.getElementById('option_uri'));

                                        isAddedFilter < 0 && (function(){
                                            filter[filterLength] = url;
                                        }());

                                        logActions.setFilter('uri', select);
                                    }());
                                }
                            },
                            {
                                dataField: 'action',
                                caption: c2ms('response'),
                                cellTemplate: function(container, options){
                                    var response = options.data.action,
                                        cell = container.context,
                                        image = document.createElement('img'),
                                        span = document.createElement('span'),
                                        src;

                                    span.className = 'response_text';

                                    return response === 'Blocked' && (function(){
                                        src = '/assets/img/event/blocked.png';
                                        image.setAttribute('src', src);

                                        span.innerText = response;

                                        cell.appendChild(image);
                                        cell.appendChild(span);
                                        return true;
                                    }()) || (function(){
                                        src = '/assets/img/event/flagged.png';
                                        image.setAttribute('src', src);

                                        span.innerText = 'Flagged';

                                        cell.appendChild(image);
                                        cell.appendChild(span);
                                        return true;
                                    }());
                                }
                            },
                            {
                                caption: c2ms('notes')
                            }
                        ]
                    }).dxDataGrid('instance');

                    return true;
                }());
            }()) || (function() {
                console.log('result error: log table');
                return false;
            }());
        }

        return {
            init: initialize,
            loadData: loadData
        }
    }());

    logActions = (function() {
        function initialize() {
            downloadExcel();
            initDatePicker();
            initTagit();
            selectDropdown();
            selectHide();
            dataSearch();
            setTooltip();
            initToggleButton();
        }

        function downloadExcel() {
            var button = document.getElementById('downloadButton'),
                exportButton = document.getElementsByClassName('dx-datagrid-export-button');

            button.addEventListener('click', function(){
                exportButton[0].click();
            });
        }

        function initDatePicker() {
            var datePickerButton = $(document.getElementById('daterangepicker'));

            setDatePicker(datePickerButton);

            datePickerButton.on('apply.daterangepicker', function() {
                changeLogDate(datePickerButton);
            });
        }

        function changeLogDate(el) {
            var data = el.data('daterangepicker'),
                from = data.startDate.format('YYYYMMDD'),
                to = data.endDate.format('YYYYMMDD');

            logListLoad.loadData(from, to);
        }

        function initTagit() {
            $("#event_tagit").tagit({
                afterTagAdded: function(event, ui){ setFilterChanged();}, // Tag 추가 됐을 때
                afterTagRemoved : function(event, ui){ // Tag 삭제 됐을 때
                    //checkbox 업데이트
                    $checkbox = $('input[data="'+ui.tagLabel+'"]');
                    $checkbox.prop("checked", false);

                    setFilterChanged();
                }
            });
        }

        function setFilter(key, div) {
            //체크박스에 체크했을 경우에는 filter를 새로 그리지 않는다.
            if(div.find('input:checkbox').is(":checked")) return false;

            div.html('');

            //Data가 있을 때만.
            if(filterSelect[key].length > 0) {
                for(var i in filterSelect[key]) {
                    var _id = key + i;
                    var _divtemp = $("<div></div>");
                    div.append(_divtemp);
                    _divtemp
                        .append($("<input type='checkbox'/>")
                            .attr("id", _id)
                            .attr("name", _id)
                            .attr("value", _id)
                            .attr("data", filterSelect[key][i])
                        );

                    if(filterSelect[key][i] != undefined && filterSelect[key][i].length > 22) {
                        _divtemp.append($("<label></label>")
                            .attr("for", _id)
                            .attr("title", filterSelect[key][i])
                            .text((filterSelect[key][i].substring(0, 22)) + "..")
                        );
                    } else {
                        _divtemp.append($("<label></label>")
                            .attr("for", _id)
                            .text(filterSelect[key][i])
                        );
                    }
                }
            }
        }

        function setFilterChanged(){
            var filterExp = [];
            var	$checkbox = "", filter_column = "";

            // check된 checkbox 가져와서 필터에 적용할 3차 array 생성.
            $(".filter_wrap").each(function(){
                var _column = $(this).attr("id").replace("filter_", "").toString();

                var tempExp = [];

                $(this).find('input[type="checkbox"]:checked').each(function()
                {
                    if (tempExp.length > 0) tempExp[tempExp.length] = "or";
                    tempExp[tempExp.length] = [_column, "=", $(this).attr("data")];
                });

                if(tempExp.length > 0) filterExp[filterExp.length] = tempExp;
            });

            gridObject.clearFilter();
            if (filterExp.length > 0) gridObject.filter(filterExp);
        }

        function selectDropdown() {
            $("#filter_attack").click(function(){ $("#option_attack").parents(".filter_options_wrap").toggle(); });
            $("#filter_ip").click(function(){ $("#option_ip").parents(".filter_options_wrap").toggle(); });
            $("#filter_country").click(function(){ $("#option_country").parents(".filter_options_wrap").toggle(); });
            $("#filter_uri").click(function(){ $("#option_uri").parents(".filter_options_wrap").toggle(); });

            $(document).on("change", "input:checkbox", function(){
                if($(this).is(":checked")){
                    $("#event_tagit").tagit("createTag", $(this).attr("data"));
                }
                else {
                    $("#event_tagit").tagit("removeTagByLabel", $(this).attr("data"));
                }

            });
        }

        function selectHide() {
            $(document).click(function(e){
                //-- filter dropdown
                setFilterHide("#filter_attack");
                setFilterHide("#filter_ip");
                setFilterHide("#filter_country");
                setFilterHide("#filter_uri");

                function setFilterHide(target){
                    if($(e.target).parents(target).size() === 0) {
                        var dropdown = $(target).find(".filter_options_wrap");
                        if(dropdown.css("display") === "block") {
                            dropdown.hide();
                        }
                    }
                }
            });
        }

        function dataSearch() {
            var input = document.getElementById('searchInput');

            input.addEventListener('keyup', function(){
                var _text = $(this).val();
                gridObject.searchByText(_text);
            });
        }

        function initToggleButton() {
            var table = document.getElementById('event_data');

            table.addEventListener('mouseover', function(e){
                var target = e.target,
                    nodeName = target.nodeName.toLowerCase(),
                    nodeDataType = target.getAttribute('data-type');

                (nodeName === 'button' && target.className.indexOf('option_button') > -1) && (function(){
                    var status = target.getAttribute('data-status'),
                        prevClassName = target.className,
                        buttonPrevText = target.innerText;

                    target.setAttribute('data-active', 'on');
                    target.className = prevClassName.replace(/btn-line/g, 'btn-');
                    target.innerText = nodeDataType === 'ip' && ( status === 'blocked' && 'Unblock' || 'Block' ) ||
                        nodeDataType === 'url' && ( status === 'blocked' && 'Unbypass' || 'BypassURL' ) ||
                        'error';

                    target.onmouseleave === null && (function(){
                        target.addEventListener('mouseleave', function(){
                            this.setAttribute('data-active', '');
                            this.className = prevClassName;
                            this.innerText = buttonPrevText;
                            this.blur();
                        });

                        target.onmouseleave = function(){};
                    }());
                }());
            });

            table.addEventListener('click', function(e){
                var target = e.target,
                    nodeName = target.nodeName.toLowerCase();

                (nodeName === 'button' && target.className.indexOf('option_button') > -1) && (function(){
                    var parent = target.parentElement,
                        type = target.getAttribute('data-type'),
                        wrapClass = type + '_options',
                        buttonWrap = parent.getElementsByClassName(wrapClass),
                        isButtonWrap = buttonWrap.length;

                    target.style.display = 'none';

                    isButtonWrap < 1 && (function(){
                        var wrap = document.createElement('div'),
                            button = document.createElement('button'),
                            noButton = button.cloneNode(),
                            yesButton = button.cloneNode(),
                            color = type === 'ip' && 'red' || 'blue';

                        wrap.className = 'option_wrap ' + wrapClass;

                        noButton.className = 'btn btn-xs btn-ip-active btn-no btn-line' + color;
                        noButton.innerText = 'NO';

                        yesButton.className = 'btn btn-xs btn-ip-active btn-yes btn-' + color;
                        yesButton.innerText = 'YES';

                        wrap.appendChild(noButton);
                        wrap.appendChild(yesButton);
                        parent.appendChild(wrap);

                        return true;
                    }()) || (function(){
                        buttonWrap[0].style.display = 'block';
                    }());
                }());

                (nodeName === 'button' && target.className.indexOf('btn-no') > -1) && (function(){
                    var wrap = target.parentElement,
                        ipButton = wrap.previousElementSibling;

                    wrap.style.display = 'none';
                    ipButton.style.display = 'block';
                }());

                (nodeName === 'button' && target.className.indexOf('btn-yes') > -1) && (function(){
                    var wrap = target.parentElement,
                        optionWrap = wrap.parentElement,
                        button = optionWrap.getElementsByClassName('option_button')[0];

                    toggleRequest(button, wrap);
                }());
            });
        }

        function toggleRequest(el, buttonWrap) {
            var xhr = new XMLHttpRequest(),
                domainIndex = sitePath.getPathIndex(3),
                dataStatus = el.getAttribute('data-status'),
                toggleStatus = dataStatus === '' && 'blocked' || '',
                valueType = el.getAttribute('data-type'),
                value = el.getAttribute('data-value'),
                method = dataStatus === 'blocked' && 'DELETE' || 'POST',
                restURL, responseResult, sendObject;

            valueType === 'ip' && (function(){
                restURL = '/settings/updateipcontrol/';
                sendObject = {type: 'black', ip: [value]};
                return true;
            }()) || valueType === 'url' && (function(){
                restURL = '/settings/updateextraurl/';
                sendObject = {url: [value]};
                return true;
            }()) || (function(){
                console.log('value type error');
                return false;
            }());

            xhr.open(method, restURL + domainIndex);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onload = function() {
                var status = this.status,
                    response = JSON.parse(this.responseText);

                return status === 200 && (function() {
                    responseResult = response.result;

                    console.log( 'ok' );
                    return true;
                }()) || (function() {
                    console.log('connected error');
                    return false;
                }());
            };

            xhr.onloadend = function(){
                return responseResult && (function(){
                    el.style.display = 'block';
                    buttonWrap.style.display = 'none';

                    $('[data-value="' + value + '"]').attr('data-status', toggleStatus);
                    return true;
                }());
            };

            xhr.send(JSON.stringify(sendObject));
        }

        function setTooltip() {
            makeTooltip($("#event_tooltip"), "bottom", "hide", c2ms("T_event"));
        }

        return {
            init: initialize,
            toggleButton: initToggleButton,
            setFilter: setFilter
        };
    }());

    return (function() {
        logListLoad.init();
        logActions.init();
    }());
}());