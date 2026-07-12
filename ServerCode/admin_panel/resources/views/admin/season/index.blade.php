@extends('admin.layout.page-app')
@section('page_title', __('label.season'))
@section('tab_title', __('label.season'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- Mobile page title -->
            <h1 class="page-title-sm">{{__('label.season')}}</h1>

            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-sm-11">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.season')}}</li>
                    </ol>
                </div>
                <div class="col-sm-1 d-flex justify-content-start mb-3">
                    <button type="button" data-toggle="modal" data-target="#sortableModal" onclick="sortableBTN()" class="btn btn-default-white" style="border-radius: 10px;">
                        <i class="fa-solid fa-arrow-up-wide-short fa-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Add Season -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-plus-circle"></i>{{__('label.add_season')}}</div>
                <form id="save_season" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="{{__('label.enter_name')}}" autofocus>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-default mw-120" onclick="save_season()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.save')}}</button>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    </div>
                </form>
            </div>

            <!-- Search & Table -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-list"></i>{{__('label.season')}}</div>
                <div class="card-body">
                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search" aria-describedby="basic-addon1">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.name')}}</th>
                                    <th>{{__('label.action')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Edit Model -->
            <div class="modal fade" id="EditModel" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{__('label.edit_season')}}</h5>
                            <button type="button" class="close mt-2 mr-2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="update_season" enctype="multipart/form-data">
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit_id">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{__('label.name')}}<span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="edit_name" class="form-control" placeholder="{{__('label.enter_name')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default mw-120 mr-2" onclick="update_season()"><i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{__('label.update')}}</button>
                                <button type="button" class="btn btn-cancel mw-120" data-dismiss="modal">{{__('label.close')}}</button>
                                <input type="hidden" name="_method" value="PATCH">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- sortableModal -->
            <div class="modal fade" id="sortableModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="sortableModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title w-100 text-center" id="sortableModalLabel">{{__('label.season_sortable_list')}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="contentListId"></div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <form id="save_season_sortable" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input id="outputvalues" type="hidden" name="ids" value="" />
                                <div class="w-100 text-center">
                                    <button type="button" class="btn btn-default mw-120" onclick="save_season_sortable()">
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
    <!-- Sortable -->
    <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.season.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'name', name: 'name', orderable: false, searchable: false,
                        render: function(data) {
                            return data ? data : "-";
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function() {
                table.draw();
            });
        });

        function save_season() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_season")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.season.store") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_season', '{{ route("admin.season.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        $(document).on("click", ".edit_season", function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            $(".modal-body #edit_id").val(id);
            $(".modal-body #edit_name").val(name);
        });
        function update_season() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#update_season")[0]);
            var Edit_Id = $("#edit_id").val();

            var url = '{{ route("admin.season.update", ":id") }}';
            url = url.replace(':id', Edit_Id);

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

                    if (resp.status == 200) {
                        $('#EditModel').modal('toggle');
                    }
                    get_responce_message(resp, 'update_season', '{{ route("admin.season.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // Sortable Season
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
        var season_data = JSON.parse('<?php echo json_encode($data); ?>'); // Convert PHP to JS array
        function sortableBTN() {
            var listContainer = $('#contentListId');
            listContainer.html('');

            var htmlContent = ''; 
            for (var i = 0; i < season_data.length; i++) { 
                htmlContent += `<div id="${season_data[i].id}" class="bn-sort-item listitemClass">
                                    <i class="fa-solid fa-grip-vertical drag-handle"></i>
                                    <span class="sort-num">${i + 1}</span>
                                    <span style="flex:1">${season_data[i].name}</span>                                    
                                </div>`;
            }                    
            listContainer.append(htmlContent);
        }
        function save_season_sortable() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#save_season_sortable")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.season.sortable.save") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'save_season_sortable', '{{ route("admin.season.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
