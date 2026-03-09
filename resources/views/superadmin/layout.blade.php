<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Superadmin') — SistemKami</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    {{-- Jiade theme CSS --}}
    <link href="{{ asset('vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/swiper/css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link class="main-css" href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">

    {{-- Toastr & SweetAlert --}}
    <link href="{{ asset('vendor/toastr/css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.css') }}" rel="stylesheet">

    <style>
        #brand-logo-img { height: 30px; }
        @media (max-width: 576px) { #brand-logo-img { height: 30px; } }
    </style>

    @stack('styles')
</head>

<body>

{{-- Preloader --}}
<div id="preloader">
    <div class="lds-ripple"><div></div><div></div></div>
</div>

<div id="main-wrapper">

    {{-- ── Nav Header ──────────────────────────────────────────────────── --}}
    <div class="nav-header">
        <a href="{{ route('superadmin.dashboard') }}" class="brand-logo">
            <img id="brand-logo-img" src="{{ asset('images/logo-white.png') }}" alt="SistemKami">
        </a>
        <div class="nav-control">
            <div class="hamburger" id="hamburger-toggle">
                <span class="line"></span><span class="line"></span><span class="line"></span>
            </div>
        </div>
    </div>

    {{-- ── Header ──────────────────────────────────────────────────────── --}}
    <div class="header">
        <div class="header-content">
            <nav class="navbar navbar-expand">
                <div class="collapse navbar-collapse justify-content-between">
                    <div class="header-left">
                        <div class="dashboard_bar">@yield('title', 'Dashboard')</div>
                    </div>
                    <ul class="navbar-nav header-right">
                        <li class="nav-item d-flex align-items-center px-3">
                            <span class="text-muted small">
                                <i class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">shield</i>
                                &nbsp;{{ auth('superadmin')->user()->username ?? 'admin' }}
                            </span>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <form method="POST" action="{{ route('superadmin.logout') }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">logout</i>
                                    &nbsp;Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="page-titles">
            <ol class="breadcrumb">
                <li class="breadcrumb-item ms-auto">
                    <a href="{{ route('superadmin.dashboard') }}">
                        <i class="material-symbols-outlined" style="font-size:14px;vertical-align:middle">home</i>
                        Home
                    </a>
                </li>
                @yield('breadcrumb_items')
                <li class="breadcrumb-item active">@yield('title', 'Dashboard')</li>
            </ol>
        </div>
    </div>

    {{-- ── Sidebar ──────────────────────────────────────────────────────── --}}
    <div class="dlabnav">
        <div class="feature-box style-3"></div>
        <span class="main-menu">Main Menu</span>
        <div class="menu-scroll">
            <div class="dlabnav-scroll">
                <ul class="metismenu" id="menu">
                    <li>
                        <a href="{{ route('superadmin.dashboard') }}"
                           class="{{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}"
                           aria-expanded="false">
                            <i class="material-symbols-outlined">dashboard</i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.organizers') }}"
                           class="{{ request()->routeIs('superadmin.organizers*', 'superadmin.organizer*') ? 'active' : '' }}"
                           aria-expanded="false">
                            <i class="material-symbols-outlined">business</i>
                            <span class="nav-text">Organizers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.reminders') }}"
                           class="{{ request()->routeIs('superadmin.reminders*') ? 'active' : '' }}"
                           aria-expanded="false">
                            <i class="material-symbols-outlined">notifications</i>
                            <span class="nav-text">Reminders</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.commands') }}"
                           class="{{ request()->routeIs('superadmin.commands*') ? 'active' : '' }}"
                           aria-expanded="false">
                            <i class="material-symbols-outlined">terminal</i>
                            <span class="nav-text">Commands</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('superadmin.settings') }}"
                           class="{{ request()->routeIs('superadmin.settings*') ? 'active' : '' }}"
                           aria-expanded="false">
                            <i class="material-symbols-outlined">settings</i>
                            <span class="nav-text">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ── Content Body ─────────────────────────────────────────────────── --}}
    <div class="content-body default-height">
        <div class="container-fluid">

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mt-3">
                    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')

        </div>
    </div>

    {{-- ── Footer ──────────────────────────────────────────────────────── --}}
    <div class="footer style-1">
        <div class="copyright">
            <p>Copyright &copy; Designed &amp; Developed by <a href="" target="_blank">SistemKami</a>
                <span class="current-year">2025</span></p>
        </div>
    </div>

</div>{{-- #main-wrapper --}}

{{-- ── Scripts ────────────────────────────────────────────────────────── --}}
<script src="{{ asset('vendor/global/global.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
<script src="{{ asset('js/dlabnav-init.js') }}"></script>
<script src="{{ asset('vendor/toastr/js/toastr.min.js') }}"></script>
<script src="{{ asset('vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>

<script>
    // Hamburger logo toggle (same as organizer layout)
    document.addEventListener('DOMContentLoaded', function () {
        const hamburger = document.getElementById('hamburger-toggle');
        const logoImg   = document.getElementById('brand-logo-img');
        let isMenuOpen  = false;

        function updateLogo() {
            const isMobile = window.innerWidth <= 768;
            if (isMobile) {
                logoImg.src = "{{ asset('images/logo-white-only.png') }}";
            } else {
                logoImg.src = isMenuOpen
                    ? "{{ asset('images/logo-white-only.png') }}"
                    : "{{ asset('images/logo-white.png') }}";
            }
        }

        hamburger.addEventListener('click', function () {
            isMenuOpen = !isMenuOpen;
            updateLogo();
        });
        window.addEventListener('resize', updateLogo);
        updateLogo();
    });
</script>

@if(session('success'))
<script>
    toastr.success("{{ session('success') }}", "Success", {
        timeOut: 4000, closeButton: true, progressBar: true,
        positionClass: "toast-top-right"
    });
</script>
@endif

@if(session('info'))
<script>
    toastr.info("{{ session('info') }}", "Info", {
        timeOut: 4000, closeButton: true, progressBar: true,
        positionClass: "toast-top-right"
    });
</script>
@endif

@stack('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const f = this;
            Swal.fire({
                title: 'Are you sure?',
                text: f.dataset.confirm,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e55353',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel'
            }).then(function (result) { if (result.isConfirmed) f.submit(); });
        });
    });
    document.querySelectorAll('a[data-confirm]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const href = this.href, target = this.target;
            Swal.fire({
                title: 'Are you sure?',
                text: this.dataset.confirm,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e55353',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel'
            }).then(function (result) {
                if (result.isConfirmed) {
                    if (target === '_blank') window.open(href, '_blank');
                    else window.location.href = href;
                }
            });
        });
    });
});
</script>
</body>
</html>
