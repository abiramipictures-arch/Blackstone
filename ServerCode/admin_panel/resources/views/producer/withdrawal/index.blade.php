@extends('producer.layout.page-app')
@section('page_title', __('label.withdrawal_request'))
@section('tab_title', __('label.withdrawal_request'))

@section('content')
    @include('producer.layout.sidebar')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

    <div class="right-content">
        @include('producer.layout.header')

        <div class="body-content">
            <h1 class="page-title-sm">{{__('label.withdrawal_request')}}</h1>

            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('producer.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.withdrawal_request')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-money-bill-transfer"></i>{{__('label.add_withdrawal_request')}}</div>
                <div class="card-body pb-0">
                    <form id="withdrawal_request" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{__('label.price')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="price" class="form-control" placeholder="{{__('label.price_here')}}" autofocus>
                                    <label class="text-gray mt-3">{{__('label.min_withdrawal_amount')}} : {{ $setting['min_withdrawal_amount'] }}</label>
                                </div>
                            </div>
                            <div class="col-md-3 ml-5">
                                <div class="form-group">
                                    <label>{{__('label.wallet_amount')}}</label>
                                    <input type="text" value="{{ $producer['wallet'] }}" class="form-control" readonly>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer mt-0">
                    <button type="button" class="btn btn-default mw-120" onclick="save_request()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.save')}}</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </div>

            <div class="card custom-border-card mt-3">
                <div class="card-header"><i class="fa-solid fa-list"></i>{{__('label.withdrawal_request')}}</div>
                <div class="card-body">
                    <div class="page-search p-0 mb-3">
                        <div class="sorting w-25 p-0">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('producer.withdrawal.index') }}",
                    data: function (d) {
                        d.input_status = $('#input_status').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'price',
                        name: 'price',
                        render: function (data) {
                            return data ? data : "-";
                        }
                    },
                    { data: 'date', name: 'date', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                ],
            });

            $('#input_status').change(function () {
                table.draw();
            });
        });

        function save_request() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#withdrawal_request")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("producer.withdrawal.store") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'withdrawal_request', '{{ route("producer.withdrawal.index") }}');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
