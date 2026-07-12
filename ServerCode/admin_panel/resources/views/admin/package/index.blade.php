@extends('admin.layout.page-app')
@section('page_title', __('label.package'))
@section('tab_title', __('label.package'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.package')}}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.package')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.package.create') }}" class="btn btn-default-white mw-120">
                        <i class="fa-solid fa-plus fa-sm mr-1"></i>{{__('label.add_package')}}
                    </a>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-box-archive mr-2"></i>{{__('label.package')}}</div>
                <div class="card-body">
                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.package_type')}}</th>
                                    <th>{{__('label.name')}}</th>
                                    <th>{{__('label.price')}}</th>
                                    <th>{{__('label.duration')}}</th>
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
        $(document).ready(function() {
            table = $('#datatable').DataTable({
                ...dataTableDefaults,
                ajax: {
                    url: "{{ route('admin.package.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex',    name: 'DT_RowIndex',   orderable: false, searchable: false },
                    { data: 'package_type',   name: 'package_type',  orderable: false, searchable: false,
                      render: function(data) {
                          if (data == 1) return "{{__('label.paid')}}";
                          if (data == 2) return "{{__('label.free')}}";
                          return '-';
                      }},
                    { data: 'name',           name: 'name',          orderable: false, searchable: false },
                    { data: 'price',          name: 'price',         orderable: false, searchable: false,
                      render: function(data) { return data ? "{{ Currency_Code() }}" + ' ' + data : '-'; } },
                    { data: 'time',           name: 'time',          orderable: false, searchable: false,
                      render: function(data, type, row) {
                          return (row.time && row.type) ? row.time + ' ' + row.type : '-';
                      }},
                    { data: 'status',         name: 'status',        orderable: false, searchable: false },
                    { data: 'action',         name: 'action',        orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function() { table.draw(); });
        });

        function change_status(id) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            $.ajax({
                type: "GET",
                url: "{{route('admin.package.show', '')}}" + "/" + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: id,
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
    </script>
@endsection
