@extends('admin.layout.page-app')
@section('page_title', __('label.producer_content'))
@section('tab_title', __('label.producer_content'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <h1 class="page-title-sm">{{__('label.producer_content')}}</h1>

            <div class="row">
                <div class="col-sm-10">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.producer.index') }}">{{__('label.producer')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.producer_content')}}</li>
                    </ol>
                </div>
                <div class="col-sm-2">
                    <a href="{{ route('admin.producer.index') }}" class="btn btn-default-white mw-120">{{__('label.producer_list')}}</a>
                </div>
            </div>

            @if(isset($producer) && $producer)
                <div class="pc-producer-header">
                    <img src="{{ $producer->image }}" class="pc-producer-avatar">
                    <div class="pc-producer-info">
                        <h4>{{ $producer->full_name ?? '-' }}</h4>
                        <span>{{ $producer->user_name ?? '-' }}</span>
                    </div>
                </div>
            @endif

            <div class="card custom-border-card">
                <div class="card-header">
                    <a href="{{ route('admin.producer.content', ['producer_id' => $producer_id, 'content_type' => 1]) }}" class="pc-tab mr-4 {{ $content_type == 1 ? 'active' : '' }}">
                        <i class="fa-solid fa-video fa-md"></i> {{__('label.video')}}
                        <span class="pc-tab-count">{{ $videos_count }}</span>
                    </a>
                    <a href="{{ route('admin.producer.content', ['producer_id' => $producer_id, 'content_type' => 2]) }}" class="pc-tab mr-4 {{ $content_type == 2 ? 'active' : '' }}">
                        <i class="fa-solid fa-tv fa-md"></i> {{__('label.tvshow')}}
                        <span class="pc-tab-count">{{ $tvshows_count }}</span>
                    </a>
                    <a href="{{ route('admin.producer.content', ['producer_id' => $producer_id, 'content_type' => 3]) }}" class="pc-tab {{ $content_type == 3 ? 'active' : '' }}">
                        <i class="fa-solid fa-bolt fa-md"></i> {{__('label.shorts')}}
                        <span class="pc-tab-count">{{ $shorts_count }}</span>
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.producer.content', ['producer_id' => $producer_id, 'content_type' => $content_type]) }}" method="GET" id="pc-filter-form">
                        <div class="page-search p-0">
                            <div class="input-group">
                                <input type="text" id="input_search" value="{{ request('input_search') }}" class="form-control" placeholder="{{__('label.search')}}" aria-label="Search">
                            </div>
                            <div class="sorting w-50">
                                <select class="form-control" name="input_status" onchange="document.getElementById('pc-filter-form').submit()">
                                    <option value="all" {{ request('input_status', 'all') == 'all' ? 'selected' : '' }}>{{__('label.all_status')}}</option>
                                    <option value="1"   {{ request('input_status') === '1' ? 'selected' : '' }}>{{__('label.show')}}</option>
                                    <option value="0"   {{ request('input_status') === '0' ? 'selected' : '' }}>{{__('label.hide')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="page-search p-0 mt-2">
                            @if($content_type == 1 || $content_type == 2)
                                <div class="sorting w-50 p-0">
                                    <select class="form-control" name="input_rent" onchange="document.getElementById('pc-filter-form').submit()">
                                        <option value="0" {{ request('input_rent', '0') == '0' ? 'selected' : '' }}>{{__('label.all_video')}}</option>
                                        <option value="1" {{ request('input_rent') == '1' ? 'selected' : '' }}>{{__('label.rent_video')}}</option>
                                    </select>
                                </div>
                            @endif
                            @if($content_type == 1)
                                <div class="sorting w-50">
                                    <select class="form-control" name="input_premimum" onchange="document.getElementById('pc-filter-form').submit()">
                                        <option value="all" {{ request('input_premimum', 'all') == 'all' ? 'selected' : '' }}>{{__('label.all_video')}}</option>
                                        <option value="0"   {{ request('input_premimum') === '0' ? 'selected' : '' }}>{{__('label.non_premium')}}</option>
                                        <option value="1"   {{ request('input_premimum') === '1' ? 'selected' : '' }}>{{__('label.premium')}}</option>
                                    </select>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-default mw-120 ml-2 mr-2">
                                <i class="fa-solid fa-filter fa-md mr-1"></i>{{__('label.search')}}
                            </button>
                            @if(request()->hasAny(['input_search','input_rent','input_premimum','input_status']))
                                <a href="{{ route('admin.producer.content', ['producer_id' => $producer_id, 'content_type' => $content_type]) }}" class="btn btn-cancel">
                                    <i class="fa-solid fa-xmark fa-md"></i> Reset
                                </a>
                            @endif
                        </div>
                    </form>
                    
                    @if($result->count() > 0)
                        <div class="pc-list mt-4">
                            @foreach($result as $value)
                            <div class="pc-row">
                                <div class="pc-row-num">{{ $result->firstItem() + $loop->index }}</div>
                                <div class="pc-thumb">
                                    <img src="{{ $value->thumbnail }}">
                                </div>

                                <div class="pc-info">
                                    @if(isset($value->type) && $value->type)
                                        <div class="pc-info-type">{{ $value->type->name }}</div>
                                    @endif

                                    <div class="pc-info-title" title="{{ $value->name }}">{{ $value->name }}</div>

                                    <div class="pc-info-meta">
                                        <span class="pc-meta-chip">
                                            <i class="fa-regular fa-eye"></i>
                                            {{ No_Format($value->total_view ?? 0) }}
                                        </span>
                                        @if($content_type != 3 && isset($value->avg_rating) && $value->avg_rating > 0)
                                        <span class="pc-meta-chip">
                                            <i class="fa-solid fa-star"></i>
                                            {{ number_format($value->avg_rating, 1) }}
                                        </span>
                                        @endif
                                        @if($content_type == 1 && !empty($value->is_premium) && $value->is_premium == 1)
                                        <span class="pc-badge">
                                            <i class="fa-solid fa-crown fa-md"></i> {{__('label.premium')}}
                                        </span>
                                        @endif
                                        @if($content_type != 3 && !empty($value->is_rent) && $value->is_rent == 1)
                                        <span class="pc-badge">
                                            <i class="fa-solid fa-lock fa-md"></i> {{__('label.rent_content')}}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="pc-actions">
                                    @if($value->status == 1)
                                        <label id='{{ $value->id }}' class='status-toggle status-on' onclick="change_status('{{ $content_type }}', '{{ $value->id }}')"><span class='status-toggle-track'><span class='status-toggle-thumb'></span></span></label>
                                    @else
                                        <label id='{{ $value->id }}' class='status-toggle status-off' onclick="change_status('{{ $content_type }}', '{{ $value->id }}')"><span class='status-toggle-track'><span class='status-toggle-thumb'></span></span></label>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="pc-pagination">
                            <span>Showing {{ $result->firstItem() }}–{{ $result->lastItem() }} of {{ $result->total() }}</span>
                            {{ $result->appends(request()->query())->links() }}
                        </div>

                    @else
                        <div class="pc-empty">
                            @if($content_type == 1) <i class="fa-solid fa-video"></i>
                            @elseif($content_type == 2) <i class="fa-solid fa-tv"></i>
                            @else <i class="fa-solid fa-bolt"></i>
                            @endif
                            <p>No content found{{ request()->hasAny(['input_search','input_rent','input_premimum','input_status']) ? ' matching your filters' : '' }}.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        function change_status(content_type, id) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            $("#dvloader").show();
            $.ajax({
                type: "post",
                url: "{{ route('admin.producer.content_status') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    content_type:content_type,
                    id:id,
                },
                success: function(resp) {
                    $("#dvloader").hide();
                    if (resp.status == 200) {
                        if (resp.status_code == 1) {
                            $('#'+ id).removeClass('status-off').addClass('status-on');
                        } else {
                            $('#'+ id).removeClass('status-on').addClass('status-off');
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
