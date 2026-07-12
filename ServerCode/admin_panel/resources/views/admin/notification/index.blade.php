@extends('admin.layout.page-app')
@section('page_title', __('label.notification'))
@section('tab_title', __('label.notification'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.notification')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.notification')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.notification.setting') }}" class="btn btn-default-white mw-120">{{__('label.notification_setting')}}</a>
                </div>
            </div>

            <!-- Add Notification -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-plus-circle mr-2"></i>{{__('label.add_notification')}}</div>
                <form id="save_notification" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                            <input name="title" type="text" class="form-control" placeholder="{{__('label.title_here')}}" autofocus>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.message')}}<span class="text-danger">*</span></label>
                                            <textarea class="form-control" rows="2" name="message" placeholder="{{__('label.description_here')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group ml-5">
                                    <label>{{__('label.image')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type='file' name="image" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                        <label class="avatar-preview">
                                            <img src="{{ asset('assets/imgs/upload_img.png') }}" class="imagePreview" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="save_notification()">
                            <i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.save')}}
                        </button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>

            <!-- Search & Table -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-list mr-2"></i>{{__('label.notification')}}</div>
                <div class="card-body">
                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.image')}}</th>
                                    <th>{{__('label.title')}}</th>
                                    <th>{{__('label.message')}}</th>
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
                    url: "{{ route('admin.notification.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'image', name: 'image', orderable: false, searchable: false,
                        render: function(data) {
                            return "<img src='" + data + "' class='table-image'>";
                        },
                    },
                    { data: 'title', name: 'title', orderable: false, searchable: false },
                    {
                        data: 'message', name: 'message', orderable: false, searchable: false,
                        render: function(data) {
                            return data ? '<div style="text-align:left; font-size:14px;">' + data + '</div>' : '-';
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function() { table.draw(); });
        });

        function save_notification() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_notification")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.notification.store") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_notification', '{{ route("admin.notification.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
