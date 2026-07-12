@extends('admin.layout.page-app')
@section('page_title', __('label.profile'))
@section('tab_title', __('label.profile'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- Mobile page title -->
            <h1 class="page-title-sm">{{__('label.profile')}}</h1>

            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.profile')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-user-circle"></i>{{__('label.personal_info')}}</div>
                <form id="save_profile" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.user_name')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="user_name" value="{{ $data->user_name }}" class="form-control" placeholder="{{__('label.user_name_here')}}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.email')}}<span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ $data->email }}" class="form-control" placeholder="{{__('label.email_here')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="update_profile()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-lock"></i>{{__('label.change_password')}}</div>
                <form id="save_change_password" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="{{ $data->id }}">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.current_password')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="current_password" name="current_password" class="form-control" placeholder="{{__('label.current_password_here')}}">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary px-3" onclick="togglePassword('current_password', this)">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.new_password')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="new_password" name="new_password" class="form-control" placeholder="{{__('label.new_password_here')}}">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary px-3" onclick="togglePassword('new_password', this)">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.confirm_password')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="{{__('label.confirm_password_here')}}">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary px-3" onclick="togglePassword('confirm_password', this)">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="update_password()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function togglePassword(fieldId, btn) {
            var input = document.getElementById(fieldId);
            var icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        function update_profile() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_profile")[0]);
            $.ajax({
                type: 'POST',
                url: '{{route("admin.profile.store")}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_profile', '{{ route("admin.profile.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        function update_password() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_change_password")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.profile.changepassword") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_change_password', '{{ route("admin.profile.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
