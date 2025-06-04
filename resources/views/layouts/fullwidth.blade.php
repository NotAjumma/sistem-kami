@php
    $controller = DzHelper::controller();
    $page = $action = DzHelper::action();
    $action = $controller . '_' . $action;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <!--Title-->
    <title>{{ config('dz.name') }} | @yield('title', $page_title ?? '')</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Sistem Kami">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords"
        content="Sistem Kami, Business Software, Custom Software, Web Applications, Ready-made Systems, Laravel, Tailor-made Solutions, Business Tools, Productivity Software, Admin Dashboard, CRM, ERP, POS, Custom Web Development, Business Automation, Scalable Software, Business Platform, UI Kit, Responsive Design, Software as a Service, Custom Dashboards, Web UI, SaaS Platform, Software for SMEs, Business Management, Cloud Solutions">
    <meta name="description"
        content="@yield('page_description', $page_description ?? 'We build custom and ready-made systems to help your business work better — simple, fast, and tailored for you.')">
    <meta property="og:title" content="Sistem Kami | Smart, Simple, Custom Business Software">
    <meta property="og:description"
        content="@yield('page_description', $page_description ?? 'We build custom and ready-made systems to help your business work better — simple, fast, and tailored for you.')">
    <meta property="og:image" content="">
    <meta name="format-detection" content="telephone=no">
    <meta name="twitter:title" content="Sistem Kami | Smart, Simple, Custom Business Software">
    <meta name="twitter:description"
        content="@yield('page_description', $page_description ?? 'We build custom and ready-made systems to help your business work better — simple, fast, and tailored for you.')">
    <meta name="twitter:image" content="">
    <meta name="twitter:card" content="summary_large_image">

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    @if (!empty(config('dz.public.pagelevel.css.' . $action)))
        @foreach (config('dz.public.pagelevel.css.' . $action) as $style)
            <link href="{{ asset($style) }}" rel="stylesheet" type="text/css" />
        @endforeach
    @endif

    <!-- Style css -->
    @if (!empty(config('dz.public.global.css')))
        @foreach (config('dz.public.global.css') as $style)
            <link href="{{ asset($style) }}" rel="stylesheet" type="text/css" />
        @endforeach
    @endif
    <link class="main-css" href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">
</head>

<body>
    <div class="authincation fix-wrapper">
        <div class="container">
            <div class="row justify-content-center h-100 align-items-center">
                @yield('content')
            </div>
        </div>
    </div>


    <!-- Required vendors -->
    <!-- Required vendors -->
    @if (!empty(config('dz.public.global.js.top')))
        @foreach (config('dz.public.global.js.top') as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
        @endforeach
    @endif
    @if (!empty(config('dz.public.pagelevel.js.' . $action)))
        @foreach (config('dz.public.pagelevel.js.' . $action) as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
        @endforeach
    @endif
    @if (!empty(config('dz.public.global.js.bottom')))
        @foreach (config('dz.public.global.js.bottom') as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
        @endforeach
    @endif

    @stack('scripts')
</body>

</html>