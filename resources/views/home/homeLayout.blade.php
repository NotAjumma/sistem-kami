@php
	$controller = DzHelper::controller();
	$page = $action = DzHelper::action();
	$action = $controller . '_' . $action;
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() === 'ms' ? 'ms-MY' : (app()->getLocale() === 'zh' ? 'zh-Hans' : 'en-GB') }}">

<head>
	<!-- Google Analytics (loaded only after cookie consent) -->
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	// Default: deny analytics until user consents
	gtag('consent', 'default', { analytics_storage: 'denied' });
	</script>
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-WF0W2KJX24"></script>
	<script>
	gtag('js', new Date());
	gtag('config', 'G-WF0W2KJX24');
	// If previously consented, grant immediately
	if (localStorage.getItem('sk_cookie_consent') === 'accepted') {
		gtag('consent', 'update', { analytics_storage: 'granted' });
	}
	</script>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="{{ config('app.name', 'Sistem Kami') }}">
	<meta name="robots" content="index, follow">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	@php
		// Default meta values
		$appName 			= config('app.name', 'Sistem Kami');
		$defaultDescription = "$appName is an online booking system in Malaysia for event organizers and vendors. Manage packages, schedules, and customer bookings — all in one platform.";
		$defaultKeywords 	= "$appName, Booking System Malaysia, Online Booking System Malaysia, Event Booking System Malaysia, Vendor Booking System Malaysia, Event Organizer System, Vendor Management, Event Booking, Vendor Services, Event Platform, Booking System, Event Tools, Custom Software, Business Platform, Online Booking, Event Management, Vendor Marketplace, SaaS Event Platform, Business Automation, Cloud Solutions";
		$defaultTitle 		= "$appName | Online Booking System Malaysia — Event & Vendor Booking";
		$defaultImage 		= asset('images/SISTEM-KAMI-LOGO.png');

		// If $seo exists, extract it for convenience
		$seoTitle 		= $seo['title'] ?? ($page_title ?? $defaultTitle);
		$seoDescription = $seo['description'] ?? ($page_description ?? $defaultDescription);
		$seoKeywords 	= $seo['keywords'] ?? ($page_keywords ?? $defaultKeywords);
		$seoImage 		= $seo['image'] ?? ($page_image ?? $defaultImage);
	@endphp

	<meta name="keywords" content="{{ $seoKeywords }}">
	<meta name="description" content="{{ $seoDescription }}">
	<meta property="og:title" content="{{ $seoTitle }}">
	<meta property="og:description" content="{{ $seoDescription }}">
	<meta property="og:image" content="{{ $seoImage }}">
	<meta name="format-detection" content="@yield('page_phone', $page_phone ?? 'telephone=no')">
	<meta name="twitter:title" content="{{ $seoTitle }}">
	<meta name="twitter:description" content="{{ $seoDescription }}">
	<meta name="twitter:image" content="{{ $seoImage }}">
	<meta name="twitter:card" content="summary_large_image">
	<meta property="og:type" content="website">
	<meta property="og:url" content="{{ url()->current() }}">
	<meta property="og:site_name" content="{{ config('app.name') }}">

	<title>{{ $seoTitle }}</title>
	<link rel="canonical" href="{{ $seo['canonical'] ?? url()->current() }}">
	{{-- hreflang: helps Google index all language versions --}}
	<link rel="alternate" hreflang="en" href="{{ \App\Helpers\LocaleUrl::alternate('en') }}">
	<link rel="alternate" hreflang="ms" href="{{ \App\Helpers\LocaleUrl::alternate('ms') }}">
	<link rel="alternate" hreflang="zh-Hans" href="{{ \App\Helpers\LocaleUrl::alternate('zh') }}">
	<link rel="alternate" hreflang="x-default" href="{{ \App\Helpers\LocaleUrl::alternate('en') }}">

	<!-- Geo targeting -->
	<meta name="geo.region" content="MY">
	<meta name="geo.placename" content="Malaysia">
	<meta name="language" content="en-MY">

	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Preconnect to external domains (must be before any resource loads) -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">
	<link rel="dns-prefetch" href="https://cdn.jsdelivr.net">


	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}">
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

	<!-- Critical inline CSS for above-the-fold content (prevents FOUC & CLS) -->
	<style>
		/* Override style.css opacity:0 — show content immediately */
		#main-wrapper.show{opacity:1!important}
		/* Prevent CLS: restrict transitions to opacity only, not all layout properties */
		#main-wrapper{transition:opacity 0.25s ease-in !important}
		.content-body{transition:none !important}
		.content-body.default-height{min-height:100vh}
		/* Header skeleton */
		.header-bg{background:#fff;min-height:64px}
		/* Search section skeleton */
		.search-section{background:#001f4d;min-height:120px}
		/* Card images prevent CLS */
		.package-img,.carousel-item img{width:100%;height:260px;object-fit:cover}
	</style>

	<!-- Hide page loader only after ALL layout-critical CSS files have loaded -->
	<script>
		var _cssReady=0;
		function _hideLoader(){var l=document.getElementById('page-loader');if(l&&!l._done){l._done=true;l.style.opacity='0';setTimeout(function(){if(l.parentNode)l.parentNode.removeChild(l);},350);}}
		function _onCssReady(){if(++_cssReady>=4)_hideLoader();}
		// Hard fallback: hide after 6s no matter what
		setTimeout(_hideLoader,6000);
	</script>

	<!-- Main CSS (counts toward loader) -->
	<link rel="preload" as="style" href="{{ asset('css/style-public.min.css') }}"
		onload="this.onload=null;this.rel='stylesheet';_onCssReady();var s=document.createElement('style');s.textContent='@font-face{font-family:&quot;Font Awesome 5 Free&quot;;src:url(&quot;/webfonts/fa-solid-900.woff2&quot;) format(&quot;woff2&quot;);font-weight:900;font-display:swap}@font-face{font-family:&quot;Font Awesome 5 Free&quot;;src:url(&quot;/webfonts/fa-regular-400.woff2&quot;) format(&quot;woff2&quot;);font-weight:400;font-display:swap}@font-face{font-family:&quot;Font Awesome 5 Brands&quot;;src:url(&quot;/webfonts/fa-brands-400.woff2&quot;) format(&quot;woff2&quot;);font-weight:400;font-display:swap}';document.head.appendChild(s)">
	<noscript><link class="main-css" href="{{ asset('css/style-public.min.css') }}" rel="stylesheet"></noscript>

	<!-- Tailwind CSS (counts toward loader) -->
	<link rel="preload" as="style" href="{{ asset('css/tailwind.min.css') }}"
		onload="this.onload=null;this.rel='stylesheet';_onCssReady()">
	<noscript><link rel="stylesheet" href="{{ asset('css/tailwind.min.css') }}"></noscript>

	<!-- Bootstrap Icons (counts toward loader — affects icon layout) -->
	<link rel="preload" as="style"
		href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
		onload="this.onload=null;this.rel='stylesheet';_onCssReady()">
	<noscript><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"></noscript>

	<!-- Google Fonts: async-load (font-display:swap, does not block layout) -->
	<link rel="preload" as="style"
		href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;600;700&display=swap"
		onload="this.onload=null;this.rel='stylesheet'">
	<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@400;600;700&display=swap"></noscript>

	<!-- Font Awesome (counts toward loader — affects icon layout) -->
	<link rel="preload" as="style"
		href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
		onload="this.onload=null;this.rel='stylesheet';_onCssReady()">
	<noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></noscript>

	<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.9/dist/cdn.min.js" defer></script>
	<style>
		.content-body {
			margin-left: 0 !important;
		}

		h1,h2,h3,h6 {
            font-family: 'Playfair Display', serif !important;
        }

		.bg-gray-900 {
			background-color: rgba(0, 31, 77, 1) !important;
		}

		.dashed-hr {
			border: none;
			border-top: 1px dashed #999;
			/* or your preferred color */
		}

		.text-pink {
			color: #c2185b;
		}

		.social-links a i {
			font-size: 2rem;
			transition: color 0.2s;
		}

		.social-links a:hover i {
			color: var(--bs-primary);
		}

		/* Always apply brand colors */
		.bi-facebook {
			color: #1877F2;
		}

		.bi-instagram {
			color: #E1306C;
		}

		.bi-tiktok {
			color: #000000;
		}

		.bi-twitter {
			color: #1DA1F2;
		}

		.bi-youtube {
			color: #FF0000;
		}

		.bi-linkedin {
			color: #0A66C2;
		}

		.form-select {
			background-color: #fff !important;
		}

		@media (max-width: 600px) {
			.btn-primary {
				padding: 1rem 1rem !important;
			}
		}

		.bg-primary-dark {
			background-color: rgba(0, 31, 77, 1);
		}

		@media (max-width: 991.98px) { /* tablets and below */
            .icon-info {
                display: none !important;
            }
        }
	</style>
	@stack('styles')
	@stack('json_ld')
</head>

<body class="bg-white text-gray-900">

	<!-- Page loader: shown while layout CSS loads async, fades out when ready -->
	<div id="page-loader" style="position:fixed;inset:0;background:#fff;z-index:99999;display:flex;align-items:center;justify-content:center;transition:opacity 0.3s ease">
		<div style="width:44px;height:44px;border:3px solid #e5e7eb;border-top-color:#001f4d;border-radius:50%;animation:sk 0.8s linear infinite"></div>
	</div>
	<style>@keyframes sk{to{transform:rotate(360deg)}}</style>

	<!--**********************************
        Main wrapper start
    ***********************************-->
	<div id="main-wrapper" class="show bg-base {{ in_array($page, array('dashboard', 'dashboard_2')) ? 'wallet-open active' : '' }}">
		
		<header class="header-bg w-full border-b border-gray-200">
			<nav class="container mx-auto flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8" style="position:relative;">
				<!-- Logo -->
				<a href="/">
					<img
						src="{{ asset('images/SISTEM-KAMI-LOGO.png') }}"
						alt="Sistem Kami Logo"
						class="h-10 w-auto"
						style="height:40px;width:auto"
						fetchpriority="high"
					>
				</a>

				<!-- Mobile right side: language dropdown + hamburger -->
				<div class="lg:hidden flex items-center" style="gap:6px;">
					{{-- Mobile language dropdown --}}
					<div style="position:relative;" id="mobile-lang-wrapper">
						<button id="mobile-lang-btn" type="button"
							style="background:none;border:1px solid #d1d5db;padding:5px 9px;cursor:pointer;display:flex;align-items:center;gap:4px;border-radius:8px;font-size:0.8rem;font-weight:600;color:#1a2942;"
							aria-label="Select language">
							@if(app()->getLocale() === 'ms') BM
							@elseif(app()->getLocale() === 'zh') 中文
							@else EN
							@endif
							<svg style="width:12px;height:12px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.657a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
						</button>
						<div id="mobile-lang-menu" style="display:none;position:absolute;right:0;top:calc(100% + 6px);background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);min-width:100px;z-index:200;">
							<a href="{{ \App\Helpers\LocaleUrl::alternate('en') }}" style="display:flex;align-items:center;gap:6px;padding:9px 14px;font-size:0.82rem;font-weight:600;text-decoration:none;border-radius:8px 8px 0 0;{{ app()->getLocale()==='en' ? 'color:#001f4d;background:#f0f4ff;' : 'color:#6b7280;background:#fff;' }}">EN <span style="font-size:0.7rem;font-weight:400;color:#9ca3af;">English</span></a>
							<a href="{{ \App\Helpers\LocaleUrl::alternate('ms') }}" style="display:flex;align-items:center;gap:6px;padding:9px 14px;font-size:0.82rem;font-weight:600;text-decoration:none;{{ app()->getLocale()==='ms' ? 'color:#001f4d;background:#f0f4ff;' : 'color:#6b7280;background:#fff;' }}">BM <span style="font-size:0.7rem;font-weight:400;color:#9ca3af;">Melayu</span></a>
							<a href="{{ \App\Helpers\LocaleUrl::alternate('zh') }}" style="display:flex;align-items:center;gap:6px;padding:9px 14px;font-size:0.82rem;font-weight:600;text-decoration:none;border-radius:0 0 8px 8px;{{ app()->getLocale()==='zh' ? 'color:#001f4d;background:#f0f4ff;' : 'color:#6b7280;background:#fff;' }}">中文 <span style="font-size:0.7rem;font-weight:400;color:#9ca3af;">普通话</span></a>
						</div>
					</div>
					{{-- Hamburger --}}
					<button id="menu-toggle" aria-label="Open menu" style="background:none;border:none;padding:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='none'">
						<svg id="menu-icon-open" class="w-6 h-6" fill="none" stroke="#1a2942" stroke-width="2.2" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
						</svg>
						<svg id="menu-icon-close" class="w-6 h-6" fill="none" stroke="#1a2942" stroke-width="2.2" viewBox="0 0 24 24" style="display:none;">
							<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
				</div>

				<!-- Desktop Center Nav -->
				<ul class="hidden lg:flex space-x-6 text-sm font-normal text-gray-900" style="position:absolute;left:50%;transform:translateX(-50%);align-items:center;list-style:none;margin:0;padding:0;">
					<li><a class="hover:underline" href="{{ lroute('index') }}">{{ __('nav.home') }}</a></li>
					<li><a class="hover:underline" href="{{ lroute('about') }}">{{ __('nav.about_us') }}</a></li>

					<!-- Pages Dropdown -->
					<li class="relative">
						<button type="button" id="pages-menu-button"
							style="background:none;border:none;padding:0;cursor:pointer;display:inline-flex;align-items:center;gap:4px;font-size:inherit;color:inherit;font-weight:inherit;"
							aria-haspopup="true">
							{{ __('nav.pages') }}
							<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.657a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
							</svg>
						</button>
						<div id="pages-menu" class="absolute z-10 hidden mt-2 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" style="min-width:180px;left:0;">
							<div class="py-1 text-xs text-gray-700" role="menu">
								<a href="{{ lroute('faq') }}" class="block px-4 py-2 hover:bg-gray-100" role="menuitem">{{ __('nav.faq') }}</a>
								<a href="{{ lroute('privacy-policy') }}" class="block px-4 py-2 hover:bg-gray-100" role="menuitem">{{ __('nav.privacy_policy') }}</a>
								<a href="{{ lroute('terms') }}" class="block px-4 py-2 hover:bg-gray-100" role="menuitem">{{ __('nav.terms') }}</a>
							</div>
						</div>
					</li>
				</ul>

				<!-- Desktop Right: Contact Us + Organizer + Lang -->
				<div class="hidden lg:flex items-center" style="gap:8px;">

					<!-- Contact Us Dropdown -->
					<div style="position:relative;" id="contact-menu-wrapper">
						<button id="contact-menu-button" type="button"
							style="background:#22c55e;color:#fff;border:none;padding:7px 16px;border-radius:6px;cursor:pointer;display:inline-flex;align-items:center;gap:5px;font-size:0.85rem;font-weight:600;transition:background 0.2s;"
							onmouseover="this.style.background='#16a34a'" onmouseout="this.style.background='#22c55e'">
							{{ __('nav.contact_us') }}
							<svg style="width:12px;height:12px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.657a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
						</button>
						<div id="contact-menu" style="display:none;position:absolute;right:0;top:calc(100% + 6px);background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);min-width:180px;z-index:200;">
							<a href="https://wa.me/601123053082?text=Hi%20SistemKami,%20saya%20nak%20tanya%20tentang%20platform%20anda." target="_blank"
								style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:0.85rem;font-weight:500;color:#374151;text-decoration:none;border-radius:8px 8px 0 0;"
								onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background=''">
								<span style="width:28px;height:28px;background:#25D366;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
									<svg style="width:15px;height:15px;fill:#fff;" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
								</span>
								WhatsApp
							</a>
							<a href="mailto:salessistemkami@gmail.com"
								style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:0.85rem;font-weight:500;color:#374151;text-decoration:none;border-radius:0 0 8px 8px;"
								onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background=''">
								<span style="width:28px;height:28px;background:#3b82f6;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
									<svg style="width:14px;height:14px;fill:#fff;" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
								</span>
								Email
							</a>
						</div>
					</div>

					<!-- Organizer Dropdown -->
					<div style="position:relative;">
						<button type="button"
							class="btn btn-primary inline-flex justify-center text-xs font-normal text-gray-900"
							id="organizer-menu-button" aria-expanded="true" aria-haspopup="true">
							{{ __('nav.organizer') }}
							<svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.657a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
							</svg>
						</button>
						<div class="absolute z-10 hidden mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5" id="organizer-menu">
							<div class="py-1 text-xs text-gray-700" role="menu">
								<a href="{{ route('organizer.login') }}" class="block px-4 py-2 hover:bg-gray-100" role="menuitem">{{ __('nav.organizer_login') }}</a>
								<a href="{{ route('organizer.register') }}" class="block px-4 py-2 hover:bg-gray-100" role="menuitem">{{ __('nav.organizer_register') }}</a>
								<a href="{{ route('organizer.worker.login') }}" class="block px-4 py-2 hover:bg-gray-100" role="menuitem">{{ __('nav.worker_login') }}</a>
							</div>
						</div>
					</div>

					<!-- Language Dropdown -->
					<div style="position:relative;" id="desktop-lang-wrapper">
						<button id="desktop-lang-btn" type="button"
							style="background:none;border:1px solid #d1d5db;padding:5px 12px;cursor:pointer;display:flex;align-items:center;gap:5px;border-radius:8px;font-size:0.8rem;font-weight:600;color:#1a2942;transition:background 0.2s;"
							aria-label="Select language">
							@if(app()->getLocale() === 'ms') BM
							@elseif(app()->getLocale() === 'zh') 中文
							@else EN
							@endif
							<svg style="width:12px;height:12px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.657a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
						</button>
					<div id="desktop-lang-menu" style="display:none;position:absolute;right:0;top:calc(100% + 6px);background:#fff;border:1px solid #e5e7eb;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);min-width:110px;z-index:200;">
							<a href="{{ \App\Helpers\LocaleUrl::alternate('en') }}" style="display:flex;align-items:center;gap:6px;padding:9px 14px;font-size:0.82rem;font-weight:600;text-decoration:none;border-radius:8px 8px 0 0;{{ app()->getLocale()==='en' ? 'color:#001f4d;background:#f0f4ff;' : 'color:#6b7280;background:#fff;' }}">EN <span style="font-size:0.7rem;font-weight:400;color:#9ca3af;">English</span></a>
							<a href="{{ \App\Helpers\LocaleUrl::alternate('ms') }}" style="display:flex;align-items:center;gap:6px;padding:9px 14px;font-size:0.82rem;font-weight:600;text-decoration:none;{{ app()->getLocale()==='ms' ? 'color:#001f4d;background:#f0f4ff;' : 'color:#6b7280;background:#fff;' }}">BM <span style="font-size:0.7rem;font-weight:400;color:#9ca3af;">Melayu</span></a>
							<a href="{{ \App\Helpers\LocaleUrl::alternate('zh') }}" style="display:flex;align-items:center;gap:6px;padding:9px 14px;font-size:0.82rem;font-weight:600;text-decoration:none;border-radius:0 0 8px 8px;{{ app()->getLocale()==='zh' ? 'color:#001f4d;background:#f0f4ff;' : 'color:#6b7280;background:#fff;' }}">中文 <span style="font-size:0.7rem;font-weight:400;color:#9ca3af;">普通话</span></a>
						</div>
					</div>

				</div>{{-- end desktop right --}}

			</nav>

			<!-- Mobile Menu: clean dark slide-down panel -->
			<div id="mobile-menu" class="lg:hidden" style="display:none; background:#001f4d; border-top:1px solid rgba(255,255,255,0.1);">
				<div style="padding:8px 12px 16px;">

						<a href="{{ lroute('index') }}" style="display:flex;align-items:center;gap:12px;padding:13px 14px;color:rgba(255,255,255,0.9);text-decoration:none;font-size:0.95rem;font-weight:500;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">
						<svg style="width:18px;height:18px;opacity:0.6;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H15.75v-5.25H8.25V21.75H3.75A.75.75 0 013 21V9.75z"/></svg>
						{{ __('nav.home') }}
					</a>

					<a href="{{ lroute('about') }}" style="display:flex;align-items:center;gap:12px;padding:13px 14px;color:rgba(255,255,255,0.9);text-decoration:none;font-size:0.95rem;font-weight:500;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">
						<svg style="width:18px;height:18px;opacity:0.6;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path stroke-linecap="round" d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
						{{ __('nav.about_us') }}
					</a>

				{{-- Contact Us collapsible --}}
				<button id="mobile-contact-toggle" type="button" style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:13px 14px;background:none;border:none;color:rgba(255,255,255,0.9);font-size:0.95rem;font-weight:500;border-radius:8px;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">
					<span style="display:flex;align-items:center;gap:12px;">
						<svg style="width:18px;height:18px;opacity:0.6;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25H4.5a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
						{{ __('nav.contact_us') }}
					</span>
					<svg id="mobile-contact-chevron" style="width:16px;height:16px;transition:transform 0.25s;opacity:0.6;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
				</button>
				<div id="mobile-contact-submenu" style="display:none;padding-left:12px;">
					<a href="https://wa.me/601123053082?text=Hi%20SistemKami,%20saya%20nak%20tanya%20tentang%20platform%20anda." target="_blank" style="display:flex;align-items:center;gap:10px;padding:10px 14px;color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.88rem;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(37,211,102,0.15)'" onmouseout="this.style.background='transparent'">
						<span style="width:24px;height:24px;background:#25D366;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
							<svg style="width:13px;height:13px;fill:#fff;" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
						</span>
						WhatsApp
					</a>
					<a href="mailto:salessistemkami@gmail.com" style="display:flex;align-items:center;gap:10px;padding:10px 14px;color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.88rem;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(59,130,246,0.15)'" onmouseout="this.style.background='transparent'">
						<span style="width:24px;height:24px;background:#3b82f6;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
							<svg style="width:13px;height:13px;fill:#fff;" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
						</span>
						Email
					</a>
				</div>

				<div style="height:1px;background:rgba(255,255,255,0.1);margin:8px 14px;"></div>

					{{-- Pages collapsible --}}
					<button id="mobile-pages-toggle" type="button" style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:13px 14px;background:none;border:none;color:rgba(255,255,255,0.9);font-size:0.95rem;font-weight:500;border-radius:8px;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">
						<span>{{ __('nav.pages') }}</span>
						<svg id="mobile-pages-chevron" style="width:16px;height:16px;transition:transform 0.25s;opacity:0.6;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
					</button>
					<div id="mobile-pages-submenu" style="display:none;padding-left:12px;">
						<a href="{{ lroute('faq') }}" style="display:block;padding:10px 14px;color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.88rem;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">{{ __('nav.faq') }}</a>
						<a href="{{ lroute('privacy-policy') }}" style="display:block;padding:10px 14px;color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.88rem;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">{{ __('nav.privacy_policy') }}</a>
						<a href="{{ lroute('terms') }}" style="display:block;padding:10px 14px;color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.88rem;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">{{ __('nav.terms') }}</a>
					</div>

					<div style="height:1px;background:rgba(255,255,255,0.1);margin:8px 14px;"></div>

					{{-- Organizer collapsible --}}
					<button id="mobile-org-toggle" type="button" style="width:100%;display:flex;align-items:center;justify-content:space-between;padding:13px 14px;background:none;border:none;color:rgba(255,255,255,0.9);font-size:0.95rem;font-weight:500;border-radius:8px;cursor:pointer;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">
						<span>{{ __('nav.organizer') }}</span>
						<svg id="mobile-org-chevron" style="width:16px;height:16px;transition:transform 0.25s;opacity:0.6;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
					</button>
					<div id="mobile-org-submenu" style="display:none;padding-left:12px;">
						<a href="{{ route('organizer.login') }}" style="display:block;padding:10px 14px;color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.88rem;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">{{ __('nav.organizer_login') }}</a>
						<a href="{{ route('organizer.register') }}" style="display:block;padding:10px 14px;color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.88rem;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">{{ __('nav.organizer_register') }}</a>
						<a href="{{ route('organizer.worker.login') }}" style="display:block;padding:10px 14px;color:rgba(255,255,255,0.75);text-decoration:none;font-size:0.88rem;border-radius:8px;transition:background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.08)'" onmouseout="this.style.background='transparent'">{{ __('nav.worker_login') }}</a>
					</div>

				</div>
			</div>
		</header>

		<!--**********************************
            Nav header end
        ***********************************-->

		<!--**********************************
            Content body start
        ***********************************-->
		@php
			$body_class = 'default-height';
			if ($page == 'ui_button') {
				$body_class = 'btn-page';
			}
			if ($page == 'ui_badge') {
				$body_class = 'badge-demo';
			}

		@endphp
		<div class="content-body {{$body_class}}">
			@yield('content')
		</div>
		<!-- modal-box-strat -->


		@stack('modal')

		<!--**********************************
            Content body end
        ***********************************-->

		<!--**********************************
            Footer start
        ***********************************-->
		<footer class="mt-5" style="background: linear-gradient(135deg, #001f4d 0%, #001233 100%);">
			<!-- Top wave separator -->
			<svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" style="display:block;margin-top:-1px;">
				<path d="M0 60L48 52C96 44 192 28 288 20C384 12 480 12 576 18C672 24 768 36 864 40C960 44 1056 40 1152 34C1248 28 1344 20 1392 16L1440 12V60H0Z" fill="#001f4d"/>
			</svg>

			<div class="container py-5">
				<div class="row">
					<!-- Brand & Description -->
					<div class="col-lg-4 col-md-6 mb-4">
						<a href="{{ lroute('index') }}" class="d-inline-block mb-3">
							<img src="{{ asset('images/SISTEM-KAMI-LOGO.png') }}" alt="Sistem Kami" style="height: 36px; filter: brightness(0) invert(1);">
						</a>
						<p class="small mb-4" style="color: rgba(255,255,255,0.6); line-height: 1.7;">
							{{ __('nav.footer_tagline') }}
						</p>
						<!-- Social Links -->
						<div class="d-flex gap-2">
							<a href="https://wa.me/601123053082?text=Hi%20SistemKami,%20saya%20nak%20tanya%20tentang%20platform%20anda."
								target="_blank"
								class="d-flex align-items-center justify-content-center rounded-circle"
								style="width:36px;height:36px;background:rgba(255,255,255,0.1);color:#fff;transition:background 0.3s;"
								onmouseover="this.style.background='#25D366'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
								<i class="fab fa-whatsapp"></i>
							</a>
							<a href="https://www.instagram.com/sistemkami/" target="_blank"
								class="d-flex align-items-center justify-content-center rounded-circle"
								style="width:36px;height:36px;background:rgba(255,255,255,0.1);color:#fff;transition:background 0.3s;"
								onmouseover="this.style.background='#E1306C'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
								<i class="fab fa-instagram"></i>
							</a>
						</div>
					</div>

					<!-- Quick Links -->
					<div class="col-lg-2 col-md-6 col-6 mb-4">
						<h6 class="text-white fw-bold mb-3" style="font-size: 0.9rem;">{{ __('nav.quick_links') }}</h6>
						<ul class="list-unstyled" style="font-size: 0.85rem;">
							<li class="mb-2"><a href="{{ url('/') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ __('nav.home') }}</a></li>
							<li class="mb-2"><a href="{{ lroute('about') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ __('nav.about_us') }}</a></li>
							<li class="mb-2"><a href="{{ lroute('search') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ __('nav.search') }}</a></li>
							<li class="mb-2"><a href="{{ lroute('faq') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ __('nav.faq') }}</a></li>
							<li class="mb-2"><a href="{{ lroute('privacy-policy') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ __('nav.privacy_policy') }}</a></li>
							<li class="mb-2"><a href="{{ lroute('terms') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ __('nav.terms') }}</a></li>
						</ul>
					</div>

					<!-- For Organizers -->
					<div class="col-lg-3 col-md-6 col-6 mb-4">
						<h6 class="text-white fw-bold mb-3" style="font-size: 0.9rem;">{{ __('nav.for_organizers') }}</h6>
						<ul class="list-unstyled" style="font-size: 0.85rem;">
							<li class="mb-2"><a href="{{ route('organizer.login') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ __('nav.organizer') }} {{ __('nav.organizer_login') }}</a></li>
							<li class="mb-2"><a href="{{ route('organizer.register') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ __('nav.organizer') }} {{ __('nav.organizer_register') }}</a></li>
							<li class="mb-2"><a href="{{ route('organizer.worker.login') }}" class="text-decoration-none" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">{{ __('nav.worker_login') }}</a></li>
						</ul>
					</div>

					<!-- Contact Info -->
					<div class="col-lg-3 col-md-6 mb-4">
						<h6 class="text-white fw-bold mb-3" style="font-size: 0.9rem;">{{ __('nav.contact') }}</h6>
						<ul class="list-unstyled small" style="color: rgba(255,255,255,0.6);">
							<li class="mb-2 d-flex align-items-start gap-2">
								<i class="fas fa-map-marker-alt mt-1" style="color: rgba(255,255,255,0.4); min-width: 14px;"></i>
								<span>Ipoh, Perak, Malaysia</span>
							</li>
							<li class="mb-2 d-flex align-items-center gap-2">
								<i class="fas fa-phone" style="color: rgba(255,255,255,0.4); min-width: 14px;"></i>
								<span>011-2406-9291</span>
							</li>
							<li class="mb-2 d-flex align-items-center gap-2">
								<i class="fas fa-envelope" style="color: rgba(255,255,255,0.4); min-width: 14px;"></i>
								<a href="mailto:salessistemkami@gmail.com" class="text-decoration-none" style="color: rgba(255,255,255,0.6); transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">salessistemkami@gmail.com</a>
							</li>
						</ul>
					</div>
				</div>

				<!-- Bottom bar -->
				<div class="pt-4 mt-3" style="border-top: 1px solid rgba(255,255,255,0.1);">
					<div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
						<small style="color: rgba(255,255,255,0.4);">&copy; {{ date('Y') }} Sistem Kami. {{ __('nav.copyright') }}</small>
						<div class="d-flex gap-3 flex-wrap justify-content-center">
							<a href="{{ lroute('privacy-policy') }}" style="color:rgba(255,255,255,0.35);font-size:0.78rem;text-decoration:none;" onmouseover="this.style.color='rgba(255,255,255,0.7)'" onmouseout="this.style.color='rgba(255,255,255,0.35)'">{{ __('nav.privacy_policy') }}</a>
							<a href="{{ lroute('terms') }}" style="color:rgba(255,255,255,0.35);font-size:0.78rem;text-decoration:none;" onmouseover="this.style.color='rgba(255,255,255,0.7)'" onmouseout="this.style.color='rgba(255,255,255,0.35)'">{{ __('nav.terms') }}</a>
							<small style="color: rgba(255,255,255,0.3);">{{ __('nav.made_in_malaysia') }}</small>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<!--**********************************
            Footer end
        ***********************************-->

	</div>
	<!--**********************************
        Main wrapper end
    ***********************************-->

	<!--**********************************
        Scripts
    ***********************************-->
	<!-- Required vendors -->

	@if(!empty(config('dz.public.global.js.top')))
		@foreach(config('dz.public.global.js.top') as $script)
			<script src="{{ asset($script) }}" type="text/javascript" defer></script>
		@endforeach
	@endif
	@if(!empty(config('dz.public.pagelevel.js.' . $action)))
		@foreach(config('dz.public.pagelevel.js.' . $action) as $script)
			<script src="{{ asset($script) }}" type="text/javascript" defer></script>
		@endforeach
	@endif
	@if(!empty(config('dz.public.global.js.bottom')))
		@foreach(config('dz.public.global.js.bottom') as $script)
			<script src="{{ asset($script) }}" type="text/javascript" defer></script>
		@endforeach
	@endif

	@if(session('success'))
		<script>
			toastr.info("{{ session('success') }}", "Success", {
				timeOut: 5000,
				closeButton: true,
				progressBar: true,
				positionClass: "toast-top-right",
				newestOnTop: true,
				preventDuplicates: true,
				tapToDismiss: false,
				showDuration: "300",
				hideDuration: "1000",
				extendedTimeOut: "1000",
				showEasing: "swing",
				hideEasing: "linear",
				showMethod: "fadeIn",
				hideMethod: "fadeOut"
			});
		</script>
	@endif

	@stack('scripts')
	<script>
		// Desktop Pages dropdown
		var pb=document.getElementById('pages-menu-button'),pm=document.getElementById('pages-menu');
		if(pb&&pm){pb.addEventListener('click',function(e){e.stopPropagation();pm.classList.toggle('hidden')});document.addEventListener('click',function(e){if(!pb.contains(e.target)&&!pm.contains(e.target))pm.classList.add('hidden')})}
		// Desktop organizer dropdown
		var b=document.getElementById('organizer-menu-button'),m=document.getElementById('organizer-menu');
		if(b&&m){b.addEventListener('click',function(){m.classList.toggle('hidden')});document.addEventListener('click',function(e){if(!b.contains(e.target)&&!m.contains(e.target))m.classList.add('hidden')})}
		// Desktop language dropdown
		(function(){var dlb=document.getElementById('desktop-lang-btn'),dlm=document.getElementById('desktop-lang-menu');if(!dlb||!dlm)return;dlb.addEventListener('click',function(e){e.stopPropagation();dlm.style.display=dlm.style.display==='none'?'block':'none'});document.addEventListener('click',function(e){if(!dlb.contains(e.target)&&!dlm.contains(e.target))dlm.style.display='none'})})();
		// Desktop Contact Us dropdown
		(function(){var cb=document.getElementById('contact-menu-button'),cm=document.getElementById('contact-menu');if(!cb||!cm)return;cb.addEventListener('click',function(e){e.stopPropagation();cm.style.display=cm.style.display==='none'?'block':'none'});document.addEventListener('click',function(e){if(!cb.contains(e.target)&&!cm.contains(e.target))cm.style.display='none'})})();
		// Mobile language dropdown
		(function(){var mlb=document.getElementById('mobile-lang-btn'),mlm=document.getElementById('mobile-lang-menu');if(!mlb||!mlm)return;mlb.addEventListener('click',function(e){e.stopPropagation();mlm.style.display=mlm.style.display==='none'?'block':'none'});document.addEventListener('click',function(e){if(!mlb.contains(e.target)&&!mlm.contains(e.target))mlm.style.display='none'})})();
		// Mobile menu toggle with icon swap
		(function(){
			var t=document.getElementById('menu-toggle');
			var mm=document.getElementById('mobile-menu');
			var iOpen=document.getElementById('menu-icon-open');
			var iClose=document.getElementById('menu-icon-close');
			if(!t||!mm)return;
			var open=false;
			t.addEventListener('click',function(){
				open=!open;
				mm.style.display=open?'block':'none';
				if(iOpen)iOpen.style.display=open?'none':'block';
				if(iClose)iClose.style.display=open?'block':'none';
			});
		})();
		// Mobile Contact submenu toggle
		(function(){
			var btn=document.getElementById('mobile-contact-toggle');
			var sub=document.getElementById('mobile-contact-submenu');
			var chv=document.getElementById('mobile-contact-chevron');
			if(!btn||!sub)return;
			var open=false;
			btn.addEventListener('click',function(){
				open=!open;
				sub.style.display=open?'block':'none';
				if(chv)chv.style.transform=open?'rotate(180deg)':'rotate(0deg)';
			});
		})();
		// Mobile Pages submenu toggle
		(function(){
			var btn=document.getElementById('mobile-pages-toggle');
			var sub=document.getElementById('mobile-pages-submenu');
			var chv=document.getElementById('mobile-pages-chevron');
			if(!btn||!sub)return;
			var open=false;
			btn.addEventListener('click',function(){
				open=!open;
				sub.style.display=open?'block':'none';
				if(chv)chv.style.transform=open?'rotate(180deg)':'rotate(0deg)';
			});
		})();
		// Mobile Organizer submenu toggle
		(function(){
			var btn=document.getElementById('mobile-org-toggle');
			var sub=document.getElementById('mobile-org-submenu');
			var chv=document.getElementById('mobile-org-chevron');
			if(!btn||!sub)return;
			var open=false;
			btn.addEventListener('click',function(){
				open=!open;
				sub.style.display=open?'block':'none';
				if(chv)chv.style.transform=open?'rotate(180deg)':'rotate(0deg)';
			});
		})();
	</script>

	{{-- ── Cookie Consent Banner ─────────────────────────────────── --}}
	<div id="cookie-banner" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:9999;background:#1a2942;border-top:1px solid rgba(255,255,255,0.1);padding:14px 20px;">
		<div style="max-width:900px;margin:0 auto;display:flex;flex-wrap:wrap;align-items:center;gap:12px;justify-content:space-between;">
			<p style="margin:0;font-size:0.85rem;color:rgba(255,255,255,0.8);flex:1;min-width:220px;">
				{!! __('nav.cookie_text', ['link' => '<a href="' . lroute('privacy-policy') . '" style="color:#93c5fd;text-decoration:underline;">' . __('nav.privacy_policy') . '</a>']) !!}
			</p>
			<div style="display:flex;gap:8px;flex-shrink:0;">
				<button id="cookie-decline" style="padding:7px 16px;border-radius:6px;border:1px solid rgba(255,255,255,0.3);background:transparent;color:rgba(255,255,255,0.7);font-size:0.82rem;cursor:pointer;">
					{{ __('nav.cookie_decline') }}
				</button>
				<button id="cookie-accept" style="padding:7px 18px;border-radius:6px;border:none;background:#3b82f6;color:#fff;font-size:0.82rem;font-weight:600;cursor:pointer;">
					{{ __('nav.cookie_accept') }}
				</button>
			</div>
		</div>
	</div>
	<script>
	(function () {
		var consent = localStorage.getItem('sk_cookie_consent');
		var banner  = document.getElementById('cookie-banner');

		if (!consent) banner.style.display = 'block';

		document.getElementById('cookie-accept').addEventListener('click', function () {
			localStorage.setItem('sk_cookie_consent', 'accepted');
			banner.style.display = 'none';
			if (typeof gtag === 'function') gtag('consent', 'update', { analytics_storage: 'granted' });
		});

		document.getElementById('cookie-decline').addEventListener('click', function () {
			localStorage.setItem('sk_cookie_consent', 'declined');
			banner.style.display = 'none';
		});
	})();
	</script>
</body>
</html>