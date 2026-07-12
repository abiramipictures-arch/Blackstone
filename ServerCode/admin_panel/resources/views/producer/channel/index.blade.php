@extends('producer.layout.page-app')
@section('page_title', __('label.channel'))
@section('tab_title', __('label.channel'))

@section('content')
    @include('producer.layout.sidebar')

    <div class="right-content">
        @include('producer.layout.header')

        <div class="body-content">
            <h1 class="page-title-sm">{{__('label.channel')}}</h1>

            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('producer.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.channel')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-list"></i>{{__('label.channel')}}</div>
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
                                    <th>{{__('label.image')}}</th>
                                    <th>{{__('label.name')}}</th>
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
                    url: "{{ route('producer.channel.index') }}",
                    data: function (d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'portrait_img', name: 'portrait_img', orderable: false, searchable: false,
                        render: function (data) {
                            return `<img src='${data}' class='table-image'>`;
                        },
                    },
                    {
                        data: 'name', name: 'name', orderable: false, searchable: false,
                        render: function (data) {
                            return data ? data : "-";
                        }
                    },
                ],
            });

            $('#input_search').keyup(function () {
                table.draw();
            });
        });
    </script>
@endsection
