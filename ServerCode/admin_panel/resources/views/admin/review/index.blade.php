@extends('admin.layout.page-app')
@section('page_title', __('label.reviews'))
@section('tab_title', __('label.reviews'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.reviews_ratings')}}</h1>

            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.reviews')}}</li>
                    </ol>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="row">
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card-earning stat-card stat-card-primary">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ $total }}</p>
                                <p class="earning-title mb-0">{{__('label.total_reviews')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-star fa-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card-earning stat-card stat-card-pending">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ $pending }}</p>
                                <p class="earning-title mb-0">{{__('label.pending')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-clock fa-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card-earning stat-card stat-card-approve">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ $approved }}</p>
                                <p class="earning-title mb-0">{{__('label.approved')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-circle-check fa-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3 mb-3">
                    <div class="card-earning stat-card stat-card-reject">
                        <div class="card-align">
                            <div>
                                <p class="earning-amount">{{ $rejected }}</p>
                                <p class="earning-title mb-0">{{__('label.rejected')}}</p>
                            </div>
                            <div class="stat-card-icon">
                                <i class="fa-solid fa-circle-xmark fa-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search & Table -->
            <div class="card custom-border-card">
                <div class="card-header"><i class="fa-solid fa-star mr-2"></i>{{__('label.reviews_ratings')}}</div>
                <div class="card-body">
                    <div class="page-search p-0">
                        <div class="input-group">
                            <input type="text" id="input_search" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search">
                        </div>
                        <div class="sorting w-50">
                            <select class="form-control" id="input_status">
                                <option value="">{{__('label.all_status')}}</option>
                                <option value="0">{{__('label.pending')}}</option>
                                <option value="1">{{__('label.approved')}}</option>
                                <option value="2">{{__('label.rejected')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.content')}}</th>
                                    <th>{{__('label.type')}}</th>
                                    <th>{{__('label.user')}}</th>
                                    <th>{{__('label.rating')}}</th>
                                    <th>{{__('label.review')}}</th>
                                    <th>{{__('label.date')}}</th>
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
                    url: "{{ route('admin.reviews.index') }}",
                    data: function(d) {
                        d.input_status = $('#input_status').val();
                        d.input_search = $('#input_search').val();
                    },
                },
                columns: [
                    { data: 'DT_RowIndex',   name: 'DT_RowIndex',   orderable: false, searchable: false },
                    { data: 'content',       name: 'content',       orderable: false, searchable: false },
                    { data: 'type_badge',    name: 'type_badge',    orderable: false, searchable: false },
                    { data: 'user_info',     name: 'user_info',     orderable: false, searchable: false },
                    { data: 'rating_stars',  name: 'rating_stars',  orderable: false, searchable: false },
                    { data: 'review_text',   name: 'review_text',   orderable: false, searchable: false },
                    { data: 'date',          name: 'date',          orderable: false, searchable: false },
                    { data: 'status_badge',  name: 'status_badge',  orderable: false, searchable: false },
                    { data: 'action',        name: 'action',        orderable: false, searchable: false },
                ],
            });

            $('#input_search').keyup(function() { table.draw(); });
            $('#input_status').change(function() { table.draw(); });
        });

        function approve_review(id) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            Swal.fire({
                theme             : 'dark',
                title             : "{{ __('label.confirm_approve') }}",
                icon              : 'question',
                showCancelButton  : true,
                confirmButtonColor: '#058f00',
                cancelButtonColor : '#e3000b',
                confirmButtonText : "{{ __('label.approve') }}",
                cancelButtonText  : "{{ __('label.cancel') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#dvloader").show();
                    $.ajax({
                        type: 'POST',
                        url : '{{ route("admin.reviews.approve", ":id") }}'.replace(':id', id),
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(resp) {
                            $("#dvloader").hide();
                            get_responce_message(resp, '', '{{ route("admin.reviews.index") }}');
                        },
                        error: function(xhr, status, error) {
                            $("#dvloader").hide();
                            toastr.error(error);
                        }
                    });
                }
            });
        }
        function reject_review(id) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            Swal.fire({
                theme             : 'dark',
                title             : "{{ __('label.confirm_reject') }}",
                icon              : 'warning',
                showCancelButton  : true,
                confirmButtonColor: '#e3000b',
                cancelButtonColor : '#058f00',
                confirmButtonText : "{{ __('label.reject') }}",
                cancelButtonText  : "{{ __('label.cancel') }}",
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#dvloader").show();
                    $.ajax({
                        type: 'POST',
                        url : '{{ route("admin.reviews.reject", ":id") }}'.replace(':id', id),
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(resp) {
                            $("#dvloader").hide();
                            get_responce_message(resp, '', '{{ route("admin.reviews.index") }}');
                        },
                        error: function(xhr, status, error) {
                            $("#dvloader").hide();
                            toastr.error(error);
                        }
                    });
                }
            });
        }
        function deleteReview(url) {
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
    </script>
@endsection
