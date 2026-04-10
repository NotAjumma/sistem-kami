@php
    $spImgs = $organizer->special_page_images ?? [];
    $img    = fn(string $key, string $fallback) => asset('storage/' . ($spImgs[$key] ?? $fallback));

    $cfg    = $organizer->special_page_config ?? [];
    $accent = $cfg['accent_color'] ?? '#14b9d5';
    $vis    = fn(string $s) => ($cfg['sections'][$s]['visible'] ?? true) !== false;
    $txt    = fn(string $s, string $k, string $fallback) =>
                  (!empty($cfg['sections'][$s][$k]) ? $cfg['sections'][$s][$k] : $fallback);

    // Fonts
    $headingFont = $cfg['heading_font'] ?? 'Imperial Script';
    $bodyFont    = $cfg['body_font']    ?? 'Poppins';
    $gfHeading   = ['Imperial Script'=>'Imperial+Script','Great Vibes'=>'Great+Vibes','Playfair Display'=>'Playfair+Display:ital,wght@1,400','Cormorant Garamond'=>'Cormorant+Garamond:ital,wght@1,400','Dancing Script'=>'Dancing+Script','Cinzel'=>'Cinzel'][$headingFont] ?? 'Imperial+Script';
    $gfBody      = ['Poppins'=>'Poppins:wght@300;400;500','Lato'=>'Lato:wght@300;400;700','Montserrat'=>'Montserrat:wght@300;400;500;600','Raleway'=>'Raleway:wght@300;400;500;600'][$bodyFont] ?? 'Poppins:wght@300;400;500';

    // Venue overlay helper: hex color + opacity → rgba string
    $venueOverlay = function(string $s) use ($cfg) {
        $hex = $cfg['sections'][$s]['overlay_color']   ?? '#f3ede5';
        $op  = (float)($cfg['sections'][$s]['overlay_opacity'] ?? 0.90);
        [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x');
        return "rgba({$r},{$g},{$b},{$op})";
    };
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ms' ? 'ms-MY' : 'en-GB' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $organizer->name }} | Perkahwinan</title>
    <meta name="description" content="Discover beautiful wedding venues at {{ $organizer->name }}.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ $gfHeading }}&family=Josefin+Sans:wght@300;400;600&family={{ $gfBody }}&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background: #faf8f5; color: #999; -webkit-font-smoothing: antialiased; }
        img  { display: block; max-width: 100%; }
        a    { text-decoration: none; color: inherit; }

        /* ── Navbar ─────────────────────────────────────────────────── */
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
        .ldt-nav .nav-links a::after { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 1px; background: {{ $accent }}; transform: scaleX(0); transition: transform 0.25s; }
        .ldt-nav .nav-links a:hover, .ldt-nav .nav-links a.active { color: {{ $accent }}; }
        .ldt-nav .nav-links a.active::after, .ldt-nav .nav-links a:hover::after { transform: scaleX(1); }
        .ldt-nav.scrolled .nav-links a { color: #444; }
        .ldt-nav.scrolled .nav-links a:hover, .ldt-nav.scrolled .nav-links a.active { color: {{ $accent }}; }

        /* Lang switcher */
        .lang-switcher { display: flex; align-items: center; gap: 4px; }
        .lang-switcher a { font-family: 'Josefin Sans', sans-serif; font-size: 11px; font-weight: 600; letter-spacing: 1px; padding: 3px 7px; border: 1px solid rgba(255,255,255,0.4); color: rgba(255,255,255,0.75) !important; transition: background 0.2s, color 0.2s, border-color 0.2s; }
        .lang-switcher a::after { display: none !important; }
        .lang-switcher a.active, .lang-switcher a:hover { background: {{ $accent }}; border-color: {{ $accent }}; color: #fff !important; }
        .ldt-nav.scrolled .lang-switcher a { border-color: #ddd; color: #777 !important; }
        .ldt-nav.scrolled .lang-switcher a.active, .ldt-nav.scrolled .lang-switcher a:hover { background: {{ $accent }}; border-color: {{ $accent }}; color: #fff !important; }

        .ldt-nav .nav-toggle { display: none; background: none; border: none; cursor: pointer; padding: 4px; }
        .ldt-nav .nav-toggle span { display: block; width: 22px; height: 1.5px; background: #fff; margin: 5px 0; transition: background 0.35s; }
        .ldt-nav.scrolled .nav-toggle span { background: #222; }

        /* Mobile menu */
        .ldt-mobile-menu { display: none; position: fixed; top: 70px; left: 0; right: 0; background: #fff; border-bottom: 1px solid #eee; padding: 16px 28px 24px; z-index: 998; }
        .ldt-mobile-menu.open { display: block; }
        .ldt-mobile-menu a { display: block; padding: 13px 0; font-family: 'Josefin Sans', sans-serif; font-size: 13px; letter-spacing: 1px; text-transform: uppercase; color: #555; border-bottom: 1px solid #f0ebe4; }
        .ldt-mobile-menu a:last-child { border-bottom: none; }
        .ldt-mobile-menu a.active { color: {{ $accent }}; }
        .mobile-lang { display: flex !important; gap: 8px; padding: 13px 0; border-bottom: 1px solid #f0ebe4; }
        .mobile-lang a { display: inline-block !important; padding: 4px 10px !important; border: 1px solid #ddd !important; font-size: 11px !important; color: #777 !important; border-bottom: 1px solid #ddd !important; }
        .mobile-lang a.active, .mobile-lang a:hover { background: {{ $accent }} !important; border-color: {{ $accent }} !important; color: #fff !important; }

        @media (max-width: 767px) { .ldt-nav .nav-links { display: none; } .ldt-nav .nav-toggle { display: block; } }

        main { padding-top: 0; }
        .ldt-container { max-width: 1140px; margin: 0 auto; padding: 0 28px; }

        /* ── Animations ─────────────────────────────────────────────── */
        .slide-up { opacity: 0; transform: translateY(80px); transition: opacity 1.1s cubic-bezier(0.16, 1, 0.3, 1), transform 1.1s cubic-bezier(0.16, 1, 0.3, 1); }
        .slide-up.visible { opacity: 1; transform: translateY(0); }
        .slide-up-child { opacity: 0; transform: translateY(50px); transition: opacity 1s cubic-bezier(0.16, 1, 0.3, 1), transform 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .slide-up-child.visible { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.15s; }
        .delay-2 { transition-delay: 0.35s; }
        @keyframes heroSlideUp { from { opacity: 0; transform: translateY(60px); } to { opacity: 1; transform: translateY(0); } }
        .hero-title-anim { animation: heroSlideUp 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both; }
        .hero-sub-anim   { animation: heroSlideUp 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.45s both; }

        /* ── Hero (Premium) ─────────────────────────────────────────── */
        .wedding-hero {
            position: relative; width: 100%; height: 480px;
            background-image: url("{{ $img('wedding_hero', 'Pelamin_wedding_hero.jpg') }}");
            background-size: cover; background-position: center top;
            display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        .wedding-hero::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.50) 100%);
        }
        .wedding-hero-content { position: relative; text-align: center; }
        .wedding-hero h1 {
            position: relative; font-family: 'Imperial Script', cursive;
            font-size: clamp(64px, 10vw, 110px); font-weight: 400; color: #ffffff;
            margin: 0; text-shadow: 0 2px 24px rgba(0,0,0,0.35); line-height: 1;
        }
        .wedding-hero-sub {
            font-family: 'Josefin Sans', sans-serif; font-size: 12px; font-weight: 300;
            letter-spacing: 4px; text-transform: uppercase; color: rgba(255,255,255,0.7);
            margin-top: 14px;
        }

        /* ── Intro (Butik) ──────────────────────────────────────────── */
        .wedding-intro { background: #ffffff; padding: 72px 0 64px; }
        .wedding-intro-wrap { max-width: 720px; margin: 0 auto; text-align: center; }
        .wedding-intro p {
            font-size: 15px; color: #6b6057; line-height: 2; margin-bottom: 16px;
        }
        .wedding-intro p:last-child { margin-bottom: 0; }
        .intro-ornament {
            display: block; width: 48px; height: 1px; background: {{ $accent }};
            margin: 0 auto 32px; opacity: 0.5;
        }

        /* ── Butik Venue Sections (full-width bg + overlay) ─────────── */
        .butik-section {
            position: relative; padding: 88px 0 96px;
            background-size: cover; background-position: center; background-attachment: fixed;
        }
        .butik-section::before {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: rgba(243, 237, 229, 0.90);
            transition: background 0.4s;
        }
        .butik-section:nth-of-type(even)::before { background: rgba(232, 224, 213, 0.90); }
        .butik-section .ldt-container { position: relative; z-index: 1; }

        .butik-row {
            display: flex; flex-direction: row; align-items: center; gap: 56px;
        }
        .butik-row-reverse { flex-direction: row-reverse; }
        .butik-text-col { flex: 0 0 42%; max-width: 42%; }
        .butik-img-col  { flex: 1; }

        .butik-title {
            font-family: '{{ $headingFont }}', cursive;
            font-size: clamp(38px, 5vw, 58px); font-weight: 400;
            color: #2a1f12; line-height: 1.1; margin: 0 0 14px 0;
        }

        .butik-price {
            display: inline-flex; align-items: baseline; gap: 6px;
            margin-bottom: 22px;
        }
        .butik-price-label {
            font-family: 'Josefin Sans', sans-serif; font-size: 10px; font-weight: 600;
            letter-spacing: 1.5px; text-transform: uppercase; color: #bba998;
        }
        .butik-price-amount {
            font-family: 'Josefin Sans', sans-serif; font-size: 26px; font-weight: 600;
            color: {{ $accent }}; letter-spacing: 0.5px;
        }

        .butik-desc {
            font-size: 14px; color: #5a4f42; line-height: 1.9; margin-bottom: 24px;
        }
        .butik-desc p { margin: 0 0 10px 0; }
        .butik-desc p:last-child { margin-bottom: 0; }

        .butik-divider {
            width: 40px; height: 1px; background: {{ $accent }}; opacity: 0.35;
            margin-bottom: 24px;
        }

        .butik-img {
            width: 100%; height: auto; display: block;
            border-radius: 12px; overflow: hidden;
            box-shadow: 0 12px 40px rgba(0,0,0,0.18);
            transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.5s ease;
        }
        .butik-img:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 56px rgba(0,0,0,0.22);
        }

        /* Items list */
        .butik-items { list-style: none; padding: 0; margin: 0 0 24px 0; }
        .butik-items li {
            font-size: 13px; color: #5a4f42; line-height: 1.5;
            padding: 6px 0 6px 24px; position: relative;
        }
        .butik-items li::before {
            content: '';
            position: absolute; left: 0; top: 12px;
            width: 8px; height: 8px; border-radius: 50%;
            border: 1.5px solid {{ $accent }};
        }
        .butik-item-qty {
            font-size: 11px; color: #bba998; font-weight: 500; margin-left: 4px;
        }

        /* Addons */
        .butik-addons {
            padding-top: 20px; border-top: 1px solid rgba(90,79,66,0.15);
        }
        .butik-addons-label {
            font-family: 'Josefin Sans', sans-serif; font-size: 10px; font-weight: 600;
            letter-spacing: 2px; text-transform: uppercase; color: #bba998;
            display: block; margin-bottom: 12px;
        }
        .butik-addons ul { list-style: none; padding: 0; margin: 0; }
        .butik-addons li {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 13px; color: #5a4f42; padding: 5px 0;
        }
        .butik-addon-price {
            font-family: 'Josefin Sans', sans-serif; font-size: 12px;
            color: {{ $accent }}; font-weight: 600; white-space: nowrap;
        }

        /* ── Footer ─────────────────────────────────────────────────── */
        .ldt-footer { background: #1a1510; padding: 40px 28px; text-align: center; }
        .ldt-footer .footer-brand { font-family: 'Imperial Script', cursive; font-size: 32px; color: rgba(255,255,255,0.8); margin-bottom: 12px; }
        .ldt-footer p { font-family: 'Josefin Sans', sans-serif; font-size: 11px; letter-spacing: 1px; color: rgba(255,255,255,0.4); text-transform: uppercase; }
        .ldt-footer .footer-links { display: flex; justify-content: center; gap: 24px; margin-bottom: 16px; }
        .ldt-footer .footer-links a { font-family: 'Josefin Sans', sans-serif; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,0.5); transition: color 0.2s; }
        .ldt-footer .footer-links a:hover { color: {{ $accent }}; }

        /* ── Dynamic font overrides ─────────────────────────────────── */
        body { font-family: '{{ $bodyFont }}', sans-serif; }
        .ldt-nav .nav-brand,
        .wedding-hero h1,
        .butik-title,
        .ldt-footer .footer-brand { font-family: '{{ $headingFont }}', cursive; }

        /* ── Responsive ─────────────────────────────────────────────── */
        @media (max-width: 991px) {
            .butik-row,
            .butik-row.butik-row-reverse { flex-direction: column; gap: 32px; }
            .butik-text-col, .butik-img-col { flex: unset; max-width: 100%; width: 100%; }
            .butik-section { padding: 60px 0 68px; background-attachment: scroll; }
            .wedding-hero { height: 320px; }
        }

        @media (max-width: 575px) {
            .butik-section { padding: 48px 0 56px; }
            .butik-price-amount { font-size: 22px; }
            .wedding-hero { height: 260px; }
        }
    </style>
</head>
<body>

    @include('home.special_page.lady_d_touch._navbar')

    <main>

        <div class="wedding-hero">
            <div class="wedding-hero-content">
                <h1 class="hero-title-anim">Perkahwinan</h1>
                <p class="wedding-hero-sub hero-sub-anim">{{ $organizer->name }}</p>
            </div>
        </div>

        @if($vis('wedding_intro'))
        <section class="wedding-intro">
            <div class="ldt-container">
                <div class="wedding-intro-wrap">
                    <span class="intro-ornament slide-up"></span>
                    <p class="slide-up">{{ $txt('wedding_intro', 'intro1', 'Sama ada majlis romantik yang kecil atau perayaan besar-besaran, lihat pelbagai pilihan kami untuk memulakan perjalanan anda ke arah perkahwinan yang indah dan tidak terlupakan.') }}</p>
                    <p class="slide-up">{{ $txt('wedding_intro', 'intro2', 'Lengkapkan tetapan acara anda dengan item tambahan individu kepada pakej pilihan.') }}</p>
                    <p class="slide-up">{{ $txt('wedding_intro', 'intro3', 'Di ' . $organizer->name . ', tetamu mempunyai pilihan untuk mengadakan majlis mereka sama ada di dalam atau di luar. Kami menawarkan pelbagai pakej perkahwinan dari gaya mudah namun elegan hingga tetapan moden yang mewah. Menu makanan Melayu dan Antarabangsa yang lazat juga tersedia untuk keperluan F&B anda.') }}</p>
                    <p class="slide-up">{{ $txt('wedding_intro', 'intro4', 'Rumah ini termasuk penggunaan bilik pengantin dengan bilik mandi dalam serta dua bilik persendirian lain untuk tetamu.') }}</p>
                </div>
            </div>
        </section>
        @endif

        @foreach ($weddingPackages as $package)
        <section class="butik-section slide-up" style="background-image: url('{{ $package->display_image_url }}');">
            <div class="ldt-container">
                <div class="butik-row {{ $loop->odd ? 'butik-row-reverse' : '' }}">
                    <div class="butik-text-col slide-up-child delay-1">
                        <h3 class="butik-title">{{ $package->name }}</h3>

                        @if($package->final_price)
                        <div class="butik-price">
                            <span class="butik-price-label">Bermula</span>
                            <span class="butik-price-amount">RM {{ number_format($package->final_price, 0) }}</span>
                        </div>
                        @endif

                        @if($package->description)
                        <div class="butik-desc">{!! $package->description !!}</div>
                        @endif

                        @if($package->items->isNotEmpty())
                        <div class="butik-divider"></div>
                        <ul class="butik-items">
                            @foreach ($package->items as $item)
                            <li>
                                {{ $item->title }}
                                @if($item->quantity > 1) <span class="butik-item-qty">&times;{{ $item->quantity }}</span> @endif
                            </li>
                            @endforeach
                        </ul>
                        @endif

                        @if($package->addons->isNotEmpty())
                        <div class="butik-addons">
                            <span class="butik-addons-label">Tambahan</span>
                            <ul>
                                @foreach ($package->addons as $addon)
                                <li>
                                    <span>{{ $addon->name }}</span>
                                    @if($addon->price > 0) <span class="butik-addon-price">+ RM {{ number_format($addon->price, 0) }}</span> @endif
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="butik-img-col slide-up-child delay-2">
                        <img src="{{ $package->display_image_url }}" alt="{{ $package->name }}" class="butik-img" loading="lazy">
                    </div>
                </div>
            </div>
        </section>
        @endforeach

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
