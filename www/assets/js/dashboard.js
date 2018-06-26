(function() {
    var dashboardLoad = (function() {
        var domainIndex = sitePath.getPathIndex(3),
            donutPalette = ['#EC7063', '#AF7AC5', '#45B39D', '#F4D03F', '#5DADE2'];

        function initialize() {
            loadData();
            setMonthBandwidth();
        }

        function loadData(from, to) {
            var date = (from !== undefined && to !== undefined) && '?from=' + from + '&to=' + to || '';

            setOverviewPreviousTab(date);
            setOverviewNextTab(date);
            setAttackIpCountries(date);
            setAttackPurposes(date);
            setAttackUrls(date);
            setRecentAttacks(date);
            setVisitsCountries(date);
            setThroughputLogs(date);
            setTrafficLogs(date);
        }

        function setOverviewPreviousTab(date) {
            var xhr = new XMLHttpRequest(),
                path = '/dashboard/visitsattacks/' + domainIndex + date,
                renderWrap = document.getElementById('overview_graph1'),
                loadSpinner = renderWrap.getElementsByClassName('data_loading')[0],
                attackCount = document.getElementById('overview_num_attacks'),
                visitCount = document.getElementById('overview_num_visits');

            xhr.open('GET', path);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                attackCount.innerText = '-';
                visitCount.innerText = '-';
                loadSpinner.className = 'data_loading';
            };

            xhr.onload = function() {
                var response = this,
                    status = response.status,
                    responseData = JSON.parse(response.responseText);

                status === 200 && !function() {
                    var data = responseData.result_info,
                        result = responseData.result || false;

                    result && !function() {
                        var count = data.total_count,
                            logData = data.total_log;

                        logData !== null && !function() {
                            var logDataLength = logData.length;

                            attackCount.textContent = commifyNumber(count.attacks);
                            visitCount.textContent = commifyNumber(count.visits);

                            var arr_attack = [];
                            var arr_visit = [];

                            for(var i = 0; i < logDataLength; i++) {
                                arr_attack[i] = logData[i].attack;
                                arr_visit[i] = logData[i].visit;
                            }

                            var max_attack = Math.max.apply(null, arr_attack);
                            var max_visit = Math.max.apply(null, arr_visit);

                            var overview_attack_graph_max = max_attack+(max_attack*2);
                            if(max_attack == 0) overview_attack_graph_max = 0;

                            $(renderWrap).dxChart({
                                dataSource: logData, //데이터
                                commonSeriesSettings:{  //하단 데이터
                                    argumentField: "date",
                                    format: "MM/dd",
                                    type: "area",
                                    /*border: {
                                        "visible" : true,
                                    },*/
                                },
                                argumentAxis: {
                                    label: {
                                        customizeText: function () {
                                            var date = this.value.substring(5,10);
                                            return date.replace(/-/g,'/');
                                        }
                                    }
                                },
                                height:300,
                                series: [
                                    { //그래프 데이터 설정
                                        valueField: "visits",
                                        name: "Visits",
                                        color: "#5dade2",
                                        opacity:0.9,
                                    },
                                    { //이게 있어야 오른쪽에 축이 생김
                                        axis: "attacks",
                                        valueField: "attacks",
                                        name: "Attacks",
                                        color: "#ec7063",
                                        opacity:0.9,
                                    },
                                ],
                                valueAxis: [{
                                    max: max_visit,
                                    grid: {visible: true},
                                    title: {text: c2ms('visits')}
                                }, {
                                    max: overview_attack_graph_max,
                                    name: "attacks",
                                    position: "right",
                                    grid: {visible: true},
                                    title: {text: c2ms('attacks'), color: "red"}
                                }],
                                tooltip: {
                                    enabled: true,
                                    shared: true,
                                    precision: 1,
                                    customizeTooltip: function (arg) {
                                        var items = arg.valueText.split("\n"),
                                            color = arg.point.getColor();
                                        $.each(items, function(i, item) {
                                            if(item.indexOf(arg.seriesName) == 0) {
                                                items[i] = $("<b>")
                                                    .text(item)
                                                    .css("color", color)
                                                    .prop("outerHTML");
                                            }
                                        });
                                        items[2] = "[ " + arg.argument + " ]";
                                        return { text: items.join("\n") };
                                    }
                                },
                                legend: {
                                    visible: false
                                },
                                onDrawn: function() {
                                    $("#overview_1").find(".overview_nodata").hide();
                                },
                            });
                        }();
                    }() || !function() {
                        console.log('result error: visits attacks');
                    }();
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.onloadend = function() {
                loadSpinner.className = 'data_loading hide_loading';
            };

            xhr.send();
        }

        function setOverviewNextTab(date) {
            var xhr = new XMLHttpRequest(),
                path = '/dashboard/visitorshackers/' + domainIndex + date,
                renderWrap = document.getElementById('overview_graph2'),
                loadSpinner = renderWrap.getElementsByClassName('data_loading')[0],
                attackCount = document.getElementById('overview_num_hackers'),
                visitCount = document.getElementById('overview_num_visitors');

            xhr.open('GET', path);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                attackCount.innerText = '-';
                visitCount.innerText = '-';
                loadSpinner.className = 'data_loading';
            };

            xhr.onload = function() {
                var response = this,
                    status = response.status,
                    responseData = JSON.parse(response.responseText);

                status === 200 && !function() {
                    var data = responseData.result_info,
                        result = responseData.result || false;

                    result && !function() {
                        var count = data.total_count,
                            logData = data.total_log;

                        logData !== null && !function() {
                            var logDataLength = logData.length;

                            attackCount.textContent = commifyNumber(count.attackers);
                            visitCount.textContent = commifyNumber(count.visitors);

                            var arr_hacker = [];
                            var arr_visitor = [];

                            for(var i=0; i<logDataLength; i++){
                                arr_hacker[i] = logData[i].hacker;
                                arr_visitor[i] = logData[i].visitor;
                            }

                            var max_hacker = Math.max.apply(null, arr_hacker);
                            var max_visitor = Math.max.apply(null, arr_visitor);

                            var overview_hacker_graph_max = max_hacker+(max_hacker*3);
                            if(max_hacker == 0) overview_hacker_graph_max = 0;

                            $(renderWrap).dxChart({
                                dataSource: logData, //데이터
                                commonSeriesSettings:{ //하단 데이터
                                    argumentField: "date",
                                    type: "area",
                                    /*border: {
                                        "visible" : true,
                                    },*/
                                },
                                argumentAxis: {
                                    label: {
                                        customizeText: function () {
                                            var date = this.value.substring(5,10);
                                            return date.replace(/-/g,'/');
                                        }
                                    }
                                },
                                series: [{ //그래프 설정
                                    valueField: "visitors",
                                    name: "Visitors",
                                    color: "#5dade2",
                                    opacity:0.9,
                                }, { //이게 있어야 오른쪽에 축이 생김
                                    axis: "attackers",
                                    valueField: "attackers",
                                    name: "Hackers",
                                    color: "#ec7063",
                                    opacity:0.9,
                                }
                                ],
                                valueAxis: [{
                                    min:0,
                                    grid: {visible: true},
                                    title: {text: c2ms('visitors')}
                                }, {
                                    max: overview_hacker_graph_max, //위에서 60% 내림 (MAX + (MAX * 4))
                                    name: "attackers",
                                    position: "right",
                                    grid: {visible: true},
                                    title: {text: c2ms('hackers'), color: "red"}
                                }],
                                tooltip: {
                                    enabled: true,
                                    shared: true,
                                    precision: 1,
                                    customizeTooltip: function (arg) {
                                        var items = arg.valueText.split("\n"),
                                            color = arg.point.getColor();
                                        $.each(items, function(index, item) {
                                            if(item.indexOf(arg.seriesName) == 0) {
                                                items[index] = $("<b>")
                                                    .text(item)
                                                    .css("color", color)
                                                    .prop("outerHTML");
                                            }
                                        });
                                        items[2] = "[ " + arg.argument + " ]";
                                        return { text: items.join("\n") };
                                    }
                                },
                                legend: {
                                    visible: false
                                },
                                onDrawn: function() {
                                    $("#overview_2").find(".overview_nodata").hide();
                                },
                            });

                            $("#overview_graph2").find(".dxc-val-axis:nth-child(2)").find("text").css("fill", "#288dbf");
                            $("#overview_graph2").find(".dxc-val-axis:nth-child(3)").find("text").css("fill", "#da5656");
                        }();
                    }() || !function() {
                        console.log('result error: visitors hackers');
                    }();
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.onloadend = function() {
                loadSpinner.className = 'data_loading hide_loading';
            };

            xhr.send();
        }

        function setAttackIpCountries(date) {
            var xhr = new XMLHttpRequest(),
                path = '/dashboard/attackipcountries/' + domainIndex + date,
                renderWrap = document.getElementById('attackMapWrap'),
                loadSpinner = renderWrap.getElementsByClassName('data_loading')[0];

            xhr.open('GET', path);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                loadSpinner.className = 'data_loading';
            };

            xhr.onload = function() {
                var response = this,
                    status = response.status,
                    responseData = JSON.parse(response.responseText);

                status === 200 && !function() {
                    setAttackIps(responseData);
                    setAttackCountries(responseData);
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.onloadend = function() {
                loadSpinner.className = 'data_loading hide_loading';
            };

            xhr.send();
        }

        function setAttackIps(response) {
            var res = JSON.parse(response.ip),
                data = res.result_info,
                result = res.result || false,
                table = document.getElementById('dash_attack_ip_table'),
                tableRows = table.getElementsByTagName('tr'),
                addRows = '';

            for(var i = 0; i < 5; i ++) {
                addRows = '';
                addRows += '<tr>';
                addRows += '<td>';
                addRows += '<span class="dash_map_order_num">-</span>';
                addRows += '</td>';
                addRows += '<td>';
                addRows += '<span class="attack_map_ip_value"></span>';
                addRows += '</td>';
                addRows += '<td>';
                addRows += '<button class="btn btn-gray btn-xs btn_map_action" type="button">-</button>';
                addRows += '</td>';
                addRows += '</tr>';

                tableRows[i].innerHTML = addRows;
            }

            result && !function() {
                var ipsData = data.top_attack_ips;

                ipsData !== null && !function() {
                    var ipsDataLength = ipsData.length,
                        orderNumber = 0;

                    var attackPalette = ['#ec7063', '#ee8377', '#f1978e', '#f4aca4', '#f7c1bb'];

                    var attackMapIpData = {
                        type: "FeatureCollection",
                        features: $.map(ipsData, function(data) {
                            return {
                                type: "Feature",
                                geometry: {
                                    type: "Point",
                                    coordinates: data.coordinates
                                },
                                properties: {
                                    text: data.country,
                                    ip: data.ip,
                                    value: data.count
                                }
                            }
                        })
                    };

                    $('#dash_attack_map_ip').dxVectorMap({
                        layers: [{
                            data: DevExpress.viz.map.sources.world,
                            color: 'white',
                            borderColor: "#c3c3c3",
                            hoverEnabled: false,
                        }, {
                            name: "bubbles",
                            data: attackMapIpData,
                            elementType: "bubble",
                            color: "#e74c3c",
                            dataField: "value",
                            minSize: 20,
                            maxSize: 60,
                            customize: function(elements) {
                                $.each(elements, function(i, element) {
                                    element.applySettings({
                                        color: attackPalette[i],
                                    });
                                });
                            },
                        }],
                        tooltip: {
                            enabled: true,
                            customizeTooltip: function(arg) {
                                if(arg.layer.type === "marker") {
                                    return {
                                        text: arg.attribute("ip") + " (" + arg.attribute("text") + ") : " + arg.attribute("value"),
                                    };
                                }
                            }
                        },
                        bounds: [-180, 85, 180, -60],
                        background: {
                            color: "#ddd"
                        },
                        controlBar: {enabled: true},
                        wheelEnabled: false,
                        onDrawn: function() {
                            $(".dash_attack_ip_nodata").hide();
                        },
                    });

                    for(var i = 0; i < ipsDataLength; i++) {
                        ipsData[i] && !function(index) {
                            orderNumber = i + 1;

                            addRows = '';
                            addRows += '<tr>';
                            addRows += '<td>';
                            addRows += '<span class="dash_map_order_num">' + orderNumber + '</span>';
                            addRows += '</td>';
                            addRows += '<td>';
                            addRows += '<span class="attack_map_ip_value" pre-add="false">' + ipsData[index].ip + '</span>';
                            addRows += '<span class="label label-pill label-linegray">' + ipsData[index].country + '</span>';
                            addRows += '</td>';
                            addRows += '<td>';
                            addRows += '<button class="btn btn-gray btn-xs btn_map_action attack_toggle_ip" pre-add="false" data-value="' + ipsData[index].ip + '" data-status="' + ipsData[index].action + '" type="button">' + ipsData[index].count + '</button>';
                            addRows += '</td>';
                            addRows += '</tr>';

                            tableRows[index].innerHTML = addRows;
                        }(i);
                    }
                }();
            }() || !function() {
                console.log('result error: attack ips');
            }();
        }

        function setAttackCountries(response) {
            var res = JSON.parse(response.country),
                data = res.result_info,
                result = res.result || false,
                table = document.getElementById('dash_attack_country_table'),
                tableRows = table.getElementsByTagName('tr'),
                addRows = '';

            for(var i = 0; i < 5; i++) {
                addRows = '';
                addRows += '<tr>';
                addRows += '<td>';
                addRows += '<span class="dash_map_order_num">-</span>';
                addRows += '</td>';
                addRows += '<td>';
                addRows += '<span class="attack_map_country_value"></span>';
                addRows += '</td>';
                addRows += '<td>';
                addRows += '<input class="btn btn-gray btn-xs btn_map_action" value="-" type="button">';
                addRows += '</td>';
                addRows += '</tr>';

                tableRows[i].innerHTML = addRows;
            }

            result && !function() {
                var contriesData = data.top_attack_countries;

                contriesData !== null && !function() {
                    var contriesDataLength = contriesData.length,
                        orderNumber = 0;

                    var attackPalette = ['#ec7063', '#ee8377', '#f1978e', '#f4aca4', '#f7c1bb'];

                    var arr_country_code = [];
                    var arr_country_value = [];
                    for(var i = 0; i < contriesDataLength; i++) {
                        arr_country_code[i] = contriesData[i].country_code;
                        arr_country_value[i] = contriesData[i].count;
                    }

                    $('#dash_attack_map_country').dxVectorMap({
                        mapData: DevExpress.viz.map.sources.world,
                        bounds: [-180, 85, 180, -60],
                        areaSettings: {
                            color: 'white',
                            borderColor: "#c3c3c3",
                            selectionMode: 'multiple',
                            customize: function(arg) {

                                var country = arr_country_code.indexOf(arg.attribute("iso_a2"));
                                if(country >= 0) {

                                    arg.attribute("attack-country", arr_country_value[country]);

                                    return {
                                        color: attackPalette[country]
                                    };
                                } else {
                                    return {
                                        color: "#fff"
                                    };
                                }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            customizeTooltip: function(arg) {
                                if(arg.attribute("attack-country")) {
                                    return {text: arg.attribute("name") + " : " + arg.attribute("attack-country")};
                                }
                            }
                        },
                        background: {
                            color: "#ddd"
                        },
                        controlBar: {enabled: true},
                        wheelEnabled: false,
                        onDrawn: function() {
                            $(".dash_attack_country_nodata").hide();
                        },
                    });

                    for(var i = 0; i < contriesDataLength; i++) {
                        contriesData[i] && !function(index) {
                            orderNumber = i + 1;

                            addRows = '';
                            addRows += '<tr>';
                            addRows += '<td>';
                            addRows += '<span class="dash_map_order_num">' + orderNumber + '</span>';
                            addRows += '</td>';
                            addRows += '<td>';
                            addRows += '<span class="attack_map_country_value" pre-add="false">' + contriesData[index].country + '</span>';
                            addRows += '</td>';
                            addRows += '<td>';
                            addRows += '<input type="button" id="' + contriesData[index].country_code + '" class="btn btn-gray btn-xs btn_map_action attack_toggle_country" pre-add="false" value="' + contriesData[index].count + '" data="' + contriesData[index].count + '">';
                            addRows += '</td>';
                            addRows += '</tr>';

                            tableRows[index].innerHTML = addRows;
                        }(i);
                    }
                }();
            }() || !function() {
                console.log('result error: attack countries');
            }();
        }

        function setAttackPurposes(date) {
            var xhr = new XMLHttpRequest(),
                path = '/dashboard/attackpurposes/' + domainIndex + date,
                renderWrap = document.getElementById('purposeGraphWrap'),
                loadSpinner = renderWrap.getElementsByClassName('data_loading')[0];

            xhr.open('GET', path);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                loadSpinner.className = 'data_loading';
            };

            xhr.onload = function() {
                var response = this,
                    status = response.status,
                    responseData = JSON.parse(response.responseText);

                status === 200 && !function() {
                    var data = responseData.result_info,
                        result = responseData.result || false,
                        $graphElement = $('#dash_purpose_graph'),
                        table = document.getElementById('attackPurposeTable'),
                        tableRows = table.getElementsByTagName('tr'),
                        addRows = '';

                    for(var i = 0; i < 5; i ++) {
                        addRows = '';
                        addRows += '<tr>';
                        addRows += '<td>';
                        addRows += '<svg width="30" height="30">';
                        addRows += '<rect width="30" height="30" fill="#c3c3c3"></rect>';
                        addRows += '</svg>';
                        addRows += '</td>';
                        addRows += '<td class="pie_graph_info_text">-</td>';
                        addRows += '<td></td>';
                        addRows += '</tr>';

                        tableRows[i].innerHTML = addRows;
                    }

                    $graphElement.empty();
                    $graphElement.removeData();

                    $("#dash_purpose").find(".pie_graph_nodata").show();

                    result && !function() {
                        var purposeData = data.top_attack_purposes,
                            purposeDataLength = purposeData.length;

                        purposeDataLength > 0 && !function() {
                            var pieChart, series, points;

                            DevExpress.viz.registerPalette('donutPalette', donutPalette);

                            pieChart = $graphElement.dxPieChart({
                                dataSource: purposeData,
                                legend: {visible: false},
                                tooltip: {
                                    enabled: true,
                                    customizeTooltip: function() {
                                        return {text: this.argumentText.toString() + " (" + this.valueText + ")"};
                                    }
                                },
                                series: [{
                                    type: "pie",
                                    name: 'purpose',
                                    argumentField: "purpose",
                                    valueField: "count",
                                    startAngle: 90,
                                }],
                                onDrawn: function() {
                                    $("#dash_purpose").find(".pie_graph_nodata").hide();
                                },
                                palette: "donutPalette"
                            }).dxPieChart('instance');

                            series = pieChart.getSeriesByName('purpose');
                            points = series.getAllPoints();
                            /*var donutPaletteHover = series._patterns;*/

                            for(var i = 0; i < purposeDataLength; i++) {
                                purposeData[i] && !function(index) {
                                    var purpose = purposeData[index].purpose,
                                        purposeTipName = (purpose.toLowerCase()).replace(/ /g, '_'),
                                        detailImage;

                                    addRows = '';
                                    addRows += '<tr>';
                                    addRows += '<td>';
                                    addRows += '<svg width="30" height="30">';
                                    addRows += '<rect width="30" height="30" fill="' + donutPalette[index] + '"></rect>';
                                    addRows += '</svg>';
                                    addRows += '</td>';
                                    addRows += '<td class="pie_graph_info_text">' + purpose + ' (' + purposeData[index].count + ')</td>';
                                    addRows += '<td><span id="purpose' + index + '" class="pie_graph_info_img"><img src="/assets/img/dashboard/attack_purpose_01.png"></span></td>';
                                    addRows += '</tr>';

                                    tableRows[index].innerHTML = addRows;
                                    detailImage = document.getElementById('purpose' + index);

                                    makeTooltip($(detailImage), 'right', "hide", c2ms('description_' + purposeTipName));

                                    tableRows[index].addEventListener('mouseover', function(){
                                        points[index].showTooltip();
                                        /*series._markersGroup.element.children[index].setAttribute('fill', donutPaletteHover[index].id)*/
                                    });

                                    tableRows[index].addEventListener('mouseleave', function(){
                                        points[index].hideTooltip();
                                        /*series._markersGroup.element.children[index].setAttribute('fill', donutPalette[index])*/
                                    });
                                }(i);
                            }
                        }();
                    }() || !function() {
                        console.log('result error: attack purposes');
                    }();
                }() || !function() {
                    console.log('connected error');
                    return false;
                }();
            };

            xhr.onloadend = function() {
                loadSpinner.className = 'data_loading hide_loading';
            };

            xhr.send();
        }

        function setAttackUrls(date) {
            var xhr = new XMLHttpRequest(),
                path = '/dashboard/topattackurls/' + domainIndex + date,
                renderWrap = document.getElementById('urlsGraphWrap'),
                loadSpinner = renderWrap.getElementsByClassName('data_loading')[0];

            xhr.open('GET', path);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                loadSpinner.className = 'data_loading';
            };

            xhr.onload = function() {
                var response = this,
                    status = response.status,
                    responseData = JSON.parse(response.responseText);

                status === 200 && !function() {
                    var data = responseData.result_info,
                        result = responseData.result || false,
                        $graphElement = $("#dash_urls_graph"),
                        table = document.getElementById('attackUrlsTable'),
                        tableRows = table.getElementsByTagName('tr'),
                        addRows = '';

                    for(var i = 0; i < 5; i ++) {
                        addRows = '';
                        addRows += '<td>';
                        addRows += '<svg width="30" height="30">';
                        addRows += '<rect width="30" height="30" fill="#c3c3c3"></rect>';
                        addRows += '</svg>';
                        addRows += '</td>';
                        addRows += '<td class="pie_graph_info_text">-</td>';

                        tableRows[i].innerHTML = addRows;
                    }

                    $graphElement.empty();
                    $graphElement.removeData();

                    $("#dash_urls").find(".pie_graph_nodata").show();

                    result && !function() {
                        var attackData = data.top_attack_uris,
                            attackDataLength = attackData.length;

                        attackDataLength > 0 && !function() {
                            var pieChart, series, points;

                            DevExpress.viz.registerPalette('donutPalette', donutPalette);

                            pieChart = $("#dash_urls_graph").dxPieChart({
                                dataSource: attackData,
                                legend: {visible: false},
                                tooltip: {
                                    enabled: true,
                                    customizeTooltip: function() {
                                        return {text: this.argumentText.toString() + " (" + this.valueText + ")"};
                                    }
                                },
                                series: [{
                                    type: "pie",
                                    name: 'url',
                                    argumentField: "uri",
                                    valueField: "count",
                                    startAngle: 90
                                }],
                                onDrawn: function() {
                                    $("#dash_urls").find(".pie_graph_nodata").hide();
                                },
                                palette: "donutPalette"
                            }).dxPieChart('instance');

                            series = pieChart.getSeriesByName('url');
                            points = series.getAllPoints();
                            /*var donutPaletteHover = series._patterns;*/

                            for(var i = 0; i < 5; i++) {
                                attackData[i] && !function(index) {
                                    addRows = '';
                                    addRows += '<td>';
                                    addRows += '<svg width="30" height="30">';
                                    addRows += '<rect width="30" height="30" fill="' + donutPalette[i] + '"></rect>';
                                    addRows += '</svg>';
                                    addRows += '</td>';
                                    addRows += '<td class="pie_graph_info_text">' + attackData[i].uri + ' (' + attackData[i].count + ')</td>';

                                    tableRows[index].innerHTML = addRows;

                                    tableRows[index].addEventListener('mouseover', function(){
                                        points[index].showTooltip();
                                        /*series._markersGroup.element.children[index].setAttribute('fill', donutPaletteHover[index].id)*/
                                    });

                                    tableRows[index].addEventListener('mouseleave', function(){
                                        points[index].hideTooltip();
                                        /*series._markersGroup.element.children[index].setAttribute('fill', donutPalette[index])*/
                                    });
                                }(i);
                            }
                        }();
                    }() || !function() {
                        console.log('result error: attack urls');
                    }();
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.onloadend = function() {
                loadSpinner.className = 'data_loading hide_loading';
            };

            xhr.send();
        }

        function setRecentAttacks(date) {
            var xhr = new XMLHttpRequest(),
                path = '/dashboard/recentattacks/' + domainIndex + date,
                renderWrap = document.getElementById('recentWrap'),
                loadSpinner = renderWrap.getElementsByClassName('data_loading')[0];

            xhr.open('GET', path);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                loadSpinner.className = 'data_loading';
            };

            xhr.onload = function() {
                var response = this,
                    status = response.status,
                    responseData = JSON.parse(response.responseText);

                status === 200 && !function() {
                    var data = responseData.result_info[0],
                        result = responseData.result || false,
                        tableBody = document.getElementById('recent_attack_data');

                    tableBody.innerHTML = '<tr><td colspan="5">-</td></tr>';

                    result && !function() {
                        var logData = data.attack_logs,
                            logDataLength = logData.length;

                        logDataLength > 0 && !function() {
                            var addRows = '';

                            for(var i = 0; i < logDataLength; i++) {
                                addRows += '<tr>';
                                addRows += '<td>' + logData[i].log_time + '</td>';
                                addRows += '<td>' + logData[i].attack + '</td>';
                                addRows += '<td>' + logData[i].ip + '</td>';
                                addRows += '<td>' + logData[i].country + '</td>';
                                addRows += '<td>' + logData[i].uri + '</td>';
                                addRows += '</tr>';
                            }

                            tableBody.innerHTML = addRows;
                        }();
                    }() || !function() {
                        console.log('result error: recent attack');
                    }();
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.onloadend = function() {
                loadSpinner.className = 'data_loading hide_loading';
            };

            xhr.send();
        }

        function setVisitsCountries(date) {
            var xhr = new XMLHttpRequest(),
                path = '/dashboard/visitcountries/' + domainIndex + date,
                renderWrap = document.getElementById('visitMapWrap'),
                loadSpinner = renderWrap.getElementsByClassName('data_loading')[0];

            xhr.open('GET', path);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                loadSpinner.className = 'data_loading';
            };

            xhr.onload = function() {
                var response = this,
                    status = response.status,
                    responseData = JSON.parse(response.responseText);

                status === 200 && !function() {
                    var data = responseData.result_info,
                        result = responseData.result || false,
                        table = document.getElementById('dash_visit_map_table'),
                        tableRows = table.getElementsByTagName('tr'),
                        addRows = '';

                    for(var i = 0; i < 5; i++) {
                        addRows = '';
                        addRows += '<tr>';
                        addRows += '<td>';
                        addRows += '<span class="dash_map_order_num">-</span>';
                        addRows += '</td>';
                        addRows += '<td>';
                        addRows += '<span class="attack_map_country_value"></span>';
                        addRows += '</td>';
                        addRows += '<td>';
                        addRows += '<div class="visit_map_num">-</div>';
                        addRows += '</td>';
                        addRows += '</tr>';

                        tableRows[i].innerHTML = addRows;
                    }

                    result && !function() {
                        var contriesData = data.top_visit_countries;

                        contriesData !== null && !function() {
                            var contriesDataLength = contriesData.length,
                                orderNumber = 0;

                            var visitPalette = ['#1473b1', '#3a97c4', '#5ca7cb', '#87bad2', '#b0d1e0'];

                            //국가코드만 array에 담기
                            var arr_country_code = [];
                            var arr_country_value = [];

                            for(var i = 0; i < contriesDataLength; i++) {
                                arr_country_code[i] = contriesData[i].country_code;
                                arr_country_value[i] = contriesData[i].count;
                            }

                            $('#dash_visit_map_vector').dxVectorMap({
                                mapData: DevExpress.viz.map.sources.world,
                                bounds: [-180, 85, 180, -60],
                                areaSettings: {
                                    color: 'white',
                                    borderColor: "#c3c3c3",
                                    selectionMode: 'multiple',
                                    customize: function(arg) {

                                        var country = arr_country_code.indexOf(arg.attribute("iso_a2"));
                                        if(country >= 0) {
                                            arg.attribute("visit-country", arr_country_value[country]);
                                            return {
                                                color: visitPalette[country]
                                            };
                                        }
                                    }
                                },
                                tooltip: {
                                    enabled: true,
                                    customizeTooltip: function(arg) {
                                        if(arg.attribute("visit-country")) {
                                            return {text: arg.attribute("name") + " : " + arg.attribute("visit-country")};
                                        }
                                    }
                                },
                                background: {
                                    color: "#ddd"
                                },
                                controlBar: {enabled: true},
                                wheelEnabled: false,
                                onDrawn: function() {
                                    $(".dash_visit_map_nodata").hide();
                                },
                            });

                            for(var i = 0; i < contriesDataLength; i++) {
                                contriesData[i] && !function(index) {
                                    orderNumber = i + 1;

                                    addRows = '';
                                    addRows += '<tr>';
                                    addRows += '<td>';
                                    addRows += '<span class="dash_map_order_num">' + orderNumber + '</span>';
                                    addRows += '</td>';
                                    addRows += '<td>';
                                    addRows += '<span>' + contriesData[index].country + '</span>';
                                    addRows += '</td>';
                                    addRows += '<td>';
                                    addRows += '<div class="visit_map_num">' + contriesData[index].count + '</div>';
                                    addRows += '</td>';
                                    addRows += '</tr>';

                                    tableRows[index].innerHTML = addRows;
                                }(i);
                            }
                        }();
                    }() || !function() {
                        console.log('result error: visit countries');
                    }();
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.onloadend = function() {
                loadSpinner.className = 'data_loading hide_loading';
            };

            xhr.send();
        }

        function setMonthBandwidth() {
            var xhr = new XMLHttpRequest(),
                path = '/dashboard/accumulatedbandwidth/' + domainIndex,
                renderWrap = document.getElementById('bandwidthGraphWrap'),
                loadSpinner = renderWrap.getElementsByClassName('data_loading')[0];

            xhr.open('GET', path);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                loadSpinner.className = 'data_loading';
            };

            xhr.onload = function() {
                var response = this,
                    status = response.status,
                    responseData = JSON.parse(response.responseText);

                status === 200 && !function() {
                    var data = responseData.result_info,
                        result = responseData.result || false;

                    result && !function() {
                        var trafficData = data.traffic_logs;

                        trafficData !== null && !function() {
                            var trafficDataLength = trafficData.length,
                                limit = data.limit,
                                expected = data.expected;

                            // Month 표시
                            var arr_month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                            $("#dash_bandwidth_month").text(c2ms("C_month_" + arr_month[new Date(trafficData[0].date).getMonth()]));

                            var last_date = null;

                            //마지막 날짜 구하기
                            if(typeof trafficData[trafficData.length-1].traffic != 'undefined'){ //현재날짜의 달이 아닌경우
                                last_date = new Date(trafficData[trafficData.length-1].date).getDate();
                            }else{ //현재날짜의 달인경우
                                for(var i=trafficData.length-1; i>=0; i--){
                                    if(typeof trafficData[i].traffic == 'undefined'){
                                        continue;
                                    }else{
                                        last_date = new Date(trafficData[i].date).getDate();
                                        break;
                                    }
                                }
                            }

                            var max_value = 0;
                            for(var i = 0; i < trafficDataLength; i++) {
                                if(i > 0) {
                                    if( trafficData[i].traffic > 0) {
                                        trafficData[i].traffic += trafficData[i-1].traffic;
                                    }
                                }

                                if(trafficData[i].traffic > max_value) max_value = trafficData[i].traffic;
                            }
                            if(max_value == 0) $("#dash_throughput").find(".throughput_nodata").show();

                            $("#dash_bandwidth_graph").dxChart({
                                size:{
                                    height:300
                                },
                                dataSource : trafficData,
                                legend : {
                                    visible:false
                                },
                                animation: {
                                    easing: 'easeOutCubic',
                                    duration: 1000
                                },
                                commonSeriesSettings : {
                                    argumentField: "date",
                                    type: "area"
                                },
                                series : [{
                                    valueField: "traffic",
                                    name: c2ms('accumulated_bandwidth'),
                                    hoverMode: 'none',
                                    color: '#3498DB',
                                    opacity: 0.5,
                                    label: {
                                        horizontalOffset: -10,
                                        verticalOffset: 0,
                                        visible: true,
                                        backgroundColor: 'transparent',
                                        alignment: 'left',
                                        customizeText: function(e){
                                            if(e.argument.getDate() ===  new Date().getDate()) {
                                                return '<span style="color: #3498DB">' + e.seriesName + '</span>';
                                            }
                                        }
                                    }
                                },{
                                    name: c2ms('expected_bandwidth'),
                                    type: "spline",
                                    dashStyle: "longDash",
                                    valueField: "expected",
                                    color:'#7F7F7F',
                                    opacity:0.9,
                                    hoverMode: 'none',
                                    border: false,
                                    label: {
                                        horizontalOffset: 20,
                                        verticalOffset: 0,
                                        visible: true,
                                        backgroundColor: 'transparent',
                                        alignment: 'right',
                                        customizeText: function(e){
                                            if(e.value == 0) {return;}
                                            else if ((e.argument).getDate() == new Date(trafficData[trafficData.length-1].date).getDate()){
                                                return '<span style="color: #7F7F7F">' + e.seriesName + '</span>';
                                            }
                                        }
                                    }
                                }],
                                argumentAxis:{
                                    argumentType: 'datetime',
                                    maxValueMargin: 0.05,
                                    label : { format: 'MM/dd' }
                                },
                                valueAxis: {
                                    label: {
                                        customizeText: function(){
                                            var value = +this.valueText;

                                            return value > 1048576 && (function(){
                                                return (value / 1073741824).toFixed(2) + 'GB';
                                            }()) || value > 1024 && (function(){
                                                return (value / 1048576).toFixed(2) + 'KB';
                                            }()) || value < 1024 && (function(){
                                                return (value / 1024).toFixed(2) + 'B';
                                            }());
                                        }
                                    },
                                    constantLines : [(function(){
                                        return limit > 0 && {
                                            label : {
                                                horizontalAlignment: 'left',
                                                font: {
                                                    color: '#EA5E5F',
                                                    weight: 700
                                                },
                                                text : "Limit traffic"
                                            },
                                            width : 3,
                                            value : limit,
                                            color : '#EA5E5F',
                                            weight: 700,
                                            dashStyle : 'dash'
                                        };
                                    }())],
                                    max : expected,
                                },
                                onDrawn: function() {
                                    $(".bandwidth_nodata").hide();
                                },
                            });
                        }();
                    }() || !function() {
                        console.log('result error: accumulated bandwidth');
                    }();
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.onloadend = function() {
                loadSpinner.className = 'data_loading hide_loading';
            };

            xhr.send();
        }

        function setThroughputLogs(date) {
            var xhr = new XMLHttpRequest(),
                path = '/dashboard/throughputlogs/' + domainIndex + date,
                renderWrap = document.getElementById('throughputGraphWrap'),
                loadSpinner = renderWrap.getElementsByClassName('data_loading')[0];

            xhr.open('GET', path);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                loadSpinner.className = 'data_loading';
            };

            xhr.onload = function() {
                var response = this,
                    status = response.status,
                    responseData = JSON.parse(response.responseText);

                status === 200 && !function() {
                    var data = responseData.result_info,
                        result = responseData.result || false;

                    result && !function() {
                        var throughputData = data.maximum_bps_logs;

                        throughputData !== null && !function() {
                            var throughputDataLength = throughputData.length,
                                limit = data.limit;

                            var max_value = 0;
                            for(var i = 0; i < throughputDataLength; i++) {
                                throughputData[i].maximum_bps = throughputData[i].maximum_bps;
                                if(throughputData[i].maximum_bps > max_value) max_value = throughputData[i].maximum_bps;
                            }
                            if(max_value == 0) $("#dash_throughput").find(".throughput_nodata").show();

                            $("#dash_throughput_graph").dxChart({
                                size:{ height:300 },
                                dataSource: throughputData,
                                commonSeriesSettings: {
                                    argumentField: "date",
                                    type: "spline"
                                },
                                argumentAxis: {
                                    label: {
                                        customizeText: function () {
                                            var date = this.value.substring(5,10);
                                            return date.replace(/-/g,'/');
                                        }
                                    }
                                },
                                series: [{
                                    hoverMode: "none",
                                    valueField: "maximum_bps",
                                    name: "Traffic",
                                    width: 2,
                                    color: "#3498DB",
                                }],
                                legend: { visible:false },
                                tooltip: {
                                    enabled: true
                                },
                                valueAxis: {
                                    label: {
                                        customizeText: function(){
                                            var value = +this.valueText;

                                            return value > 1000000 && (function(){
                                                return (value / 1000000).toFixed(2) + 'Mb/s';
                                            }()) || value > 1000 && (function(){
                                                return (value / 1000) + 'Kb/s';
                                            }()) || value < 1000 && (function(){
                                                return value.toFixed(2);
                                            }());
                                        },
                                        precision: 1
                                    },
                                    constantLines : [(function(){
                                        return limit > 0 && {
                                            label : {
                                                horizontalAlignment: 'left',
                                                font: {
                                                    color: '#EA5E5F',
                                                    weight: 700
                                                },
                                                text : "Limit traffic"
                                            },
                                            width : 3,
                                            value : limit,
                                            color : '#EA5E5F',
                                            weight: 700,
                                            dashStyle : 'dash'
                                        };
                                    }())],
                                    max : max_value
                                },
                                onDrawn: function() {
                                    $(".throughput_nodata").hide();
                                },
                            }).dxChart("instance");
                        }();
                    }() || !function() {
                        console.log('result error: throughput logs');
                    }();
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.onloadend = function() {
                loadSpinner.className = 'data_loading hide_loading';
            };

            xhr.send();
        }

        function setTrafficLogs(date) {
            var xhr = new XMLHttpRequest(),
                path = '/dashboard/trafficlogs/' + domainIndex + date,
                renderWrap = document.getElementById('trafficGraphWrap'),
                loadSpinner = renderWrap.getElementsByClassName('data_loading')[0];

            xhr.open('GET', path);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                loadSpinner.className = 'data_loading';
            };

            xhr.onload = function() {
                var response = this,
                    status = response.status,
                    responseData = JSON.parse(response.responseText);

                status === 200 && !function() {
                    var data = responseData.result_info,
                        result = responseData.result || false;

                    result && !function() {
                        var trafficData = data.traffic_logs;

                        trafficData !== null && !function() {
                            var trafficDataLength = trafficData.length;

                            var max_value = 0;
                            for(var i = 0; i < trafficDataLength; i++) {
                                trafficData[i].traffic = trafficData[i].traffic / 1048576;
                                if(trafficData[i].traffic > max_value) max_value = trafficData[i].traffic / 1048576;
                            }
                            if(max_value == 0) $("#dash_traffic_day").find(".traffic_day_nodata").show();

                            $("#dash_traffic_day_graph").dxChart({
                                size:{ height:300 },
                                dataSource: trafficData,
                                commonSeriesSettings: {
                                    argumentField: "date",
                                    type: "bar"
                                },
                                argumentAxis: {
                                    label: {
                                        customizeText: function () {
                                            var date = this.value.substring(5,10);
                                            return date.replace(/-/g,'/');
                                        }
                                    }
                                },
                                series: [{
                                    hoverMode: "none",
                                    valueField: "traffic",
                                    name: "Traffic",
                                    width: 2,
                                    color: "#3498DB",
                                }],
                                legend: { visible:false },
                                tooltip: {
                                    enabled: true,
                                    customizeTooltip: function (arg) {
                                        var traffic = +arg.valueText;

                                        return {
                                            text: (function(){
                                                if(traffic < 1024) {
                                                    return traffic.toFixed(2) + "MB";
                                                } else {
                                                    return ((traffic / 1024).toFixed(2)) + "GB";
                                                }
                                            }())
                                        };
                                    }
                                },
                                valueAxis: {
                                    label: {
                                        customizeText: function(){
                                            if((this.valueText) < 1024){ return (this.valueText) + "MB"; }
                                            else{ return ((this.valueText / 1024).toFixed(2)) + "GB"; }
                                        },
                                        precision: 1
                                    },
                                },
                                onDrawn: function() {
                                    $(".traffic_day_nodata").hide();
                                },
                            }).dxChart("instance");
                        }();
                    }() || !function() {
                        console.log('result error: traffic by day');
                    }();
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.onloadend = function() {
                loadSpinner.className = 'data_loading hide_loading';
            };

            xhr.send();
        }

        return {
            init: initialize,
            load: loadData
        };
    }());

    var dashboardActions = (function() {
        var toggleButtons = document.getElementsByClassName('attacks_toggle'),
            datePickerButton = $(document.getElementById('daterangepicker'));

        function initialize() {
            initDatePicker();
            toggleAttackMap();
            initTooltips();
            attackIpToggle();
        }

        function initDatePicker() {
            setDatePicker(datePickerButton);

            datePickerButton.on('apply.daterangepicker', function() {
                changeDashDate();
            });
        }

        function changeDashDate() {
            var data = datePickerButton.data('daterangepicker'),
                from = data.startDate.format('YYYYMMDD'),
                to = data.endDate.format('YYYYMMDD');

            dashboardLoad.load(from, to);
        }

        function toggleAttackMap() {
            for(var i = 0; i < 2; i++) {
                toggleButtons[i].addEventListener('click', function() {
                    var button = this,
                        status = button.getAttribute('data-status'),
                        targetTable = button.getAttribute('data-target-table'),
                        targetMap = button.getAttribute('data-target-map'),
                        tables = document.getElementById(targetTable),
                        maps = document.getElementById(targetMap);

                    status === 'hidden' && (function() {
                        toggleEvent();

                        button.className = 'attacks_toggle active';
                        button.setAttribute('data-status', 'active');

                        tables.style.display = 'block';
                        maps.style.display = 'block';
                    }());
                });
            }
        }

        function toggleEvent() {
            for(var i = 0; i < 2; i++) {
                var button = toggleButtons[i],
                    targetTable = button.getAttribute('data-target-table'),
                    targetMap = button.getAttribute('data-target-map'),
                    tables = document.getElementById(targetTable),
                    maps = document.getElementById(targetMap);

                button.className = 'attacks_toggle';
                button.setAttribute('data-status', 'hidden');

                tables.style.display = 'none';
                maps.style.display = 'none';
            }
        }

        function initTooltips() {
            makeTooltip($("#dash_attack_map_title"), "right", "hide", c2ms("T_attackMap"));
            makeTooltip($("#dash_visit_map_title"), "right", "hide", c2ms("T_visitMap"));
        }

        function attackIpToggle() {
            var table = document.getElementById('dash_attack_ip_table');

            table.addEventListener('mouseover', function(e){
                var target = e.target,
                    nodeName = target.nodeName.toLowerCase();

                (nodeName === 'button' && target.className.indexOf('attack_toggle_ip') > -1) && !function(){
                    var status = target.getAttribute('data-status'),
                        prevClassName = target.className,
                        buttonPrevText = target.innerText;

                    target.setAttribute('data-active', 'on');
                    target.className = prevClassName.replace(/btn-gray/g, 'btn-red');
                    target.innerText = status === 'blocked' && 'Unblock' || 'Block';

                    target.onmouseleave === null && (function(){
                        target.addEventListener('mouseleave', function(){
                            this.setAttribute('data-active', '');
                            this.className = prevClassName;
                            this.innerText = buttonPrevText;
                            this.blur();
                        });

                        target.onmouseleave = function(){};
                    }());
                }();
            });

            table.addEventListener('click', function(e){
                var target = e.target,
                    nodeName = target.nodeName.toLowerCase();

                (nodeName === 'button' && target.className.indexOf('attack_toggle_ip') > -1) && !function(){
                    var parent = target.parentElement,
                        optionWrap = parent.getElementsByClassName('option_wrap'),
                        isButtonWrap = optionWrap.length;

                    target.style.display = 'none';

                    isButtonWrap < 1 && !function(){
                        var parent = target.parentElement,
                            wrap = document.createElement('div'),
                            button = document.createElement('button'),
                            noButton = button.cloneNode(),
                            yesButton = button.cloneNode();

                        wrap.className = 'option_wrap';

                        noButton.className = 'btn btn-xs btn-linered btn-no';
                        noButton.innerText = 'NO';

                        yesButton.className = 'btn btn-xs btn-red btn-yes';
                        yesButton.innerText = 'YES';

                        wrap.appendChild(noButton);
                        wrap.appendChild(yesButton);
                        parent.appendChild(wrap);
                    }() || !function(){
                        optionWrap[0].style.display = 'block';
                    }();
                }();

                (nodeName === 'button' && target.className.indexOf('btn-no') > -1) && !function(){
                    var wrap = target.parentElement,
                        ipButton = wrap.previousElementSibling;

                    wrap.style.display = 'none';
                    ipButton.style.display = 'block';
                }();

                (nodeName === 'button' && target.className.indexOf('btn-yes') > -1) && (function(){
                    var wrap = target.parentElement,
                        optionWrap = wrap.parentElement,
                        button = optionWrap.getElementsByClassName('attack_toggle_ip')[0];

                    toggleRequest(button, wrap);
                }());
            });
        }

        function toggleRequest(el, buttonWrap) {
            var xhr = new XMLHttpRequest(),
                domainIndex = sitePath.getPathIndex(3),
                dataStatus = el.getAttribute('data-status'),
                toggleStatus = dataStatus === '' && 'blocked' || '',
                value = el.getAttribute('data-value'),
                method = dataStatus === 'blocked' && 'DELETE' || 'POST',
                restURL = '/settings/updateipcontrol/',
                sendObject = {type: 'black', ip: [value]},
                loadSpinner = document.getElementById('loading'),
                responseResult;

            xhr.open(method, restURL + domainIndex);
            xhr.setRequestHeader("Content-type", "application/json");

            xhr.onloadstart = function() {
                loadSpinner.className = 'content_loading';
            }

            xhr.onload = function() {
                var status = this.status,
                    response = JSON.parse(this.responseText);

                return status === 200 && !function() {
                    responseResult = response.result;
                    console.log( 'ok' );
                }() || !function() {
                    console.log('connected error');
                }();
            };

            xhr.onloadend = function(){
                loadSpinner.className = 'content_loading hide_loading';

                responseResult && !function(){
                    el.style.display = 'block';
                    buttonWrap.style.display = 'none';

                    $('[data-value="' + value + '"]').attr('data-status', toggleStatus);
                }();
            };

            xhr.send(JSON.stringify(sendObject));
        }

        return {
            init: initialize
        };
    }());

    !function(){
        window.addEventListener('load', function(){
            dashboardLoad.init();
        });

        dashboardActions.init();
    }();
}());