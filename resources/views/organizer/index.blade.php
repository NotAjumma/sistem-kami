@extends('layouts.admin.default')
@push('styles')
    <style>
        .check-point-area {
            width: 80% !important;
            height: auto !important;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <!-- Row -->
        <div class="row">
            <div class="col-xl-12">
                <div class="row  main-card">
                    <div class="swiper mySwiper-counter position-relative overflow-hidden">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="card card-box bg-secondary">
                                    <div class="card-header border-0 pb-0">
                                        <div class="chart-num">
                                            <p>
                                                <i class="fa-solid fa-sort-down me-2"></i>
                                                Total Income
                                            </p>
                                            <h2 class="font-w600 mb-0">RM{{ $totalIncome }}</h2>
                                        </div>
                                        <div class="dlab-swiper-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45"
                                                viewBox="0 0 24 24">
                                                <!-- White circular background -->
                                                <circle cx="12" cy="12" r="12" fill="#ffffff" />

                                                <!-- Group to scale and center the path -->
                                                <g transform="translate(3.6, 3.6) scale(0.7)">
                                                    <line x1="12" y1="1" x2="12" y2="23" fill="none" stroke="#3693ff"
                                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                    </line>
                                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" fill="none"
                                                        stroke="#3693ff" stroke-width="1.8" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="widgetChart5" class="chart-primary"></div>
                                    </div>
                                </div>
                            </div>
                            @if($authUser->type == "event")
                            <div class="swiper-slide">
                                <div class="card card-box bg-pink">
                                    <div class="card-header border-0 pb-0">
                                        <div class="chart-num">
                                            <p>
                                                <i class="fa-solid fa-sort-down me-2"></i>
                                                Total Events Created
                                            </p>
                                            <h2 class="font-w600 mb-0">{{ $totalEvents }}</h2>
                                        </div>
                                        <div class="dlab-swiper-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45"
                                                viewBox="0 0 24 24">
                                                <!-- White circular background -->
                                                <circle cx="12" cy="12" r="12" fill="#ffffff" />

                                                <!-- Group to scale and center the path -->
                                                <g transform="translate(3.6, 3.6) scale(0.7)">
                                                    <path
                                                        d="M15 14V17.6C15 18.4401 15 18.8601 14.8365 19.181C14.6927 19.4632 14.4632 19.6927 14.181 19.8365C13.8601 20 13.4401 20 12.6 20H7.40001C6.55994 20 6.1399 20 5.81903 19.8365C5.53679 19.6927 5.30731 19.4632 5.1635 19.181C5.00001 18.8601 5.00001 18.4401 5.00001 17.6V10M19 10V20M5.00001 16H15M5.55778 4.88446L3.5789 8.84223C3.38722 9.22559 3.29138 9.41727 3.3144 9.57308C3.3345 9.70914 3.40976 9.8309 3.52246 9.90973C3.65153 10 3.86583 10 4.29444 10H19.7056C20.1342 10 20.3485 10 20.4776 9.90973C20.5903 9.8309 20.6655 9.70914 20.6856 9.57308C20.7086 9.41727 20.6128 9.22559 20.4211 8.84223L18.4422 4.88446C18.2817 4.5634 18.2014 4.40287 18.0817 4.28558C17.9758 4.18187 17.8482 4.10299 17.7081 4.05465C17.5496 4 17.3701 4 17.0112 4H6.98887C6.62991 4 6.45043 4 6.29198 4.05465C6.15185 4.10299 6.02422 4.18187 5.91833 4.28558C5.79858 4.40287 5.71832 4.5634 5.55778 4.88446Z"
                                                        fill="none" stroke="#ac4cbc" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </g>
                                            </svg>

                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="widgetChart6" class="chart-primary"></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($authUser->type == "service")
                            <div class="swiper-slide">
                                <div class="card card-box bg-pink">
                                    <div class="card-header border-0 pb-0">
                                        <div class="chart-num">
                                            <p>
                                                <i class="fa-solid fa-sort-down me-2"></i>
                                                Total Packages Created
                                            </p>
                                            <h2 class="font-w600 mb-0">{{ $totalPackages }}</h2>
                                        </div>
                                        <div class="dlab-swiper-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45"
                                                viewBox="0 0 24 24">
                                                <!-- White circular background -->
                                                <circle cx="12" cy="12" r="12" fill="#ffffff" />

                                                <!-- Group to scale and center the path -->
                                                <g transform="translate(3.6, 3.6) scale(0.7)">
                                                    <path
                                                        d="M15 14V17.6C15 18.4401 15 18.8601 14.8365 19.181C14.6927 19.4632 14.4632 19.6927 14.181 19.8365C13.8601 20 13.4401 20 12.6 20H7.40001C6.55994 20 6.1399 20 5.81903 19.8365C5.53679 19.6927 5.30731 19.4632 5.1635 19.181C5.00001 18.8601 5.00001 18.4401 5.00001 17.6V10M19 10V20M5.00001 16H15M5.55778 4.88446L3.5789 8.84223C3.38722 9.22559 3.29138 9.41727 3.3144 9.57308C3.3345 9.70914 3.40976 9.8309 3.52246 9.90973C3.65153 10 3.86583 10 4.29444 10H19.7056C20.1342 10 20.3485 10 20.4776 9.90973C20.5903 9.8309 20.6655 9.70914 20.6856 9.57308C20.7086 9.41727 20.6128 9.22559 20.4211 8.84223L18.4422 4.88446C18.2817 4.5634 18.2014 4.40287 18.0817 4.28558C17.9758 4.18187 17.8482 4.10299 17.7081 4.05465C17.5496 4 17.3701 4 17.0112 4H6.98887C6.62991 4 6.45043 4 6.29198 4.05465C6.15185 4.10299 6.02422 4.18187 5.91833 4.28558C5.79858 4.40287 5.71832 4.5634 5.55778 4.88446Z"
                                                        fill="none" stroke="#ac4cbc" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </g>
                                            </svg>

                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="widgetChart6" class="chart-primary"></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="swiper-slide">
                                <div class="card card-box bg-warning">
                                    <div class="card-header border-0 pb-0">
                                        <div class="chart-num">
                                            <p>
                                                <i class="fa-solid fa-sort-down me-2"></i>
                                                Total Bookings
                                            </p>
                                            <h2 class="font-w600 mb-0">{{ $totalBookings }}</h2>
                                        </div>
                                        <div class="dlab-swiper-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45"
                                                viewBox="0 0 24 24">
                                                <!-- White circular background -->
                                                <circle cx="12" cy="12" r="12" fill="#ffffff" />

                                                <!-- Group to scale and center the path -->
                                                <g transform="translate(4.8, 4.8) scale(0.6)">
                                                    <path
                                                        d="M6 2L3 6v14c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2V6l-3-4H6zM3.8 6h16.4M16 10a4 4 0 1 1-8 0"
                                                        fill="none" stroke="#FFAB2D" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </g>
                                            </svg>
                                        </div>

                                    </div>
                                    <div class="card-body p-0">
                                        <div id="widgetChart1" class="chart-primary"></div>
                                    </div>
                                </div>
                            </div>
                            @if($authUser->type == "event")
                            <div class="swiper-slide">
                                <div class="card card-box bg-dark">
                                    <div class="card-header border-0 pb-0">
                                        <div class="chart-num">
                                            <p>
                                                <i class="fa-solid fa-sort-down me-2"></i>
                                                Total Tickets Sold
                                            </p>
                                            <h2 class="font-w600 mb-0">{{ $ticketSold }}</h2>
                                        </div>
                                        <div class="dlab-swiper-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45"
                                                viewBox="0 0 24 24">
                                                <!-- White circular background -->
                                                <circle cx="12" cy="12" r="12" fill="#ffffff" />

                                                <!-- Group to scale and center the path -->
                                                <g transform="translate(3.6, 3.6) scale(0.7)">
                                                    <path
                                                        d="M20.75,5 C21.4403559,5 22,5.55964406 22,6.250043 L22,8.50884316 C22,8.90254021 21.6954171,9.22920669 21.3026979,9.25693735 C19.8694655,9.35814067 18.75,10.5545175 18.75,12 C18.75,13.4454825 19.8694655,14.6418593 21.3026979,14.7430626 C21.6954171,14.7707933 22,15.0974598 22,15.4911568 L22,17.75 C22,18.4403559 21.4403559,19 20.75,19 L3.25,19 C2.55964406,19 2,18.4403559 2.00000001,17.7500876 L1.99973613,15.4913152 C1.99973613,15.0975356 2.30418767,14.7707771 2.69699233,14.7430845 C4.13036194,14.6420325 5.25,13.4455976 5.25,12 C5.25,10.5544024 4.13036194,9.3579675 2.69699233,9.2569155 C2.30418767,9.22922292 1.99973613,8.90246439 1.99973613,8.50868478 L2,6.25 C2,5.55964406 2.55964406,5 3.25,5 L20.75,5 Z"
                                                        fill="none" stroke="#5b5e81" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="widgetChart2" class="chart-primary"></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($authUser->type == "service")
                            <div class="swiper-slide">
                                <div class="card card-box bg-dark">
                                    <div class="card-header border-0 pb-0">
                                        <div class="chart-num">
                                            <p>
                                                <i class="fa-solid fa-sort-down me-2"></i>
                                                Total Slot Booked
                                            </p>
                                            <h2 class="font-w600 mb-0">{{ $totalSlotBooked }}</h2>
                                        </div>
                                        <div class="dlab-swiper-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45"
                                                viewBox="0 0 24 24">
                                                <!-- White circular background -->
                                                <circle cx="12" cy="12" r="12" fill="#ffffff" />

                                                <!-- Group to scale and center the path -->
                                                <g transform="translate(3.6, 3.6) scale(0.7)">
                                                    <path
                                                        d="M20.75,5 C21.4403559,5 22,5.55964406 22,6.250043 L22,8.50884316 C22,8.90254021 21.6954171,9.22920669 21.3026979,9.25693735 C19.8694655,9.35814067 18.75,10.5545175 18.75,12 C18.75,13.4454825 19.8694655,14.6418593 21.3026979,14.7430626 C21.6954171,14.7707933 22,15.0974598 22,15.4911568 L22,17.75 C22,18.4403559 21.4403559,19 20.75,19 L3.25,19 C2.55964406,19 2,18.4403559 2.00000001,17.7500876 L1.99973613,15.4913152 C1.99973613,15.0975356 2.30418767,14.7707771 2.69699233,14.7430845 C4.13036194,14.6420325 5.25,13.4455976 5.25,12 C5.25,10.5544024 4.13036194,9.3579675 2.69699233,9.2569155 C2.30418767,9.22922292 1.99973613,8.90246439 1.99973613,8.50868478 L2,6.25 C2,5.55964406 2.55964406,5 3.25,5 L20.75,5 Z"
                                                        fill="none" stroke="#5b5e81" stroke-width="1.8"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="widgetChart2" class="chart-primary"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="card card-box bg-info">
                                    <div class="card-header border-0 pb-0">
                                        <div class="chart-num">
                                            <p>
                                                <i class="fa-solid fa-sort-down me-2"></i>
                                                Total Upcoming Slots
                                            </p>
                                            <h2 class="font-w600 mb-0">{{ $totalUpcomingSlots }}</h2>
                                        </div>
                                        <div class="dlab-swiper-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45"
                                                viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="12" fill="#ffffff" />

                                                <g transform="translate(6.6,6.6) scale(0.45)">
                                                    <path
                                                        d="M10,1h6a0,0,0,0,1,0,0V6.13a.87.87,0,0,1-.87.87H10.87A.87.87,0,0,1,10,6.13V1A0,0,0,0,1,10,1Z"
                                                        fill="#00ADA3" />
                                                    <path
                                                        d="M11,26H3a3,3,0,0,1-3-3V3A3,3,0,0,1,3,0H23a3,3,0,0,1,3,3v8a1,1,0,0,1-2,0V3a1,1,0,0,0-1-1H3A1,1,0,0,0,2,3V23a1,1,0,0,0,1,1h8a1,1,0,0,1,0,2Z"
                                                        fill="#00ADA3" />
                                                    <path d="M7,22H5a1,1,0,0,1,0-2H7a1,1,0,0,1,0,2Z" fill="#00ADA3" />
                                                    <path
                                                        d="M23,32a9,9,0,1,1,9-9A9,9,0,0,1,23,32Zm0-16a7,7,0,1,0,7,7A7,7,0,0,0,23,16Z"
                                                        fill="#00ADA3" />
                                                    <circle class="cls-1" cx="23" cy="23" r="5" fill="#FFFA6F"></circle>
                                                    <path
                                                        d="M23,26a1,1,0,0,1-.71-.29l-2-2a1,1,0,0,1,1.42-1.42L23,23.59l3.29-3.3a1,1,0,0,1,1.42,1.42l-4,4A1,1,0,0,1,23,26Z"
                                                        fill="#00ADA3" />
                                                </g>
                                            </svg>

                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="widgetChart3" class="chart-primary"></div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <!-- <div class="swiper-slide">
                                <div class="card card-box bg-info">
                                    <div class="card-header border-0 pb-0">
                                        <div class="chart-num">
                                            <p>
                                                <i class="fa-solid fa-sort-down me-2"></i>
                                                Confirmed Booking
                                            </p>
                                            <h2 class="font-w600 mb-0">{{ $confirmBookings }}</h2>
                                        </div>
                                        <div class="dlab-swiper-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45"
                                                viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="12" fill="#ffffff" />

                                                <g transform="translate(6.6,6.6) scale(0.45)">
                                                    <path
                                                        d="M10,1h6a0,0,0,0,1,0,0V6.13a.87.87,0,0,1-.87.87H10.87A.87.87,0,0,1,10,6.13V1A0,0,0,0,1,10,1Z"
                                                        fill="#00ADA3" />
                                                    <path
                                                        d="M11,26H3a3,3,0,0,1-3-3V3A3,3,0,0,1,3,0H23a3,3,0,0,1,3,3v8a1,1,0,0,1-2,0V3a1,1,0,0,0-1-1H3A1,1,0,0,0,2,3V23a1,1,0,0,0,1,1h8a1,1,0,0,1,0,2Z"
                                                        fill="#00ADA3" />
                                                    <path d="M7,22H5a1,1,0,0,1,0-2H7a1,1,0,0,1,0,2Z" fill="#00ADA3" />
                                                    <path
                                                        d="M23,32a9,9,0,1,1,9-9A9,9,0,0,1,23,32Zm0-16a7,7,0,1,0,7,7A7,7,0,0,0,23,16Z"
                                                        fill="#00ADA3" />
                                                    <circle class="cls-1" cx="23" cy="23" r="5" fill="#FFFA6F"></circle>
                                                    <path
                                                        d="M23,26a1,1,0,0,1-.71-.29l-2-2a1,1,0,0,1,1.42-1.42L23,23.59l3.29-3.3a1,1,0,0,1,1.42,1.42l-4,4A1,1,0,0,1,23,26Z"
                                                        fill="#00ADA3" />
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="widgetChart3" class="chart-primary"></div>
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="swiper-slide">
                                <div class="card card-box bg-danger">
                                    <div class="card-header border-0 pb-0">
                                        <div class="chart-num">
                                            <p>
                                                <i class="fa-solid fa-sort-down me-2"></i>
                                                Pending Bookings
                                            </p>
                                            <h2 class="font-w600 mb-0">{{ $pendingBookings }}</h2>
                                        </div>
                                        <div class="dlab-swiper-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="45"
                                                viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="12" fill="#ffffff" />

                                                <g transform="translate(6.6,6.6) scale(0.45)">
                                                    <path
                                                        d="M10,1h6a0,0,0,0,1,0,0V6.13a.87.87,0,0,1-.87.87H10.87A.87.87,0,0,1,10,6.13V1A0,0,0,0,1,10,1Z"
                                                        fill="#fd5353" />
                                                    <path
                                                        d="M11,26H3a3,3,0,0,1-3-3V3A3,3,0,0,1,3,0H23a3,3,0,0,1,3,3v8a1,1,0,0,1-2,0V3a1,1,0,0,0-1-1H3A1,1,0,0,0,2,3V23a1,1,0,0,0,1,1h8a1,1,0,0,1,0,2Z"
                                                        fill="#fd5353" />
                                                    <path d="M7,22H5a1,1,0,0,1,0-2H7a1,1,0,0,1,0,2Z" fill="#fd5353" />
                                                    <path
                                                        d="M23,32a9,9,0,1,1,9-9A9,9,0,0,1,23,32Zm0-16a7,7,0,1,0,7,7A7,7,0,0,0,23,16Z"
                                                        fill="#fd5353" />
                                                    <circle class="cls-1" cx="23" cy="23" r="5" fill="#e59ff1"></circle>
                                                    <path
                                                        d="M24.41,23l1.3-1.29a1,1,0,0,0-1.42-1.42L23,21.59l-1.29-1.3a1,1,0,0,0-1.42,1.42L21.59,23l-1.3,1.29a1,1,0,0,0,0,1.42,1,1,0,0,0,1.42,0L23,24.41l1.29,1.3a1,1,0,0,0,1.42,0,1,1,0,0,0,0-1.42Z"
                                                        fill="#fd5353" />
                                                </g>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div id="widgetChart4" class="chart-primary"></div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="row">

                    <!-- <div class="col-xl-6">
                        <div class="card crypto-chart">
                            <div class="card-header pb-0 border-0 flex-wrap">
                                <div class="mb-0">
                                    <h4 class="card-title">Shirt Size Distribution of the First 100 Confirmed Bookings</h4>
                                </div>
                            </div>
                            <div class="card-body pt2">
                                <div class="chart-point">
                                    <div class="check-point-area">
                                        <canvas id="extra_info_chart" style="width: 200px; height: 300px;"></canvas>
                                    </div>
                                    <ul class="chart-point-list">
                                        @php
                                            $bgColors = [
                                                'rgb(210, 243, 25)',
                                                'rgba(100, 24, 195, 1)',
                                                'rgba(255, 62, 62, 1)',
                                                'rgba(235, 129, 83, 1)',
                                                'rgba(75, 192, 192, 1)',
                                                'rgba(153, 102, 255, 1)',
                                            ];
                                        @endphp

                                        @foreach($shirtSizeData as $size => $count)
                                            @php $i = $loop->index; @endphp
                                            <li>
                                                <i class="fa fa-circle me-1"
                                                    style="color: {{ $bgColors[$i % count($bgColors)] }}"></i>
                                                {{ $size }} - {{ $count }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="col-xl-6">
                        <div class="card crypto-chart">
                            <div class="card-header pb-0 border-0 flex-wrap">
                                <div class="mb-0">
                                    <h4 class="card-title">Sales Statistics</h4>
                                    <p>Monthly confirmed booking income per top 3 events</p>
                                </div>
                            </div>
                            <div class="card-body pt-2">
                                <div id="marketChart"></div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-xl-6">
                                                                                                <div class="card market-chart">
                                                                                                    <div class="card-header border-0 pb-0 flex-wrap">
                                                                                                        <div class="mb-0">
                                                                                                            <h4 class="card-title">Market Overview</h4>
                                                                                                            <p>Lorem ipsum dolor sit amet, consectetur</p>
                                                                                                        </div>
                                                                                                        <a href="javascript:void(0);" class="btn-link text-primary get-report mb-2">
                                                                                                            <svg class="me-2" width="16" height="16" viewBox="0 0 24 24"
                                                                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                                                <path
                                                                                                                    d="M24 22.5C24 23.3284 23.3284 24 22.5 24H1.5C0.671578 24 0 23.3284 0 22.5C0 21.6716 0.671578 21 1.5 21H22.5C23.3284 21 24 21.6716 24 22.5ZM10.9394 17.7482C11.2323 18.0411 11.6161 18.1875 12 18.1875C12.3838 18.1875 12.7678 18.0411 13.0606 17.7482L18.3752 12.4336C18.961 11.8478 18.961 10.8981 18.3752 10.3123C17.7894 9.72652 16.8397 9.72652 16.2539 10.3123L13.5 13.0662V1.5C13.5 0.671578 12.8284 0 12 0C11.1716 0 10.5 0.671578 10.5 1.5V13.0662L7.74609 10.3123C7.1603 9.72652 6.21056 9.72652 5.62477 10.3123C5.03897 10.8981 5.03897 11.8478 5.62477 12.4336L10.9394 17.7482Z"
                                                                                                                    fill="var(--primary)"></path>
                                                                                                            </svg>

                                                                                                            Get Report</a>
                                                                                                    </div>
                                                                                                    <div class="card-body pt-2">
                                                                                                        <div class="d-flex justify-content-between flex-wrap">
                                                                                                            <div class="d-flex align-items-center mb-2">
                                                                                                                <h5 class="me-2 font-w600 m-0"><span class="text-success me-2">BUY</span> $5,673
                                                                                                                </h5>
                                                                                                                <h5 class="ms-2 font-w600 m-0"><span class="text-danger me-2">SELL</span> $5,982
                                                                                                                </h5>
                                                                                                            </div>
                                                                                                            <ul class="nav nav-pills justify-content-between mb-2" id="myTab" role="tablist">
                                                                                                                <li class="nav-item" role="presentation">
                                                                                                                    <a class="nav-link active" id="market-week-tab" href="#week"
                                                                                                                        data-bs-toggle="tab" data-bs-target="#market-week">Week</a>
                                                                                                                </li>
                                                                                                                <li class="nav-item" role="presentation">
                                                                                                                    <a class="nav-link" id="market-month-tab" data-bs-toggle="tab"
                                                                                                                        href="#month" data-bs-target="#market-month">month</a>
                                                                                                                </li>
                                                                                                                <li class="nav-item" role="presentation">
                                                                                                                    <a class="nav-link" id="market-year-tab" data-bs-toggle="tab" href="#year"
                                                                                                                        data-bs-target="#market-year">year</a>
                                                                                                                </li>
                                                                                                            </ul>
                                                                                                        </div>
                                                                                                        <div id="marketChart2" class="market-line"></div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div> -->
                </div>
                <!-- <div class="col-lg-12">
                                                                                            <div class="card transaction-table">
                                                                                                <div class="card-header border-0 flex-wrap pb-0">
                                                                                                    <div class="mb-2">
                                                                                                        <h4 class="card-title">Recent Transactions</h4>
                                                                                                        <p class="mb-sm-3 mb-0">Lorem ipsum dolor sit amet, consectetur</p>
                                                                                                    </div>
                                                                                                    <ul class="float-end nav nav-pills mb-2">
                                                                                                        <li class="nav-item" role="presentation">
                                                                                                            <button class="nav-link active" id="Week-tab" data-bs-toggle="tab"
                                                                                                                data-bs-target="#Week" type="button" role="tab" aria-controls="month"
                                                                                                                aria-selected="true">Week</button>
                                                                                                        </li>
                                                                                                        <li class="nav-item" role="presentation">
                                                                                                            <button class="nav-link" id="month-tab" data-bs-toggle="tab" data-bs-target="#month"
                                                                                                                type="button" role="tab" aria-controls="month"
                                                                                                                aria-selected="false">Month</button>
                                                                                                        </li>
                                                                                                        <li class="nav-item" role="presentation">
                                                                                                            <button class="nav-link" id="year-tab" data-bs-toggle="tab" data-bs-target="#year"
                                                                                                                type="button" role="tab" aria-controls="year"
                                                                                                                aria-selected="false">Year</button>
                                                                                                        </li>
                                                                                                    </ul>
                                                                                                </div>
                                                                                                <div class="card-body p-0">
                                                                                                    <div class="tab-content" id="myTabContent1">
                                                                                                        <div class="tab-pane fade show active" id="Week" role="tabpanel"
                                                                                                            aria-labelledby="Week-tab">
                                                                                                            <div class="table-responsive">
                                                                                                                <table class="table table-responsive-md">
                                                                                                                    <thead>
                                                                                                                        <tr>
                                                                                                                            <th>
                                                                                                                                #
                                                                                                                            </th>
                                                                                                                            <th>Transaction ID</th>
                                                                                                                            <th>Date</th>
                                                                                                                            <th>From</th>
                                                                                                                            <th>To</th>
                                                                                                                            <th>Coin</th>
                                                                                                                            <th>Amount</th>
                                                                                                                            <th class="text-end">Status</th>
                                                                                                                        </tr>
                                                                                                                    </thead>
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <svg class="arrow svg-main-icon"
                                                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                                                                                        fill-rule="evenodd">
                                                                                                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                                                                                                        <rect fill="#fff" opacity="0.3"
                                                                                                                                            transform="translate(11.646447, 12.853553) rotate(-315.000000) translate(-11.646447, -12.853553) "
                                                                                                                                            x="10.6464466" y="5.85355339" width="2"
                                                                                                                                            height="14" rx="1" />
                                                                                                                                        <path
                                                                                                                                            d="M8.1109127,8.90380592 C7.55862795,8.90380592 7.1109127,8.45609067 7.1109127,7.90380592 C7.1109127,7.35152117 7.55862795,6.90380592 8.1109127,6.90380592 L16.5961941,6.90380592 C17.1315855,6.90380592 17.5719943,7.32548256 17.5952502,7.8603687 L17.9488036,15.9920967 C17.9727933,16.5438602 17.5449482,17.0106003 16.9931847,17.0345901 C16.4414212,17.0585798 15.974681,16.6307346 15.9506913,16.0789711 L15.6387276,8.90380592 L8.1109127,8.90380592 Z"
                                                                                                                                            fill="#fff" fill-rule="nonzero" />
                                                                                                                                    </g>
                                                                                                                                </svg>
                                                                                                                            </td>
                                                                                                                            <td>#12415346563475</td>
                                                                                                                            <td>01 August 2020</td>
                                                                                                                            <td>Thomas</td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/avatar/1.jpg') }}" class=" me-2" width="30"
                                                                                                                                        alt=""> <span class="w-space-no">Dr.
                                                                                                                                        Jackson</span></div>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/svg/btc.svg') }}" alt=""
                                                                                                                                        class="me-2 img-btc">Bitcoin</div>
                                                                                                                            </td>
                                                                                                                            <td class="text-success font-w600">+$5,553</td>
                                                                                                                            <td class="text-end">
                                                                                                                                <div class="badge badge-sm badge-success">COMPLETED</div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <svg class="arrow style-1 svg-main-icon"
                                                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                                                                                        fill-rule="evenodd">
                                                                                                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                                                                                                        <rect fill="#fff" opacity="0.3"
                                                                                                                                            transform="translate(11.646447, 12.853553) rotate(-315.000000) translate(-11.646447, -12.853553) "
                                                                                                                                            x="10.6464466" y="5.85355339" width="2"
                                                                                                                                            height="14" rx="1" />
                                                                                                                                        <path
                                                                                                                                            d="M8.1109127,8.90380592 C7.55862795,8.90380592 7.1109127,8.45609067 7.1109127,7.90380592 C7.1109127,7.35152117 7.55862795,6.90380592 8.1109127,6.90380592 L16.5961941,6.90380592 C17.1315855,6.90380592 17.5719943,7.32548256 17.5952502,7.8603687 L17.9488036,15.9920967 C17.9727933,16.5438602 17.5449482,17.0106003 16.9931847,17.0345901 C16.4414212,17.0585798 15.974681,16.6307346 15.9506913,16.0789711 L15.6387276,8.90380592 L8.1109127,8.90380592 Z"
                                                                                                                                            fill="#fff" fill-rule="nonzero" />
                                                                                                                                    </g>
                                                                                                                                </svg>
                                                                                                                            </td>
                                                                                                                            <td>#12415346563475</td>
                                                                                                                            <td>01 August 2020</td>
                                                                                                                            <td>Thomas</td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/avatar/2.jpg') }}" class=" me-2" width="30"
                                                                                                                                        alt=""> <span class="w-space-no">Dr.
                                                                                                                                        Jackson</span></div>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/svg/btc.svg') }}" alt=""
                                                                                                                                        class="me-2 img-btc">Bitcoin</div>
                                                                                                                            </td>
                                                                                                                            <td class="text-success font-w600">+$5,553</td>
                                                                                                                            <td class="text-end">
                                                                                                                                <div class="badge badge-sm badge-warning">PENDING</div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <svg class="arrow style-2 svg-main-icon"
                                                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                                                                                        fill-rule="evenodd">
                                                                                                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                                                                                                        <rect fill="#fff" opacity="0.3"
                                                                                                                                            transform="translate(11.646447, 12.853553) rotate(-315.000000) translate(-11.646447, -12.853553) "
                                                                                                                                            x="10.6464466" y="5.85355339" width="2"
                                                                                                                                            height="14" rx="1" />
                                                                                                                                        <path
                                                                                                                                            d="M8.1109127,8.90380592 C7.55862795,8.90380592 7.1109127,8.45609067 7.1109127,7.90380592 C7.1109127,7.35152117 7.55862795,6.90380592 8.1109127,6.90380592 L16.5961941,6.90380592 C17.1315855,6.90380592 17.5719943,7.32548256 17.5952502,7.8603687 L17.9488036,15.9920967 C17.9727933,16.5438602 17.5449482,17.0106003 16.9931847,17.0345901 C16.4414212,17.0585798 15.974681,16.6307346 15.9506913,16.0789711 L15.6387276,8.90380592 L8.1109127,8.90380592 Z"
                                                                                                                                            fill="#fff" fill-rule="nonzero" />
                                                                                                                                    </g>
                                                                                                                                </svg>
                                                                                                                            </td>
                                                                                                                            <td>#12415346563475</td>
                                                                                                                            <td>01 August 2020</td>
                                                                                                                            <td>Thomas</td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/avatar/3.jpg') }}" class="me-2" width="30"
                                                                                                                                        alt=""> <span class="w-space-no">Dr.
                                                                                                                                        Jackson</span></div>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/svg/btc.svg') }}" alt=""
                                                                                                                                        class="me-2 img-btc">Bitcoin</div>
                                                                                                                            </td>
                                                                                                                            <td class="text-danger font-w600">+$5,553</td>
                                                                                                                            <td class="text-end">
                                                                                                                                <div class="badge badge-sm badge-danger">CANCEL</div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="tab-pane fade show" id="month" role="tabpanel"
                                                                                                            aria-labelledby="month-tab">
                                                                                                            <div class="table-responsive">
                                                                                                                <table class="table table-responsive-md">
                                                                                                                    <thead>
                                                                                                                        <tr>
                                                                                                                            <th>
                                                                                                                                #
                                                                                                                            </th>
                                                                                                                            <th>Transaction ID</th>
                                                                                                                            <th>Date</th>
                                                                                                                            <th>From</th>
                                                                                                                            <th>To</th>
                                                                                                                            <th>Coin</th>
                                                                                                                            <th>Amount</th>
                                                                                                                            <th class="text-end">Status</th>
                                                                                                                        </tr>
                                                                                                                    </thead>
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <svg class="arrow style-1 svg-main-icon"
                                                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                                                                                        fill-rule="evenodd">
                                                                                                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                                                                                                        <rect fill="#fff" opacity="0.3"
                                                                                                                                            transform="translate(11.646447, 12.853553) rotate(-315.000000) translate(-11.646447, -12.853553) "
                                                                                                                                            x="10.6464466" y="5.85355339" width="2"
                                                                                                                                            height="14" rx="1" />
                                                                                                                                        <path
                                                                                                                                            d="M8.1109127,8.90380592 C7.55862795,8.90380592 7.1109127,8.45609067 7.1109127,7.90380592 C7.1109127,7.35152117 7.55862795,6.90380592 8.1109127,6.90380592 L16.5961941,6.90380592 C17.1315855,6.90380592 17.5719943,7.32548256 17.5952502,7.8603687 L17.9488036,15.9920967 C17.9727933,16.5438602 17.5449482,17.0106003 16.9931847,17.0345901 C16.4414212,17.0585798 15.974681,16.6307346 15.9506913,16.0789711 L15.6387276,8.90380592 L8.1109127,8.90380592 Z"
                                                                                                                                            fill="#fff" fill-rule="nonzero" />
                                                                                                                                    </g>
                                                                                                                                </svg>
                                                                                                                            </td>
                                                                                                                            <td>#12415346563475</td>
                                                                                                                            <td>01 August 2020</td>
                                                                                                                            <td>Thomas</td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/avatar/2.jpg') }}" class=" me-2" width="24"
                                                                                                                                        alt=""> <span class="w-space-no">Dr.
                                                                                                                                        Jackson</span></div>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/svg/btc.svg') }}" alt=""
                                                                                                                                        class="me-2 img-btc">Bitcoin</div>
                                                                                                                            </td>
                                                                                                                            <td>+$5,553</td>
                                                                                                                            <td class="text-end">
                                                                                                                                <div class="badge badge-sm badge-warning">PENDING</div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <svg class="arrow svg-main-icon"
                                                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                                                                                        fill-rule="evenodd">
                                                                                                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                                                                                                        <rect fill="#fff" opacity="0.3"
                                                                                                                                            transform="translate(11.646447, 12.853553) rotate(-315.000000) translate(-11.646447, -12.853553) "
                                                                                                                                            x="10.6464466" y="5.85355339" width="2"
                                                                                                                                            height="14" rx="1" />
                                                                                                                                        <path
                                                                                                                                            d="M8.1109127,8.90380592 C7.55862795,8.90380592 7.1109127,8.45609067 7.1109127,7.90380592 C7.1109127,7.35152117 7.55862795,6.90380592 8.1109127,6.90380592 L16.5961941,6.90380592 C17.1315855,6.90380592 17.5719943,7.32548256 17.5952502,7.8603687 L17.9488036,15.9920967 C17.9727933,16.5438602 17.5449482,17.0106003 16.9931847,17.0345901 C16.4414212,17.0585798 15.974681,16.6307346 15.9506913,16.0789711 L15.6387276,8.90380592 L8.1109127,8.90380592 Z"
                                                                                                                                            fill="#fff" fill-rule="nonzero" />
                                                                                                                                    </g>
                                                                                                                                </svg>
                                                                                                                            </td>
                                                                                                                            <td>#12415346563475</td>
                                                                                                                            <td>01 August 2020</td>
                                                                                                                            <td>Thomas</td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/avatar/1.jpg') }}" class=" me-2" width="24"
                                                                                                                                        alt=""> <span class="w-space-no">Dr.
                                                                                                                                        Jackson</span></div>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/svg/btc.svg') }}" alt=""
                                                                                                                                        class="me-2 img-btc">Bitcoin</div>
                                                                                                                            </td>
                                                                                                                            <td>+$5,553</td>
                                                                                                                            <td class="text-end">
                                                                                                                                <div class="badge badge-sm badge-success">COMPLETED</div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <svg class="arrow style-2 svg-main-icon"
                                                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                                                                                        fill-rule="evenodd">
                                                                                                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                                                                                                        <rect fill="#fff" opacity="0.3"
                                                                                                                                            transform="translate(11.646447, 12.853553) rotate(-315.000000) translate(-11.646447, -12.853553) "
                                                                                                                                            x="10.6464466" y="5.85355339" width="2"
                                                                                                                                            height="14" rx="1" />
                                                                                                                                        <path
                                                                                                                                            d="M8.1109127,8.90380592 C7.55862795,8.90380592 7.1109127,8.45609067 7.1109127,7.90380592 C7.1109127,7.35152117 7.55862795,6.90380592 8.1109127,6.90380592 L16.5961941,6.90380592 C17.1315855,6.90380592 17.5719943,7.32548256 17.5952502,7.8603687 L17.9488036,15.9920967 C17.9727933,16.5438602 17.5449482,17.0106003 16.9931847,17.0345901 C16.4414212,17.0585798 15.974681,16.6307346 15.9506913,16.0789711 L15.6387276,8.90380592 L8.1109127,8.90380592 Z"
                                                                                                                                            fill="#fff" fill-rule="nonzero" />
                                                                                                                                    </g>
                                                                                                                                </svg>
                                                                                                                            </td>
                                                                                                                            <td>#12415346563475</td>
                                                                                                                            <td>01 August 2020</td>
                                                                                                                            <td>Thomas</td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/avatar/3.jpg') }}" class=" me-2" width="24"
                                                                                                                                        alt=""> <span class="w-space-no">Dr.
                                                                                                                                        Jackson</span></div>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/svg/btc.svg') }}" alt=""
                                                                                                                                        class="me-2 img-btc">Bitcoin</div>
                                                                                                                            </td>
                                                                                                                            <td>+$5,553</td>
                                                                                                                            <td class="text-end">
                                                                                                                                <div class="badge badge-sm badge-danger">CANCEL</div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="tab-pane fade show" id="year" role="tabpanel"
                                                                                                            aria-labelledby="year-tab">
                                                                                                            <div class="table-responsive">
                                                                                                                <table class="table table-responsive-md">
                                                                                                                    <thead>
                                                                                                                        <tr>
                                                                                                                            <th>
                                                                                                                                #
                                                                                                                            </th>
                                                                                                                            <th>Transaction ID</th>
                                                                                                                            <th>Date</th>
                                                                                                                            <th>From</th>
                                                                                                                            <th>To</th>
                                                                                                                            <th>Coin</th>
                                                                                                                            <th>Amount</th>
                                                                                                                            <th class="text-end">Status</th>
                                                                                                                        </tr>
                                                                                                                    </thead>
                                                                                                                    <tbody>
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <svg class="arrow svg-main-icon"
                                                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                                                                                        fill-rule="evenodd">
                                                                                                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                                                                                                        <rect fill="#fff" opacity="0.3"
                                                                                                                                            transform="translate(11.646447, 12.853553) rotate(-315.000000) translate(-11.646447, -12.853553) "
                                                                                                                                            x="10.6464466" y="5.85355339" width="2"
                                                                                                                                            height="14" rx="1" />
                                                                                                                                        <path
                                                                                                                                            d="M8.1109127,8.90380592 C7.55862795,8.90380592 7.1109127,8.45609067 7.1109127,7.90380592 C7.1109127,7.35152117 7.55862795,6.90380592 8.1109127,6.90380592 L16.5961941,6.90380592 C17.1315855,6.90380592 17.5719943,7.32548256 17.5952502,7.8603687 L17.9488036,15.9920967 C17.9727933,16.5438602 17.5449482,17.0106003 16.9931847,17.0345901 C16.4414212,17.0585798 15.974681,16.6307346 15.9506913,16.0789711 L15.6387276,8.90380592 L8.1109127,8.90380592 Z"
                                                                                                                                            fill="#fff" fill-rule="nonzero" />
                                                                                                                                    </g>
                                                                                                                                </svg>
                                                                                                                            </td>
                                                                                                                            <td>#12415346563475</td>
                                                                                                                            <td>01 August 2020</td>
                                                                                                                            <td>Thomas</td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/avatar/1.jpg') }}" class=" me-2" width="24"
                                                                                                                                        alt=""> <span class="w-space-no">Dr.
                                                                                                                                        Jackson</span></div>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/svg/btc.svg') }}" alt=""
                                                                                                                                        class="me-2 img-btc">Bitcoin</div>
                                                                                                                            </td>
                                                                                                                            <td>+$5,553</td>
                                                                                                                            <td class="text-end">
                                                                                                                                <div class="badge badge-sm badge-success">COMPLETED</div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <svg class="arrow style-1 svg-main-icon"
                                                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                                                                                        fill-rule="evenodd">
                                                                                                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                                                                                                        <rect fill="#fff" opacity="0.3"
                                                                                                                                            transform="translate(11.646447, 12.853553) rotate(-315.000000) translate(-11.646447, -12.853553) "
                                                                                                                                            x="10.6464466" y="5.85355339" width="2"
                                                                                                                                            height="14" rx="1" />
                                                                                                                                        <path
                                                                                                                                            d="M8.1109127,8.90380592 C7.55862795,8.90380592 7.1109127,8.45609067 7.1109127,7.90380592 C7.1109127,7.35152117 7.55862795,6.90380592 8.1109127,6.90380592 L16.5961941,6.90380592 C17.1315855,6.90380592 17.5719943,7.32548256 17.5952502,7.8603687 L17.9488036,15.9920967 C17.9727933,16.5438602 17.5449482,17.0106003 16.9931847,17.0345901 C16.4414212,17.0585798 15.974681,16.6307346 15.9506913,16.0789711 L15.6387276,8.90380592 L8.1109127,8.90380592 Z"
                                                                                                                                            fill="#fff" fill-rule="nonzero" />
                                                                                                                                    </g>
                                                                                                                                </svg>
                                                                                                                            </td>
                                                                                                                            <td>#12415346563475</td>
                                                                                                                            <td>01 August 2020</td>
                                                                                                                            <td>Thomas</td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/avatar/2.jpg') }}" class=" me-2" width="24"
                                                                                                                                        alt=""> <span class="w-space-no">Dr.
                                                                                                                                        Jackson</span></div>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/svg/btc.svg') }}" alt=""
                                                                                                                                        class="me-2 img-btc">Bitcoin</div>
                                                                                                                            </td>
                                                                                                                            <td>+$5,553</td>
                                                                                                                            <td class="text-end">
                                                                                                                                <div class="badge badge-sm badge-warning">PENDING</div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <svg class="arrow style-2 svg-main-icon"
                                                                                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                                                                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                                                                                                                    <g stroke="none" stroke-width="1" fill="none"
                                                                                                                                        fill-rule="evenodd">
                                                                                                                                        <polygon points="0 0 24 0 24 24 0 24" />
                                                                                                                                        <rect fill="#fff" opacity="0.3"
                                                                                                                                            transform="translate(11.646447, 12.853553) rotate(-315.000000) translate(-11.646447, -12.853553) "
                                                                                                                                            x="10.6464466" y="5.85355339" width="2"
                                                                                                                                            height="14" rx="1" />
                                                                                                                                        <path
                                                                                                                                            d="M8.1109127,8.90380592 C7.55862795,8.90380592 7.1109127,8.45609067 7.1109127,7.90380592 C7.1109127,7.35152117 7.55862795,6.90380592 8.1109127,6.90380592 L16.5961941,6.90380592 C17.1315855,6.90380592 17.5719943,7.32548256 17.5952502,7.8603687 L17.9488036,15.9920967 C17.9727933,16.5438602 17.5449482,17.0106003 16.9931847,17.0345901 C16.4414212,17.0585798 15.974681,16.6307346 15.9506913,16.0789711 L15.6387276,8.90380592 L8.1109127,8.90380592 Z"
                                                                                                                                            fill="#fff" fill-rule="nonzero" />
                                                                                                                                    </g>
                                                                                                                                </svg>
                                                                                                                            </td>
                                                                                                                            <td>#12415346563475</td>
                                                                                                                            <td>01 August 2020</td>
                                                                                                                            <td>Thomas</td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/avatar/3.jpg') }}" class=" me-2" width="24"
                                                                                                                                        alt=""> <span class="w-space-no">Dr.
                                                                                                                                        Jackson</span></div>
                                                                                                                            </td>
                                                                                                                            <td>
                                                                                                                                <div class="d-flex align-items-center"><img
                                                                                                                                        src="{{ asset('images/svg/btc.svg') }}" alt=""
                                                                                                                                        class="me-2 img-btc">Bitcoin</div>
                                                                                                                            </td>
                                                                                                                            <td>+$5,553</td>
                                                                                                                            <td class="text-end">
                                                                                                                                <div class="badge badge-sm badge-danger">CANCEL</div>
                                                                                                                            </td>
                                                                                                                        </tr>
                                                                                                                    </tbody>
                                                                                                                </table>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div> -->
            </div>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title">Make Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Payment method</label>
                    <div>
                        <select class="image-select default-select dashboard-select w-100 mb-3" aria-label="Default">
                            <option selected>Open this select menu</option>
                            <option value="1">Bank Card</option>
                            <option value="2">Online</option>
                            <option value="3">Cash On Time</option>
                        </select>
                    </div>
                    <label class="form-label">Amount</label>
                    <input type="number" class="form-control mb-3" id="exampleInputEmail4" placeholder="Rupee">
                    <label class="form-label">Card Holder Name</label>
                    <input type="number" class="form-control mb-3" id="exampleInputEmail5" placeholder="Amount">
                    <label class="form-label">Card Name</label>
                    <input type="text" class="form-control mb-3" id="exampleInputEmail6" placeholder="Amount">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title">Make Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Seller Mobile Number</label>
                        <input type="number" class="form-control mb-3" id="exampleInputEmail1" placeholder="Number">
                        <label class="form-label">Product Name</label>
                        <input type="text" class="form-control mb-3" id="exampleInputEmail2" placeholder=" Name">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control mb-3" id="exampleInputEmail3" placeholder="Amount">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
    <script>
        jQuery(document).ready(function () {
            setTimeout(function () {
                dlabSettingsOptions.version = 'light';
                new dlabSettings(dlabSettingsOptions);
                setCookie('version', 'light');
            }, 1500)
        });
    </script>
    <script>
        window.salesChartData = @json($salesChartData);
        window.$shirtSizeData = @json($shirtSizeData); // Optional, for debugging
    </script>

@endpush