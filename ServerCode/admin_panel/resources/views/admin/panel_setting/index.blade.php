@extends('admin.layout.page-app')
@section('page_title', __('label.panel_settings'))
@section('tab_title', __('label.panel_settings'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.panel_settings')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.panel_settings')}}</li>
                    </ol>
                </div>
            </div>

            <form id="pannel_setting" enctype="multipart/form-data">
                <input type="hidden" name="old_panel_login_page_img" value="{{ $result['panel_login_page_img'] }}">
                <input type="hidden" name="old_panel_profile_no_img" value="{{ $result['panel_profile_no_img'] }}">
                <input type="hidden" name="old_panel_normal_no_img" value="{{ $result['panel_normal_no_img'] }}">
                <input type="hidden" name="old_panel_portrait_no_img" value="{{ $result['panel_portrait_no_img'] }}">
                <input type="hidden" name="old_panel_landscape_no_img" value="{{ $result['panel_landscape_no_img'] }}">
                <input type="hidden" name="old_powered_by_image" value="{{ $result['powered_by_image'] }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="row">
                    <div class="col-3">
                        <div class="card custom-border-card">
                            <div class="card-header"><i class="fa-solid fa-image"></i>{{__('label.login_page_image')}}</div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="avatar-upload image-upload-wrapper">
                                                <input type='file' name="panel_login_page_img" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                                <label class="avatar-preview">
                                                    <img src="{{ $result['panel_login_page_img'] }}" class="imagePreview" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card custom-border-card">
                            <div class="card-header"><i class="fa-solid fa-bolt"></i>{{__('label.powered_by_image')}}</div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="avatar-upload image-upload-wrapper">
                                                <input type='file' name="powered_by_image" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                                <label class="avatar-preview">
                                                    <img src="{{ $result['powered_by_image'] }}" class="imagePreview" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card custom-border-card">
                            <div class="card-header"><i class="fa-solid fa-images"></i>{{__('label.by_default_image')}}</div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.profile_image')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type='file' name="panel_profile_no_img" class="imageUpload" accept=".png, .jpg, .jpeg .webp" hidden/>
                                                <label class="avatar-preview">
                                                    <img src="{{$result['panel_profile_no_img']}}" class="imagePreview" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.normal_image')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type='file' name="panel_normal_no_img" class="imageUpload" accept=".png, .jpg, .jpeg .webp" hidden/>
                                                <label class="avatar-preview">
                                                    <img src="{{$result['panel_normal_no_img']}}" class="imagePreview" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.portrait_image')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type='file' name="panel_portrait_no_img" class="imageUpload" accept=".png, .jpg, .jpeg .webp" hidden/>
                                                <label class="avatar-preview">
                                                    <img src="{{$result['panel_portrait_no_img']}}" class="imagePreview" />
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.landscape_image')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type='file' name="panel_landscape_no_img" class="imageUpload" accept=".png, .jpg, .jpeg .webp" hidden/>
                                                <label class="avatar-preview landscape-preview">
                                                    <img src="{{ $result['panel_landscape_no_img'] }}" class="imagePreview" id="imagePreviewLandscape"/>
                                                    <input type="hidden" class="form-control" id="landscape_tmdb" name="landscape_tmdb">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer mt-0">
                                <button type="button" class="btn btn-default mw-120" onclick="save_panel_setting()">
                                    <i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function save_panel_setting() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#pannel_setting")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.panel.setting.save") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'pannel_setting', '{{ route("admin.panel.setting.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
