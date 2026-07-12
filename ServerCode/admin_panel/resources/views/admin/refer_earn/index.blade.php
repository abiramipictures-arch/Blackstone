@extends('admin.layout.page-app')
@section('page_title', __('label.refer_earn'))
@section('tab_title', __('label.refer_earn'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.refer_earn')}}</h1>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.refer_earn')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row">
                <div class="col-sm-6 col-lg-4 mb-3">
                    <div class="card-earning stat-card stat-card-primary">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ $total_referrals }}</p>
                                <p class="earning-title mb-0">{{__('label.total_referrals')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-people-arrows fa-xl mr-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 mb-3">
                    <div class="card-earning stat-card stat-card-approve">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ $total_parent_earn }}</p>
                                <p class="earning-title mb-0">{{__('label.total_parent_earn')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-coins fa-xl mr-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 mb-3">
                    <div class="card-earning stat-card stat-card-pending">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ $total_child_earn }}</p>
                                <p class="earning-title mb-0">{{__('label.total_child_earn')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-gift fa-xl mr-2"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Table -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-gift mr-2"></i>{{__('label.refer_earn')}}</div>
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
                                    <th>{{__('label.reference_code')}}</th>
                                    <th>{{__('label.parent_user')}}</th>
                                    <th>{{__('label.parent_email')}}</th>
                                    <th>{{__('label.parent_earn')}}</th>
                                    <th>{{__('label.child_user')}}</th>
                                    <th>{{__('label.child_email')}}</th>
                                    <th>{{__('label.child_earn')}}</th>
                                    <th>{{__('label.date')}}</th>
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
                    url: "{{ route('admin.refer-earn.index') }}",
                    data: function(d) {
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex',    name: 'DT_RowIndex',    orderable: false, searchable: false },
                    { data: 'reference_code', name: 'reference_code', orderable: false, searchable: false },
                    { data: 'parent_name',    name: 'parent_name',    orderable: false, searchable: false },
                    { data: 'parent_email',   name: 'parent_email',   orderable: false, searchable: false },
                    { data: 'parent_earn',    name: 'parent_earn',    orderable: false, searchable: false },
                    { data: 'child_name',     name: 'child_name',     orderable: false, searchable: false },
                    { data: 'child_email',    name: 'child_email',    orderable: false, searchable: false },
                    { data: 'child_earn',     name: 'child_earn',     orderable: false, searchable: false },
                    { data: 'date',           name: 'date',           orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function() { table.draw(); });
        });
    </script>
@endsection
