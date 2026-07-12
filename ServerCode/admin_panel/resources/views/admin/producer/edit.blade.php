@extends('admin.layout.page-app')
@section('page_title', __('label.edit_producer'))
@section('tab_title', __('label.edit_producer'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.edit_producer')}}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.producer.index') }}">{{__('label.producer')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.edit_producer')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.producer.index') }}" class="btn btn-default-white mw-120">{{__('label.producer_list')}}</a>
                </div>
            </div>

            <!-- Edit Producer -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-pen-to-square"></i>{{__('label.edit_producer')}}</div>
                <form id="update_producer" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <input type="hidden" name="old_storage_type" value="{{ $data->storage_type }}">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.user_name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="user_name" value="{{ $data->user_name }}" class="form-control" placeholder="{{__('label.user_name_here')}}" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.full_name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="full_name" value="{{ $data->full_name }}" class="form-control" placeholder="{{__('label.full_name_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.mobile_number')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="mobile_number" value="{{ $data->mobile_number }}" class="form-control" placeholder="{{__('label.mobile_number_here')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.email')}}<span class="text-danger">*</span></label>
                                            <input type="email" name="email" value="{{ $data->email }}" class="form-control" placeholder="{{__('label.email_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.new_password')}}</label>
                                            <input type="password" name="password" class="form-control" placeholder="{{__('label.password_here')}}">
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
                                            <img src="{{ $data['image'] }}" class="imagePreview" />
                                        </label>
                                    </div>
                                    <input type="hidden" name="old_image" value="{{ $data['image'] }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="update_producer()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
                        <a href="{{route('admin.producer.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                        <input type="hidden" name="_method" value="PATCH">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function update_producer() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#update_producer")[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                enctype: 'multipart/form-data',
                type: 'POST',
                url: '{{ route("admin.producer.update", [$data->id]) }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'update_producer', '{{ route("admin.producer.index") }}');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
