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
	<meta name="author" content="Dexignlabs">
	<meta name="robots" content="index, follow">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="keywords"
		content="Admin Dashboard, Bootstrap Template, FrontEnd, Web Application, Responsive Design, User Experience, Customizable, Modern UI, Dashboard Template, Admin Panel, Bootstrap 5, HTML5, CSS3, JavaScript, Admin Template, UI Kit, SASS, SCSS, Analytics, Responsive Dashboard, responsive admin dashboard, ui kit, web app, Admin Dashboard, Template, Admin, Authentication, FrontEnd Integration, Web Application UI, Bootstrap Framework, User Interface Kit, SASS Integration, Customizable Template, HTML5/CSS3, Analytics Dashboard, Admin Dashboard UI, Mobile-Friendly Design, UI Components, Dashboard Widgets, Dashboard Framework, Data Visualization, User Experience (UX), Dashboard Widgets, Real-time Analytics, Cross-Browser Compatibility, Interactive Charts, Performance Optimization, Multi-Purpose Template, Efficient Admin Tools, Modern Web Technologies, Responsive Tables, Dashboard Widgets, Invoice Management, Access Control, Modular Design, Trend Analysis, User-Friendly Interface, Crypto Trading UI, Cryptocurrency Dashboard, Trading Platform Interface, Responsive Crypto Admin, Financial Dashboard, UI Components for Crypto, Cryptocurrency Exchange, Blockchain , Crypto Portfolio Template, Crypto Market Analytics">
	<meta name="description" content="@yield('page_description', $page_description ?? '')">
	<meta property="og:title" content="Jiade : Laravel Crypto Trading UI Admin  Bootstrap 5 Template | Dexignlabs">
	<meta property="og:description" content="@yield('page_description', $page_description ?? '')">
	<meta property="og:image" content="https://jiade.dexignlab.com/laravel/social-image.png">
	<meta name="format-detection" content="telephone=no">
	<meta name="twitter:title" content="Jiade : Laravel Crypto Trading UI Admin  Bootstrap 5 Template | Dexignlabs">
	<meta name="twitter:description" content="@yield('page_description', $page_description ?? '')">
	<meta name="twitter:image" content="https://jiade.dexignlab.com/laravel/social-image.png">
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
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Bootstrap JS Bundle (includes Popper) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	@stack('styles')

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
				<img src="{{ asset('images/logo-white.png') }}" alt="">

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
		@include('layouts.admin.elements.header')
		<!--**********************************
            Header end ti-comment-alt
        ***********************************-->

		<!--**********************************
            Sidebar start
        ***********************************-->
		@include('layouts.admin.elements.sidebar')
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
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	@stack('scripts')
</body>

</html>