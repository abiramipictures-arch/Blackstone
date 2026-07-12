@extends('admin.layout.page-app')
@section('page_title', __('label.episodes'))
@section('tab_title', __('label.episodes'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <h1 class="page-title-sm">{{__('label.episodes')}}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.shorts.index', ['type_id' => $type['id']]) }}">{{$type['name']}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.episodes')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.shorts.episode.add', ['id' => $shorts_id, 'type_id' => $type['id']]) }}" class="btn btn-default-white mw-150">
                        <i class="fa-solid fa-plus fa-sm mr-1"></i>{{__('label.add_new_episode')}}
                    </a>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-film mr-2"></i>{{__('label.episodes')}}</div>
                <div class="card-body">

                    <!-- Filters -->
                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search">
                        </div>
                        <div class="sorting w-50">
                            <select class="form-control" id="input_season">
                                <option value="0" selected>{{__('label.all_season')}}</option>
                                @foreach ($season as $s)
                                    <option value="{{ $s['id'] }}">{{ $s['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-default ml-2" style="border-radius: 10px;">
                            <i class="fa-solid fa-arrow-up-wide-short fa-xl"></i>
                        </button>
                    </div>

                    <div class="table-responsive mt-2">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.image')}}</th>
                                    <th>{{__('label.name')}}</th>
                                    <th>{{__('label.type')}}</th>
                                    <th>{{__('label.views')}}</th>
                                    <th>{{__('label.status')}}</th>
                                    <th>{{__('label.action')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Video Player Modal -->
            <div class="modal fade" id="videoModal" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-body p-0 bg-transparent">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <video controls width="800" height="500" preload="none" poster="" id="theVideo"
                                controlsList="nodownload noplaybackrate" disablepictureinpicture>
                                <source src="" type="video/mp4">
                            </video>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sortable Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title w-100 text-center" id="exampleModalLabel">{{__('label.episode_sortable_list')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="contentListId">
                                @foreach ($sortorder_data as $key => $value)
                                    <div id="{{ $value->id }}" class="bn-sort-item listitemClass">
                                        <i class="fa-solid fa-grip-vertical drag-handle"></i>
                                        <span class="sort-num">{{ $key + 1 }}</span>
                                        <span style="flex:1">{{ $value->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form id="save_episode_sortable" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input id="outputvalues" type="hidden" name="ids" value="" />
                                <div class="w-100 text-center">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_episode_sortable()">
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
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.shorts.episode.index', ['id' => $shorts_id, 'type_id' => $type['id']]) }}",
                    data: function (d) {
                        d.input_search = $('#input_search').val();
                        d.input_season = $('#input_season').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'thumbnail_img', name: 'thumbnail_img', orderable: false, searchable: false,
                        render: function (data) {
                            return "<img src='" + data + "' class='table-image'>";
                        }
                    },
                    { data: 'name_col', name: 'name_col', orderable: false, searchable: false },
                    { data: 'type_badge', name: 'type_badge', orderable: false, searchable: false },
                    { data: 'stats', name: 'stats', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function () { table.draw(); });
            $('#input_season').change(function () { table.draw(); });
        });

        /* Video player */
        $(document).on('click', '.video', function () {
            const theModal    = $(this).data("target");
            const videoSRC    = $(this).data("video");
            const videoPoster = $(this).data("image");
            $(theModal + ' source').attr('src', videoSRC);
            $(theModal + ' video').attr('poster', videoPoster);
            const video = $(theModal + ' video')[0];
            video.load();
            $("#videoModal .close").click(function () {
                video.pause();
                video.currentTime = 0;
                $(theModal + ' source').attr('src', '');
            });
            $('#videoModal').on('contextmenu', function (e) { e.preventDefault(); });
        });

        /* Status toggle */
        function change_status(id) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            $.ajax({
                type: "GET",
                url: "{{ route('admin.shorts.episode.status') }}",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: { id: id },
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
                error: function(xhr, status, error) {
                    $("#dvloader").hide();
                    toastr.error(error);
                }
            });
        }

        /* Delete */
        function deleteEpisode(url) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            Swal.fire({
                theme             : 'dark',
                title             : "{{ __('label.confirm_deletion') }}",
                text              : "{{ __('label.delete_item') }}",
                icon              : 'warning',
                showCancelButton  : true,
                confirmButtonColor: '#e3000b',
                cancelButtonColor : '#058f00',
                confirmButtonText : "{{ __('label.delete') }}",
                cancelButtonText  : "{{ __('label.cancel') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        /* Sortable */
        $("#contentListId").sortable({
            update: function(event, ui) { getIdsOfContents(); }
        });
        function getIdsOfContents() {
            var values = [];
            $('.listitemClass').each(function(index) {
                values.push($(this).attr("id").replace("imageNo", ""));
            });
            $('#outputvalues').val(values);
        }
        function save_episode_sortable() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_episode_sortable")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.shorts.episode.sortable") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_episode_sortable', '{{ route("admin.shorts.episode.index", ["id" => $shorts_id, "type_id" => $type["id"]]) }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
