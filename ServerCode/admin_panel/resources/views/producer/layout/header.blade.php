<header class="header">
    <div class="title-control">
        <button class="btn side-toggle" aria-label="Toggle Sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <a href="{{route('producer.dashboard')}}" class="side-logo primary-color">
            <h3>{{ App_Name() }}</h3>
        </a>
 
        <h1 class="page-title">@yield('page_title')</h1>

        @if( env('DEMO_MODE') == 'ON')
            <div class="version-badge ml-2">
                <span>v{{ env('VERSION', '1.0') }}</span>
            </div>
        @endif
    </div>
    <div class="head-control">

        @if( env('DEMO_MODE') == 'ON')
            <div class="demo-mode-box mr-2">
                <span>{{__('label.demo_mode')}}</span>
            </div>
        @endif

        <!-- Profile -->
        <div class="dropdown dropright">
            <a href="#" class="btn head-btn head-avatar-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-user fa-xl primary-color" class="avatar-img"></i>
            </a>

            <div class="dropdown-menu p-2 mt-2" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item assest-color" href="{{ route('producer.profile.index') }}">
                    <span><i class="fa-solid fa-user fa-xl mr-2"></i></span>
                    {{__('label.profile')}}
                </a>
                <a class="dropdown-item assest-color" href="{{ route('producer.logout') }}">
                    <span><i class="fa-solid fa-arrow-right-from-bracket fa-xl mr-2"></i></span>
                    {{__('label.logout')}}
                </a>
            </div>
        </div>
    </div>
</header>