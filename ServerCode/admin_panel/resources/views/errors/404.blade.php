<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="{{ Tab_Icon() }}">
    <title>404 - {{ App_Name() }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #1e1e2e;
            font-family: 'Rubik', sans-serif;
            overflow: hidden;
        }
        /* ── Animated background particles ── */
        .bg-particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.12;
            animation: drift linear infinite;
        }
        .particle:nth-child(1)  { width:320px; height:320px; background:#BAFA34; top:-80px;  left:-60px;  animation-duration:18s; }
        .particle:nth-child(2)  { width:220px; height:220px; background:#BAFA34; top:60%;    right:-40px; animation-duration:14s; animation-delay:-6s; }
        .particle:nth-child(3)  { width:160px; height:160px; background:#BAFA34; bottom:-40px; left:30%;  animation-duration:20s; animation-delay:-3s; }
        .particle:nth-child(4)  { width:100px; height:100px; background:#fff;    top:20%;    left:15%;    animation-duration:12s; animation-delay:-9s; opacity:0.04; }
        @keyframes drift {
            0%   { transform: translateY(0)   scale(1); }
            50%  { transform: translateY(-40px) scale(1.05); }
            100% { transform: translateY(0)   scale(1); }
        }
        /* ── Card ── */
        .error-card {
            position: relative;
            z-index: 1;
            text-align: center;
            padding: 60px 48px 52px;
            max-width: 520px;
            width: 90%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(186,250,52,0.12);
            border-radius: 24px;
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 32px 80px rgba(0,0,0,0.6), 0 0 0 1px rgba(186,250,52,0.08);
            animation: cardIn 0.7s cubic-bezier(0.22,1,0.36,1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(40px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0)    scale(1); }
        }
        /* ── 404 number ── */
        .error-number {
            font-size: clamp(96px, 20vw, 160px);
            font-weight: 900;
            line-height: 1;
            letter-spacing: -6px;
            background: linear-gradient(135deg, #BAFA34 0%, #d4ff6e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 40px rgba(186,250,52,0.45));
            animation: pulse 2.4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { filter: drop-shadow(0 0 30px rgba(186,250,52,0.4)); }
            50%       { filter: drop-shadow(0 0 60px rgba(186,250,52,0.75)); }
        }
        /* ── Divider ── */
        .error-divider {
            width: 56px;
            height: 3px;
            background: linear-gradient(90deg, #BAFA34, #d4ff6e);
            border-radius: 2px;
            margin: 20px auto 24px;
        }
        /* ── Text ── */
        .error-title {
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .error-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: rgba(255,255,255,0.45);
            line-height: 1.7;
            margin-bottom: 36px;
        }
        /* ── Icon SVG ── */
        .error-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 20px;
            background: rgba(186,250,52,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-icon svg {
            width: 30px;
            height: 30px;
        }
    </style>
</head>

<body>

    <!-- Background particles -->
    <div class="bg-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- Error card -->
    <div class="error-card">

        <div class="error-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="#BAFA34" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>

        <div class="error-number">404</div>

        <div class="error-divider"></div>

        <div class="error-title">Page Not Found</div>
        <div class="error-subtitle">
            Looks like this page took a wrong turn.<br>
            The content you're looking for doesn't exist or has been moved.
        </div>
    </div>

</body>

</html>