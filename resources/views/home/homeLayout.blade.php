@php
	$controller = DzHelper::controller();
	$page = $action = DzHelper::action();
	$action = $controller . '_' . $action;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-WF0W2KJX24"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-WF0W2KJX24');
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
		$defaultDescription = "$appName provides a platform for event organizers to host and manage events, and for vendors to list and manage their services and bookings — all in one place.";
		$defaultKeywords 	= "$appName, Event Organizer System, Vendor Management, Event Booking, Vendor Services, Event Platform, Booking System, Event Tools, Custom Software, Business Platform, Online Booking, Event Management, Vendor Marketplace, SaaS Event Platform, Business Automation, Cloud Solutions";
		$defaultTitle 		= "$appName | Event Organizer & Vendor Booking Platform";
		$defaultImage 		= asset('images/logo-blue-full.png');

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

	<title>{{ $seoTitle }}</title>

	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
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
	<link class="main-css" href="{{ asset('css/style.css') }}" rel="stylesheet">
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0">
	<link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display&family=Poppins&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

	<script src="https://cdn.tailwindcss.com"></script>

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
	<style>
		.content-body {
			margin-left: 0 !important;
		}

		h2,
		h6 {
			font-family: "Press Start 2P", system-ui !important;
			font-weight: 400;
			font-style: normal;
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
	</style>
	@stack('styles')
</head>

<body class="bg-white text-gray-900">

	<!--*******************
        Preloader start
    ********************-->
	<div id="preloader">
		<div class="lds-ripple">
			<div></div>
			<div></div>
		</div>
	</div>
	<!--*******************
        Preloader end
    ********************-->

	<!--**********************************
        Main wrapper start
    ***********************************-->
	<div id="main-wrapper" class="bg-base {{ in_array($page, array('dashboard', 'dashboard_2')) ? 'wallet-open active' : '' }}">
		
		<header class="header-bg w-full border-b border-gray-200">
			<nav class="container mx-auto flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
				<!-- Logo -->
				<a href="/">
					<img 
						src="{{ asset('images/SISTEM-KAMI-LOGO.png') }}" 
						alt="Sistem Kami Logo"
						class="h-10 w-auto"
					>
				</a>

				<!-- Hamburger button (Mobile only) -->
				<div class="lg:hidden">
					<button id="menu-toggle" class="text-gray-800 focus:outline-none">
						<!-- Hamburger Icon -->
						<svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
						</svg>
					</button>
				</div>

				<div class="hidden lg:flex gap-3 align-items-center">
					<!-- Desktop Menu -->
					<ul class="hidden lg:flex space-x-6 text-md font-normal text-gray-900">
						<li><a class="hover:underline" href="/">Home</a></li>
						
						<!-- Contact Us WhatsApp Button -->
						<li>
							<a href="https://wa.me/601123053082?text=Hi%20SistemKami,%20saya%20nak%20tanya%20tentang%20platform%20anda." 
							target="_blank" 
							class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
								Contact Us
							</a>
						</li>
					</ul>


					<!-- Organizer Menu (Desktop) -->
					<div class="hidden lg:block relative">
						<button type="button"
							class="btn btn-primary inline-flex justify-center text-xs font-normal text-gray-900"
							id="organizer-menu-button" aria-expanded="true" aria-haspopup="true">
							Organizer
							<svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
								<path fill-rule="evenodd"
									d="M5.23 7.21a.75.75 0 011.06.02L10 11.293l3.71-4.06a.75.75 0 111.08 1.04l-4.25 4.657a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z"
									clip-rule="evenodd" />
							</svg>
						</button>

						<div class="absolute z-10 hidden mt-2 w-36 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
							id="organizer-menu">
							<div class="py-1 text-xs text-gray-700" role="menu" aria-orientation="vertical"
								aria-labelledby="organizer-menu-button">
								<a href="{{ route('organizer.login') }}" class="block px-4 py-2 hover:bg-gray-100"
									role="menuitem">Login</a>
								<a href="{{ route('organizer.register') }}" class="block px-4 py-2 hover:bg-gray-100"
									role="menuitem">Register</a>
								<a href="{{ route('organizer.worker.login') }}"
									class="block px-4 py-2 hover:bg-gray-100" role="menuitem">Worker Login</a>
							</div>
						</div>
					</div>
				</div>

			</nav>

			<!-- Mobile Menu -->
			<div id="mobile-menu" class="lg:hidden hidden px-4 pb-4">
				<button class="btn btn-primary" style="width: 90%; text-align: start; padding: 0.65rem 1rem;">
					<a class="dropdown-item" href="/">Home</a>
				</button>

				<button class="btn btn-primary mt-3" style="width: 90%; text-align: start; padding: 0.65rem 1rem;">
					<a class="dropdown-item" href="https://wa.me/601123053082?text=Hi%20SistemKami,%20saya%20nak%20tanya%20tentang%20platform%20anda.">
						Contact Us</a>
				</button>

				<div class="dropdown mt-3">
					<button class="btn btn-primary dropdown-toggle" type="button" id="menuDropdown"
						data-bs-toggle="dropdown" aria-expanded="false" style="width: 90%; text-align: start; padding: 0.65rem 1rem;">
						Organizer
					</button>
					<ul class="dropdown-menu" aria-labelledby="menuDropdown">
						<li><a class="dropdown-item" href="{{ route('organizer.login') }}">Login</a></li>
						<li><a class="dropdown-item" href="{{ route('organizer.register') }}">Register</a>
						</li>
						<li><a class="dropdown-item" href="{{ route('organizer.worker.login') }}">Worker Login</a></li>
					</ul>
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
		<footer
			class="container bg-gray-900 text-gray-400 text-xs py-6 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center mx-auto mt-5">
			<div class="container">
				<div class="row">
					<!-- About Section -->
					<div class="col-md-4 mb-3">
						<h6 class="text-light pb-3">About Us</h6>
						<p class="small">
							SistemKami.com is a booking and business management platform 
							designed to help studios and service providers manage packages, 
							schedules, and customers more efficiently. 
							<br><br>
							We simplify your operations so you can focus on growing your business.
						</p>
					</div>

					<!-- Quick Links -->
					<div class="col-md-4 mb-3">
						<h6 class="text-light pb-3">Quick Links</h6>
						<ul class="list-unstyled">
							<li><a href="{{ url('/') }}" class="text-light text-decoration-none">Home</a></li>
							<li><a href="{{ route('organizer.login') }}"
									class="text-light text-decoration-none">Organizer Login</a></li>
							<li><a href="{{ route('organizer.register') }}"
									class="text-light text-decoration-none">Organizer Register</a></li>
						</ul>
					</div>

					<!-- Contact Info -->
					<div class="col-md-4 mb-3">
						<h6 class="text-light pb-3">Contact Us</h6>
						<address class="small">
							Ipoh<br>
							Perak, Malaysia<br>
							Phone: 011-2406-9291<br>
							Email: <a href="mailto:salessistemkami@gmail.com" class="text-light">salessistemkami@gmail.com</a>
						</address>
					</div>
				</div>

				<hr class="bg-secondary" />

				<div class="d-flex justify-content-between align-items-center pt-3">
					<small>&copy; {{ date('Y') }} Sistem Kami. All rights reserved.</small>
					<div>
						<!-- <a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a> -->
						<a href="https://wa.me/601123053082?text=Hi%20SistemKami,%20saya%20nak%20tanya%20tentang%20platform%20anda." 
						target="_blank" 
						class="text-light me-3">
							<i class="fab fa-whatsapp"></i>
						</a>

						<a href="https://www.instagram.com/sistemkami/" target="_blank" class="text-light me-3"><i class="fab fa-instagram"></i></a>
						<!-- <a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a> -->
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
			<script src="{{ asset($script) }}" type="text/javascript"></script>
		@endforeach
	@endif
	@if(!empty(config('dz.public.pagelevel.js.' . $action)))
		@foreach(config('dz.public.pagelevel.js.' . $action) as $script)
			<script src="{{ asset($script) }}" type="text/javascript"></script>
		@endforeach
	@endif
	@if(!empty(config('dz.public.global.js.bottom')))
		@foreach(config('dz.public.global.js.bottom') as $script)
			<script src="{{ asset($script) }}" type="text/javascript"></script>
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
		const button = document.getElementById('organizer-menu-button');
		const menu = document.getElementById('organizer-menu');

		button.addEventListener('click', () => {
			menu.classList.toggle('hidden');
		});

		// Optional: close when clicking outside
		document.addEventListener('click', (e) => {
			if (!button.contains(e.target) && !menu.contains(e.target)) {
				menu.classList.add('hidden');
			}
		});
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', function () {
			const toggleButton = document.getElementById('menu-toggle');
			const mobileMenu = document.getElementById('mobile-menu');

			toggleButton.addEventListener('click', function () {
				mobileMenu.classList.toggle('hidden');
			});
		});
	</script>
</body>
</html>