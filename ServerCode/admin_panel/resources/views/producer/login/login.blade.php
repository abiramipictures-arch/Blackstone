@extends('producer.layout.page-app')
@section('tab_title', __('label.login'))

@section('content')
    <div class="lpp-root">
        <div class="lpp-bg">
            <div class="lpp-orb lpp-orb-1"></div>
            <div class="lpp-orb lpp-orb-2"></div>
            <div class="lpp-orb lpp-orb-3"></div>
        </div>
        <div class="lpp-grid"></div>

        <div class="lpp-card">

            <div class="lpp-brand">
                <div class="lpp-brand-logo-wrap">
                    <img src="{{ Tab_Icon() }}" class="lpp-brand-logo" alt="{{ App_Name() }}" oncontextmenu="return false;">
                </div>

                <div class="lpp-brand-name">Producer Portal</div>
                <div class="lpp-brand-sep"></div>
                @if(env('DEMO_MODE') == 'ON')
                    <div class="lpp-brand-tag">
                        <div class="lpp-dot"></div>
                        {{ env('VERSION') }}
                    </div>
                @endif
            </div>

            <h2 class="lpp-heading">{{ __('label.welcome_back_producer') }}</h2>
            <p class="lpp-sub">{{ __('label.please_sign_in_to_your_account') }}</p>

            @php
                $emailValue    = env('DEMO_MODE') == 'ON' ? 'producer@producer.com' : '';
                $passwordValue = env('DEMO_MODE') == 'ON' ? 'producer' : '';
            @endphp

            <form id="login_form" autocomplete="off">
                <div class="lpp-field">
                    <label for="lpp_email">{{ __('label.email') }}</label>
                    <div class="lpp-input-wrap">
                        <i class="fa-solid fa-envelope lpp-ico"></i>
                        <input id="lpp_email" class="lpp-input" name="email" type="email" value="{{ $emailValue }}" placeholder="{{ __('label.email_here') }}" autofocus>
                    </div>
                </div>

                <div class="lpp-field">
                    <label for="lpp_password">{{ __('label.password') }}</label>
                    <div class="lpp-input-wrap">
                        <i class="fa-solid fa-lock lpp-ico"></i>
                        <input id="lpp_password" class="lpp-input" name="password" type="password" value="{{ $passwordValue }}" placeholder="{{ __('label.password_here') }}">
                        <button type="button" class="lpp-pw-btn" id="lpp_pw_toggle" aria-label="Toggle password visibility">
                            <i class="fa-solid fa-eye" id="lpp_pw_icon"></i>
                        </button>
                    </div>
                </div>

                <button class="lpp-btn" type="button" id="lpp_submit" onclick="save_login()">
                    <i class="fa-solid fa-arrow-right-to-bracket fa-lg mr-2"></i>
                    {{__('label.login')}}
                </button>
            </form>

            @if(env('DEMO_MODE') == 'ON')
                <div class="lpp-divider">
                    <div class="lpp-divider-line"></div>
                    <span>DEMO</span>
                    <div class="lpp-divider-line"></div>
                </div>
                <div class="lpp-demo">
                    {{__('label.if_you_cannot_login_then')}}
                    <a href="{{ route('producer.login') }}" target="_blank">{{ __('label.click_here') }}</a>
                </div>
            @endif
        </div>

        @if(env('DEMO_MODE') == 'ON')
            <div class="lpp-footer">
                <span class="lpp-footer-label">Powered by</span>
                <a class="lpp-dt-mark" href="https://divinetechs.com" target="_blank" rel="noopener" title="DivineTechs">
                    <span class="lpp-dt-name">DivineTechs • All rights reserved.</span>
                </a>
            </div>
        @endif            
    </div>
@endsection

@section('pagescript')
    <script>
        // ── Password visibility toggle ──────────────────────────
        document.getElementById('lpp_pw_toggle').addEventListener('click', function () {
            var input = document.getElementById('lpp_password');
            var icon  = document.getElementById('lpp_pw_icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'fa-solid fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'fa-solid fa-eye';
            }
        });

        // ── Login submit ────────────────────────────────────────
        function save_login() {
            $("#dvloader").show();

            var formData = new FormData($("#login_form")[0]);
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                type: 'POST',
                url: '{{ route("producer.save.login") }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, 'login_form', '{{ route("producer.dashboard") }}');
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
