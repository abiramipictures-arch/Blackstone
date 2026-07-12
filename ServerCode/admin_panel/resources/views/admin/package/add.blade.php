@extends('admin.layout.page-app')
@section('page_title', __('label.add_package'))
@section('tab_title', __('label.add_package'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.add_package')}}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.package.index') }}">{{__('label.package')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.add_package')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.package.index') }}" class="btn btn-default-white mw-120">{{__('label.package_list')}}</a>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-box-open mr-2"></i>{{__('label.add_package')}}</div>
                <div class="card-body">
                    <form enctype="multipart/form-data" id="save_package">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                    <input name="name" type="text" class="form-control" placeholder="{{__('label.name_here')}}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.package_type')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="package_type" id="package_type_paid" class="custom-control-input" value="1" checked>
                                            <label class="custom-control-label" for="package_type_paid">{{__('label.paid')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="package_type" id="package_type_free" class="custom-control-input" value="2">
                                            <label class="custom-control-label" for="package_type_free">{{__('label.free')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 price_group">
                                <div class="form-group">
                                    <label>{{__('label.price')}}<span class="text-danger">*</span></label>
                                    <input name="price" type="number" class="form-control" placeholder="{{__('label.price_here')}}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.no_of_device_sync')}}<span class="text-danger">*</span></label>
                                    <input name="no_of_device_sync" type="number" value="1" min="1" class="form-control" placeholder="{{__('label.no_of_device_sync_here')}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.package_time')}}<span class="text-danger">*</span></label>
                                    <select class="form-control" id="validity_type" name="type">
                                        <option value="">{{__('label.select_type')}}</option>
                                        <option value="Day">{{__('label.day')}}</option>
                                        <option value="Week">{{__('label.week')}}</option>
                                        <option value="Month">{{__('label.month')}}</option>
                                        <option value="Year">{{__('label.year')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mt-3 package_time">
                                <div class="form-group">
                                    <select class="form-control mt-2" name="time">
                                        <option value="">{{__('label.select_number')}}</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.watch_on_tv')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="watch_on_laptop_tv" id="watch_on_laptop_tv_yes" class="custom-control-input" value="1" checked>
                                            <label class="custom-control-label" for="watch_on_laptop_tv_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="watch_on_laptop_tv" id="watch_on_laptop_tv_no" class="custom-control-input" value="0">
                                            <label class="custom-control-label" for="watch_on_laptop_tv_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.ads_free_content')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="ads_free_content" id="ads_free_content_yes" class="custom-control-input" value="1" checked>
                                            <label class="custom-control-label" for="ads_free_content_yes">{{__('label.yes')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="ads_free_content" id="ads_free_content_no" class="custom-control-input" value="0">
                                            <label class="custom-control-label" for="ads_free_content_no">{{__('label.no')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label>{{__('label.android_product_package')}}</label>
                                    <input name="android_product_package" type="text" class="form-control" placeholder="{{__('label.android_package_here')}}">
                                </div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label>{{__('label.ios_product_package')}}</label>
                                    <input name="ios_product_package" type="text" class="form-control" placeholder="{{__('label.ios_package_here')}}">
                                </div>
                            </div>
                            <div class="col-md-3 mt-3">
                                <div class="form-group">
                                    <label>{{__('label.web_product_package')}}</label>
                                    <input name="web_product_package" type="text" class="form-control" placeholder="{{__('label.web_package_here')}}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-default mw-120" onclick="save_package()">
                        <i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}
                    </button>
                    <a href="{{route('admin.package.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $(document).ready(function() {
            $('.package_time').hide();

            $('input[name="package_type"]').on('change', function() {
                $(this).val() == 1 ? $('.price_group').show() : $('.price_group').hide();
            });

            $('#validity_type').on('click', function() {
                $('.package_time').show();
                var type = $(this).val();

                for (let i = 1; i <= 31; i++) {
                    $(".package_time option[value=" + i + "]").show().attr("selected", false);
                }

                if (type == "Day") {
                    for (let i = 8; i <= 31; i++) { $(".package_time option[value=" + i + "]").hide(); }
                } else if (type == "Week") {
                    for (let i = 5; i <= 31; i++) { $(".package_time option[value=" + i + "]").hide(); }
                } else if (type == "Month") {
                    for (let i = 13; i <= 31; i++) { $(".package_time option[value=" + i + "]").hide(); }
                } else if (type == "Year") {
                    for (let i = 2; i <= 31; i++) { $(".package_time option[value=" + i + "]").hide(); }
                } else {
                    $('.package_time').hide();
                }
            });
        });

        function save_package() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_package")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.package.store") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_package', '{{ route("admin.package.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
