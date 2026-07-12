@extends('admin.layout.page-app')
@section('page_title', __('label.page'))
@section('tab_title', __('label.page'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.page')}}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.page')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.page.create') }}" class="btn btn-default-white mw-120">
                        <i class="fa-solid fa-plus fa-sm mr-2"></i>{{__('label.add_page')}}
                    </a>
                </div>
            </div>

            <!-- Page Layout Setting -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-palette"></i>{{__('label.page_layout_setting')}}</div>
                <form id="layout_setting" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.background_color')}}<span class="text-danger">*</span></label>
                                    <div class="input-group colorpicker-component">
                                        <input type="text" id="hexcolor-1" class="form-control hexcolor" value="{{ isset($setting_data['page_background_color']) ? $setting_data['page_background_color'] : ''}}" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                        <input type="color" id="colorpicker-1" name="background_color" value="{{ isset($setting_data['page_background_color']) ? $setting_data['page_background_color'] : ''}}" class="colorpicker" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.title_color')}}<span class="text-danger">*</span></label>
                                    <div class="input-group colorpicker-component">
                                        <input type="text" id="hexcolor-2" class="form-control hexcolor" value="{{ isset($setting_data['page_title_color']) ? $setting_data['page_title_color'] : ''}}" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                        <input type="color" id="colorpicker-2" name="title_color" value="{{ isset($setting_data['page_title_color']) ? $setting_data['page_title_color'] : '' }}" class="colorpicker" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer mt-0">
                        <button type="button" class="btn btn-default mw-120" onclick="layout_setting()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.save')}}</button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>

            <!-- Search & Table -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-file-lines"></i>{{__('label.page')}}</div>
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
                                    <th>{{__('label.icon')}}</th>
                                    <th>{{__('label.title')}}</th>
                                    <th>{{__('label.sub_title')}}</th>
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
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.page.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'icon', name: 'icon', orderable: false, searchable: false,
                        render: function(data) {
                            return "<img src='" + data + "' class='table-image'>";
                        },
                    },
                    {
                        data: 'title', name: 'title', orderable: false, searchable: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    {
                        data: 'page_subtitle', name: 'page_subtitle', orderable: false, searchable: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function() {
                table.draw();
            });
        });

        // Color Picker
        $(document).ready(function() {
            // Event handler for color picker input change
            $('.colorpicker').on('input', function() {
                var target = $(this).attr('id').split('-')[1];
                $('#hexcolor-' + target).val(this.value.toUpperCase());
            });

            // Event handler for hex color input change
            $('.hexcolor').on('input', function() {
                var target = $(this).attr('id').split('-')[1];
                const hexPattern = /^#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/;
                if (hexPattern.test(this.value)) {
                    $('#colorpicker-' + target).val(this.value);
                }
            });
        });

        // Layout Setting
        function layout_setting() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#layout_setting")[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                enctype: 'multipart/form-data',
                type: 'POST',
                url: '{{route("admin.page.setting.save")}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'layout_setting', '{{ route("admin.page.index") }}');
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
                url: "{{route('admin.page.show', '')}}" + "/" + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
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
        };
    </script>
@endsection