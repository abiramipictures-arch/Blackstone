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
                        <li class="breadcrumb-item"><a href="{{ route('admin.tvshow.index', ['type_id' => $type['id']]) }}">{{$type['name']}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tvshow.episode.index', ['id' => $tvshow_id, 'type_id' => $type['id']]) }}">{{__('label.episodes')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.add_episode')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.tvshow.episode.index',['id'=> $tvshow_id, 'type_id' => $type['id']]) }}" class="btn btn-default-white mw-120">{{__('label.episode_list')}}</a>
                </div>
            </div>

            <form id="save_tvshow_video" enctype="multipart/form-data">
                <input type="hidden" name="show_id" id="show_id" value="{{$tvshow_id}}">
                <!-- Title-Card -->
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
                                            <option value="{{$value->id}}">{{$value->name}}</option>
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
                                        <option value="youtube">{{__('label.youtube')}}</option>
                                        <option value="vdocipher_id">{{__('label.vdocipher_id')}}</option>
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
                            <div class="col-md-2 Is_Download">
                                <div class="form-group">
                                    <label>{{__('label.is_download')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_download" id="is_download_no" class="custom-control-input" value="0" checked>
                                            <label class="custom-control-label" for="is_download_no">{{__('label.no')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_download" id="is_download_yes" class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="is_download_yes">{{__('label.yes')}}</label>
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
                            <div class="col-lg-3 video_box">
                                <div class="form-group ">
                                    <div style="display: block;">
                                        <label>{{__('label.upload_video_320_px')}}<span class="text-danger">*</span></label>
                                        <div id="filelist"></div>
                                        <div id="container" style="position: relative;">
                                            <div class="form-group">
                                                <input type="file" id="uploadFile" name="uploadFile" style="position: relative; z-index: 1;" class="form-control">
                                            </div>
                                            <input type="hidden" name="video_320" id="mp3_file_name" class="form-control">
                                            <div class="form-group">
                                                <a id="upload" class="btn upload-btn">{{__('label.upload_files')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-3 video_box">
                                <div style="display: block;">
                                    <label>{{__('label.upload_video_480_px')}}</label>
                                    <div id="filelist1"></div>
                                    <div id="container1" style="position: relative;">
                                        <div class="form-group">
                                            <input type="file" id="uploadFile1" name="uploadFile1" style="position: relative; z-index: 1;" class="form-control">
                                        </div>
                                        <input type="hidden" name="video_480" id="mp3_file_name1" class="form-control">
                                        <div class="form-group">
                                            <a id="upload1" class="btn upload-btn">{{__('label.upload_files')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-3 video_box">
                                <div style="display: block;">
                                    <label>{{__('label.upload_video_720_px')}}</label>
                                    <div id="filelist2"></div>
                                    <div id="container2" style="position: relative;">
                                        <div class="form-group">
                                            <input type="file" id="uploadFile2" name="uploadFile2" style="position: relative; z-index: 1;" class="form-control">
                                        </div>
                                        <input type="hidden" name="video_720" id="mp3_file_name2" class="form-control">
                                        <div class="form-group">
                                            <a id="upload2" class="btn upload-btn">{{__('label.upload_files')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-3 video_box">
                                <div style="display: block;">
                                    <label>{{__('label.upload_video_1080_px')}}</label>
                                    <div id="filelist3"></div>
                                    <div id="container3" style="position: relative;">
                                        <div class="form-group">
                                            <input type="file" id="uploadFile3" name="uploadFile3" style="position: relative; z-index: 1;" class="form-control">
                                        </div>
                                        <input type="hidden" name="video_1080" id="mp3_file_name3" class="form-control">
                                        <div class="form-group">
                                            <a id="upload3" class="btn upload-btn">{{__('label.upload_files')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 s3_video_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_video_320_px')}}<span class="text-danger">*</span></label>
                                    <input type="file" name="video_320" class="form-control"  accept=".mp4">
                                </div>
                            </div>
                            <div class="col-lg-3 s3_video_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_video_480_px')}}</label>
                                    <input type="file" name="video_480" class="form-control"  accept=".mp4">
                                </div>
                            </div>
                            <div class="col-lg-3 s3_video_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_video_720_px')}}</label>
                                    <input type="file" name="video_720" class="form-control"  accept=".mp4">
                                </div>
                            </div>
                            <div class="col-lg-3 s3_video_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_video_1080_px')}}</label>
                                    <input type="file" name="video_1080" class="form-control"  accept=".mp4">
                                </div>
                            </div>
                            <div class="form-group col-lg-6 url_box_main">
                                <label>{{__('label.url_320_px')}}<span class="text-danger">*</span></label>
                                <input name="video_url_320" type="url" class="form-control" placeholder="{{__('label.enter_video_url_320_px')}}">
                            </div>
                            <div class="form-group col-lg-6 url_box">
                                <label>{{__('label.url_480_px')}}</label>
                                <input name="video_url_480" type="url" class="form-control" placeholder="{{__('label.enter_video_url_480_px')}}">
                            </div>
                            <div class="form-group col-lg-6 url_box">
                                <label>{{__('label.url_720_px')}}</label>
                                <input name="video_url_720" type="url" class="form-control" placeholder="{{__('label.enter_video_url_720_px')}}">
                            </div>
                            <div class="form-group col-lg-6 url_box">
                                <label>{{__('label.url_1080_px')}}</label>
                                <input name="video_url_1080" type="url" class="form-control" placeholder="{{__('label.enter_video_url_1080_px')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Subtitle Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-closed-captioning mr-2"></i>{{__('label.subtitle')}}</div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="form-group col-lg-4">
                                <label>{{__('label.subtitle_type')}}<span class="text-danger">*</span></label>
                                <select name="subtitle_type" id="subtitle_type" class="form-control">
                                    <option value="server_video">{{__('label.server_video')}}</option>
                                    <option value="external">{{__('label.external_url')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.language_name')}}</label>
                                    <input type="text" name="subtitle_lang_1" class="form-control" placeholder="{{__('label.language_here')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.language_name')}}</label>
                                    <input type="text" name="subtitle_lang_2" class="form-control" placeholder="{{__('label.language_here')}}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.language_name')}}</label>
                                    <input type="text" name="subtitle_lang_3" class="form-control" placeholder="{{__('label.language_here')}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-lg-4 subtitle_box">
                                <div style="display: block;">
                                    <label>{{__('label.upload_subtitle')}}</label>
                                    <div id="filelist4"></div>
                                    <div id="container4" style="position: relative;">
                                        <div class="form-group">
                                            <input type="file" id="uploadFile4" name="uploadFile4" style="position: relative; z-index: 1;" class="form-control">
                                        </div>
                                        <input type="hidden" name="subtitle_1" id="mp3_file_name4" class="form-control">

                                        <div class="form-group">
                                            <a id="upload4" class="btn upload-btn">{{__('label.upload_files')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 subtitle_box">
                                <div style="display: block;">
                                    <label>{{__('label.upload_subtitle')}}</label>
                                    <div id="filelist6"></div>
                                    <div id="container6" style="position: relative;">
                                        <div class="form-group">
                                            <input type="file" id="uploadFile6" name="uploadFile6" style="position: relative; z-index: 1;" class="form-control">
                                        </div>
                                        <input type="hidden" name="subtitle_2" id="mp3_file_name6" class="form-control">

                                        <div class="form-group">
                                            <a id="upload6" class="btn upload-btn">{{__('label.upload_files')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-4 subtitle_box">
                                <div style="display: block;">
                                    <label>{{__('label.upload_subtitle')}}</label>
                                    <div id="filelist7"></div>
                                    <div id="container7" style="position: relative;">
                                        <div class="form-group">
                                            <input type="file" id="uploadFile7" name="uploadFile7" style="position: relative; z-index: 1;" class="form-control">
                                        </div>
                                        <input type="hidden" name="subtitle_3" id="mp3_file_name7" class="form-control">

                                        <div class="form-group">
                                            <a id="upload7" class="btn upload-btn">{{__('label.upload_files')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 s3_subtitle_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_subtitle')}}</label>
                                    <input type="file" name="subtitle_1" class="form-control"  accept=".mp4">
                                </div>
                            </div>
                            <div class="col-lg-4 s3_subtitle_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_subtitle')}}</label>
                                    <input type="file" name="subtitle_2" class="form-control"  accept=".mp4">
                                </div>
                            </div>
                            <div class="col-lg-4 s3_subtitle_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_subtitle')}}</label>
                                    <input type="file" name="subtitle_3" class="form-control"  accept=".mp4">
                                </div>
                            </div>
                            <div class="form-group col-lg-4 subtitle_url_box">
                                <label>{{__('label.subtitle')}}</label>
                                <input name="subtitle_url_1" type="url" class="form-control" placeholder="{{__('label.subtitle_url_here')}}">
                            </div>
                            <div class="form-group col-lg-4 subtitle_url_box">
                                <label>{{__('label.subtitle')}}</label>
                                <input name="subtitle_url_2" type="url" class="form-control" placeholder="{{__('label.subtitle_url_here')}}">
                            </div>
                            <div class="form-group col-lg-4 subtitle_url_box">
                                <label>{{__('label.subtitle')}}</label>
                                <input name="subtitle_url_3" type="url" class="form-control" placeholder="{{__('label.subtitle_url_here')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Image Card -->
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.landscape_image')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type='file' name="landscape" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                        <label class="avatar-preview landscape-preview">
                                            <img src="{{ asset('assets/imgs/upload_img_land.png') }}" class="imagePreview" id="imagePreviewLandscape"/>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-default mw-120" onclick="save_tvshow_video()">
                            <i class="fa-solid fa-floppy-disk fa-lg mr-1"></i>{{__('label.save')}}
                        </button>
                        <a href="{{route('admin.tvshow.episode.index', ['id'=> $tvshow_id, 'type_id' => $type['id']])}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
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
        })

        function save_tvshow_video() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }
            
            var formData = new FormData($("#save_tvshow_video")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.tvshow.episode.save") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_tvshow_video', '{{ route("admin.tvshow.episode.index",["id"=> $tvshow_id, "type_id" => $type["id"]]) }}');
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
                $(".s3_subtitle_box").hide();
            } else {
                $(".video_box").hide();
                $(".subtitle_box").hide();
            }

            $(".url_box").hide();
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
                    $(".url_box").hide();
                } else if (optionValue == 'vdocipher_id'){

                    $(".url_box").hide();
                    $(".url_box_main").show();
                    $(".video_box").hide();
                    $(".s3_video_box").hide();

                    $(".url_box_main label").html("{{__('label.vdocipher_id')}}<span class='text-danger'>*</span>");
                    $(".url_box_main input").attr("placeholder", "{{__('label.vdocipher_id_here')}}");
                } else {
                    $(".url_box").show();
                    $(".url_box_main").show();
                    $(".video_box").hide();
                    $(".s3_video_box").hide();

                    $(".url_box_main label").html("{{__('label.url_320_px')}}<span class='text-danger'>*</span>");
                    $(".url_box_main input").attr("placeholder", "{{__('label.enter_video_url_320_px')}}");
                }

                if (optionValue == 'server_video') {
                    $(".Is_Download").show();
                } else {
                    $(".Is_Download").hide();
                }
            });

            $(".subtitle_url_box").hide();
            $('#subtitle_type').change(function() {
                var optionValue = $(this).val();

                if (optionValue == 'server_video') {
                    if (storage_type == 1) {
                        $(".subtitle_box").show();
                        $(".s3_subtitle_box").hide();
                    } else {
                        $(".subtitle_box").hide();
                        $(".s3_subtitle_box").show();
                    }
                    $(".subtitle_url_box").hide();
                } else {
                    $(".subtitle_url_box").show();
                    $(".subtitle_box").hide();
                    $(".s3_subtitle_box").hide();
                }
            });
        });
    </script>
@endsection