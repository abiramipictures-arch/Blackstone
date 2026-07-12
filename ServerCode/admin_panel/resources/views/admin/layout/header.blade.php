<header class="header">
    <div class="title-control">
        <button class="btn side-toggle" aria-label="Toggle Sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <a href="{{ route('admin.dashboard') }}" class="side-logo primary-color">
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

        <!-- Language -->
        <div class="dropdown dropright">
            <a href="#" class="btn head-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="{{ __('label.language') ?? 'Language' }}">
                <i class="fa-solid fa-language fa-xl primary-color"></i>
            </a>

            <div class="dropdown-menu p-2 mt-2" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item assest-color" href="{{ route('change.language', ['locale' => 'en']) }}">
                    <i class="fa-solid fa-circle-dot fa-xs mr-2"></i> English
                </a>
                <a class="dropdown-item assest-color" href="{{ route('change.language', ['locale' => 'hi']) }}">
                    <i class="fa-solid fa-circle-dot fa-xs mr-2"></i> Hindi
                </a>
                <a class="dropdown-item assest-color" href="{{ route('change.language', ['locale' => 'fr']) }}">
                    <i class="fa-solid fa-circle-dot fa-xs mr-2"></i> French
                </a>
            </div>
        </div>

        <!-- Profile -->
        <div class="dropdown dropright">
            <a href="#" class="btn head-btn head-avatar-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-user fa-xl primary-color" class="avatar-img"></i>
            </a>

            <div class="dropdown-menu p-2 mt-2" aria-labelledby="dropdownMenuLink">
                <a class="dropdown-item assest-color" href="{{ route('admin.profile.index') }}">
                    <span><i class="fa-solid fa-user fa-xl mr-2"></i></span>
                    {{__('label.profile')}}
                </a>
                <a class="dropdown-item assest-color" href="{{ route('admin.logout') }}">
                    <span><i class="fa-solid fa-arrow-right-from-bracket fa-xl mr-2"></i></span>
                    {{__('label.logout')}}
                </a>
            </div>
        </div>
    </div>
</header>
