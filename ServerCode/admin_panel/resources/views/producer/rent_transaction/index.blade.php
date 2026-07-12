@extends('producer.layout.page-app')
@section('page_title', __('label.rent_transaction'))
@section('tab_title', __('label.rent_transaction'))

@section('content')
    @include('producer.layout.sidebar')

    <div class="right-content">
        @include('producer.layout.header')

        <div class="body-content">
            <h1 class="page-title-sm">{{__('label.rent_transaction')}}</h1>

            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('producer.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.rent_transaction')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Earnings -->
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card-earning">
                        <p class="earning-title">{{__('label.total_earning_today')}}</p>
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $today_sum['total_commission'] ?? 00 }}</p>
                                <p class="earning-title">{{__('label.commission')}}</p>
                            </div>
                            <div class="earning-divider"></div>
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $today_sum['total_producer_earning'] ?? 00 }}</p>
                                <p class="earning-title">{{__('label.earnings')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card-earning">
                        <p class="earning-title">{{__('label.total_earning_current_month')}}</p>
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $month_sum['total_commission'] ?? 00 }}</p>
                                <p class="earning-title">{{__('label.commission')}}</p>
                            </div>
                            <div class="earning-divider"></div>
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $month_sum['total_producer_earning'] ?? 00 }}</p>
                                <p class="earning-title">{{__('label.earnings')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="card-earning">
                        <p class="earning-title">{{__('label.total_earning_current_year')}}</p>
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $year_sum['total_commission'] ?? 00 }}</p>
                                <p class="earning-title">{{__('label.commission')}}</p>
                            </div>
                            <div class="earning-divider"></div>
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $year_sum['total_producer_earning'] ?? 00 }}</p>
                                <p class="earning-title">{{__('label.earnings')}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <div class="card-header"><i class="fa-solid fa-receipt"></i>{{__('label.rent_transaction')}}</div>
                <div class="card-body">
                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search">
                        </div>
                        <div class="sorting w-25">
                            <select class="form-control" name="input_type" id="input_type">
                                <option value="all">{{__('label.all')}}</option>
                                <option value="today">{{__('label.today')}}</option>
                                <option value="month">{{__('label.month')}}</option>
                                <option value="year">{{__('label.year')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.coupon')}}</th>
                                    <th>{{__('label.user')}}</th>
                                    <th>{{__('label.contact')}}</th>
                                    <th>{{__('label.content')}}</th>
                                    <th>{{__('label.earnings')}}</th>
                                    <th>{{__('label.transaction_id')}}</th>
                                    <th>{{__('label.date')}}</th>
                                    <th>{{__('label.expiry')}}</th>
                                    <th>{{__('label.status')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        const demo_mode = '{{ Demo_Mode() }}';
        function maskEmail(email) {
            const [user, domain] = email.split('@');
            const maskedUser = user.charAt(0) + '******';
            return maskedUser + '@' + domain;
        }

        $(document).ready(function() {
            table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('producer.rent-transaction.index') }}",
                    data: function(d) {
                        d.input_type   = $('#input_type').val();
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'coupon_code', name: 'coupon_code', orderable: false, searchable: false,
                        render: function(data) {
                            return `<h6 class='primary-color d-flex align-items-center' style="font-size: 14px; font-weight: 600;">${data || '-'}</h6>`;
                        },
                    },
                    {
                        data: 'user', name: 'user', orderable: false, searchable: false,
                        render: function(data) {
                            return `<div style="text-align: left; font-size: 14px;">${data?.user_name || '-'}<br><span style="font-weight: 600;">${data?.full_name || '-'}</span></div>`;
                        }
                    },
                    {
                        data: 'user', name: 'user', orderable: false, searchable: false,
                        render: function(data) {
                            if (demo_mode == 0) {
                                const mobile = data?.mobile_number ? '******' + data.mobile_number.slice(-4) : '-';
                                const email  = data?.email ? maskEmail(data.email) : '-';
                                return `<div style="text-align: left; font-size: 14px;">${mobile}<br><span style="font-weight: 600;">${email}</span></div>`;
                            }
                            return `<div style="text-align: left; font-size: 14px;">${data?.mobile_number || '-'}<br><span style="font-weight: 600;">${data?.email || '-'}</span></div>`;
                        }
                    },
                    { data: 'video_name', name: 'video_name', orderable: false, searchable: false, render: function(data) {
                        return `<div style="font-size: 14px;">${data || '-'}</div>`;
                    }},
                    { data: 'price_details',                name: 'price_details',              orderable: false, searchable: false },
                    { data: 'transaction_id',               name: 'transaction_id',             orderable: false, searchable: false },
                    { data: 'date',                         name: 'date',                       orderable: false, searchable: false },
                    { data: 'expiry_status',                name: 'expiry_status',              orderable: false, searchable: false },
                    { data: 'payment_transaction_status',   name: 'payment_transaction_status', orderable: false, searchable: false },
                ],
            });

            $('#input_type').change(function() { table.draw(); });
            $('#input_search').keyup(function() { table.draw(); });
        });
    </script>
@endsection
