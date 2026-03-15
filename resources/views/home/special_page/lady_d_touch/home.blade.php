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
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ms' ? 'ms-MY' : 'en-GB' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $organizer->name }}</title>
    <meta name="description" content="{{ $organizer->description ?? 'Beautiful wedding venue for your special day.' }}">

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
        .ldt-nav .nav-inner {
            width: 100%; max-width: 1200px; margin: 0 auto; padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .ldt-nav .nav-brand { font-family: 'Imperial Script', cursive; font-size: 26px; font-weight: 400; color: #fff; line-height: 1; transition: color 0.35s; }
        .ldt-nav.scrolled .nav-brand { color: #222; }
        .ldt-nav .nav-links { display: flex; align-items: center; gap: 28px; list-style: none; }
        .ldt-nav .nav-links a {
            font-family: 'Josefin Sans', sans-serif; font-size: 12px; font-weight: 400;
            letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,0.85);
            transition: color 0.2s; position: relative; padding-bottom: 3px;
        }
        .ldt-nav .nav-links a::after {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0;
            height: 1px; background: {{ $accent }}; transform: scaleX(0); transition: transform 0.25s;
        }
        .ldt-nav .nav-links a:hover, .ldt-nav .nav-links a.active { color: {{ $accent }}; }
        .ldt-nav .nav-links a.active::after, .ldt-nav .nav-links a:hover::after { transform: scaleX(1); }
        .ldt-nav.scrolled .nav-links a { color: #444; }
        .ldt-nav.scrolled .nav-links a:hover, .ldt-nav.scrolled .nav-links a.active { color: {{ $accent }}; }

        /* Lang switcher */
        .lang-switcher { display: flex; align-items: center; gap: 4px; }
        .lang-switcher a {
            font-family: 'Josefin Sans', sans-serif; font-size: 11px; font-weight: 600;
            letter-spacing: 1px; padding: 3px 7px; border: 1px solid rgba(255,255,255,0.4);
            color: rgba(255,255,255,0.75) !important; transition: background 0.2s, color 0.2s, border-color 0.2s;
        }
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

        /* Scroll animations */
        .reveal { opacity: 0; transform: translateY(50px); transition: opacity 0.9s cubic-bezier(0.16,1,0.3,1), transform 0.9s cubic-bezier(0.16,1,0.3,1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.25s; }
        @keyframes heroLoad { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }

        main { padding-top: 0; }
        .ldt-container { max-width: 1140px; margin: 0 auto; padding: 0 28px; }

        /* Hero */
        .hero-section {
            position: relative; width: 100%; height: 100vh; min-height: 500px;
            background-image: url("{{ $img('hero', 'lady_d_touch/hero-main.jpg') }}");
            background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        .hero-section::before { content: ''; position: absolute; inset: 0; background: rgba(10, 18, 30, 0.45); }
        .hero-content { position: relative; text-align: center; animation: heroLoad 1.3s cubic-bezier(0.16,1,0.3,1) 0.2s both; padding: 0 24px; }
        .hero-content h1 { font-family: 'Imperial Script', cursive; font-size: clamp(56px, 10vw, 110px); font-weight: 400; color: #fff; line-height: 1; margin-bottom: 18px; text-shadow: 0 2px 24px rgba(0,0,0,0.35); }
        .hero-content p { font-family: 'Josefin Sans', sans-serif; font-size: clamp(11px, 1.5vw, 14px); font-weight: 300; letter-spacing: 4px; text-transform: uppercase; color: rgba(255,255,255,0.82); }

        /* About */
        .about-section { padding: 90px 0; background: #fff; }
        .about-row { display: flex; flex-direction: row; align-items: center; gap: 60px; }
        .about-text { flex: 0 0 44%; max-width: 44%; }
        .about-text p { font-size: 14px; color: #888; line-height: 1.9; margin-bottom: 14px; }
        .about-text p:last-of-type { margin-bottom: 28px; }
        .about-link { font-family: 'Josefin Sans', sans-serif; font-size: 11px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: #222; border-bottom: 1px solid #222; padding-bottom: 2px; display: inline-flex; align-items: center; gap: 8px; transition: color 0.2s, border-color 0.2s; }
        .about-link:hover { color: {{ $accent }}; border-color: {{ $accent }}; }
        .about-img-col { flex: 1; }
        .about-img-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
        .about-img-grid img { width: 100%; height: 200px; object-fit: cover; }
        .about-img-grid img:first-child { grid-column: 1 / -1; height: 260px; }

        /* Gallery */
        .gallery-section { padding: 90px 0; background: #f9f7f4; overflow: hidden; }
        .gallery-row { display: flex; flex-direction: row; align-items: center; gap: 60px; }
        .gallery-text { flex: 0 0 40%; max-width: 40%; }
        .gallery-text .eyebrow { font-family: 'Josefin Sans', sans-serif; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #bbb; margin-bottom: 12px; }
        .gallery-text h2 { font-family: 'Imperial Script', cursive; font-size: clamp(40px, 5vw, 58px); font-weight: 400; color: #222; line-height: 1.15; margin-bottom: 28px; }
        .gallery-text .gallery-link { font-family: 'Josefin Sans', sans-serif; font-size: 11px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: #222; border-bottom: 1px solid #222; padding-bottom: 2px; display: inline-flex; align-items: center; gap: 8px; transition: color 0.2s, border-color 0.2s; }
        .gallery-text .gallery-link:hover { color: {{ $accent }}; border-color: {{ $accent }}; }
        .gallery-img-col { flex: 1; }
        .gallery-couple-img { width: 100%; max-width: 480px; margin-left: auto; box-shadow: 0 12px 40px rgba(0,0,0,0.12); }

        /* Wedding showcase */
        .wedding-section { padding: 90px 0; background: #fff; }
        .wedding-row { display: flex; flex-direction: row; align-items: center; gap: 60px; }
        .wedding-img-col { flex: 0 0 50%; max-width: 50%; }
        .wedding-mosaic { display: grid; grid-template-columns: 1fr 1fr; gap: 6px; }
        .wedding-mosaic img { width: 100%; object-fit: cover; }
        .wedding-mosaic img:nth-child(1) { grid-column: 1 / -1; height: 260px; }
        .wedding-mosaic img:nth-child(2), .wedding-mosaic img:nth-child(3) { height: 180px; }
        .wedding-text { flex: 1; }
        .wedding-text h2 { font-family: 'Imperial Script', cursive; font-size: clamp(44px, 5.5vw, 66px); font-weight: 400; color: #222; line-height: 1.1; margin-bottom: 20px; }
        .wedding-text p { font-size: 14px; color: #888; line-height: 1.9; margin-bottom: 12px; }
        .wedding-text p:last-of-type { margin-bottom: 28px; }
        .btn-wedding { display: inline-flex; align-items: center; gap: 8px; font-family: 'Josefin Sans', sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: #fff; background: {{ $accent }}; padding: 14px 28px; transition: background 0.2s; }
        .btn-wedding:hover { background: #0ea5c9; color: #fff; }

        /* Location */
        .location-section { padding: 90px 0; background: #f9f7f4; }
        .location-row { display: flex; flex-direction: row; align-items: flex-start; gap: 60px; }
        .location-text { flex: 0 0 42%; max-width: 42%; }
        .location-text h2 { font-family: 'Imperial Script', cursive; font-size: clamp(40px, 5vw, 58px); font-weight: 400; color: #222; line-height: 1.1; margin-bottom: 28px; }
        .location-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; font-size: 14px; color: #888; line-height: 1.6; }
        .location-item svg { flex-shrink: 0; margin-top: 3px; }
        .location-map-col { flex: 1; }
        .location-map-col img { width: 100%; box-shadow: 0 8px 32px rgba(0,0,0,0.1); }
        .hours-box { margin-top: 28px; padding: 20px; background: #fff; border-left: 3px solid {{ $accent }}; }
        .hours-box h4 { font-family: 'Josefin Sans', sans-serif; font-size: 12px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: #222; margin-bottom: 10px; }
        .hours-box p { font-size: 13px; color: #999; margin-bottom: 4px; }

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
        .hero-content h1,
        .gallery-text h2,
        .wedding-text h2,
        .location-text h2,
        .ldt-footer .footer-brand { font-family: '{{ $headingFont }}', cursive; }

        /* Responsive */
        @media (max-width: 991px) {
            .about-row, .gallery-row, .wedding-row, .location-row { flex-direction: column; gap: 32px; }
            .about-text, .gallery-text, .wedding-img-col, .wedding-text, .location-text, .location-map-col, .about-img-col, .gallery-img-col { flex: unset; max-width: 100%; width: 100%; }
            .hero-section { height: 100vh; min-height: 400px; }
            .about-section, .gallery-section, .wedding-section, .location-section { padding: 60px 0; }
        }
    </style>
</head>
<body>

    @include('home.special_page.lady_d_touch._navbar')

    <main>

        @if($vis('hero'))
        <section class="hero-section">
            <div class="hero-content">
                <h1>{{ $organizer->name }}</h1>
                <p>{{ $txt('hero', 'slogan', __('lady_d_touch.hero_slogan')) }}</p>
            </div>
        </section>
        @endif

        @if($vis('about'))
        <section class="about-section" id="story">
            <div class="ldt-container">
                <div class="about-row">
                    <div class="about-text reveal">
                        <p>{{ $txt('about', 'p1', __('lady_d_touch.about_p1', ['name' => $organizer->name])) }}</p>
                        <p>{{ $txt('about', 'p2', __('lady_d_touch.about_p2')) }}</p>
                        <p>{{ $txt('about', 'p3', __('lady_d_touch.about_p3', ['name' => $organizer->name])) }}</p>
                        <a href="{{ route('special-page.wedding', ['slug' => $specialPage]) }}" class="about-link">{{ $txt('about', 'link_text', __('lady_d_touch.about_link')) }}</a>
                    </div>
                    <div class="about-img-col reveal reveal-delay-2">
                        <div class="about-img-grid">
                            <img src="{{ $img('venue_dewan', 'Pelamin_DSDusun_2024-1-1024x618.jpeg') }}" alt="Venue" loading="lazy">
                            <img src="{{ $img('venue_dataran', 'DataranSriDusun-1024x682.jpg') }}" alt="Dataran" loading="lazy">
                            <img src="{{ $img('venue_laman', 'LamanDusun-1024x768.jpg') }}" alt="Laman" loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        @if($vis('gallery'))
        <section class="gallery-section">
            <div class="ldt-container">
                <div class="gallery-row">
                    <div class="gallery-text reveal">
                        <p class="eyebrow">{{ $txt('gallery', 'eyebrow', __('lady_d_touch.gallery_eyebrow')) }}</p>
                        <h2>{{ $txt('gallery', 'heading', __('lady_d_touch.gallery_heading')) }}</h2>
                        @php $fbLink = ($organizer->social_links ?? [])['facebook'] ?? null; @endphp
                        @if($fbLink)
                        <a href="{{ $fbLink }}" target="_blank" class="gallery-link">{{ $txt('gallery', 'link_text', __('lady_d_touch.gallery_link')) }}</a>
                        @else
                        <a href="{{ route('special-page.wedding', ['slug' => $specialPage]) }}" class="gallery-link">{{ $txt('gallery', 'link_text', __('lady_d_touch.gallery_link_venues')) }}</a>
                        @endif
                    </div>
                    <div class="gallery-img-col reveal reveal-delay-2">
                        <img src="{{ $img('gallery', 'lady_d_touch/gallery-couple.png') }}" alt="Gallery" class="gallery-couple-img" loading="lazy">
                    </div>
                </div>
            </div>
        </section>
        @endif

        @if($vis('venues'))
        <section class="wedding-section" id="venues">
            <div class="ldt-container">
                <div class="wedding-row">
                    <div class="wedding-img-col reveal">
                        <div class="wedding-mosaic">
                            <img src="{{ $img('venue_dataran', 'DataranSriDusun-1024x682.jpg') }}" alt="Dataran Sri Dusun" loading="lazy">
                            <img src="{{ $img('venue_dewan', 'Pelamin_DSDusun_2024-1-1024x618.jpeg') }}" alt="Dewan Sri Dusun" loading="lazy">
                            <img src="{{ $img('venue_laman', 'LamanDusun-1024x768.jpg') }}" alt="Laman Dusun" loading="lazy">
                        </div>
                    </div>
                    <div class="wedding-text reveal reveal-delay-2">
                        <h2>{{ $txt('venues', 'heading', __('lady_d_touch.showcase_heading')) }}</h2>
                        <p>{{ $txt('venues', 'p1', __('lady_d_touch.showcase_p1')) }}</p>
                        <p>{{ $txt('venues', 'p2', __('lady_d_touch.showcase_p2')) }}</p>
                        <a href="{{ route('special-page.wedding', ['slug' => $specialPage]) }}" class="btn-wedding">{{ $txt('venues', 'btn_text', __('lady_d_touch.showcase_btn')) }}</a>
                    </div>
                </div>
            </div>
        </section>
        @endif

        @if($vis('location'))
        <section class="location-section" id="location">
            <div class="ldt-container">
                <div class="location-row">
                    <div class="location-text reveal">
                        <h2>{{ $txt('location', 'heading', __('lady_d_touch.location_heading')) }}</h2>
                        @if($organizer->address_line1 || $organizer->city)
                        <div class="location-item">
                            <svg width="16" height="16" fill="none" stroke="{{ $accent }}" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $organizer->address_line1 }}@if($organizer->address_line2), {{ $organizer->address_line2 }}@endif @if($organizer->city), {{ $organizer->city }}@endif @if($organizer->state), {{ $organizer->state }}@endif @if($organizer->postal_code){{ $organizer->postal_code }}@endif</span>
                        </div>
                        @endif
                        @if($organizer->email)
                        <div class="location-item">
                            <svg width="16" height="16" fill="none" stroke="{{ $accent }}" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $organizer->email }}" style="color:#888;">{{ $organizer->email }}</a>
                        </div>
                        @endif
                        @if($organizer->phone)
                        <div class="location-item">
                            <svg width="16" height="16" fill="none" stroke="{{ $accent }}" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:{{ $organizer->phone }}" style="color:#888;">{{ $organizer->phone }}</a>
                        </div>
                        @endif
                        <div class="hours-box">
                            <h4>{{ $txt('location', 'hours_heading', __('lady_d_touch.hours_heading')) }}</h4>
                            <p>{{ $txt('location', 'hours_line1', __('lady_d_touch.hours_line1')) }}</p>
                            <p style="font-size:12px;color:#bbb;">{{ $txt('location', 'hours_note', __('lady_d_touch.hours_note')) }}</p>
                        </div>
                    </div>
                    <div class="location-map-col reveal reveal-delay-2">
                        <img src="{{ $img('map', 'lady_d_touch/map.jpeg') }}" alt="Our Location" loading="lazy">
                    </div>
                </div>
            </div>
        </section>
        @endif

    </main>

    @include('home.special_page.lady_d_touch._footer')

    <script>
        (function () {
            var nav = document.getElementById('ldt-nav');
            function update() { window.scrollY > 60 ? nav.classList.add('scrolled') : nav.classList.remove('scrolled'); }
            update();
            window.addEventListener('scroll', update, { passive: true });
        })();
        document.getElementById('ldt-toggle').addEventListener('click', function () {
            document.getElementById('ldt-mobile-menu').classList.toggle('open');
        });
        document.querySelectorAll('.ldt-ml').forEach(function (a) {
            a.addEventListener('click', function () { document.getElementById('ldt-mobile-menu').classList.remove('open'); });
        });
        (function () {
            var obs = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
            }, { threshold: 0, rootMargin: '0px 0px -60px 0px' });
            document.querySelectorAll('.reveal').forEach(function (el) {
                var r = el.getBoundingClientRect();
                if (r.top < window.innerHeight) { setTimeout(function () { el.classList.add('visible'); }, 80); }
                else { obs.observe(el); }
            });
        })();
    </script>
</body>
</html>
