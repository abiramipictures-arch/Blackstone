@extends('admin.layout.page-app')
@section('page_title', __('label.admob_settings'))
@section('tab_title', __('label.admob_settings'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.admob_settings')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.admob_settings')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Admob Status -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-mobile-screen"></i>{{__('label.admob_ads_status')}}</div>
                <div class="card-body pb-0">
                    <form id="save_admob_status" enctype="multipart/form-data">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.status')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="admob_status1" name="admob_status" class="custom-control-input" {{ ($result['admob_status'] ==  1) ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="admob_status1">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="admob_status0" name="admob_status" class="custom-control-input" {{ ($result['admob_status'] == 0) ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="admob_status0">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer mt-0">
                    <button type="button" class="btn btn-default mw-120" onclick="save_admob_status()">
                        <i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}
                    </button>
                </div>
            </div>
            <!-- Android -->
            <div class="card custom-border-card mb-3">
                <div class="card-header"><i class="fa-brands fa-android"></i>{{__('label.admob_android_settings')}}</div>
                <div class="card-body pb-0">
                    <form id="admob_android">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="banner_ad">{{__('label.banner_ad')}}</label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="banner_ad" name="banner_ad" class="custom-control-input" {{ $result['banner_ad'] == '1' ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="banner_ad">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="banner_ad1" name="banner_ad" class="custom-control-input" {{ $result['banner_ad'] == '0' ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="banner_ad1">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="interstital_ad">{{__('label.interstital_ad')}}</label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="interstital_ad" name="interstital_ad" class="custom-control-input" {{ $result['interstital_ad'] == '1' ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="interstital_ad">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="interstital_ad1" name="interstital_ad" class="custom-control-input" {{ $result['interstital_ad'] == '0' ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="interstital_ad1">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="reward_ad">{{__('label.reward_ad')}}</label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="reward_ad" name="reward_ad" class="custom-control-input" {{ $result['reward_ad'] == '1' ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="reward_ad">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="reward_ad1" name="reward_ad" class="custom-control-input" {{ $result['reward_ad'] == '0' ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="reward_ad1">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.banner_ad_id')}}</label>
                                    <input type="text" name="banner_adid" class="form-control" placeholder="{{__('label.id_here')}}" value="{{$result['banner_adid']}}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.interstital_ad_id')}}</label>
                                    <input type="text" name="interstital_adid" class="form-control" placeholder="{{__('label.id_here')}}" value="{{$result['interstital_adid']}}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.reward_ad_id')}}</label>
                                    <input type="text" name="reward_adid" class="form-control" placeholder="{{__('label.id_here')}}" value="{{$result['reward_adid']}}">
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label></label>
                                    &nbsp;
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.interstital_ad_click')}}</label>
                                    <input type="text" name="interstital_adclick" class="form-control" placeholder="{{__('label.click_here')}}" value="{{$result['interstital_adclick']}}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.reward_ad_click')}}</label>
                                    <input type="text" name="reward_adclick" class="form-control" placeholder="{{__('label.click_here')}}" value="{{$result['reward_adclick']}}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer mt-0">
                    <button type="button" class="btn btn-default mw-120" onclick="admob_android()">
                        <i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}
                    </button>
                </div>
            </div>
            <!-- IOS -->
            <div class="card custom-border-card mb-3">
                <div class="card-header"><i class="fa-brands fa-apple"></i>{{__('label.admob_ios_settings')}}</div>
                <div class="card-body pb-0">
                    <form id="admob_ios">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="ios_banner_ad">{{__('label.banner_ad')}}</label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="ios_banner_ad" name="ios_banner_ad" class="custom-control-input" {{ $result['ios_banner_ad'] == '1' ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="ios_banner_ad">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="ios_banner_ad1" name="ios_banner_ad" class="custom-control-input" {{ $result['ios_banner_ad'] == '0' ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="ios_banner_ad1">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="ios_interstital_ad">{{__('label.interstital_ad')}}</label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="ios_interstital_ad" name="ios_interstital_ad" class="custom-control-input" {{ $result['ios_interstital_ad'] == '1' ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="ios_interstital_ad">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="ios_interstital_ad1" name="ios_interstital_ad" class="custom-control-input" {{ $result['ios_interstital_ad'] == '0' ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="ios_interstital_ad1">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label for="ios_reward_ad">{{__('label.reward_ad')}}</label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="ios_reward_ad" name="ios_reward_ad" class="custom-control-input" {{ $result['ios_reward_ad'] == '1' ? "checked" : "" }} value="1">
                                            <label class="custom-control-label" for="ios_reward_ad">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="ios_reward_ad1" name="ios_reward_ad" class="custom-control-input" {{ $result['ios_reward_ad'] == '0' ? "checked" : "" }} value="0">
                                            <label class="custom-control-label" for="ios_reward_ad1">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.banner_ad_id')}}</label>
                                    <input type="text" name="ios_banner_adid" class="form-control" placeholder="{{__('label.id_here')}}" value="{{$result['ios_banner_adid']}}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.interstital_ad_id')}}</label>
                                    <input type="text" name="ios_interstital_adid" class="form-control" placeholder="{{__('label.id_here')}}" value="{{$result['ios_interstital_adid']}}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.reward_ad_id')}}</label>
                                    <input type="text" name="ios_reward_adid" class="form-control" placeholder="{{__('label.id_here')}}" value="{{$result['ios_reward_adid']}}">
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label></label>
                                    &nbsp;
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.interstital_ad_click')}}</label>
                                    <input type="text" name="ios_interstital_adclick" class="form-control" placeholder="{{__('label.click_here')}}" value="{{$result['ios_interstital_adclick']}}">
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.reward_ad_click')}}</label>
                                    <input type="text" name="ios_reward_adclick" class="form-control" placeholder="{{__('label.click_here')}}" value="{{$result['ios_reward_adclick']}}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer mt-0">
                    <button type="button" class="btn btn-default mw-120" onclick="admob_ios()">
                        <i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function save_admob_status() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_admob_status")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.admob.status") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_admob_status', '{{ route("admin.admob.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        function admob_android() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#admob_android")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.admob.android") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        function admob_ios() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#admob_ios")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.admob.ios") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
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