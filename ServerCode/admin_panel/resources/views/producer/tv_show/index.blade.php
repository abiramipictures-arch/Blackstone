@extends('producer.layout.page-app')
@section('page_title', $type['name'])
@section('tab_title', $type['name'])

@section('content')
    @include('producer.layout.sidebar')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

    <div class="right-content">
        @include('producer.layout.header')

        <div class="body-content">
            <h1 class="page-title-sm">{{ $type['name'] }}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('producer.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $type['name'] }}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('producer.tvshow.add', ['type_id' => $type['id']]) }}" class="btn btn-default-white mw-120">
                        <i class="fa-solid fa-plus fa-sm mr-1"></i>{{__('label.add_content')}}
                    </a>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-tv"></i>{{ $type['name'] }}</div>
                <div class="card-body">

                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search">
                        </div>
                    </div>

                    <div class="page-search p-0 my-2">
                        <div class="sorting w-50 mr-2 p-0">
                            <select class="form-control" id="input_rent">
                                <option value="0">{{__('label.all_video')}}</option>
                                <option value="1">{{__('label.rent_video')}}</option>
                            </select>
                        </div>
                        <div class="sorting w-50 pl-0">
                            <select class="form-control" id="input_status">
                                <option value="all">{{__('label.all_status')}}</option>
                                <option value="0">{{__('label.hide')}}</option>
                                <option value="1">{{__('label.show')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.image')}}</th>
                                    <th>{{__('label.name')}}</th>
                                    <th>{{__('label.rent')}}</th>
                                    <th>{{__('label.views')}} / {{__('label.rating')}}</th>
                                    <th>{{__('label.status')}}</th>
                                    <th>{{__('label.action')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="ReleasesModal" tabindex="-1" data-backdrop="static" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{__('label.releases_tvshows')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="release_tvshow" enctype="multipart/form-data">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>{{__('label.type')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="type_id" id="type_id">
                                                <option value="">{{__('label.select_type')}}</option>
                                                @foreach ($releases_type as $rv)
                                                    <option value="{{ $rv->id }}" data-type="{{ $rv->type }}">{{ $rv->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 channel_list">
                                        <div class="form-group">
                                            <label>{{__('label.channel')}}<span class="text-danger">*</span></label>
                                            <select class="form-control" name="channel_id" id="channel_id" style="width:100%!important;">
                                                <option value="">{{__('label.select_channel')}}</option>
                                                @foreach ($channel_list as $cv)
                                                    <option value="{{ $cv->id }}">{{ $cv->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120" onclick="release_tvshow()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $('#channel_id').select2({ dropdownParent: $('#ReleasesModal') });

        $(document).ready(function () {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('producer.tvshow.index', ['type_id' => $type['id']]) }}",
                    data: function (d) {
                        d.input_search = $('#input_search').val();
                        d.input_rent   = $('#input_rent').val();
                        d.input_status = $('#input_status').val();
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
            $('#input_rent, #input_status').change(function () { table.draw(); });

            /* Releases modal — delegated for dynamically rendered rows */
            $(document).on('click', '.releases_modal', function () {
                $("#release_tvshow #edit_id").val($(this).data("id"));
            });
        });

        /* Status toggle */
        function change_status(id) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: "GET",
                url: "{{ route('producer.tvshow.status') }}",
                data: { id: id },
                success: function (resp) {
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
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        /* Delete */
        function deleteTVShow(url) {
            if ('{{ Demo_Mode() }}' != 1) { showError(); return; }
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

        /* Releases modal */
        $(".channel_list").hide();
        $('#type_id').on('change', function () {
            $(".channel_list").toggle($(this).find('option:selected').data("type") == 6);
        });

        function release_tvshow() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#release_tvshow")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("producer.tvshow.releases") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (resp) {
                    $("#dvloader").hide();
                    if (resp.status == 200) { $('#ReleasesModal').modal('toggle'); }
                    get_responce_message(resp, 'release_tvshow', '{{ route("producer.tvshow.index", ["type_id" => $type["id"]]) }}');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
