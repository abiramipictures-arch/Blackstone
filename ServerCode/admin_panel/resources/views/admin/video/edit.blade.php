@extends('admin.layout.page-app')
@section('page_title', __('label.edit_content'))
@section('tab_title', __('label.edit_content'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />
        <!-- Data Time Picker -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.edit_content')}}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.video.index', ['type_id' => $type_id]) }}">{{ $type['name'] }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.edit_content')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.video.index', ['type_id' => $type_id]) }}" class="btn btn-default-white mw-120">{{__('label.content_list')}}</a>
                </div>
            </div>

            <form id="edit_video" enctype="multipart/form-data">
                <input type="hidden" name="id" value="{{ $data->id }}">
                <input type="hidden" name="video_type" value="{{ $type->type }}">
                <input type="hidden" name="old_thumbnail" value="{{ $data->thumbnail }}">
                <input type="hidden" name="old_landscape" value="{{ $data->landscape }}">
                <input type="hidden" name="old_video_upload_type" value="{{ $data->video_upload_type }}">
                <input type="hidden" name="old_video_320" value="{{ $data->video_320 }}">
                <input type="hidden" name="old_video_480" value="{{ $data->video_480 }}">
                <input type="hidden" name="old_video_720" value="{{ $data->video_720 }}">
                <input type="hidden" name="old_video_1080" value="{{ $data->video_1080 }}">
                <input type="hidden" name="old_trailer_type" value="{{ $data->trailer_type }}">
                <input type="hidden" name="old_trailer" value="{{ $data->trailer_url }}">
                <input type="hidden" name="old_subtitle_type" value="{{ $data->subtitle_type }}">
                <input type="hidden" name="old_subtitle_1" value="{{ $data->subtitle_1 }}">
                <input type="hidden" name="old_subtitle_2" value="{{ $data->subtitle_2 }}">
                <input type="hidden" name="old_subtitle_3" value="{{ $data->subtitle_3 }}">
                <input type="hidden" name="old_storage_type" value="{{ $data->storage_type }}">
                <input type="hidden" name="old_video_storage_type" value="{{ $data->video_storage_type }}">
                <input type="hidden" name="old_trailer_storage_type" value="{{ $data->trailer_storage_type }}">
                <input type="hidden" name="old_subtitle_storage_type" value="{{ $data->subtitle_storage_type }}">

                <!-- Title Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-film"></i><span>{{__('label.edit_content')}}</span></div>
                    <div class="card-body py-2">
                        <?php $status = TMDB_Status(); ?> <!-- 0- No, 1- Yes-->
                        @if($status == 0)
                            <div class="form-row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                        <input type="text" name="name" value="{{ $data->name }}" class="form-control" placeholder="{{__('label.name_here')}}" autofocus>
                                    </div>
                                </div>
                            </div>
                        @elseif ($status == 1)
                            <div class="form-row">
                                <div class="col-md-2">
                                    <div class="form-group pt-3">
                                        <label>{{__('label.import_from_tmdb')}}</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="Tmdb_id" id="Tmdb_id" class="form-control" placeholder="{{__('label.enter_tmdb_id_eg')}}">
                                        <label class="mt-1 text-gray">{{__('label.tmdb_notes')}} <a href="https://www.reddit.com/r/jellyfin/comments/xf8uvg/where_to_find_tmdb_id/" target="_blank" class="btn-link">{{__('label.click_here')}}</a> </label>
                                    </div>
                                </div>
                                <div class="col-md-2 ml-5">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-default mw-120" onclick="tmdb_data_fetch()">{{__('label.fetch')}}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 ml-5">
                                    <div class="form-group">
                                        <label>{{__('label.or')}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-2 pt-3">
                                    <div class="form-group">
                                        <label>{{__('label.movies_name')}}<span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="name" value="{{ $data->name }}" id="Tmdb_name" list="Tmdb_name_list" class="form-control" placeholder="{{__('label.movies_name_here')}}">
                                        <datalist id="Tmdb_name_list"></datalist>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Basic Detail Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-list-check"></i><span>{{__('label.basic_detail')}}</span></div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-md-6">
                                <?php $x = explode(",", $data->category_id); ?>
                                <div class="form-group">
                                    <label>{{__('label.category')}}<span class="text-danger">*</span></label>
                                    <select class="form-control" name="category_id[]" id="category_id" multiple>
                                        @foreach ($category as $key => $value)
                                            <option value="{{ $value->id }}" {{(in_array($value->id, $x)) ? 'selected' : ''}}>{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php $y = explode(",", $data->language_id); ?>
                                <div class="form-group">
                                    <label>{{__('label.language')}}<span class="text-danger">*</span></label>
                                    <select class="form-control" name="language_id[]" id="language_id" multiple>
                                        @foreach ($language as $key => $value)
                                            <option value="{{ $value->id }}" {{(in_array($value->id, $y)) ? 'selected' : ''}}>{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-6">
                                <?php $z = explode(",", $data->cast_id); ?>
                                <div class="form-group">
                                    <label>{{__('label.cast')}}</label>
                                    <select class="form-control" name="cast_id[]" id="cast_id" multiple>
                                        @foreach ($cast as $key => $value)
                                            <option value="{{ $value->id }}" {{(in_array($value->id, $z)) ? 'selected' : ''}}>{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.producer')}}</label>
                                    <select class="form-control" name="producer_id" id="producer_id">
                                        <option value="">{{__('label.select_producer')}}</option>
                                        @foreach ($producer as $key => $value)
                                            <option value="{{ $value->id }}" {{ $data->producer_id == $value->id ? 'selected' : '' }}>{{ $value->user_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if($type['type'] == 6)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{__('label.channel')}}<span class="text-danger">*</span></label>
                                        <select class="form-control" name="channel_id" id="channel_id">
                                            <option value="">{{__('label.select_channel')}}</option>
                                            @foreach ($channel as $key => $value)
                                                <option value="{{ $value->id }}" {{ $data->channel_id == $value->id ? 'selected' : '' }}>{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.release_date')}}</label>
                                    <input name="release_date" type="date" value="{{ $data->release_date }}" class="form-control" id="release_date">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video File Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-video"></i><span>{{__('label.video_file')}}</span></div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__('label.video_upload_type')}}<span class="text-danger">*</span></label>
                                    <select name="video_upload_type" id="video_upload_type" class="form-control">
                                        <option value="server_video" {{$data->video_upload_type == "server_video" ? 'selected' : ''}}>{{__('label.server_video')}}</option>
                                        <option value="external" {{$data->video_upload_type == "external" ? 'selected' : ''}}>{{__('label.external_url')}}</option>
                                        <option value="youtube" {{$data->video_upload_type == "youtube" ? 'selected' : ''}}>{{__('label.youtube')}}</option>
                                        <option value="live_stream_url" {{$data->video_upload_type == "live_stream_url" ? 'selected' : ''}}>{{__('label.live_stream_url')}}</option>
                                        <option value="vdocipher_id" {{$data->video_upload_type == "vdocipher_id" ? 'selected' : ''}}>{{__('label.vdocipher_id')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{__('label.is_premium')}}<span class="text-danger">*</span></label>
                                    <div class="radio-group">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_premium" id="is_premium_no" class="custom-control-input" value="0" {{$data->is_premium == 0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="is_premium_no">{{__('label.no')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_premium" id="is_premium_yes" class="custom-control-input" value="1" {{$data->is_premium == 1 ? 'checked' : ''}}>
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
                                            <input type="radio" name="is_title" id="is_title_no" class="custom-control-input" value="0" {{$data->is_title == 0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="is_title_no">{{__('label.no')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_title" id="is_title_yes" class="custom-control-input" value="1" {{$data->is_title == 1 ? 'checked' : ''}}>
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
                                            <input type="radio" name="is_download" id="is_download_no" class="custom-control-input" value="0" {{$data->is_download == 0 ? 'checked' : ''}}>
                                            <label class="custom-control-label" for="is_download_no">{{__('label.no')}}</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" name="is_download" id="is_download_yes" class="custom-control-input" value="1" {{$data->is_download == 1 ? 'checked' : ''}}>
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
                                <div class="form-group">
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
                                        @if($data->video_upload_type == 'server_video' && $data->video_320)
                                            <small class="text-muted"><i class="fa-solid fa-file-video fa-xs"></i> {{ $data->video_320 }}</small>
                                        @endif
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
                                    @if($data->video_upload_type == 'server_video' && $data->video_480)
                                        <small class="text-muted"><i class="fa-solid fa-file-video fa-xs"></i> {{ $data->video_480 }}</small>
                                    @endif
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
                                    @if($data->video_upload_type == 'server_video' && $data->video_720)
                                        <small class="text-muted"><i class="fa-solid fa-file-video fa-xs"></i> {{ $data->video_720 }}</small>
                                    @endif
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
                                    @if($data->video_upload_type == 'server_video' && $data->video_1080)
                                        <small class="text-muted"><i class="fa-solid fa-file-video fa-xs"></i> {{ $data->video_1080 }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 s3_video_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_video_320_px')}}<span class="text-danger">*</span></label>
                                    <input type="file" name="video_320" class="form-control" accept=".mp4">
                                    @if($data->video_upload_type == 'server_video' && $data->video_320)
                                        <small class="text-muted mt-1 d-block"><i class="fa-solid fa-file-video fa-xs"></i> {{ $data->video_320 }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 s3_video_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_video_480_px')}}</label>
                                    <input type="file" name="video_480" class="form-control" accept=".mp4">
                                    @if($data->video_upload_type == 'server_video' && $data->video_480)
                                        <small class="text-muted mt-1 d-block"><i class="fa-solid fa-file-video fa-xs"></i> {{ $data->video_480 }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 s3_video_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_video_720_px')}}</label>
                                    <input type="file" name="video_720" class="form-control" accept=".mp4">
                                    @if($data->video_upload_type == 'server_video' && $data->video_720)
                                        <small class="text-muted mt-1 d-block"><i class="fa-solid fa-file-video fa-xs"></i> {{ $data->video_720 }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3 s3_video_box">
                                <div class="form-group">
                                    <label>{{__('label.upload_video_1080_px')}}</label>
                                    <input type="file" name="video_1080" class="form-control" accept=".mp4">
                                    @if($data->video_upload_type == 'server_video' && $data->video_1080)
                                        <small class="text-muted mt-1 d-block"><i class="fa-solid fa-file-video fa-xs"></i> {{ $data->video_1080 }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group col-lg-6 url_box_main">
                                <label id="dynamicLabel">{{__('label.url_320_px')}}<span class="text-danger">*</span></label>
                                <input name="video_url_320" value="@if($data->video_upload_type != 'server_video'){{ $data->video_320 }}@endif" type="url" class="form-control" placeholder="{{__('label.enter_video_url_320_px')}}">
                            </div>
                            <div class="form-group col-lg-6 url_box">
                                <label>{{__('label.url_480_px')}}</label>
                                <input name="video_url_480" value="@if($data->video_upload_type != 'server_video'){{ $data->video_480 }}@endif" type="url" class="form-control" placeholder="{{__('label.enter_video_url_480_px')}}">
                            </div>
                            <div class="form-group col-lg-6 url_box">
                                <label>{{__('label.url_720_px')}}</label>
                                <input name="video_url_720" value="@if($data->video_upload_type != 'server_video'){{ $data->video_720 }}@endif" type="url" class="form-control" placeholder="{{__('label.enter_video_url_720_px')}}">
                            </div>
                            <div class="form-group col-lg-6 url_box">
                                <label>{{__('label.url_1080_px')}}</label>
                                <input name="video_url_1080" value="@if($data->video_upload_type != 'server_video'){{ $data->video_1080 }}@endif" type="url" class="form-control" placeholder="{{__('label.enter_video_url_1080_px')}}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trailer Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-clapperboard"></i><span>{{__('label.trailer')}}</span></div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-lg-3 form-group">
                                <label>{{__('label.trailer_type')}}</label>
                                <select name="trailer_type" id="trailer_type" class="form-control">
                                    <option value="server_video" {{ $data->trailer_type == "server_video" ? 'selected' : ''}}>{{__('label.server_video')}}</option>
                                    <option value="external" {{ $data->trailer_type == "external" ? 'selected' : ''}}>{{__('label.external_url')}}</option>
                                    <option value="youtube" {{ $data->trailer_type == "youtube" ? 'selected' : ''}}>{{__('label.youtube')}}</option>
                                </select>
                            </div>
                            <div class="col-lg-6 form-group trailer_box">
                                <div style="display: block;">
                                    <label>{{__('label.trailer')}}</label>
                                    <div id="filelist5"></div>
                                    <div id="container5" style="position: relative;">
                                        <div class="form-group d-flex align-items-center">
                                            <input type="file" id="uploadFile5" name="uploadFile5" style="position: relative; z-index: 1;" class="form-control mr-4">
                                            <a id="upload5" class="btn upload-btn py-2">{{__('label.upload_files')}}</a>
                                        </div>
                                        <input type="hidden" name="trailer" id="mp3_file_name5" class="form-control">
                                        @if($data->trailer_type == 'server_video' && $data->trailer_url)
                                            <small class="text-muted"><i class="fa-solid fa-file-video fa-xs"></i> {{ $data->trailer_url }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 form-group s3_trailer_box">
                                <label>{{__('label.trailer')}}</label>
                                <input type="file" name="trailer" class="form-control" accept=".mp4">
                                @if($data->trailer_type == 'server_video' && $data->trailer_url)
                                    <small class="text-muted mt-1 d-block"><i class="fa-solid fa-file-video fa-xs"></i> {{ $data->trailer_url }}</small>
                                @endif
                            </div>
                            <div class="col-lg-6 form-group trailer_url_box">
                                <label>{{__('label.trailer')}}</label>
                                <input name="trailer_url" value="@if($data->trailer_type != 'server_video'){{ $data->trailer_url }}@endif" type="url" class="form-control" placeholder="{{__('label.trailer_url')}}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subtitle Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-closed-captioning"></i><span>{{__('label.subtitle')}}</span></div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-lg-4 form-group">
                                <label>{{__('label.subtitle_type')}}<span class="text-danger">*</span></label>
                                <select name="subtitle_type" id="subtitle_type" class="form-control">
                                    <option value="server_video" {{ $data->subtitle_type == "server_video" ? 'selected' : ''}}>{{__('label.server_video')}}</option>
                                    <option value="external" {{ $data->subtitle_type == "external" ? 'selected' : ''}}>{{__('label.external_url')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-md-4 form-group">
                                <label>{{__('label.language_name')}}</label>
                                <input type="text" name="subtitle_lang_1" value="{{ $data->subtitle_lang_1 }}" class="form-control" placeholder="{{__('label.language_here')}}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>{{__('label.language_name')}}</label>
                                <input type="text" name="subtitle_lang_2" value="{{ $data->subtitle_lang_2 }}" class="form-control" placeholder="{{__('label.language_here')}}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label>{{__('label.language_name')}}</label>
                                <input type="text" name="subtitle_lang_3" value="{{ $data->subtitle_lang_3 }}" class="form-control" placeholder="{{__('label.language_here')}}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-4 form-group subtitle_box">
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
                                        @if($data->subtitle_type == 'server_video' && $data->subtitle_1)
                                            <small class="text-muted"><i class="fa-solid fa-file fa-xs"></i> {{ $data->subtitle_1 }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 form-group subtitle_box">
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
                                        @if($data->subtitle_type == 'server_video' && $data->subtitle_2)
                                            <small class="text-muted"><i class="fa-solid fa-file fa-xs"></i> {{ $data->subtitle_2 }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 form-group subtitle_box">
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
                                        @if($data->subtitle_type == 'server_video' && $data->subtitle_3)
                                            <small class="text-muted"><i class="fa-solid fa-file fa-xs"></i> {{ $data->subtitle_3 }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 form-group s3_subtitle_box">
                                <label>{{__('label.upload_subtitle')}}</label>
                                <input type="file" name="subtitle_1" class="form-control" accept=".mp4">
                                @if($data->subtitle_type == 'server_video' && $data->subtitle_1)
                                    <small class="text-muted mt-1 d-block"><i class="fa-solid fa-file fa-xs"></i> {{ $data->subtitle_1 }}</small>
                                @endif
                            </div>
                            <div class="col-lg-4 form-group s3_subtitle_box">
                                <label>{{__('label.upload_subtitle')}}</label>
                                <input type="file" name="subtitle_2" class="form-control" accept=".mp4">
                                @if($data->subtitle_type == 'server_video' && $data->subtitle_2)
                                    <small class="text-muted mt-1 d-block"><i class="fa-solid fa-file fa-xs"></i> {{ $data->subtitle_2 }}</small>
                                @endif
                            </div>
                            <div class="col-lg-4 form-group s3_subtitle_box">
                                <label>{{__('label.upload_subtitle')}}</label>
                                <input type="file" name="subtitle_3" class="form-control" accept=".mp4">
                                @if($data->subtitle_type == 'server_video' && $data->subtitle_3)
                                    <small class="text-muted mt-1 d-block"><i class="fa-solid fa-file fa-xs"></i> {{ $data->subtitle_3 }}</small>
                                @endif
                            </div>
                            <div class="form-group col-lg-4 subtitle_url_box">
                                <label>{{__('label.subtitle')}}</label>
                                <input name="subtitle_url_1" type="url" value="@if($data->subtitle_type != 'server_video'){{ $data->subtitle_1 }}@endif" class="form-control" placeholder="{{__('label.subtitle_url_here')}}">
                            </div>
                            <div class="form-group col-lg-4 subtitle_url_box">
                                <label>{{__('label.subtitle')}}</label>
                                <input name="subtitle_url_2" type="url" value="@if($data->subtitle_type != 'server_video'){{ $data->subtitle_2 }}@endif" class="form-control" placeholder="{{__('label.subtitle_url_here')}}">
                            </div>
                            <div class="form-group col-lg-4 subtitle_url_box">
                                <label>{{__('label.subtitle')}}</label>
                                <input name="subtitle_url_3" type="url" value="@if($data->subtitle_type != 'server_video'){{ $data->subtitle_3 }}@endif" class="form-control" placeholder="{{__('label.subtitle_url_here')}}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media & Description Card -->
                <div class="card custom-border-card">
                    <div class="card-header"><i class="fa-solid fa-photo-film"></i><span>{{__('label.description')}} &amp; {{__('label.media')}}</span></div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-8">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.description')}}</label>
                                            <textarea name="description" class="form-control" rows="2" id="description" placeholder="{{__('label.description_here')}}">{{$data->description}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.is_rent')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="is_rent" id="is_rent_no" class="custom-control-input" value="0" {{$data->is_rent == 0 ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="is_rent_no">{{__('label.no')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="is_rent" id="is_rent_yes" class="custom-control-input" value="1" {{$data->is_rent == 1 ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="is_rent_yes">{{__('label.yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.is_comment')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="is_comment" id="is_comment_no" class="custom-control-input" value="0" {{$data->is_comment == 0 ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="is_comment_no">{{__('label.no')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="is_comment" id="is_comment_yes" class="custom-control-input" value="1" {{$data->is_comment == 1 ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="is_comment_yes">{{__('label.yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{__('label.is_like')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="is_like" id="is_like_no" class="custom-control-input" value="0" {{$data->is_like == 0 ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="is_like_no">{{__('label.no')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="is_like" id="is_like_yes" class="custom-control-input" value="1" {{$data->is_like == 1 ? 'checked' : ''}}>
                                                    <label class="custom-control-label" for="is_like_yes">{{__('label.yes')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-md-4 rent_price">
                                        <div class="form-group">
                                            <label>{{__('label.price_list')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="price">
                                                <option value="">{{__('label.select_price')}}</option>
                                                @foreach ($rent_price_list as $key => $value)
                                                    <option value="{{ $value->id }}" {{ $data->price == $value->id ? 'selected' : '' }}>{{ $value->price }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 rent_price">
                                        <div class="form-group">
                                            <label>{{__('label.rent_time_in_days')}}<span class="text-danger">*</span></label>
                                            <input type="number" name="rent_day" value="{{ $data->rent_day }}" class="form-control" placeholder="{{__('label.howmany_days')}}" min="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group ml-5">
                                    <label>{{__('label.thumbnail_image')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type='file' name="thumbnail" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                        <label class="avatar-preview">
                                            <img src="{{ $data->thumbnail }}" class="imagePreview" id="imagePreviewThumbnail"/>
                                            <input type="hidden" class="form-control" id="thumbnail_tmdb" name="thumbnail_tmdb">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>{{__('label.landscape_image')}}</label>
                                    <div class="avatar-upload my-2 image-upload-wrapper">
                                        <input type='file' name="landscape" class="imageUpload" accept=".png, .jpg, .jpeg, .webp" hidden/>
                                        <label class="avatar-preview landscape-preview">
                                            <img src="{{ $data->landscape }}" class="imagePreview" id="imagePreviewLandscape"/>
                                            <input type="hidden" class="form-control" id="landscape_tmdb" name="landscape_tmdb">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="edit_video()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
                        <a href="{{route('admin.video.index', ['type_id' => $type_id])}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Data Time Picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <!-- Chunk JS -->
    <script src="{{ asset('/assets/js/plupload.full.min.js')}}"></script>
    <script src="{{ asset('/assets/js/common.js')}}"></script>

    <script>
        var duration = '<?php echo $data->video_duration; ?>';
        let hours = msToHours(duration);
        let minutes = msToMinutes(duration);
        let seconds = msToSeconds(duration);

        var date = new Date();
        date.setHours(hours, minutes, seconds);

        $('#timePicker').datetimepicker({
            useCurrent: false,
            format: 'HH:mm:ss',
            defaultDate: date,
            showClose: true,
            showTodayButton: true,
            icons: {
                up: "fa fa-chevron-up",
                down: "fa fa-chevron-down",
                today: "fa fa-clock fa-regular",
                close: "fa fa-times",
            }
        });

        function edit_video() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#edit_video")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{route("admin.video.update", [$data->id])}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'edit_video', '{{ route("admin.video.index", ["type_id" => $type_id]) }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        $(document).ready(function() {
            $("#category_id").select2({placeholder: "{{__('label.select_category')}}", allowClear: true, width: '100%'});
            $("#language_id").select2({placeholder: "{{__('label.select_language')}}", allowClear: true, width: '100%'});
            $("#cast_id").select2({placeholder: "{{__('label.select_cast')}}", allowClear: true, width: '100%'});
            $("#producer_id").select2();
            $("#channel_id").select2();

            var video_upload_type = "<?php echo $data->video_upload_type; ?>";
            var subtitle_type     = "<?php echo $data->subtitle_type; ?>";
            var trailer_type      = "<?php echo $data->trailer_type; ?>";
            var storage_type      = "<?php echo Storage_Type(); ?>";

            // --- Video upload type initial state ---
            if (video_upload_type == "server_video") {
                if (storage_type == 1) {
                    $(".video_box").show();
                    $(".s3_video_box").hide();
                } else {
                    $(".video_box").hide();
                    $(".s3_video_box").show();
                }
                $(".url_box").hide();
                $(".url_box_main").hide();
            } else if (video_upload_type == "live_stream_url") {
                $(".video_box").hide();
                $(".s3_video_box").hide();
                $(".url_box_main").show();
                $(".url_box").hide();
                $(".url_box_main label").html("{{__('label.live_stream_url')}}<span class='text-danger'>*</span>");
                $(".url_box_main input").attr("placeholder", "{{__('label.live_stream_url_here')}}");
            } else if (video_upload_type == "vdocipher_id") {
                $(".video_box").hide();
                $(".s3_video_box").hide();
                $(".url_box_main").show();
                $(".url_box").hide();
                $(".url_box_main label").html("{{__('label.vdocipher_id')}}<span class='text-danger'>*</span>");
                $(".url_box_main input").attr("placeholder", "{{__('label.vdocipher_id_here')}}");
            } else {
                $(".video_box").hide();
                $(".s3_video_box").hide();
                $(".url_box").show();
                $(".url_box_main").show();
                $(".url_box_main label").html("{{__('label.url_320_px')}}<span class='text-danger'>*</span>");
                $(".url_box_main input").attr("placeholder", "{{__('label.enter_video_url_320_px')}}");
            }
            if (video_upload_type == "server_video") {
                $(".Is_Download").show();
            } else {
                $(".Is_Download").hide();
            }

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
                    $(".url_box").hide();
                    $(".url_box_main").hide();
                } else if (optionValue == 'live_stream_url') {
                    $(".url_box").hide();
                    $(".url_box_main").show();
                    $(".video_box").hide();
                    $(".s3_video_box").hide();
                    $(".url_box_main label").html("{{__('label.live_stream_url')}}<span class='text-danger'>*</span>");
                    $(".url_box_main input").attr("placeholder", "{{__('label.live_stream_url_here')}}");
                } else if (optionValue == 'vdocipher_id') {
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

            // --- Trailer type initial state ---
            if (trailer_type == "server_video") {
                if (storage_type == 1) {
                    $(".trailer_box").show();
                    $(".s3_trailer_box").hide();
                } else {
                    $(".trailer_box").hide();
                    $(".s3_trailer_box").show();
                }
                $(".trailer_url_box").hide();
            } else {
                $(".trailer_box").hide();
                $(".s3_trailer_box").hide();
                $(".trailer_url_box").show();
            }
            $('#trailer_type').change(function() {
                var optionValue = $(this).val();
                if (optionValue == 'server_video') {
                    if (storage_type == 1) {
                        $(".trailer_box").show();
                        $(".s3_trailer_box").hide();
                    } else {
                        $(".trailer_box").hide();
                        $(".s3_trailer_box").show();
                    }
                    $(".trailer_url_box").hide();
                } else {
                    $(".trailer_box").hide();
                    $(".trailer_url_box").show();
                    $(".s3_trailer_box").hide();
                }
            });

            // --- Subtitle type initial state ---
            if (subtitle_type == "server_video") {
                if (storage_type == 1) {
                    $(".subtitle_box").show();
                    $(".s3_subtitle_box").hide();
                } else {
                    $(".subtitle_box").hide();
                    $(".s3_subtitle_box").show();
                }
                $(".subtitle_url_box").hide();
            } else {
                $(".subtitle_box").hide();
                $(".s3_subtitle_box").hide();
                $(".subtitle_url_box").show();
            }
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

            // --- Rent price initial state ---
            var is_rent = "<?php echo $data->is_rent; ?>";
            if (is_rent == 1) {
                $(".rent_price").show();
            } else {
                $(".rent_price").hide();
            }
            $('input[type=radio][name=is_rent]').change(function() {
                if (this.value == 1) {
                    $(".rent_price").show();
                } else if (this.value == 0) {
                    $(".rent_price").hide();
                }
            });
        });

        $('#Tmdb_name').keyup(function() {
            var txtVal = this.value;
            if (txtVal.length >= 3) {
                var url = "{{route('admin.video.serach.name', '')}}" + "/" + txtVal;
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: txtVal,
                    success: function(resp) {
                        if (resp.status == 200) {
                            if (resp.data.results.length > 0) {
                                $('#Tmdb_name_list').empty();
                                var Title_Data = resp.data.results;
                                for (let i = 0; i < Title_Data.length; i++) {
                                    $('#Tmdb_name_list').append('<option id="' + resp.data.results[i].id + '" value="' + resp.data.results[i].title + '"></option>');
                                }
                            }
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        toastr.error(errorThrown, textStatus);
                    }
                });
            }
        });
        $('#Tmdb_name').on('input', function() {
            var userText = $(this).val();
            $("#Tmdb_name_list").find("option").each(function() {
                if ($(this).val() == userText) {
                    var MoviesName = $("#Tmdb_name").val();
                    c_id = $('#Tmdb_name_list').find('option[value="' + MoviesName + '"]').attr('id');
                    $("#dvloader").show();
                    var url = "{{route('admin.video.getdata', '')}}" + "/" + c_id;
                    $.ajax({
                        type: "POST",
                        url: url,
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        data: c_id,
                        success: function(resp) {
                            $("#dvloader").hide();
                            if (resp.status == 200) {
                                var C_Id = resp.data.C_Id;
                                var L_Id = resp.data.L_Id;
                                var C_Insert_Data = resp.data.C_Insert_Data;
                                var L_Insert_Data = resp.data.L_Insert_Data;
                                var Cast_Id = resp.data.Cast_Id;
                                var Cast_Insert_Data = resp.data.Cast_Insert_Data;
                                var Thumbnail = resp.data.Thumbnail;
                                var Title = resp.data.Title;
                                var Description = resp.data.Description;
                                var Release_Date = resp.data.Release_Date;

                                for (let i = 0; i < C_Insert_Data.length; i++) {
                                    $('#category_id').append('<option value="' + C_Insert_Data[i].id + '">' + C_Insert_Data[i].name + '</option>');
                                }
                                $("#category_id").val(C_Id).trigger("change");

                                for (let i = 0; i < L_Insert_Data.length; i++) {
                                    $('#language_id').append('<option value="' + L_Insert_Data[i].id + '">' + L_Insert_Data[i].name + '</option>');
                                }
                                $("#language_id").val(L_Id).trigger("change");

                                for (let i = 0; i < Cast_Insert_Data.length; i++) {
                                    $('#cast_id').append('<option value="' + Cast_Insert_Data[i].id + '">' + Cast_Insert_Data[i].name + '</option>');
                                }
                                $("#cast_id").val(Cast_Id).trigger("change");

                                $('#imagePreviewThumbnail').attr('src', Thumbnail);
                                $('#imagePreviewLandscape').attr('src', Thumbnail);
                                $('#thumbnail_tmdb').attr('value', Thumbnail);
                                $('#landscape_tmdb').attr('value', Thumbnail);
                                $('#description').val(Description);
                                $("#release_date").attr('value', Release_Date);
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $("#dvloader").hide();
                            toastr.error(errorThrown, textStatus);
                        }
                    });
                }
            });
        });
        function tmdb_data_fetch() {
            var id = $("#Tmdb_id").val();
            if (id != "") {
                $("#dvloader").show();
                var url = "{{route('admin.video.getdata', '')}}" + "/" + id;
                $.ajax({
                    type: "POST",
                    url: url,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    data: id,
                    success: function(resp) {
                        $("#dvloader").hide();
                        if (resp.status == 200) {
                            var C_Id = resp.data.C_Id;
                            var L_Id = resp.data.L_Id;
                            var C_Insert_Data = resp.data.C_Insert_Data;
                            var L_Insert_Data = resp.data.L_Insert_Data;
                            var Cast_Id = resp.data.Cast_Id;
                            var Cast_Insert_Data = resp.data.Cast_Insert_Data;
                            var Thumbnail = resp.data.Thumbnail;
                            var Title = resp.data.Title;
                            var Description = resp.data.Description;
                            var Release_Date = resp.data.Release_Date;

                            for (let i = 0; i < C_Insert_Data.length; i++) {
                                $('#category_id').append('<option value="' + C_Insert_Data[i].id + '">' + C_Insert_Data[i].name + '</option>');
                            }
                            $("#category_id").val(C_Id).trigger("change");

                            for (let i = 0; i < L_Insert_Data.length; i++) {
                                $('#language_id').append('<option value="' + L_Insert_Data[i].id + '">' + L_Insert_Data[i].name + '</option>');
                            }
                            $("#language_id").val(L_Id).trigger("change");

                            for (let i = 0; i < Cast_Insert_Data.length; i++) {
                                $('#cast_id').append('<option value="' + Cast_Insert_Data[i].id + '">' + Cast_Insert_Data[i].name + '</option>');
                            }
                            $("#cast_id").val(Cast_Id).trigger("change");

                            $('#imagePreviewThumbnail').attr('src', Thumbnail);
                            $('#imagePreviewLandscape').attr('src', Thumbnail);
                            $('#thumbnail_tmdb').attr('value', Thumbnail);
                            $('#landscape_tmdb').attr('value', Thumbnail);
                            $('#description').val(Description);
                            $("#release_date").attr('value', Release_Date);
                            $('#Tmdb_name').val(Title);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                alert('{{__("label.please_enter_tmdb_id")}}');
            }
        }
    </script>
@endsection
