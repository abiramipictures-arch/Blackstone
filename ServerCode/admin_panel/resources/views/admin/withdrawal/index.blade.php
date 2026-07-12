@extends('admin.layout.page-app')
@section('page_title', __('label.withdrawal_request'))
@section('tab_title', __('label.withdrawal_request'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.withdrawal_request')}}</h1>

            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.withdrawal_request')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row">
                <div class="col-sm-6 col-lg-4 mb-3">
                    <div class="card-earning stat-card stat-card-pending">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $pending_sum['total'] ?? 0 }}</p>
                                <p class="earning-title mb-0">{{__('label.pending')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-clock fa-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 mb-3">
                    <div class="card-earning stat-card stat-card-approve">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ Currency_Code() }}{{ $completed_sum['total'] ?? 0 }}</p>
                                <p class="earning-title mb-0">{{__('label.completed')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-circle-check fa-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-money-bill-transfer mr-2"></i>{{__('label.withdrawal_request')}}</div>
                <div class="card-body">
                    <div class="page-search p-0">
                        <div class="sorting w-50 mr-2 px-0">
                            <select class="form-control select2" id="input_producer">
                                <option value="all">{{__('label.all_producer')}}</option>
                                @foreach($producer as $value)
                                    <option value="{{$value->id}}">{{$value->full_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="sorting w-50 px-0">
                            <select class="form-control" id="input_status">
                                <option value="all">{{__('label.all_status')}}</option>
                                <option value="0">{{__('label.pending')}}</option>
                                <option value="1">{{__('label.completed')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.producer')}}</th>
                                    <th>{{__('label.email')}}</th>
                                    <th>{{__('label.mobile_number')}}</th>
                                    <th>{{__('label.price')}}</th>
                                    <th>{{__('label.date')}}</th>
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
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        const demo_mode = '{{ Demo_Mode() }}';
        function maskEmail(email) {
            const [user, domain] = email.split('@');
            return user.charAt(0) + '******@' + domain;
        }

        $(document).ready(function() {
            $('#input_producer').select2({ width: '100%' });

            table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.withdrawal.index') }}",
                    data: function(d) {
                        d.input_status   = $('#input_status').val();
                        d.input_producer = $('#input_producer').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'producer',    name: 'producer',    orderable: false, searchable: false,
                      render: function(data) { return data ? data.full_name : '-'; } },
                    { data: 'producer',    name: 'producer',    orderable: false, searchable: false,
                      render: function(data) {
                          if (!data?.email) return '-';
                          return demo_mode == 0 ? maskEmail(data.email) : data.email;
                      }},
                    { data: 'producer',    name: 'producer',    orderable: false, searchable: false,
                      render: function(data) {
                          if (!data?.mobile_number) return '-';
                          return demo_mode == 0 ? '******' + data.mobile_number.slice(-4) : data.mobile_number;
                      }},
                    { data: 'price',       name: 'price',       orderable: false, searchable: false,
                      render: function(data) { return data ? "{{ Currency_Code() }}" + ' ' + data : '-'; } },
                    { data: 'date',        name: 'date',        orderable: false, searchable: false },
                    { data: 'status',      name: 'status',      orderable: false, searchable: false },
                ],
            });

            $('#input_status, #input_producer').change(function() { table.draw(); });
        });

        function change_status(id) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            $.ajax({
                type: 'GET',
                url: "{{ route('admin.withdrawal.show', '') }}" + "/" + id,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(resp) {
                    $("#dvloader").hide();
                    if (resp.status == 200) {
                        if (resp.status_code == 1) {
                            $('#status_' + id).text('{{__("label.completed")}}').removeClass('hide-btn').addClass('show-btn');
                        } else {
                            $('#status_' + id).text('{{__("label.pending")}}').removeClass('show-btn').addClass('hide-btn');
                        }
                        toastr.success(resp.success);
                    } else {
                        toastr.error(resp.errors);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
