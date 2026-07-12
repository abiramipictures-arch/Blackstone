@extends('admin.layout.page-app')
@section('tab_title', __('label.login'))

@section('content')
    <div class="dl-root">
        <div class="dl-left">
            <div class="dl-left-bg">
                <img src="{{ Login_Image() }}" alt="" oncontextmenu="return false;">
            </div>
            <div class="dl-left-overlay"></div>
            <div class="dl-left-glow"></div>

            <div class="dl-left-topbar">
                <div class="dl-left-brand">
                    <div class="dl-left-brand-img-wrap">
                        <img src="{{ Tab_Icon() }}" alt="{{ App_Name() }}" oncontextmenu="return false;">
                    </div>
                </div>
                @if(env('DEMO_MODE') == 'ON')
                    <div class="dl-live-pill">
                        <div class="dl-live-dot"></div>
                        <span>{{ env('VERSION') }}</span>
                    </div>
                @endif
            </div>

            <div class="dl-left-body">
                <h1 class="dl-left-headline">
                    Stream Live.<br>
                    Earn More.<br>
                    <em>Scale Fast.</em>
                </h1>

                <p class="dl-left-sub">
                    One powerful dashboard to manage all your live streams, content, subscribers, and revenue in real time.
                </p>

                <div class="dl-chips">
                    <div class="dl-chip"><i class="fa-solid fa-users"></i> User Management</div>
                    <div class="dl-chip"><i class="fa-solid fa-chart-line"></i> Analytics</div>
                    <div class="dl-chip"><i class="fa-solid fa-money-bill-wave"></i> Revenue Tracking</div>
                    <div class="dl-chip"><i class="fa-solid fa-film"></i> Video & Shorts</div>
                    <div class="dl-chip"><i class="fa-solid fa-video"></i> Live Streaming</div>
                </div>

                <div class="dl-stats">
                    <div class="dl-stat-item">
                        <span class="dl-stat-val">24/7</span>
                        <span class="dl-stat-label">Uptime</span>
                    </div>
                    <div class="dl-stat-divider"></div>
                    <div class="dl-stat-item">
                        <span class="dl-stat-val">HD</span>
                        <span class="dl-stat-label">Quality</span>
                    </div>
                    <div class="dl-stat-divider"></div>
                    <div class="dl-stat-item">
                        <span class="dl-stat-val">∞</span>
                        <span class="dl-stat-label">Streams</span>
                    </div>
                    <div class="dl-stat-divider"></div>
                    <div class="dl-stat-item">
                        <span class="dl-stat-val">S3</span>
                        <span class="dl-stat-label">Storage</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dl-right">
            <div class="dl-right-inner">
                <div class="dl-app-brand">
                    <div class="dl-app-brand-name">{{ App_Name() }}</div>
                    <div class="dl-app-brand-panel">
                        Admin Panel
                    </div>
                </div>
                <div class="dl-brand-divider">
                    <div class="dl-brand-divider-line"></div>
                    <div class="dl-brand-divider-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <div class="dl-brand-divider-line"></div>
                </div>
                <div class="dl-form-box">
                    <h2 class="dl-form-heading">{{__('label.welcome_back_admin')}}</h2>
                    <p class="dl-form-sub">{{__('label.please_sign_in_to_your_account')}}</p>
                    @php
                        $emailValue    = env('DEMO_MODE') == 'ON' ? 'admin@admin.com' : '';
                        $passwordValue = env('DEMO_MODE') == 'ON' ? 'admin' : '';
                    @endphp

                    <form id="login_form" autocomplete="off">
                        <div class="dl-field">
                            <label for="dl_email">{{__('label.email')}}</label>
                            <div class="dl-input-wrap">
                                <i class="fa-solid fa-envelope dl-input-ico"></i>
                                <input id="dl_email" class="dl-input" name="email" type="email" value="{{ $emailValue }}" placeholder="{{__('label.email_here')}}" autofocus>
                            </div>
                        </div>
                        <div class="dl-field">
                            <label for="dl_password">{{__('label.password')}}</label>
                            <div class="dl-input-wrap">
                                <i class="fa-solid fa-lock dl-input-ico"></i>
                                <input id="dl_password" class="dl-input" name="password" type="password" value="{{ $passwordValue }}" placeholder="{{__('label.password_here')}}">
                                <button type="button" class="dl-pw-toggle" id="dl_pw_toggle" aria-label="Toggle password visibility">
                                    <i class="fa-solid fa-eye" id="dl_pw_icon"></i>
                                </button>
                            </div>
                        </div>
                        <button class="dl-btn" type="button" id="dl_submit" onclick="save_login()">
                            <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i>
                            {{__('label.login')}}
                        </button>
                    </form>

                    @if(env('DEMO_MODE') == 'ON')
                        <div class="dl-demo">
                            {{__('label.if_you_cannot_login_then')}}
                            <a href="{{ route('admin.login') }}" target="_blank">{{__('label.click_here')}}</a>
                        </div>
                    @endif
                </div>
            </div>

            @if(env('DEMO_MODE') == 'ON')
                <div class="dl-footer">
                    <span class="dl-footer-label">Powered by</span>
                    <a class="dl-dt-mark" href="https://divinetechs.com" target="_blank" rel="noopener" title="DivineTechs">
                        <span class="dl-dt-name">DivineTechs • All rights reserved.</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        // ── Password toggle ─────────────────────────────────────────
        document.getElementById('dl_pw_toggle').addEventListener('click', function () {
            var input = document.getElementById('dl_password');
            var icon  = document.getElementById('dl_pw_icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        });

        // ── Login submit ────────────────────────────────────────────
        function save_login() {
            $("#dvloader").show();

            var formData = new FormData($("#login_form")[0]);
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                url: '{{ route("admin.save.login") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'login_form', '{{ route("admin.dashboard") }}');
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        // ── Enter to submit ─────────────────────────────────────────
        $('#login_form').keypress(function (e) {
            if (e.which === 13) { save_login(); }
        });
    </script>
@endsection
