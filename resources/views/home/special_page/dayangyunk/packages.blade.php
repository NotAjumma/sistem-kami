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

    // Venue overlay helper: hex + opacity → rgba
    $venueOverlay = function(string $s) use ($cfg) {
        $hex = $cfg['sections'][$s]['overlay_color']   ?? '#f3ede5';
        $op  = (float)($cfg['sections'][$s]['overlay_opacity'] ?? 0.90);
        [$r, $g, $b] = sscanf($hex, '#%02x%02x%02x');
        return "rgba({$r},{$g},{$b},{$op})";
    };

    // Build a background image for each group section (first child cover or fallback)
    $groupBgs = [];
    $fallbacks = ['Pelamin_DSDusun_2024-1-1024x618.jpeg', 'DataranSriDusun-1024x682.jpg', 'LamanDusun-1024x768.jpg'];
    foreach ($packageGroups as $gi => $group) {
        $bg = null;
        foreach ($group->children as $c) {
            $cover = $c->images->firstWhere('is_cover', true) ?? $c->images->first();
            if ($cover) {
                $bg = asset('storage/uploads/' . $organizer->id . '/packages/' . $c->id . '/' . $cover->url);
                break;
            }
        }
        $groupBgs[$group->id] = $bg ?? asset('storage/' . ($fallbacks[$gi % 3]));
    }

