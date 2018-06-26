<template>
    <div class="page" data-name="dashboard">
        <div class="navbar">
            <div class="navbar-inner sliding">
                <div class="left">
                    <a href="#" class="link back">
                        <i class="icon icon-back"></i>
                        <span class="ios-only">Back</span>
                    </a>
                </div>
                <div class="title">Dashboard</div>
                <div class="right">
                    <a href="#" data-popup=".setting-popup" class="popup-open link">
                        <i class="icon f7-icons">gear</i>
                    </a>
                </div>
            </div>
        </div>

        <div class="popup setting-popup">
            <div class="view">
                <div class="page">
                    <div class="navbar">
                        <div class="navbar-inner">
                            <!-- 팝업 타이틀 가운데 정렬을 위해 안 보이는 아이콘 -->
                            <div class="left">
                                <a class="link popup-close">
                                <i class="icon color-blue material-icons ">close</i>
                                </a>
                            </div>
                            <div class="title">Settings</div>
                            <div class="right">
                                <!-- Link to close popup -->
                                <a class="link popup-close">
                                    <i class="icon material-icons">close</i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="page-content">
                        <div class="block-title">General <div class="float-right">{{domain.name}}</div></div>
                        <div class="list simple-list">
                            <ul>
                                <li>
                                    <span>Bypass Mode</span>
                                    <label class="toggle toggle-init color-red">
                                        <input type="checkbox">
                                        <span class="toggle-icon"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                        <div class="block-title">Redirection Settings</div>
                        <div class="list simple-list">
                            <ul>
                                <li>
                                    <span>Redirect to www</span>
                                    <label class="toggle toggle-init color-blue">
                                        <input type="checkbox" checked="checked">
                                        <span class="toggle-icon"></span>
                                    </label>
                                </li>
                                <li>
                                    <span>Redirect to HTTPS</span>
                                    <label class="toggle toggle-init color-blue">
                                        <input type="checkbox" checked="checked">
                                        <span class="toggle-icon"></span>
                                    </label>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
            ...
        </div>

        <div class="tabs">
            <div id="tab-visits" class="page-content tab tab-active">
                <div class="list">
                    <ul>
                        <li>
                            <div class="item-content">
                                <div class="item-media"><img src="/assets-mobile/img/{{statusImage}}.png" alt="{{statusImage}}"></div>
                                <div class="item-inner">
                                    <div class="item-title domain-status">{{domain.status}}</div>
                                    <div class="item-after">{{domain.name}}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="block">
                    <div class="segmented">
                        <a href="#tab-visits" class="button button-round button-outline button-active tab-link">Visits</a>
                        <a href="#tab-attacks" class="button button-round button-outline color-red tab-link">Attacks</a>
                    </div>
                </div>
                <div class="block block-strong block-total-data">
                    <div class="row">
                        <div class="col total-traffic">
                            <dl class="total-data-list">
                                <dt>Total Traffic</dt>
                                <dd>May</dd>
                            </dl>
                            <div class="total-data-traffic">{{traffic}}GB / {{limitTraffic}}GB</div>
                        </div>
                        <div class="col total-visits">
                            <dl class="total-data-list">
                                <dt>Total Visits</dt>
                                <dd>May</dd>
                            </dl>
                            <div class="total-data-visits">{{visitCount}}</div>
                        </div>
                    </div>
                </div>
                <div class="block-title">Total Visits</div>
                <div class="block block-strong">
                    <div id="chart-visits"></div>
                </div>
                <div class="block-title">Top Countries</div>
                <div class="block block-strong">
                    <div id="chart-countries"></div>
                </div>
                <div class="block-title">Traffic By Day</div>
                <div class="block block-strong">
                    <div id="chart-traffic"></div>
                </div>
            </div>
            <div id="tab-attacks" class="page-content tab">
                <div class="list">
                    <ul>
                        <li>
                            <div class="item-content">
                                <div class="item-media"><img src="/assets-mobile/img/{{statusImage}}.png" alt="{{statusImage}}"></div>
                                <div class="item-inner">
                                    <div class="item-title domain-status">{{domain.status}}</div>
                                    <div class="item-after">{{domain.name}}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="block">
                    <div class="segmented">
                        <a href="#tab-visits" class="button button-round button-outline tab-link">Visits</a>
                        <a href="#tab-attacks" class="button button-round button-outline button-active color-red tab-link">Attacks</a>
                    </div>
                </div>
                <div class="block block-strong block-total-data">
                    <div class="row">
                        <div class="col total-traffic">
                            <dl class="total-data-list">
                                <dt>Total Traffic</dt>
                                <dd>May</dd>
                            </dl>
                            <div class="total-data-traffic">{{traffic}}GB / {{limitTraffic}}GB</div>
                        </div>
                        <div class="col total-visits">
                            <dl class="total-data-list">
                                <dt>Total Attacks</dt>
                                <dd>May</dd>
                            </dl>
                            <div class="total-data-attacks">{{attackCount}}</div>
                        </div>
                    </div>
                </div>
                <div class="block-title">Total Attacks</div>
                <div class="block block-strong">
                    <div id="chart-attacks"></div>
                </div>
                <div class="block-title">Top Ips</div>
                <div class="block block-strong">
                    <div id="chart-ips"></div>
                </div>
                <div class="block-title">Attack Purpose</div>
                <div class="block block-strong">
                    <div id="chart-purpose"></div>
                </div>
                <div class="block-title">Attack URLs</div>
                <div class="block block-strong">
                    <div id="chart-urls"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="view-settings"></div>
</template>

