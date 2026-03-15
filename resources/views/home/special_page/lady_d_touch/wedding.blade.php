<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ms' ? 'ms-MY' : 'en-GB' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $organizer->name }} | {{ __('lady_d_touch.nav_wedding') }}</title>
    <meta name="description" content="Discover beautiful wedding venues at {{ $organizer->name }}.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Josefin+Sans:wght@300;400;600&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background: #fff; color: #999; }
        img  { display: block; max-width: 100%; }
        a    { text-decoration: none; color: inherit; }

        /* Navbar (identical to home) */
        .ldt-nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 999;
            height: 70px; background: transparent; border-bottom: 1px solid transparent;
            display: flex; align-items: center; transition: background 0.35s, border-color 0.35s;
        }
        .ldt-nav.scrolled { background: rgba(255,255,255,0.97); backdrop-filter: blur(8px); border-bottom-color: #eeeeee; }
        .ldt-nav .nav-inner { width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 28px; display: flex; align-items: center; justify-content: space-between; }
        .ldt-nav .nav-brand { font-family: 'Imperial Script', cursive; font-size: 26px; font-weight: 400; color: #fff; line-height: 1; transition: color 0.35s; }
        .ldt-nav.scrolled .nav-brand { color: #222; }
        .ldt-nav .nav-links { display: flex; align-items: center; gap: 28px; list-style: none; }
        .ldt-nav .nav-links a { font-family: 'Josefin Sans', sans-serif; font-size: 12px; font-weight: 400; letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,0.85); transition: color 0.2s; position: relative; padding-bottom: 3px; }
        .ldt-nav .nav-links a::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 1px; background: #14b9d5; transform: scaleX(0); transition: transform 0.25s; }
        .ldt-nav .nav-links a:hover, .ldt-nav .nav-links a.active { color: #14b9d5; }
        .ldt-nav .nav-links a.active::after, .ldt-nav .nav-links a:hover::after { transform: scaleX(1); }
        .ldt-nav.scrolled .nav-links a { color: #444; }
        .ldt-nav.scrolled .nav-links a:hover, .ldt-nav.scrolled .nav-links a.active { color: #14b9d5; }

        /* Lang switcher */
        .lang-switcher { display: flex; align-items: center; gap: 4px; }
        .lang-switcher a { font-family: 'Josefin Sans', sans-serif; font-size: 11px; font-weight: 600; letter-spacing: 1px; padding: 3px 7px; border: 1px solid rgba(255,255,255,0.4); color: rgba(255,255,255,0.75) !important; transition: background 0.2s, color 0.2s, border-color 0.2s; }
        .lang-switcher a::after { display: none !important; }
        .lang-switcher a.active, .lang-switcher a:hover { background: #14b9d5; border-color: #14b9d5; color: #fff !important; }
        .ldt-nav.scrolled .lang-switcher a { border-color: #ddd; color: #777 !important; }
        .ldt-nav.scrolled .lang-switcher a.active, .ldt-nav.scrolled .lang-switcher a:hover { background: #14b9d5; border-color: #14b9d5; color: #fff !important; }

        .ldt-nav .nav-toggle { display: none; background: none; border: none; cursor: pointer; padding: 4px; }
        .ldt-nav .nav-toggle span { display: block; width: 22px; height: 1.5px; background: #fff; margin: 5px 0; transition: background 0.35s; }
        .ldt-nav.scrolled .nav-toggle span { background: #222; }

        /* Mobile menu */
        .ldt-mobile-menu { display: none; position: fixed; top: 70px; left: 0; right: 0; background: #fff; border-bottom: 1px solid #eee; padding: 16px 28px 24px; z-index: 998; }
        .ldt-mobile-menu.open { display: block; }
        .ldt-mobile-menu a { display: block; padding: 13px 0; font-family: 'Josefin Sans', sans-serif; font-size: 13px; letter-spacing: 1px; text-transform: uppercase; color: #555; border-bottom: 1px solid #f0ebe4; }
        .ldt-mobile-menu a:last-child { border-bottom: none; }
        .ldt-mobile-menu a.active { color: #14b9d5; }
        .mobile-lang { display: flex !important; gap: 8px; padding: 13px 0; border-bottom: 1px solid #f0ebe4; }
        .mobile-lang a { display: inline-block !important; padding: 4px 10px !important; border: 1px solid #ddd !important; font-size: 11px !important; color: #777 !important; border-bottom: 1px solid #ddd !important; }
        .mobile-lang a.active, .mobile-lang a:hover { background: #14b9d5 !important; border-color: #14b9d5 !important; color: #fff !important; }

        @media (max-width: 767px) { .ldt-nav .nav-links { display: none; } .ldt-nav .nav-toggle { display: block; } }

        main { padding-top: 0; }
        .ldt-container { max-width: 1140px; margin: 0 auto; padding: 0 28px; }

        /* Animations */
        .slide-up { opacity: 0; transform: translateY(80px); transition: opacity 1.1s cubic-bezier(0.16, 1, 0.3, 1), transform 1.1s cubic-bezier(0.16, 1, 0.3, 1); }
        .slide-up.visible { opacity: 1; transform: translateY(0); }
        .slide-up-child { opacity: 0; transform: translateY(50px); transition: opacity 1s cubic-bezier(0.16, 1, 0.3, 1), transform 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .slide-up-child.visible { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.15s; }
        .delay-2 { transition-delay: 0.35s; }
        @keyframes heroSlideUp { from { opacity: 0; transform: translateY(60px); } to { opacity: 1; transform: translateY(0); } }
        .hero-title-anim { animation: heroSlideUp 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both; }

        /* Hero */
        .wedding-hero { position: relative; width: 100%; height: 380px; background-image: url("{{ $img('wedding_hero', 'Pelamin_wedding_hero.jpg') }}"); background-size: cover; background-position: center top; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .wedding-hero::before { content: ''; position: absolute; inset: 0; background: rgba(0, 0, 0, 0.42); }
        .wedding-hero h1 { position: relative; font-family: 'Imperial Script', cursive; font-size: clamp(64px, 10vw, 100px); font-weight: 400; color: #ffffff; margin: 0; text-shadow: 0 2px 18px rgba(0,0,0,0.4); line-height: 1; }

        /* Intro */
        .wedding-intro { background: #ffffff; padding: 54px 0 46px; }
        .wedding-intro p { font-size: 15px; color: #555555; line-height: 1.85; margin-bottom: 14px; }
        .wedding-intro p:last-child { margin-bottom: 0; }

        /* Venue sections */
        .venue-section { padding: 80px 0; position: relative; background-size: cover; background-position: center; background-attachment: fixed; }
        .venue-section::before { content: ''; position: absolute; inset: 0; pointer-events: none; }
        .venue-section:nth-of-type(odd)::before  { background: rgba(243, 237, 229, 0.90); }
        .venue-section:nth-of-type(even)::before { background: rgba(235, 228, 218, 0.90); }
        .venue-dewan   { background-image: url("{{ $img('venue_dewan', 'Pelamin_DSDusun_2024-1-1024x618.jpeg') }}"); }
        .venue-dataran { background-image: url("{{ $img('venue_dataran', 'DataranSriDusun-1024x682.jpg') }}"); }
        .venue-laman   { background-image: url("{{ $img('venue_laman', 'LamanDusun-1024x768.jpg') }}"); }
        .venue-section .ldt-container { position: relative; z-index: 1; }
        .venue-row { display: flex; flex-direction: row; align-items: center; gap: 52px; }
        .venue-text-col { flex: 0 0 36%; max-width: 36%; }
        .venue-img-col  { flex: 1; }
        .venue-name { font-family: 'Imperial Script', cursive; font-size: clamp(42px, 5.5vw, 62px); font-weight: 400; color: #2a1f12; line-height: 1.1; margin: 0 0 20px 0; }
        .venue-desc { font-size: 15px; color: #5a4f42; line-height: 1.85; margin: 0; }
        .venue-img { width: 100%; height: auto; display: block; box-shadow: 0 8px 32px rgba(0,0,0,0.18); }

        /* Footer (identical to home) */
        .ldt-footer { background: #1a1510; padding: 40px 28px; text-align: center; }
        .ldt-footer .footer-brand { font-family: 'Imperial Script', cursive; font-size: 32px; color: rgba(255,255,255,0.8); margin-bottom: 12px; }
        .ldt-footer p { font-family: 'Josefin Sans', sans-serif; font-size: 11px; letter-spacing: 1px; color: rgba(255,255,255,0.4); text-transform: uppercase; }
        .ldt-footer .footer-links { display: flex; justify-content: center; gap: 24px; margin-bottom: 16px; }
        .ldt-footer .footer-links a { font-family: 'Josefin Sans', sans-serif; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,0.5); transition: color 0.2s; }
        .ldt-footer .footer-links a:hover { color: #14b9d5; }

        /* Responsive */
        @media (max-width: 991px) {
            .venue-row { flex-direction: column; gap: 28px; }
            .venue-text-col, .venue-img-col { flex: unset; max-width: 100%; width: 100%; }
            .venue-section { background-attachment: scroll; }
            .wedding-hero { height: 240px; }
        }
    </style>
</head>
<body>

@php
    $spImgs = $organizer->special_page_images ?? [];
    $img = fn(string $key, string $fallback) => asset('storage/' . ($spImgs[$key] ?? $fallback));
@endphp

    @include('home.special_page.lady_d_touch._navbar')

    <main>

        <div class="wedding-hero">
            <h1 class="hero-title-anim">{{ __('lady_d_touch.wedding_hero') }}</h1>
        </div>

        <section class="wedding-intro">
            <div class="ldt-container" style="max-width:760px;">
                <p class="slide-up">{{ __('lady_d_touch.wedding_intro1') }}</p>
                <p class="slide-up">{{ __('lady_d_touch.wedding_intro2') }}</p>
                <p class="slide-up">{{ __('lady_d_touch.wedding_intro3', ['name' => $organizer->name]) }}</p>
                <p class="slide-up">{{ __('lady_d_touch.wedding_intro4') }}</p>
            </div>
        </section>

        <section class="venue-section venue-dewan slide-up">
            <div class="ldt-container">
                <div class="venue-row">
                    <div class="venue-text-col slide-up-child delay-1">
                        <h3 class="venue-name">{{ __('lady_d_touch.venue1_name') }}</h3>
                        <p class="venue-desc">{{ __('lady_d_touch.venue1_desc') }}</p>
                    </div>
                    <div class="venue-img-col slide-up-child delay-2">
                        <img src="{{ $img('venue_dewan', 'Pelamin_DSDusun_2024-1-1024x618.jpeg') }}" alt="{{ __('lady_d_touch.venue1_name') }}" class="venue-img" loading="lazy">
                    </div>
                </div>
            </div>
        </section>

        <section class="venue-section venue-dataran slide-up">
            <div class="ldt-container">
                <div class="venue-row">
                    <div class="venue-text-col slide-up-child delay-1">
                        <h3 class="venue-name">{{ __('lady_d_touch.venue2_name') }}</h3>
                        <p class="venue-desc">{{ __('lady_d_touch.venue2_desc') }}</p>
                    </div>
                    <div class="venue-img-col slide-up-child delay-2">
                        <img src="{{ $img('venue_dataran', 'DataranSriDusun-1024x682.jpg') }}" alt="{{ __('lady_d_touch.venue2_name') }}" class="venue-img" loading="lazy">
                    </div>
                </div>
            </div>
        </section>

        <section class="venue-section venue-laman slide-up">
            <div class="ldt-container">
                <div class="venue-row">
                    <div class="venue-text-col slide-up-child delay-1">
                        <h3 class="venue-name">{{ __('lady_d_touch.venue3_name') }}</h3>
                        <p class="venue-desc">{{ __('lady_d_touch.venue3_desc') }}</p>
                    </div>
                    <div class="venue-img-col slide-up-child delay-2">
                        <img src="{{ $img('venue_laman', 'LamanDusun-1024x768.jpg') }}" alt="{{ __('lady_d_touch.venue3_name') }}" class="venue-img" loading="lazy">
                    </div>
                </div>
            </div>
        </section>

    </main>

    @include('home.special_page.lady_d_touch._footer')

    <script>
        // Navbar transparent → solid on scroll
        (function () {
            var nav = document.getElementById('ldt-nav');
            var hero = document.querySelector('.wedding-hero');
            function update() {
                var threshold = hero ? (hero.offsetHeight - nav.offsetHeight - 20) : 60;
                window.scrollY > threshold ? nav.classList.add('scrolled') : nav.classList.remove('scrolled');
            }
            update();
            window.addEventListener('scroll', update, { passive: true });
        })();

        // Mobile nav toggle
        document.getElementById('ldt-toggle').addEventListener('click', function () {
            document.getElementById('ldt-mobile-menu').classList.toggle('open');
        });
        document.querySelectorAll('.ldt-ml').forEach(function (a) {
            a.addEventListener('click', function () { document.getElementById('ldt-mobile-menu').classList.remove('open'); });
        });

        // Scroll animations
        (function () {
            function observe(selector, rootMargin) {
                var els = document.querySelectorAll(selector);
                var obs = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
                }, { threshold: 0, rootMargin: rootMargin || '0px 0px -60px 0px' });
                els.forEach(function (el) {
                    var r = el.getBoundingClientRect();
                    if (r.top < window.innerHeight && r.bottom > 0) { setTimeout(function () { el.classList.add('visible'); }, 100); }
                    else { obs.observe(el); }
                });
            }
            observe('.slide-up', '0px 0px -80px 0px');
            observe('.slide-up-child', '0px 0px -40px 0px');
        })();
    </script>
</body>
</html>