@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ms' ? 'ms-MY' : 'en-GB' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $organizer->name }} | Pakej</title>
    <meta name="description" content="Terokai pakej perkahwinan yang ditawarkan oleh {{ $organizer->name }}.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ $gfHeading }}&family=Josefin+Sans:wght@300;400;600&family={{ $gfBody }}&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Poppins', sans-serif; background: #fff; color: #999; }
        img  { display: block; max-width: 100%; }
        a    { text-decoration: none; color: inherit; }

        /* Navbar */
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

        /* Animations */
        .slide-up { opacity: 0; transform: translateY(80px); transition: opacity 1.1s cubic-bezier(0.16, 1, 0.3, 1), transform 1.1s cubic-bezier(0.16, 1, 0.3, 1); }
        .slide-up.visible { opacity: 1; transform: translateY(0); }
        .slide-up-child { opacity: 0; transform: translateY(50px); transition: opacity 1s cubic-bezier(0.16, 1, 0.3, 1), transform 1s cubic-bezier(0.16, 1, 0.3, 1); }
        .slide-up-child.visible { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.15s; }
        .delay-2 { transition-delay: 0.30s; }
        .delay-3 { transition-delay: 0.45s; }
        @keyframes heroSlideUp { from { opacity: 0; transform: translateY(60px); } to { opacity: 1; transform: translateY(0); } }
        .hero-title-anim { animation: heroSlideUp 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both; }

        /* Hero */
        .pkg-hero { position: relative; width: 100%; height: 380px; background-image: url("{{ $img('packages_hero', $spImgs['wedding_hero'] ?? 'Pelamin_wedding_hero.jpg') }}"); background-size: cover; background-position: center top; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .pkg-hero::before { content: ''; position: absolute; inset: 0; background: rgba(0, 0, 0, 0.42); }
        .pkg-hero h1 { position: relative; font-family: 'Imperial Script', cursive; font-size: clamp(64px, 10vw, 100px); font-weight: 400; color: #ffffff; margin: 0; text-shadow: 0 2px 18px rgba(0,0,0,0.4); line-height: 1; }

        /* Intro */
        .pkg-intro { background: #ffffff; padding: 54px 0 46px; }
        .pkg-intro p { font-size: 15px; color: #555555; line-height: 1.85; margin-bottom: 14px; }
        .pkg-intro p:last-child { margin-bottom: 0; }

        /* Group sections (parallax, like venue sections) */
        .pkg-group-section { padding: 80px 0; position: relative; background-size: cover; background-position: center; background-attachment: fixed; }
        .pkg-group-section::before { content: ''; position: absolute; inset: 0; pointer-events: none; }
        .pkg-group-section:nth-of-type(odd)::before  { background: rgba(243, 237, 229, 0.90); }
        .pkg-group-section:nth-of-type(even)::before { background: rgba(235, 228, 218, 0.90); }
        .pkg-group-section .ldt-container { position: relative; z-index: 1; }

        /* Group heading row */
        .pkg-group-header { margin-bottom: 36px; }
        .pkg-group-name { font-family: 'Imperial Script', cursive; font-size: clamp(42px, 5.5vw, 62px); font-weight: 400; color: #2a1f12; line-height: 1.1; margin: 0 0 12px 0; }
        .pkg-group-desc { font-size: 15px; color: #5a4f42; line-height: 1.85; margin: 0; max-width: 640px; }

        /* Package cards grid */
        .pkg-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
        .pkg-card { background: rgba(255,255,255,0.94); overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 2px 16px rgba(0,0,0,0.07); transition: box-shadow 0.25s, transform 0.25s; }
        .pkg-card:hover { box-shadow: 0 10px 36px rgba(0,0,0,0.13); transform: translateY(-4px); }
        .pkg-card-img { width: 100%; height: 200px; object-fit: cover; display: block; }
        .pkg-card-no-img { width: 100%; height: 200px; background: #ede8e1; display: flex; align-items: center; justify-content: center; }
        .pkg-card-body { padding: 18px 20px 22px; flex: 1; display: flex; flex-direction: column; }
        .pkg-card-name { font-family: 'Josefin Sans', sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: #222; margin-bottom: 8px; }
        .pkg-card-code { font-family: 'Josefin Sans', sans-serif; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; color: #bbb; margin-bottom: 8px; }
        .pkg-card-desc { font-size: 13px; color: #aaa; line-height: 1.75; margin-bottom: 12px; flex: 1; }
        .pkg-card-price { font-family: 'Josefin Sans', sans-serif; font-size: 12px; color: #888; margin-bottom: 14px; }
        .pkg-card-price strong { font-size: 16px; color: #2a1f12; font-weight: 600; }
        .pkg-card-btn { display: block; text-align: center; font-family: 'Josefin Sans', sans-serif; font-size: 10px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: #fff; background: {{ $accent }}; padding: 11px 16px; transition: background 0.2s; margin-top: auto; }
        .pkg-card-btn:hover { background: #0ea5c9; color: #fff; }

        /* Standalone packages section */
        .pkg-standalone-section { padding: 80px 0; background: #fff; }
        .pkg-standalone-eyebrow { font-family: 'Josefin Sans', sans-serif; font-size: 11px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase; color: {{ $accent }}; margin-bottom: 8px; }
        .pkg-standalone-heading { font-family: 'Imperial Script', cursive; font-size: clamp(42px, 5.5vw, 60px); font-weight: 400; color: #222; line-height: 1.1; margin: 0 0 36px 0; }

        /* Footer */
        .ldt-footer { background: #1a1510; padding: 40px 28px; text-align: center; }
        .ldt-footer .footer-brand { font-family: 'Imperial Script', cursive; font-size: 32px; color: rgba(255,255,255,0.8); margin-bottom: 12px; }
        .ldt-footer p { font-family: 'Josefin Sans', sans-serif; font-size: 11px; letter-spacing: 1px; color: rgba(255,255,255,0.4); text-transform: uppercase; }
        .ldt-footer .footer-links { display: flex; justify-content: center; gap: 24px; margin-bottom: 16px; }
        .ldt-footer .footer-links a { font-family: 'Josefin Sans', sans-serif; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,0.5); transition: color 0.2s; }
        .ldt-footer .footer-links a:hover { color: {{ $accent }}; }

        /* Dynamic font overrides */
        body { font-family: '{{ $bodyFont }}', sans-serif; }
        .ldt-nav .nav-brand,
        .pkg-hero h1,
        .pkg-group-name,
        .pkg-standalone-heading,
        .ldt-footer .footer-brand { font-family: '{{ $headingFont }}', cursive; }

        /* Responsive */
        @media (max-width: 991px) {
            .pkg-group-section { background-attachment: scroll; }
            .pkg-grid { grid-template-columns: 1fr 1fr; }
            .pkg-hero { height: 260px; }
        }
        @media (max-width: 575px) {
            .pkg-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    @include('home.special_page.lady_d_touch._navbar')

    <main>

        {{-- Hero --}}
        <div class="pkg-hero">
            <h1 class="hero-title-anim">{{ $txt('packages', 'heading', 'Pakej Kami') }}</h1>
        </div>

        {{-- Intro --}}
        <section class="pkg-intro">
            <div class="ldt-container" style="max-width:760px;">
                <p class="slide-up">{{ $txt('packages', 'p1', 'Kami menawarkan pelbagai pakej perkahwinan yang direka dengan teliti untuk memenuhi setiap perayaan, bajet, dan gaya.') }}</p>
                <p class="slide-up">{{ $txt('packages', 'p2', 'Setiap pakej direka untuk meringankan beban perancangan, supaya anda boleh fokus kepada perkara yang paling penting — hari istimewa anda.') }}</p>
            </div>
        </section>

        {{-- Package Groups — each group = a parallax section --}}
        @foreach ($packageGroups as $group)
            @if ($group->children->isNotEmpty())
            <section class="pkg-group-section slide-up"
                style="background-image: url('{{ $groupBgs[$group->id] }}');">
                <div class="ldt-container">
                    <div class="pkg-group-header slide-up-child delay-1">
                        <h2 class="pkg-group-name">{{ $group->name }}</h2>
                        @if ($group->description)
                            <p class="pkg-group-desc">{{ strip_tags($group->description) }}</p>
                        @endif
                    </div>
                    <div class="pkg-grid">
                        @foreach ($group->children as $ci => $pkg)
                            @php
                                $cover = $pkg->images->firstWhere('is_cover', true) ?? $pkg->images->first();
                                $delayClass = $ci < 4 ? 'delay-' . ($ci + 1) : '';
                            @endphp
                            <div class="pkg-card slide-up-child {{ $delayClass }}">
                                @if ($cover)
                                    <img src="{{ asset('storage/uploads/' . $organizer->id . '/packages/' . $pkg->id . '/' . $cover->url) }}"
                                        alt="{{ $pkg->name }}" class="pkg-card-img" loading="lazy">
                                @else
                                    <div class="pkg-card-no-img">
                                        <svg width="48" height="48" fill="none" stroke="#c9bfb2" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9l4-4 4 4 4-4 4 4"/><circle cx="8.5" cy="13.5" r="1.5"/></svg>
                                    </div>
                                @endif
                                <div class="pkg-card-body">
                                    <p class="pkg-card-name">{{ $pkg->name }}</p>
                                    @if ($pkg->package_code)
                                        <p class="pkg-card-code">{{ $pkg->package_code }}</p>
                                    @endif
                                    @if ($pkg->description)
                                        <p class="pkg-card-desc">{{ Str::limit(strip_tags($pkg->description), 100) }}</p>
                                    @endif
                                    @if ($pkg->final_price)
                                        <p class="pkg-card-price">From <strong>RM {{ number_format($pkg->final_price, 0) }}</strong></p>
                                    @endif
                                    <a href="{{ route('business.booking', [$organizer->slug, $pkg->slug]) }}"
                                        class="pkg-card-btn">Tempah Sekarang &rarr;</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif
        @endforeach

        {{-- Standalone packages (not in any group) --}}
        @if ($standalonePackages->isNotEmpty())
        <section class="pkg-standalone-section slide-up">
            <div class="ldt-container">
                @if ($packageGroups->isNotEmpty())
                    <p class="pkg-standalone-eyebrow">Pakej Lain</p>
                @endif
                <h2 class="pkg-standalone-heading">{{ $packageGroups->isNotEmpty() ? 'Tawaran Lain' : 'Pakej Kami' }}</h2>
                <div class="pkg-grid">
                    @foreach ($standalonePackages as $ci => $pkg)
                        @php
                            $cover = $pkg->images->firstWhere('is_cover', true) ?? $pkg->images->first();
                            $delayClass = $ci < 4 ? 'delay-' . ($ci + 1) : '';
                        @endphp
                        <div class="pkg-card slide-up-child {{ $delayClass }}">
                            @if ($cover)
                                <img src="{{ asset('storage/uploads/' . $organizer->id . '/packages/' . $pkg->id . '/' . $cover->url) }}"
                                    alt="{{ $pkg->name }}" class="pkg-card-img" loading="lazy">
                            @else
                                <div class="pkg-card-no-img">
                                    <svg width="48" height="48" fill="none" stroke="#c9bfb2" stroke-width="1.5" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9l4-4 4 4 4-4 4 4"/><circle cx="8.5" cy="13.5" r="1.5"/></svg>
                                </div>
                            @endif
                            <div class="pkg-card-body">
                                <p class="pkg-card-name">{{ $pkg->name }}</p>
                                @if ($pkg->package_code)
                                    <p class="pkg-card-code">{{ $pkg->package_code }}</p>
                                @endif
                                @if ($pkg->description)
                                    <p class="pkg-card-desc">{{ Str::limit(strip_tags($pkg->description), 100) }}</p>
                                @endif
                                @if ($pkg->final_price)
                                    <p class="pkg-card-price">From <strong>RM {{ number_format($pkg->final_price, 0) }}</strong></p>
                                @endif
                                <a href="{{ route('business.booking', [$organizer->slug, $pkg->slug]) }}"
                                    class="pkg-card-btn">Tempah Sekarang &rarr;</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- Empty state --}}
        @if ($packageGroups->isEmpty() && $standalonePackages->isEmpty())
        <section class="pkg-standalone-section">
            <div class="ldt-container" style="text-align:center;padding:60px 28px;">
                <p style="font-family:'Josefin Sans',sans-serif;font-size:13px;letter-spacing:1px;text-transform:uppercase;color:#ccc;">
                    Pakej akan datang tidak lama lagi. Nantikan!
                </p>
            </div>
        </section>
        @endif

    </main>

    @include('home.special_page.lady_d_touch._footer')

    <script>
        // Navbar transparent → solid on scroll
        (function () {
            var nav = document.getElementById('ldt-nav');
            var hero = document.querySelector('.pkg-hero');
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
