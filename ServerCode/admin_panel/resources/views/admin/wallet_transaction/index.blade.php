@extends('admin.layout.page-app')
@section('page_title', __('label.wallet_transactions'))
@section('tab_title', __('label.wallet_transactions'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.wallet_transactions')}}</h1>

            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.wallet_transactions')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row">
                <div class="col-sm-6 col-lg-4 mb-3">
                    <div class="card-earning stat-card stat-card-primary">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $today_sum['total_amount'] ?? 0 }}</p>
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
                                <p class="earning-amount">{{ Currency_Code() }}{{ $month_sum['total_amount'] ?? 0 }}</p>
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
                                <p class="earning-amount">{{ Currency_Code() }}{{ $year_sum['total_amount'] ?? 0 }}</p>
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
                <div class="card-header"><i class="fa-solid fa-wallet mr-2"></i>{{__('label.wallet_transactions')}}</div>
                <div class="card-body">
                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search">
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
                                    <th>{{__('label.user')}}</th>
                                    <th>{{__('label.email')}}</th>
                                    <th>{{__('label.mobile_number')}}</th>
                                    <th>{{__('label.amount')}}</th>
                                    <th>{{__('label.transaction_id')}}</th>
                                    <th>{{__('label.description')}}</th>
                                    <th>{{__('label.transaction_date')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8" class="text-center"></td>
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
                    url: "{{ route('admin.wallet-transaction.index') }}",
                    data: function(d) {
                        d.input_type   = $('#input_type').val();
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user.full_name', name: 'user.full_name', orderable: false, searchable: false },
                    { data: 'user.email', name: 'user.email', orderable: false, searchable: false,
                        render: function(data) {
                            if (!data) return '-';
                            return demo_mode == 0 ? maskEmail(data) : data;
                        }
                    },
                    { data: 'user.mobile_number', name: 'user.mobile_number', orderable: false, searchable: false,
                        render: function(data) {
                            if (!data) return '-';
                            return demo_mode == 0 ? '******' + data.slice(-4) : data;
                        }
                    },
                    { data: 'amount',           name: 'amount',           orderable: false, searchable: false,
                      render: function(data) { return data ? "{{ Currency_Code() }}" + data : 0; } },
                    { data: 'transaction_id',   name: 'transaction_id',   orderable: false, searchable: false },
                    { data: 'description',      name: 'description',      orderable: false, searchable: false },
                    { data: 'date',             name: 'date',             orderable: false, searchable: false },
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api    = this.api();
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    var total = api.column(4).data().reduce(function(a, b) { return intVal(a) + intVal(b); }, 0);
                    $(api.column(1).footer()).html("{{__('label.total_amount')}} {{Currency_Code()}} " + total);
                },
            });

            $('#input_type').change(function() { table.draw(); });
            $('#input_search').keyup(function() { table.draw(); });
        });
    </script>
@endsection
