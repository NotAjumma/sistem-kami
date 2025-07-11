@php
	$controller = DzHelper::controller();
	$page = $action = DzHelper::action();
	$action = $controller . '_' . $action;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
	<!--Title-->
	<title>
		@if (request()->is('/'))
			{{ config('dz.name') }}
		@else
			{{ $page_title ?? '' }}
		@endif
	</title>


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
	<meta property="og:title" content="Sistem Kami | Smart, Simple, Custom Business Software">
	<meta property="og:description"
		content="@yield('page_description', $page_description ?? 'We build custom and ready-made systems to help your business work better — simple, fast, and tailored for you.')">
	<meta property="og:image" content="">
	<meta name="format-detection" content="telephone=no">
	<meta name="twitter:title" content="Sistem Kami | Smart, Simple, Custom Business Software">
	<meta name="twitter:description"
		content="@yield('page_description', $page_description ?? 'We build custom and ready-made systems to help your business work better — simple, fast, and tailored for you.')">
	<meta name="twitter:image" content="">
	<meta name="twitter:card" content="summary_large_image">

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
	<div id="main-wrapper" class="{{ in_array($page, array('dashboard', 'dashboard_2')) ? 'wallet-open active' : '' }}">
		<!--**********************************
            Nav header start
        ***********************************-->
		<!-- <div class="nav-header">
			<a href="{{ url('index') }}" class="brand-logo">
				<svg class="logo-abbr" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
					width="27.5px" height="36.5px">
					<path fill-rule="evenodd" stroke="var(--primary)" stroke-width="1px" stroke-linecap="butt"
						stroke-linejoin="miter" fill="var(--primary)"
						d="M24.253,5.072 L24.253,16.207 C24.253,17.311 24.210,17.744 24.253,18.412 L24.253,20.016 L24.253,25.458 C24.253,26.562 24.344,32.102 24.253,33.103 L22.202,33.103 C22.202,32.102 22.202,27.277 22.202,25.459 L22.202,5.076 C21.972,4.819 21.837,4.484 21.837,4.137 C21.837,3.770 21.987,3.414 22.242,3.154 C22.504,2.893 22.865,2.749 23.226,2.749 C23.593,2.749 23.954,2.893 24.210,3.154 C24.711,3.656 24.725,4.543 24.253,5.072 ZM18.243,16.511 C18.243,17.615 18.199,18.047 18.243,18.715 L18.243,20.319 L18.243,25.762 C18.243,26.866 18.334,32.405 18.243,33.407 L16.192,33.407 C16.192,32.405 16.192,27.580 16.192,25.762 L16.192,5.379 C15.962,5.123 15.826,4.787 15.826,4.441 C15.826,4.073 15.977,3.718 16.232,3.457 C16.493,3.197 16.855,3.052 17.215,3.052 C17.583,3.052 17.943,3.196 18.199,3.457 C18.701,3.959 18.714,4.846 18.243,5.375 L18.243,16.511 ZM12.735,25.098 C12.735,26.915 12.134,28.000 10.592,28.964 L7.575,30.848 C7.601,31.001 7.616,31.157 7.616,31.316 C7.616,31.883 7.156,32.342 6.590,32.342 C6.023,32.342 5.564,31.883 5.564,31.316 C5.564,31.189 5.530,31.071 5.471,30.968 C5.469,30.964 5.466,30.961 5.464,30.957 C5.458,30.948 5.453,30.938 5.448,30.929 C5.321,30.736 5.103,30.609 4.855,30.609 C4.465,30.609 4.146,30.926 4.146,31.316 C4.146,31.707 4.465,32.026 4.855,32.026 C5.422,32.026 5.881,32.485 5.881,33.052 C5.881,33.618 5.421,34.077 4.855,34.077 C3.333,34.077 2.095,32.839 2.095,31.316 C2.095,29.795 3.333,28.557 4.855,28.557 C5.470,28.557 6.040,28.760 6.499,29.102 L9.505,27.224 C10.441,26.639 10.683,26.201 10.683,25.098 L10.683,19.655 C10.653,19.674 10.623,19.694 10.592,19.713 L7.575,21.598 C7.601,21.750 7.616,21.906 7.616,22.066 C7.616,22.633 7.156,23.092 6.590,23.092 C6.023,23.092 5.564,22.633 5.564,22.066 C5.564,21.940 5.531,21.822 5.473,21.719 C5.470,21.715 5.466,21.711 5.464,21.707 C5.457,21.697 5.452,21.686 5.446,21.676 C5.319,21.485 5.102,21.358 4.855,21.358 C4.465,21.358 4.146,21.675 4.146,22.066 C4.146,22.456 4.465,22.775 4.855,22.775 C5.422,22.775 5.881,23.234 5.881,23.801 C5.881,24.368 5.421,24.827 4.855,24.827 C3.333,24.827 2.095,23.588 2.095,22.066 C2.095,20.544 3.333,19.306 4.855,19.306 C5.470,19.306 6.040,19.509 6.499,19.851 L9.505,17.973 C10.441,17.388 10.683,16.951 10.683,15.847 L10.683,4.709 C10.451,4.457 10.322,4.118 10.322,3.773 C10.322,3.407 10.466,3.051 10.728,2.790 C10.984,2.534 11.344,2.384 11.711,2.384 C12.072,2.384 12.434,2.534 12.689,2.790 C13.190,3.296 13.204,4.181 12.735,4.706 L12.735,25.098 Z" />
				</svg>

				<div class="brand-title">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="91px"
						height="29px">
						<path fill-rule="evenodd" fill="var(--primary)"
							d="M90.219,18.825 C89.722,19.295 89.031,19.595 88.143,19.726 L78.472,21.174 C78.759,22.035 79.347,22.688 80.234,23.131 C81.121,23.549 82.140,23.757 83.288,23.757 C84.358,23.757 85.363,23.627 86.303,23.366 C87.269,23.079 88.052,22.753 88.652,22.387 C89.070,22.648 89.422,23.014 89.709,23.483 C89.997,23.953 90.140,24.449 90.140,24.971 C90.140,26.145 89.592,27.019 88.496,27.594 C87.661,28.037 86.721,28.337 85.676,28.494 C84.632,28.650 83.654,28.729 82.740,28.729 C81.200,28.729 79.764,28.520 78.433,28.102 C77.128,27.659 75.979,27.006 74.987,26.145 C74.022,25.284 73.251,24.201 72.677,22.896 C72.129,21.591 71.855,20.065 71.855,18.316 C71.855,16.594 72.129,15.120 72.677,13.893 C73.251,12.641 73.995,11.623 74.909,10.840 C75.822,10.031 76.867,9.444 78.041,9.079 C79.216,8.687 80.417,8.491 81.643,8.491 C83.027,8.491 84.280,8.700 85.402,9.118 C86.551,9.535 87.530,10.109 88.339,10.840 C89.174,11.571 89.814,12.445 90.258,13.463 C90.727,14.480 90.963,15.589 90.963,16.790 C90.963,17.677 90.714,18.356 90.219,18.825 ZM83.837,14.128 C83.340,13.606 82.609,13.345 81.643,13.345 C81.017,13.345 80.469,13.450 79.999,13.658 C79.555,13.867 79.190,14.141 78.903,14.480 C78.616,14.793 78.394,15.159 78.237,15.576 C78.107,15.968 78.028,16.372 78.002,16.790 L84.698,15.694 C84.619,15.172 84.332,14.650 83.837,14.128 ZM63.870,28.142 C62.669,28.533 61.285,28.729 59.720,28.729 C58.023,28.729 56.496,28.494 55.139,28.024 C53.807,27.554 52.672,26.876 51.732,25.989 C50.818,25.101 50.114,24.045 49.618,22.818 C49.148,21.565 48.913,20.156 48.913,18.590 C48.913,16.868 49.174,15.381 49.696,14.128 C50.218,12.849 50.936,11.792 51.850,10.957 C52.789,10.122 53.873,9.509 55.099,9.118 C56.352,8.700 57.697,8.491 59.132,8.491 C59.654,8.491 60.163,8.543 60.659,8.648 C61.155,8.726 61.560,8.831 61.873,8.961 L61.873,2.620 C62.134,2.541 62.552,2.463 63.126,2.385 C63.700,2.280 64.288,2.228 64.888,2.228 C65.462,2.228 65.971,2.267 66.415,2.346 C66.885,2.424 67.276,2.581 67.589,2.815 C67.903,3.050 68.138,3.376 68.294,3.794 C68.451,4.185 68.529,4.707 68.529,5.360 L68.529,23.914 C68.529,25.141 67.955,26.119 66.806,26.850 C66.049,27.346 65.071,27.776 63.870,28.142 ZM61.912,14.167 C61.390,13.854 60.764,13.697 60.033,13.697 C58.623,13.697 57.540,14.102 56.783,14.911 C56.026,15.720 55.647,16.946 55.647,18.590 C55.647,20.208 56.000,21.435 56.705,22.270 C57.409,23.079 58.428,23.483 59.759,23.483 C60.228,23.483 60.646,23.418 61.011,23.288 C61.403,23.131 61.703,22.961 61.912,22.779 L61.912,14.167 ZM43.746,27.202 C42.050,28.220 39.661,28.729 36.581,28.729 C35.198,28.729 33.945,28.598 32.822,28.337 C31.726,28.076 30.773,27.685 29.964,27.163 C29.181,26.641 28.568,25.976 28.124,25.167 C27.706,24.358 27.497,23.418 27.497,22.348 C27.497,20.548 28.032,19.164 29.103,18.199 C30.173,17.233 31.831,16.633 34.075,16.398 L39.205,15.850 L39.205,15.576 C39.205,14.820 38.865,14.285 38.186,13.971 C37.534,13.632 36.581,13.463 35.329,13.463 C34.336,13.463 33.371,13.567 32.431,13.776 C31.491,13.985 30.643,14.245 29.886,14.559 C29.547,14.324 29.259,13.971 29.025,13.502 C28.789,13.006 28.672,12.497 28.672,11.975 C28.672,11.297 28.829,10.762 29.142,10.370 C29.481,9.953 29.990,9.600 30.669,9.313 C31.426,9.026 32.314,8.817 33.332,8.687 C34.376,8.556 35.354,8.491 36.268,8.491 C37.678,8.491 38.957,8.635 40.105,8.922 C41.280,9.209 42.272,9.653 43.081,10.253 C43.916,10.827 44.556,11.571 45.000,12.484 C45.443,13.371 45.665,14.428 45.665,15.655 L45.665,24.423 C45.665,25.101 45.469,25.662 45.078,26.106 C44.712,26.524 44.269,26.889 43.746,27.202 ZM39.244,20.234 L36.425,20.469 C35.694,20.522 35.093,20.678 34.623,20.939 C34.153,21.200 33.919,21.591 33.919,22.113 C33.919,22.635 34.115,23.066 34.506,23.405 C34.924,23.718 35.616,23.875 36.581,23.875 C37.025,23.875 37.508,23.836 38.030,23.757 C38.578,23.653 38.983,23.523 39.244,23.366 L39.244,20.234 ZM20.871,7.317 C19.775,7.317 18.888,6.978 18.209,6.299 C17.556,5.621 17.230,4.786 17.230,3.794 C17.230,2.802 17.556,1.967 18.209,1.289 C18.888,0.610 19.775,0.271 20.871,0.271 C21.968,0.271 22.842,0.610 23.495,1.289 C24.174,1.967 24.513,2.802 24.513,3.794 C24.513,4.786 24.174,5.621 23.495,6.299 C22.842,6.978 21.968,7.317 20.871,7.317 ZM5.049,28.729 C3.691,28.729 2.595,28.520 1.760,28.102 C1.159,27.815 0.716,27.424 0.429,26.928 C0.167,26.432 0.037,25.871 0.037,25.245 C0.037,24.723 0.102,24.266 0.233,23.875 C0.389,23.483 0.572,23.170 0.781,22.935 C1.277,23.040 1.694,23.118 2.034,23.170 C2.399,23.196 2.791,23.209 3.208,23.209 C4.226,23.209 4.957,22.961 5.401,22.466 C5.845,21.944 6.067,21.187 6.067,20.195 L6.067,4.381 C6.354,4.329 6.811,4.264 7.437,4.185 C8.064,4.081 8.664,4.029 9.238,4.029 C9.839,4.029 10.361,4.081 10.804,4.185 C11.274,4.264 11.666,4.420 11.979,4.655 C12.292,4.890 12.527,5.216 12.684,5.634 C12.841,6.051 12.919,6.599 12.919,7.278 L12.919,21.761 C12.919,24.084 12.253,25.832 10.922,27.006 C9.590,28.155 7.633,28.729 5.049,28.729 ZM20.519,9.000 C21.093,9.000 21.602,9.039 22.046,9.118 C22.516,9.196 22.908,9.353 23.221,9.587 C23.534,9.822 23.769,10.148 23.926,10.566 C24.108,10.957 24.200,11.479 24.200,12.132 L24.200,28.063 C23.913,28.115 23.482,28.181 22.908,28.259 C22.359,28.363 21.798,28.416 21.224,28.416 C20.649,28.416 20.127,28.376 19.658,28.298 C19.214,28.220 18.835,28.063 18.522,27.828 C18.209,27.594 17.961,27.280 17.778,26.889 C17.622,26.471 17.543,25.936 17.543,25.284 L17.543,9.353 C17.830,9.300 18.248,9.235 18.796,9.157 C19.370,9.052 19.945,9.000 20.519,9.000 Z" />
					</svg>
				</div>

			</a>
			<div class="nav-control">
				<div class="hamburger">
					<span class="line"></span><span class="line"></span><span class="line"></span>
				</div>
			</div>
		</div> -->
		<header class="w-full border-b border-gray-200">
			<nav class="container mx-auto flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
				<!-- Logo -->
				<div class="font-bold text-xl select-none">
					Sistem Kami
				</div>

				<!-- Hamburger button (Mobile only) -->
				<div class="lg:hidden">
					<button id="menu-toggle" class="text-gray-800 focus:outline-none">
						<!-- Hamburger Icon -->
						<svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
						</svg>
					</button>
				</div>

				<div class="hidden lg:flex gap-5 align-items-center">
					<!-- Desktop Menu -->
					<ul class="hidden lg:flex space-x-6 text-md font-normal text-gray-900">
						<li><a class="hover:underline" href="/">Home</a></li>
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
								<a href="{{ route('organizer.worker.login') }}" class="block px-4 py-2 hover:bg-gray-100"
									role="menuitem">Worker Login</a>
							</div>
						</div>
					</div>
				</div>

			</nav>

			<!-- Mobile Menu -->
			<div id="mobile-menu" class="lg:hidden hidden px-4 pb-4">
				<ul class="space-y-2 text-sm text-gray-700">
					<li><a href="/" class="block py-2">Home</a></li>
					<li><a href="{{ route('organizer.login') }}" class="block py-2">Organizer Login</a></li>
					<li><a href="{{ route('organizer.register') }}" class="block py-2">Organizer Register</a></li>
				</ul>
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
							Sistem Kami makes systems for your business.
							We build ready-made or custom software to help you work better.

							Simple. Fast. For you.
						</p>
					</div>

					<!-- Quick Links -->
					<div class="col-md-4 mb-3">
						<h6 class="text-light pb-3">Quick Links</h6>
						<ul class="list-unstyled">
							<li><a href="{{ url('/') }}" class="text-light text-decoration-none">Home</a></li>
							<!-- <li><a href="{{ url('/about') }}" class="text-light text-decoration-none">About</a></li> -->
							<!-- <li><a href="{{ url('/events') }}" class="text-light text-decoration-none">Events</a></li> -->
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
							Email: <a href="mailto:admin@sistemkami.com" class="text-light">admin@sistemkami.com</a>
						</address>
					</div>
				</div>

				<hr class="bg-secondary" />

				<div class="d-flex justify-content-between align-items-center pt-3">
					<small>&copy; {{ date('Y') }} Sistem Kami. All rights reserved.</small>
					<div>
						<a href="#" class="text-light me-3"><i class="fab fa-facebook-f"></i></a>
						<a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
						<a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
						<a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a>
					</div>
				</div>
			</div>
		</footer>
		<!--**********************************
            Footer end
        ***********************************-->

		<!--**********************************
           Support ticket button start
        ***********************************-->

		<!--**********************************
           Support ticket button end
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