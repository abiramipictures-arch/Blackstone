@extends('admin.layout.page-app')
@section('page_title', __('label.payment'))
@section('tab_title', __('label.payment'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.payment')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.payment')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-credit-card mr-2"></i>{{__('label.payment')}}</div>
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
                                    <th>{{__('label.name')}}</th>
                                    <th>{{__('label.status')}}</th>
                                    <th>{{__('label.payment_environment')}}</th>
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
                    url: "{{ route('admin.payment.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex',  name: 'DT_RowIndex',  orderable: false, searchable: false },
                    { data: 'name',         name: 'name',         orderable: false, searchable: false },
                    { data: 'visibility',   name: 'visibility',   orderable: false, searchable: false,
                      render: function(data) { return data == 1 ? "{{__('label.active')}}" : "{{__('label.in_active')}}"; } },
                    { data: 'is_live',      name: 'is_live',      orderable: false, searchable: false,
                      render: function(data) { return data == 1 ? "{{__('label.live')}}" : "{{__('label.sandbox')}}"; } },
                    { data: 'action',       name: 'action',       orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function() { table.draw(); });
        });
    </script>
@endsection