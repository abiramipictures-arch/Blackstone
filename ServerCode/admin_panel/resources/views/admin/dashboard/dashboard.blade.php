@extends('admin.layout.page-app')
@section('page_title', __('label.dashboard'))
@section('tab_title', __('label.dashboard'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.dashboard')}}</h1>

            <!-- Stat Cards Row 1 -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-users"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($UserCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.users')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-video"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($VideoCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.movies')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-tv"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($TVShowCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.tv_show')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-clapperboard"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($ShortsCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.shorts')}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Cards Row 2 — Platform -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-film"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($ChannelCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.channel')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-user-tie"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($CastCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.cast')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-user-shield"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($ProducerCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.producer')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-crown"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($ActiveSubscriptions ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.active_subscriptions')}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Cards Row 3 — Finance -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-box-archive"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($PackageCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.package')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-right-left"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($TotalWithdrawalCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.total_withdrawal')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($PendingWithdrawals ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.pending_withdrawals')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card db-stat-earn">
                        <div class="db-stat-icon"><i class="fa-solid fa-calendar-day"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($TodayRevenue ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.today_revenue')}} ({{Currency_Code()}})</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Cards Row 4 — Earnings -->
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card db-stat-earn">
                        <div class="db-stat-icon"><i class="fa-solid fa-money-bill-1"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($CurrentMounthCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.monthly_package_earnings')}} ({{Currency_Code()}})</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card db-stat-earn">
                        <div class="db-stat-icon"><i class="fa-solid fa-money-bill"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($TransactionCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.package_earnings')}} ({{Currency_Code()}})</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card db-stat-earn">
                        <div class="db-stat-icon"><i class="fa-solid fa-money-bill-1-wave"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($CurrentMounthRentCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.monthly_rent_earnings')}} ({{Currency_Code()}})</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12">
                    <div class="db-stat-card db-stat-earn">
                        <div class="db-stat-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($RentTransactionCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.rent_earnings')}} ({{Currency_Code()}})</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Join User Statistics & Rent Earning -->
            <div class="row mb-4">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <div class="db-chart-card">
                        <div class="db-chart-header">
                            <h2 class="db-chart-title">
                                <i class="fa-solid fa-chart-column"></i>
                                {{__('label.join_users_statistice_current_year')}}
                            </h2>
                            <a href="{{ route('admin.user.index') }}" class="db-chart-link">{{__('label.view_all')}}</a>
                        </div>
                        <div class="db-filter-btns">
                            <button id="year" class="db-filter-btn active">{{__('label.this_year')}}</button>
                            <button id="month" class="db-filter-btn">{{__('label.this_month')}}</button>
                        </div>
                        <div id="User_Chart"></div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="db-chart-card">
                        <div class="db-chart-header">
                            <h2 class="db-chart-title">
                                <i class="fa-solid fa-chart-pie"></i>
                                {{__('label.rent_earning_current_year')}}
                            </h2>
                            <a href="{{ route('admin.rent-transaction.index') }}" class="db-chart-link">{{__('label.view_all')}}</a>
                        </div>
                        <div id="Rent_Earning"></div>
                    </div>
                </div>
            </div>

            <!-- Plan Earning Statistics & Best Category -->
            <div class="row mb-4">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <div class="db-chart-card">
                        <div class="db-chart-header">
                            <h2 class="db-chart-title">
                                <i class="fa-solid fa-chart-column"></i>
                                {{__('label.plan_earning_statistice_current_year')}}
                            </h2>
                            <a href="{{ route('admin.transaction.index') }}" class="db-chart-link">{{__('label.view_all')}}</a>
                        </div>
                        <div id="PackageChart"></div>
                    </div>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="db-chart-card">
                        <div class="db-chart-header">
                            <h2 class="db-chart-title">
                                <i class="fa-solid fa-chart-pie"></i>
                                {{__('label.most_used_categorise')}}
                            </h2>
                            <a href="{{ route('admin.category.index') }}" class="db-chart-link">{{__('label.view_all')}}</a>
                        </div>
                        <div id="Category_Chart"></div>
                    </div>
                </div>
            </div>

            <!-- Most Viewed Content & Best Channel -->
            <div class="row mb-4">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <div class="db-chart-card">
                        <div class="db-chart-header">
                            <h2 class="db-chart-title">
                                <i class="fa-solid fa-chart-bar"></i>
                                {{__('label.most_view_movies_tvshow')}}
                            </h2>
                        </div>

                        <ul class="nav db-tabs" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link db-tab-link active" id="pills-video-view-tab" data-toggle="pill" href="#pills-video-view" role="tab" aria-controls="pills-video-view" aria-selected="true">{{__('label.movies')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link db-tab-link" id="pills-tvshow-view-tab" data-toggle="pill" href="#pills-tvshow-view" role="tab" aria-controls="pills-tvshow-view" aria-selected="false">{{__('label.tv_show')}}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link db-tab-link" id="pills-shorts-view-tab" data-toggle="pill" href="#pills-shorts-view" role="tab" aria-controls="pills-shorts-view" aria-selected="false">{{__('label.shorts')}}</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-video-view" role="tabpanel" aria-labelledby="pills-video-view-tab">
                                <div class="db-content-list">
                                    @for ($i = 0; $i < count($top_video_view); $i++)
                                        <div class="db-content-item">
                                            <span class="db-content-rank">{{ $i + 1 }}</span>
                                            <img src="{{ $top_video_view[$i]['thumbnail'] }}" class="db-content-thumb" alt="">
                                            <div class="db-content-meta">
                                                <div class="db-content-title" title="{{ $top_video_view[$i]['name'] }}">{{ String_Cut($top_video_view[$i]['name'], 45) }}</div>
                                                <div class="db-content-type">{{ $top_video_view[$i]['type']['name'] ?? '-' }}</div>
                                            </div>
                                            <div class="db-content-views">
                                                <i class="fa-solid fa-eye"></i>
                                                <span class="counting" data-count="{{ No_Format($top_video_view[$i]['total_view'] ?? 0) }}">{{ No_Format($top_video_view[$i]['total_view'] ?? 0) }}</span>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-tvshow-view" role="tabpanel" aria-labelledby="pills-tvshow-view-tab">
                                <div class="db-content-list">
                                    @for ($i = 0; $i < count($top_tvshow_view); $i++)
                                        <div class="db-content-item">
                                            <span class="db-content-rank">{{ $i + 1 }}</span>
                                            <img src="{{ $top_tvshow_view[$i]['thumbnail'] }}" class="db-content-thumb" alt="">
                                            <div class="db-content-meta">
                                                <div class="db-content-title" title="{{ $top_tvshow_view[$i]['name'] }}">{{ String_Cut($top_tvshow_view[$i]['name'], 45) }}</div>
                                                <div class="db-content-type">{{ $top_tvshow_view[$i]['type']['name'] ?? '-' }}</div>
                                            </div>
                                            <div class="db-content-views">
                                                <i class="fa-solid fa-eye"></i>
                                                <span class="counting" data-count="{{ No_Format($top_tvshow_view[$i]['total_view'] ?? 0) }}">{{ No_Format($top_tvshow_view[$i]['total_view'] ?? 0) }}</span>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-shorts-view" role="tabpanel" aria-labelledby="pills-shorts-view-tab">
                                <div class="db-content-list">
                                    @for ($i = 0; $i < count($top_shorts_view); $i++)
                                        <div class="db-content-item">
                                            <span class="db-content-rank">{{ $i + 1 }}</span>
                                            <img src="{{ $top_shorts_view[$i]['thumbnail'] }}" class="db-content-thumb" alt="">
                                            <div class="db-content-meta">
                                                <div class="db-content-title" title="{{ $top_shorts_view[$i]['name'] }}">{{ String_Cut($top_shorts_view[$i]['name'], 45) }}</div>
                                                <div class="db-content-type">{{ $top_shorts_view[$i]['type']['name'] ?? '-' }}</div>
                                            </div>
                                            <div class="db-content-views">
                                                <i class="fa-solid fa-eye"></i>
                                                <span class="counting" data-count="{{ No_Format($top_shorts_view[$i]['total_view'] ?? 0) }}">{{ No_Format($top_shorts_view[$i]['total_view'] ?? 0) }}</span>
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4">
                    <div class="db-chart-card">
                        <div class="db-chart-header">
                            <h2 class="db-chart-title">
                                <i class="fa-solid fa-table-cells-large"></i>
                                {{__('label.best_channel')}}
                            </h2>
                            <a href="{{ route('admin.channel.index') }}" class="db-chart-link">{{__('label.view_all')}}</a>
                        </div>
                        <div class="row pr-2">
                            @for ($i = 0; $i < count($best_channel); $i++)
                                @if($i > 0 && (($i % 4) == 1 || ($i % 4) == 2))
                                    <div class="col-5 mb-2 pr-1 pl-1">
                                        <div class="db-channel-item">
                                            <img src="{{ $best_channel[$i]['portrait_img'] }}" class="db-channel-img" alt="{{ $best_channel[$i]['name'] }}">
                                            <div class="db-channel-name">{{ $best_channel[$i]['name'] }}</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-7 mb-2 pr-1 pl-1">
                                        <div class="db-channel-item">
                                            <img src="{{ $best_channel[$i]['portrait_img'] }}" class="db-channel-img" alt="{{ $best_channel[$i]['name'] }}">
                                            <div class="db-channel-name">{{ $best_channel[$i]['name'] }}</div>
                                        </div>
                                    </div>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>

        // User Chart
        let userYear = JSON.parse(`<?php echo $user_year ?>`);
        let userMonth = JSON.parse(`<?php echo $user_month ?>`);
        let month = [
            '{{__("label.jan")}}', '{{__("label.feb")}}', '{{__("label.mar")}}', '{{__("label.apr")}}',
            '{{__("label.may")}}', '{{__("label.jun")}}', '{{__("label.jul")}}', '{{__("label.aug")}}',
            '{{__("label.sep")}}', '{{__("label.oct")}}', '{{__("label.nov")}}', '{{__("label.dec")}}'
        ] ;
        let chartOptions = {
            chart: {
                type: 'line',
                height: 400,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                selection: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            markers: {
                size: 5,
                colors: ['#BAFA34'],
                strokeColors: '#fff',
                strokeWidth: 2
            },
            colors: ['#BAFA34'],
            grid: {
                borderColor: '#9a9a9a',
                strokeDashArray: 4
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '14px'
                }
            },
            series: [],
            xaxis: {
                categories: [],
                labels: {
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        colors: '#FFFFFF'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        colors: '#FFFFFF'
                    }
                }
            },
            legend: {
                position: 'bottom',
                fontSize: '16px',
                fontWeight: 'bold',
                labels: {
                    colors: '#FFFFFF',
                    useSeriesColors: false
                }
            }
        };

        let chart = new ApexCharts(document.querySelector("#User_Chart"), chartOptions);
        chart.render();

        function loadChartData(type) {
            if (type === 'year') {
                chart.updateOptions({
                    series: [{
                        name: "{{ __('label.users') }}",
                        data: userYear.sum
                    }],
                    xaxis: {
                        categories: month
                    }
                });
            } else {
                let daysInMonth = userMonth.sum.length;
                chart.updateOptions({
                    series: [{
                        name: "{{ __('label.users') }}",
                        data: userMonth.sum
                    }],
                    xaxis: {
                        categories: Array.from({ length: daysInMonth }, (_, i) => (i + 1).toString())
                    }
                });
            }
        }

        loadChartData('year');

        document.getElementById('year').addEventListener('click', function () {
            document.querySelectorAll('.db-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            loadChartData('year');
        });
        document.getElementById('month').addEventListener('click', function () {
            document.querySelectorAll('.db-filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            loadChartData('month');
        });

        // Rent Earning Statistics
        var rent_cData = JSON.parse(`<?php echo $rent_earning; ?>`);
        var rentOptions = {
            chart: {
                type: 'pie',
                height: 400
            },
            series: rent_cData['sum'],
            labels: month,
            colors: [
                '#FF6384', '#4BC0C0', '#FFCD56', '#B04645',
                '#35B03B', '#36A2EB', '#E007F0', '#9966FF',
                '#FF9F40', '#E04714', '#A19135', '#E876D3'
            ],
            legend: {
                position: 'bottom',
                fontSize: '14px',
                labels: {
                    colors: '#fff'
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return val;
                    }
                }
            }
        };
        var rentChart = new ApexCharts(document.querySelector("#Rent_Earning"), rentOptions);
        rentChart.render();


        // Package Earning Chart
        var package_cData = JSON.parse(`<?php echo $package_data; ?>`);
        let seriesData = [];
        for (let i = 0; i < package_cData.label.length; i++) {
            seriesData.push({
                name: package_cData.label[i],
                data: package_cData.sum[i]
            });
        }
        var packageOptions = {
            chart: {
                type: 'bar',
                stacked: true,
                height: 430,
                toolbar: { show: false }
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '14px'
                }
            },
            series: seriesData,
            xaxis: {
                categories: month,
                labels: {
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        colors: '#FFFFFF'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold',
                        colors: '#FFFFFF'
                    }
                }
            },
            legend: {
                position: 'bottom',
                labels: {
                    colors: '#fff'
                }
            },
            plotOptions: {
                bar: { horizontal: false }
            }
        };
        var packageChart = new ApexCharts(document.querySelector("#PackageChart"), packageOptions);
        packageChart.render();


        // Most Used Categories
        var category_cData = JSON.parse(`<?php echo $most_used_categorise; ?>`);
        var categoryOptions = {
            chart: {
                type: 'donut',
                height: 400
            },
            series: category_cData.sum,
            labels: category_cData.labels,
            colors: [
                '#8abd1bff',
                '#6ba704ff',
                '#3e6b00ff',
                '#2b5000ff'
            ],
            legend: {
                position: 'bottom',
                fontSize: '14px',
                labels: {
                    colors: '#fff'
                }
            },
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return val;
                    }
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%'
                    }
                }
            }
        };
        var categoryChart = new ApexCharts(document.querySelector("#Category_Chart"), categoryOptions);
        categoryChart.render();
    </script>
@endsection
