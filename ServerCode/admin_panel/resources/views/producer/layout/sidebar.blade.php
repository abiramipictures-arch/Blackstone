<div class="sidebar">
    <div class="side-head">
        <a href="{{ route('producer.dashboard') }}" class="primary-color side-logo">
            <h3>{{ App_Name() }}</h3>
        </a>
        <button class="btn side-toggle" aria-label="Close Sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
 
    <ul class="side-menu mt-3">
        <li class="side_line {{ request()->routeIs('producer.dashboard') ? 'active' : '' }}">
            <a href="{{ route('producer.dashboard')}}">
                <i class="fa-solid fa-house fa-xl menu-icon"></i>
                <span>{{__('label.dashboard')}}</span>
            </a>
        </li>

        <p class="partition"><span>{{__('label.profile')}}</span></p>
        <li class="side_line {{ request()->routeIs('producer.profile*') ? 'active' : '' }}">
            <a href="{{ route('producer.profile.index')}}">
                <i class="fa-solid fa-user fa-xl menu-icon"></i>
                <span>{{__('label.profile')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('producer.change-password*') ? 'active' : '' }}">
            <a href="{{ route('producer.change-password.index')}}">
                <i class="fa-solid fa-lock fa-xl menu-icon"></i>
                <span>{{__('label.change_password')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('producer.channel*') ? 'active' : '' }}">
            <a href="{{ route('producer.channel.index')}}">
                <i class="fa-solid fa-tower-broadcast fa-xl menu-icon"></i>
                <span>{{__('label.channel')}}</span>
            </a>
        </li>

        <?php $type = Get_Type_List(); ?>
        <p class="partition"><span>{{__('label.content')}}</span></p>
        @foreach($type as $value)
            @if($value['type'] == 1)
                <li class="side_line {{ request('type_id') == $value['id'] ? 'active' : '' }}">
                    <a href="{{ route('producer.video.index', ['type_id' => $value['id'] ]) }}">
                        <i class="fa-solid fa-video fa-xl menu-icon"></i> 
                        <span>{{ $value['name'] }}</span>
                    </a>
                </li>
            @endif
            @if($value['type'] == 2)
                <li class="side_line {{ request('type_id') == $value['id'] ? 'active' : '' }}">
                    <a href="{{ route('producer.tvshow.index', ['type_id' => $value['id'] ]) }}">
                        <i class="fa-solid fa-tv fa-xl menu-icon"></i>
                        <span>{{ $value['name'] }}</span>
                    </a>
                </li>
            @endif
            @if($value['type'] == 5 || $value['type'] == 6 || $value['type'] == 7)
                <li class="dropdown {{ request('type_id') == $value['id'] ? 'active' : '' }}">
                    <a class="dropdown-toggle" id="dropdownMenuClickable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if($value['type'] == 5)
                            <i class="fa-solid fa-clapperboard fa-xl menu-icon"></i>
                        @elseif($value['type'] == 6)
                            <i class="fa-solid fa-film fa-xl menu-icon"></i>
                        @elseif($value['type'] == 7)
                            <i class="fa-solid fa-children fa-xl menu-icon"></i>
                        @endif
                        <span>{{ $value['name'] }}</span>
                    </a>
                    <ul class="dropdown-menu side-submenu {{ request('type_id') == $value['id'] ? 'show' : '' }}" aria-labelledby="dropdownMenuClickable">
                        <li class="side_line {{ request('type_id') == $value['id'] && request()->routeIs('producer.video*') ? 'active' : '' }}">
                            <a href="{{ route('producer.video.index', ['type_id' => $value['id'] ]) }}" class="dropdown-item">
                                <i class="fa-solid fa-video fa-xl submenu-icon"></i>
                                <span>{{__('label.movies')}}</span>
                            </a>
                        </li>
                        <li class="side_line {{ request('type_id') == $value['id'] && request()->routeIs('producer.tvshow*') ? 'active' : '' }}">
                            <a href="{{ route('producer.tvshow.index', ['type_id' => $value['id'] ])}}" class="dropdown-item">
                                <i class="fa-solid fa-tv fa-xl submenu-icon"></i>
                                <span>{{__('label.tv_show')}}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if($value['type'] == 8)
                <li class="side_line {{ request('type_id') == $value['id'] ? 'active' : '' }}">
                    <a href="{{ route('producer.shorts.index', ['type_id' => $value['id'] ]) }}">
                        <i class="fa-solid fa-bolt fa-xl menu-icon"></i>
                        <span>{{ $value['name'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach

        <p class="partition"><span>{{__('label.financial')}}</span></p>
        <li class="side_line {{ request()->routeIs('producer.rent-transaction*') ? 'active' : '' }}">
            <a href="{{ route('producer.rent-transaction.index') }}">
                <i class="fa-solid fa-wallet fa-xl menu-icon"></i>
                <span>{{__('label.rent_transaction')}}</span>
            </a>
        </li>
        <li class="side_line {{ (request()->routeIs('producer.withdrawal*')) ? 'active' : '' }}">
            <a href="{{ route('producer.withdrawal.index') }}">
                <i class="fa-solid fa-right-left fa-xl menu-icon"></i>
                <span>{{__('label.withdrawal')}}</span>
            </a>
        </li>

        <p class="partition"><span>{{__('label.logout')}}</span></p>
        <li>
            <a href="{{ route('producer.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-arrow-right-from-bracket fa-xl menu-icon"></i>
                <span>{{__('label.logout')}}</span>
            </a>

            <form id="logout-form" action="{{ route('producer.logout') }}" method="GET" class="d-none">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </li>
    </ul>
</div>