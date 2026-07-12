@extends('admin.layout.page-app')
@section('page_title', __('label.coupon'))
@section('tab_title', __('label.coupon'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- Mobile page title -->
            <h1 class="page-title-sm">{{__('label.coupon')}}</h1>

            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.coupon')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Add Coupon -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-plus-circle"></i>{{__('label.add_coupon')}}</div>
                <form id="save_coupon" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="card-body pb-0">
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                    <input name="title" type="text" class="form-control" placeholder="{{__('label.title_here')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.coupon_code')}}</label>
                                    <input name="code" type="text" class="form-control" placeholder="{{__('label.coupon_code_here')}}" style="text-transform:uppercase;">
                                    <small class="text-muted">{{__('label.coupon_code_help')}}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.start_date')}}<span class="text-danger">*</span></label>
                                    <input name="start_date" type="date" class="form-control" min="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.end_date')}}<span class="text-danger">*</span></label>
                                    <input name="end_date" type="date" class="form-control" min="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.amount_type')}}</label>
                                    <select class="form-control" name="discount_type" id="discount_type">
                                        <option value="1">{{__('label.price')}}</option>
                                        <option value="2">{{__('label.percentage')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.price')}} / {{__('label.percentage')}}<span class="text-danger">*</span></label>
                                    <input name="discount_value" type="number" class="form-control" placeholder="{{__('label.please_enter_price_percentage')}}" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.coupon_type')}}<span class="text-danger">*</span></label>
                                    <select class="form-control" name="applicable_for" id="applicable_for">
                                        <option value="0">{{__('label.coupon_type_both')}}</option>
                                        <option value="1">{{__('label.coupon_type_subscription')}}</option>
                                        <option value="2">{{__('label.coupon_type_rental')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 package_id_wrap" style="display:none;">
                                <div class="form-group">
                                    <label>{{__('label.package')}}</label>
                                    <select class="form-control" name="package_id" id="package_id">
                                        <option value="">{{__('label.all')}}</option>
                                        @foreach($packages as $pkg)
                                            <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.is_use')}}</label>
                                    <select class="form-control" name="is_single_use">
                                        <option value="0">{{__('label.multiple_use')}}</option>
                                        <option value="1">{{__('label.single_use')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.usage_per_user')}}</label>
                                    <input name="usage_per_user" type="number" min="0" class="form-control" placeholder="{{__('label.usage_per_user_here')}}">
                                    <small class="text-muted">{{__('label.usage_per_user_help')}}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.is_use_limit')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_use_limit" id="is_use_limit_no" class="custom-control-input" value="0" checked>
                                            <label class="custom-control-label" for="is_use_limit_no">{{__('label.no')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_use_limit" id="is_use_limit_yes" class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="is_use_limit_yes">{{__('label.yes')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 use_limit_wrap" style="display:none;">
                                <div class="form-group">
                                    <label>{{__('label.use_limit')}}<span class="text-danger">*</span></label>
                                    <input name="usage_limit" type="number" min="1" class="form-control" placeholder="{{__('label.use_limit_here')}}">
                                    <small class="text-muted">{{__('label.use_limit_help')}}</small>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>{{__('label.description')}}</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="{{__('label.description_here')}}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="save_coupon()">
                            <i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.save')}}
                        </button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>

            <!-- Search & Table -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-list"></i>{{__('label.coupon')}}</div>
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
                                    <th>{{__('label.coupon_code')}}</th>
                                    <th>{{__('label.title')}}</th>
                                    <th>{{__('label.start_date')}}</th>
                                    <th>{{__('label.end_date')}}</th>
                                    <th>{{__('label.discount')}}</th>
                                    <th>{{__('label.is_use')}}</th>
                                    <th>{{__('label.used')}}</th>
                                    <th>{{__('label.coupon_type')}}</th>
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
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_coupon')}}</h5>
                            <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>     
                        </div>
                        <form id="update_coupon" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="edit_title" class="form-control" placeholder="{{__('label.title_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.coupon_code')}}</label>
                                            <input type="text" name="code" id="edit_code" class="form-control" placeholder="{{__('label.coupon_code_here')}}" style="text-transform:uppercase;">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.start_date')}}<span class="text-danger">*</span></label>
                                            <input name="start_date" type="date" id="edit_start_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.end_date')}}<span class="text-danger">*</span></label>
                                            <input name="end_date" type="date" id="edit_end_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.amount_type')}}</label>
                                            <select class="form-control" name="discount_type" id="edit_discount_type">
                                                <option value="1">{{__('label.price')}}</option>
                                                <option value="2">{{__('label.percentage')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.price')}} / {{__('label.percentage')}}<span class="text-danger">*</span></label>
                                            <input name="discount_value" type="number" id="edit_discount_value" class="form-control" placeholder="{{__('label.please_enter_price_percentage')}}" min="0" step="0.01">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.coupon_type')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="applicable_for" id="edit_applicable_for">
                                                <option value="0">{{__('label.coupon_type_both')}}</option>
                                                <option value="1">{{__('label.coupon_type_subscription')}}</option>
                                                <option value="2">{{__('label.coupon_type_rental')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_package_id_wrap" style="display:none;">
                                        <div class="form-group">
                                            <label>{{__('label.package')}}</label>
                                            <select class="form-control" name="package_id" id="edit_package_id">
                                                <option value="">{{__('label.all')}}</option>
                                                @foreach($packages as $pkg)
                                                    <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.is_use')}}</label>
                                            <select class="form-control" name="is_single_use" id="edit_is_single_use">
                                                <option value="0">{{__('label.multiple_use')}}</option>
                                                <option value="1">{{__('label.single_use')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.usage_per_user')}}</label>
                                            <input name="usage_per_user" type="number" min="0" id="edit_usage_per_user" class="form-control" placeholder="{{__('label.usage_per_user_here')}}">
                                            <small class="text-muted">{{__('label.usage_per_user_help')}}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.is_use_limit')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="edit_is_use_limit" id="edit_is_use_limit_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="edit_is_use_limit_no">{{__('label.no')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="edit_is_use_limit" id="edit_is_use_limit_yes" class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="edit_is_use_limit_yes">{{__('label.yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_use_limit_wrap" style="display:none;">
                                        <div class="form-group">
                                            <label>{{__('label.use_limit')}}<span class="text-danger">*</span></label>
                                            <input name="usage_limit" type="number" min="1" id="edit_usage_limit" class="form-control" placeholder="{{__('label.use_limit_here')}}">
                                            <small class="text-muted">{{__('label.use_limit_help')}}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.used_count')}}</label>
                                            <input type="text" id="edit_used_count" class="form-control" readonly disabled>
                                            <small class="text-muted">{{__('label.used_count_help')}}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.description')}}</label>
                                            <textarea name="description" id="edit_description" class="form-control" rows="2" placeholder="{{__('label.description_here')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120 mr-2" onclick="update_coupon()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
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
                    url: "{{ route('admin.coupon.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'code', name: 'code', orderable: false, searchable: false,
                        render: function(data) { return data ? '<strong class="primary-color">' + data + '</strong>' : "-"; }
                    },
                    {
                        data: 'title', name: 'title', orderable: false, searchable: false,
                        render: function(data) { return data ? data : "-"; }
                    },
                    {
                        data: 'start_date', name: 'start_date', orderable: false, searchable: false,
                        render: function(data) { return data ? data : "-"; }
                    },
                    {
                        data: 'end_date', name: 'end_date', orderable: false, searchable: false,
                        render: function(data) { return data ? data : "-"; }
                    },
                    {
                        data: 'discount_value', name: 'discount_value', orderable: false, searchable: false,
                        render: function(data, type, full) {
                            if (!data && data !== 0) return "-";
                            return full.discount_type == 2
                                ? '<strong>' + data + '%</strong>'
                                : '<strong>{{ Currency_Code() }} ' + data + '</strong>';
                        }
                    },
                    {
                        data: 'is_single_use', name: 'is_single_use', orderable: false, searchable: false,
                        render: function(data) {
                            if (data == 0) return "{{__('label.multiple_use')}}";
                            if (data == 1) return "{{__('label.single_use')}}";
                            return "-";
                        }
                    },
                    {
                        data: 'used_count', name: 'used_count', orderable: false, searchable: false,
                        render: function(data, type, full) {
                            var used  = data ?? 0;
                            var limit = full.usage_limit > 0 ? full.usage_limit : '<span style="font-size:18px;font-weight:700;line-height:1;">∞</span>';
                            return used + ' / ' + limit;
                        }
                    },
                    {
                        data: 'applicable_for', name: 'applicable_for', orderable: false, searchable: false,
                        render: function(data) {
                            if (data == 1) return "{{__('label.coupon_type_subscription')}}";
                            if (data == 2) return "{{__('label.coupon_type_rental')}}";
                            return "{{__('label.coupon_type_both')}}";
                        }
                    },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function() {
                table.draw();
            });

            /* Add form — use_limit toggle */
            $('input[type=radio][name=is_use_limit]').change(function() {
                $('.use_limit_wrap').toggle(this.value == 1);
            });

            /* Add form — package_id toggle */
            $('#applicable_for').change(function() {
                $('.package_id_wrap').toggle($(this).val() == 1);
            });
        });

        function save_coupon() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_coupon")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.coupon.store") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_coupon', '{{ route("admin.coupon.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        $(document).on("click", ".edit_coupon", function() {
            var id             = $(this).data('id');
            var title          = $(this).data('title');
            var code           = $(this).data('code');
            var description    = $(this).data('description');
            var start_date     = $(this).data('start_date');
            var end_date       = $(this).data('end_date');
            var discount_type  = $(this).data('discount_type');
            var discount_value = $(this).data('discount_value');
            var applicable_for = $(this).data('applicable_for');
            var package_id     = $(this).data('package_id');
            var usage_limit    = $(this).data('usage_limit');
            var usage_per_user = $(this).data('usage_per_user');
            var is_single_use  = $(this).data('is_single_use');
            var used_count     = $(this).data('used_count');

            $("#edit_id").val(id);
            $("#edit_title").val(title);
            $("#edit_code").val(code);
            $("#edit_description").val(description);
            $("#edit_start_date").val(start_date);
            $("#edit_end_date").val(end_date);
            $("#edit_discount_type").val(discount_type);
            $("#edit_discount_value").val(discount_value);
            $("#edit_usage_per_user").val(usage_per_user);
            $("#edit_is_single_use").val(is_single_use);
            $("#edit_used_count").val(used_count ?? 0);

            var hasLimit = usage_limit > 0;
            $("#edit_is_use_limit_yes").prop('checked', hasLimit);
            $("#edit_is_use_limit_no").prop('checked', !hasLimit);
            $(".edit_use_limit_wrap").toggle(hasLimit);
            if (hasLimit) { $("#edit_usage_limit").val(usage_limit); }

            $('input[type=radio][name=edit_is_use_limit]').off('change').on('change', function() {
                $(".edit_use_limit_wrap").toggle(this.value == 1);
            });

            /* Bind handler first, then set value so toggle fires correctly */
            $('#edit_applicable_for').off('change').on('change', function() {
                $(".edit_package_id_wrap").toggle($(this).val() == 1);
            });
            $("#edit_applicable_for").val(applicable_for).trigger('change');
            $("#edit_package_id").val(package_id);
        });
        function update_coupon() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#update_coupon")[0]);
            var Edit_Id  = $("#edit_id").val();
            var url      = '{{ route("admin.coupon.update", ":id") }}';
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
                    get_responce_message(resp, 'update_coupon', '{{ route("admin.coupon.index") }}');
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
                url: "{{route('admin.coupon.show', '')}}" + "/" + id,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
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
        }
    </script>
@endsection
