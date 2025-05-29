@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <!-- row -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 ">
            <div class="card card-box bg-primary">
                <div class="card-header border-0 pb-0">
                    <div class="chart-num">
                        <p>
                            <i class="fa-solid fa-sort-down me-2"></i>
                            4%(30 days)
                        </p>
                        <h2 class="font-w600 mb-0">$65,123</h2>
                    </div>
                    <svg width="50" height="45" viewBox="0 0 137 137" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M68.5 0C30.6686 0 0 30.6686 0 68.5C0 106.331 30.6686 137 68.5 137C106.331 137 137 106.331 137 68.5C136.958 30.6865 106.313 0.0418093 68.5 0ZM40.213 63.6068H59.7843C62.4869 63.6068 64.6774 65.7973 64.6774 68.5C64.6774 71.2027 62.4869 73.3932 59.7843 73.3932H40.213C37.5104 73.3932 35.3199 71.2027 35.3199 68.5C35.3199 65.7973 37.5119 63.6068 40.213 63.6068ZM101.393 56.6456L95.5088 86.0883C94.1231 92.9226 88.122 97.8411 81.1488 97.8576H40.213C37.5104 97.8576 35.3199 95.6671 35.3199 92.9644C35.3199 90.2617 37.5119 88.0712 40.213 88.0712H81.1488C83.4617 88.0652 85.4522 86.4347 85.9121 84.168L91.7982 54.7253C92.3208 52.0973 90.6156 49.544 87.9891 49.0214C87.677 48.9601 87.3605 48.9288 87.0439 48.9288H49.9994C47.2967 48.9288 45.1062 46.7383 45.1062 44.0356C45.1062 41.3329 47.2967 39.1424 49.9994 39.1424H87.0439C95.128 39.1454 101.679 45.699 101.677 53.7831C101.677 54.7433 101.582 55.7019 101.393 56.6456Z"
                            fill="#FFF" />
                    </svg>
                </div>
                <div class="card-body p-0">
                    <div id="widgetChart1" class="chart-primary"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 ">
            <div class="card card-box bg-dark">
                <div class="card-header border-0 pb-0">
                    <div class="chart-num">
                        <p>
                            <i class="fa-solid fa-sort-down me-2"></i>
                            4%(30 days)
                        </p>
                        <h2 class="font-w600 mb-0">$65,123</h2>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="50px"
                        height="50px">
                        <path fill-rule="evenodd" fill="rgb(255, 255, 255)"
                            d="M21.000,42.000 C9.402,42.000 -0.001,32.598 -0.001,21.000 C-0.001,9.402 9.402,-0.000 21.000,-0.000 C32.592,0.013 41.987,9.408 42.000,21.000 C42.000,32.598 32.598,42.000 21.000,42.000 ZM28.171,14.437 C28.383,14.172 28.499,13.843 28.499,13.504 C28.500,12.675 27.829,12.001 26.999,12.000 L22.499,12.000 L22.499,7.500 C22.499,6.672 21.828,6.000 21.000,6.000 C20.171,6.000 19.500,6.671 19.500,7.500 L19.500,12.000 L15.000,12.000 C14.171,12.000 13.499,12.671 13.499,13.500 C13.499,14.328 14.171,15.000 15.000,15.000 L23.878,15.000 L13.827,27.562 C13.615,27.829 13.499,28.160 13.499,28.501 C13.499,29.329 14.171,30.000 15.000,30.000 L19.500,30.000 L19.500,34.500 C19.500,35.328 20.171,36.000 21.000,36.000 C21.828,36.000 22.499,35.328 22.499,34.500 L22.499,30.000 L26.999,30.000 C27.828,30.000 28.500,29.328 28.500,28.500 C28.500,27.672 27.828,27.000 26.999,27.000 L18.121,27.000 L28.171,14.437 Z" />
                    </svg>
                </div>
                <div class="card-body p-0">
                    <div id="widgetChart2" class="chart-primary"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 ">
            <div class="card card-box bg-warning">
                <div class="card-header border-0 pb-0">
                    <div class="chart-num">
                        <p>
                            <i class="fa-solid fa-sort-down me-2"></i>
                            4%(30 days)
                        </p>
                        <h2 class="font-w600 mb-0">$65,123</h2>
                    </div>
                    <svg width="50" height="50" viewBox="0 0 137 137" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M92.9644 53.8221C92.9599 48.4197 88.5804 44.0417 83.1795 44.0357H53.822V63.6069H83.1795C88.5804 63.6024 92.9599 59.2229 92.9644 53.8221Z"
                            fill="#FFF" />
                        <path
                            d="M53.822 92.9645H83.1795C88.5834 92.9645 92.9644 88.5835 92.9644 83.1796C92.9644 77.7743 88.5834 73.3933 83.1795 73.3933H53.822V92.9645Z"
                            fill="#FFF" />
                        <path
                            d="M68.5001 9.15527e-05C30.6687 9.15527e-05 0.00012207 30.6687 0.00012207 68.5001C0.00012207 106.332 30.6687 137 68.5001 137C106.332 137 137 106.332 137 68.5001C136.957 30.6866 106.314 0.0433939 68.5001 9.15527e-05V9.15527e-05ZM102.751 83.1781C102.737 93.9828 93.9829 102.737 83.1797 102.749V107.643C83.1797 110.345 80.9877 112.536 78.2865 112.536C75.5838 112.536 73.3933 110.345 73.3933 107.643V102.749H63.6084V107.643C63.6084 110.345 61.4164 112.536 58.7153 112.536C56.0126 112.536 53.8221 110.345 53.8221 107.643V102.749H39.144C36.4414 102.749 34.2509 100.559 34.2509 97.8577C34.2509 95.155 36.4414 92.9645 39.144 92.9645H44.0357V44.0357H39.144C36.4414 44.0357 34.2509 41.8452 34.2509 39.1425C34.2509 36.4399 36.4414 34.2493 39.144 34.2493H53.8221V29.3577C53.8221 26.655 56.0126 24.4645 58.7153 24.4645C61.4179 24.4645 63.6084 26.655 63.6084 29.3577V34.2493H73.3933V29.3577C73.3933 26.655 75.5838 24.4645 78.2865 24.4645C80.9891 24.4645 83.1797 26.655 83.1797 29.3577V34.2493C93.9426 34.2045 102.705 42.8919 102.751 53.6548C102.775 59.3543 100.304 64.7791 95.9867 68.5001C100.263 72.1793 102.731 77.5354 102.751 83.1781V83.1781Z"
                            fill="#FFF" />
                    </svg>
                </div>
                <div class="card-body p-0">
                    <div id="widgetChart3" class="chart-primary"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6 ">
            <div class="card card-box bg-pink">
                <div class="card-header border-0 pb-0">
                    <div class="chart-num">
                        <p>
                            <i class="fa-solid fa-sort-down me-2"></i>
                            4%(30 days)
                        </p>
                        <h2 class="font-w600 mb-0">$65,123</h2>
                    </div>
                    <svg width="50" height="50" viewBox="0 0 137 137" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M70.3615 78.5206C69.1671 78.9977 67.8366 78.9977 66.6421 78.5206L53.8232 73.3927L68.5018 102.75L83.1804 73.3927L70.3615 78.5206Z"
                            fill="#FFF" />
                        <path d="M68.4982 68.5L88.0696 61.6503L68.4982 34.25L48.9268 61.6503L68.4982 68.5Z"
                            fill="#FFF" />
                        <path
                            d="M68.5 0C30.6686 0 0 30.6686 0 68.5C0 106.331 30.6686 137 68.5 137C106.331 137 137 106.331 137 68.5C136.958 30.6865 106.313 0.0418093 68.5 0V0ZM97.3409 65.7958L72.8765 114.725C71.6685 117.142 68.7285 118.122 66.3125 116.914C65.3643 116.44 64.5968 115.673 64.1235 114.725L39.6591 65.7958C38.899 64.2698 38.9856 62.4586 39.8875 61.0117L64.3519 21.8692C65.978 19.5787 69.151 19.0381 71.4416 20.6642C71.9089 20.9957 72.3166 21.4019 72.6481 21.8692L97.111 61.0117C98.0144 62.4586 98.101 64.2698 97.3409 65.7958V65.7958Z"
                            fill="#FFF" />
                    </svg>
                </div>
                <div class="card-body p-0">
                    <div id="widgetChart4" class="chart-primary"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Timeline</h4>
                </div>
                <div class="card-body p-0">
                    <div id="DZ_W_TimeLine" class="widget-timeline dlab-scroll my-4 px-4 height370">
                        <ul class="timeline">
                            <li>
                                <div class="timeline-badge primary"></div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>10 minutes ago</span>
                                    <h6 class="mb-0">Youtube, a video-sharing website, goes live <strong
                                            class="text-primary">$500</strong>.</h6>
                                </a>
                            </li>
                            <li>
                                <div class="timeline-badge info">
                                </div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>20 minutes ago</span>
                                    <h6 class="mb-0">New order placed <strong class="text-info">#XF-2356.</strong>
                                    </h6>
                                    <p class="mb-0">Quisque a consequat ante Sit amet magna at volutapt...
                                    </p>
                                </a>
                            </li>
                            <li>
                                <div class="timeline-badge danger">
                                </div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>30 minutes ago</span>
                                    <h6 class="mb-0">john just buy your product <strong class="text-warning">Sell
                                            $250</strong></h6>
                                </a>
                            </li>
                            <li>
                                <div class="timeline-badge success">
                                </div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>15 minutes ago</span>
                                    <h6 class="mb-0">StumbleUpon is acquired by eBay. </h6>
                                </a>
                            </li>
                            <li>
                                <div class="timeline-badge warning">
                                </div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>20 minutes ago</span>
                                    <h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
                                </a>
                            </li>
                            <li>
                                <div class="timeline-badge dark">
                                </div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>20 minutes ago</span>
                                    <h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Timeline 2</h4>
                </div>
                <div class="card-body p-0">
                    <div id="DZ_W_TimeLine11" class="widget-timeline dlab-scroll  px-4 my-4 style-1 height370">
                        <ul class="timeline">
                            <li>
                                <div class="timeline-badge primary"></div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>10 minutes ago</span>
                                    <h6 class="mb-0">Youtube, a video-sharing website, goes live <strong
                                            class="text-primary">$500</strong>.</h6>
                                </a>
                            </li>
                            <li>
                                <div class="timeline-badge info">
                                </div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>20 minutes ago</span>
                                    <h6 class="mb-0">New order placed <strong class="text-info">#XF-2356.</strong>
                                    </h6>
                                    <p class="mb-0">Quisque a consequat ante Sit amet magna at volutapt...
                                    </p>
                                </a>
                            </li>
                            <li>
                                <div class="timeline-badge danger">
                                </div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>30 minutes ago</span>
                                    <h6 class="mb-0">john just buy your product <strong class="text-warning">Sell
                                            $250</strong></h6>
                                </a>
                            </li>
                            <li>
                                <div class="timeline-badge success">
                                </div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>15 minutes ago</span>
                                    <h6 class="mb-0">StumbleUpon is acquired by eBay. </h6>
                                </a>
                            </li>
                            <li>
                                <div class="timeline-badge warning">
                                </div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>20 minutes ago</span>
                                    <h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
                                </a>
                            </li>
                            <li>
                                <div class="timeline-badge dark">
                                </div>
                                <a class="timeline-panel text-muted" href="#">
                                    <span>20 minutes ago</span>
                                    <h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card">
                <div class="card-header  border-0 pb-0">
                    <h4 class="card-title">Notifications</h4>
                </div>
                <div class="card-body p-0 ">
                    <div id="DZ_W_Todo1" class="widget-media dlab-scroll px-4 my-4 height370">
                        <ul class="timeline">
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/1.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Dr sultads Send you Photo</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-info">
                                        KG
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Resport created successfully</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-info light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-success">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Reminder : Treatment Time!</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/1.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Dr sultads Send you Photo</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-danger">
                                        KG
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Resport created successfully</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-danger light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-primary">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Reminder : Treatment Time!</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 pb-0">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Notifications 2</h4>
                </div>
                <div class="card-body p-0 ">
                    <div id="DZ_W_Todo2" class="widget-media dlab-scroll my-4 px-4 height370">
                        <ul class="timeline">
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/1.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Dr sultads Send you Photo</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-info">
                                        KG
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Resport created successfully <span
                                                class="badge badge-warning">Warning</span></h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-info light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-success">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Reminder : Treatment Time!</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/1.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Dr sultads Send you Photo <span
                                                class="badge light badge-danger">Danger</span></h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-danger">
                                        KG
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Resport created successfully <span
                                                class="badge light badge-success">Success</span></h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-danger light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-primary">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Reminder : Treatment Time! <span
                                                class="badge light badge-success">Success</span></h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4  col-lg-6">
            <div class="card border-0 pb-0">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Message</h4>
                </div>
                <div class="card-body p-0">
                    <div id="DZ_W_Todo3" class="widget-media dlab-scroll my-4 px-4 height370">
                        <ul class="timeline ">
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/1.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Alfie Mason <small class="text-muted">29 July
                                                2020</small></h5>
                                        <p class="mb-1">I shared this on my fb wall a few months back..</p>
                                        <a href="#" class="btn btn-primary btn-xxs shadow">Reply</a>
                                        <a href="#" class="btn btn-outline-danger btn-xxs">Delete</a>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-info">
                                        KG
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Jacob Tucker <small class="text-muted">29 July
                                                2020</small></h5>
                                        <p class="mb-1">I shared this on my fb wall a few months back..</p>
                                        <a href="#" class="btn btn-primary btn-xxs shadow">Reply</a>
                                        <a href="#" class="btn btn-outline-danger btn-xxs">Delete</a>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-info light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-success">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/2.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Jack Ronan <small class="text-muted">29 July
                                                2020</small></h5>
                                        <p class="mb-1">I shared this on my fb wall a few months back..</p>
                                        <a href="#" class="btn btn-primary btn-xxs shadow">Reply</a>
                                        <a href="#" class="btn btn-outline-danger btn-xxs">Delete</a>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/1.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Noah Baldon <small class="text-muted">29 July
                                                2020</small></h5>
                                        <p class="mb-1">I shared this on my fb wall a few months back..</p>
                                        <a href="#" class="btn btn-primary btn-xxs shadow">Reply</a>
                                        <a href="#" class="btn btn-outline-danger btn-xxs">Delete</a>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-danger">
                                        PU
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Thomas Grady <small class="text-muted">02:26
                                                PM</small></h5>
                                        <p class="mb-1">I shared this on my fb wall a few months back..</p>
                                        <a href="#" class="btn btn-primary btn-xxs shadow">Reply</a>
                                        <a href="#" class="btn btn-outline-danger btn-xxs">Delete</a>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-danger light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-primary">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/3.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Oscar Weston <small class="text-muted">29 July
                                                2020</small></h5>
                                        <p class="mb-1">I shared this on my fb wall a few months back..</p>
                                        <a href="#" class="btn btn-primary btn-xxs shadow">Reply</a>
                                        <a href="#" class="btn btn-outline-danger btn-xxs">Delete</a>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6">
            <div class="card border-0 pb-0">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">To Do List</h4>
                </div>
                <div class="card-body p-0 ">
                    <div id="DZ_W_Todo4" class="widget-media dlab-scroll  px-4 my-4 height370">
                        <ul class="timeline">
                            <li>
                                <div class="timeline-panel">
                                    <div class="form-check custom-checkbox checkbox-success check-lg me-3">
                                        <input type="checkbox" class="form-check-input" id="customCheckBox1"
                                            required="">
                                        <label class="form-check-label" for="customCheckBox1"></label>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-0">Get up</h5>
                                        <small class="text-muted">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="form-check custom-checkbox checkbox-warning check-lg me-3">
                                        <input type="checkbox" class="form-check-input" id="customCheckBox2"
                                            required="">
                                        <label class="form-check-label" for="customCheckBox2"></label>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-0">Stand up</h5>
                                        <small class="text-muted">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-warning light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="form-check custom-checkbox checkbox-primary check-lg me-3">
                                        <input type="checkbox" class="form-check-input" id="customCheckBox3"
                                            required="">
                                        <label class="form-check-label" for="customCheckBox3"></label>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-0">Don't give up the fight.</h5>
                                        <small class="text-muted">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="form-check custom-checkbox checkbox-info check-lg me-3">
                                        <input type="checkbox" class="form-check-input" id="customCheckBox4"
                                            required="">
                                        <label class="form-check-label" for="customCheckBox4"></label>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-0">Do something else</h5>
                                        <small class="text-muted">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-info light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="form-check custom-checkbox checkbox-success check-lg me-3">
                                        <input type="checkbox" class="form-check-input" id="customCheckBox5"
                                            required="">
                                        <label class="form-check-label" for="customCheckBox5"></label>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-0">Get up</h5>
                                        <small class="text-muted">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="form-check custom-checkbox checkbox-warning check-lg me-3">
                                        <input type="checkbox" class="form-check-input" id="customCheckBox6"
                                            required="">
                                        <label class="form-check-label" for="customCheckBox6"></label>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-0">Stand up</h5>
                                        <small class="text-muted">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-warning light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24"
                                                version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <div class="media ai-icon">
                        <span class="me-3 bgl-primary text-primary">
                            <!-- <i class="ti-user"></i> -->
                            <svg id="icon-customers" xmlns="http://www.w3.org/2000/svg" width="30"
                                height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-user">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </span>
                        <div class="media-body">
                            <p class="mb-1">Patient</p>
                            <h4 class="mb-0">3280</h4>
                            <span class="badge badge-primary">+3.5%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <div class="media ai-icon">
                        <span class="me-3 bgl-warning text-warning">
                            <svg id="icon-orders" xmlns="http://www.w3.org/2000/svg" width="30"
                                height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-file-text">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                        </span>
                        <div class="media-body">
                            <p class="mb-1">Bills</p>
                            <h4 class="mb-0">2570</h4>
                            <span class="badge badge-warning">+3.5%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3  col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body  p-4">
                    <div class="media ai-icon">
                        <span class="me-3 bgl-danger text-danger">
                            <svg id="icon-revenue" xmlns="http://www.w3.org/2000/svg" width="30"
                                height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-dollar-sign">
                                <line x1="12" y1="1" x2="12" y2="23"></line>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </span>
                        <div class="media-body">
                            <p class="mb-1">Revenue</p>
                            <h4 class="mb-0">364.50K</h4>
                            <span class="badge badge-danger">-3.5%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <div class="media ai-icon">
                        <span class="me-3 bgl-success text-success">
                            <svg id="icon-database-widget" xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-database">
                                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                            </svg>
                        </span>
                        <div class="media-body">
                            <p class="mb-1">Patient</p>
                            <h4 class="mb-0">364.50K</h4>
                            <span class="badge badge-success">-3.5%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3  col-lg-6 col-sm-6">
            <div class="widget-stat card bg-primary">
                <div class="card-body  p-4">
                    <div class="media">
                        <span class="me-3">
                            <i class="la la-users"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Students</p>
                            <h3 class="text-white">3280</h3>
                            <div class="progress mb-2 bg-secondary">
                                <div class="progress-bar progress-animated bg-white" style="width: 80%">
                                </div>
                            </div>
                            <small>80% Increase in 20 Days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-warning">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="me-3">
                            <i class="la la-user"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">New Students</p>
                            <h3 class="text-white">245</h3>
                            <div class="progress mb-2 bg-primary">
                                <div class="progress-bar progress-animated bg-white" style="width: 50%">
                                </div>
                            </div>
                            <small>50% Increase in 25 Days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card bg-secondary">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="me-3">
                            <i class="la la-graduation-cap"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Total Course</p>
                            <h3 class="text-white">28</h3>
                            <div class="progress mb-2 bg-primary">
                                <div class="progress-bar progress-animated bg-white" style="width: 76%">
                                </div>
                            </div>
                            <small>76% Increase in 20 Days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3  col-lg-6 col-sm-6">
            <div class="widget-stat card bg-danger ">
                <div class="card-body p-4">
                    <div class="media">
                        <span class="me-3">
                            <i class="la la-dollar"></i>
                        </span>
                        <div class="media-body text-white">
                            <p class="mb-1">Fees Collect</p>
                            <h3 class="text-white">250$</h3>
                            <div class="progress mb-2 bg-secondary">
                                <div class="progress-bar progress-animated bg-white" style="width: 30%">
                                </div>
                            </div>
                            <small>30% Increase in 30 Days</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3  col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <h4 class="card-title">Total Students</h4>
                    <h3>3280</h3>
                    <div class="progress mb-2">
                        <div class="progress-bar progress-animated bg-primary" style="width: 80%"></div>
                    </div>
                    <small>80% Increase in 20 Days</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <h4 class="card-title">New Students</h4>
                    <h3>245</h3>
                    <div class="progress mb-2">
                        <div class="progress-bar progress-animated bg-warning" style="width: 50%"></div>
                    </div>
                    <small>50% Increase in 25 Days</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3  col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <h4 class="card-title">Total Course</h4>
                    <h3>28</h3>
                    <div class="progress mb-2">
                        <div class="progress-bar progress-animated bg-red" style="width: 76%"></div>
                    </div>
                    <small>76% Increase in 20 Days</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3  col-lg-6 col-sm-6">
            <div class="widget-stat card">
                <div class="card-body p-4">
                    <h4 class="card-title">Fees Collection</h4>
                    <h3>25160$</h3>
                    <div class="progress mb-2">
                        <div class="progress-bar progress-animated bg-success" style="width: 30%"></div>
                    </div>
                    <small>30% Increase in 30 Days</small>
                </div>
            </div>
        </div>


        <div class="col-xl-12 col-lg-12 col-sm-12">
            <div class="row">
                <div class="col-xl-3  col-lg-6 col-sm-6">
                    <div class="widget-stat card bg-primary">
                        <div class="card-header border-0 pb-0">
                            <h3 class="card-title text-white">Total Students</h3>
                            <h5 class="text-white mb-0"><i class="fa fa-caret-up"></i> 422</h5>
                        </div>
                        <div class="card-body text-center">
                            <div class="ico-sparkline">
                                <div id="sparkline12"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-sm-6">
                    <div class="widget-stat card bg-warning overflow-hidden">
                        <div class="card-header border-0 ">
                            <h3 class="card-title text-white">New Students</h3>
                            <h5 class="text-white mb-0"><i class="fa fa-caret-up"></i> 357</h5>
                        </div>
                        <div class="card-body text-center p-0">
                            <div class="ico-sparkline">
                                <div id="spark-bar-2"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3  col-lg-6 col-sm-6">
                    <div class="widget-stat card bg-secondary">
                        <div class="card-header pb-3 border-0 pb-0">
                            <h3 class="card-title text-white">Total Course</h3>
                            <h5 class="text-white mb-0"><i class="fa fa-caret-up"></i> 547</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="px-4"><span class="bar1"
                                    data-peity='{ "fill": ["rgb(255, 255, 255)", "rgb(255, 255, 255)"]}'>6,2,8,4,-3,8,1,-3,6,-5,9,2,-8,1,4,8,9,8,2,1</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6 col-sm-6">
                    <div class="widget-stat card bg-danger overflow-hidden">
                        <div class="card-header pb-3 border-0 pb-0">
                            <h3 class="card-title text-white">Fees Collection</h3>
                            <h5 class="text-white mb-0"><i class="fa fa-caret-up"></i> 3280$</h5>
                        </div>
                        <div class="card-body p-0">
                            <span class="peity-line-2" data-width="100%">7,6,8,7,3,8,3,3,6,5,9,2,8</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-sm-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h2 class="card-title">about me</h2>
                </div>
                <div class="card-body pb-0">
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <strong>Gender</strong>
                            <span class="mb-0">Male</span>
                        </li>
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <strong>Education</strong>
                            <span class="mb-0">PHD</span>
                        </li>
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <strong>Designation</strong>
                            <span class="mb-0">Se. Professor</span>
                        </li>
                        <li class="list-group-item d-flex px-0 justify-content-between">
                            <strong>Operation Done</strong>
                            <span class="mb-0">120</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer pt-0 pb-0 text-center">
                    <div class="row">
                        <div class="col-4 pt-3 pb-3 border-end">
                            <h3 class="mb-1 text-primary">150</h3>
                            <span>Projects</span>
                        </div>
                        <div class="col-4 pt-3 pb-3 border-end">
                            <h3 class="mb-1 text-primary">140</h3>
                            <span>Uploads</span>
                        </div>
                        <div class="col-4 pt-3 pb-3">
                            <h3 class="mb-1 text-primary">45</h3>
                            <span>Tasks</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-8 col-xxl-8 col-lg-12 col-sm-12">
            <div id="user-activity" class="card">
                <div class="card-header border-0 pb-0 d-sm-flex d-block">
                    <h4 class="card-title">Visitor Activity</h4>
                    <div class="card-action mb-sm-0 my-2">
                        <ul class="nav nav-pills light" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="day-tab" href="#user" data-bs-toggle="tab"
                                    data-bs-target="#day-tab-pane" role="tab" aria-selected="true">Day</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="month-tab" href="#bounce" data-bs-toggle="tab"
                                    data-bs-target="#month-tab-pane" role="tab" aria-selected="false">
                                    Month</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="year-tab" href="#session-duration" data-bs-toggle="tab"
                                    data-bs-target="#year-tab-pane" role="tab" aria-selected="false">Year</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="user" role="tabpanel">
                            <canvas id="activity" class="chartjs"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <div class="text-center">
                        <div class="profile-photo">
                            <img src="{{ asset('images/profile/profile.png') }}" width="100" class="img-fluid rounded-circle"
                                alt="">
                        </div>
                        <h3 class="mt-4 mb-1">Deangelo Sena</h3>
                        <p class="text-muted">Senior Manager</p>
                        <a class="btn btn-outline-primary btn-rounded mt-3 px-5"
                            href="javascript:void(0);;">Folllow</a>
                    </div>
                </div>

                <div class="card-footer pt-0 pb-0 text-center">
                    <div class="row">
                        <div class="col-4 pt-3 pb-3 border-end">
                            <h3 class="mb-1">150</h3><span>Follower</span>
                        </div>
                        <div class="col-4 pt-3 pb-3 border-end">
                            <h3 class="mb-1">140</h3><span>Place Stay</span>
                        </div>
                        <div class="col-4 pt-3 pb-3">
                            <h3 class="mb-1">45</h3><span>Reviews</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-sm-6">
            <div class="card active_users">
                <div class="card-header bg-primary border-0 pb-0">
                    <h4 class="card-title text-white">Active Users</h4>
                    <span id="counter"></span>
                </div>
                <div class="bg-primary">
                    <canvas id="activeUser"></canvas>
                </div>
                <div class="card-body pt-0">
                    <div class="list-group-flush mt-4">
                        <div
                            class="list-group-item bg-transparent d-flex justify-content-between px-0 py-1 fw-semi-bold border-0 border-bottom">
                            <p class="mb-0">Top Active Pages</p>
                            <p class="mb-0">Active Users</p>
                        </div>
                        <div
                            class="list-group-item bg-transparent d-flex justify-content-between px-0 py-1 border-0 border-bottom">
                            <p class="mb-0">/bootstrap-themes/</p>
                            <p class="mb-0">3</p>
                        </div>
                        <div
                            class="list-group-item bg-transparent d-flex justify-content-between px-0 py-1 border-0 border-bottom">
                            <p class="mb-0">/tags/html5/</p>
                            <p class="mb-0">3</p>
                        </div>
                        <div
                            class="list-group-item bg-transparent d-xxl-flex justify-content-between px-0 py-1 d-none">
                            <p class="mb-0">/</p>
                            <p class="mb-0">2</p>
                        </div>
                        <div
                            class="list-group-item bg-transparent d-xxl-flex justify-content-between px-0 py-1 d-none">
                            <p class="mb-0">/preview/falcon/dashboard/</p>
                            <p class="mb-0">2</p>
                        </div>
                        <div
                            class="list-group-item bg-transparent d-flex justify-content-between px-0 py-1 border-0 border-bottom">
                            <p class="mb-0">/100-best-themes...all-time/</p>
                            <p class="mb-0">1</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-12 col-sm-12">
            <div class="card overflow-hidden">
                <div class="text-center p-3 overlay-box " style="background-image: url(images/big/img1.jpg);">
                    <div class="profile-photo">
                        <img src="{{ asset('images/profile/profile.png') }}" width="100" class="img-fluid rounded-circle"
                            alt="">
                    </div>
                    <h3 class="mt-3 mb-1 text-white">Deangelo Sena</h3>
                    <p class="text-white mb-0">Senior Manager</p>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Patient
                            Gender</span> <strong class="text-muted">Female </strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Years
                            Old</span> <strong class="text-muted">Age: 24 </strong></li>
                </ul>
                <div class="card-footer border-0 mt-0">
                    <button class="btn btn-primary btn-lg btn-block">
                        <i class="fa fa-bell-o"></i> Reminder Alarm
                    </button>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-xxl-12 col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Recent Payments Queue</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive recentOrderTable">
                        <table class="table verticle-middle table-responsive-md">
                            <thead>
                                <tr>
                                    <th scope="col">Ward No.</th>
                                    <th scope="col">Patient</th>
                                    <th scope="col">Dr Name</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Bills</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>12</td>
                                    <td>Mr. Bobby</td>
                                    <td>Dr. Jackson</td>
                                    <td>01 August 2020</td>
                                    <td><span class="badge badge-rounded badge-success">Checkin</span></td>
                                    <td>$120</td>
                                    <td>
                                        <div class="dropdown custom-dropdown mb-0">
                                            <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="18px"
                                                    height="18px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <circle fill="#000000" cx="12" cy="5"
                                                            r="2" />
                                                        <circle fill="#000000" cx="12" cy="12"
                                                            r="2" />
                                                        <circle fill="#000000" cx="12" cy="19"
                                                            r="2" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="javascript:void(0);;;">Details</a>
                                                <a class="dropdown-item text-danger"
                                                    href="javascript:void(0);;;">Cancel</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>10 </td>
                                    <td>Mr. Dexter</td>
                                    <td>Dr. Charles</td>
                                    <td>31 July 2020</td>
                                    <td><span class="badge badge-rounded badge-warning">Panding</span></td>
                                    <td>$540</td>
                                    <td>
                                        <div class="dropdown custom-dropdown mb-0">
                                            <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="18px"
                                                    height="18px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <circle fill="#000000" cx="12" cy="5"
                                                            r="2" />
                                                        <circle fill="#000000" cx="12" cy="12"
                                                            r="2" />
                                                        <circle fill="#000000" cx="12" cy="19"
                                                            r="2" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="javascript:void(0);;;">Details</a>
                                                <a class="dropdown-item text-danger"
                                                    href="javascript:void(0);;;">Cancel</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>03 </td>
                                    <td>Mr. Nathan</td>
                                    <td>Dr. Frederick</td>
                                    <td>30 July 2020</td>
                                    <td><span class="badge badge-rounded badge-danger">Canceled</span></td>
                                    <td>$301</td>
                                    <td>
                                        <div class="dropdown custom-dropdown mb-0">
                                            <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="18px"
                                                    height="18px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <circle fill="#000000" cx="12" cy="5"
                                                            r="2" />
                                                        <circle fill="#000000" cx="12" cy="12"
                                                            r="2" />
                                                        <circle fill="#000000" cx="12" cy="19"
                                                            r="2" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="javascript:void(0);;;">Details</a>
                                                <a class="dropdown-item text-danger"
                                                    href="javascript:void(0);;;">Cancel</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>05</td>
                                    <td>Mr. Aurora</td>
                                    <td>Dr. Roman</td>
                                    <td>29 July 2020</td>
                                    <td><span class="badge badge-rounded badge-success">Checkin</span></td>
                                    <td>$099</td>
                                    <td>
                                        <div class="dropdown custom-dropdown mb-0">
                                            <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="18px"
                                                    height="18px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <circle fill="#000000" cx="12" cy="5"
                                                            r="2" />
                                                        <circle fill="#000000" cx="12" cy="12"
                                                            r="2" />
                                                        <circle fill="#000000" cx="12" cy="19"
                                                            r="2" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="javascript:void(0);;;">Details</a>
                                                <a class="dropdown-item text-danger"
                                                    href="javascript:void(0);;;">Cancel</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>06</td>
                                    <td>Mr. Matthew</td>
                                    <td>Dr. Samantha</td>
                                    <td>28 July 2020</td>
                                    <td><span class="badge badge-rounded badge-success">Checkin</span></td>
                                    <td>$520</td>
                                    <td>
                                        <div class="dropdown custom-dropdown mb-0">
                                            <div class="btn sharp btn-primary tp-btn" data-bs-toggle="dropdown">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="18px"
                                                    height="18px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none"
                                                        fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24" />
                                                        <circle fill="#000000" cx="12" cy="5"
                                                            r="2" />
                                                        <circle fill="#000000" cx="12" cy="12"
                                                            r="2" />
                                                        <circle fill="#000000" cx="12" cy="19"
                                                            r="2" />
                                                    </g>
                                                </svg>
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="javascript:void(0);;;">Details</a>
                                                <a class="dropdown-item text-danger"
                                                    href="javascript:void(0);;;">Cancel</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-xxl-4 col-sm-6">
            <div class="card text-white bg-primary">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Blood type
                            :</span><strong>O+</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Allergies
                            :</span><strong>Penicilin, peanuts </strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Pressure
                            :</span><strong>120/100 mmHG</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Diseases
                            :</span><strong>Diabetes</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Temperture
                            :</span><strong>34 Degree</strong></li>
                </ul>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-xxl-4 col-sm-6">
            <div class="card text-white bg-warning text-black">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Regular
                            Checkups</span></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Dr
                            Theodore Handle :</span><strong>Dentist</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Dr
                            Valentino Morose :</span><strong>Surgeon</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Dr Fleece
                            Marigold :</span><strong>Clinical</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span class="mb-0">Dr Eric
                            Widget :</span><strong>Cardiology</strong></li>
                </ul>
            </div>
        </div>

        <div class="col-xl-4 col-lg-12 col-xxl-4 col-sm-12">
            <div class="card">
                <div class="card-body text-center ai-icon  text-primary">
                    <svg id="rocket-icon" class="my-2" viewBox="0 0 24 24" width="80" height="80"
                        stroke="currentColor" stroke-width="1" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <h4 class="my-2">You don’t have badges yet</h4>
                    <a href="javascript:void(0);;;" class="btn my-2 btn-primary btn-lg px-4"><i
                            class="fa fa-usd"></i> Earn Budges</a>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-12 col-xxl-8 col-sm-12">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-xxl-6 col-md-6">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <div class="clearfix">
                                <h3 class="card-title">Blood pressure</h3>
                                <span>In the normal</span>
                            </div>
                            <div class="clearfix text-center">
                                <h3 class="text-primary mb-0">120/89</h3>
                                <span>mmHG</span>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="ico-sparkline">
                                <div id="spark-bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-xxl-6 col-md-6">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <div class="clearfix">
                                <h3 class="card-title">Heart Rate</h3>
                                <span class="text-danger">Above the normal</span>
                            </div>
                            <div class="clearfix text-center">
                                <h3 class="text-danger mb-0">107</h3>
                                <span>Per Min</span>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="ico-sparkline">
                                <div id="composite-bar"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-xxl-6 col-md-6">
                    <div class="card overflow-hidden">
                        <div class="card-header border-0 pb-0">
                            <div class="clearfix">
                                <h3 class="card-title">Glucose Rate</h3>
                                <span>In the normal</span>
                            </div>
                            <div class="clearfix text-center">
                                <h3 class="text-success mb-0">97</h3>
                                <span>mg/dl</span>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="ico-sparkline">
                                <div id="sparkline8"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-xxl-6 col-md-6">
                    <div class="card overflow-hidden">
                        <div class="card-header border-0 pb-0">
                            <div class="clearfix">
                                <h3 class="card-title">Clolesterol</h3>
                                <span>In the normal</span>
                            </div>
                            <div class="clearfix text-center">
                                <h3 class="text-info mb-0">124</h3>
                                <span>mg/dl</span>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <div class="ico-sparkline">
                                <div id="sparkline9" class="height80;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-12 col-sm-12">
            <div class="card overflow-hidden">
                <div class="text-center p-5 overlay-box" style="background-image: url({{ asset('images/big/img5.jpg') }});">
                    <img src="{{ asset('images/profile/profile.png') }}" width="100" class="img-fluid rounded-circle"
                        alt="">
                    <h3 class="mt-3 mb-0 text-white">Deangelo Sena</h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="bgl-primary rounded p-3">
                                <h4 class="mb-0">Female</h4>
                                <small>Patient Gender</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bgl-primary rounded p-3">
                                <h4 class="mb-0">Age: 24</h4>
                                <small>Years Old</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer mt-0">
                    <button class="btn btn-primary btn-lg btn-block">View Profile</button>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-xxl-6 col-sm-6">
            <div class="card bg-primary overflow-hidden">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-white">Power</h5>
                            <span class="text-white">2017.1.20</span>
                        </div>
                        <div class="col text-end">
                            <h5 class="text-white"><i class="fa fa-caret-up"></i> 260</h5>
                            <span class="text-white">+12.5(2.8%)</span>
                        </div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="chart_widget_1"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-xxl-6 col-sm-6">
            <div class="card bg-success overflow-hidden">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col">
                            <h5 class="text-white">Power</h5>
                            <span class="text-white">2017.1.20</span>
                        </div>
                        <div class="col text-end">
                            <h5 class="text-white"><i class="fa fa-caret-up"></i> 260</h5>
                            <span class="text-white">+12.5(2.8%)</span>
                        </div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <div id="chart_widget_5"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-xxl-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body pb-0">
                    <div class="row">
                        <div class="col">
                            <h5>3650</h5>
                            <span>VIEWS OF YOUR PROJECT</span>
                        </div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="chart_widget_2"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-xxl-6 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-auto">
                            <h4 class="text-uppercase">74,206 K</h4>
                            <span>Lifetime earnings</span>
                        </div>
                        <div class="col-auto">
                            <div class="chart-wrapper height100">
                                <canvas id="chart_widget_7"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-xxl-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="row justify-content-between">
                                <div class="col-auto">
                                    <h5>Lorem Ipsum</h5>
                                </div>
                                <div class="col-auto">
                                    <h5>
                                        <span><i class="fa fa-caret-up"></i></span>
                                        <span>2,250</span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <div id="chart_widget_6"></div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col text-center">
                                    <h5 class="fw-normal">1230</h5>
                                    <span>Type A</span>
                                </div>
                                <div class="col text-center">
                                    <h5 class="fw-normal">1230</h5>
                                    <span>Type A</span>
                                </div>
                                <div class="col text-center">
                                    <h5 class="fw-normal">1230</h5>
                                    <span>Type A</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body pb-0">
                            <div class="row justify-content-between">
                                <div class="col-auto">
                                    <h5>Lorem Ipsum</h5>
                                </div>
                                <div class="col-auto">
                                    <h5>
                                        <span><i class="fa fa-caret-up"></i></span>
                                        <span>2,250</span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="chart_widget_3"></canvas>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col text-center">
                                    <h5 class="fw-normal">1230</h5>
                                    <span>Type A</span>
                                </div>
                                <div class="col text-center">
                                    <h5 class="fw-normal">1230</h5>
                                    <span>Type A</span>
                                </div>
                                <div class="col text-center">
                                    <h5 class="fw-normal">1230</h5>
                                    <span>Type A</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card overflow-hidden">
                <div class="card-body pb-0">
                    <h4 class="card-title text-uppercase fw-normal">Market Now</h4>
                    <h2 class="fw-normal text-danger">
                        <span><i class="fa fa-caret-up"></i></span>
                        <span>3454664</span>
                    </h2>
                    <div class="row mt-5">
                        <div class="col text-center">
                            <h5 class="fw-normal">APPL</h5>
                            <span class="text-success">+ 82.24 %</span>
                        </div>
                        <div class="col text-center">
                            <h5 class="fw-normal">FB</h5>
                            <span class="text-danger">- 12.24 %</span>
                        </div>
                        <div class="col text-center">
                            <h5 class="fw-normal">GOOG</h5>
                            <span class="text-success">+ 42.24 %</span>
                        </div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="chart_widget_4"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6">
            <div class="card">
                <div class="card-body pb-0">
                    <h4 class="card-title text-uppercase fw-normal">Sales Analysis</h4>
                    <h2 class="fw-normal text-danger">
                        <span><i class="fa fa-caret-up"></i></span>
                        <span>3454664</span>
                    </h2>
                    <div class="row mt-5">
                        <div class="col text-center">
                            <h5 class="fw-normal">Today</h5>
                            <span class="text-success">+ 8224</span>
                        </div>
                        <div class="col text-center">
                            <h5 class="fw-normal">Today</h5>
                            <span class="text-danger">- 1224</span>
                        </div>
                        <div class="col text-center">
                            <h5 class="fw-normal">Week</h5>
                            <span class="text-success">+ 4224</span>
                        </div>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <div id="chart_widget_8"></div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Top Products</h4>
                </div>
                <div class="card-body pb-0">
                    <div class="widget-media">
                        <ul class="timeline">
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/1.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Dr sultads Send you Photo</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24"
                                                version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-info">
                                        KG
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Resport created successfully</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-info light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24"
                                                version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-success">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Reminder : Treatment Time!</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24"
                                                version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="chart_widget_9"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-12">
            <div class="card overflow-hidden">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Top Products</h4>
                </div>
                <div class="card-body pb-0">
                    <div class="widget-media">
                        <ul class="timeline">
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/4.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Dr sultads Send you Photo</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-primary light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24"
                                                version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-info">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/2.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Resport created successfully</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-info light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24"
                                                version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-success">
                                        <img alt="image" width="50" src="{{ asset('images/avatar/3.jpg') }}">
                                    </div>
                                    <div class="media-body">
                                        <h5 class="mb-1">Reminder : Treatment Time!</h5>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-success light sharp"
                                            data-bs-toggle="dropdown">
                                            <svg width="18px" height="18px" viewBox="0 0 24 24"
                                                version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none"
                                                    fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <circle fill="#000000" cx="5" cy="12" r="2" />
                                                    <circle fill="#000000" cx="12" cy="12" r="2" />
                                                    <circle fill="#000000" cx="19" cy="12" r="2" />
                                                </g>
                                            </svg>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="chart-wrapper">
                    <canvas id="chart_widget_10"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-xxl-8 col-lg-12">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card overflow-hidden">
                        <div class="row no-gutters">
                            <div class="col-5 p-0">
                                <div class="card-body">
                                    <h6 class="fw-normal text-uppercase">Weekly sales</h6>
                                    <h4>$ 14000</h4>
                                    <div>
                                        <span class="badge badge-light">60%</span>
                                        <span>Higher</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-7 p-0">
                                <div class="chart-wrapper">
                                    <canvas id="chart_widget_11"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>570</h5>
                            <p>All Sales</p>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="chart_widget_14"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>570</h5>
                            <p>All Sales</p>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="chart_widget_15"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-xxl-4 col-lg-12">
            <div class="card">
                <div class="chart-wrapper">
                    <canvas id="chart_widget_16"></canvas>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Sales Status</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <h6>67%</h6>
                                <span>Grow</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: 80%"></div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-between">
                                <h6>67%</h6>
                                <span>Grow</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: 70%"></div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-between">
                                <h6>67%</h6>
                                <span>Grow</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-info" style="width: 40%"></div>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="d-flex justify-content-between">
                                <h6>67%</h6>
                                <span>Grow</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card overflow-hidden">
                <div class="social-graph-wrapper widget-facebook">
                    <span class="s-icon"><i class="fab fa-facebook-f"></i></span>
                </div>
                <div class="row">
                    <div class="col-6 border-end">
                        <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                            <h4 class="m-1"><span class="counter">89</span> k</h4>
                            <p class="m-0">Friends</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                            <h4 class="m-1"><span class="counter">119</span> k</h4>
                            <p class="m-0">Followers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card overflow-hidden">
                <div class="social-graph-wrapper widget-linkedin">
                    <span class="s-icon"><i class="fab fa-linkedin-in"></i></span>
                </div>
                <div class="row">
                    <div class="col-6 border-end">
                        <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                            <h4 class="m-1"><span class="counter">89</span> k</h4>
                            <p class="m-0">Friends</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                            <h4 class="m-1"><span class="counter">119</span> k</h4>
                            <p class="m-0">Followers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card overflow-hidden">
                <div class="social-graph-wrapper widget-googleplus">
                    <span class="s-icon"><i class="fab fa-google-plus-g"></i></span>
                </div>
                <div class="row">
                    <div class="col-6 border-end">
                        <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                            <h4 class="m-1"><span class="counter">89</span> k</h4>
                            <p class="m-0">Friends</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                            <h4 class="m-1"><span class="counter">119</span> k</h4>
                            <p class="m-0">Followers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-xxl-3 col-sm-6">
            <div class="card overflow-hidden">
                <div class="social-graph-wrapper widget-twitter">
                    <span class="s-icon"><i class="fab fa-twitter"></i></span>
                </div>
                <div class="row">
                    <div class="col-6 border-end">
                        <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                            <h4 class="m-1"><span class="counter">89</span> k</h4>
                            <p class="m-0">Friends</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="pt-3 pb-3 ps-0 pe-0 text-center">
                            <h4 class="m-1"><span class="counter">119</span> k</h4>
                            <p class="m-0">Followers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
