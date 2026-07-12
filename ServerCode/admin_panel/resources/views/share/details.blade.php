<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="{{ $title }}">
    <meta name="description" content="{{ $description }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:url" content="{{ $url }}">
    <meta property="og:type" content="video.other">
    <meta property="og:site_name" content="blackstone">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Optional redirect for human visitors -->
    <script>
        setTimeout(() => {
            window.location.href = "{{ $redirect_url }}";
        }, 1500);
    </script>
</head>
<body>
</body>
</html>
