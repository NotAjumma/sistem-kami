<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $organizer->name }} | Wedding Venues</title>
    <meta name="description" content="Discover beautiful wedding venues at {{ $organizer->name }}.">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Imperial Script font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background: #fff; }

        /* ── Own Navbar ──────────────────────────────────────────────────────── */
        .ldt-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            height: 70px;
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #ede8e1;
            display: flex;
            align-items: center;
        }
        .ldt-nav .nav-inner {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .ldt-nav .nav-brand {
            font-family: 'Imperial Script', cursive;
            font-size: 28px;
            font-weight: 400;
            color: #2a1f12;
            text-decoration: none;
            line-height: 1;
        }
        .ldt-nav .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
            list-style: none;
        }
        .ldt-nav .nav-links a {
            font-size: 13px;
            font-weight: 400;
            color: #555;
            text-decoration: none;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            transition: color 0.2s;
        }
        .ldt-nav .nav-links a:hover { color: #2a1f12; }

        /* Mobile hamburger */
        .ldt-nav .nav-toggle {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
        }
        .ldt-nav .nav-toggle span {
            display: block;
            width: 22px;
            height: 2px;
            background: #2a1f12;
            margin: 5px 0;
            transition: all 0.3s;
        }
        .ldt-mobile-menu {
            display: none;
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            background: rgba(255,255,255,0.98);
            border-bottom: 1px solid #ede8e1;
            padding: 16px 24px 24px;
            z-index: 998;
        }
        .ldt-mobile-menu.open { display: block; }
        .ldt-mobile-menu a {
            display: block;
            padding: 12px 0;
            font-size: 14px;
            color: #555;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom: 1px solid #f0ebe4;
        }
        .ldt-mobile-menu a:last-child { border-bottom: none; }

        @media (max-width: 767px) {
            .ldt-nav .nav-links { display: none; }
            .ldt-nav .nav-toggle { display: block; }
        }

        /* ── Page top offset for fixed nav ──────────────────────────────────── */
        main { padding-top: 70px; }

        /* ── Animations ──────────────────────────────────────────────────────── */
        .slide-up {
            opacity: 0;
            transform: translateY(80px);
            transition: opacity 1.1s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 1.1s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .slide-up.visible { opacity: 1; transform: translateY(0); }

        .slide-up-child {
            opacity: 0;
            transform: translateY(50px);
            transition: opacity 1s cubic-bezier(0.16, 1, 0.3, 1),
                        transform 1s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .slide-up-child.visible { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.15s; }
        .delay-2 { transition-delay: 0.35s; }

        @keyframes heroSlideUp {
            from { opacity: 0; transform: translateY(60px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hero-title-anim {
            animation: heroSlideUp 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
        }

        /* ── Hero ────────────────────────────────────────────────────────────── */
        .wedding-hero {
            position: relative;
            width: 100%;
            height: 380px;
            background-image: url("{{ asset('storage/Pelamin_DSDusun_2024-1-1024x618.jpeg') }}");
            background-size: cover;
            background-position: center top;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .wedding-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.42);
        }
        .wedding-hero h1 {
            position: relative;
            font-family: 'Imperial Script', cursive;
            font-size: clamp(64px, 10vw, 100px);
            font-weight: 400;
            color: #ffffff;
            margin: 0;
            text-shadow: 0 2px 18px rgba(0,0,0,0.4);
            line-height: 1;
        }

        /* ── Intro ───────────────────────────────────────────────────────────── */
        .wedding-intro {
            background: #ffffff;
            padding: 54px 24px 46px;
        }
        .wedding-intro-inner {
            max-width: 760px;
            margin: 0 auto;
        }
        .wedding-intro p {
            font-size: 15px;
            color: #555555;
            line-height: 1.85;
            margin-bottom: 14px;
        }
        .wedding-intro p:last-child { margin-bottom: 0; }

        /* ── Venue sections ──────────────────────────────────────────────────── */
        .venue-section {
            padding: 80px 0;
            position: relative;
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .venue-section::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
        }
        .venue-section:nth-of-type(odd)::before  { background: rgba(243, 237, 229, 0.90); }
        .venue-section:nth-of-type(even)::before { background: rgba(235, 228, 218, 0.90); }

        .venue-dewan   { background-image: url("{{ asset('storage/Pelamin_DSDusun_2024-1-1024x618.jpeg') }}"); }
        .venue-dataran { background-image: url("{{ asset('storage/DataranSriDusun-1024x682.jpg') }}"); }
        .venue-laman   { background-image: url("{{ asset('storage/LamanDusun-1024x768.jpg') }}"); }

        .venue-section .ldt-container { position: relative; z-index: 1; }

        .ldt-container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Text LEFT, image RIGHT */
        .venue-row {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 52px;
        }
        .venue-text-col { flex: 0 0 36%; max-width: 36%; }
        .venue-img-col  { flex: 1; }

        @media (max-width: 991px) {
            .venue-row       { flex-direction: column; gap: 28px; }
            .venue-text-col,
            .venue-img-col   { flex: unset; max-width: 100%; width: 100%; }
            .venue-section   { background-attachment: scroll; }
            .wedding-hero    { height: 240px; }
        }

        .venue-name {
            font-family: 'Imperial Script', cursive;
            font-size: clamp(42px, 5.5vw, 62px);
            font-weight: 400;
            color: #2a1f12;
            line-height: 1.1;
            margin: 0 0 20px 0;
        }
        .venue-desc {
            font-size: 15px;
            color: #5a4f42;
            line-height: 1.85;
            margin: 0;
        }
        .venue-img {
            width: 100%;
            height: auto;
            display: block;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }

        /* ── Footer ──────────────────────────────────────────────────────────── */
        .ldt-footer {
            background: #2a1f12;
            color: rgba(255,255,255,0.6);
            text-align: center;
            padding: 32px 24px;
            font-size: 13px;
            letter-spacing: 0.05em;
        }
        .ldt-footer a {
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            transition: color 0.2s;
        }
        .ldt-footer a:hover { color: #fff; }
    </style>
</head>
<body>

    {{-- ── Own Navbar ──────────────────────────────────────────────────────── --}}
    <nav class="ldt-nav">
        <div class="nav-inner">
            <a href="#" class="nav-brand">{{ $organizer->name }}</a>

            <ul class="nav-links">
                <li><a href="#home">Home</a></li>
                <li><a href="#venues">Wedding</a></li>
                @if($organizer->phone)
                <li>
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $organizer->phone) }}?text=Hi%2C%20I%27m%20interested%20in%20your%20wedding%20venue." target="_blank">
                        Contact Us
                    </a>
                </li>
                @endif
            </ul>

            <button class="nav-toggle" id="ldt-menu-toggle" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <div class="ldt-mobile-menu" id="ldt-mobile-menu">
        <a href="#home" class="ldt-mobile-link">Home</a>
        <a href="#venues" class="ldt-mobile-link">Wedding</a>
        @if($organizer->phone)
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $organizer->phone) }}?text=Hi%2C%20I%27m%20interested%20in%20your%20wedding%20venue." target="_blank" class="ldt-mobile-link">Contact Us</a>
        @endif
    </div>

    <main id="home">

        {{-- ── Hero ──────────────────────────────────────────────────────────── --}}
        <div class="wedding-hero">
            <h1 class="hero-title-anim">Wedding</h1>
        </div>

        {{-- ── Intro ───────────────────────────────────────────────────────────── --}}
        <section class="wedding-intro">
            <div class="wedding-intro-inner">
                <p class="slide-up">Be it a small romantic affair or a large scale ground celebration, view our array of choice to kickstart your journey to a beautiful and memorable wedding.</p>
                <p class="slide-up">Compliment your event setting with and add-on individual items to the featured package.</p>
                <p class="slide-up">At {{ $organizer->name }}, guests have the option of holding their ceremonies either indoors or outdoors. We offer a wide selection of wedding packages ranging from simple yet elegant styles to elaborate modern settings. An extended menu of delectable Malay and International dishes is also available to serve for your F&amp;B needs.</p>
                <p class="slide-up">The house includes the usage of bridal suite with attached bathroom as well as two other private rooms for guests.</p>
            </div>
        </section>

        {{-- ── Venues ──────────────────────────────────────────────────────────── --}}
        <div id="venues">

            {{-- Venue 1: Dewan Sri Dusun --}}
            <section class="venue-section venue-dewan slide-up">
                <div class="ldt-container">
                    <div class="venue-row">
                        <div class="venue-text-col slide-up-child delay-1">
                            <h3 class="venue-name">Dewan Sri Dusun</h3>
                            <p class="venue-desc">Our Dewan Sri Dusun consists of Ruang Kaca (air-conditioned) and can seat up to 160 guests for sit down dinner receptions whilst for buffet lunch receptions we can host a maximum of 1000 guests (come &amp; go) with the usage of outdoor areas as well.</p>
                        </div>
                        <div class="venue-img-col slide-up-child delay-2">
                            <img src="{{ asset('storage/Pelamin_DSDusun_2024-1-1024x618.jpeg') }}" alt="Dewan Sri Dusun" class="venue-img" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

            {{-- Venue 2: Dataran Sri Dusun --}}
            <section class="venue-section venue-dataran slide-up">
                <div class="ldt-container">
                    <div class="venue-row">
                        <div class="venue-text-col slide-up-child delay-1">
                            <h3 class="venue-name">Dataran Sri Dusun</h3>
                            <p class="venue-desc">Dataran Sri Dusun is an outdoor space that is perfect for those who would want to keep the traditional wedding ambiance. Dataran Sri Dusun can accommodate up to 150 chairs for theatre seating and Dewan Sri Dusun will be used as guests dining.</p>
                        </div>
                        <div class="venue-img-col slide-up-child delay-2">
                            <img src="{{ asset('storage/DataranSriDusun-1024x682.jpg') }}" alt="Dataran Sri Dusun" class="venue-img" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

            {{-- Venue 3: Laman Dusun --}}
            <section class="venue-section venue-laman slide-up">
                <div class="ldt-container">
                    <div class="venue-row">
                        <div class="venue-text-col slide-up-child delay-1">
                            <h3 class="venue-name">Laman Dusun</h3>
                            <p class="venue-desc">Our Laman Dusun is perfect for outdoor ceremonies. We can accommodate up to 150 chiavari chairs for theatre seating. Laman Dusun is also covered by canopies for guest dining.</p>
                        </div>
                        <div class="venue-img-col slide-up-child delay-2">
                            <img src="{{ asset('storage/LamanDusun-1024x768.jpg') }}" alt="Laman Dusun" class="venue-img" loading="lazy">
                        </div>
                    </div>
                </div>
            </section>

        </div>{{-- #venues --}}

    </main>

    {{-- ── Footer ───────────────────────────────────────────────────────────── --}}
    <footer class="ldt-footer">
        <p>© {{ date('Y') }} {{ $organizer->name }}. All rights reserved.</p>
        @if($organizer->phone)
        <p style="margin-top:8px;">
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $organizer->phone) }}" target="_blank">WhatsApp Us</a>
        </p>
        @endif
    </footer>

    <script>
        // Mobile menu toggle
        var toggle = document.getElementById('ldt-menu-toggle');
        var mobileMenu = document.getElementById('ldt-mobile-menu');
        toggle.addEventListener('click', function () {
            mobileMenu.classList.toggle('open');
        });
        // Close mobile menu on link click
        document.querySelectorAll('.ldt-mobile-link').forEach(function (a) {
            a.addEventListener('click', function () { mobileMenu.classList.remove('open'); });
        });

        // Scroll animations
        (function () {
            function observe(selector, rootMargin) {
                var els = document.querySelectorAll(selector);
                var observer = new IntersectionObserver(function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0, rootMargin: rootMargin || '0px 0px -60px 0px' });

                els.forEach(function (el) {
                    var rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight && rect.bottom > 0) {
                        setTimeout(function () { el.classList.add('visible'); }, 100);
                    } else {
                        observer.observe(el);
                    }
                });
            }
            observe('.slide-up',       '0px 0px -80px 0px');
            observe('.slide-up-child', '0px 0px -40px 0px');
        })();
    </script>
</body>
</html>
