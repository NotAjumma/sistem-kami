@extends('home.homeLayout')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Imperial+Script&display=swap" rel="stylesheet">

<style>
    /* ── Override global h1/h3 font with Imperial Script ─────────────────────── */
    .wedding-hero h1,
    .venue-name {
        font-family: 'Imperial Script', cursive !important;
    }

    /* ── Scroll / load animation ─────────────────────────────────────────────── */
    .slide-up {
        opacity: 0;
        transform: translateY(80px);
        transition: opacity 1.1s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 1.1s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .slide-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Hero title plays on page load via CSS animation */
    @keyframes heroSlideUp {
        from { opacity: 0; transform: translateY(60px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .hero-title-anim {
        animation: heroSlideUp 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both;
    }

    /* Inner stagger children */
    .slide-up-child {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 1s cubic-bezier(0.16, 1, 0.3, 1),
                    transform 1s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .slide-up-child.visible { opacity: 1; transform: translateY(0); }
    .delay-1 { transition-delay: 0.15s; }
    .delay-2 { transition-delay: 0.35s; }

    /* ── Hero ────────────────────────────────────────────────────────────────── */
    .wedding-hero {
        position: relative;
        width: 100%;
        height: 340px;
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
        font-size: clamp(64px, 10vw, 100px) !important;
        font-weight: 400 !important;
        color: #ffffff !important;
        margin: 0;
        text-shadow: 0 2px 18px rgba(0,0,0,0.4);
        line-height: 1;
    }

    /* ── Intro section ───────────────────────────────────────────────────────── */
    .wedding-intro {
        background: #ffffff;
        padding: 54px 0 46px;
    }
    .wedding-intro p {
        font-size: 15px;
        color: #555555;
        line-height: 1.85;
        margin-bottom: 14px;
    }
    .wedding-intro p:last-child { margin-bottom: 0; }

    /* ── Venue sections: each with its own bg image + cream overlay ──────────── */
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

    .venue-section .container { position: relative; z-index: 1; }

    /* ── Venue layout: text LEFT, image RIGHT ────────────────────────────────── */
    .venue-row {
        display: flex;
        flex-direction: row;
        align-items: center;
        gap: 52px;
    }
    .venue-text-col { flex: 0 0 36%; max-width: 36%; }
    .venue-img-col  { flex: 1; }

    @media (max-width: 991.98px) {
        .venue-row       { flex-direction: column; gap: 28px; }
        .venue-text-col,
        .venue-img-col   { flex: unset; max-width: 100%; width: 100%; }
        .venue-section   { background-attachment: scroll; }
        .wedding-hero    { height: 230px; }
    }

    /* ── Venue title ─────────────────────────────────────────────────────────── */
    .venue-name {
        font-size: clamp(42px, 5.5vw, 62px) !important;
        font-weight: 400 !important;
        color: #2a1f12;
        line-height: 1.1;
        margin: 0 0 20px 0;
    }

    /* ── Venue description ───────────────────────────────────────────────────── */
    .venue-desc {
        font-size: 15px;
        color: #5a4f42;
        line-height: 1.85;
        margin: 0;
    }

    /* ── Venue image ─────────────────────────────────────────────────────────── */
    .venue-img {
        width: 100%;
        height: auto;
        display: block;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    }
</style>
@endpush

@section('content')
<main>

    {{-- ── Hero ─────────────────────────────────────────────────────────────── --}}
    <div class="wedding-hero">
        <h1 class="hero-title-anim">Wedding</h1>
    </div>

    {{-- ── Intro text ──────────────────────────────────────────────────────── --}}
    <section class="wedding-intro">
        <div class="container" style="max-width: 760px;">
            @if(app()->getLocale() === 'ms')
                <p class="slide-up">Sama ada majlis romantik yang kecil atau perayaan besar-besaran, lihat pelbagai pilihan kami untuk memulakan perjalanan anda ke arah perkahwinan yang indah dan tidak terlupakan.</p>
                <p class="slide-up">Lengkapkan tetapan acara anda dengan item tambahan individu kepada pakej pilihan.</p>
                <p class="slide-up">Di Rumah Dusun, tetamu mempunyai pilihan untuk mengadakan majlis mereka sama ada di dalam atau di luar. Kami menawarkan pelbagai pakej perkahwinan dari gaya mudah namun elegan hingga tetapan moden yang mewah. Menu makanan Melayu dan Antarabangsa yang lazat juga tersedia untuk keperluan F&B anda.</p>
                <p class="slide-up">Rumah ini termasuk penggunaan bilik pengantin dengan bilik mandi dalam serta dua bilik persendirian lain untuk tetamu.</p>
            @elseif(app()->getLocale() === 'zh')
                <p class="slide-up">无论是小型浪漫仪式还是大型庆典，探索我们多样化的婚礼场地选择，开启您通往美好难忘婚礼的旅程。</p>
                <p class="slide-up">在特色套餐的基础上，以个人附加项目为您的活动设置锦上添花。</p>
                <p class="slide-up">在 Rumah Dusun，宾客可选择在室内或室外举行仪式。我们提供从简约优雅到精心现代风格的多种婚礼套餐。同时提供马来菜和国际菜式的丰盛菜单，满足您的餐饮需求。</p>
                <p class="slide-up">别墅含带附属浴室的新娘套房及另外两间供宾客使用的私人房间。</p>
            @else
                <p class="slide-up">Be it a small romantic affair or a large scale ground celebration, view our array of choice to kickstart your journey to a beautiful and memorable wedding.</p>
                <p class="slide-up">Compliment your event setting with and add-on individual items to the featured package.</p>
                <p class="slide-up">At Rumah Dusun, guests have the option of holding their ceremonies either indoors or outdoors. We offer a wide selection of wedding packages ranging from simple yet elegant styles to elaborate modern settings. An extended menu of delectable Malay and International dishes is also available to serve for your F&amp;B needs.</p>
                <p class="slide-up">The house includes the usage of bridal suite with attached bathroom as well as two other private rooms for guests.</p>
            @endif
        </div>
    </section>

    {{-- ── Venue 1: Dewan Sri Dusun ────────────────────────────────────────── --}}
    <section class="venue-section venue-dewan slide-up">
        <div class="container">
            <div class="venue-row">
                <div class="venue-text-col slide-up-child delay-1">
                    <h3 class="venue-name">{{ __('wedding.venue1_name') }}</h3>
                    <p class="venue-desc">{{ __('wedding.venue1_desc') }}</p>
                </div>
                <div class="venue-img-col slide-up-child delay-2">
                    <img
                        src="{{ asset('storage/Pelamin_DSDusun_2024-1-1024x618.jpeg') }}"
                        alt="{{ __('wedding.venue1_name') }}"
                        class="venue-img"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- ── Venue 2: Dataran Sri Dusun ──────────────────────────────────────── --}}
    <section class="venue-section venue-dataran slide-up">
        <div class="container">
            <div class="venue-row">
                <div class="venue-text-col slide-up-child delay-1">
                    <h3 class="venue-name">{{ __('wedding.venue2_name') }}</h3>
                    <p class="venue-desc">{{ __('wedding.venue2_desc') }}</p>
                </div>
                <div class="venue-img-col slide-up-child delay-2">
                    <img
                        src="{{ asset('storage/DataranSriDusun-1024x682.jpg') }}"
                        alt="{{ __('wedding.venue2_name') }}"
                        class="venue-img"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </section>

    {{-- ── Venue 3: Laman Dusun ────────────────────────────────────────────── --}}
    <section class="venue-section venue-laman slide-up">
        <div class="container">
            <div class="venue-row">
                <div class="venue-text-col slide-up-child delay-1">
                    <h3 class="venue-name">{{ __('wedding.venue3_name') }}</h3>
                    <p class="venue-desc">{{ __('wedding.venue3_desc') }}</p>
                </div>
                <div class="venue-img-col slide-up-child delay-2">
                    <img
                        src="{{ asset('storage/LamanDusun-1024x768.jpg') }}"
                        alt="{{ __('wedding.venue3_name') }}"
                        class="venue-img"
                        loading="lazy"
                    >
                </div>
            </div>
        </div>
    </section>

</main>
@endsection

@push('scripts')
<script>
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
            // Already in view on load — show with slight delay
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
@endpush
