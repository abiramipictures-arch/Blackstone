@extends('admin.layout.page-app')
@section('page_title', __('label.transactions'))
@section('tab_title', __('label.transactions'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.transactions')}}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.transactions')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.transaction.create') }}" class="btn btn-default-white mw-120">{{__('label.add_transaction')}}</a>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row">
                <div class="col-sm-6 col-lg-4 mb-3">
                    <div class="card-earning stat-card stat-card-primary">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $today_sum['total'] ?? 0 }}</p>
                                <p class="earning-title mb-0">{{__('label.total_earning_today')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-calendar-day fa-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 mb-3">
                    <div class="card-earning stat-card stat-card-approve">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $month_sum['total'] ?? 0 }}</p>
                                <p class="earning-title mb-0">{{__('label.total_earning_current_month')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-calendar-week fa-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 mb-3">
                    <div class="card-earning stat-card stat-card-pending">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $year_sum['total'] ?? 0 }}</p>
                                <p class="earning-title mb-0">{{__('label.total_earning_current_year')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-calendar fa-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-receipt mr-2"></i>{{__('label.transactions')}}</div>
                <div class="card-body">
                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search">
                        </div>
                        <div class="sorting w-50">
                            <select class="form-control" id="input_package">
                                <option value="0">{{__('label.all_package')}}</option>
                                @foreach($package as $pkg)
                                    <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sorting w-50">
                            <select class="form-control" id="input_type">
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
                                    <th>{{__('label.package')}}</th>
                                    <th>{{__('label.price')}}</th>
                                    <th>{{__('label.transaction_id')}}</th>
                                    <th>{{__('label.date')}}</th>
                                    <th>{{__('label.expiry')}}</th>
                                    <th>{{__('label.status')}}</th>
                                    <th>{{__('label.action')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="11" class="text-center"></td>
                                </tr>
                            </tfoot>
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
            return user.charAt(0) + '******@' + domain;
        }

        $(document).ready(function() {
            table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.transaction.index') }}",
                    data: function(d) {
                        d.input_type    = $('#input_type').val();
                        d.input_package = $('#input_package').val();
                        d.input_search  = $('#input_search').val();
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
                    { data: 'package.name',                 name: 'package.name',               orderable: false, searchable: false },
                    { data: 'price',                        name: 'price',                      orderable: false, searchable: false,
                      render: function(data) { return data ? "{{ Currency_Code() }}" + ' ' + data : '-'; } },
                    { data: 'transaction_id',               name: 'transaction_id',             orderable: false, searchable: false },
                    { data: 'date',                         name: 'date',                       orderable: false, searchable: false },
                    { data: 'expiry_status',                name: 'expiry_status',              orderable: false, searchable: false },
                    { data: 'payment_transaction_status',   name: 'payment_transaction_status', orderable: false, searchable: false },
                    { data: 'action',                       name: 'action',                     orderable: false, searchable: false },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api    = this.api();
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    var total = api.column(5).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                    $(api.column(1).footer()).html("{{__('label.total_amount')}} {{Currency_Code()}} " + total);
                },
            });

            $('#input_type, #input_package').change(function() { table.draw(); });
            $('#input_search').keyup(function() { table.draw(); });
        });
    </script>
@endsection
