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
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Sistem Kami | Smart, Simple, Custom Business Software')">
    <meta property="og:description"
        content="@yield('page_description', $page_description ?? 'We build custom and ready-made systems to help your business work better — simple, fast, and tailored for you.')">
    <meta property="og:image" content="@yield('og_image', asset('images/SISTEM-KAMI-LOGO.png'))">
    <meta name="format-detection" content="telephone=no">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', 'Sistem Kami | Smart, Simple, Custom Business Software')">
    <meta name="twitter:description"
        content="@yield('page_description', $page_description ?? 'We build custom and ready-made systems to help your business work better — simple, fast, and tailored for you.')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/SISTEM-KAMI-LOGO.png'))">

    <!-- MOBILE SPECIFIC -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- FAVICONS ICON -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <!-- Canonical -->
    <link rel="canonical" href="{{ url()->current() }}">
    @if(!empty(config('dz.public.pagelevel.css.' . $action)))
        @foreach(config('dz.public.pagelevel.css.' . $action) as $style)
            <link href="{{ asset($style) }}" rel="stylesheet" type="text/css" />
        @endforeach
    @endif

    <!-- Style css -->
    @if(!empty(config('dz.public.global.css')))
        @foreach(config('dz.public.global.css') as $style)
            <link href="{{ asset($style) }}" rel="stylesheet" type="text/css" />
        @endforeach
    @endif
    <!-- Preconnect to font origins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link class="main-css" href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&display=swap">

<style>
.logo img {
    max-width: 180px;   /* default mobile */
    height: auto;
}

@media (min-width: 768px) {  /* tablet */
    .logo img {
        max-width: 140px;
    }
}

@media (min-width: 1200px) { /* desktop besar */
    .logo img {
        max-width: 120px;
    }
}
</style>
</head>

<body class="body">
    <div class="authincation d-flex flex-column flex-lg-row flex-column-fluid">
        <div class="login-aside text-center  d-flex flex-column flex-row-auto">
            <div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15">
                <div class="text-center mb-lg-4 mb-2 pt-5 logo">
                    <a href="{{ route('index') }}">
                        <img src="{{ asset('images/logo-white.png') }}" alt="Sistem Kami Logo" width="180" height="auto">
                    </a>
                </div>

                <h3 class="mb-2 text-white">Welcome back!</h3>
                <p class="mb-4">Your all-in-one platform to organize, manage,<br>and grow your events seamlessly.</p>
            </div>
            <div class="aside-image position-relative" style="background-image: url('{{ asset('images/background/pic-2.png') }}');">
                <img class="img1 move-1" src="{{ asset('images/background/pic3.png') }}" alt="" loading="lazy" decoding="async">
                <img class="img2 move-2" src="{{ asset('images/background/pic4.png') }}" alt="" loading="lazy" decoding="async">
                <img class="img3 move-3" src="{{ asset('images/background/pic5.png') }}" alt="" loading="lazy" decoding="async">

            </div>
        </div>
        <div
            class="container flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <div class="d-flex justify-content-center h-100 align-items-center">
                <div class="authincation-content style-2">
                    <div class="row no-gutters">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>






    <!--**********************************
 Scripts
***********************************-->
    <!-- Required vendors -->
    <!-- Required vendors -->
    @if (!empty(config('dz.public.global.js.top')))
        @foreach (config('dz.public.global.js.top') as $script)
            <script src="{{ asset($script) }}" type="text/javascript" defer></script>
        @endforeach
    @endif
    @if (!empty(config('dz.public.pagelevel.js.' . $action)))
        @foreach (config('dz.public.pagelevel.js.' . $action) as $script)
            <script src="{{ asset($script) }}" type="text/javascript" defer></script>
        @endforeach
    @endif
    @if (!empty(config('dz.public.global.js.bottom')))
        @foreach (config('dz.public.global.js.bottom') as $script)
            <script src="{{ asset($script) }}" type="text/javascript" defer></script>
        @endforeach
    @endif

    @stack('scripts')
</body>

</html>