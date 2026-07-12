@extends('producer.layout.page-app')
@section('page_title', $type['name'])
@section('tab_title', $type['name'])

@section('content')
    @include('producer.layout.sidebar')

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
                    <a href="{{ route('producer.shorts.add', ['type_id' => $type['id']]) }}" class="btn btn-default-white mw-120">
                        <i class="fa-solid fa-plus fa-sm mr-1"></i>{{__('label.add_content')}}
                    </a>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-film"></i>{{ $type['name'] }}</div>
                <div class="card-body">

                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search">
                        </div>
                        <div class="sorting w-50">
                            <select class="form-control" id="input_status">
                                <option value="all">{{__('label.all_status')}}</option>
                                <option value="0">{{__('label.hide')}}</option>
                                <option value="1">{{__('label.show')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive mt-2">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.image')}}</th>
                                    <th>{{__('label.name')}}</th>
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
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $(document).ready(function () {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('producer.shorts.index', ['type_id' => $type['id']]) }}",
                    data: function (d) {
                        d.input_search = $('#input_search').val();
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
                    { data: 'stats', name: 'stats', orderable: false, searchable: false },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function () { table.draw(); });
            $('#input_status').change(function () { table.draw(); });
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
                url: "{{ route('producer.shorts.status') }}",
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
        function deleteShorts(url) {
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
    </script>
@endsection