<script>
    var events = {
        data: function() {
            var data = {
                statusImage: function() {
                    var data = this.$route.context.domain,
                        status = data.status,
                        imageCode;

                    switch(status) {
                        case 'Protected':
                            imageCode = 'protected';
                            break;
                        case 'Bypass':
                            imageCode = 'bypass';
                            break;
                        case 'Pending':
                            imageCode = 'pending';
                            break;
                        case 'Deleting':
                            imageCode = 'deleting';
                            break;
                        case 'Disconnected':
                            imageCode = 'disconnected';
                            break;
                    }

                    return imageCode;
                },
                traffic: function() {
                    var data = this.$route.context.domain,
                        traffic = data.traffic.total;

                    return replaceFixed(traffic);
                },
                limitTraffic: function() {
                    var data = this.$route.context.domain,
                        traffic = data.traffic.limit;

                    return replaceFixed(traffic);
                },
                visitCount: function() {
                    var data = this.$route.context.domain,
                        count = data.visits_attacks.visits_count;

                    return count;
                },
                attackCount: function() {
                    var data = this.$route.context.domain,
                        count = data.visits_attacks.attacks_count;

                    return count;
                },
            };

            return data;
        },
        on: {
            pageInit: function() {
                var data = this.$route.context.domain,
                    visitAttackData = data.visits_attacks.data,
                    countryData = data.countries.data,
                    trafficData = data.traffic.data,
                    ipsData = data.ips.data,
                    purposeData = data.purpose.data.map(function(obj){
                        return [obj.purpose, obj.count];
                    }),
                    urlsData = data.urls.data.map(function(obj) {
                        return [obj.uri, obj.count];
                    });

                var chartVisits = c3.generate({
                    bindto: '#chart-visits',
                    data: {
                        json: visitAttackData,
                        keys: {
                            x: 'date',
                            value: ['visits'],
                        },
                        type: 'area-spline'
                    },
                    axis: {
                        x: {
                            type: 'timeseries',
                            tick: {
                                format: '%d'
                            }
                        }
                    },
                    point: {
                        r: 2
                    }
                });

                var chartCountries = c3.generate({
                    bindto: '#chart-countries',
                    data: {
                        json: countryData,
                        keys: {
                            x: 'country',
                            value: ['count']
                        },
                        type: 'bar'
                    },
                    axis: {
                        x: {
                            type: 'category'
                        },
                        rotated: true
                    },
                    bar: {
                        width: 20
                    }
                });

                var chartTraffic = c3.generate({
                    bindto: '#chart-traffic',
                    padding: {
                        left: 60
                    },
                    data: {
                        json: trafficData,
                        keys: {
                            x: 'date',
                            value: ['traffic']
                        },
                        type: 'bar'
                    },
                    axis: {
                        x: {
                            type: 'timeseries',
                            tick: {
                                format: '%m/%d',
                                count: 7
                            }
                        },
                        y: {
                            tick: {
                                format: function(d) {
                                    return (d / 1024 / 1024).toFixed(1) + 'MB';
                                },
                                count: 5
                            }
                        }
                    },
                    bar: {
                        width: {
                            ratio: 0.1
                        }
                    }
                });

                var chartAttacks = c3.generate({
                    bindto: '#chart-attacks',
                    data: {
                        json: visitAttackData,
                        keys: {
                            x: 'date',
                            value: ['attacks'],
                        },
                        type: 'area',
                        colors: {
                            attacks: '#ff7f0e'
                        },
                        empty: {
                            label: {
                                text: "No Data"
                            }
                        }
                    },
                    axis: {
                        x: {
                            type: 'timeseries',
                            tick: {
                                format: '%d'
                            }
                        }
                    },
                    point: {
                        r: 2
                    }
                });

                var chartIps = c3.generate({
                    bindto: '#chart-ips',
                    data: {
                        json: ipsData,
                        keys: {
                            x: 'ip',
                            value: ['count']
                        },
                        type: 'bar',
                        colors: {
                            count: '#d62728'
                        },
                        empty: {
                            label: {
                                text: "No Data"
                            }
                        }
                    },
                    axis: {
                        x: {
                            type: 'category'
                        },
                        y: {
                            tick: {
                                format: function(d) {
                                    return d.toFixed(0);
                                },
                                count: 5
                            }
                        },
                        rotated: true,
                    },
                    bar: {
                        width: 20
                    }
                });

                var chartPurpose = c3.generate({
                    bindto: '#chart-purpose',
                    data: {
                        columns: purposeData,
                        type: 'pie',
                        empty: {
                            label: {
                                text: "No Data"
                            }
                        }
                    },
                    color: {
                        pattern: ['#d62728', '#ff7f0e', '#8c564b', '#ffbb78', '#ff9896', '#7f7f7f', '#c7c7c7']
                    },
                    pie: {
                        label: {
                            format: function (value) {
                                return value;
                            }
                        }
                    }
                });

                var chartUrls = c3.generate({
                    bindto: '#chart-urls',
                    data: {
                        columns: urlsData,
                        type: 'pie',
                        empty: {
                            label: {
                                text: "No Data"
                            }
                        }
                    },
                    color: {
                        pattern: ['#d62728', '#ff7f0e', '#8c564b', '#ffbb78', '#ff9896', '#7f7f7f', '#c7c7c7']
                    },
                    pie: {
                        label: {
                            format: function (value) {
                                return value;
                            }
                        }
                    }
                });

                $$('#tab-visits').on('tab:show', function() {
                    chartVisits.flush();
                    chartCountries.flush();
                    chartTraffic.flush();
                });

                $$('#tab-attacks').on('tab:show', function() {
                    chartAttacks.flush();
                    chartIps.flush();
                    chartPurpose.flush();
                    chartUrls.flush();
                });

            }
        }
    };

    function replaceFixed(value) {
        return (value / 1073741824).toFixed(1).replace(/\.?0+$/, '');
    }

    return events;

</script>