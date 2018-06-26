!function(){
    var recordActions = (function(){
        var restURL = sitePath.getPath(),
            typeSelect = document.getElementById('addType'),
            nameInput = document.getElementById('addName'),
            priorityInput = document.getElementById('addPriority'),
            weightInput = document.getElementById('addWeight'),
            portInput = document.getElementById('addPort'),
            valueInput = document.getElementById('addValue'),
            inputList = document.getElementsByClassName('value_input'),
            addButton = document.getElementById('addRecordButton'),
            recordIndex = '';

        function initialize() {
            var table = document.getElementById('recordTable'),
                modalWrap = document.getElementById('modals');

            typeSelect.addEventListener('change', selectChangedHandler);
            addButton.addEventListener('click', addClickHandler);
            table.addEventListener('click', deleteClickHandler);
            modalWrap.addEventListener('click', deleteRecordHandler);

            for(var i = 0, length = inputList.length; i < length; i++) {
                inputList[i].addEventListener('keyup', checkValues);
            }
        }

        function selectChangedHandler() {
            var type = this.value;

            for(var i = 1, length = inputList.length; i < length; i++) {
                inputList[i].value = '';
                inputList[i].disabled = true;
                inputList[i].style.display = 'none';
            }

            type === 'MX' && !function(){
                inputList[1].disabled = false;
                inputList[4].disabled = false;
                inputList[1].style.display = '';
                inputList[4].style.display = '';
            }() || type === 'SRV' && !function(){
                for(var i = 1; i < inputList.length; i++) {
                    inputList[i].disabled = false;
                    inputList[i].style.display = '';
                }
            }() || !function(){
                inputList[4].disabled = false;
                inputList[4].style.display = '';
                inputList[4].style.width = '';
            }();

            valueInput.setAttribute('placeholder', changeValuePlaceholder(type));
            checkValues();
        }

        function changeValuePlaceholder(type) {
            return {
                'A': c2ms('placeholder_a'),
                'TXT': c2ms('placeholder_txt'),
                'MX': c2ms('placeholder_mx'),
                'CNAME': c2ms('placeholder_cname'),
                'SRV': c2ms('placeholder_srv')
            }[type];
        }

        function addClickHandler() {
            var selectedIndex = typeSelect.selectedIndex,
                type = typeSelect[selectedIndex].value,
                name = nameInput.value,
                priority = priorityInput.value,
                weight = weightInput.value,
                port = portInput.value,
                value = valueInput.value;

            var xhr = new XMLHttpRequest(),
                responseResult;

            xhr.open('POST', restURL);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onload = function() {
                var status = this.status,
                    response = JSON.parse(this.responseText);

                status === 200 && !function() {
                    responseResult = response.result;

                    responseResult && !function() {
                        var responseData = response.result_info,
                            recordIndex = responseData.record_idx,
                            table, listLength, row, typeCell, nameCell, valueCell, statusCell, deleteCell, addValueRow;

                        table = document.getElementById('recordTable');
                        listLength = table.getElementsByClassName('record_row').length;

                        row = table.insertRow(listLength--);
                        row.id = 'record' + recordIndex;
                        row.className = 'record_row';
                        row.setAttribute('data-record-id', recordIndex);

                        typeCell = row.insertCell(0);
                        nameCell = row.insertCell(1);
                        valueCell = row.insertCell(2);
                        statusCell = row.insertCell(3);
                        deleteCell = row.insertCell(4);

                        addValueRow = '<div class="value_point"><ul id="valueList" class="value_list">';

                        switch(type) {
                            case 'A':
                            case 'CNAME':
                                addValueRow += '<li class="value_item point_label">' + c2ms('label_points_to') + '</li>';
                                break;
                            case 'MX':
                                addValueRow += '<li class="value_item point_value"><span class="point_label">' + c2ms('label_priority') + '</span> ' + priority + '</li>';
                                addValueRow += '<li class="value_item point_label">' + c2ms('label_points_to') + '</li>';
                                break;
                            case 'SRV':
                                addValueRow += '<li class="value_item point_value"><span class="point_label">' + c2ms('label_priority') + '</span> ' + priority + '</li>';
                                addValueRow += '<li class="value_item point_value"><span class="point_label">' + c2ms('label_weight') + '</span> ' + priority + '</li>';
                                addValueRow += '<li class="value_item point_value"><span class="point_label">' + c2ms('label_port') + '</span> ' + priority + '</li>';
                                addValueRow += '<li class="value_item point_label">' + c2ms('label_target') + '</li>';
                                break;
                        }

                        addValueRow += '<li class="value_item">' + value + '</li></ul></div>';

                        valueCell.className = 'data_value';
                        typeCell.innerText = type;
                        nameCell.innerText = name;
                        valueCell.innerHTML = addValueRow;
                        statusCell.innerHTML = (type === 'A' || type === 'CNAME') && '<a href="/addsite/add/' + name + '" target="_self" class="btn btn-linegray btn-xs"><img src="/assets/img/settings/dns_addcb.png" alt="addcb"></a>' || '';
                        deleteCell.innerHTML = '<button class="btn btn-linegray btn-xs delete_button" data-record-id="' + recordIndex + '"><img src="/assets/img/settings/DNS_Delete_01.png" alt="delete"></button>';

                        for(var i = 0, length = inputList.length; i < length; i++) {
                            inputList[i].value = '';
                        }
                    }() || !function() {
                        console.log('result error');
                    }();
                }() || status === 400 && !function() {
                    makeModalAlert(c2ms('modal_title_notice'), c2ms('dns_invalid_record'));
                }() || status === 500 && !function() {
                    makeModalAlert(c2ms('modal_title_notice'), c2ms('dns_db_error'));
                }() || status === 409 && !function() {
                    makeModalAlert(c2ms('modal_title_notice'), c2ms('dns_exist_record'));
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.send(JSON.stringify({host: name, class: "IN", type: type, target: value, pri: priority, weight: weight, port: port}));
        }

        function deleteClickHandler(event) {
            var target = event.target,
                isTarget = event.target.className.match('delete_button');

            isTarget && !function(){
                var useRecord = target.getAttribute('data-using');

                useRecord === 'true' && !function(){
                    var targetModal = document.getElementById('redirectModal'),
                        link = document.getElementById('redirectButtonLink'),
                        domainIndex = target.getAttribute('data-domain-id');

                    recordIndex = target.getAttribute('data-record-id');

                    targetModal.setAttribute('data-record-id', recordIndex);
                    link.setAttribute('href', '/settings/delete/' + domainIndex);
                    $(targetModal).modal({"keyboard": true}).modal('show');
                }() || !function(){
                    var targetModal = document.getElementById('deleteModal');

                    recordIndex = target.getAttribute('data-record-id');

                    targetModal.setAttribute('data-record-id', recordIndex);
                    $(targetModal).modal({"keyboard": true}).modal('show');
                }();
            }();
        }

        function deleteRecordHandler(event) {
            var isTarget = event.target.className.match('delete_record');

            isTarget && !function(){
                var xhr = new XMLHttpRequest(),
                    responseResult;

                xhr.open('DELETE', restURL);
                xhr.setRequestHeader("Content-type", "application/json");

                xhr.onloadstart = function() {
                    $("#deleteModal").modal({"keyboard": true}).modal('hide');
                };

                xhr.onload = function(){
                    var status = this.status,
                        response = JSON.parse(this.responseText);

                    status === 200 && !function(){
                        responseResult = response.result;

                        responseResult && !function(){
                            console.log('ok');
                        }() || !function(){
                            console.log('result error');
                        }();
                    }() || !function(){
                        console.log('connected error');
                    }();
                };

                xhr.onloadend = function(){
                    responseResult && !function(){
                        var table = document.getElementById('recordTable'),
                            targetRow = document.getElementById('record' + recordIndex),
                            rowIndex = targetRow.rowIndex;

                        table.deleteRow(rowIndex);
                    }();
                };

                xhr.send(JSON.stringify({record_idx: recordIndex}));
            }();
        }

        function checkValues() {
            var type = typeSelect[typeSelect.selectedIndex].value,
                isNameValue = nameInput.value !== '',
                isPriorityValue = priorityInput.value !== '',
                isWeightValue = weightInput.value !== '',
                isPortValue = portInput.value !== '',
                isPointValue = valueInput.value !== '',
                MX = type === 'MX' && (isNameValue && isPriorityValue && isPointValue),
                SRV = type === 'SRV' && (isNameValue && isPriorityValue && isWeightValue && isPortValue && isPointValue),
                otherType = type !== 'MX' && type !== 'SRV',
                status = false;

            status = MX && true || SRV && true || (otherType && (isNameValue && isPointValue)) && true || false;

            status && !function(){
                addButton.disabled = false;
            }() || !function(){
                addButton.disabled = true;
            }();
        }

        return {
            init: initialize
        };
    }());

    recordActions.init();
}();