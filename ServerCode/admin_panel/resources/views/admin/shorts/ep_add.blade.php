@extends('admin.layout.page-app')
@section('page_title', __('label.add_episode'))
@section('tab_title', __('label.add_episode'))

@section('content')
    @include('admin.layout.sidebar')

    <!-- Date Time Picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.add_episode')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.shorts.index', ['type_id' => $type['id']]) }}">{{$type['name']}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.shorts.episode.index', ['id' => $shorts_id, 'type_id' => $type['id']]) }}">{{__('label.episodes')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.add_episode')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.shorts.episode.index',['id'=> $shorts_id, 'type_id' => $type['id']]) }}" class="btn btn-default-white mw-120">{{__('label.episode_list')}}</a>
                </div>
            </div>

            <form id="save_short_episode" enctype="multipart/form-data">
                <input type="hidden" name="show_id" value="{{ $shorts_id }}">

                <!-- Title Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-film mr-2"></i>{{__('label.add_episode')}}</div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                    <input name="name" type="text" class="form-control" placeholder="{{__('label.name_here')}}" autofocus>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.season')}}<span class="text-danger">*</span></label>
                                    <select class="form-control" name="season_id">
                                        <option value="">{{__('label.select_season')}}</option>
                                        @foreach ($season as $key => $value)
                                            <option value="{{ $value->id }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video File Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-video mr-2"></i>{{__('label.video_file')}}</div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.video_upload_type')}}<span class="text-danger">*</span></label>
                                    <select name="video_upload_type" id="video_upload_type" class="form-control">
                                        <option value="server_video">{{__('label.server_video')}}</option>
                                        <option value="external">{{__('label.external_url')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{__('label.is_premium')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_premium" id="is_premium_no" class="custom-control-input" value="0" checked>
                                            <label class="custom-control-label" for="is_premium_no">{{__('label.no')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_premium" id="is_premium_yes" class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="is_premium_yes">{{__('label.yes')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{__('label.is_title')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_title" id="is_title_no" class="custom-control-input" value="0" checked>
                                            <label class="custom-control-label" for="is_title_no">{{__('label.no')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_title" id="is_title_yes" class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="is_title_yes">{{__('label.yes')}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.video_duration')}}</label>
                                    <input type="text" id="timePicker" name="video_duration" placeholder="{{__('label.video_duration')}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-6 video_box">
                                <div style="display: block;">
                                    <label>{{__('label.upload_video')}}<span class="text-danger">*</span></label>
                                    <div id="filelist"></div>
                                    <div id="container" style="position: relative;">
                                        <div class="form-group d-flex align-items-center">
                                            <input type="file" id="uploadFile" name="uploadFile" style="position: relative; z-index: 1;" class="form-control mr-4">
                                            <a id="upload" class="btn upload-btn py-2">{{__('label.upload_files')}}</a>
                                        </div>
                                        <input type="hidden" name="video" id="mp3_file_name" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 s3_video_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_video')}}<span class="text-danger">*</span></label>
                                    <input type="file" name="video" class="form-control" accept=".mp4">
                                </div>
                            </div>
                            <div class="form-group col-lg-6 url_box_main">
                                <label>{{__('label.url')}}<span class="text-danger">*</span></label>
                                <input name="video_url" type="url" class="form-control" placeholder="{{__('label.enter_video_url')}}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-photo-film mr-2"></i>{{__('label.media')}}</div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-6">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.description')}}</label>
                                            <textarea name="description" class="form-control" rows="5" id="description" placeholder="{{__('label.description_here')}}"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group ml-5">
                                    <label>{{__('label.thumbnail_image')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type='file' name="thumbnail" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                        <label class="avatar-preview">
                                            <img src="{{ asset('assets/imgs/upload_img.png') }}" class="imagePreview" id="imagePreviewThumbnail"/>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="save_short_episode()">
                            <i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}
                        </button>
                        <a href="{{route('admin.shorts.episode.index', ['id'=> $shorts_id, 'type_id' => $type['id']])}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Data Time Picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Chunk JS -->
    <script src="{{ asset('/assets/js/plupload.full.min.js')}}"></script>
    <script src="{{ asset('/assets/js/common.js')}}"></script>
    <script>
        var d = new Date();
        d.setHours(0,0,0);
        $('#timePicker').datetimepicker({
            useCurrent: false,
            format:'HH:mm:ss',
            defaultDate: d,
            showClose:true,
            showTodayButton: true,
            icons: {
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                today: "fa fa-clock fa-regular",
                close: "fa fa-times",
            }
        });

        function save_short_episode() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#save_short_episode")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.shorts.episode.save") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_short_episode', '{{ route("admin.shorts.episode.index",["id"=> $shorts_id, "type_id" => $type["id"]]) }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        $(document).ready(function() {
            var storage_type = "<?php echo Storage_Type(); ?>";
            if (storage_type == 1) {
                $(".s3_video_box").hide();
            } else {
                $(".video_box").hide();
            }

            $(".url_box_main").hide();
            $('#video_upload_type').change(function() {
                var optionValue = $(this).val();

                if (optionValue == 'server_video') {
                    if (storage_type == 1) {
                        $(".video_box").show();
                        $(".s3_video_box").hide();
                    } else {
                        $(".video_box").hide();
                        $(".s3_video_box").show();
                    }
                    $(".url_box_main").hide();
                } else {
                    $(".url_box_main").show();
                    $(".video_box").hide();
                    $(".s3_video_box").hide();
                }
            });
        });
    </script>
@endsection
