@extends('producer.layout.page-app')
@section('page_title', __('label.edit_content'))
@section('tab_title', __('label.edit_content'))

@section('content')
    @include('producer.layout.sidebar')

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

    <div class="right-content">
        @include('producer.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.edit_content')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('producer.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('producer.shorts.index', ['type_id' => $type['id']]) }}">{{$type['name']}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.edit_content')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('producer.shorts.index', ['type_id' => $type['id']]) }}" class="btn btn-default-white mw-120">{{__('label.content_list')}}</a>
                </div>
            </div>

            <form id="edit_shorts" enctype="multipart/form-data">
                <input type="hidden" name="id" class="form-control" value="{{ $data->id }}" id="id">
                <input type="hidden" name="type_id" value="{{ $type['id'] }}">
                <input type="hidden" name="video_type" value="{{ $type['type'] }}">
                <input type="hidden" name="old_trailer_type" value="{{ $data->trailer_type }}">
                <input type="hidden" name="old_trailer" value="{{ $data->trailer_url }}">
                <input type="hidden" name="old_thumbnail" value="{{ $data->thumbnail }}">
                <input type="hidden" name="old_storage_type" value="{{ $data->storage_type }}">
                <input type="hidden" name="old_trailer_storage_type" value="{{ $data->trailer_storage_type }}">

                <!-- Title Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-film"></i>{{__('label.name')}}</div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ $data->name }}" class="form-control" placeholder="{{__('label.name_here')}}" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Detail Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-list-check"></i>{{__('label.basic_detail')}}</div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php $x = explode(",", $data->category_id); ?>
                                    <label>{{__('label.category')}}</label>
                                    <select class="form-control" name="category_id[]" multiple id="category_id">
                                        @foreach ($category as $key => $value)
                                        <option value="{{$value->id}}" {{(in_array($value->id, $x)) ? 'selected' : ''}}>
                                            {{ $value->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <?php $y = explode(",", $data->language_id); ?>
                                    <label>{{__('label.language')}}</label>
                                    <select class="form-control" name="language_id[]" id="language_id" multiple>
                                        @foreach ($language as $key => $value)
                                        <option value="{{$value->id}}" {{(in_array($value->id, $y)) ? 'selected' : ''}}>
                                            {{ $value->name }}
                                        </option>
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
                                    <select class="form-control" name="cast_id[]" multiple id="cast_id">
                                        @foreach ($cast as $key => $value)
                                        <option value="{{ $value->id}}" {{(in_array($value->id, $z)) ? 'selected' : ''}}>
                                            {{ $value->name }}
                                        </option>
                                        @endforeach
                                    </select>
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
                            <div class="col-md-2">
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
                            <div class="col-md-2">
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
                    </div>
                </div>

                <!-- Trailer Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-clapperboard"></i>{{__('label.trailer')}}</div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="form-group col-lg-4">
                                <label>{{__('label.trailer_type')}}<span class="text-danger">*</span></label>
                                <select name="trailer_type" id="trailer_type" class="form-control">
                                    <option value="server_video" {{ $data->trailer_type == "server_video" ? 'selected' : ''}}>{{__('label.server_video')}}</option>
                                    <option value="external" {{ $data->trailer_type == "external" ? 'selected' : ''}}>{{__('label.external_url')}}</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6 trailer_box">
                                <div style="display: block;">
                                    <label>{{__('label.trailer')}}<span class="text-danger">*</span></label>
                                    <div id="filelist5"></div>
                                    <div id="container5" style="position: relative;">
                                        <div class="form-group d-flex align-items-center">
                                            <input type="file" id="uploadFile5" name="uploadFile5" style="position: relative; z-index: 1;" class="form-control mr-4">
                                            <a id="upload5" class="btn upload-btn py-2">{{__('label.upload_files')}}</a>
                                        </div>
                                        <input type="hidden" name="trailer" id="mp3_file_name5" class="form-control">
                                        <label class="text-gray">@if($data->trailer_type == 'server_video'){{{$data->trailer_url}}}@endif</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 s3_trailer_box">
                                <div class="form-group">
                                    <label>{{__('label.trailer')}}<span class="text-danger">*</span></label>
                                    <input type="file" name="trailer" class="form-control" accept=".mp4">
                                    <label class="text-gray mt-2">@if($data->trailer_type == 'server_video'){{$data->trailer_url}}@endif</label>
                                </div>
                            </div>
                            <div class="form-group col-lg-6 trailer_url_box">
                                <label>{{__('label.trailer')}}<span class="text-danger">*</span></label>
                                <input name="trailer_url" value="@if($data->trailer_type != 'server_video'){{{$data->trailer_url}}}@endif" type="url" class="form-control" placeholder="{{__('label.trailer_url')}}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description & Media Card -->
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-photo-film"></i>{{__('label.media')}}</div>
                    <div class="card-body py-2">
                        <div class="form-row">
                            <div class="col-6">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.description')}}</label>
                                            <textarea name="description" class="form-control" rows="5" id="description" placeholder="{{__('label.description_here')}}">{{ $data->description }}</textarea>
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
                                            <img src="{{ $data->thumbnail }}" class="imagePreview" id="imagePreviewThumbnail"/>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="edit_shorts()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
                        <a href="{{route('producer.shorts.index', ['type_id' => $type['id']])}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
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
    <!-- Chunk JS -->
    <script src="{{ asset('/assets/js/plupload.full.min.js')}}"></script>
    <script src="{{ asset('/assets/js/common.js')}}"></script>
    <script>
        function edit_shorts() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var formData = new FormData($("#edit_shorts")[0]);
            $("#dvloader").show();
            $.ajax({
                type: 'POST',
                url: '{{ route("producer.shorts.update", [$data->id])}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'edit_shorts', '{{ route("producer.shorts.index", ["type_id" => $type["id"]]) }}');
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

            var trailer_type = "<?php echo $data->trailer_type; ?>";
            var storage_type = "<?php echo Storage_Type(); ?>";

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
                    $(".trailer_url_box").show();
                    $(".trailer_box").hide();
                    $(".s3_trailer_box").hide();
                }
            });
        });
    </script>
@endsection
