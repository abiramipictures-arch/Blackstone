@extends('admin.layout.page-app')
@section('page_title', __('label.rent_price_list'))
@section('tab_title', __('label.rent_price_list'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- Mobile page title -->
            <h1 class="page-title-sm">{{__('label.rent_price_list')}}</h1>

            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.rent_price_list')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Add Price -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-plus-circle"></i>{{__('label.add_rent_price_list')}}</div>
                <form id="save_price" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.price')}}<span class="text-danger">*</span></label>
                                    <input type="number" name="price" class="form-control" placeholder="{{__('label.price_here')}}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.android_product_package')}}</label>
                                    <input name="android_product_package" type="text" class="form-control" placeholder="{{__('label.android_package_here')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.ios_product_package')}}</label>
                                    <input name="ios_product_package" type="text" class="form-control" placeholder="{{__('label.ios_package_here')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.web_price_id_stripe_only')}}</label>
                                    <input name="web_price_id" type="text" class="form-control" placeholder="{{__('label.web_price_id_here')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="save_price()">
                            <i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.save')}}
                        </button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>

            <!-- Search & Table -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-list"></i>{{__('label.rent_price_list')}}</div>
                <div class="card-body">
                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.price')}}</th>
                                    <th>{{__('label.status')}}</th>
                                    <th>{{__('label.action')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_rent_price_list')}}</h5>
                            <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_price" enctype="multipart/form-data" autocomplete="off">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.price')}}<span class="text-danger">*</span></label>
                                            <input type="number" name="price" id="edit_price_val" class="form-control" placeholder="{{__('label.price_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.android_product_package')}}</label>
                                            <input type="text" name="android_product_package" id="edit_android_product_package" class="form-control" placeholder="{{__('label.android_package_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.ios_product_package')}}</label>
                                            <input type="text" name="ios_product_package" id="edit_ios_product_package" class="form-control" placeholder="{{__('label.ios_package_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.web_price_id_stripe_only')}}</label>
                                            <input type="text" name="web_price_id" id="edit_web_price_id" class="form-control" placeholder="{{__('label.web_price_id_here')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120 mr-2" onclick="update_price()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.rent-price-list.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'price', name: 'price', orderable: false, searchable: false,
                        render: function(data) { return data ? '{{ Currency_Code() }} ' + data : "-"; }
                    },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function() {
                table.draw();
            });
        });

        function save_price() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_price")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.rent-price-list.store") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_price', '{{ route("admin.rent-price-list.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        $(document).on("click", ".edit_price", function() {
            var id                      = $(this).data('id');
            var price                   = $(this).data('price');
            var android_product_package = $(this).data('android_product_package');
            var ios_product_package     = $(this).data('ios_product_package');
            var web_price_id            = $(this).data('web_price_id');

            $("#edit_id").val(id);
            $("#edit_price_val").val(price);
            $("#edit_android_product_package").val(android_product_package);
            $("#edit_ios_product_package").val(ios_product_package);
            $("#edit_web_price_id").val(web_price_id);
        });
        function update_price() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#update_price")[0]);
            var Edit_Id  = $("#edit_id").val();
            var url      = '{{ route("admin.rent-price-list.update", ":id") }}';
            url          = url.replace(':id', Edit_Id);

            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                enctype: 'multipart/form-data',
                type: 'POST',
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    if (resp.status == 200) { $('#EditModel').modal('toggle'); }
                    get_responce_message(resp, 'update_price', '{{ route("admin.rent-price-list.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        function change_status(id) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            $.ajax({
                type: "GET",
                url: "{{route('admin.rent-price-list.show', '')}}" + "/" + id,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: id,
                success: function(resp) {
                    $("#dvloader").hide();
                    if (resp.status == 200) {
                        if (resp.status_code == 1) {
                            $('#' + id).removeClass('status-off').addClass('status-on');
                            $('#text_' + id).text('{{__("label.show")}}').removeClass('text-danger').addClass('text-success');
                        } else {
                            $('#' + id).removeClass('status-on').addClass('status-off');
                            $('#text_' + id).text('{{__("label.hide")}}').removeClass('text-success').addClass('text-danger');
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
