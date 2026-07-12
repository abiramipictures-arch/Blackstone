@extends('admin.layout.page-app')
@section('page_title', __('label.system_settings'))
@section('tab_title', __('label.system_settings'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">

            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.system_settings')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.system_settings')}}</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="card custom-border-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <span><i class="fa-solid fa-broom mr-2"></i>{{__('label.clear_cache')}}</span>
                            <button type="button" class="btn btn-default mw-120" onclick="clear_data()">{{__('label.clear_cache')}}</button>
                        </div>
                        <div class="card-body">
                            <p class="system-note mb-0">{{__('label.clear_cache_notes')}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card custom-border-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <span><i class="fa-solid fa-database mr-2"></i>{{__('label.backup_database')}}</span>
                            <button type="button" class="btn btn-default mw-120" onclick="backup_database()">{{__('label.download')}}</button>
                        </div>
                        <div class="card-body">
                            <p class="system-note mb-0">{{__('label.backup_database_notes')}}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="card custom-border-card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <span><i class="fa-solid fa-trash-can mr-2"></i>{{__('label.clean_database')}}</span>
                            <button type="button" class="btn btn-default mw-120" onclick="clean_database()">{{__('label.clean_database')}}</button>
                        </div>
                        <div class="card-body">
                            <p class="system-note mb-0">{{__('label.dalete_all_data_in_database')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function clear_data() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            Swal.fire({
                theme             : 'dark',
                title             : "{{ __('label.clear_cache_confirm_title') }}",
                text              : "{{ __('label.clear_cache_confirm_text') }}",
                icon              : 'warning',
                showCancelButton  : true,
                confirmButtonColor: '#e3000b',
                cancelButtonColor : '#058f00',
                confirmButtonText : "{{ __('label.yes') }}",
                cancelButtonText  : "{{ __('label.no') }}",
            }).then((result) => {
                if (!result.isConfirmed) return;

                $("#dvloader").show();
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: 'POST',
                    url: '{{ route("admin.system.setting.cleardata") }}',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, '', '{{ route("admin.system.setting.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            });
        }

        function backup_database() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            Swal.fire({
                theme             : 'dark',
                title             : "{{ __('label.backup_database_confirm_title') }}",
                text              : "{{ __('label.backup_database_confirm_text') }}",
                icon              : 'question',
                showCancelButton  : true,
                confirmButtonColor: '#058f00',
                cancelButtonColor : '#6c757d',
                confirmButtonText : "{{ __('label.yes') }}",
                cancelButtonText  : "{{ __('label.no') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route("admin.system.setting.downloadsqlfile") }}';
                }
            });
        }

        function clean_database() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            Swal.fire({
                theme             : 'dark',
                title             : "{{ __('label.clean_database_confirm_title') }}",
                text              : "{{ __('label.clean_database_confirm_text') }}",
                icon              : 'warning',
                showCancelButton  : true,
                confirmButtonColor: '#e3000b',
                cancelButtonColor : '#058f00',
                confirmButtonText : "{{ __('label.yes') }}",
                cancelButtonText  : "{{ __('label.no') }}",
            }).then((result) => {
                if (!result.isConfirmed) return;

                $("#dvloader").show();
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: 'POST',
                    url: '{{ route("admin.system.setting.cleandatabase") }}',
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(resp) {
                        $("#dvloader").hide();
                        get_responce_message(resp, '', '{{ route("admin.system.setting.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            });
        }
    </script>
@endsection
