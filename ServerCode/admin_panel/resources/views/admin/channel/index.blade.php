@extends('admin.layout.page-app')
@section('page_title', __('label.channel'))
@section('tab_title', __('label.channel'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- Mobile page title -->
            <h1 class="page-title-sm">{{__('label.channel')}}</h1>

            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.channel')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Add Channel -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-plus-circle"></i>{{__('label.add_channel')}}</div>
                <form id="save_channel" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                            <input type="text" value="" name="name" class="form-control" placeholder="{{__('label.enter_name')}}" autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.is_title')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="is_title" id="is_title_no" class="custom-control-input" value="0" checked>
                                                    <label class="custom-control-label" for="is_title_no">{{__('label.no')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="is_title" id="is_title_yes" class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="is_title_yes">{{__('label.yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ml-5">
                                    <label>{{__('label.portrait_image')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type='file' name="portrait_img" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                        <label class="avatar-preview">
                                            <img src="{{ asset('assets/imgs/upload_img.png') }}" class="imagePreview" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.landscape_image')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type='file' name="landscape_img" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                        <label class="avatar-preview landscape-preview">
                                            <img src="{{ asset('assets/imgs/upload_img_land.png') }}" class="imagePreview" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="save_channel()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.save')}}</button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>

            <!-- Search & Table -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-list"></i>{{__('label.channel')}}</div>
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
                                    <th>{{__('label.image')}}</th>
                                    <th>{{__('label.name')}}</th>
                                    <th>{{__('label.status')}}</th>
                                    <th>{{__('label.action')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Edit Model -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_channel')}}</h5>
                            <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_channel" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id">
                                <input type="hidden" name="old_storage_type" id="edit_storage_type">
                                <input type="hidden" name="old_portrait_img" id="edit_old_portrait_img">
                                <input type="hidden" name="old_landscape_img" id="edit_old_landscape_img">
                                <div class="form-row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="edit_name" class="form-control" placeholder="{{__('label.enter_name')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.is_title')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="is_title" id="edit_is_title_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="edit_is_title_no">{{__('label.no')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="is_title" id="edit_is_title_yes" class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="edit_is_title_yes">{{__('label.yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group ml-3">
                                            <label>{{__('label.portrait_image')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type='file' name="portrait_img" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                                <label class="avatar-preview">
                                                    <img src="" class="imagePreview" id="imagePreviewModel" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.landscape_image')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type='file' name="landscape_img" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                                <label class="avatar-preview landscape-preview">
                                                    <img src="" class="imagePreview" id="imagePreviewLandscapeModel" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120 mr-2" onclick="update_channel()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
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
                    url: "{{ route('admin.channel.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'portrait_img', name: 'portrait_img', orderable: false, searchable: false,
                        render: function(data) {
                            return `<img src='${data}' class='table-image'>`;
                        },
                    },
                    {
                        data: 'name', name: 'name', orderable: false, searchable: false,
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

        function save_channel() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_channel")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.channel.store") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_channel', '{{ route("admin.channel.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        function change_status(id, status) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            $.ajax({
                type: "GET",
                url: "{{route('admin.channel.show', '')}}" + "/" + id,
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

        $(document).on("click", ".edit_channel", function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var portrait_img = $(this).data('portrait_img');
            var landscape_img = $(this).data('landscape_img');
            var is_title = $(this).data('is_title');
            var storage_type = $(this).data('storage_type');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_name").val(name);
            $(".modal-body #imagePreviewModel").attr("src", portrait_img);
            $(".modal-body #imagePreviewLandscapeModel").attr("src", landscape_img);
            $(".modal-body #edit_old_portrait_img").val(portrait_img);
            $(".modal-body #edit_old_landscape_img").val(landscape_img);
            $(".modal-body #edit_storage_type").val(storage_type);

            if (is_title == 1) {
                $('input:radio[id=edit_is_title_yes]').prop('checked', true);
            } else {
                $('input:radio[id=edit_is_title_no]').prop('checked', true);
            }
        });
        function update_channel() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#update_channel")[0]);
            var Edit_Id = $("#edit_id").val();

            var url = '{{ route("admin.channel.update", ":id") }}';
            url = url.replace(':id', Edit_Id);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                enctype: 'multipart/form-data',
                type: 'POST',
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();

                    if (resp.status == 200) {
                        $('#EditModel').modal('toggle');
                    }
                    get_responce_message(resp, 'update_channel', '{{ route("admin.channel.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
