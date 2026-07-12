@extends('admin.layout.page-app')
@section('page_title', __('label.banner'))
@section('tab_title', __('label.banner'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select 2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <h1 class="page-title-sm">{{__('label.banner')}}</h1>

            <div class="row">
                <div class="col-sm-11">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.banner')}}</li>
                    </ol>
                </div>
                <div class="col-sm-1 d-flex justify-content-start mb-3">
                    <button type="button" data-toggle="modal" data-target="#sortableModal" onclick="sortableBTN()" class="btn btn-default-white" style="border-radius: 10px;">
                        <i class="fa-solid fa-arrow-up-wide-short fa-xl"></i>
                    </button>
                </div>
            </div>

            @if(isset($type) && $type != null && count($type) > 0)
                <ul class="tabs nav nav-pills custom-tabs inline-tabs mt-2" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="app-tab" onclick="Selected_Type('{{$type[0]['id']}}', '{{$type[0]['type']}}', 1)" data-is_home_screen="1" href="#app" role="tab" data-toggle="tab">
                            {{__('label.home')}}
                        </a>
                    </li>
                    @for ($i = 0; $i < count($type); $i++) 
                        <li class="nav-item">
                            <a class="nav-link" id="{{$type[$i]['name']}}-tab" onclick="Selected_Type('{{$type[$i]['id']}}', '{{$type[$i]['type']}}', 2)" data-is_home_screen="2" data-id="{{$type[$i]['id']}}" data-type="{{$type[$i]['type']}}" data-toggle="tab" href="#{{$type[$i]['name']}}">
                                {{ $type[$i]['name']}}
                            </a>
                        </li>
                    @endfor
                </ul>
            @endif

            <div class="card custom-border-card">
                <div class="bn-form-card">
                    <div class="card-header">
                        <i class="fa-solid fa-plus-circle"></i>{{__('label.add_banner')}}
                    </div>

                    @if(isset($type) && $type != null && count($type) > 0)
                        <div class="bn-form-body">
                            <form id="save_banner" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                
                                <div class="radio-row bn-field">
                                    <label>{{__('label.select_type')}}</label>                                   
                                    <div class="bn-type-radios">
                                        @for ($i = 0; $i < count($type); $i++)
                                            <input type="radio" name="type_id" class="bn-type-radio" id="Video_Selecte{{$i}}" onclick="Selected_Type('{{ $type[$i]['id'] }}', '{{ $type[$i]['type'] }}', 2)"
                                            data-id="{{ $type[$i]['id'] }}" data-type="{{ $type[$i]['type'] }}" data-name="{{ $type[$i]['name'] }}" value="{{ $type[$i]['id'] }}" {{ $i == 0 ? 'checked' : '' }}>
                                            <label class="bn-type-label" for="Video_Selecte{{$i}}">{{ $type[$i]['name'] }}</label>
                                        @endfor
                                    </div>
                                </div>        
                                <div class="bn-form-row">
                                    <div class="bn-field subvideo_type">
                                        <label>{{__('label.sub_video_type')}}</label>
                                        <select class="form-control" name="subvideo_type" id="subvideo_type">
                                            <option value="" selected disabled>{{__('label.select_type')}}</option>
                                            <option value="1">{{__('label.video')}}</option>
                                            <option value="2">{{__('label.show')}}</option>
                                        </select>
                                    </div>
                                    <div class="bn-field option_class_video">
                                        <label>{{__('label.video')}}</label>
                                        <select class="form-control" name="video_id" id="video_id">
                                            <option selected disabled>{{__('label.select_video')}}</option>
                                            @foreach ($video as $key => $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
                                
                <div class="bn-list-wrap">
                    <div class="bn-list-header">
                        <i class="fa-solid fa-panorama primary-color fa-xl"></i>
                        {{__('label.banner_list')}}
                    </div>
                    <div class="after-add-more"></div>
                </div>
            </div>

            <!-- Sort Order -->
            <div class="modal fade" id="sortableModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="sortableModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title w-100 text-center" id="sortableModalLabel">{{__('label.banner_sortable_list')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="contentListId"></div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form id="save_banner_sortable" enctype="multipart/form-data">
                                <input id="outputvalues" type="hidden" name="ids" value="" />
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="button" class="btn btn-default mw-120" onclick="save_banner_sortable()">
                                    <i class="fa-solid fa-floppy-disk fa-sm mr-1"></i>{{__('label.save')}}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- select 2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <!-- Sortable -->
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script>
        $("#video_id").select2();

        var type = $('input[name=type_id]:checked').data('type');
        if (type == 1 || type == 2 || type == 8) {
            $(".subvideo_type").hide();
        }

        function Selected_Type(type_id, type, is_home_page) {
            $("#video_id").empty();
            $('#video_id').append(`<option selected disabled>{{__('label.select_video')}}</option>`);
            $(".subvideo_type").hide();
            if (type == 5 || type == 6 || type == 7) {
                $(".subvideo_type").show();
            }
            if (is_home_page == 1) {
                $("#Video_Selecte0").prop('checked', true);
            }
            if (type == 1 || type == 2 || type == 8) {
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: 'POST',
                    url: '{{ route("admin.banner.data") }}',
                    data: { type_id: type_id, type: type },
                    success: function(resp) {
                        for (var i = 0; i < resp.result.length; i++) {
                            $('#video_id').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { toastr.error(errorThrown, textStatus); }
                });
            } else if (type == 5 || type == 6 || type == 7) {
                var subvideo_type = $('#subvideo_type').find(":selected").val();
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: 'POST',
                    url: '{{ route("admin.banner.data") }}',
                    data: { type_id: type_id, type: type, subvideo_type: subvideo_type },
                    success: function(resp) {
                        for (var i = 0; i < resp.result.length; i++) {
                            $('#video_id').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { toastr.error(errorThrown, textStatus); }
                });
            }
        }

        $('#subvideo_type').on('change', function () {
            $("#video_id").empty();
            $('#video_id').append(`<option selected disabled>{{__('label.select_video')}}</option>`);
            var Tab = $("ul.tabs li a.active");
            var Is_home_screen = Tab.data("is_home_screen");
            var subvideo_type = $(this).children("option:selected").val();
            if (Is_home_screen == 1) {
                var type_id = $('input[name=type_id]:checked').data('id');
                var type    = $('input[name=type_id]:checked').data('type');
            } else {
                var type_id = Tab.data("id");
                var type    = Tab.data("type");
            }
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                url: '{{ route("admin.banner.data") }}',
                data: { type_id: type_id, type: type, subvideo_type: subvideo_type },
                success: function(resp) {
                    for (var i = 0; i < resp.result.length; i++) {
                        $('#video_id').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { toastr.error(errorThrown, textStatus); }
            });
        });

        $('#video_id').on('change', function () {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var Tab           = $("ul.tabs li a.active");
            var Is_home_screen = Tab.data("is_home_screen");
            var Video_Id      = $(this).children("option:selected").val();
            var subvideo_type = $('#subvideo_type').find(":selected").val();
            if (Is_home_screen == 1) {
                var Type_Id    = $('input[name=type_id]:checked').val();
                var Video_Type = $('input[name=type_id]:checked').data('type');
            } else {
                var Type_Id    = Tab.data("id");
                var Video_Type = Tab.data("type");
            }
            $("#dvloader").show();
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                url: '{{ route("admin.banner.store") }}',
                data: { is_home_screen: Is_home_screen, type_id: Type_Id, video_type: Video_Type, video_id: Video_Id, subvideo_type: subvideo_type },
                success: function(resp) {
                    $("#dvloader").hide();
                    if (resp.status == 200) {
                        toastr.success(resp.success);
                        refreshCurrentBannerList();
                        refreshVideoDropdown();
                    } else {
                        toastr.error(Array.isArray(resp.errors) ? resp.errors.join('<br>') : resp.errors);
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        });

        /* ── Refresh video dropdown (excludes already-added banners) ── */
        function refreshVideoDropdown() {
            var Tab           = $("ul.tabs li a.active");
            var Is_home_screen = Tab.data("is_home_screen");
            var type_id, type;
            if (Is_home_screen == 1) {
                type_id = $('input[name=type_id]:checked').data('id');
                type    = $('input[name=type_id]:checked').data('type');
            } else {
                type_id = Tab.data("id");
                type    = Tab.data("type");
            }
            var subvideo_type = $('#subvideo_type').find(":selected").val();
            $("#video_id").empty();
            $('#video_id').append(`<option selected disabled>{{__('label.select_video')}}</option>`);
            if (!type_id) return;
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                url: '{{ route("admin.banner.data") }}',
                data: { type_id: type_id, type: type, subvideo_type: subvideo_type },
                success: function(resp) {
                    for (var i = 0; i < resp.result.length; i++) {
                        $('#video_id').append(`<option value="${resp.result[i].id}">${resp.result[i].name}</option>`);
                    }
                    $('#video_id').trigger('change.select2');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { toastr.error(errorThrown, textStatus); }
            });
        }

        /* ── Banner List Renderer ── */
        var sort_order_array = [];
        function refreshCurrentBannerList() {
            var Tab = $("ul.tabs li a.active");
            var Is_home_screen = Tab.data("is_home_screen");
            var postData = { is_home_screen: Is_home_screen };
            if (Is_home_screen == 2) {
                postData.type_id = Tab.data("id");
            }
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: "POST",
                data: postData,
                url: '{{ route("admin.banner.list") }}',
                success: function(resp) { renderBannerList(resp.result); },
                error: function(XMLHttpRequest, textStatus, errorThrown) { toastr.error(errorThrown, textStatus); }
            });
        }
        function renderBannerList(results) {
            sort_order_array = results;
            var container = $('.after-add-more');
            container.empty();
            if (results.length === 0) {
                container.append(
                    `<div class="bn-empty"><i class="fa-solid fa-panorama"></i><p>No banners added yet.<p></div>`
                );
                return;
            }
            results.forEach(function(item, idx) {
                container.append(
                    `<div class="bn-item">
                        <span class="bn-item-num">${idx + 1}</span>
                        <span class="bn-item-type">${item.type.name}</span>
                        <span class="bn-item-name">${item.video ? item.video.name : ''}</span>
                        <a onclick="DeleteBanner(${item.id})" class="edit-delete-btn">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </div>`
                );
            });
        }

        /* Initial load */
        refreshCurrentBannerList();
        $('.nav-item a').on('click', function () {
            var Is_home_screen = $(this).data("is_home_screen");
            if (Is_home_screen == 2) {
                $('.radio-row').hide();
                var type_id = $(this).data("id");
                $("#dvloader").show();
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: "POST",
                    data: { type_id: type_id, is_home_screen: Is_home_screen },
                    url: '{{ route("admin.banner.list") }}',
                    success: function(resp) { $("#dvloader").hide(); renderBannerList(resp.result); },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { $("#dvloader").hide(); toastr.error(errorThrown, textStatus); }
                });
            } else {
                $('.radio-row').show();
                $("#dvloader").show();
                $.ajax({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    type: "POST",
                    data: { is_home_screen: Is_home_screen },
                    url: '{{ route("admin.banner.list") }}',
                    success: function(resp) { $("#dvloader").hide(); renderBannerList(resp.result); },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { $("#dvloader").hide(); toastr.error(errorThrown, textStatus); }
                });
            }
        });

        /* Delete */
        function DeleteBanner(id) {
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
                    var url = "{{route('admin.banner.destroy', '')}}" + "/" + id;
                    $("#dvloader").show();
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        type: 'DELETE',
                        url: url,
                        success: function(resp) {
                            $("#dvloader").hide();
                            if (resp.status == 200) {
                                toastr.success(resp.success);
                                refreshCurrentBannerList();
                                refreshVideoDropdown();
                            } else {
                                toastr.error(Array.isArray(resp.errors) ? resp.errors.join('<br>') : resp.errors);
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            $("#dvloader").hide();
                            toastr.error(errorThrown, textStatus);
                        }
                    });
                }
            });
        }

        /* Sortable */
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
        function sortableBTN() {
            var listContainer = $('#contentListId');
            listContainer.html('');
            sort_order_array.forEach(function(item, idx) {
                listContainer.append(
                    `<div id="${item.id}" class="bn-sort-item listitemClass">
                        <i class="fa-solid fa-grip-vertical drag-handle"></i>
                        <span class="sort-num">${idx + 1}</span>
                        <span style="flex:1">${(item.video && item.video.name) ? item.video.name : ''}</span>
                    </div>`
                );
            });
        }
        function save_banner_sortable() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_banner_sortable")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.banner.sortable.save") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_banner_sortable', '{{ route("admin.banner.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
