@extends('producer.layout.page-app')
@section('page_title', __('label.profile'))
@section('tab_title', __('label.profile'))

@section('content')
    @include('producer.layout.sidebar')

    <div class="right-content">
        @include('producer.layout.header')

        <div class="body-content">
            <h1 class="page-title-sm">{{__('label.profile')}}</h1>

            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('producer.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.profile')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-user"></i>{{__('label.profile')}}</div>
                <div class="card-body">
                    <form id="save_profile" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <input type="hidden" name="old_storage_type" value="{{ $data->storage_type }}">
                        <div class="form-row">
                            <div class="col-md-9">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.user_name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="user_name" value="{{ $data->user_name }}" class="form-control" placeholder="{{__('label.user_name_here')}}" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.full_name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="full_name" value="{{ $data->full_name }}" class="form-control" placeholder="{{__('label.full_name_here')}}">
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
                                            <label>{{__('label.mobile_number')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="mobile_number" value="{{ $data->mobile_number }}" class="form-control" placeholder="{{__('label.mobile_number_here')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ml-5">
                                    <label>{{__('label.image')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type="file" name="image" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                        <label class="avatar-preview">
                                            <img src="{{ $data->image }}" class="imagePreview" />
                                        </label>
                                        <input type="hidden" name="old_image" value="{{ $data->image }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer mt-0">
                    <button type="button" class="btn btn-default mw-120" onclick="update_profile()"><i class="fa-solid fa-floppy-disk mr-2"></i>{{__('label.update')}}</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function update_profile() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_profile")[0]);
            $.ajax({
                type: 'POST',
                url: '{{route("producer.profile.store")}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_profile', '{{ route("producer.profile.index") }}');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
