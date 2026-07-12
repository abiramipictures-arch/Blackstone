<div class="sidebar">
    <div class="side-head">
        <a href="{{ route('admin.dashboard') }}" class="primary-color side-logo">
            <h3>{{ App_Name() }}</h3>
        </a>
        <button class="btn side-toggle" aria-label="Close Sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <ul class="side-menu mt-3">
        <li class="side_line {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}{{ request()->routeIs('admin.profile*') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard')}}">
                <i class="fa-solid fa-house fa-xl menu-icon"></i>
                <span>{{__('label.dashboard')}}</span>
            </a>
        </li>

        <p class="partition"><span>{{__('label.basic_element')}}</span></p>
        <li class="side_line {{ request()->routeIs('admin.type*') ? 'active' : '' }}">
            <a href="{{ route('admin.type.index') }}">
                <i class="fa-solid fa-t fa-xl menu-icon"></i>
                <span>{{__('label.types')}}</span>
            </a>
        </li>
        <li class="dropdown {{ request()->routeIs('admin.category*') ? 'active' : '' }}{{ request()->routeIs('admin.language*') ? 'active' : '' }}{{ request()->routeIs('admin.season*') ? 'active' : '' }}{{ request()->routeIs('admin.avatar*') ? 'active' : '' }}{{ request()->routeIs('admin.channel*') ? 'active' : '' }}">
            <a class="dropdown-toggle" id="dropdownMenuClickable" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-gear fa-xl menu-icon"></i>
                <span>{{__('label.basic_items')}}</span>
            </a>
            <ul class="dropdown-menu side-submenu {{ request()->routeIs('admin.category*') ? 'show' : '' }}{{ request()->routeIs('admin.language*') ? 'show' : '' }}{{ request()->routeIs('admin.season*') ? 'show' : '' }}{{ request()->routeIs('admin.avatar*') ? 'show' : '' }}{{ request()->routeIs('admin.channel*') ? 'show' : '' }}" aria-labelledby="dropdownMenuClickable">
                <li class="side_line {{ request()->routeIs('admin.category*') ? 'active' : '' }}">
                    <a href="{{ route('admin.category.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-list fa-xl submenu-icon"></i>
                        <span>{{__('label.category')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.language*') ? 'active' : '' }}">
                    <a href="{{ route('admin.language.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-globe fa-xl submenu-icon"></i>
                        <span>{{__('label.language')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.season*') ? 'active' : '' }}">
                    <a href="{{ route('admin.season.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-list-ol fa-xl submenu-icon"></i>
                        <span>{{__('label.season')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.avatar*') ? 'active' : '' }}">
                    <a href="{{ route('admin.avatar.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-user-plus fa-xl submenu-icon"></i>
                        <span>{{__('label.avatar')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.channel*') ? 'active' : '' }}">
                    <a href="{{ route('admin.channel.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-tower-broadcast fa-xl submenu-icon"></i>
                        <span>{{__('label.channel')}}</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="side_line {{ request()->routeIs('admin.user*') ? 'active' : '' }}">
            <a href="{{ route('admin.user.index') }}">
                <i class="fa-solid fa-users fa-xl menu-icon"></i>
                <span>{{__('label.users')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.producer*') ? 'active' : '' }}">
            <a href="{{ route('admin.producer.index') }}">
                <i class="fa-solid fa-user-shield fa-xl menu-icon"></i>
                <span>{{__('label.producer')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.cast*') ? 'active' : '' }}">
            <a href="{{ route('admin.cast.index') }}">
                <i class="fa-solid fa-user-tie fa-xl menu-icon"></i>
                <span>{{__('label.cast')}}</span>
            </a>
        </li>

        <p class="partition"><span>{{__('label.configuration')}}</span></p>
        <li class="side_line {{ request()->routeIs('admin.banner*') ? 'active' : '' }}">
            <a href="{{ route('admin.banner.index') }}">
                <i class="fa-solid fa-panorama fa-xl menu-icon"></i>
                <span>{{__('label.banner')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.section*') ? 'active' : '' }}">
            <a href="{{ route('admin.section.index') }}">
                <i class="fa-solid fa-layer-group fa-xl menu-icon"></i>
                <span>{{__('label.section')}}</span>
            </a>
        </li>

        <p class="partition"><span>{{__('label.content')}}</span></p>
        <?php $type = Get_Type_List(); ?>
        @foreach($type as $value)

            @if($value['type'] == 1)
                <li class="side_line {{ request('type_id') == $value['id'] ? 'active' : '' }}">
                    <a href="{{ route('admin.video.index', ['type_id' => $value['id'] ]) }}">
                        <i class="fa-solid fa-video fa-xl menu-icon"></i>
                        <span>{{ $value['name'] }}</span>
                    </a>
                </li>
            @endif

            @if($value['type'] == 2)
                <li class="side_line {{ request('type_id') == $value['id'] ? 'active' : '' }}">
                    <a href="{{ route('admin.tvshow.index', ['type_id' => $value['id'] ]) }}">
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
                        <li class="side_line {{ request('type_id') == $value['id'] && request()->routeIs('admin.video*') ? 'active' : '' }}">
                            <a href="{{ route('admin.video.index', ['type_id' => $value['id'] ]) }}" class="dropdown-item">
                                <i class="fa-solid fa-video fa-xl submenu-icon"></i>
                                <span>{{__('label.movies')}}</span>
                            </a>
                        </li>
                        <li class="side_line {{ request('type_id') == $value['id'] && request()->routeIs('admin.tvshow*') ? 'active' : '' }}">
                            <a href="{{ route('admin.tvshow.index', ['type_id' => $value['id'] ])}}" class="dropdown-item">
                                <i class="fa-solid fa-tv fa-xl submenu-icon"></i>
                                <span>{{__('label.tv_show')}}</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

            @if($value['type'] == 8)
                <li class="side_line {{ request('type_id') == $value['id'] ? 'active' : '' }}">
                    <a href="{{ route('admin.shorts.index', ['type_id' => $value['id'] ]) }}">
                        <i class="fa-solid fa-bolt fa-xl menu-icon"></i>
                        <span>{{ $value['name'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach

        <p class="partition"><span>{{__('label.interaction')}}</span></p>
        <li class="side_line {{ request()->routeIs('admin.notification.*') ? 'active' : '' }}">
            <a href="{{ route('admin.notification.index') }}">
                <i class="fa-solid fa-bell fa-xl menu-icon"></i>
                <span>{{__('label.notification')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
            <a href="{{ route('admin.reviews.index') }}">
                <i class="fa-solid fa-star fa-xl menu-icon"></i>
                <span>{{__('label.reviews')}}</span>
                @php $pending_reviews = \Illuminate\Support\Facades\DB::table('tbl_review')->where('status', 0)->count(); @endphp
                @if($pending_reviews > 0)
                    <span class="badge badge-warning text-dark ml-2" style="font-size:12px;">{{ $pending_reviews }}</span>
                @endif
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.refer-earn*') ? 'active' : '' }}">
            <a href="{{ route('admin.refer-earn.index') }}">
                <i class="fa-solid fa-gift fa-xl menu-icon"></i>
                <span>{{__('label.refer_earn')}}</span>
            </a>
        </li>

        <p class="partition"><span>{{__('label.financial')}}</span></p>
        <li class="side_line {{ request()->routeIs('admin.coupon*') ? 'active' : '' }}">
            <a href="{{ route('admin.coupon.index') }}">
                <i class="fa-solid fa-ticket fa-xl menu-icon"></i>
                <span>{{__('label.coupon')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.rent-price-list*') ? 'active' : '' }}">
            <a href="{{ route('admin.rent-price-list.index') }}">
                <i class="fa-solid fa-money-check-dollar fa-xl menu-icon"></i>
                <span>{{__('label.rent_price_list')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.rent-transaction*') ? 'active' : '' }}">
            <a href="{{ route('admin.rent-transaction.index') }}">
                <i class="fa-solid fa-wallet fa-xl menu-icon"></i>
                <span>{{__('label.rent_transaction')}}</span>
            </a>
        </li>
        <li class="dropdown {{ request()->routeIs('admin.package*') ? 'active' : '' }}{{ request()->routeIs('admin.payment*') ? 'active' : '' }}{{ request()->routeIs('admin.transaction*') ? 'active' : '' }}">
            <a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa-solid fa-money-bill fa-xl menu-icon"></i>
                <span>{{__('label.subscription')}}</span>
            </a>
            <ul class="dropdown-menu side-submenu {{ request()->routeIs('admin.package*') ? 'show' : '' }}{{ request()->routeIs('admin.payment*') ? 'show' : '' }}{{ request()->routeIs('admin.transaction*') ? 'show' : '' }}">
                <li class="side_line {{ request()->routeIs('admin.package*') ? 'active' : '' }}">
                    <a href="{{ route('admin.package.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-box-archive fa-xl submenu-icon"></i>
                        <span>{{__('label.package')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.transaction*') ? 'active' : '' }}">
                    <a href="{{ route('admin.transaction.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-wallet fa-xl submenu-icon"></i>
                        <span>{{__('label.transactions')}}</span>
                    </a>
                </li>
                <li class="side_line {{ request()->routeIs('admin.payment*') ? 'active' : '' }}">
                    <a href="{{ route('admin.payment.index') }}" class="dropdown-item">
                        <i class="fa-solid fa-money-bill-wave fa-xl submenu-icon"></i>
                        <span>{{__('label.payment')}}</span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="side_line {{ request()->routeIs('admin.wallet-transaction*') ? 'active' : '' }}">
            <a href="{{ route('admin.wallet-transaction.index') }}">
                <i class="fa-solid fa-coins fa-xl menu-icon"></i>
                <span>{{__('label.wallet_transactions')}}</span>
            </a>
        </li>
        <li class="side_line {{ (request()->routeIs('admin.withdrawal*')) ? 'active' : '' }}">
            <a href="{{ route('admin.withdrawal.index') }}">
                <i class="fa-solid fa-right-left fa-xl menu-icon"></i>
                <span>{{__('label.withdrawal')}}</span>
            </a>
        </li>

        <p class="partition"><span>{{__('label.ads')}}</span></p>
        <li class="side_line {{ request()->routeIs('admin.admob*') ? 'active' : '' }}">
            <a href="{{ route('admin.admob.index') }}">
                <i class="fa-brands fa-square-google-plus fa-xl menu-icon"></i>
                <span>{{__('label.admob')}}</span>
            </a>
        </li>

        <p class="partition"><span>{{__('label.settings')}}</span></p>
        <li class="side_line {{ request()->routeIs('admin.appsetting*') ? 'active' : '' }}">
            <a href="{{ route('admin.appsetting') }}">
                <i class="fa-solid fa-gear fa-xl menu-icon"></i>
                <span>{{__('label.app_settings')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.panel.setting*') ? 'active' : '' }}">
            <a href="{{ route('admin.panel.setting.index') }}">
                <i class="fa-solid fa-palette fa-xl menu-icon"></i>
                <span>{{__('label.panel_settings')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.system.setting*') ? 'active' : '' }}">
            <a href="{{ route('admin.system.setting.index') }}">
                <i class="fa-solid fa-screwdriver-wrench fa-xl menu-icon"></i>
                <span>{{__('label.system_settings')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.notificationconfiguration*') ? 'active' : '' }}">
            <a href="{{ route('admin.notificationconfiguration.index') }}">
                <i class="fa-solid fa-bell fa-xl menu-icon"></i>
                <span>{{__('label.notification_setting')}}</span>
            </a>
        </li>
        <li class="side_line {{ request()->routeIs('admin.page*') ? 'active' : '' }}">
            <a href="{{ route('admin.page.index') }}">
                <i class="fa-solid fa-book-open-reader fa-xl menu-icon"></i>
                <span>{{__('label.page')}}</span>
            </a>
        </li>

        <p class="partition"><span>{{__('label.logout')}}</span></p>
        <li>
            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-arrow-right-from-bracket fa-xl menu-icon"></i>
                <span>{{__('label.logout')}}</span>
            </a>

            <form id="logout-form" action="{{ route('admin.logout') }}" method="GET" class="d-none">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </li>
    </ul>
</div>
