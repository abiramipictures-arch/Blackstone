@extends('admin.layout.page-app')
@section('page_title', __('label.add_user'))
@section('tab_title', __('label.add_user'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.add_user')}}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{__('label.users')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.add_user')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-default-white mw-120">{{__('label.user_list')}}</a>
                </div>
            </div>

            <!-- Add User -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-plus-circle"></i>{{__('label.add_user')}}</div>
                <form id="save_user" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.full_name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="full_name" class="form-control" placeholder="{{__('label.full_name_here')}}" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.mobile_number')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="mobile_number" class="form-control" placeholder="{{__('label.mobile_number_here')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.email')}}<span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control" placeholder="{{__('label.email_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" style="display: block;">
                                            <label>{{__('label.password')}}<span class="text-danger">*</span></label>
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
                                            <img src="{{ asset('assets/imgs/upload_img.png') }}" class="imagePreview" />
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="save_user()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.save')}}</button>
                        <a href="{{route('admin.user.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
			</div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function save_user() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_user")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.user.store") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_user', '{{ route("admin.user.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection