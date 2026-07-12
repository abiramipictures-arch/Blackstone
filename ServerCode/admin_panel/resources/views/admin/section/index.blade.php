@extends('admin.layout.page-app')
@section('page_title', __('label.section'))
@section('tab_title', __('label.section'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.section')}}</h1>

            <div class="row">
                <div class="col-sm-11">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.section')}}</li>
                    </ol>
                </div>
                <div class="col-sm-1 d-flex justify-content-start mb-3">
                    <button type="button" data-toggle="modal" data-target="#sortableModal" onclick="sortableBTN()" class="btn btn-default-white" style="border-radius: 10px;">
                        <i class="fa-solid fa-arrow-up-wide-short fa-xl"></i>
                    </button>
                </div>
            </div>

            <ul class="tabs nav nav-pills custom-tabs inline-tabs mt-2" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="app-tab" onclick="Top_Content('1', '0', '0', '0')" data-is_home_screen="1" data-id="0" data-type="0" data-toggle="pill" href="#app" role="tab" aria-controls="app" aria-selected="true">{{__('label.home')}}</a>
                </li>
                @for ($i = 0; $i < count($type); $i++) 
                <li class="nav-item">
                    <a class="nav-link" id="{{ $type[$i]['name'] }}-tab" onclick="Top_Content('2' , '{{ $type[$i]['id'] }}', '{{ $type[$i]['type'] }}', '0')" data-is_home_screen="2" data-id="{{ $type[$i]['id'] }}" data-type="{{ $type[$i]['type'] }}" data-toggle="pill" href="#{{ $type[$i]['name'] }}" role="tab" aria-controls="{{ $type[$i]['name'] }}" aria-selected="true">{{ $type[$i]['name'] }}</a>
                </li>
                @endfor
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="app" role="tabpanel" aria-labelledby="app-tab">
                    <div class="card custom-border-card">
                        <div class="card-header">
                            <i class="fa-solid fa-plus-circle"></i>{{__('label.add_section')}}
                        </div>                        
                        <form id="save_section" enctype="multipart/form-data">
                            <div class="card-body">
                                <input type="hidden" name="id" value="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control" placeholder="{{__('label.title_here')}}" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>{{__('label.short_title')}}</label>
                                            <input type="text" name="short_title" class="form-control" placeholder="{{__('label.short_title_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-3 video_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.video_type')}}<span class="text-danger">*</span></label>
                                            <select name="video_type" class="form-control" id="video_type">
                                                <option value="">{{__('label.select_type')}}</option>
                                                <option value="1">{{__('label.movies')}}</option>
                                                <option value="2">{{__('label.tv_show')}}</option>
                                                <option value="3">{{__('label.category')}}</option>
                                                <option value="4">{{__('label.language')}}</option>
                                                <option value="5">{{__('label.upcoming_content')}}</option>
                                                <option value="6">{{__('label.channel_content')}}</option>
                                                <option value="7">{{__('label.kids_content')}}</option>
                                                <option value="8">{{__('label.shorts')}}</option>
                                                <option value="101">{{__('label.continue_watching')}}</option>
                                                <option value="102">{{__('label.channel_list')}}</option>
                                                <option value="103">{{__('label.rent_content')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 section_type">
                                        <div class="form-group">
                                            <label>{{__('label.section_type')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="section_type" id="section_type_dynamic" class="custom-control-input" value="0" checked>
                                                    <label class="custom-control-label" for="section_type_dynamic">{{__('label.dynamic')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="section_type" id="section_type_manually" class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="section_type_manually">{{__('label.manually')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="section_type" id="section_type_ai" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="section_type_ai">{{__('label.ai')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.type')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="type_id" id="type_id">
                                                <option value="">{{__('label.select_type')}}</option>
                                                @foreach ($type as $key => $value)
                                                    <option value="{{ $value->id }}" data-type="{{ $value->type }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 sub_video_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.sub_video_type')}}<span class="text-danger">*</span></label>
                                            <select name="sub_video_type" class="form-control" id="sub_video_type">
                                                <option value="">{{__('label.select_type')}}</option>
                                                <option value="1">{{__('label.movies')}}</option>
                                                <option value="2">{{__('label.tv_show')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 category_drop">
                                        <div class="form-group">
                                            <label>{{__('label.category')}}<span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-control" id="category_id">
                                                <option value="0">{{__('label.all_category')}}</option>
                                                @for ($i = 0; $i < count($category); $i++) 
                                                    <option value="{{ $category[$i]['id'] }}">{{ $category[$i]['name'] }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 language_drop">
                                        <div class="form-group">
                                            <label>{{__('label.language')}}<span class="text-danger">*</span></label>
                                            <select name="language_id" class="form-control" id="language_id">
                                                <option value="0">{{__('label.all_language')}}</option>
                                                @for ($i = 0; $i < count($language); $i++) 
                                                    <option value="{{ $language[$i]['id'] }}">{{ $language[$i]['name'] }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 channel_drop">
                                        <div class="form-group">
                                            <label>{{__('label.channel')}}<span class="text-danger">*</span></label>
                                            <select name="channel_id" class="form-control" id="channel_id">
                                                <option value="0">{{__('label.all_channel')}}</option>
                                                @for ($i = 0; $i < count($channel); $i++) 
                                                    <option value="{{ $channel[$i]['id'] }}">{{ $channel[$i]['name'] }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 screen_layout_drop">
                                        <div class="form-group">
                                            <label>{{__('label.screen_layout')}}<span class="text-danger">*</span></label>
                                            <select name="screen_layout" class="form-control" id="screen_layout">
                                                <option value="">{{__('label.select_screen_layout')}}</option>
                                                <option value="landscape">{{__('label.landscape')}}</option>
                                                <option value="portrait">{{__('label.portrait')}}</option>
                                                <option value="square">{{__('label.square')}}</option>
                                                <option value="category">{{__('label.category')}}</option>
                                                <option value="language">{{__('label.language')}}</option>
                                                <option value="channel">{{__('label.channel')}}</option>
                                                <option value="big_landscape">{{__('label.big_landscape')}}</option>
                                                <option value="big_portrait">{{__('label.big_portrait')}}</option>
                                                <option value="index_landscape">{{__('label.index_landscape')}}</option>
                                                <option value="index_portrait">{{__('label.index_portrait')}}</option>
                                                <option value="shorts">{{__('label.shorts')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 no_of_content_drop">
                                        <div class="form-group">
                                            <label>{{__('label.no_of_content')}}<span class="text-danger">*</span></label>
                                            <input type="number" min="1" value="1" name="no_of_content" class="form-control" placeholder="{{__('label.number_of_content_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 order_by_upload_drop">
                                        <div class="form-group">
                                            <label>{{__('label.order_by_upload')}}</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="order_by_upload_asc" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="order_by_upload_asc">{{__('label.asc')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="order_by_upload_desc" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="order_by_upload_desc">{{__('label.desc')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 order_by_view_drop">
                                        <div class="form-group">
                                            <label>{{__('label.order_by_view')}}</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_view" id="order_by_view_asc" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="order_by_view_asc">{{__('label.asc')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_view" id="order_by_view_desc" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="order_by_view_desc">{{__('label.desc')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 premium_video_drop">
                                        <div class="form-group">
                                            <label>{{__('label.premium_video')}}</label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="premium_video" id="premium_video_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="premium_video_yes">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="premium_video" id="premium_video_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="premium_video_no">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 view_all_drop">
                                        <div class="form-group">
                                            <label>{{__('label.view_all')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="view_all_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="view_all_yes">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="view_all_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="view_all_no">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 content_drop">
                                        <div class="form-group">
                                            <label>{{__('label.content')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="content[]" id="content_ids" multiple style="width:100%!important;">
                                                <option value="">{{__('label.select_contents')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer mt-0">
                                <button type="button" class="btn btn-default mw-120" onclick="save_section()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.save')}}</button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </form>
                    </div>
                    <div class="after-add-more"></div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_section')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="edit_content_section" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id" value="">
                                <input type="hidden" name="is_home_screen" id="edit_is_home_screen" value="">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.title')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="title" id="edit_title" class="form-control" placeholder="{{__('label.title_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.short_title')}}</label>
                                            <input type="text" name="short_title" id="edit_short_title" class="form-control" placeholder="{{__('label.short_title_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_video_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.video_type')}}<span class="text-danger">*</span></label>
                                            <select name="video_type" class="form-control" id="edit_video_type">
                                                <option value="">{{__('label.select_type')}}</option>
                                                <option value="1">{{__('label.movies')}}</option>
                                                <option value="2">{{__('label.tv_show')}}</option>
                                                <option value="3">{{__('label.category')}}</option>
                                                <option value="4">{{__('label.language')}}</option>
                                                <option value="5">{{__('label.upcoming_content')}}</option>
                                                <option value="6">{{__('label.channel_content')}}</option>
                                                <option value="7">{{__('label.kids_content')}}</option>
                                                <option value="8">{{__('label.shorts')}}</option>
                                                <option value="101">{{__('label.continue_watching')}}</option>
                                                <option value="102">{{__('label.channel_list')}}</option>
                                                <option value="103">{{__('label.rent_content')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_section_type">
                                        <div class="form-group">
                                            <label>{{__('label.section_type')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="section_type" id="edit_section_type_dynamic" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="edit_section_type_dynamic">{{__('label.dynamic')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="section_type" id="edit_section_type_manually" class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="edit_section_type_manually">{{__('label.manually')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="section_type" id="edit_section_type_ai" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="edit_section_type_ai">{{__('label.ai')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.type')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="type_id" id="edit_type_id">
                                                <option value="">{{__('label.select_type')}}</option>
                                                @foreach ($type as $key => $value)
                                                <option value="{{ $value->id }}" data-type="{{ $value->type }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_sub_video_type_drop">
                                        <div class="form-group">
                                            <label>{{__('label.sub_video_type')}}<span class="text-danger">*</span></label>
                                            <select name="sub_video_type" class="form-control" id="edit_sub_video_type">
                                                <option value="">{{__('label.select_type')}}</option>
                                                <option value="1">{{__('label.movies')}}</option>
                                                <option value="2">{{__('label.tv_show')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_category_drop">
                                        <div class="form-group">
                                            <label>{{__('label.category')}}<span class="text-danger">*</span></label>
                                            <select name="category_id" class="form-control" id="edit_category_id" style="width:100%!important;">
                                                <option value="0">{{__('label.all_category')}}</option>
                                                @for ($i = 0; $i < count($category); $i++) 
                                                    <option value="{{ $category[$i]['id'] }}">{{ $category[$i]['name'] }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_language_drop">
                                        <div class="form-group">
                                            <label>{{__('label.language')}}<span class="text-danger">*</span></label>
                                            <select name="language_id" class="form-control" id="edit_language_id" style="width:100%!important;">
                                                <option value="0">{{__('label.all_language')}}</option>
                                                @for ($i = 0; $i < count($language); $i++) 
                                                    <option value="{{ $language[$i]['id'] }}">{{ $language[$i]['name'] }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_channel_drop">
                                        <div class="form-group">
                                            <label>{{__('label.channel')}}<span class="text-danger">*</span></label>
                                            <select name="channel_id" class="form-control" id="edit_channel_id" style="width:100%!important;">
                                                <option value="0">{{__('label.all_channel')}}</option>
                                                @for ($i = 0; $i < count($channel); $i++) 
                                                    <option value="{{ $channel[$i]['id'] }}">{{ $channel[$i]['name'] }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_screen_layout_drop">
                                        <div class="form-group">
                                            <label>{{__('label.screen_layout')}}<span class="text-danger">*</span></label>
                                            <select name="screen_layout" class="form-control" id="edit_screen_layout">
                                                <option value="">{{__('label.select_screen_layout')}}</option>
                                                <option value="landscape">{{__('label.landscape')}}</option>
                                                <option value="portrait">{{__('label.portrait')}}</option>
                                                <option value="square">{{__('label.square')}}</option>
                                                <option value="category">{{__('label.category')}}</option>
                                                <option value="language">{{__('label.language')}}</option>
                                                <option value="channel">{{__('label.channel')}}</option>
                                                <option value="big_landscape">{{__('label.big_landscape')}}</option>
                                                <option value="big_portrait">{{__('label.big_portrait')}}</option>
                                                <option value="index_landscape">{{__('label.index_landscape')}}</option>
                                                <option value="index_portrait">{{__('label.index_portrait')}}</option>
                                                <option value="shorts">{{__('label.shorts')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 edit_no_of_content_drop">
                                        <div class="form-group">
                                            <label>{{__('label.no_of_content')}}<span class="text-danger">*</span></label>
                                            <input type="number" min="1" name="no_of_content" id="edit_no_of_content" class="form-control" placeholder="{{__('label.number_of_content_here')}}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 edit_order_by_upload_drop">
                                        <div class="form-group">
                                            <label>{{__('label.order_by_upload')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="edit_order_by_upload_asc" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_order_by_upload_asc">{{__('label.asc')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_upload" id="edit_order_by_upload_desc" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="edit_order_by_upload_desc">{{__('label.desc')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 edit_order_by_view_drop">
                                        <div class="form-group">
                                            <label>{{__('label.order_by_view')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_view" id="edit_order_by_view_asc" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_order_by_view_asc">{{__('label.asc')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="order_by_view" id="edit_order_by_view_desc" class="custom-control-input" value="2">
                                                    <label class="custom-control-label" for="edit_order_by_view_desc">{{__('label.desc')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 edit_premium_video_drop">
                                        <div class="form-group">
                                            <label>{{__('label.premium_video')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="premium_video" id="edit_premium_video_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_premium_video_yes">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="premium_video" id="edit_premium_video_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="edit_premium_video_no">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 edit_view_all_drop">
                                        <div class="form-group">
                                            <label>{{__('label.view_all')}}<span class="text-danger">*</span></label>
                                            <div class="radio-group">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="edit_view_all_yes" class="custom-control-input" value="1" checked>
                                                    <label class="custom-control-label" for="edit_view_all_yes">{{__('label.yes')}}</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" name="view_all" id="edit_view_all_no" class="custom-control-input" value="0">
                                                    <label class="custom-control-label" for="edit_view_all_no">{{__('label.no')}}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 edit_content_drop">
                                        <div class="form-group">
                                            <label>{{__('label.content')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="content[]" id="edit_content_ids" multiple style="width:100%!important;">
                                                <option value="">{{__('label.select_contents')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="update_section()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- sortableModal -->
            <div class="modal fade" id="sortableModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="sortableModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title w-100 text-center" id="sortableModalLabel">{{__('label.section_sortable_list')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="contentListId"></div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form id="save_section_sortable" enctype="multipart/form-data">
                                <input id="outputvalues" type="hidden" name="ids" value="" />
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="w-100 text-center">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_section_sortable()">
                                        <i class="fa-solid fa-floppy-disk fa-sm mr-1"></i>{{__('label.save')}}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Sortable -->
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script>
        $("#category_id").select2();
        $("#language_id").select2();
        $("#channel_id").select2();
        $("#content_ids").select2({ placeholder: "{{__('label.select_contents')}}", allowClear: true, width: '100%' });        
        $("#edit_category_id").select2({ dropdownParent: $('#exampleModal') });
        $("#edit_language_id").select2({ dropdownParent: $('#exampleModal') });
        $("#edit_channel_id").select2({ dropdownParent: $('#exampleModal') });
        $("#edit_content_ids").select2({ dropdownParent: $('#exampleModal'), placeholder: "{{__('label.select_contents')}}", allowClear: true, width: '100%' });

        $(".type_drop").hide();
        $(".sub_video_type_drop").hide();
        $(".category_drop").hide();
        $(".language_drop").hide();
        $(".channel_drop").hide();
        $(".screen_layout_drop").hide();
        $(".no_of_content_drop").hide();
        $(".order_by_upload_drop").hide();
        $(".order_by_view_drop").hide();
        $(".premium_video_drop").hide();
        $(".view_all_drop").hide();
        $(".content_drop").hide();

        var tab = $("ul.tabs li a.active");
        var is_home_screen = tab.data("is_home_screen");
        var top_type_id = 0;
        var top_type_type = 0;
        $('.nav-item a').on('click', function() {
            is_home_screen = $(this).data("is_home_screen");
            top_type_id = $(this).data("id");
            top_type_type = $(this).data("type"); 
        });

        // ********** ADD SECTION **********
        // Section Type
        $("input[name='section_type']").change(function() {

            $("#video_type").children().removeAttr("selected");
            $(".type_drop").hide();
            $(".sub_video_type_drop").hide();
            $(".category_drop").hide();
            $(".language_drop").hide();
            $(".channel_drop").hide();
            $(".screen_layout_drop").hide();
            $(".no_of_content_drop").hide();
            $(".order_by_upload_drop").hide();
            $(".order_by_view_drop").hide();
            $(".premium_video_drop").hide();
            $(".view_all_drop").hide();
            $(".content_drop").hide();

            if(is_home_screen == 2) {

                var section_type = $("input[name='section_type']:checked").val();
                $("#content_ids").empty();

                if(section_type == 1) { // Manually

                    $(".screen_layout_drop").show();

                    if(top_type_type == 1 || top_type_type == 2) {
                        Get_Content();

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                            ["category", "language", "channel", "shorts"]
                        );

                        $("#content_ids").children().removeAttr("selected");
                        $(".section_type").show();
                        $(".content_drop").show();
                    } else if(top_type_type == 5 || top_type_type == 6 || top_type_type == 7) {

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                            ["category", "language", "channel", "shorts"]
                        );

                        $("#content_ids").children().removeAttr("selected");
                        $(".section_type").show();
                        $(".content_drop").show();
                        $(".sub_video_type_drop").show();
                        if(top_type_type == 6){
                            $(".channel_drop").show();
                        } else {
                            $(".channel_drop").hide();
                        }
                    } else if(top_type_type == 8) {
                        Get_Content();

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["shorts"],
                            ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                        );

                        $("#screen_layout").children().removeAttr("selected");
                        $("#screen_layout option[value='landscape']").hide();
                        $("#screen_layout option[value='portrait']").hide();
                        $("#screen_layout option[value='square']").hide();
                        $("#screen_layout option[value='category']").hide();
                        $("#screen_layout option[value='language']").hide();
                        $("#screen_layout option[value='channel']").hide();
                        $("#screen_layout option[value='big_landscape']").hide();
                        $("#screen_layout option[value='big_portrait']").hide();
                        $("#screen_layout option[value='index_landscape']").hide();
                        $("#screen_layout option[value='index_portrait']").hide();
                        $("#screen_layout option[value='shorts']").show();

                        $("#content_ids").children().removeAttr("selected");
                        $(".section_type").show();
                        $(".content_drop").show();
                    } else {
                        $(".sub_video_type_drop").hide();
                        $(".type_drop").hide();
                        $(".category_drop").hide();
                        $(".language_drop").hide();
                        $(".channel_drop").hide();
                        $(".screen_layout_drop").hide();
                        $(".no_of_content_drop").hide();
                        $(".order_by_upload_drop").hide();
                        $(".order_by_view_drop").hide();
                        $(".premium_video_drop").hide();
                        $(".view_all_drop").hide();
                        $(".content_drop").hide();
                    }
                } else if(section_type == 0) { // Dynamic

                    $(".type_drop").hide();
                    $("#content_ids").children().removeAttr("selected");
                    $(".content_drop").hide();
                    $(".video_type_drop").hide();
                    $(".category_drop").show();
                    $(".language_drop").show();
                    if(top_type_type == 5 || top_type_type == 6 || top_type_type == 7){
                        $(".sub_video_type_drop").show();
                    } else {
                        $(".sub_video_type_drop").hide();
                    }
                    if(top_type_type == 1){
                        $(".premium_video_drop").show();
                    } else {
                        $(".premium_video_drop").hide();
                    }                        
                    if(top_type_type == 6){
                        $(".channel_drop").show();
                    } else {
                        $(".channel_drop").hide();
                    }
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").show();
                    $(".order_by_view_drop").show();
                    $(".view_all_drop").show();

                    if(top_type_type == 8){

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["shorts"],
                            ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        );
                    } else {

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                            ["category", "language", "channel", "shorts"]
                        );
                    }
                } else if(section_type == 2) { // AI

                    $(".type_drop").hide();
                    $("#content_ids").children().removeAttr("selected");
                    $(".content_drop").hide();
                    $(".video_type_drop").hide();
                    $(".category_drop").hide();
                    $(".language_drop").hide();
                    if(top_type_type == 5 || top_type_type == 6 || top_type_type == 7){
                        $(".sub_video_type_drop").show();
                    } else {
                        $(".sub_video_type_drop").hide();
                    }
                    if(top_type_type == 1){
                        $(".premium_video_drop").show();
                    } else {
                        $(".premium_video_drop").hide();
                    }
                    if(top_type_type == 6){
                        $(".channel_drop").show();
                    } else {
                        $(".channel_drop").hide();
                    }
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").hide();
                    $(".order_by_view_drop").hide();
                    $(".view_all_drop").show();

                    if(top_type_type == 8){

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["shorts"],
                            ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        );
                    } else {

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                            ["category", "language", "channel", "shorts"]
                        );
                    }
                }       
            }
        });
        // Video_Type 
        $("#video_type").change(function() {

            var video_type = $(this).children("option:selected").val();
            var SectionType = $("input[name='section_type']:checked").val();
            $("#content_ids").empty();

            if(SectionType == 1) { // Manually

                $("#type_id").children().removeAttr("selected");
                $(".type_drop").show();
                $(".screen_layout_drop").show();

                if(video_type == 1 || video_type == 2) {

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#content_ids").children().removeAttr("selected");
                    $(".section_type").show();
                    $(".content_drop").show();
                    $(".premium_video_drop").hide();
                    $(".sub_video_type_drop").hide();
                    $(".channel_drop").hide();

                    $("#type_id").children().removeAttr("selected");
                    if(video_type == 1) {
                        $("#type_id option[data-type=1]").show();
                        $("#type_id option[data-type=2]").hide();
                    } else {
                        $("#type_id option[data-type=1]").hide();
                        $("#type_id option[data-type=2]").show();
                    }
                    $("#type_id option[data-type=5]").hide();
                    $("#type_id option[data-type=6]").hide();
                    $("#type_id option[data-type=7]").hide();
                    $("#type_id option[data-type=8]").hide();
                } else if(video_type == 3 || video_type == 4 || video_type == 102){
                    Get_Content();

                    if(video_type == 3){

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["category"],
                            ["landscape", "portrait", "square", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else if(video_type == 4){

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["language"],
                            ["landscape", "portrait", "square", "category", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else {

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["channel"],
                            ["landscape", "portrait", "square", "category", "language", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    }

                    $("#content_ids").children().removeAttr("selected");
                    $(".section_type").show();
                    $(".content_drop").show();
                    $(".premium_video_drop").hide();
                    $(".sub_video_type_drop").hide();
                    $(".channel_drop").hide();
                    $(".type_drop").hide();
                } else if(video_type == 5 || video_type == 6 || video_type == 7) {

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#content_ids").children().removeAttr("selected");
                    $(".content_drop").hide();
                    $(".premium_video_drop").hide();
                    $(".sub_video_type_drop").hide();
                    $(".channel_drop").hide();
                    $(".section_type").show();

                    $("#type_id").children().removeAttr("selected");
                    $("#type_id option[data-type=1]").hide();
                    $("#type_id option[data-type=2]").hide();
                    if(video_type == 5){
                        $("#type_id option[data-type=5]").show();
                        $("#type_id option[data-type=6]").hide();
                        $("#type_id option[data-type=7]").hide();
                    } else if(video_type == 6){
                        $("#type_id option[data-type=5]").hide();
                        $("#type_id option[data-type=6]").show();
                        $("#type_id option[data-type=7]").hide();
                    } else if(video_type == 7){
                        $("#type_id option[data-type=5]").hide();
                        $("#type_id option[data-type=6]").hide();
                        $("#type_id option[data-type=7]").show();
                    }
                    $("#type_id option[data-type=8]").hide();
                } else if(video_type == 101) {

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait"],
                        ["category", "language", "channel", "index_landscape", "index_portrait", "shorts"]
                    );

                    $("#content_ids").children().removeAttr("selected");
                    $(".type_drop").hide();
                    $(".section_type").hide();
                    $(".premium_video_drop").hide();
                    $(".sub_video_type_drop").hide();
                    $(".channel_drop").hide();
                    $(".content_drop").hide();
                   
                } else if(video_type == 103) {

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#content_ids").children().removeAttr("selected");
                    $(".channel_drop").hide();
                    $(".section_type").show();
                    $("#type_id").children().removeAttr("selected");
                    $("#type_id option[data-type=1]").show();
                    $("#type_id option[data-type=2]").show();
                    $("#type_id option[data-type=5]").hide();
                    $("#type_id option[data-type=6]").show();
                    $("#type_id option[data-type=7]").show();
                    $("#type_id option[data-type=8]").hide();
                } else if(video_type == 8) {
                    
                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["shorts"]
                    );

                    $("#content_ids").children().removeAttr("selected");
                    $(".section_type").show();
                    $(".content_drop").show();
                    $(".premium_video_drop").hide();
                    $(".sub_video_type_drop").hide();
                    $(".channel_drop").hide();

                    $("#type_id").children().removeAttr("selected");
                    $("#type_id option[data-type=1]").hide();
                    $("#type_id option[data-type=2]").hide();
                    $("#type_id option[data-type=5]").hide();
                    $("#type_id option[data-type=6]").hide();
                    $("#type_id option[data-type=7]").hide();
                    $("#type_id option[data-type=8]").show();
                } else {

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").hide();
                    $(".category_drop").hide();
                    $(".language_drop").hide();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").hide();
                    $(".no_of_content_drop").hide();
                    $(".order_by_upload_drop").hide();
                    $(".order_by_view_drop").hide();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").hide();
                }
            } else if(SectionType == 0) { // Dynamic

                if(video_type == 1 || video_type == 2){

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").show();
                    $(".category_drop").show();
                    $(".language_drop").show();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").show();
                    $(".order_by_view_drop").show();
                    if(video_type == 1) {
                        $(".premium_video_drop").show();
                    } else {
                        $(".premium_video_drop").hide();
                    }
                    $(".view_all_drop").show();
                    $(".section_type").show();

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#content_ids").children().removeAttr("selected");
                    $("#type_id").children().removeAttr("selected");
                    if(video_type == 1){
                        $("#type_id option[data-type=1]").show();
                        $("#type_id option[data-type=2]").hide();
                    } else {
                        $("#type_id option[data-type=1]").hide();
                        $("#type_id option[data-type=2]").show();
                    }
                    $("#type_id option[data-type=5]").hide();
                    $("#type_id option[data-type=6]").hide();
                    $("#type_id option[data-type=7]").hide();
                    $("#type_id option[data-type=8]").hide();

                } else if(video_type == 3 || video_type == 4 || video_type == 102){

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").hide();
                    $(".category_drop").hide();
                    $(".language_drop").hide();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").hide();
                    $(".order_by_view_drop").hide();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").show();
                    $(".section_type").show();

                    $("#content_ids").children().removeAttr("selected");
                    if(video_type == 3){

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["category"],
                            ["landscape", "portrait", "square", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else if(video_type == 4){

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["language"],
                            ["landscape", "portrait", "square", "category", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else {

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["channel"],
                            ["landscape", "portrait", "square", "category", "language", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    }
                } else if(video_type == 5 || video_type == 6 || video_type == 7){

                    $("#sub_video_type").children().removeAttr("selected");
                    $(".sub_video_type_drop").show();
                    $(".type_drop").show();
                    $(".category_drop").show();
                    $(".language_drop").show();
                    $("#channel_id").val("0").trigger('change');
                    if(video_type == 5 || video_type == 7){
                        $(".channel_drop").hide();
                    } else {
                        $(".channel_drop").show();
                    }
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").show();
                    $(".order_by_view_drop").show();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").show();
                    $(".section_type").show();
                    $(".content_drop").hide();
                    $("#content_ids").children().removeAttr("selected");

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#type_id").children().removeAttr("selected");
                    $("#type_id option[data-type=1]").hide();
                    $("#type_id option[data-type=2]").hide();
                    if(video_type == 5){
                        $("#type_id option[data-type=5]").show();
                        $("#type_id option[data-type=6]").hide();
                        $("#type_id option[data-type=7]").hide();
                    } else if(video_type == 6){
                        $("#type_id option[data-type=5]").hide();
                        $("#type_id option[data-type=6]").show();
                        $("#type_id option[data-type=7]").hide();
                    } else if(video_type == 7){
                        $("#type_id option[data-type=5]").hide();
                        $("#type_id option[data-type=6]").hide();
                        $("#type_id option[data-type=7]").show();
                    }
                    $("#type_id option[data-type=8]").hide();
                } else if(video_type == 101){

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").hide();
                    $(".category_drop").hide();
                    $(".language_drop").hide();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").hide();
                    $(".order_by_view_drop").hide();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").show();
                    $(".section_type").hide();
                    $("#content_ids").children().removeAttr("selected");

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait"],
                        ["category", "language", "channel", "index_landscape", "index_portrait", "shorts"]
                    );
                } else if(video_type == 103){

                    $("#sub_video_type").children().removeAttr("selected");
                    $(".sub_video_type_drop").hide();
                    $(".type_drop").show();
                    $(".category_drop").show();
                    $(".language_drop").show();
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").show();
                    $(".order_by_view_drop").show();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").show();
                    $(".section_type").show();
                    $("#channel_id").val("0").trigger('change');
                    $("#content_ids").children().removeAttr("selected");

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#type_id").children().removeAttr("selected");
                    $("#type_id option[data-type=1]").show();
                    $("#type_id option[data-type=2]").show();
                    $("#type_id option[data-type=5]").hide();
                    $("#type_id option[data-type=6]").show();
                    $("#type_id option[data-type=7]").show();
                    $("#type_id option[data-type=8]").hide();
                } else if(video_type == 8){

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").show();
                    $(".category_drop").show();
                    $(".language_drop").show();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".order_by_upload_drop").show();
                    $(".order_by_view_drop").show();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").show();
                    $(".section_type").show();

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["shorts"],
                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                    );

                    $("#content_ids").children().removeAttr("selected");
                    $("#type_id").children().removeAttr("selected");
                    $("#type_id option[data-type=1]").hide();
                    $("#type_id option[data-type=2]").hide();
                    $("#type_id option[data-type=5]").hide();
                    $("#type_id option[data-type=6]").hide();
                    $("#type_id option[data-type=7]").hide();
                    $("#type_id option[data-type=8]").show();
                } else {

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").hide();
                    $(".category_drop").hide();
                    $(".language_drop").hide();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").hide();
                    $(".no_of_content_drop").hide();
                    $(".order_by_upload_drop").hide();
                    $(".order_by_view_drop").hide();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").hide();
                }
            } else if(SectionType == 2) { // AI

                $(".category_drop").hide();
                $(".language_drop").hide();
                $(".order_by_upload_drop").hide();
                $(".order_by_view_drop").hide();
                if(video_type == 1 || video_type == 2){

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").show();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    if(video_type == 1) {
                        $(".premium_video_drop").show();
                    } else {
                        $(".premium_video_drop").hide();
                    }
                    $(".view_all_drop").show();
                    $(".section_type").show();

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#content_ids").children().removeAttr("selected");
                    $("#type_id").children().removeAttr("selected");
                    if(video_type == 1){
                        $("#type_id option[data-type=1]").show();
                        $("#type_id option[data-type=2]").hide();
                    } else {
                        $("#type_id option[data-type=1]").hide();
                        $("#type_id option[data-type=2]").show();
                    }
                    $("#type_id option[data-type=5]").hide();
                    $("#type_id option[data-type=6]").hide();
                    $("#type_id option[data-type=7]").hide();
                    $("#type_id option[data-type=8]").hide();

                } else if(video_type == 3 || video_type == 4 || video_type == 102){

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").hide();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").show();
                    $(".section_type").show();

                    $("#content_ids").children().removeAttr("selected");
                    if(video_type == 3){

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["category"],
                            ["landscape", "portrait", "square", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else if(video_type == 4){

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["language"],
                            ["landscape", "portrait", "square", "category", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else {

                        // Screen Layout Hide-Show
                        toggleScreenLayout(
                            ["channel"],
                            ["landscape", "portrait", "square", "category", "language", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    }
                } else if(video_type == 5 || video_type == 6 || video_type == 7){

                    $("#sub_video_type").children().removeAttr("selected");
                    $(".sub_video_type_drop").show();
                    $(".type_drop").show();
                    $("#channel_id").val("0").trigger('change');
                    if(video_type == 5 || video_type == 7){
                        $(".channel_drop").hide();
                    } else {
                        $(".channel_drop").show();
                    }
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").show();
                    $(".section_type").show();
                    $(".content_drop").hide();
                    $("#content_ids").children().removeAttr("selected");

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#type_id").children().removeAttr("selected");
                    $("#type_id option[data-type=1]").hide();
                    $("#type_id option[data-type=2]").hide();
                    if(video_type == 5){
                        $("#type_id option[data-type=5]").show();
                        $("#type_id option[data-type=6]").hide();
                        $("#type_id option[data-type=7]").hide();
                    } else if(video_type == 6){
                        $("#type_id option[data-type=5]").hide();
                        $("#type_id option[data-type=6]").show();
                        $("#type_id option[data-type=7]").hide();
                    } else if(video_type == 7){
                        $("#type_id option[data-type=5]").hide();
                        $("#type_id option[data-type=6]").hide();
                        $("#type_id option[data-type=7]").show();
                    }
                    $("#type_id option[data-type=8]").hide();
                } else if(video_type == 101){

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").hide();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").show();
                    $(".section_type").hide();
                    $("#content_ids").children().removeAttr("selected");

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait"],
                        ["category", "language", "channel", "index_landscape", "index_portrait", "shorts"]
                    );
                } else if(video_type == 103){

                    $("#sub_video_type").children().removeAttr("selected");
                    $(".sub_video_type_drop").hide();
                    $(".type_drop").show();
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").show();
                    $(".section_type").show();
                    $("#channel_id").val("0").trigger('change');
                    $("#content_ids").children().removeAttr("selected");

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#type_id").children().removeAttr("selected");
                    $("#type_id option[data-type=1]").show();
                    $("#type_id option[data-type=2]").show();
                    $("#type_id option[data-type=5]").hide();
                    $("#type_id option[data-type=6]").show();
                    $("#type_id option[data-type=7]").show();
                    $("#type_id option[data-type=8]").hide();
                } else if(video_type == 8){

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").show();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").show();
                    $(".no_of_content_drop").show();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").show();
                    $(".section_type").show();

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["shorts"],
                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                    );

                    $("#content_ids").children().removeAttr("selected");
                    $("#type_id").children().removeAttr("selected");
                    $("#type_id option[data-type=1]").hide();
                    $("#type_id option[data-type=2]").hide();
                    $("#type_id option[data-type=5]").hide();
                    $("#type_id option[data-type=6]").hide();
                    $("#type_id option[data-type=7]").hide();
                    $("#type_id option[data-type=8]").show();
                } else {

                    $(".sub_video_type_drop").hide();
                    $(".type_drop").hide();
                    $(".channel_drop").hide();
                    $(".screen_layout_drop").hide();
                    $(".no_of_content_drop").hide();
                    $(".premium_video_drop").hide();
                    $(".view_all_drop").hide();
                }
            }
        });
        // Sub Video_Type 
        $("#sub_video_type").change(function() {

            var sub_video_type = $(this).children("option:selected").val();
            var Sactiontype = $("input[name='section_type']:checked").val();

            if(Sactiontype == 1) { // Manually
                $(".premium_video_drop").hide();
                Get_Content();
            } else if(Sactiontype == 0) { // Dynamic
                $(".premium_video_drop").hide();
                if(sub_video_type == 1){
                    $(".premium_video_drop").show();
                }
            } else if(Sactiontype == 2){ // AI
                $(".premium_video_drop").hide();
                if(sub_video_type == 1){
                    $(".premium_video_drop").show();
                }
            }
        });
        // Type 
        $("#type_id").change(function() {

            var selected_type = $(this).find("option:selected").data('type');            
            var sectiontype = $("input[name='section_type']:checked").val();
            $("#sub_video_type").children().removeAttr("selected");
            if(sectiontype == 1) { // Manually
                $("#content_ids").empty();
                $(".content_drop").show();
            }

            if(selected_type == 5 || selected_type == 6 || selected_type == 7) {

                $(".sub_video_type_drop").show();
                $(".premium_video_drop").hide();
                $(".channel_drop").hide();
                if (selected_type == 6) {
                    $(".channel_drop").show();
                }
            } else if(selected_type == 1 || selected_type == 2) {

                $(".sub_video_type_drop").hide();
                $(".channel_drop").hide();
                $(".premium_video_drop").hide();
                if(sectiontype == 1) { // Manually
                    Get_Content();
                } else if(sectiontype == 0) { // Dynamic
                    if(selected_type == 1) {
                        $(".premium_video_drop").show();
                    }
                } else if(sectiontype == 2) { // AI
                    if(selected_type == 1) {
                        $(".premium_video_drop").show();
                    }
                } 
            } else if(selected_type == 8) {

                $(".sub_video_type_drop").hide();
                $(".channel_drop").hide();
                $(".premium_video_drop").hide();
                if(sectiontype == 1) { // Manually
                    Get_Content();
                }
            }
        });
        // Channel
        $("#channel_id").change(function() {
            var Sactiontype = $("input[name='section_type']:checked").val();
            if(Sactiontype == 1) { // Manually
                Get_Content();
            }
        });
        // Get Conntent For Add Manually Section
        function Get_Content() {

            if(is_home_screen == 1){
                var video_type = $("#video_type").val() || "";
                var type_id = $("#type_id").val() || "";
                var type_type = $("#type_id option:selected").data('type') || "";
            } else {
                var video_type = top_type_type || "";
                var type_id = top_type_id || "";
                var type_type = top_type_type || "";
            }
            var sub_video_type = $("#sub_video_type").val() || "";
            var channel_id = $("#channel_id").val() || "";

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("admin.section.content") }}',
                data: {
                    video_type: video_type,
                    type_id: type_id,
                    sub_video_type: sub_video_type,
                    channel_id: channel_id,
                    type_type: type_type,
                },
                success: function(resp) {
                    $("#content_ids").empty();
                    for (var i = 0; i < resp.result.length; i++) {
                        $('#content_ids').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);          
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        // Save Section
        function save_section() {
			var Demo_Mode = '<?php echo Demo_Mode(); ?>';
            if(Demo_Mode == 1){

                var formData = new FormData($("#save_section")[0]);
                formData.append('is_home_screen', is_home_screen);
                formData.append('top_type_id', top_type_id);
                formData.append('top_type_type', top_type_type);

                $("#dvloader").show();
                $.ajax({
                    type:'POST',
                    url:'{{ route("admin.section.store") }}',
                    data:formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success:function(resp){
                        $("#dvloader").hide();
                        get_responce_message(resp, 'save_section', '{{ route("admin.section.index") }}');
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        $("#dvloader").hide();
                        toastr.error(errorThrown, textStatus);
                    }
                });
            } else {
                showError();
            }
		}

        // ********** List Section **********
        if(is_home_screen == 1) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("admin.section.content.data") }}',
                data: {
                    is_home_screen: is_home_screen,
                    top_type_id: 0,
                },
                success: function(resp) {
                    renderSectionList(resp.result);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        function Top_Content(is_home_screen, top_type_id, type_type) {

            document.getElementById("save_section").reset();
            $("#category_id").val(0).trigger("change"); 
            $("#language_id").val(0).trigger("change");
            $("#channel_id").val(0).trigger("change");

            if(is_home_screen == 1){

                $(".sub_video_type_drop").hide();
                $(".type_drop").hide();
                $(".category_drop").hide();
                $(".language_drop").hide();
                $(".channel_drop").hide();
                $(".screen_layout_drop").hide();
                $(".no_of_content_drop").hide();
                $(".order_by_upload_drop").hide();
                $(".order_by_view_drop").hide();
                $(".premium_video_drop").hide();
                $(".view_all_drop").hide();
                $("#content_ids").children().removeAttr("selected");
                $(".content_drop").hide();

                $(".video_type_drop").show();
                $("#video_type").children().removeAttr("selected");
            } else if(is_home_screen == 2){

                $(".sub_video_type_drop").hide();
                $(".section_type").show();
                $(".type_drop").hide();
                $(".category_drop").hide();
                $(".language_drop").hide();
                $(".channel_drop").hide();
                $(".screen_layout_drop").hide();
                $(".no_of_content_drop").hide();
                $(".order_by_upload_drop").hide();
                $(".order_by_view_drop").hide();
                $(".premium_video_drop").hide();
                $(".view_all_drop").hide();
                $(".content_drop").hide();

                $(".video_type_drop").hide();
                if(type_type == 5 || type_type == 6 || type_type == 7){
                    $(".sub_video_type_drop").show();
                } else {
                    $(".sub_video_type_drop").hide();
                }
                if(type_type == 1){
                    $(".premium_video_drop").show();
                } else {
                    $(".premium_video_drop").hide();
                }

                $(".category_drop").show();
                $(".language_drop").show();
                if(type_type == 6){
                    $(".channel_drop").show();
                } else {
                    $(".channel_drop").hide();
                }
                $(".screen_layout_drop").show();
                $(".no_of_content_drop").show();
                $(".order_by_upload_drop").show();
                $(".order_by_view_drop").show();
                $(".view_all_drop").show();

                if(type_type == 8){

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["shorts"],
                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                    );
                } else {

                    // Screen Layout Hide-Show
                    toggleScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );
                }
            }

            $("#dvloader").show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("admin.section.content.data") }}',
                data: {
                    is_home_screen: is_home_screen,
                    top_type_id: top_type_id,
                },
                success: function(resp) {
                    $("#dvloader").hide();
                    renderSectionList(resp.result);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        };

        // ********** RENDER SECTION LIST **********
        const videoTypeLabels = {
            1: "{{__('label.movies')}}", 2: "{{__('label.tv_show')}}",
            3: "{{__('label.category')}}", 4: "{{__('label.language')}}",
            5: "{{__('label.upcoming_content')}}", 6: "{{__('label.channel_content')}}",
            7: "{{__('label.kids_content')}}", 8: "{{__('label.shorts')}}",
            101: "{{__('label.continue_watching')}}", 102: "{{__('label.channel_list')}}",
            103: "{{__('label.rent_content')}}"
        };
        const screenLayoutLabels = {
            "landscape": "{{__('label.landscape')}}", "portrait": "{{__('label.portrait')}}",
            "square": "{{__('label.square')}}", "category": "{{__('label.category')}}",
            "language": "{{__('label.language')}}", "channel": "{{__('label.channel')}}",
            "big_landscape": "{{__('label.big_landscape')}}", "big_portrait": "{{__('label.big_portrait')}}",
            "index_landscape": "{{__('label.index_landscape')}}", "index_portrait": "{{__('label.index_portrait')}}",
            "shorts": "{{__('label.shorts')}}"
        };
        function renderSectionList(results) {
            var container = $('.after-add-more');
            container.html('');
            if (results.length === 0) {
                // container.html(`<div class="sc-empty"><i class="fa-solid fa-layer-group"></i>{{__('label.no_data_found')}}</div>`);
                return;
            }
            var html = `<div class="sc-list-wrap card custom-border-card">
                <div class="sc-list-header mb-2">
                    <i class="fa-solid fa-layer-group primary-color fa-xl"></i>{{__('label.section_list')}}
                </div>`;
            for (var i = 0; i < results.length; i++) {
                var r           = results[i];
                var video_type  = videoTypeLabels[r.video_type] || '-';
                var layout      = screenLayoutLabels[r.screen_layout] || '';
                var statusBtn   = r.status == 1
                    ? `<div class="d-flex flex-column align-items-center mr-2" style="gap:4px;">
                           <label id="${r.id}" class="status-toggle status-on" onclick="change_status(${r.id})"><span class="status-toggle-track"><span class="status-toggle-thumb"></span></span></label>
                           <small id="text_${r.id}" class="font-weight-bold text-success">{{__('label.show')}}</small>
                       </div>`
                    : `<div class="d-flex flex-column align-items-center mr-2" style="gap:4px;">
                           <label id="${r.id}" class="status-toggle status-off" onclick="change_status(${r.id})"><span class="status-toggle-track"><span class="status-toggle-thumb"></span></span></label>
                           <small id="text_${r.id}" class="font-weight-bold text-danger">{{__('label.hide')}}</small>
                       </div>`;
                html += `<div class="sc-item-group mx-2">
                    <div class="sc-item">
                        <span class="sc-item-num">${i + 1}</span>
                        <div class="sc-item-main">
                            <span class="sc-item-title">${r.title}</span>
                            ${r.short_title ? `<span class="sc-item-sub">${r.short_title}</span>` : ''}
                        </div>
                        <span class="sc-badge">${video_type}</span>
                        ${layout ? `<span class="sc-badge">${layout}</span>` : ''}
                        <div class="sc-item-actions">
                            ${statusBtn}
                            <button type="button" data-toggle="modal" data-target="#exampleModal" class="edit-delete-btn" onclick="edit_section(${r.id})" title="{{__('label.update')}}"><i class="fa-solid fa-pen-to-square fa-xl"></i></button>
                            <button type="button" class="edit-delete-btn" onclick="delete_section(${r.id})" title="{{__('label.delete')}}"><i class="fa-solid fa-trash-can fa-xl"></i></button>
                        </div>
                    </div>`;
                if (r.section_type == 1 && r.content && r.content.length > 0) {
                    html += `<div class="sc-content-chips">`;
                    for (var j = 0; j < r.content.length; j++) {
                        html += `<span class="sc-chip">${r.content[j]}</span>`;
                    }
                    html += `</div>`;
                }
                html += `</div>`;
            }
            html += `</div>`;
            container.html(html);
        }

        // ********** EDIT SECTION **********
        function edit_section(id){

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("admin.section.content.edit") }}',
                data: {
                    id: id,
                },
                success: function(resp) {

                    if(resp.result != null){

                        if(resp.result.section_type == 1) { // Manually

                            $("#edit_content_ids").empty();
                            for (var i = 0; i < resp.result.content_data.length; i++) {
                                $('#edit_content_ids').append(`<option value="${resp.result.content_data[i].id}">${resp.result.content_data[i].name}</option>`);          
                            }

                            $("#edit_id").val(resp.result.id);
                            $("#edit_is_home_screen").val(resp.result.is_home_screen);
                            $("#edit_title").val(resp.result.title);
                            $("#edit_short_title").val(resp.result.short_title);
                            $("#edit_video_type").val(resp.result.video_type).attr("selected","selected");
                            $("#edit_sub_video_type").val(resp.result.sub_video_type).attr("selected","selected");
                            $("#edit_type_id").val(resp.result.type_id).attr("selected","selected");
                            
                            $('#edit_channel_id').val(resp.result.channel_id).trigger('change.select2');
                            $("#edit_screen_layout").val(resp.result.screen_layout).attr("selected","selected");
                            $("#edit_content_ids").val(resp.result.content).trigger("change");
                            if(resp.result.section_type == 1){
                                $("#edit_section_type_dynamic").prop('checked', false);
                                $("#edit_section_type_manually").prop('checked', true);
                            } else {
                                $("#edit_section_type_manually").prop('checked', false);
                                $("#edit_section_type_dynamic").prop('checked', true);
                            }

                            $(".edit_category_drop").hide();
                            $(".edit_language_drop").hide();
                            $(".edit_order_by_upload_drop").hide();
                            $(".edit_order_by_view_drop").hide();
                            $(".edit_premium_video_drop").hide();
                            $(".edit_view_all_drop").hide();
                            $(".edit_no_of_content_drop").hide();
                            $(".edit_channel_drop").hide();
                            $(".edit_sub_video_type_drop").hide();
                            $(".edit_type_drop").hide();
                            $(".edit_screen_layout_drop").show();

                            if(resp.result.is_home_screen == 1) {

                                $(".edit_video_type_drop").show();
                                $(".edit_video_type_drop option[value='1']").show();
                                $(".edit_video_type_drop option[value='2']").show();
                                $(".edit_video_type_drop option[value='3']").show();
                                $(".edit_video_type_drop option[value='4']").show();
                                $(".edit_video_type_drop option[value='5']").show();
                                $(".edit_video_type_drop option[value='6']").show();
                                $(".edit_video_type_drop option[value='7']").show();
                                $(".edit_video_type_drop option[value='8']").show();
                                $(".edit_video_type_drop option[value='101']").show();
                                $(".edit_video_type_drop option[value='102']").show();
                                $(".edit_video_type_drop option[value='103']").show();
                                $(".edit_section_type").show();
                                $(".edit_content_drop").show();

                                if(resp.result.video_type == 1 || resp.result.video_type == 2){

                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").show();
                                    
                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    );

                                    if(resp.result.video_type == 1){
                                        $("#edit_type_id option[data-type=1]").show();
                                        $("#edit_type_id option[data-type=2]").hide();
                                    } else {
                                        $("#edit_type_id option[data-type=1]").hide();
                                        $("#edit_type_id option[data-type=2]").show();
                                    }
                                    $("#edit_type_id option[data-type=5]").hide();
                                    $("#edit_type_id option[data-type=6]").hide();
                                    $("#edit_type_id option[data-type=7]").hide();
                                    $("#edit_type_id option[data-type=8]").hide();
                                } else if(resp.result.video_type == 3 || resp.result.video_type == 4 || resp.result.video_type == 102){

                                    if(resp.result.video_type == 3){

                                        // Screen Layout Hide-Show
                                        toggleEditScreenLayout(
                                            ["category"],
                                            ["landscape", "portrait", "square", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                                        );
                                    } else if(resp.result.video_type == 4){

                                        // Screen Layout Hide-Show
                                        toggleEditScreenLayout(
                                            ["language"],
                                            ["landscape", "portrait", "square", "category", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                                        );
                                    } else {

                                        // Screen Layout Hide-Show
                                        toggleEditScreenLayout(
                                            ["channel"],
                                            ["landscape", "portrait", "square", "category", "language", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                                        );
                                    }
                                } else if(resp.result.video_type == 5 || resp.result.video_type == 6 || resp.result.video_type == 7){

                                    $(".edit_sub_video_type_drop").show();
                                    $(".edit_type_drop").show();
                                    if(resp.result.video_type == 6){
                                        $(".edit_channel_drop").show();
                                    } else {
                                        $(".edit_channel_drop").hide();
                                    }
                                    
                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    );

                                    $("#edit_type_id option[data-type=1]").hide();
                                    $("#edit_type_id option[data-type=2]").hide();
                                    if(resp.result.video_type == 5){
                                        $("#edit_type_id option[data-type=5]").show();
                                        $("#edit_type_id option[data-type=6]").hide();
                                        $("#edit_type_id option[data-type=7]").hide();
                                    } else if(resp.result.video_type == 6) {
                                        $("#edit_type_id option[data-type=5]").hide();
                                        $("#edit_type_id option[data-type=6]").show();
                                        $("#edit_type_id option[data-type=7]").hide();
                                    } else if(resp.result.video_type == 7) {
                                        $("#edit_type_id option[data-type=5]").hide();
                                        $("#edit_type_id option[data-type=6]").hide();
                                        $("#edit_type_id option[data-type=7]").show();
                                    }
                                    $("#edit_type_id option[data-type=8]").hide();
                                } else if(resp.result.video_type == 103){

                                    var edit_type = $("#edit_type_id").find("option:selected").data('type');

                                    $(".edit_type_drop").show();
                                    if(edit_type == 6 || edit_type == 7){

                                        $(".edit_sub_video_type_drop").show();
                                        if(edit_type == 6) {
                                            $(".edit_channel_drop").show();
                                        } 
                                    } else {
                                        $(".edit_sub_video_type_drop").hide();
                                        $(".edit_channel_drop").hide();
                                    }

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    );

                                    $("#edit_type_id option[data-type=1]").show();
                                    $("#edit_type_id option[data-type=2]").show();
                                    $("#edit_type_id option[data-type=5]").hide();
                                    $("#edit_type_id option[data-type=6]").show();
                                    $("#edit_type_id option[data-type=7]").show();
                                    $("#edit_type_id option[data-type=8]").show();
                                } else if(resp.result.video_type == 8){

                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["shorts"]
                                    );

                                    $("#edit_type_id option[data-type=1]").hide();
                                    $("#edit_type_id option[data-type=2]").hide();
                                    $("#edit_type_id option[data-type=5]").hide();
                                    $("#edit_type_id option[data-type=6]").hide();
                                    $("#edit_type_id option[data-type=7]").hide();
                                    $("#edit_type_id option[data-type=8]").show(); 
                                } else {

                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").hide();
                                    $(".edit_category_drop").hide();
                                    $(".edit_language_drop").hide();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").hide();
                                    $(".edit_no_of_content_drop").hide();
                                    $(".edit_order_by_upload_drop").hide();
                                    $(".edit_order_by_view_drop").hide();
                                    $(".edit_premium_video_drop").hide();
                                    $(".edit_view_all_drop").hide();
                                }
                            } else if(resp.result.is_home_screen == 2){

                                $(".edit_content_drop").show();
                                $(".edit_type_drop").hide();
                                $(".edit_video_type_drop").hide();
                                if(resp.result.video_type == 5 || resp.result.video_type == 6 || resp.result.video_type == 7){
                                    $(".edit_sub_video_type_drop").show();
                                } else {
                                    $(".edit_sub_video_type_drop").hide();
                                }
                                if(resp.result.video_type == 6){
                                    $(".edit_channel_drop").show();
                                } else {
                                    $(".edit_channel_drop").hide();
                                }
                                $(".edit_premium_video_drop").hide();
                                $(".edit_screen_layout_drop").show();

                                if(resp.result.video_type == 8){

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["shorts"],
                                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                                    );
                                } else {

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    );
                                }
                            } else {
                                $(".edit_video_type_drop").hide();
                            }
                        } else if(resp.result.section_type == 0) { // Dynamic

                            $("#edit_id").val(resp.result.id);
                            $("#edit_is_home_screen").val(resp.result.is_home_screen);
                            $("#edit_title").val(resp.result.title);
                            $("#edit_short_title").val(resp.result.short_title);
                            $("#edit_no_of_content").val(resp.result.no_of_content);
                            $("#edit_video_type").val(resp.result.video_type).attr("selected","selected");
                            $("#edit_sub_video_type").val(resp.result.sub_video_type).attr("selected","selected");
                            $("#edit_type_id").val(resp.result.type_id).attr("selected","selected");
                            $('#edit_category_id').val(resp.result.category_id).trigger('change');
                            $('#edit_language_id').val(resp.result.language_id).trigger('change');
                            $('#edit_channel_id').val(resp.result.channel_id).trigger('change');
                            $("#edit_screen_layout").val(resp.result.screen_layout).attr("selected","selected");
                            if(resp.result.section_type == 1){
                                $("#edit_section_type_dynamic").prop('checked', false);
                                $("#edit_section_type_manually").prop('checked', true);
                            } else {
                                $("#edit_section_type_manually").prop('checked', false);
                                $("#edit_section_type_dynamic").prop('checked', true);
                            }
                            if(resp.result.order_by_upload == 1){
                                $("#edit_order_by_upload_asc").prop('checked', true);
                                $("#edit_order_by_upload_desc").prop('checked', false);
                            } else if(resp.result.order_by_upload == 2) {
                                $("#edit_order_by_upload_asc").prop('checked', false);
                                $("#edit_order_by_upload_desc").prop('checked', true);
                            }
                            if(resp.result.order_by_view == 1){
                                $("#edit_order_by_view_asc").prop('checked', true);
                                $("#edit_order_by_view_desc").prop('checked', false);
                            } else {
                                $("#edit_order_by_view_asc").prop('checked', false);
                                $("#edit_order_by_view_desc").prop('checked', true);
                            }
                            if(resp.result.premium_video == 1){
                                $("#edit_premium_video_no").prop('checked', false);
                                $("#edit_premium_video_yes").prop('checked', true);
                            } else {
                                $("#edit_premium_video_yes").prop('checked', false);
                                $("#edit_premium_video_no").prop('checked', true);
                            }
                            if(resp.result.view_all == 1){
                                $("#edit_view_all_no").prop('checked', false);
                                $("#edit_view_all_yes").prop('checked', true);
                            } else {
                                $("#edit_view_all_yes").prop('checked', false);
                                $("#edit_view_all_no").prop('checked', true);
                            }

                            if(resp.result.is_home_screen == 1){

                                $(".edit_video_type_drop").show();
                                $(".edit_video_type_drop option[value='1']").show();
                                $(".edit_video_type_drop option[value='2']").show();
                                $(".edit_video_type_drop option[value='3']").show();
                                $(".edit_video_type_drop option[value='4']").show();
                                $(".edit_video_type_drop option[value='5']").show();
                                $(".edit_video_type_drop option[value='6']").show();
                                $(".edit_video_type_drop option[value='7']").show();
                                $(".edit_video_type_drop option[value='8']").show();
                                $(".edit_video_type_drop option[value='101']").show();
                                $(".edit_video_type_drop option[value='102']").show();
                                $(".edit_video_type_drop option[value='103']").show();
                                $(".edit_section_type").show(); 
                                $(".edit_content_drop").hide();

                                if(resp.result.video_type == 1 || resp.result.video_type == 2){
    
                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").show();
                                    $(".edit_category_drop").show();
                                    $(".edit_language_drop").show();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    $(".edit_order_by_upload_drop").show();
                                    $(".edit_order_by_view_drop").show();
                                    if(resp.result.video_type == 1){
                                        $(".edit_premium_video_drop").show();
                                    } else {
                                        $(".edit_premium_video_drop").hide();
                                    }
                                    $(".edit_view_all_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    );
    
                                    if(resp.result.video_type == 1){
                                        $("#edit_type_id option[data-type=1]").show();
                                        $("#edit_type_id option[data-type=2]").hide();
                                    } else {
                                        $("#edit_type_id option[data-type=1]").hide();
                                        $("#edit_type_id option[data-type=2]").show();
                                    }
                                    $("#edit_type_id option[data-type=5]").hide();
                                    $("#edit_type_id option[data-type=6]").hide();
                                    $("#edit_type_id option[data-type=7]").hide();
                                    $("#edit_type_id option[data-type=8]").hide();
    
                                } else if(resp.result.video_type == 3 || resp.result.video_type == 4 || resp.result.video_type == 102){

                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").hide();
                                    $(".edit_category_drop").hide();
                                    $(".edit_language_drop").hide();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    $(".edit_order_by_upload_drop").hide();
                                    $(".edit_order_by_view_drop").hide();
                                    $(".edit_premium_video_drop").hide();
                                    $(".edit_view_all_drop").show();

                                    if(resp.result.video_type == 3){

                                        // Screen Layout Hide-Show
                                        toggleEditScreenLayout(
                                            ["category"],
                                            ["landscape", "portrait", "square", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                                        );
                                    } else if(resp.result.video_type == 4){
                                        
                                        // Screen Layout Hide-Show
                                        toggleEditScreenLayout(
                                            ["language"],
                                            ["landscape", "portrait", "square", "category", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                                        );
                                    } else {

                                        // Screen Layout Hide-Show
                                        toggleEditScreenLayout(
                                            ["channel"],
                                            ["landscape", "portrait", "square", "category", "language", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                                        );
                                    }
                                } else if(resp.result.video_type == 5 || resp.result.video_type == 6 || resp.result.video_type == 7){

                                    $(".edit_sub_video_type_drop").show();
                                    $(".edit_type_drop").show();
                                    $(".edit_category_drop").show();
                                    $(".edit_language_drop").show();
                                    if(resp.result.video_type == 6){
                                        $(".edit_channel_drop").show();
                                    } else {
                                        $(".edit_channel_drop").hide();
                                    }
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    $(".edit_order_by_upload_drop").show();
                                    $(".edit_order_by_view_drop").show();
                                    if(resp.result.sub_video_type == 1 ){
                                        $(".edit_premium_video_drop").show();
                                    } else {
                                        $(".edit_premium_video_drop").hide();
                                    }
                                    $(".edit_view_all_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    );

                                    $("#edit_type_id option[data-type=1]").hide();
                                    $("#edit_type_id option[data-type=2]").hide();
                                    if(resp.result.video_type == 5){
                                        $("#edit_type_id option[data-type=5]").show();
                                        $("#edit_type_id option[data-type=6]").hide();
                                        $("#edit_type_id option[data-type=7]").hide();
                                    } else if(resp.result.video_type == 6) {
                                        $("#edit_type_id option[data-type=5]").hide();
                                        $("#edit_type_id option[data-type=6]").show();
                                        $("#edit_type_id option[data-type=7]").hide();
                                    } else if(resp.result.video_type == 7) {
                                        $("#edit_type_id option[data-type=5]").hide();
                                        $("#edit_type_id option[data-type=6]").hide();
                                        $("#edit_type_id option[data-type=7]").show();
                                    }
                                    $("#edit_type_id option[data-type=8]").hide();
                                } else if(resp.result.video_type == 101){

                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_section_type").hide();
                                    $(".edit_type_drop").hide();
                                    $(".edit_category_drop").hide();
                                    $(".edit_language_drop").hide();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    $(".edit_order_by_upload_drop").hide();
                                    $(".edit_order_by_view_drop").hide();
                                    $(".edit_premium_video_drop").hide();
                                    $(".edit_view_all_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait"],
                                        ["category", "language", "channel", "index_landscape", "index_portrait", "shorts"]
                                    );    
                                } else if(resp.result.video_type == 103){

                                    var edit_type = $("#edit_type_id").find("option:selected").data('type');

                                    $(".edit_type_drop").show();
                                    $(".edit_category_drop").show();
                                    $(".edit_language_drop").show();
                                    if(edit_type == 6 || edit_type == 7){
                                        $(".edit_sub_video_type_drop").show();
                                        if(edit_type == 6) {
                                            $(".edit_channel_drop").show();
                                        } 
                                    } else {
                                        $(".edit_sub_video_type_drop").hide();
                                        $(".edit_channel_drop").hide();
                                    }
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    $(".edit_order_by_upload_drop").show();
                                    $(".edit_order_by_view_drop").show();
                                    if(resp.result.sub_video_type == 1 ){
                                        $(".edit_premium_video_drop").show();
                                    } else {
                                        $(".edit_premium_video_drop").hide();
                                    }
                                    $(".edit_view_all_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    ); 

                                    $("#edit_type_id option[data-type=1]").show();
                                    $("#edit_type_id option[data-type=2]").show();
                                    $("#edit_type_id option[data-type=5]").hide();
                                    $("#edit_type_id option[data-type=6]").show();
                                    $("#edit_type_id option[data-type=7]").show();
                                    $("#edit_type_id option[data-type=8]").hide();
                                } else if(resp.result.video_type == 8){

                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").show();
                                    $(".edit_category_drop").show();
                                    $(".edit_language_drop").show();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    $(".edit_order_by_upload_drop").show();
                                    $(".edit_order_by_view_drop").show();
                                    $(".edit_premium_video_drop").hide();
                                    $(".edit_view_all_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["shorts"],
                                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                                    ); 

                                    $("#edit_type_id option[data-type=1]").hide();
                                    $("#edit_type_id option[data-type=2]").hide();
                                    $("#edit_type_id option[data-type=5]").hide();
                                    $("#edit_type_id option[data-type=6]").hide();
                                    $("#edit_type_id option[data-type=7]").hide();
                                    $("#edit_type_id option[data-type=8]").show();
                                } else {
    
                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").hide();
                                    $(".edit_category_drop").hide();
                                    $(".edit_language_drop").hide();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").hide();
                                    $(".edit_no_of_content_drop").hide();
                                    $(".edit_order_by_upload_drop").hide();
                                    $(".edit_order_by_view_drop").hide();
                                    $(".edit_premium_video_drop").hide();
                                    $(".edit_view_all_drop").hide();
                                }

                            } else if(resp.result.is_home_screen == 2){

                                $(".edit_content_drop").hide();
                                $(".edit_video_type_drop").hide();
                                if(resp.result.video_type == 5 || resp.result.video_type == 6 || resp.result.video_type == 7){
                                    $(".edit_sub_video_type_drop").show();
                                } else {
                                    $(".edit_sub_video_type_drop").hide();
                                }
                                $(".edit_type_drop").hide();
                                $(".edit_category_drop").show();
                                $(".edit_language_drop").show();
                                if(resp.result.video_type == 6){
                                    $(".edit_channel_drop").show();
                                } else {
                                    $(".edit_channel_drop").hide();
                                }
                                $(".edit_screen_layout_drop").show();
                                $(".edit_no_of_content_drop").show();
                                $(".edit_order_by_upload_drop").show();
                                $(".edit_order_by_view_drop").show();
                                if(resp.result.video_type == 1 || resp.result.sub_video_type == 1 ){
                                    $(".edit_premium_video_drop").show();
                                } else {
                                    $(".edit_premium_video_drop").hide();
                                }
                                $(".edit_view_all_drop").show();

                                if(resp.result.video_type == 8){

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["shorts"],
                                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                                    );
                                } else {

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    );
                                }
                            } else {
                                $(".edit_video_type_drop").hide();
                            }
                        } else if(resp.result.section_type == 2) { // AI

                            $("#edit_id").val(resp.result.id);
                            $("#edit_is_home_screen").val(resp.result.is_home_screen);
                            $("#edit_title").val(resp.result.title);
                            $("#edit_short_title").val(resp.result.short_title);
                            $("#edit_no_of_content").val(resp.result.no_of_content);
                            $("#edit_video_type").val(resp.result.video_type).attr("selected","selected");
                            $("#edit_sub_video_type").val(resp.result.sub_video_type).attr("selected","selected");
                            $("#edit_type_id").val(resp.result.type_id).attr("selected","selected");
                            $('#edit_channel_id').val(resp.result.channel_id).trigger('change');
                            $("#edit_screen_layout").val(resp.result.screen_layout).attr("selected","selected");
                            if(resp.result.section_type == 1){
                                $("#edit_section_type_dynamic").prop('checked', false);
                                $("#edit_section_type_manually").prop('checked', true);
                                $("#edit_section_type_ai").prop('checked', false);
                            } else if(resp.result.section_type == 0){
                                $("#edit_section_type_dynamic").prop('checked', true);
                                $("#edit_section_type_manually").prop('checked', false);
                                $("#edit_section_type_ai").prop('checked', false);
                            } else if(resp.result.section_type == 2){
                                $("#edit_section_type_manually").prop('checked', false);
                                $("#edit_section_type_dynamic").prop('checked', false);
                                $("#edit_section_type_ai").prop('checked', true);                                
                            }
                            if(resp.result.premium_video == 1){
                                $("#edit_premium_video_no").prop('checked', false);
                                $("#edit_premium_video_yes").prop('checked', true);
                            } else {
                                $("#edit_premium_video_yes").prop('checked', false);
                                $("#edit_premium_video_no").prop('checked', true);
                            }
                            if(resp.result.view_all == 1){
                                $("#edit_view_all_no").prop('checked', false);
                                $("#edit_view_all_yes").prop('checked', true);
                            } else {
                                $("#edit_view_all_yes").prop('checked', false);
                                $("#edit_view_all_no").prop('checked', true);
                            }

                            $(".edit_category_drop").hide();
                            $(".edit_language_drop").hide();
                            $(".edit_order_by_upload_drop").hide();
                            $(".edit_order_by_view_drop").hide();
                            if(resp.result.is_home_screen == 1){

                                $(".edit_video_type_drop").show();
                                $(".edit_video_type_drop option[value='1']").show();
                                $(".edit_video_type_drop option[value='2']").show();
                                $(".edit_video_type_drop option[value='3']").show();
                                $(".edit_video_type_drop option[value='4']").show();
                                $(".edit_video_type_drop option[value='5']").show();
                                $(".edit_video_type_drop option[value='6']").show();
                                $(".edit_video_type_drop option[value='7']").show();
                                $(".edit_video_type_drop option[value='8']").show();
                                $(".edit_video_type_drop option[value='101']").show();
                                $(".edit_video_type_drop option[value='102']").show();
                                $(".edit_video_type_drop option[value='103']").show();
                                $(".edit_section_type").show(); 
                                $(".edit_content_drop").hide();

                                if(resp.result.video_type == 1 || resp.result.video_type == 2){
    
                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").show();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    if(resp.result.video_type == 1){
                                        $(".edit_premium_video_drop").show();
                                    } else {
                                        $(".edit_premium_video_drop").hide();
                                    }
                                    $(".edit_view_all_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    );
    
                                    if(resp.result.video_type == 1){
                                        $("#edit_type_id option[data-type=1]").show();
                                        $("#edit_type_id option[data-type=2]").hide();
                                    } else {
                                        $("#edit_type_id option[data-type=1]").hide();
                                        $("#edit_type_id option[data-type=2]").show();
                                    }
                                    $("#edit_type_id option[data-type=5]").hide();
                                    $("#edit_type_id option[data-type=6]").hide();
                                    $("#edit_type_id option[data-type=7]").hide();
                                    $("#edit_type_id option[data-type=8]").hide();
    
                                } else if(resp.result.video_type == 3 || resp.result.video_type == 4 || resp.result.video_type == 102){

                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").hide();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    $(".edit_premium_video_drop").hide();
                                    $(".edit_view_all_drop").show();

                                    if(resp.result.video_type == 3){

                                        // Screen Layout Hide-Show
                                        toggleEditScreenLayout(
                                            ["category"],
                                            ["landscape", "portrait", "square", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                                        );
                                    } else if(resp.result.video_type == 4){
                                        
                                        // Screen Layout Hide-Show
                                        toggleEditScreenLayout(
                                            ["language"],
                                            ["landscape", "portrait", "square", "category", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                                        );
                                    } else {

                                        // Screen Layout Hide-Show
                                        toggleEditScreenLayout(
                                            ["channel"],
                                            ["landscape", "portrait", "square", "category", "language", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                                        );
                                    }
                                } else if(resp.result.video_type == 5 || resp.result.video_type == 6 || resp.result.video_type == 7){

                                    $(".edit_sub_video_type_drop").show();
                                    $(".edit_type_drop").show();
                                    if(resp.result.video_type == 6){
                                        $(".edit_channel_drop").show();
                                    } else {
                                        $(".edit_channel_drop").hide();
                                    }
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    if(resp.result.sub_video_type == 1 ){
                                        $(".edit_premium_video_drop").show();
                                    } else {
                                        $(".edit_premium_video_drop").hide();
                                    }
                                    $(".edit_view_all_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    );

                                    $("#edit_type_id option[data-type=1]").hide();
                                    $("#edit_type_id option[data-type=2]").hide();
                                    if(resp.result.video_type == 5){
                                        $("#edit_type_id option[data-type=5]").show();
                                        $("#edit_type_id option[data-type=6]").hide();
                                        $("#edit_type_id option[data-type=7]").hide();
                                    } else if(resp.result.video_type == 6) {
                                        $("#edit_type_id option[data-type=5]").hide();
                                        $("#edit_type_id option[data-type=6]").show();
                                        $("#edit_type_id option[data-type=7]").hide();
                                    } else if(resp.result.video_type == 7) {
                                        $("#edit_type_id option[data-type=5]").hide();
                                        $("#edit_type_id option[data-type=6]").hide();
                                        $("#edit_type_id option[data-type=7]").show();
                                    }
                                    $("#edit_type_id option[data-type=8]").hide();
                                } else if(resp.result.video_type == 101){

                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_section_type").hide();
                                    $(".edit_type_drop").hide();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    $(".edit_premium_video_drop").hide();
                                    $(".edit_view_all_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait"],
                                        ["category", "language", "channel", "index_landscape", "index_portrait", "shorts"]
                                    );    
                                } else if(resp.result.video_type == 103){

                                    var edit_type = $("#edit_type_id").find("option:selected").data('type');

                                    $(".edit_type_drop").show();
                                    if(edit_type == 6 || edit_type == 7){
                                        $(".edit_sub_video_type_drop").show();
                                        if(edit_type == 6) {
                                            $(".edit_channel_drop").show();
                                        } 
                                    } else {
                                        $(".edit_sub_video_type_drop").hide();
                                        $(".edit_channel_drop").hide();
                                    }
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    if(resp.result.sub_video_type == 1 ){
                                        $(".edit_premium_video_drop").show();
                                    } else {
                                        $(".edit_premium_video_drop").hide();
                                    }
                                    $(".edit_view_all_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    ); 

                                    $("#edit_type_id option[data-type=1]").show();
                                    $("#edit_type_id option[data-type=2]").show();
                                    $("#edit_type_id option[data-type=5]").hide();
                                    $("#edit_type_id option[data-type=6]").show();
                                    $("#edit_type_id option[data-type=7]").show();
                                    $("#edit_type_id option[data-type=8]").hide();
                                } else if(resp.result.video_type == 8){

                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").show();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").show();
                                    $(".edit_no_of_content_drop").show();
                                    $(".edit_premium_video_drop").hide();
                                    $(".edit_view_all_drop").show();

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["shorts"],
                                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                                    ); 

                                    $("#edit_type_id option[data-type=1]").hide();
                                    $("#edit_type_id option[data-type=2]").hide();
                                    $("#edit_type_id option[data-type=5]").hide();
                                    $("#edit_type_id option[data-type=6]").hide();
                                    $("#edit_type_id option[data-type=7]").hide();
                                    $("#edit_type_id option[data-type=8]").show();
                                } else {
    
                                    $(".edit_sub_video_type_drop").hide();
                                    $(".edit_type_drop").hide();
                                    $(".edit_channel_drop").hide();
                                    $(".edit_screen_layout_drop").hide();
                                    $(".edit_no_of_content_drop").hide();
                                    $(".edit_premium_video_drop").hide();
                                    $(".edit_view_all_drop").hide();
                                }

                            } else if(resp.result.is_home_screen == 2){

                                $(".edit_section_type").show();
                                $(".edit_content_drop").hide();
                                $(".edit_video_type_drop").hide();
                                if(resp.result.video_type == 5 || resp.result.video_type == 6 || resp.result.video_type == 7){
                                    $(".edit_sub_video_type_drop").show();
                                } else {
                                    $(".edit_sub_video_type_drop").hide();
                                }
                                $(".edit_type_drop").hide();
                                if(resp.result.video_type == 6){
                                    $(".edit_channel_drop").show();
                                } else {
                                    $(".edit_channel_drop").hide();
                                }
                                $(".edit_screen_layout_drop").show();
                                $(".edit_no_of_content_drop").show();
                                if(resp.result.video_type == 1 || resp.result.sub_video_type == 1 ){
                                    $(".edit_premium_video_drop").show();
                                } else {
                                    $(".edit_premium_video_drop").hide();
                                }
                                $(".edit_view_all_drop").show();

                                if(resp.result.video_type == 8){

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["shorts"],
                                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                                    );
                                } else {

                                    // Screen Layout Hide-Show
                                    toggleEditScreenLayout(
                                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                                        ["category", "language", "channel", "shorts"]
                                    );
                                }
                            } else {
                                $(".edit_video_type_drop").hide();
                            }
                        }
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        // Section Type
        $(".edit_section_type input[name='section_type']").change(function() {

            $("#edit_video_type").children().removeAttr("selected");
            $(".edit_sub_video_type_drop").hide();
            $(".edit_type_drop").hide();
            $(".edit_category_drop").hide();
            $(".edit_language_drop").hide();
            $(".edit_channel_drop").hide();
            $(".edit_screen_layout_drop").hide();
            $(".edit_no_of_content_drop").hide();
            $(".edit_order_by_upload_drop").hide();
            $(".edit_order_by_view_drop").hide();
            $(".edit_premium_video_drop").hide();
            $(".edit_view_all_drop").hide();
            $(".edit_content_drop").hide();

            if(is_home_screen == 2) {

                var section_type =  $(".edit_section_type input[name='section_type']:checked").val();
                $("#edit_content_ids").empty();

                if(section_type == 1) { // Manually

                    $(".edit_screen_layout_drop").show(); 

                    if(top_type_type == 1 || top_type_type == 2) {
                        Get_Edit_Content();

                        $("#edit_screen_layout").children().removeAttr("selected");
                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                            ["category", "language", "channel", "shorts"]
                        );

                        $("#edit_content_ids").children().removeAttr("selected");
                        $(".edit_section_type").show();
                        $(".edit_content_drop").show();
                    } else if(top_type_type == 5 || top_type_type == 6 || top_type_type == 7) {

                        $("#edit_screen_layout").children().removeAttr("selected");
                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                            ["category", "language", "channel", "shorts"]
                        );

                        $("#edit_content_ids").children().removeAttr("selected");
                        $(".edit_section_type").show();
                        $(".edit_content_drop").show();
                        $("#edit_sub_video_type").children().removeAttr("selected");
                        $("#edit_sub_video_type option:first").attr('selected','selected');
                        $(".edit_sub_video_type_drop").show();
                        if(top_type_type == 6){
                            $(".edit_channel_drop").show();
                        } else {
                            $(".edit_channel_drop").hide();
                        }
                    } else if(top_type_type == 8) {
                        Get_Edit_Content();

                        $("#edit_screen_layout").children().removeAttr("selected");
                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["shorts"],
                            ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                        );

                        $("#edit_content_ids").children().removeAttr("selected");
                        $(".edit_section_type").show();
                        $(".edit_content_drop").show();
                    } else {
                        $(".edit_sub_video_type_drop").hide();
                        $(".edit_type_drop").hide();
                        $(".edit_category_drop").hide();
                        $(".edit_language_drop").hide();
                        $(".edit_channel_drop").hide();
                        $(".edit_screen_layout_drop").hide();
                        $(".edit_no_of_content_drop").hide();
                        $(".edit_order_by_upload_drop").hide();
                        $(".edit_order_by_view_drop").hide();
                        $(".edit_premium_video_drop").hide();
                        $(".edit_view_all_drop").hide();
                        $(".edit_content_drop").hide();
                    }
                } else if(section_type == 0) { // Dynamic

                    $(".edit_type_drop").hide();
                    $("#edit_content_ids").children().removeAttr("selected");
                    $(".edit_content_drop").hide();
                    $(".edit_video_type_drop").hide();
                    $(".edit_category_drop").show();
                    $(".edit_language_drop").show();
                    $("#edit_sub_video_type").children().removeAttr("selected");
                    $("#edit_sub_video_type option:first").attr('selected','selected');
                    if(top_type_type == 5 || top_type_type == 6 || top_type_type == 7){
                        $(".edit_sub_video_type_drop").show();
                    } else {
                        $(".edit_sub_video_type_drop").hide();
                    }
                    if(top_type_type == 1){
                        $(".edit_premium_video_drop").show();
                    } else {
                        $(".edit_premium_video_drop").hide();
                    }
                    if(top_type_type == 6){
                        $(".edit_channel_drop").show();
                    } else {
                        $(".edit_channel_drop").hide();
                    }
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_order_by_upload_drop").show();
                    $(".edit_order_by_view_drop").show();
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    if(top_type_type == 8){

                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["shorts"],
                            ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                        );
                    } else {

                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                            ["category", "language", "channel", "shorts"]
                        );
                    }
                } else if(section_type == 2) { // AI

                    $(".edit_type_drop").hide();
                    $("#edit_content_ids").children().removeAttr("selected");
                    $(".edit_content_drop").hide();
                    $(".edit_video_type_drop").hide();
                    $(".edit_category_drop").hide();
                    $(".edit_language_drop").hide();
                    $("#edit_sub_video_type").children().removeAttr("selected");
                    $("#edit_sub_video_type option:first").attr('selected','selected');
                    if(top_type_type == 5 || top_type_type == 6 || top_type_type == 7){
                        $(".edit_sub_video_type_drop").show();
                    } else {
                        $(".edit_sub_video_type_drop").hide();
                    }
                    if(top_type_type == 1){
                        $(".edit_premium_video_drop").show();
                    } else {
                        $(".edit_premium_video_drop").hide();
                    }
                    if(top_type_type == 6){
                        $(".edit_channel_drop").show();
                    } else {
                        $(".edit_channel_drop").hide();
                    }
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_order_by_upload_drop").hide();
                    $(".edit_order_by_view_drop").hide();
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    if(top_type_type == 8){

                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["shorts"],
                            ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                        );
                    } else {

                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                            ["category", "language", "channel", "shorts"]
                        );
                    }
                }          
            }
        });
        // Video_Type 
        $("#edit_video_type").change(function() {

            var video_type = $(this).children("option:selected").val();
            var SectionType = $(".edit_section_type input[name='section_type']:checked").val();
            $(".edit_section_type").show();

            if(SectionType == 1) { // Manually

                $(".edit_category_drop").hide();
                $(".edit_language_drop").hide();
                $(".edit_order_by_upload_drop").hide();
                $(".edit_order_by_view_drop").hide();
                $(".edit_premium_video_drop").hide();
                $(".edit_view_all_drop").hide();
                $(".edit_no_of_content_drop").hide();
                $(".edit_channel_drop").hide();
                $(".edit_sub_video_type_drop").hide();
                $(".edit_type_drop").hide();
                $("#edit_type_id").val("").change();
                $(".edit_screen_layout_drop").show();
                $("#edit_sub_video_type option:first").attr('selected','selected');
                $(".edit_content_drop").show();

                if(video_type == 1 || video_type == 2) {

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    if(video_type == 1){
                        $("#edit_type_id option[data-type=1]").show();
                        $("#edit_type_id option[data-type=2]").hide();
                    } else {
                        $("#edit_type_id option[data-type=1]").hide();
                        $("#edit_type_id option[data-type=2]").show();
                    }
                    $("#edit_type_id option[data-type=5]").hide();
                    $("#edit_type_id option[data-type=6]").hide();
                    $("#edit_type_id option[data-type=7]").hide();
                    $("#edit_type_id option[data-type=8]").hide();

                } else if(video_type == 3 || video_type == 4 || video_type == 102){

                    $("#edit_screen_layout").children().removeAttr("selected");
                    if(video_type == 3){
                        
                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["category"],
                            ["landscape", "portrait", "square", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else if(video_type == 4){

                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["language"],
                            ["landscape", "portrait", "square", "category", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else {

                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["channel"],
                            ["landscape", "portrait", "square", "category", "language", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    }
                    Get_Edit_Content();

                } else if(video_type == 5 || video_type == 6 || video_type == 7){

                    $(".edit_sub_video_type_drop").show();
                    $(".edit_type_drop").show();
                    if(video_type == 6){
                        $(".edit_channel_drop").show();
                    } else {
                        $(".edit_channel_drop").hide();
                    }

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#edit_type_id option[data-type=1]").hide();
                    $("#edit_type_id option[data-type=2]").hide();
                    if(video_type == 5){
                        $("#edit_type_id option[data-type=5]").show();
                        $("#edit_type_id option[data-type=6]").hide();
                        $("#edit_type_id option[data-type=7]").hide();
                    } else if(video_type == 6) {
                        $("#edit_type_id option[data-type=5]").hide();
                        $("#edit_type_id option[data-type=6]").show();
                        $("#edit_type_id option[data-type=7]").hide();
                    } else if(video_type == 7) {
                        $("#edit_type_id option[data-type=5]").hide();
                        $("#edit_type_id option[data-type=6]").hide();
                        $("#edit_type_id option[data-type=7]").show();
                    }
                    $("#edit_type_id option[data-type=8]").hide();
                } else if(video_type == 101){

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_section_type").hide();
                    $(".edit_type_drop").hide();
                    $(".edit_category_drop").hide();
                    $(".edit_language_drop").hide();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_order_by_upload_drop").hide();
                    $(".edit_order_by_view_drop").hide();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();
                    $(".edit_content_drop").hide();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait"],
                        ["category", "language", "channel", "index_landscape", "index_portrait", "shorts"]
                    );
                } else if(video_type == 103) {

                    $(".edit_type_drop").show();
                    $(".edit_channel_drop").hide();
                    $(".edit_sub_video_type_drop").hide();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#edit_type_id option[data-type=1]").show();
                    $("#edit_type_id option[data-type=2]").show();
                    $("#edit_type_id option[data-type=5]").hide();
                    $("#edit_type_id option[data-type=6]").show();
                    $("#edit_type_id option[data-type=7]").show();
                    $("#edit_type_id option[data-type=8]").hide();
                } else if(video_type == 8) {

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["shorts"],
                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                    );

                    $("#edit_type_id option[data-type=1]").hide();
                    $("#edit_type_id option[data-type=2]").hide();
                    $("#edit_type_id option[data-type=5]").hide();
                    $("#edit_type_id option[data-type=6]").hide();
                    $("#edit_type_id option[data-type=7]").hide();
                    $("#edit_type_id option[data-type=8]").show();
                } else {

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").hide();
                    $(".edit_category_drop").hide();
                    $(".edit_language_drop").hide();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").hide();
                    $(".edit_no_of_content_drop").hide();
                    $(".edit_order_by_upload_drop").hide();
                    $(".edit_order_by_view_drop").hide();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").hide();
                }
            } else if(SectionType == 0) { // Dynamic

                if(video_type == 1 || video_type == 2){

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").show();
                    $("#edit_type_id").val("").change();
                    $(".edit_category_drop").show();
                    $(".edit_language_drop").show();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_order_by_upload_drop").show();
                    $(".edit_order_by_view_drop").show();
                    if(video_type == 1){
                        $(".edit_premium_video_drop").show();
                    } else {
                        $(".edit_premium_video_drop").hide();
                    }
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#edit_type_id").children().removeAttr("selected");
                    $("#edit_type_id option:first").attr('selected','selected');
                    if(video_type == 1){
                        $("#edit_type_id option[data-type=1]").show();
                        $("#edit_type_id option[data-type=2]").hide();
                    } else {
                        $("#edit_type_id option[data-type=1]").hide();
                        $("#edit_type_id option[data-type=2]").show();
                    }
                    $("#edit_type_id option[data-type=5]").hide();
                    $("#edit_type_id option[data-type=6]").hide();
                    $("#edit_type_id option[data-type=7]").hide();
                    $("#edit_type_id option[data-type=8]").hide();
                } else if(video_type == 3 || video_type == 4 || video_type == 102){

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").hide();
                    $(".edit_category_drop").hide();
                    $(".edit_language_drop").hide();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_order_by_upload_drop").hide();
                    $(".edit_order_by_view_drop").hide();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    if(video_type == 3){
                    
                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["category"],
                            ["landscape", "portrait", "square", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else if(video_type == 4){

                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["language"],
                            ["landscape", "portrait", "square", "category", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else {

                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["channel"],
                            ["landscape", "portrait", "square", "category", "language", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    }

                } else if(video_type == 5 || video_type == 6 || video_type == 7){

                    $(".edit_sub_video_type_drop").show();
                    $("#edit_sub_video_type").children().removeAttr("selected");
                    $("#edit_sub_video_type option:first").attr('selected','selected');
                    $(".edit_type_drop").show();
                    $("#edit_type_id").val("").change();
                    $(".edit_category_drop").show();
                    $(".edit_language_drop").show();
                    $("#edit_channel_id").val("0").change();
                    if(video_type == 6){
                        $(".edit_channel_drop").show();
                    } else {
                        $(".edit_channel_drop").hide();
                    }
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_order_by_upload_drop").show();
                    $(".edit_order_by_view_drop").show();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#edit_type_id").children().removeAttr("selected");
                    $("#edit_type_id option:first").attr('selected','selected');
                    $("#edit_type_id option[data-type=1]").hide();
                    $("#edit_type_id option[data-type=2]").hide();
                    if(video_type == 5){
                        $("#edit_type_id option[data-type=5]").show();
                        $("#edit_type_id option[data-type=6]").hide();
                        $("#edit_type_id option[data-type=7]").hide();
                    } else if(video_type == 6){
                        $("#edit_type_id option[data-type=5]").hide();
                        $("#edit_type_id option[data-type=6]").show();
                        $("#edit_type_id option[data-type=7]").hide();
                    } else if(video_type == 7){
                        $("#edit_type_id option[data-type=5]").hide();
                        $("#edit_type_id option[data-type=6]").hide();
                        $("#edit_type_id option[data-type=7]").show();
                    }
                    $("#edit_type_id option[data-type=8]").hide();
                } else if(video_type == 101){

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_section_type").hide();
                    $(".edit_type_drop").hide();
                    $(".edit_category_drop").hide();
                    $(".edit_language_drop").hide();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_order_by_upload_drop").hide();
                    $(".edit_order_by_view_drop").hide();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait"],
                        ["category", "language", "channel", "index_landscape", "index_portrait", "shorts"]
                    );
                } else if(video_type == 103){

                    $("#edit_type_id").val("").change();
                    $(".edit_type_drop").show();
                    $(".edit_category_drop").show();
                    $(".edit_language_drop").show();
                    $("#edit_channel_id").val("0").trigger('change');
                    $(".edit_channel_drop").hide();
                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_order_by_upload_drop").show();
                    $(".edit_order_by_view_drop").show();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();
                    $(".edit_content_drop").hide();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#edit_type_id option[data-type=1]").show();
                    $("#edit_type_id option[data-type=2]").show();
                    $("#edit_type_id option[data-type=5]").hide();
                    $("#edit_type_id option[data-type=6]").show();
                    $("#edit_type_id option[data-type=7]").show();
                    $("#edit_type_id option[data-type=8]").hide();
                    
                } else if(video_type == 8){

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").show();
                    $("#edit_type_id").val("").change();
                    $(".edit_category_drop").show();
                    $(".edit_language_drop").show();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_order_by_upload_drop").show();
                    $(".edit_order_by_view_drop").show();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["shorts"],
                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                    );

                    $("#edit_type_id").children().removeAttr("selected");
                    $("#edit_type_id option:first").attr('selected','selected');
                    $("#edit_type_id option[data-type=1]").hide();
                    $("#edit_type_id option[data-type=2]").hide();
                    $("#edit_type_id option[data-type=5]").hide();
                    $("#edit_type_id option[data-type=6]").hide();
                    $("#edit_type_id option[data-type=7]").hide();
                    $("#edit_type_id option[data-type=8]").show();

                } else {

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").hide();
                    $(".edit_category_drop").hide();
                    $(".edit_language_drop").hide();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").hide();
                    $(".edit_no_of_content_drop").hide();
                    $(".edit_order_by_upload_drop").hide();
                    $(".edit_order_by_view_drop").hide();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").hide();
                    $(".edit_content_drop").hide();
                }
            } else if(SectionType == 2) { // AI

                $(".edit_category_drop").hide();
                $(".edit_language_drop").hide();
                $(".edit_order_by_upload_drop").hide();
                $(".edit_order_by_view_drop").hide();
                if(video_type == 1 || video_type == 2){

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").show();
                    $("#edit_type_id").val("").change();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    if(video_type == 1){
                        $(".edit_premium_video_drop").show();
                    } else {
                        $(".edit_premium_video_drop").hide();
                    }
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#edit_type_id").children().removeAttr("selected");
                    $("#edit_type_id option:first").attr('selected','selected');
                    if(video_type == 1){
                        $("#edit_type_id option[data-type=1]").show();
                        $("#edit_type_id option[data-type=2]").hide();
                    } else {
                        $("#edit_type_id option[data-type=1]").hide();
                        $("#edit_type_id option[data-type=2]").show();
                    }
                    $("#edit_type_id option[data-type=5]").hide();
                    $("#edit_type_id option[data-type=6]").hide();
                    $("#edit_type_id option[data-type=7]").hide();
                    $("#edit_type_id option[data-type=8]").hide();
                } else if(video_type == 3 || video_type == 4 || video_type == 102){

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").hide();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    if(video_type == 3){
                    
                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["category"],
                            ["landscape", "portrait", "square", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else if(video_type == 4){

                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["language"],
                            ["landscape", "portrait", "square", "category", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    } else {

                        // Screen Layout Hide-Show
                        toggleEditScreenLayout(
                            ["channel"],
                            ["landscape", "portrait", "square", "category", "language", "big_landscape", "big_portrait", "index_landscape", "index_portrait", "shorts"]
                        );
                    }

                } else if(video_type == 5 || video_type == 6 || video_type == 7){

                    $(".edit_sub_video_type_drop").show();
                    $("#edit_sub_video_type").children().removeAttr("selected");
                    $("#edit_sub_video_type option:first").attr('selected','selected');
                    $(".edit_type_drop").show();
                    $("#edit_type_id").val("").change();
                    $("#edit_channel_id").val("0").change();
                    if(video_type == 6){
                        $(".edit_channel_drop").show();
                    } else {
                        $(".edit_channel_drop").hide();
                    }
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#edit_type_id").children().removeAttr("selected");
                    $("#edit_type_id option:first").attr('selected','selected');
                    $("#edit_type_id option[data-type=1]").hide();
                    $("#edit_type_id option[data-type=2]").hide();
                    if(video_type == 5){
                        $("#edit_type_id option[data-type=5]").show();
                        $("#edit_type_id option[data-type=6]").hide();
                        $("#edit_type_id option[data-type=7]").hide();
                    } else if(video_type == 6){
                        $("#edit_type_id option[data-type=5]").hide();
                        $("#edit_type_id option[data-type=6]").show();
                        $("#edit_type_id option[data-type=7]").hide();
                    } else if(video_type == 7){
                        $("#edit_type_id option[data-type=5]").hide();
                        $("#edit_type_id option[data-type=6]").hide();
                        $("#edit_type_id option[data-type=7]").show();
                    }
                    $("#edit_type_id option[data-type=8]").hide();
                } else if(video_type == 101){

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_section_type").hide();
                    $(".edit_type_drop").hide();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait"],
                        ["category", "language", "channel", "index_landscape", "index_portrait", "shorts"]
                    );
                } else if(video_type == 103){

                    $("#edit_type_id").val("").change();
                    $(".edit_type_drop").show();
                    $("#edit_channel_id").val("0").trigger('change');
                    $(".edit_channel_drop").hide();
                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();
                    $(".edit_content_drop").hide();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["landscape", "portrait", "square", "big_landscape", "big_portrait", "index_landscape", "index_portrait"],
                        ["category", "language", "channel", "shorts"]
                    );

                    $("#edit_type_id option[data-type=1]").show();
                    $("#edit_type_id option[data-type=2]").show();
                    $("#edit_type_id option[data-type=5]").hide();
                    $("#edit_type_id option[data-type=6]").show();
                    $("#edit_type_id option[data-type=7]").show();
                    $("#edit_type_id option[data-type=8]").hide();
                    
                } else if(video_type == 8){

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").show();
                    $("#edit_type_id").val("").change();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").show();
                    $(".edit_no_of_content_drop").show();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").show();

                    $("#edit_screen_layout").children().removeAttr("selected");
                    // Screen Layout Hide-Show
                    toggleEditScreenLayout(
                        ["shorts"],
                        ["landscape", "portrait", "square", "category", "language", "channel", "big_landscape", "big_portrait", "index_landscape", "index_portrait"]
                    );

                    $("#edit_type_id").children().removeAttr("selected");
                    $("#edit_type_id option:first").attr('selected','selected');
                    $("#edit_type_id option[data-type=1]").hide();
                    $("#edit_type_id option[data-type=2]").hide();
                    $("#edit_type_id option[data-type=5]").hide();
                    $("#edit_type_id option[data-type=6]").hide();
                    $("#edit_type_id option[data-type=7]").hide();
                    $("#edit_type_id option[data-type=8]").show();

                } else {

                    $(".edit_sub_video_type_drop").hide();
                    $(".edit_type_drop").hide();
                    $(".edit_channel_drop").hide();
                    $(".edit_screen_layout_drop").hide();
                    $(".edit_no_of_content_drop").hide();
                    $(".edit_premium_video_drop").hide();
                    $(".edit_view_all_drop").hide();
                    $(".edit_content_drop").hide();
                }
            }
        });
        // Sub Video Type
        $("#edit_sub_video_type").change(function() {

            var sub_video_type = $(this).children("option:selected").val();
            var Sactiontype = $(".edit_section_type input[name='section_type']:checked").val();

            if(Sactiontype == 1) { // Manually
                $(".edit_premium_video_drop").hide();
                Get_Edit_Content();
            } else if(Sactiontype == 0) { // Dynamic
                $(".edit_premium_video_drop").hide();
                if(sub_video_type == 1){
                    $(".edit_premium_video_drop").show();
                }
            } else if(Sactiontype == 2) { // AI
                $(".edit_premium_video_drop").hide();
                if(sub_video_type == 1){
                    $(".edit_premium_video_drop").show();
                }
            }
        });
        // Type 
        $("#edit_type_id").change(function() {

            var edit_selected_type = $(this).find("option:selected").data('type');
            var sectiontype = $(".edit_section_type input[name='section_type']:checked").val();
            $("#edit_sub_video_type").children().removeAttr("selected");
            $("#edit_sub_video_type option:first").attr('selected','selected');

            if(sectiontype == 1) { // Manually
                $("#edit_content_ids").empty();
                $(".edit_content_drop").show();
            }

            if(edit_selected_type == 5 || edit_selected_type == 6 || edit_selected_type == 7) {

                $(".edit_sub_video_type_drop").show();
                $(".edit_premium_video_drop").hide();
                $(".edit_channel_drop").hide();
                if (edit_selected_type == 6) {
                    $(".edit_channel_drop").show();
                }
            } else if(edit_selected_type == 1 || edit_selected_type == 2) {
                
                $(".edit_sub_video_type_drop").hide();
                $(".edit_premium_video_drop").hide();
                $(".edit_channel_drop").hide();
                if(sectiontype == 1) { // Manually
                    Get_Edit_Content();
                } else if(sectiontype == 0) { // Dynamic
                    if(edit_selected_type == 1) {
                        $(".edit_premium_video_drop").show();
                    }
                } else if(sectiontype == 2) { // AI
                    if(edit_selected_type == 1) {
                        $(".edit_premium_video_drop").show();
                    }
                }
            } else if(edit_selected_type == 8) {

                $(".sub_video_type_drop").hide();
                $(".edit_premium_video_drop").hide();
                $(".channel_drop").hide();
                if(sectiontype == 1) { // Manually
                    Get_Edit_Content();
                }
            }
        });
        // Channel
        $("#edit_channel_id").change(function() {
            var Sactiontype = $(".edit_section_type input[name='section_type']:checked").val();
            if(Sactiontype == 1) {
                Get_Edit_Content();
            }
        });
        // Get Conntent For Add Manually Section
        function Get_Edit_Content() {

            if(is_home_screen == 1){
                var video_type = $("#edit_video_type").val() || "";
                var type_id = $("#edit_type_id").val() || "";
                var type_type = $("#edit_type_id option:selected").data('type') || "";
            } else {
                var video_type = top_type_type || "";
                var type_id = top_type_id || "";
                var type_type = top_type_type || "";
            }
            var sub_video_type = $("#edit_sub_video_type").val() || "";
            var channel_id = $("#edit_channel_id").val() || "";

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("admin.section.content") }}',
                data: {
                    video_type: video_type,
                    type_id: type_id,
                    sub_video_type: sub_video_type,
                    channel_id: channel_id,
                    type_type: type_type,
                },
                success: function(resp) {
                    $("#edit_content_ids").empty();
                    for (var i = 0; i < resp.result.length; i++) {
                        $('#edit_content_ids').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);          
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        // Update Section
        function update_section(){
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var id = $('#edit_id').val();
            var formData = new FormData($("#edit_content_section")[0]);
            formData.append('top_type_id', top_type_id);
            formData.append('top_type_type', top_type_type);

            var url = '{{ route("admin.section.update", ":id") }}';
                url = url.replace(':id', id);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                enctype: 'multipart/form-data',
                type: 'POST',
                url: url,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {

                    $("#dvloader").hide();
                    if(resp.status == 200){
                        $('#exampleModal').modal('toggle');
                    }
                    get_responce_message(resp, 'edit_content_section', '{{ route("admin.section.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // Delete Section
        function delete_section(id){
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            Swal.fire({
                theme: 'dark',
                title: "{{ __('label.confirm_deletion') }}",
                text: "{{ __('label.delete_item') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3000b',
                cancelButtonColor: '#058f00',
                confirmButtonText: "{{ __('label.delete') }}",
                cancelButtonText: "{{ __('label.cancel') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#dvloader").show();
                    var url = '{{ route("admin.section.show", ":id") }}';
                        url = url.replace(':id', id);
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        type: 'GET',
                        url: url,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(resp) {
                            $("#dvloader").hide();
                            get_responce_message(resp, '', '{{ route("admin.section.index") }}');
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $("#dvloader").hide();
                            toastr.error(errorThrown, textStatus);
                        }
                    });
                }
            });
        }

        // Sortable Section
        $("#contentListId").sortable({
            update: function(event, ui) { getIdsOfContent(); }
        });
        function getIdsOfContent() {
            var values = [];
            $('.listitemClass').each(function(index) {
                values.push($(this).attr("id").replace("imageNo", ""));
            });
            $('#outputvalues').val(values);
        }
        function sortableBTN(){
            var tab = $("ul.tabs li a.active");
            var is_home_screen = tab.data("is_home_screen");
            var top_type_id = tab.data("id");

            $("#dvloader").show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("admin.section.content.sortable") }}',
                data: {
                    is_home_screen: is_home_screen,
                    top_type_id: top_type_id,
                },
                success: function(resp) {
                    $("#dvloader").hide();

                    $('#contentListId').html('');
                    for (var i = 0; i < resp.result.length; i++) {

                        var data = `<div id="${resp.result[i].id}" class="bn-sort-item listitemClass">
                            <i class="fa-solid fa-grip-vertical drag-handle"></i>
                            <span class="sort-num">${i + 1}</span>
                            <span style="flex:1">${resp.result[i].title}</span>
                        </div>`;
                        $('#contentListId').append(data);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
        function save_section_sortable() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_section_sortable")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.section.content.sortable.save") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_section_sortable', '{{ route("admin.section.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // Change Status
        function change_status(id) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: "{{route('admin.section.status')}}",
                data: {id: id},
                success: function(resp) {
                    $("#dvloader").hide();
                    if (resp.status == 200) {
                        if (resp.status_code == 1) {
                            $('#' + id).removeClass('status-off').addClass('status-on');
                            $('#text_' + id).text('{{__("label.show")}}').removeClass('text-danger').addClass('text-success');
                        } else {
                            $('#' + id).removeClass('status-on').addClass('status-off');
                            $('#text_' + id).text('{{__("label.hide")}}').removeClass('text-success').addClass('text-danger');
                        }
                        toastr.success(resp.success);
                    } else {
                        toastr.error(resp.errors);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        };

        // Screen Layout Hide Show
        function toggleScreenLayout(showList, hideList) {

            $("#screen_layout").children().removeAttr("selected");

            showList.forEach(val => $("#screen_layout option[value='" + val + "']").show());
            hideList.forEach(val => $("#screen_layout option[value='" + val + "']").hide());
        }
        function toggleEditScreenLayout(showList, hideList) {

            showList.forEach(val => $("#edit_screen_layout option[value='" + val + "']").show());
            hideList.forEach(val => $("#edit_screen_layout option[value='" + val + "']").hide());
        }
    </script>
@endsection