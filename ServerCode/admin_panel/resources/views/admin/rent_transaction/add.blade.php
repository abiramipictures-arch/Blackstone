@extends('admin.layout.page-app')
@section('page_title', __('label.add_rent_transaction'))
@section('tab_title', __('label.add_rent_transaction'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <!-- Select2 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" />

        <div class="body-content">
            <!-- mobile title -->
            <h1 class="page-title-sm">{{__('label.add_rent_transaction')}}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.rent-transaction.index') }}">{{__('label.rent_transaction')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.add_rent_transaction')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.rent-transaction.index') }}" class="btn btn-default-white mw-120">{{__('label.rent_transaction')}}</a>
                </div>
            </div>

            <div class="card custom-border-card mb-3">
                <div class="card-header"><i class="fa-solid fa-magnifying-glass mr-2"></i>{{__('label.search_user')}}</div>
                <div class="card-body">
                    <form enctype="multipart/form-data" id="search_user">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-row">
                            <div class="col-md-8">
                                <div class="form-group mb-0">
                                    <input name="name" type="text" class="form-control" id="name" placeholder="{{__('label.search_user_name_or_mobile')}}" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-default mw-120 mr-2" onclick="search_user()">{{__('label.search')}}</button>
                                <a href="{{route('admin.rent-transaction.create')}}" class="btn btn-cancel mw-120">{{__('label.clear')}}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($user->id))
                <div class="card custom-border-card mb-3">
                    <div class="card-header"><i class="fa-solid fa-receipt mr-2"></i>{{__('label.add_rent_transaction')}}</div>
                    <div class="card-body">
                        <form enctype="multipart/form-data" id="add_transaction">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">                            
                            <input name="user_id" type="hidden" value="{{$user->id}}">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('label.name')}}</label>
                                        <input name="full_name" type="text" class="form-control" readonly value="{{$user->full_name ?? '-'}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('label.mobile_number')}}</label>
                                        <input name="mobile_number" type="text" class="form-control" readonly value="{{$user->mobile_number ?? '-'}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('label.email')}}</label>
                                        <input name="email" type="text" class="form-control" readonly value="{{$user->email ?? '-'}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('label.rent_videos')}}</label>
                                        <select name="rent_video_id" class="form-control" id="rent_video_id">
                                            <option value="">{{__('label.select_video')}}</option>
                                            @foreach($rent_video as $row)
                                                <option value="{{$row->id}}">{{$row->name}} - {{$row->rent_price_list->price ?? 0}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('label.rent_tvshows')}}</label>
                                        <select name="rent_show_id" class="form-control" id="rent_show_id">
                                            <option value="">{{__('label.select_show')}}</option>
                                            @foreach($rent_tv_show as $row)
                                                <option value="{{$row->id}}">{{$row->name}} - {{$row->rent_price_list->price ?? 0}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">{{__('label.transaction_note')}}</small>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-default mw-120" onclick="add_transaction()">
                            <i class="fa-solid fa-floppy-disk mr-1"></i>{{__('label.save')}}
                        </button>
                        <a href="{{route('admin.rent-transaction.index')}}" class="btn btn-cancel mw-120 ml-2">{{__('label.cancel')}}</a>
                    </div>
                </div>
            @else
                <div class="card custom-border-card">
                    <div class="card-header"><i class="fa-solid fa-users mr-2"></i>{{__('label.user_list')}}</div>
                    <div class="card-body">
                        <div id="user_list"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('pagescript')
    <!-- Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#rent_video_id").select2({ width: '100%' });
            $("#rent_show_id").select2({ width: '100%' });
        });

        function add_transaction() {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            var formData = new FormData($("#add_transaction")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.rent-transaction.store") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'add_transaction', '{{ route("admin.rent-transaction.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        function search_user() {
            $("#dvloader").show();
            var formData = new FormData($("#search_user")[0]);
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.rentSearchUser") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(resp) {
                    $("#dvloader").hide();
                    $('#user_list').html(resp.result);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }
    </script>
@endsection
