@extends('admin.layout.page-app')
@section('page_title', __('label.users'))
@section('tab_title', __('label.users'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.users')}}</h1>

            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.users')}}</li>
                    </ol>
                </div>
                <!-- <div class="col-sm-2">
                    <a href="{{ route('admin.user.create') }}" class="btn btn-default-white mw-120">{{__('label.add_user')}}</a>
                </div> -->
            </div>

            <!-- Search & Table-->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-list"></i>{{__('label.users')}}</div>
                <div class="card-body">
                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                        </div>
                        <div class="sorting w-50">
                            <select class="form-control" name="input_login_type" id="input_login_type">
                                <option value="all">{{__('label.all_type')}}</option>
                                <option value="1">{{__('label.otp')}}</option>
                                <option value="2">{{__('label.google')}}</option>
                                <option value="3">{{__('label.apple')}}</option>
                                <option value="4">{{__('label.normal')}}</option>
                            </select>
                        </div>
                        <div class="sorting w-50">
                            <select class="form-control" name="input_type" id="input_type">
                                <option value="all">{{__('label.all_time')}}</option>
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
                                    <th>{{__('label.image')}}</th>
                                    <th>{{__('label.name')}}</th>
                                    <th>{{__('label.contact')}}</th>                                    
                                    <th>{{__('label.wallet_amount')}}</th>
                                    <th>{{__('label.register_date')}}</th>
                                    <th>{{__('label.type')}}</th>
                                    <th>{{__('label.status')}}</th>
                                    <th>{{__('label.action')}}</th>
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
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax:
                    {
                    url: "{{ route('admin.user.index') }}",
                    data: function(d){
                        d.input_type = $('#input_type').val();
                        d.input_login_type = $('#input_login_type').val();
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'image', name: 'image', orderable: false, searchable: false,
                        render: function(data, type, full, meta) {
                            return "<img src='" + data + "' class='table-image' />";
                        },
                    },
                    {
                        data: 'name', name: 'name', orderable: false,
                        render: function(data, type, row) {
                            return `<div style="text-align: left;">${row.user_name || '-'}<br><span style="font-size: 14px; font-weight: 600;">${row.full_name || '-'}</span>`;
                        }
                    },
                    {
                        data: 'email', name: 'email', orderable: false,
                        render: function(data, type, row) {
                            if (demo_mode == 0) {
                                const mobile = row.mobile_number ? ' ******' + row.mobile_number.slice(-4) : '-';
                                const email = row.email ? maskEmail(row.email) : '-';

                                return `<div style="text-align: left;">${mobile}<br><span style="font-size: 14px; font-weight: 600;">${email}</span></div>`;
                            }
                            return `<div style="text-align: left;">${row.mobile_number || '-'}<br><span style="font-size: 14px; font-weight: 600;">${row.email || '-'}</span></div>`;
                        }
                    },
                    {
                        data: 'wallet_amount', name: 'wallet_amount', orderable: false, searchable: false,
                        render: function(data) {
                            return (data !== null && data !== undefined) ? "{{ Currency_Code() }} " + data : "{{ Currency_Code() }} 0";
                        }
                    },
                    {
                        data: 'date', name: 'date', orderable: false, searchable: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'type', name: 'type', orderable: false, searchable: false,
                        render: function(data) {
                            if (data == 1) {
                                return "<i class='fa-solid fa-mobile-screen-button fa-2x' title='{{__('label.otp')}}'></i>";
                            } else if (data == 2) {
                                return "<i class='fa-brands fa-google fa-2x' title='{{__('label.google')}}'></i>";
                            } else if (data == 3) {
                                return "<i class='fa-brands fa-apple fa-2x' title='{{__('label.apple')}}'></i>";
                            } else if (data == 4) {
                                return "<i class='fa-solid fa-lock fa-2x' title='{{__('label.normal')}}'></i>";
                            }
                            return "-";
                        }
                    },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            $('#input_type, #input_login_type').change(function(){
                table.draw();
            });
            $('#input_search').keyup(function(){
                table.draw();
            });
        });

        function change_status(id, status) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            $.ajax({
                type: "GET",
                url: "{{route('admin.user.show', '')}}" + "/" + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: id,
                success: function(resp) {
                    $("#dvloader").hide();
                    if (resp.status == 200) {
                        if (resp.status_code == 1) {
                            $('#' + id).removeClass('status-off').addClass('status-on');
                            $('#text_' + id).text('{{__("label.active")}}').removeClass('text-danger').addClass('text-success');
                        } else {
                            $('#' + id).removeClass('status-on').addClass('status-off');
                            $('#text_' + id).text('{{__("label.inactive")}}').removeClass('text-success').addClass('text-danger');
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
        };
    </script>
@endsection