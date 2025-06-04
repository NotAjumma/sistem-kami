@php
	$controller = DzHelper::controller();
	$page = $action = DzHelper::action();
	$action = $controller . '_' . $action;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
	<!--Title-->
	<title>{{ config('dz.name') }} | @yield('title', $page_title ?? '')</title>

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


</head>

<body>

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
		@if (in_array($page, array('dashboard', 'dashboard_2')))
			<div class="header-banner" style="background-image:url(images/bg-1.png);"></div>
		@endif
		<!--**********************************
            Nav header start
        ***********************************-->
		<div class="nav-header">
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
		</div>
		<!--**********************************
            Nav header end
        ***********************************-->

		<!--**********************************
            Chat box start
        ***********************************-->
		@include('layouts.elements.header')
		<!--**********************************
            Header end ti-comment-alt
        ***********************************-->

		<!--**********************************
            Sidebar start
        ***********************************-->
		@include('layouts.elements.sidebar')
		<!--**********************************
            Sidebar end
        ***********************************-->


		<!--****
		Wallet Sidebar
		****-->
		<!-- @if (in_array($page,array('dashboard','dashboard_2')))
		<div class=" wallet-overlay">
			<div class="wallet-bar dlab-scroll" id="wallet-bar">	
				<div class="closed-icon">
					<i class="fa-solid fa-xmark"></i>
				</div>
			<div class="wallet-card">
				<div class="wallet-wrapper">
					<div class="mb-3">
						<h5 class="fs-14 font-w400 mb-0">My Portfolio</h5>
						<h4 class="fs-24 font-w600">$34,010.00</h4>
					</div>
					<div class="text-end mb-2">
						<span>
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24"/>
									<rect fill="#ffff" opacity="0.3" x="12" y="4" width="3" height="13" rx="1.5"/>
									<rect fill="#ffff" opacity="0.3" x="7" y="9" width="3" height="8" rx="1.5"/>
									<path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#ffff" fill-rule="nonzero"/>
									<rect fill="#ffff" opacity="0.3" x="17" y="11" width="3" height="6" rx="1.5"/>
								</g>
							</svg>
						</span>
						<span class="fs-14 d-block">+2.25%</span>
					</div>
				</div>
				<div class="change-btn-1">
					<a href="javascript:void(0);" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal1">
						<svg class="me-2 svg-main-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
							<rect x="0" y="0" width="24" height="24"/>
							<path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
							<rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
							<path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
						</g>
						</svg>
					Deposit</a>
					<a href="javascript:void(0);" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal2">
						<svg class="me-2 svg-main-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<rect x="0" y="0" width="24" height="24"/>
								<path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
								<rect fill="#000000" opacity="0.3" x="11" y="2" width="2" height="14" rx="1"/>
								<path d="M12.0362375,3.37797611 L7.70710678,7.70710678 C7.31658249,8.09763107 6.68341751,8.09763107 6.29289322,7.70710678 C5.90236893,7.31658249 5.90236893,6.68341751 6.29289322,6.29289322 L11.2928932,1.29289322 C11.6689749,0.916811528 12.2736364,0.900910387 12.6689647,1.25670585 L17.6689647,5.75670585 C18.0794748,6.12616487 18.1127532,6.75845471 17.7432941,7.16896473 C17.3738351,7.57947475 16.7415453,7.61275317 16.3310353,7.24329415 L12.0362375,3.37797611 Z" fill="#000000" fill-rule="nonzero"/>
							</g>
						</svg>
					Withdraw</a>
				</div>
			</div>
			<div class="order-history">
				<div class="card price-list-1 mb-0">
					<div class="card-header border-0 pb-2 px-3">
						<div>
							<h4 class="text-primary card-title mb-2">Buy Order</h4>
						</div>
						<div class="dropdown custom-dropdown">
							<div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="12" cy="5" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="19" r="2"></circle></g></svg>
							</div>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="#">Option 1</a>
								<a class="dropdown-item" href="#">Option 2</a>
								<a class="dropdown-item" href="#">Option 3</a>
							</div>
						</div>
					</div>
					<div class="card-body p-3 py-0">
						<select class="form-control custom-image-select-2 image-select mt-3 mt-sm-0 style-1">
							<option data-thumbnail="images/svg/dash.svg" data-content="<img src='images/svg/dash.svg'/> Dash Coin">Dash Coin</option>
							<option data-thumbnail="images/svg/btc.svg" data-content="<img src='images/svg/btc.svg'/> Ripple">Ripple</option>
							<option data-thumbnail="images/svg/eth.svg" data-content="<img src='images/svg/eth.svg'/> Ethereum">Ethereum</option>
							<option data-thumbnail="images/svg/btc.svg" data-content="<img src='images/svg/btc.svg'/> Bitcoin">Bitcoin</option>
						</select>
						<div class="table-responsive">
							<table class="table text-center bg-primary-hover tr-rounded order-tbl mt-2 ">
								<thead>
									<tr>
										<th class="text-start">Price</th>
										<th class="text-center">Amount</th>
										<th class="text-end">Total</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-start">82.3</td>
										<td>0.15</td>
										<td class="text-end">$134,12</td>
									</tr>
									<tr>
										<td class="text-start">83.9</td>
										<td>0.18</td>
										<td class="text-end">$237,31</td>
									</tr>
									<tr>
										<td class="text-start">84.2</td>
										<td>0.25</td>
										<td class="text-end">$252,58</td>
									</tr>
									<tr>
										<td class="text-start">86.2</td>
										<td>0.35</td>
										<td class="text-end">$126,26</td>
									</tr>
									<tr>
										<td class="text-start">91.6</td>
										<td>0.75</td>
										<td class="text-end">$46,92</td>
									</tr>
									<tr>
										<td class="text-start">92.6</td>
										<td>0.21</td>
										<td class="text-end">$123,27</td>
									</tr>
									<tr>
										<td class="text-start">93.9</td>
										<td>0.55</td>
										<td class="text-end">$212,56</td>
									</tr>
									<tr>
										<td class="text-start">94.2</td>
										<td>0.18</td>
										<td class="text-end">$129,26</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="card-footer text-center py-2">
						<a href="{{ url('coin-details') }}" class="btn-link text-primary">Show more <i class="fa fa-caret-right ms-2"></i></a>
					</div>
				</div>
				<div class="card price-list style-2 border-top border-style">
					<div class="card-header border-0 pb-2 px-3">
						<div>
							<h4 class="text-pink mb-2 card-title">Sell Order</h4>
						</div>
						<div class="dropdown custom-dropdown">
							<div class="btn sharp btn-pink tp-btn" data-bs-toggle="dropdown">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="12" cy="5" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="19" r="2"></circle></g></svg>
							</div>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="#">Option 1</a>
								<a class="dropdown-item" href="#">Option 2</a>
								<a class="dropdown-item" href="#">Option 3</a>
							</div>
						</div>
					</div>
					<div class="card-body p-3 py-0">
						<select class="form-control custom-image-select-2 style-1 image-select mt-3 mt-sm-0 pink-light style-1">
							<option data-thumbnail="images/svg/dash-pink.svg" data-content="<img src='images/svg/dash.svg'/> Dash Coin">Dash Coin</option>
							<option data-thumbnail="images/svg/btc.svg" data-content="<img src='images/svg/btc.svg'/> Ripple">Ripple</option>
							<option data-thumbnail="images/svg/eth.svg" data-content="<img src='images/svg/eth.svg'/> Ethereum">Ethereum</option>
							<option data-thumbnail="images/svg/btc.svg" data-content="<img src='images/svg/btc.svg'/> Bitcoin">Bitcoin</option>
						</select>
						<div class="table-responsive">
							<table class="table text-center bg-pink-hover tr-rounded order-tbl mt-2">
								<thead>
									<tr>
										<th class="text-start">Price</th>
										<th class="text-center">Amount</th>
										<th class="text-end">Total</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-start">82.3</td>
										<td>0.15</td>
										<td class="text-end">$134,12</td>
									</tr>
									<tr>
										<td class="text-start">83.9</td>
										<td>0.18</td>
										<td class="text-end">$237,31</td>
									</tr>
									<tr>
										<td class="text-start">84.2</td>
										<td>0.25</td>
										<td class="text-end">$252,58</td>
									</tr>
									<tr>
										<td class="text-start">86.2</td>
										<td>0.35</td>
										<td class="text-end">$126,26</td>
									</tr>
									<tr>
										<td class="text-start">91.6</td>
										<td>0.75</td>
										<td class="text-end">$46,92</td>
									</tr>
									<tr>
										<td class="text-start">92.6</td>
										<td>0.21</td>
										<td class="text-end">$123,27</td>
									</tr>
									<tr>
										<td class="text-start">93.9</td>
										<td>0.55</td>
										<td class="text-end">$212,56</td>
									</tr>
									<tr>
										<td class="text-start">94.2</td>
										<td>0.18</td>
										<td class="text-end">$129,26</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="card-footer text-center py-2">
						<a href="{{ url('coin-details') }}" class="btn-link text-pink">Show more <i class="fa fa-caret-right ms-2"></i></a>
					</div>
				</div>
			</div>
		</div>
		</div>
		
		<div class="wallet-bar-close"></div>
        @endif -->
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
		<div class="footer style-1">
			<div class="copyright">
				<p>Copyright © Designed &amp; Developed by <a href="https://dexignlab.com/"
						target="_blank">DexignLab</a> <span class="current-year">2024</span></p>
			</div>
		</div>
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

	@stack('scripts')
</body>

</html>