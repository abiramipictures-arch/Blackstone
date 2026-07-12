@extends('admin.layout.page-app')
@section('page_title', __('label.app_settings'))
@section('tab_title', __('label.app_settings'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.app_settings')}}</h1>

            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.app_settings')}}</li>
                    </ol>
                </div>
            </div>

            <ul class="nav nav-pills custom-tabs inline-tabs mt-0" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="app-tab" data-toggle="tab" href="#app" role="tab" aria-controls="app" aria-selected="true">{{__('label.app_settings')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="configrations-tab" data-toggle="tab" href="#configrations" role="tab" aria-controls="configrations" aria-selected="false">{{__('label.configrations')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="smtp-tab" data-toggle="tab" href="#smtp" role="tab" aria-controls="smtp" aria-selected="false">{{__('label.smtp')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab" aria-controls="social" aria-selected="false">{{__('label.social_setting')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="onboarding-tab" data-toggle="tab" href="#onboarding" role="tab" aria-controls="onboarding" aria-selected="false">{{__('label.onboarding_screen')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="awss3-tab" data-toggle="tab" href="#awss3" role="tab" aria-controls="awss3" aria-selected="false">{{__('label.storage')}}</a>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <!-- App Setting -->
                <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
                    <div class="card custom-border-card">
                        <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.app_settings')}}</div>
                        <form id="app_setting" enctype="multipart/form-data">
                            <div class="card-body pb-0">
                                <div class="form-row">
                                <input type="hidden" name="old_storage_type" value="{{$data['app_logo_storage_type']}}">
                                    <div class="col-md-9">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.app_name')}}</label>
                                                <input type="text" name="app_name" value="{{$data['app_name']}}" class="form-control" placeholder="{{__('label.app_name_here')}}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.app_version')}}</label>
                                                <input type="text" name="app_version" value="{{$data['app_version']}}" class="form-control" placeholder="{{__('label.app_version_here')}}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.author')}}</label>
                                                <input type="text" name="author" value="{{$data['author']}}" class="form-control" placeholder="{{__('label.author_here')}}">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.email')}} </label>
                                                <input type="email" name="email"  value="{{$data['email']}}" class="form-control" placeholder="{{__('label.email_here')}}">
                                            </div>
                                            <div class="form-group  col-md-4">
                                                <label> {{__('label.contact')}} </label>
                                                <input type="text" name="contact" value="{{$data['contact']}}" class="form-control" placeholder="{{__('label.contact_here')}}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.website')}}</label>
                                                <input type="text" name="website" value="{{$data['website']}}" class="form-control" placeholder="{{__('label.website_here')}}">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label>{{__('label.app_description')}}</label>
                                                <textarea name="app_desripation" rows="2" class="form-control" placeholder="{{__('label.description_here')}}">{{$data['app_desripation']}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ml-5">
                                            <label>{{__('label.image')}}</label>
                                            <div class="avatar-upload my-2 image-upload-wrapper">
                                                <input type='file' name="app_logo" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                                <label class="avatar-preview">
                                                    <img src="{{ $data['app_logo'] }}" class="imagePreview" />
                                                </label>
                                            </div>
                                            <input type="hidden" name="old_app_logo" value="{{$data['app_logo']}}">                                       
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer mt-0">
                                <button type="button" class="btn btn-default mw-120" onclick="app_setting()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </form>
                    </div>
                    <div class="form-row">
                        <div class="col-6">
                            <!-- API Configrations -->
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.api_configrations')}}</div>
                                <div class="card-body">
                                    <div class="input-group">
                                        <input type="text" readonly value="{{url('/')}}/api/" name="api_path" class="form-control" id="api_path">
                                        <button type="button" class="btn btn-default ml-2" style="border-radius: 10px;" onclick="Function_Api_path()" title="{{__('label.copy')}}">
                                            <i class="fa-solid fa-copy fa-xl"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- TMDb Api Key -->
                            @if( env('DEMO_MODE') == 'OFF')
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.tmdb_api_key')}}</div>
                                    <form id="save_tmdb_api_key">
                                        <div class="card-body pb-0">
                                            <div class="form-row">
                                                <div class="col-4">
                                                    <div class="form-group">
                                                        <label>{{__('label.tmdb_active')}}<span class="text-danger">*</span></label>
                                                        <div class="radio-group">
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="tmdb_status" id="tmdb_status_yes" class="custom-control-input" value="1" {{$data['tmdb_status'] == 1 ? 'checked' : ''}}>
                                                                <label class="custom-control-label" for="tmdb_status_yes">{{__('label.yes')}}</label>
                                                            </div>
                                                            <div class="custom-control custom-radio">
                                                                <input type="radio" name="tmdb_status" id="tmdb_status_no" class="custom-control-input" value="0" {{$data['tmdb_status'] == 0 ? 'checked' : ''}}>
                                                                <label class="custom-control-label" for="tmdb_status_no">{{__('label.no')}}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-8 tmdb_api_key">
                                                    <label>{{__('label.tmdb_api_key')}}<span class="text-danger">*</span></label>
                                                    <input type="text" name="tmdb_api_key" class="form-control" value="{{$data['tmdb_api_key']}}" placeholder="{{__('label.key_here')}}">
                                                    <label class="mt-1 text-gray">{{__('label.tmdb_notes')}} <a href="https://developer.themoviedb.org/docs/getting-started" target="_blank" class="btn-link">{{__('label.click_here')}}</a></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer mt-1">
                                            <button type="button" class="btn btn-default mw-120" onclick="save_tmdb_api_key()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        </div>
                                    </form>
                                </div>
                            @endif
                            <!-- Purchase Code -->
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.purchase_code')}}</div>
                                <div class="card-body pb-0">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>{{__('label.purchase_code')}}</label>
                                            <input type="text" class="form-control" value="{{env('PURCHASE_CODE')}}" readonly>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label> {{__('label.envato_name')}}</label>
                                            <input type="text" class="form-control" value="{{env('BUYER_USERNAME')}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.commission')}}</div>
                                <form id="commission_setting" enctype="multipart/form-data">
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="form-group col-12">
                                                <label>{{__('label.commission')}}(%)</label>
                                                <input type="number" value="{{ $data['commission'] }}" min="0" max="100" name="commission" class="form-control" placeholder="{{__('label.commission_here')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer mt-0">
                                        <button type="button" class="btn btn-default mw-120" onclick="commission_setting()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </div>
                                </form>
                            </div>
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.vdocipher')}}</div>
                                <form id="save_vdocipher">
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>{{__('label.status')}}</label>
                                                    <div class="radio-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="vdocipher_status" id="vdocipher_status_dev" class="custom-control-input" value="0" {{ $data['vdocipher_status'] == 0 ? 'checked' : ''}}>
                                                            <label class="custom-control-label" for="vdocipher_status_dev">{{__('label.dev')}}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="vdocipher_status" id="vdocipher_status_prod" class="custom-control-input" value="1" {{ $data['vdocipher_status'] == 1 ? 'checked' : ''}}>
                                                            <label class="custom-control-label" for="vdocipher_status_prod">{{__('label.prod')}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label>{{__('label.api_secret_key')}}</label>
                                                <input type="text" name="vdocipher_api_secret_key" class="form-control" value="{{ $data['vdocipher_api_secret_key'] }}" placeholder="{{__('label.key_here')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer mt-0">
                                        <button type="button" class="btn btn-default mw-120" onclick="save_vdocipher()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </div>
                                </form>
                            </div>
                            <!-- Refer & Earn Settings -->
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.refer_and_earn_settings')}}</div>
                                <form id="save_refer_and_earn" enctype="multipart/form-data">
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>{{__('label.refer_and_earn_status')}}</label>
                                                    <div class="radio-group">
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="refer_and_earn_status" id="refer_and_earn_status_enable" class="custom-control-input" value="1" {{ $data['refer_and_earn_status'] == 1 ? 'checked' : ''}}>
                                                            <label class="custom-control-label" for="refer_and_earn_status_enable">{{__('label.enable')}}</label>
                                                        </div>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" name="refer_and_earn_status" id="refer_and_earn_status_disable" class="custom-control-input" value="0" {{ $data['refer_and_earn_status'] == 0 ? 'checked' : ''}}>
                                                            <label class="custom-control-label" for="refer_and_earn_status_disable">{{__('label.disable')}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="refer_earn_fields">
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>{{__('label.parent_user_earn')}}</label>
                                                    <input type="number" name="parent_user_earn" class="form-control" value="{{$data['parent_user_earn']}}" min="0" placeholder="0">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>{{__('label.child_user_earn')}}</label>
                                                    <input type="number" name="child_user_earn" class="form-control" value="{{$data['child_user_earn']}}" min="0" placeholder="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer mt-0">
                                        <button type="button" class="btn btn-default mw-120" onclick="save_refer_and_earn()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-6">
                            <!-- API Token -->
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.api_token')}}</div>
                                <div class="card-body">
                                    <form id="save_key">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        @php
                                            $token = env('DEMO_MODE') == 'ON' ? 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx' : env('API_TOKEN');
                                        @endphp
                                        <div class="input-group">
                                            <input type="text" readonly value="{{ $token }}" name="api_token" class="form-control" id="api_token">
                                            <button type="button" class="btn btn-default ml-2" style="border-radius: 10px;" onclick="Function_Api_token()" title="{{__('label.copy')}}">
                                                <i class="fa-solid fa-copy fa-xl"></i>
                                            </button>
                                            <button type="button" class="btn btn-default ml-2" style="border-radius: 10px;" id="generateBtn" title="{{__('label.reset')}}">
                                                <i class="fa-solid fa-rotate fa-xl"></i>
                                            </button>
                                            <button type="button" class="btn btn-default ml-2" style="border-radius: 10px;" onclick="save_key()">
                                                <i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.update')}}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Currency Settings -->
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.currency_settings')}}</div>
                                <form id="save_currency">
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>{{__('label.currency_name')}}</label>
                                                <input type="text" name="currency" class="form-control" value="{{ $data['currency'] }}" placeholder="{{__('label.currency_name_here')}}">
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label> {{__('label.currency_code')}}</label>
                                                <input type="text" name="currency_code" class="form-control" value="{{ $data['currency_code'] }}" placeholder="{{__('label.currency_code_here')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer mt-0">
                                        <button type="button" class="btn btn-default mw-120" onclick="save_currency()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </div>
                                </form>
                            </div>
                            <!-- App Download Platform -->
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.app_download_platform')}}</div>
                                <form id="save_appdownload">
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label>{{__('label.playstore_url')}}</label>
                                                <input type="text" name="playstore_url" class="form-control" value="{{ $data['playstore_url'] }}" placeholder="{{__('label.playstore_url_here')}}">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label> {{__('label.appstore_url')}}</label>
                                                <input type="text" name="appstore_url" class="form-control" value="{{ $data['appstore_url'] }}" placeholder="{{__('label.appstore_url_here')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer mt-0">
                                        <button type="button" class="btn btn-default mw-120" onclick="save_appdownload()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </div>
                                </form>
                            </div>
                            <!-- Web Client Id -->
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.web_client_id')}}</div>
                                <form id="save_web_client_id">
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <input type="text" name="web_client_id" class="form-control" value="{{ $data['web_client_id'] }}" placeholder="{{__('label.id_here')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer mt-0">
                                        <button type="button" class="btn btn-default mw-120" onclick="save_web_client_id()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </div>
                                </form>
                            </div>
                            <!-- Vapid_key -->
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.vapid_key')}}</div>
                                <form id="save_vapid_key">
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <input type="text" name="vapid_key" class="form-control" value="{{$data['vapid_key']}}" placeholder="{{__('label.key_here')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer mt-0">
                                        <button type="button" class="btn btn-default mw-120" onclick="save_vapid_key()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </div>
                                </form>
                            </div>
                            <!-- Withdrawal Amount -->
                            <div class="card custom-border-card">
                                <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.min_withdrawal_amount')}}</div>
                                <form id="save_min_withdrawal_amount">
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <input type="text" name="min_withdrawal_amount" class="form-control" value="{{$data['min_withdrawal_amount']}}" placeholder="{{__('label.amount_here')}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer mt-0">
                                        <button type="button" class="btn btn-default mw-120" onclick="save_min_withdrawal_amount()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Basic Configrations -->
                <div class="tab-pane fade" id="configrations" role="tabpanel" aria-labelledby="configrations-tab">
                    <form id="save_basic_configrations">
                        <div class="form-row">
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.app_login')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="app_login" id="app_login_yes" class="custom-control-input" value="1" {{ $data['app_login'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="app_login_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="app_login" id="app_login_no" class="custom-control-input" value="0" {{ $data['app_login'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="app_login_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.auto_play_trailer_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="auto_play_trailer" id="auto_play_trailer_yes" class="custom-control-input" value="1" {{ $data['auto_play_trailer'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="auto_play_trailer_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="auto_play_trailer" id="auto_play_trailer_no" class="custom-control-input" value="0" {{ $data['auto_play_trailer'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="auto_play_trailer_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.active_tv_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="active_tv_status" id="active_tv_status_yes" class="custom-control-input" value="1" {{ $data['active_tv_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="active_tv_status_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="active_tv_status" id="active_tv_status_no" class="custom-control-input" value="0" {{ $data['active_tv_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="active_tv_status_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.parent_control_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="parent_control_status" id="parent_control_status_yes" class="custom-control-input" value="1" {{ $data['parent_control_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="parent_control_status_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="parent_control_status" id="parent_control_status_no" class="custom-control-input" value="0" {{ $data['parent_control_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="parent_control_status_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.watchlist_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="watchlist_status" id="watchlist_status_yes" class="custom-control-input" value="1" {{ $data['watchlist_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="watchlist_status_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="watchlist_status" id="watchlist_status_no" class="custom-control-input" value="0" {{ $data['watchlist_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="watchlist_status_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.download_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="download_status" id="download_status_yes" class="custom-control-input" value="1" {{ $data['download_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="download_status_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="download_status" id="download_status_no" class="custom-control-input" value="0" {{ $data['download_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="download_status_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.continue_watching_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="continue_watching_status" id="continue_watching_status_yes" class="custom-control-input" value="1" {{ $data['continue_watching_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="continue_watching_status_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="continue_watching_status" id="continue_watching_status_no" class="custom-control-input" value="0" {{ $data['continue_watching_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="continue_watching_status_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.onboarding_screen_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="on_boarding_screen_status" id="on_boarding_screen_status_yes" class="custom-control-input" value="1" {{ $data['on_boarding_screen_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="on_boarding_screen_status_yes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="on_boarding_screen_status" id="on_boarding_screen_status_no" class="custom-control-input" value="0" {{ $data['on_boarding_screen_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="on_boarding_screen_status_no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.coupon_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="coupon_status" id="coupon_status_yes" class="custom-control-input" value="1" {{ $data['coupon_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="coupon_status_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="coupon_status" id="coupon_status_no" class="custom-control-input" value="0" {{ $data['coupon_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="coupon_status_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.rent_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="rent_status" id="rent_status_yes" class="custom-control-input" value="1" {{ $data['rent_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="rent_status_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="rent_status" id="rent_status_no" class="custom-control-input" value="0" {{ $data['rent_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="rent_status_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.multiple_device_sync')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-6 form-group">
                                                <label>Multiple Device Sync<span class="text-danger">*</span></label>
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="multiple_device_sync" id="multiple_device_sync_yes" class="custom-control-input" value="1" {{ $data['multiple_device_sync'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="multiple_device_sync_yes">Yes</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="multiple_device_sync" id="multiple_device_sync_no" class="custom-control-input" value="0" {{ $data['multiple_device_sync'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="multiple_device_sync_no">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 form-group no_of_device_sync">
                                                <label>Number Of Device Sync<span class="text-danger">*</span></label>
                                                <input type="number" name="no_of_device_sync" value="@if($data && isset($data['no_of_device_sync'])){{$data['no_of_device_sync']}}@endif" min="0" class="form-control" placeholder="Enter Number">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.video_player_ima_ads_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="video_player_ima_ads_status" id="video_player_ima_ads_status_yes" class="custom-control-input" value="1" {{ $data['video_player_ima_ads_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="video_player_ima_ads_status_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="video_player_ima_ads_status" id="video_player_ima_ads_status_no" class="custom-control-input" value="0" {{ $data['video_player_ima_ads_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="video_player_ima_ads_status_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.screen_recording_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="screen_recording_status" id="screen_recording_status_yes" class="custom-control-input" value="1" {{ $data['screen_recording_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="screen_recording_status_yes">{{__('label.enable')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="screen_recording_status" id="screen_recording_status_no" class="custom-control-input" value="0" {{ $data['screen_recording_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="screen_recording_status_no">{{__('label.disable')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.subscription_status')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="subscription_status" id="subscription_status_yes" class="custom-control-input" value="1" {{ $data['subscription_status'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="subscription_status_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="subscription_status" id="subscription_status_no" class="custom-control-input" value="0" {{ $data['subscription_status'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="subscription_status_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <label class="mb-1" style="color: var(--white-color);">{{__('label.subscription_status_notes')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card custom-border-card">
                                    <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.auto_approve_reviews')}}<span class="text-danger">*</span></div>
                                    <div class="card-body pb-0">
                                        <div class="form-row">
                                            <div class="col-12">
                                                <div class="radio-group">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="auto_approve_reviews" id="auto_approve_reviews_yes" class="custom-control-input" value="1" {{ $data['auto_approve_reviews'] == 1 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="auto_approve_reviews_yes">{{__('label.yes')}}</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="auto_approve_reviews" id="auto_approve_reviews_no" class="custom-control-input" value="0" {{ $data['auto_approve_reviews'] == 0 ? 'checked' : ''}}>
                                                        <label class="custom-control-label" for="auto_approve_reviews_no">{{__('label.no')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="border-top pt-3 text-right">
                            <button type="button" class="btn btn-default-white mw-120" onclick="save_basic_configrations()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </div>
                    </form>
                </div>
                <!-- SMTP -->
                <div class="tab-pane fade" id="smtp" role="tabpanel" aria-labelledby="smtp-tab">
                    <div class="card custom-border-card">
                        <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.email_setting_smtp')}}</div>
                        <form id="smtp_setting">
                            <div class="card-body pb-0">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" value="@if($smtp){{$smtp->id}}@endif">
                                <div class="form-row">
                                    <div class="form-group  col-md-3">
                                        <label>{{__('label.is_smtp_active')}}<span class="text-danger">*</span></label>
                                        <select name="status" class="form-control">
                                            <option value="">{{__('label.select_status')}}</option>
                                            <option value="0" @if($smtp){{ $smtp->status == 0  ? 'selected' : ''}}@endif>{{__('label.no')}}</option>
                                            <option value="1" @if($smtp){{ $smtp->status == 1  ? 'selected' : ''}}@endif>{{__('label.yes')}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.host')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="host" class="form-control" value="@if($smtp){{$smtp->host}}@endif" placeholder="{{__('label.host_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.port')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="port" class="form-control" value="@if($smtp){{$smtp->port}}@endif" placeholder="{{__('label.port_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.protocol')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="protocol" class="form-control" value="@if($smtp){{$smtp->protocol}}@endif" placeholder="{{__('label.protocol_here')}}">
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.user_name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="user" class="form-control" value="@if($smtp){{$smtp->user}}@endif" placeholder="{{__('label.user_name_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.password')}}<span class="text-danger">*</span></label>
                                        <input type="password" name="pass" class="form-control" value="@if($smtp){{$smtp->pass}}@endif" placeholder="{{__('label.password_here')}}">
                                        <label class="mt-1 text-gray">{{__('label.search_for_better_result')}} <a href="https://support.google.com/mail/answer/185833?hl=en" target="_blank" class="btn-link">{{__('label.click_here')}}</a></label>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.from_name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="from_name" class="form-control" value="@if($smtp){{$smtp->from_name}}@endif" placeholder="{{__('label.from_name_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.from_email')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="from_email" class="form-control" value="@if($smtp){{$smtp->from_email}}@endif" placeholder="{{__('label.from_email_here')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer mt-0">
                                <button type="button" class="btn btn-default mw-120" onclick="smtp_setting()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Social Links -->
                <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                    <div class="card custom-border-card">
                        <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.social_links')}}</div>
                        <form id="social_link" enctype="multipart/form-data">
                            <div class="card-body pb-0">
                                <input type="hidden" name="step_storage_type[]" value="">
                                <div class="row col-md-12">
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.name')}}</label>
                                        <input type="text" name="name[]" class="form-control" placeholder="{{__('label.name_here')}}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>{{__('label.url')}}</label>
                                        <input type="url" name="url[]" class="form-control" placeholder="{{__('label.url_here')}}">
                                    </div>  
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.icon')}}</label>
                                        <input type="file" name="image[]" class="form-control social_img" id="social_img" accept=".png, .jpg, .jpeg, .webp">
                                        <input type="hidden" name="old_image[]" value="">
                                    </div>
                                    <div class="form-group col-md-1">
                                        <div class="custom-file">
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" style="height: 90px; width: 90px;" id="link_img_social_img">
                                        </div>
                                    </div>
                                    <div class="col-md-1 mt-2">
                                        <div class="flex-grow-1 px-5 d-inline-flex">
                                            <div class="change mr-3 mt-4" id="add_btn" title="{{__('label.add_more')}}">
                                                <a class="btn btn-success add-more text-white" onclick="add_more_link()">+</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @for ($i=0; $i < count($social_link); $i++)
                                    <div class="social_part">
                                        <input type="hidden" name="step_storage_type[]" value="{{ $social_link[$i]['storage_type'] }}">
                                        <div class="row col-lg-12">
                                            <div class="form-group col-md-3">
                                                <label>{{__('label.name')}}</label>
                                                <input type="text" name="name[]" value="{{ $social_link[$i]['name'] }}" class="form-control" placeholder="{{__('label.name_here')}}">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.url')}}</label>
                                                <input type="url" name="url[]" value="{{ $social_link[$i]['url'] }}" class="form-control" placeholder="{{__('label.url_here')}}">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{__('label.icon')}}</label>
                                                <input type="file" name="image[]" class="form-control social_img" id="social_img_{{$i}}" accept=".png, .jpg, .jpeg, .webp">
                                                <input type="hidden" name="old_image[]" value="{{ basename($social_link[$i]['image']) }}">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <div class="custom-file">
                                                    <img src="{{$social_link[$i]['image']}}" style="height: 90px; width: 90px;" id="link_img_social_img_{{$i}}">
                                                </div>
                                            </div>
                                            <div class="col-md-1 mt-2">
                                                <div class="flex-grow-1 px-5 d-inline-flex">
                                                    <div class="change mr-3 mt-4" id="add_btn" title="{{__('label.remove')}}">
                                                        <a class="btn btn-danger text-white remove_link">-</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor               
                                
                                <div class="after-add-more"></div>
                            </div>
                            <div class="card-footer mt-1">
                                <button type="button" class="btn btn-default mw-120" onclick="social_link()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Onboarding Screen -->
                <div class="tab-pane fade" id="onboarding" role="tabpanel" aria-labelledby="onboarding-tab">
                    <div class="card custom-border-card">
                        <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.onboarding_screen')}}</div>
                        <form id="onboarding_form" enctype="multipart/form-data">
                            <div class="card-body pb-0">
                                <input type="hidden" name="screen_storage_type[]" value="">
                                <div class="row col-md-12">
                                    <div class="form-group col-md-4">
                                        <label>{{__('label.title')}}</label>
                                        <input type="text" name="title[]" class="form-control" placeholder="{{__('label.title_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.description')}}</label>
                                        <input type="text" name="description[]" class="form-control" placeholder="{{__('label.description_here')}}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>{{__('label.image')}}</label>
                                        <input type="file" name="image[]" class="form-control on_boarding_img" id="on_boarding_img" accept=".png, .jpg, .jpeg, .webp">
                                        <input type="hidden" name="old_image[]" value="">
                                    </div>
                                    <div class="form-group col-md-1">
                                        <div class="custom-file">
                                            <img src="{{asset('assets/imgs/upload_img.png')}}" style="height: 90px; width: 90px;" id="link_img_on_boarding_img">
                                        </div>
                                    </div>
                                    <div class="col-md-1 mt-2">
                                        <div class="flex-grow-1 px-5 d-inline-flex">
                                            <div class="change mr-3 mt-4" id="add_btn" title="{{__('label.add_more')}}">
                                                <a class="btn btn-success add-more text-white" onclick="add_more_screen()">+</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @for ($i=0; $i < count($onboarding_screen); $i++)
                                    <div class="onboarding_part">
                                        <input type="hidden" name="screen_storage_type[]" value="{{ $onboarding_screen[$i]['storage_type'] }}">
                                        <div class="row col-lg-12">
                                            <div class="form-group col-md-4">
                                                <label>{{__('label.title')}}</label>
                                                <input type="text" name="title[]" value="{{ $onboarding_screen[$i]['title'] }}" class="form-control" placeholder="{{__('label.title_here')}}">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{__('label.description')}}</label>
                                                <input type="text" name="description[]" value="{{ $onboarding_screen[$i]['description'] }}" class="form-control" placeholder="{{__('label.description_here')}}">
                                            </div>
                                            <div class="form-group col-md-3">
                                                <label>{{__('label.image')}}</label>
                                                <input type="file" name="image[]" class="form-control on_boarding_img" id="on_boarding_img{{$i}}" accept=".png, .jpg, .jpeg, .webp">
                                                <input type="hidden" name="old_image[]" value="{{ basename($onboarding_screen[$i]['image']) }}">
                                            </div>
                                            <div class="form-group col-md-1">
                                                <div class="custom-file">
                                                    <img src="{{$onboarding_screen[$i]['image']}}" style="height: 90px; width: 90px;" id="link_img_on_boarding_img{{$i}}">
                                                </div>
                                            </div>
                                            <div class="col-md-1 mt-2">
                                                <div class="flex-grow-1 px-5 d-inline-flex">
                                                    <div class="change mr-3 mt-4" id="add_btn" title="{{__('label.remove')}}">
                                                        <a class="btn btn-danger text-white remove_on_boarding">-</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor

                                <div class="after-add-more-on-boarding"></div>
                            </div>
                            <div class="card-footer mt-1">
                                <button type="button" class="btn btn-default mw-120" onclick="onboarding()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Storage Setting -->
                <div class="tab-pane fade" id="awss3" role="tabpanel" aria-labelledby="awss3-tab">
                    <form id="storage_setting">
                        <div class="card custom-border-card">
                            <div class="card-header"><i class="fa-solid fa-cloud mr-2"></i>{{__('label.storage')}}</div>
                            <div class="card-body pb-0">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.storage_type')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="storage_type" id="storage_type">
                                                <option value="1" @if($storage){{ $storage['storage_type'] == "1" ? 'selected' : ''}}@endif>{{__('label.local_storage')}}</option>
                                                <option value="2" @if($storage){{ $storage['storage_type'] == "2" ? 'selected' : ''}}@endif>{{__('label.aws_s3_storage')}}</option>
                                                <option value="3" @if($storage){{ $storage['storage_type'] == "3" ? 'selected' : ''}}@endif>{{__('label.wasabi_storage')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
   
                        <!-- AWS S3 -->
                        <div class="card custom-border-card s3-card mt-3">
                            <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.aws_s3_storage_credentials')}}</div>
                            <div class="card-body pb-0">
                                <div class="form-row">
                                    <div class="col-md-4 access_key">
                                        <div class="form-group">
                                            <label>{{__('label.access_key')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="s3_access_key" value="@if($storage){{$storage['s3_access_key']}}@endif"  class="form-control" placeholder="{{__('label.key_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 secret_key">
                                        <div class="form-group">
                                            <label>{{__('label.secret_key')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="s3_secret_key" value="@if($storage){{$storage['s3_secret_key']}}@endif"  class="form-control" placeholder="{{__('label.key_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 bucket_name">
                                        <div class="form-group">
                                            <label>{{__('label.bucket_name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="s3_bucket_name" value="@if($storage){{$storage['s3_bucket_name']}}@endif"  class="form-control" placeholder="{{__('label.bucket_name_here')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 region">
                                        <div class="form-group">
                                            <label>{{__('label.region')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="s3_region" value="@if($storage){{$storage['s3_region']}}@endif"  class="form-control" placeholder="{{__('label.region_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 endpoint">
                                        <div class="form-group">
                                            <label>{{__('label.endpoint')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="s3_endpoint" value="@if($storage){{$storage['s3_endpoint']}}@endif"  class="form-control" placeholder="{{__('label.endpoint_here')}}">
                                            <label class="mt-1 text-gray">{{__('label.aws_endpint_demo')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Wasabi -->
                        <div class="card custom-border-card wasabi-card mt-3">
                            <div class="card-header"><i class="fa-solid fa-gear"></i>{{__('label.wasabi_storage_credentials')}}</div>
                            <div class="card-body pb-0">
                                <div class="form-row">
                                    <div class="col-md-4 access_key">
                                        <div class="form-group">
                                            <label>{{__('label.access_key')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="wasabi_access_key" value="@if($storage){{$storage['wasabi_access_key']}}@endif"  class="form-control" placeholder="{{__('label.key_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 secret_key">
                                        <div class="form-group">
                                            <label>{{__('label.secret_key')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="wasabi_secret_key" value="@if($storage){{$storage['wasabi_secret_key']}}@endif"  class="form-control" placeholder="{{__('label.key_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 bucket_name">
                                        <div class="form-group">
                                            <label>{{__('label.bucket_name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="wasabi_bucket_name" value="@if($storage){{$storage['wasabi_bucket_name']}}@endif"  class="form-control" placeholder="{{__('label.bucket_name_here')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-4 region">
                                        <div class="form-group">
                                            <label>{{__('label.region')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="wasabi_region" value="@if($storage){{$storage['wasabi_region']}}@endif"  class="form-control" placeholder="{{__('label.region_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 endpoint">
                                        <div class="form-group">
                                            <label>{{__('label.endpoint')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="wasabi_endpoint" value="@if($storage){{$storage['wasabi_endpoint']}}@endif"  class="form-control" placeholder="{{__('label.endpoint_here')}}">
                                            <label class="mt-1 text-gray">{{__('label.wasabi_endpint_demo')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2 text-right">
                            <button type="button" class="btn btn-default-white mw-120" onclick="storage_setting()"><i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        // API Key
        function Function_Api_path() {
            /* Get the text field */
            var copyText = document.getElementById("api_path");

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            document.execCommand('copy');

            /* Alert the copied text */
            alert("Copied the API Path: " + copyText.value);
        }

        $(document).ready(function() {
            var tmdb_status = "<?php echo $data['tmdb_status']; ?>";
            if(tmdb_status == 1){
                $(".tmdb_api_key").show();
            } else {
                $(".tmdb_api_key").hide();
            }
            $('input[type=radio][name=tmdb_status]').change(function() {
                if (this.value == 1) {
                    $(".tmdb_api_key").show();
                }
                else if (this.value == 0) {
                    $(".tmdb_api_key").hide();
                }
            });

            var multiple_device_sync = "<?php echo $data['multiple_device_sync']; ?>";
            if(multiple_device_sync == 1){
                $(".no_of_device_sync").show();
            } else {
                $(".no_of_device_sync").hide();
            }
            $('input[type=radio][name=multiple_device_sync]').change(function() {
                if (this.value == 1) {
                    $(".no_of_device_sync").show();
                }
                else if (this.value == 0) {
                    $(".no_of_device_sync").hide();
                }
            });

            var refer_and_earn_status = "<?php echo $data['refer_and_earn_status']; ?>";
            if (refer_and_earn_status == 1) {
                $(".refer_earn_fields").show();
            } else {
                $(".refer_earn_fields").hide();
            }
            $('input[type=radio][name=refer_and_earn_status]').change(function() {
                if (this.value == 1) {
                    $(".refer_earn_fields").show();
                } else {
                    $(".refer_earn_fields").hide();
                }
            });

            $(".s3-card").hide();
            $(".wasabi-card").hide();
            var storage_type = "<?php echo isset($storage['storage_type']) ? $storage['storage_type'] : 0; ?>";
            if (storage_type == 1) {
                $(".s3-card").hide();
                $(".wasabi-card").hide();
            } else if (storage_type == 2) {
                $(".s3-card").show();
            } else if (storage_type == 3) {
                $(".wasabi-card").show();
            }

            $('#storage_type').change(function() {
                var optionValue = $(this).val();
                if (optionValue == 1) {
                    $(".s3-card").hide();
                    $(".wasabi-card").hide();
                } else if (optionValue == 2) {
                    $(".s3-card").show();
                    $(".wasabi-card").hide();
                } else if (optionValue == 3) {
                    $(".s3-card").hide();
                    $(".wasabi-card").show();
                }
            });
        });

        // App Setting
        function app_setting() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#app_setting")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.app") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'app_setting', '{{ route("admin.appsetting") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        // TMDb API Key
        function save_tmdb_api_key() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_tmdb_api_key")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.tmdbkey") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({ scrollTop: 0 }, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        function commission_setting() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#commission_setting")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.commission") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({ scrollTop: 0 }, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        function save_min_withdrawal_amount() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#save_min_withdrawal_amount")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.withdrawal") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({ scrollTop: 0 }, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        function save_vdocipher() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#save_vdocipher")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.vdocipher") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({ scrollTop: 0 }, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // Currency Setting
        function save_currency() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#save_currency")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.currency") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({scrollTop: 0}, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        // AWS S3 storage
        function storage_setting() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#storage_setting")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.storage.save") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'storage_setting', '{{ route("admin.appsetting") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        // Basic Configrations
        function save_basic_configrations() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_basic_configrations")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.basicconfigrations") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({ scrollTop: 0 }, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        // SMTP
        function smtp_setting() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#smtp_setting")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.smtp") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({scrollTop: 0}, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // Multipal Img Show 
        $(document).on('change', '.social_img', function(){
            readURL(this, this.id);
        });
        $(document).on('change', '.on_boarding_img', function(){
            readURL(this, this.id);
        });
        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                 
                reader.onload = function (e) {
                    $('#link_img_'+id).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Social Link Add-Remove Link Part
        var i = -1;
        function add_more_link(){

            var data = '<div class="social_part">';
                data +='<input type="hidden" name="step_storage_type[]" value="">';
                data += '<div class="row col-md-12">';
                data += '<div class="form-group col-md-3">';
                data += '<label>{{__("label.name")}}</label>';
                data += '<input type="text" name="name[]" class="form-control" placeholder="{{__("label.name_here")}}">';
                data += '</div>';
                data += '<div class="form-group col-md-4">';
                data += '<label>{{__("label.url")}}</label>';
                data += '<input type="url" name="url[]" class="form-control" placeholder="{{__("label.url_here")}}">';
                data += '</div>';
                data += '<div class="form-group col-lg-3">';
                data += '<label>{{__("label.icon")}}</label>';
                data += '<input type="file" name="image[]" class="form-control social_img" id="social_img_'+i+'" accept=".png, .jpg, .jpeg, .webp">';
                data += '<input type="hidden" name="old_image[]" value="">';
                data += '</div>';
                data += '<div class="form-group col-md-1">';
                data += '<div class="custom-file">';
                data += '<img src="{{asset("assets/imgs/upload_img.png")}}" style="height: 90px; width: 90px;" id="link_img_social_img_'+i+'">';
                data += '</div>';
                data += '</div>';
                data += '<div class="col-md-1 mt-2">';
                data += '<div class="flex-grow-1 px-5 d-inline-flex">';
                data += '<div class="change mr-3 mt-4" id="add_btn" title="{{__("label.remove")}}">';
                data += '<a class="btn btn-danger add-more text-white remove_link">-</a>';
                data += '</div>';
                data += '</div>';
                data += '</div>';
                data += '</div>';
                data += '</div>';

            $('.after-add-more').append(data);
            i--;
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        }
        $("body").on("click", ".remove_link", function(e) {
            $(this).parents('.social_part').remove();
        });
        // Social Link Save
        function social_link() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#social_link")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.sociallink") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'app_setting', '{{ route("admin.appsetting") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // OnBoarding Screen Add-Remove Link Part
        var i = -1;
        function add_more_screen(){

            var data = '<div class="onboarding_part">';
                data += '<input type="hidden" name="screen_storage_type[]" value="">';
                data += '<div class="row col-md-12">';
                data += '<div class="form-group col-md-4">';
                data += '<label>{{__("label.title")}}</label>';
                data += '<input type="text" name="title[]" class="form-control" placeholder="{{__("label.title_here")}}">';
                data += '</div>';
                data += '<div class="form-group col-md-3">';
                data += '<label>{{__("label.description")}}</label>';
                data += '<input type="text" name="description[]" class="form-control" placeholder="{{__("label.description_here")}}">';
                data += '</div>';
                data += '<div class="form-group col-lg-3">';
                data += '<label>{{__("label.image")}}</label>';
                data += '<input type="file" name="image[]" class="form-control on_boarding_img" id="on_boarding_img_'+i+'" accept=".png, .jpg, .jpeg, .webp">';
                data += '<input type="hidden" name="old_image[]" value="">';
                data += '</div>';
                data += '<div class="form-group col-md-1">';
                data += '<div class="custom-file">';
                data += '<img src="{{asset("assets/imgs/upload_img.png")}}" style="height: 90px; width: 90px;" id="link_img_on_boarding_img_'+i+'">';
                data += '</div>';
                data += '</div>';
                data += '<div class="col-md-1 mt-2">';
                data += '<div class="flex-grow-1 px-5 d-inline-flex">';
                data += '<div class="change mr-3 mt-4" id="add_btn" title="{{__("label.remove")}}">';
                data += '<a class="btn btn-danger add-more text-white remove_on_boarding">-</a>';
                data += '</div>';
                data += '</div>';
                data += '</div>';
                data += '</div>';
                data += '</div>';

            $('.after-add-more-on-boarding').append(data);
            i--;
            $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        }
        $("body").on("click", ".remove_on_boarding", function(e) {
            $(this).parents('.onboarding_part').remove();
        });
        // OnBoarding Screen Save
        function onboarding() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#onboarding_form")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.onboardingscreen") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'onboarding_form', '{{ route("admin.appsetting") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // Vapid Key
        function save_vapid_key() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#save_vapid_key")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.vapidkey") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({scrollTop: 0}, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        // Web Client Id
        function save_web_client_id() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#save_web_client_id")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.webclientid") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({scrollTop: 0}, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // API Token
        function Function_Api_token() {
            /* Get the text field */
            var copyText = document.getElementById("api_token");

            /* Select the text field */
            copyText.select();
            copyText.setSelectionRange(0, 99999); /* For mobile devices */

            document.execCommand('copy');

            /* Alert the copied text */
            alert("Copied the API Token: " + copyText.value);
        }
        $('#generateBtn').click(function() {
            const icon = $(this).find('i');
            const length = 32;
            const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

            // Generate secure random token
            const randomValues = crypto.getRandomValues(new Uint32Array(length));
            let token = '';
            for (let i = 0; i < length; i++) {
                token += charset[randomValues[i] % charset.length];
            }

            // Set token value in input
            $('#api_token').val(token);

            // Rotate icon on each click
            icon.removeClass('rotate');
            void icon[0].offsetWidth; // Forces reflow so animation restarts
            icon.addClass('rotate');
        });
        function save_key() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_key")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.apitoken") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_key', '{{ route("admin.appsetting") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // App Download
        function save_appdownload() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#save_appdownload")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.appdownload") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({scrollTop: 0}, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        // Refer & Earn Setting
        function save_refer_and_earn() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#save_refer_and_earn")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.appsetting.referandearn") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $("html, body").animate({ scrollTop: 0 }, "swing");
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection