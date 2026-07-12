@extends('producer.layout.page-app')
@section('page_title', __('label.change_password'))
@section('tab_title', __('label.change_password'))

@section('content')
    @include('producer.layout.sidebar')

    <div class="right-content">
        @include('producer.layout.header')

        <div class="body-content">
            <h1 class="page-title-sm">{{__('label.change_password')}}</h1>

            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('producer.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.change_password')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-lock"></i>{{__('label.change_password')}}</div>
                <div class="card-body">
                    <form id="change_password" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="{{ $producer_id }}">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.current_password')}}<span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="password" id="current_password" name="current_password" class="form-control" placeholder="{{__('label.current_password_here')}}" autofocus>
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
                    </form>
                </div>
                <div class="card-footer mt-0">
                    <button type="button" class="btn btn-default mw-120" onclick="update_password()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
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

        function update_password() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#change_password")[0]);
            $.ajax({
                type: 'POST',
                url: '{{route("producer.change-password.store")}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'change_password', '{{ route("producer.change-password.index") }}');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
