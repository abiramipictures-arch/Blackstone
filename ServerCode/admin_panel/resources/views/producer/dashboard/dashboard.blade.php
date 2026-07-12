@extends('producer.layout.page-app')
@section('page_title', __('label.dashboard'))
@section('tab_title', __('label.dashboard'))

@section('content')
    @include('producer.layout.sidebar')

    <div class="right-content">
        @include('producer.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.dashboard')}}</h1>

            <!-- Stat Cards Row 1 — Content -->
            <div class="row">
                <div class="col-xl-4 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-video"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($VideoCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.movies')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-tv"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($TVShowCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.tv_show')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 col-12">
                    <div class="db-stat-card">
                        <div class="db-stat-icon"><i class="fa-solid fa-clapperboard"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($ShortsCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.shorts')}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stat Cards Row 2 — Earnings -->
            <div class="row">
                <div class="col-xl-4 col-sm-6 col-12">
                    <div class="db-stat-card db-stat-earn">
                        <div class="db-stat-icon"><i class="fa-solid fa-money-bill-1-wave"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($CurrentMounthRentCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.monthly_rent_earnings')}} ({{Currency_Code()}})</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 col-12">
                    <div class="db-stat-card db-stat-earn">
                        <div class="db-stat-icon"><i class="fa-solid fa-money-bill-wave"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($RentTransactionCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.rent_earnings')}} ({{Currency_Code()}})</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-sm-6 col-12">
                    <div class="db-stat-card db-stat-earn">
                        <div class="db-stat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
                        <div class="db-stat-info">
                            <p class="db-stat-value">{{ No_Format($PendingWithdrawalCount ?? 0) }}</p>
                            <p class="db-stat-label">{{__('label.pending_withdrawals')}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rent Earning Chart -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="db-chart-card">
                        <div class="db-chart-header">
                            <h2 class="db-chart-title">
                                <i class="fa-solid fa-chart-column"></i>
                                {{__('label.rent_earnings')}}
                            </h2>
                            <a href="{{ route('producer.rent-transaction.index') }}" class="db-chart-link">{{__('label.view_all')}}</a>
                        </div>
                        <div class="db-filter-btns">
                            <button id="year" class="db-filter-btn active">{{__('label.this_year')}}</button>
                            <button id="month" class="db-filter-btn">{{__('label.this_month')}}</button>
                        </div>
                        <div id="Rent_Earning_Chart"></div>
                    </div>
                </div>
            </div>

            <!-- Most View Video & TVShow -->
            <div class="row">
                <div class="col-12">
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
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Chart -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        let rentEarningYear = JSON.parse(`<?php echo $rent_earning_year ?>`);
        let rentEarningMonth = JSON.parse(`<?php echo $rent_earning_month ?>`);
        let month = [
            '{{__("label.jan")}}', '{{__("label.feb")}}', '{{__("label.mar")}}', '{{__("label.apr")}}',
            '{{__("label.may")}}', '{{__("label.jun")}}', '{{__("label.jul")}}', '{{__("label.aug")}}',
            '{{__("label.sep")}}', '{{__("label.oct")}}', '{{__("label.nov")}}', '{{__("label.dec")}}'
        ];
        let chartOptions = {
            chart: {
                type: 'line',
                height: 400,
                toolbar: { show: false },
                zoom: { enabled: false },
                selection: { enabled: false }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            markers: {
                size: 5,
                colors: ['#BAFA34'],
                strokeColors: '#fff',
                strokeWidth: 2
            },
            colors: ['#BAFA34'],
            grid: { borderColor: '#9a9a9a', strokeDashArray: 4 },
            tooltip: { theme: 'dark', style: { fontSize: '14px' } },
            series: [],
            xaxis: {
                categories: [],
                labels: { style: { fontSize: '14px', fontWeight: 'bold', colors: '#FFFFFF' } }
            },
            yaxis: {
                labels: { style: { fontSize: '14px', fontWeight: 'bold', colors: '#FFFFFF' } }
            },
            legend: {
                position: 'bottom',
                fontSize: '16px',
                fontWeight: 'bold',
                labels: { colors: '#FFFFFF', useSeriesColors: false }
            }
        };

        let chart = new ApexCharts(document.querySelector("#Rent_Earning_Chart"), chartOptions);
        chart.render();

        function loadChartData(type) {
            if (type === 'year') {
                chart.updateOptions({
                    series: [{ name: "{{ __('label.earnings') }}", data: rentEarningYear.sum }],
                    xaxis: { categories: month }
                });
            } else {
                let daysInMonth = rentEarningMonth.sum.length;
                chart.updateOptions({
                    series: [{ name: "{{ __('label.earnings') }}", data: rentEarningMonth.sum }],
                    xaxis: { categories: Array.from({ length: daysInMonth }, (_, i) => (i + 1).toString()) }
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
    </script>
@endsection