@extends('admin.layout.page-app')
@section('page_title', __('label.user_dashboard'))
@section('tab_title', __('label.user_dashboard'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- Mobile title -->
            <h1 class="page-title-sm">{{ __('label.user_detail') }}</h1>

            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('label.users') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('label.user_dashboard') }}</li>
                    </ol>
                </div>
            </div>

            <!-- ── Hero Bar ──────────────────────────────────────── -->
            <div class="ud2-hero">
                <img src="{{ $data['image'] }}" class="ud2-hero-avatar" alt="User Avatar">
                <div class="ud2-hero-info">
                    <div class="ud2-hero-name">{{ $data['full_name'] }}</div>
                    <div class="ud2-hero-meta">
                        <span><i class="fa-solid fa-envelope fa-xs"></i> {{ $data['email'] }}</span>
                        <span><i class="fa-solid fa-phone fa-xs"></i> {{ $data['mobile_number'] }}</span>
                        <span><i class="fa-solid fa-calendar fa-xs"></i> Joined {{ $data['created_at']->format('M j, Y') }}</span>
                    </div>
                    <div class="ud2-hero-badges">
                        @if ($data['status'] == 1)
                            <span class="ud2-badge ud2-badge-active"><i class="fa-solid fa-circle fa-xs"></i> Active</span>
                        @else
                            <span class="ud2-badge ud2-badge-inactive"><i class="fa-solid fa-circle fa-xs"></i> Inactive</span>
                        @endif
                        <!-- <span class="ud2-badge ud2-badge-premium"><i class="fa-solid fa-star fa-xs"></i> Premium</span> -->
                        <!-- <span class="ud2-badge ud2-badge-verified"><i class="fa-solid fa-shield-check fa-xs"></i> Verified</span> -->
                    </div>
                </div>
                <div class="ud2-hero-actions">
                    <a href="{{ route('admin.user.edit', $data['id']) }}" class="ud2-btn ud2-btn-edit"><i class="fa-solid fa-pen-to-square fa-sm"></i> Edit Profile</a>
                    <!-- <a href="#" class="ud2-btn ud2-btn-block"><i class="fa-solid fa-ban fa-sm"></i> Block User</a> -->
                </div>
            </div>

            <!-- ── Stats Row ─────────────────────────────────────── -->
            <div class="ud2-stats-row">
                <div class="ud2-stat-card">
                    <div class="ud2-stat-icon ud2-stat-icon-green">
                        <i class="fa-solid fa-film"></i>
                    </div>
                    <div class="ud2-stat-body">
                        <div class="ud2-stat-value">{{ $video_watched ?? 0 }}</div>
                        <div class="ud2-stat-label">Continued Video Watching</div>
                        <!-- <div class="ud2-stat-delta ud2-delta-up"><i class="fa-solid fa-arrow-up fa-xs"></i> 12 this week</div> -->
                    </div>
                </div>
                <!-- <div class="ud2-stat-card">
                    <div class="ud2-stat-icon ud2-stat-icon-blue">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div class="ud2-stat-body">
                        <div class="ud2-stat-value">184h</div>
                        <div class="ud2-stat-label">Watch Time</div>
                        <div class="ud2-stat-delta ud2-delta-up"><i class="fa-solid fa-arrow-up fa-xs"></i> 8h this week</div>
                    </div>
                </div> -->
                <div class="ud2-stat-card">
                    <div class="ud2-stat-icon ud2-stat-icon-purple">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div class="ud2-stat-body">
                        <div class="ud2-stat-value">{{ Currency_Code() }}{{ number_format($data['wallet_amount'], 2) }}</div>
                        <div class="ud2-stat-label">Wallet Balance</div>
                        <!-- <div class="ud2-stat-delta ud2-delta-up"><i class="fa-solid fa-arrow-up fa-xs"></i> +₹200 added</div> -->
                    </div>
                </div>
                <div class="ud2-stat-card">
                    <div class="ud2-stat-icon ud2-stat-icon-orange">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div class="ud2-stat-body">
                        <div class="ud2-stat-value">{{ $bookmarks_item ?? 0 }}</div>
                        <div class="ud2-stat-label">Watchlist Items</div>
                        <!-- <div class="ud2-stat-delta" style="color:#888">No change</div> -->
                    </div>
                </div>
                <!-- <div class="ud2-stat-card">
                    <div class="ud2-stat-icon ud2-stat-icon-red">
                        <i class="fa-solid fa-download"></i>
                    </div>
                    <div class="ud2-stat-body">
                        <div class="ud2-stat-value">14</div>
                        <div class="ud2-stat-label">Downloads</div>
                        <div class="ud2-stat-delta ud2-delta-down"><i class="fa-solid fa-arrow-down fa-xs"></i> 3 less this week</div>
                    </div>
                </div> -->
            </div>

            <!-- ── Middle Row: Profile Info + Activity ───────────── -->
            <div class="ud2-mid-row">
                <!-- Profile Info Card -->
                <div class="ud2-card">
                    <div class="ud2-card-header">
                        <div><i class="fa-solid fa-user"></i>Account Information</div>
                    </div>
                    <div class="ud2-card-body">
                        <div class="ud2-section-label">Personal Details</div>
                        <div class="ud2-info-grid">
                            <div class="ud2-info-item">
                                <div class="ud2-info-label">Full Name</div>
                                <div class="ud2-info-value">{{ $data['full_name'] }}</div>
                            </div>
                            <div class="ud2-info-item">
                                <div class="ud2-info-label">Username</div>
                                <div class="ud2-info-value highlight">{{ $data['user_name'] }}</div>
                            </div>
                            <div class="ud2-info-item">
                                <div class="ud2-info-label">Email</div>
                                <div class="ud2-info-value">{{ $data['email'] }}</div>
                            </div>
                            <div class="ud2-info-item">
                                <div class="ud2-info-label">Phone</div>
                                <div class="ud2-info-value">{{ $data['mobile_number'] }}</div>
                            </div>
                            <div class="ud2-info-item">
                                <div class="ud2-info-label">Reference Code</div>
                                <div class="ud2-info-value highlight">{{ $data['reference_code'] }}</div>
                            </div>
                            <div class="ud2-info-item">
                                <div class="ud2-info-label">Parent Control</div>
                                <div class="ud2-info-value highlight">{{ $data['parent_control_status'] == 1 ? 'Enabled' : 'Disabled' }}</div>
                            </div>
                        </div>

                        <div class="ud2-divider"></div>
                        <div class="ud2-section-label">Referral</div>
                        <div class="ud2-info-grid">
                            <div class="ud2-info-item">
                                <div class="ud2-info-label">Referred By</div>
                                <div class="ud2-info-value">{{ ($parent_user && $parent_user['parent_user'] != null) ? $parent_user['parent_user']['full_name']  : 'N/A' }} ( {{ ($parent_user && $parent_user['parent_user'] != null) ? $parent_user['parent_user']['reference_code']  : '-' }} )</div>
                            </div>
                            <div class="ud2-info-item">
                                <div class="ud2-info-label">Referrals Made</div>
                                <div class="ud2-info-value highlight">{{ $child_user ?? 0 }} users</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Wallet Balance Card -->
                <div class="ud2-card">
                    <div class="ud2-card-header">
                        <div><i class="fa-solid fa-wallet"></i>Wallet</div>
                    </div>
                    <div class="ud2-card-body">
                        <div class="ud2-wallet-inner">
                            <div class="ud2-wallet-label">Available Balance</div>
                            <div class="ud2-wallet-amount">{{ Currency_Code() }} {{ number_format($data['wallet_amount'], 2) }}</div>
                            <div class="ud2-wallet-stats">
                                <div class="ud2-wallet-stat">
                                    <div class="ud2-wallet-stat-val">{{ Currency_Code() }} {{ number_format($wallet_add_amount, 2) }}</div>
                                    <div class="ud2-wallet-stat-key">Total Added</div>
                                </div>
                                <div class="ud2-wallet-stat">
                                    <div class="ud2-wallet-stat-val">{{ Currency_Code() }} {{ number_format($wallet_spent_amount, 2) }}</div>
                                    <div class="ud2-wallet-stat-key">Total Spent</div>
                                </div>
                                <!-- <div class="ud2-wallet-stat">
                                    <div class="ud2-wallet-stat-val">11</div>
                                    <div class="ud2-wallet-stat-key">Transactions</div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Bottom Row: Subscription + Watch-time + Devices ─ -->
            <div class="ud2-bot-row">

                <!-- Subscription -->
                <div class="ud2-card">
                    <div class="ud2-card-header">
                        <div><i class="fa-solid fa-crown"></i>Subscription</div>
                    </div>
                    <div class="ud2-card-body">

                        @if($active_pkg && $active_pkg->package)
                            @php
                                $subStartTs  = strtotime($active_pkg->created_at);
                                $subExpiryTs = strtotime($active_pkg->expiry_date);
                                $subTodayTs  = time();
                                $subTotal    = max((int)(($subExpiryTs - $subStartTs) / 86400), 1);
                                $subElapsed  = min(max((int)(($subTodayTs - $subStartTs) / 86400), 0), $subTotal);
                                $subPct      = round(($subElapsed / $subTotal) * 100);
                                $subExpired  = $subTodayTs > $subExpiryTs;
                                $subColor    = $subExpired ? '#f87171' : '#86efac';
                                $subWidth    = $subPct . '%';
                            @endphp
                            <div class="ud2-sub-inner">
                                <div class="ud2-sub-plan">{{ $active_pkg->package->name }}</div>
                                <div class="ud2-sub-price">
                                    {{ Currency_Code() }} {{ $active_pkg->price }}
                                    <span>/ {{ $active_pkg->package->time }} {{ $active_pkg->package->type }}</span>
                                </div>
                                <div class="ud2-sub-rows">
                                    <div class="ud2-sub-row">
                                        <span class="ud2-sub-row-label">Started</span>
                                        <span class="ud2-sub-row-val">{{ date('M j, Y', $subStartTs) }}</span>
                                    </div>
                                    <div class="ud2-sub-row">
                                        <span class="ud2-sub-row-label">Expires</span>
                                        <span class="ud2-sub-row-val">{{ date('M j, Y', $subExpiryTs) }}</span>
                                    </div>
                                    <div class="ud2-sub-row">
                                        <span class="ud2-sub-row-label">Payment</span>
                                        <span class="ud2-sub-row-val">{{ $active_pkg->payment_type == 1 ? 'Wallet' : 'Online' }}</span>
                                    </div>
                                    <div class="ud2-sub-row">
                                        <span class="ud2-sub-row-label">Status</span>
                                        <span class="ud2-sub-row-val" style="color:{{ $subColor }}">
                                            {{ $subExpired ? 'Expired' : 'Active' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ud2-sub-progress-wrap">
                                    <div class="ud2-sub-progress-label">
                                        <span>Days elapsed</span>
                                        <span>{{ $subElapsed }} / {{ $subTotal }}</span>
                                    </div>
                                    <div class="ud2-sub-progress-bar">
                                        <div class="ud2-sub-progress-fill" style="width:{{ $subWidth }}"></div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="ud2-sub-inner" style="text-align:center;padding:32px 20px;">
                                <div style="font-size:36px;margin-bottom:12px;opacity:0.3;"><i class="fa-solid fa-crown"></i></div>
                                <div style="font-size:15px;font-weight:600;color:#a78bfa;margin-bottom:6px;">No Active Subscription</div>
                                <div style="font-size:12px;color:#a78bfa;opacity:0.7;">This user has no active plan.</div>
                            </div>
                        @endif

                        @if(isset($previous_pkg) && $previous_pkg->count() > 0)
                            <div class="ud2-divider"></div>
                            <div class="ud2-section-label">Previous Plans</div>
                            <div style="display:flex;flex-direction:column;gap:8px;">
                                @foreach($previous_pkg as $prev)
                                    @if($prev->package)
                                        <div style="display:flex;justify-content:space-between;font-size:12px;color:#aaa;">
                                            <span>{{ $prev->package->name }}</span>
                                            <span style="color:#666">
                                                {{ date('M Y', strtotime($prev->created_at)) }}
                                                – {{ date('M Y', strtotime($prev->expiry_date)) }}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>

                <!-- Watch-time Chart -->
                <!-- <div class="ud2-card">
                    <div class="ud2-card-header">
                        <div><i class="fa-solid fa-chart-bar"></i>Watch Time</div>
                        <span style="font-size:11px;color:#666;font-weight:400;">Last 7 days</span>
                    </div>
                    <div class="ud2-card-body">
                        <div class="ud2-chart-total">27.4h</div>
                        <div class="ud2-chart-subtitle">Total watch time this week</div>
                        <div class="ud2-chart-bars">
                            <div class="ud2-bar-wrap">
                                <div class="ud2-bar" style="height:40%">
                                    <span class="ud2-bar-label">Mon</span>
                                </div>
                            </div>
                            <div class="ud2-bar-wrap">
                                <div class="ud2-bar" style="height:60%">
                                    <span class="ud2-bar-label">Tue</span>
                                </div>
                            </div>
                            <div class="ud2-bar-wrap">
                                <div class="ud2-bar" style="height:35%">
                                    <span class="ud2-bar-label">Wed</span>
                                </div>
                            </div>
                            <div class="ud2-bar-wrap">
                                <div class="ud2-bar" style="height:75%">
                                    <span class="ud2-bar-label">Thu</span>
                                </div>
                            </div>
                            <div class="ud2-bar-wrap">
                                <div class="ud2-bar" style="height:55%">
                                    <span class="ud2-bar-label">Fri</span>
                                </div>
                            </div>
                            <div class="ud2-bar active" style="height:90%;border-radius:4px 4px 0 0;width:100%;position:relative">
                                {{-- Saturday - active day, handled differently for layout --}}
                            </div>
                            <div class="ud2-bar-wrap">
                                <div class="ud2-bar active" style="height:90%">
                                    <span class="ud2-bar-label">Sat</span>
                                </div>
                            </div>
                            <div class="ud2-bar-wrap">
                                <div class="ud2-bar" style="height:25%">
                                    <span class="ud2-bar-label">Sun</span>
                                </div>
                            </div>
                        </div>

                        <div class="ud2-divider"></div>
                        <div class="ud2-section-label">Content Breakdown</div>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;font-size:12px;">
                                <span style="color:#aaa"><i class="fa-solid fa-film fa-xs mr-1" style="color:rgba(200,255,0,0.6)"></i>Movies</span>
                                <div style="flex:1;margin:0 10px;height:4px;background:rgba(255,255,255,0.06);border-radius:2px;overflow:hidden;">
                                    <div style="width:52%;height:100%;background:#c8ff00;border-radius:2px"></div>
                                </div>
                                <span style="color:#c8ff00;font-weight:600">52%</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;font-size:12px;">
                                <span style="color:#aaa"><i class="fa-solid fa-tv fa-xs mr-1" style="color:rgba(96,165,250,0.6)"></i>TV Shows</span>
                                <div style="flex:1;margin:0 10px;height:4px;background:rgba(255,255,255,0.06);border-radius:2px;overflow:hidden;">
                                    <div style="width:34%;height:100%;background:#60a5fa;border-radius:2px"></div>
                                </div>
                                <span style="color:#60a5fa;font-weight:600">34%</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;align-items:center;font-size:12px;">
                                <span style="color:#aaa"><i class="fa-solid fa-bolt fa-xs mr-1" style="color:rgba(192,132,252,0.6)"></i>Shorts</span>
                                <div style="flex:1;margin:0 10px;height:4px;background:rgba(255,255,255,0.06);border-radius:2px;overflow:hidden;">
                                    <div style="width:14%;height:100%;background:#c084fc;border-radius:2px"></div>
                                </div>
                                <span style="color:#c084fc;font-weight:600">14%</span>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Device Breakdown -->
                <!-- <div class="ud2-card">
                    <div class="ud2-card-header">
                        <div><i class="fa-solid fa-mobile-screen"></i>Device Usage</div>
                    </div>
                    <div class="ud2-card-body">
                        <div class="ud2-donut-wrap" style="justify-content:center;margin-bottom:20px;">
                            <div class="ud2-donut"></div>
                            <div class="ud2-legend">
                                <div class="ud2-legend-item">
                                    <div class="ud2-legend-dot" style="background:#c8ff00"></div>
                                    Mobile App
                                    <span class="ud2-legend-pct">46%</span>
                                </div>
                                <div class="ud2-legend-item">
                                    <div class="ud2-legend-dot" style="background:#60a5fa"></div>
                                    Smart TV
                                    <span class="ud2-legend-pct">26%</span>
                                </div>
                                <div class="ud2-legend-item">
                                    <div class="ud2-legend-dot" style="background:#c084fc"></div>
                                    Web Browser
                                    <span class="ud2-legend-pct">14%</span>
                                </div>
                                <div class="ud2-legend-item">
                                    <div class="ud2-legend-dot" style="background:#fb923c"></div>
                                    Tablet
                                    <span class="ud2-legend-pct">14%</span>
                                </div>
                            </div>
                        </div>

                        <div class="ud2-divider"></div>
                        <div class="ud2-section-label">Active Sessions</div>
                        <div style="display:flex;flex-direction:column;gap:10px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;background:rgba(200,255,0,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#c8ff00;">
                                        <i class="fa-solid fa-mobile-screen fa-sm"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:12px;color:#ddd;">iPhone 15 Pro</div>
                                        <div style="font-size:11px;color:#666;">iOS 17 · Mumbai</div>
                                    </div>
                                </div>
                                <span style="font-size:10px;color:#4ade80;background:rgba(34,197,94,0.1);padding:2px 8px;border-radius:10px;">Current</span>
                            </div>
                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;background:rgba(96,165,250,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#60a5fa;">
                                        <i class="fa-solid fa-tv fa-sm"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:12px;color:#ddd;">Samsung Smart TV</div>
                                        <div style="font-size:11px;color:#666;">Tizen · Home Network</div>
                                    </div>
                                </div>
                                <span style="font-size:11px;color:#666;">2h ago</span>
                            </div>
                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;background:rgba(192,132,252,0.1);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#c084fc;">
                                        <i class="fa-solid fa-globe fa-sm"></i>
                                    </div>
                                    <div>
                                        <div style="font-size:12px;color:#ddd;">Chrome Browser</div>
                                        <div style="font-size:11px;color:#666;">Windows 11 · Office</div>
                                    </div>
                                </div>
                                <span style="font-size:11px;color:#666;">1d ago</span>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>

            <!-- ── Wallet Row ─────────────────────────────────────── -->
            <div class="ud2-wallet-row">
                <!-- Activity Feed -->
                <!-- <div class="ud2-card">
                    <div class="ud2-card-header">
                        <div><i class="fa-solid fa-bolt"></i>Recent Activity</div>
                        <span style="font-size:11px;color:#666;font-weight:400;">Last 7 days</span>
                    </div>
                    <div class="ud2-card-body" style="padding-top:12px;">
                        <div class="ud2-activity-list">
                            <div class="ud2-activity-item">
                                <div class="ud2-activity-icon ud2-act-watch"><i class="fa-solid fa-play"></i></div>
                                <div class="ud2-activity-body">
                                    <div class="ud2-activity-title">Watched "Oppenheimer" — 3h 00m</div>
                                    <div class="ud2-activity-time">Today, 10:24 AM · Mobile App</div>
                                </div>
                            </div>
                            <div class="ud2-activity-item">
                                <div class="ud2-activity-icon ud2-act-pay"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                                <div class="ud2-activity-body">
                                    <div class="ud2-activity-title">Wallet recharged — ₹200</div>
                                    <div class="ud2-activity-time">Yesterday, 6:15 PM · Razorpay</div>
                                </div>
                            </div>
                            <div class="ud2-activity-item">
                                <div class="ud2-activity-icon ud2-act-watch"><i class="fa-solid fa-play"></i></div>
                                <div class="ud2-activity-body">
                                    <div class="ud2-activity-title">Watched "Sacred Games S2" — E3–E5</div>
                                    <div class="ud2-activity-time">Yesterday, 9:00 PM · Smart TV</div>
                                </div>
                            </div>
                            <div class="ud2-activity-item">
                                <div class="ud2-activity-icon ud2-act-search"><i class="fa-solid fa-magnifying-glass"></i></div>
                                <div class="ud2-activity-body">
                                    <div class="ud2-activity-title">Searched "Bollywood 2024"</div>
                                    <div class="ud2-activity-time">Apr 19, 3:45 PM · Web Browser</div>
                                </div>
                            </div>
                            <div class="ud2-activity-item">
                                <div class="ud2-activity-icon ud2-act-sub"><i class="fa-solid fa-crown"></i></div>
                                <div class="ud2-activity-body">
                                    <div class="ud2-activity-title">Subscription renewed — Premium Annual</div>
                                    <div class="ud2-activity-time">Apr 18, 12:00 AM · Auto-debit</div>
                                </div>
                            </div>
                            <div class="ud2-activity-item">
                                <div class="ud2-activity-icon ud2-act-login"><i class="fa-solid fa-right-to-bracket"></i></div>
                                <div class="ud2-activity-body">
                                    <div class="ud2-activity-title">Logged in from new device</div>
                                    <div class="ud2-activity-time">Apr 17, 8:30 AM · iPhone 15 Pro</div>
                                </div>
                            </div>
                            <div class="ud2-activity-item">
                                <div class="ud2-activity-icon ud2-act-watch"><i class="fa-solid fa-play"></i></div>
                                <div class="ud2-activity-body">
                                    <div class="ud2-activity-title">Watched "Mirzapur S3" — E1–E4</div>
                                    <div class="ud2-activity-time">Apr 16, 11:00 PM · Mobile App</div>
                                </div>
                            </div>
                            <div class="ud2-activity-item">
                                <div class="ud2-activity-icon ud2-act-pay"><i class="fa-solid fa-indian-rupee-sign"></i></div>
                                <div class="ud2-activity-body">
                                    <div class="ud2-activity-title">Rented "Dune Part Two" — ₹79</div>
                                    <div class="ud2-activity-time">Apr 15, 5:10 PM · Wallet</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- Transaction History -->
                <!-- <div class="ud2-card">
                    <div class="ud2-card-header">
                        <div><i class="fa-solid fa-receipt"></i>Transaction History</div>
                        <span style="font-size:11px;color:#666;font-weight:400;">Recent 6</span>
                    </div>
                    <div class="ud2-card-body" style="padding-top:8px;padding-bottom:8px;">
                        <div class="ud2-tx-list">
                            <div class="ud2-tx-item">
                                <div class="ud2-tx-icon ud2-tx-credit"><i class="fa-solid fa-plus"></i></div>
                                <div class="ud2-tx-body">
                                    <div class="ud2-tx-desc">Wallet Recharge — Razorpay</div>
                                    <div class="ud2-tx-date">Apr 20, 2025 · 6:15 PM</div>
                                </div>
                                <div class="ud2-tx-amount cr">+₹200</div>
                            </div>
                            <div class="ud2-tx-item">
                                <div class="ud2-tx-icon ud2-tx-debit"><i class="fa-solid fa-minus"></i></div>
                                <div class="ud2-tx-body">
                                    <div class="ud2-tx-desc">Rented "Dune Part Two"</div>
                                    <div class="ud2-tx-date">Apr 15, 2025 · 5:10 PM</div>
                                </div>
                                <div class="ud2-tx-amount dr">-₹79</div>
                            </div>
                            <div class="ud2-tx-item">
                                <div class="ud2-tx-icon ud2-tx-credit"><i class="fa-solid fa-plus"></i></div>
                                <div class="ud2-tx-body">
                                    <div class="ud2-tx-desc">Referral Bonus — Priya M.</div>
                                    <div class="ud2-tx-date">Apr 10, 2025 · 2:30 PM</div>
                                </div>
                                <div class="ud2-tx-amount cr">+₹50</div>
                            </div>
                            <div class="ud2-tx-item">
                                <div class="ud2-tx-icon ud2-tx-debit"><i class="fa-solid fa-minus"></i></div>
                                <div class="ud2-tx-body">
                                    <div class="ud2-tx-desc">Rented "Animal" Movie</div>
                                    <div class="ud2-tx-date">Apr 5, 2025 · 9:00 PM</div>
                                </div>
                                <div class="ud2-tx-amount dr">-₹49</div>
                            </div>
                            <div class="ud2-tx-item">
                                <div class="ud2-tx-icon ud2-tx-credit"><i class="fa-solid fa-plus"></i></div>
                                <div class="ud2-tx-body">
                                    <div class="ud2-tx-desc">Wallet Recharge — UPI</div>
                                    <div class="ud2-tx-date">Mar 28, 2025 · 11:00 AM</div>
                                </div>
                                <div class="ud2-tx-amount cr">+₹500</div>
                            </div>
                            <div class="ud2-tx-item">
                                <div class="ud2-tx-icon ud2-tx-debit"><i class="fa-solid fa-minus"></i></div>
                                <div class="ud2-tx-body">
                                    <div class="ud2-tx-desc">Premium Annual Subscription</div>
                                    <div class="ud2-tx-date">Mar 20, 2025 · 12:00 AM</div>
                                </div>
                                <div class="ud2-tx-amount dr">-₹999</div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
@endsection
