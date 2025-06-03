<div class="dlabnav {{ in_array($page,array('dashboard','dashboard_2')) ? 'follow-info' : '' }}">
    <div class="feature-box {{ in_array($page,array('dashboard','dashboard_2')) ? '' : 'style-3' }}">
        <div class="wallet-box">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="50px" height="50px"
                viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24" />
                    <circle fill="#fff" opacity="0.3" cx="20.5" cy="12.5" r="1.5" />
                    <rect fill="#fff" opacity="0.3"
                        transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) "
                        x="3" y="3" width="18" height="7" rx="1" />
                    <path
                        d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z"
                        fill="#fff" />
                </g>
            </svg>
            <div class="ms-3">
                <h4 class="text-white mb-0 d-block">$2353.25</h4>
                <small>Withdraw Money</small>
            </div>
        </div>
        @if (in_array($page,array('dashboard','dashboard_2')))
            <div class="d-flex justify-content-center align-items-center">
                <div class="item-1">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px"
                        height="40px" viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24" />
                            <path
                                d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z"
                                fill="#fff" fill-rule="nonzero" opacity="0.3" />
                            <path
                                d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z"
                                fill="#fff" fill-rule="nonzero" />
                        </g>
                    </svg>
                    <h4 class="mb-0 text-white"><span class="counter">2023</span>k</h4>
                    <small>Followers</small>
                </div>
                <div class="item-1">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px"
                        height="40px" viewBox="0 0 24 24" version="1.1" class="svg-main-icon">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24" />
                            <path
                                d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                                fill="#fff" fill-rule="nonzero" opacity="0.3" />
                            <path
                                d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                                fill="#fff" fill-rule="nonzero" />
                        </g>
                    </svg>
                    <h4 class="mb-0 text-white"><span class="counter">2024</span>k</h4>
                    <small>Following</small>
                </div>
            </div>
        @endif    
    </div>
    <span class="main-menu">Main Menu</span>
    <div class="menu-scroll">
        <div class="dlabnav-scroll">
            <ul class="metismenu" id="menu">
                <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                        <i class="material-symbols-outlined">dashboard</i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('dashboard') }}">Dashboard Light</a></li>
                        <li><a href="{{ route('dashboard_2') }}">Dashboard Dark</a></li>
                        <li><a href="{{ route('market') }}">Market</a></li>
                        <li><a href="{{ route('coin_details') }}">Coin Details</a></li>
                        <li><a href="{{ route('portofolio') }}">Portofolio</a></li>
                    </ul>

                </li>
                <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                        <i class="material-symbols-outlined">monitoring</i>
                        <span class="nav-text">Trading</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('trading_market') }}">Market</a></li>
                        <li><a href="{{ route('ico_listing') }}">Ico Listing</a></li>
                        <li><a href="{{ route('p2p') }}">P2P</a></li>
                        <li><a href="{{ route('future') }}">Future</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                        <i class="material-symbols-outlined">monetization_on</i>
                        <span class="nav-text">Crypto</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('market_watch') }}">Market Watch </a></li>
                        <li><a href="{{ route('exchange') }}">Exchange</a></li>
                        <li><a href="{{ route('banking') }}">Banking</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">
                        <i class="material-symbols-outlined">lab_profile</i>
                        <span class="nav-text">Reports</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('history') }}">History</a></li>
                        <li><a href="{{ route('orders') }}">Orders</a></li>
                        <li><a href="{{ route('reports') }}">Report</a></li>
                        <li><a href="{{ route('user') }}">User</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                        <i class="material-symbols-outlined">apps_outage</i>
                        <span class="nav-text">Apps</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('app_profile') }}">Profile</a></li>
                        <li><a href="{{ route('edit_profile') }}">Edit Profile</a></li>
                        <li><a href="{{ route('post_details') }}">Post Details</a></li>
                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Email</a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('email_compose') }}">Compose</a></li>
                                <li><a href="{{ route('email_inbox') }}">Inbox</a></li>
                                <li><a href="{{ route('email_read') }}">Read</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('app_calender') }}">Calendar</a></li>
                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Shop</a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('ecom_product_grid') }}">Product Grid</a></li>
                                <li><a href="{{ route('ecom_product_list') }}">Product List</a></li>
                                <li><a href="{{ route('ecom_product_detail') }}">Product Details</a></li>
                                <li><a href="{{ route('ecom_product_order') }}">Order</a></li>
                                <li><a href="{{ route('ecom_checkout') }}">Checkout</a></li>
                                <li><a href="{{ route('ecom_invoice') }}">Invoice</a></li>
                                <li><a href="{{ route('ecom_customers') }}">Customers</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a class="has-arrow ai-icon" href="javascript:void(0);" aria-expanded="false">
                        <i class="fa-regular fa-gear fw-bold"></i>
                        <span class="nav-text">CMS</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('content') }}">Content</a></li>
                        <li><a href="{{ route('content_add') }}">Add Content</a></li>
                        <li><a href="{{ route('menu') }}">Menus</a></li>
                        <li><a href="{{ route('email_template') }}">Email Template</a></li>
                        <li><a href="{{ route('add_email') }}">Add Email</a></li>
                        <li><a href="{{ route('blog') }}">Blog</a></li>
                        <li><a href="{{ route('add_blog') }}">Add Blog</a></li>
                        <li><a href="{{ route('blog_category') }}">Blog Category</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                        <i class="material-symbols-outlined">donut_large</i>
                        <span class="nav-text">Charts</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('chart_flot') }}">Flot</a></li>
                        <li><a href="{{ route('chart_morris') }}">Morris</a></li>
                        <li><a href="{{ route('chart_chartjs') }}">Chartjs</a></li>
                        <li><a href="{{ route('chart_chartist') }}">Chartist</a></li>
                        <li><a href="{{ route('chart_sparkline') }}">Sparkline</a></li>
                        <li><a href="{{ route('chart_peity') }}">Peity</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">

                        <i class="material-symbols-outlined">favorite</i>
                        <span class="nav-text">Bootstrap</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('ui_accordion') }}">Accordion</a></li>
                        <li><a href="{{ route('ui_alert') }}">Alert</a></li>
                        <li><a href="{{ route('ui_badge') }}">Badge</a></li>
                        <li><a href="{{ route('ui_button') }}">Button</a></li>
                        <li><a href="{{ route('ui_modal') }}">Modal</a></li>
                        <li><a href="{{ route('ui_button_group') }}">Button Group</a></li>
                        <li><a href="{{ route('ui_list_group') }}">List Group</a></li>
                        <li><a href="{{ route('ui_card') }}">Cards</a></li>
                        <li><a href="{{ route('ui_carousel') }}">Carousel</a></li>
                        <li><a href="{{ route('ui_dropdown') }}">Dropdown</a></li>
                        <li><a href="{{ route('ui_popover') }}">Popover</a></li>
                        <li><a href="{{ route('ui_progressbar') }}">Progressbar</a></li>
                        <li><a href="{{ route('ui_tab') }}">Tab</a></li>
                        <li><a href="{{ route('ui_typography') }}">Typography</a></li>
                        <li><a href="{{ route('ui_pagination') }}">Pagination</a></li>
                        <li><a href="{{ route('ui_grid') }}">Grid</a></li>

                    </ul>
                </li>
                <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                        <i class="material-symbols-outlined">scatter_plot</i>
                        <span class="nav-text">Plugins</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('uc_select2') }}">Select 2</a></li>
                        <li><a href="{{ route('uc_nestable') }}">Nestedable</a></li>
                        <li><a href="{{ route('uc_noui_slider') }}">Noui Slider</a></li>
                        <li><a href="{{ route('uc_sweetalert') }}">Sweet Alert</a></li>
                        <li><a href="{{ route('uc_toastr') }}">Toastr</a></li>
                        <li><a href="{{ route('map_jqvmap') }}">Jqv Map</a></li>
                        <li><a href="{{ route('uc_lightgallery') }}">Light Gallery</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('widget_basic') }}" class="" aria-expanded="false">
                        <i class="material-symbols-outlined">widgets</i>
                        <span class="nav-text">Widget</span>
                    </a>
                </li>
                <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                        <i class="material-symbols-outlined">request_quote</i>
                        <span class="nav-text">Forms</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('form_element') }}">Form Elements</a></li>
                        <li><a href="{{ route('form_wizard') }}">Wizard</a></li>
                        <li><a href="{{ route('form_ckeditor') }}">CkEditor</a></li>
                        <li><a href="{{ route('form_pickers') }}">Pickers</a></li>
                        <li><a href="{{ route('form_validation') }}">Form Validate</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                        <i class="material-symbols-outlined">table_chart</i>
                        <span class="nav-text">Table</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('table_bootstrap_basic') }}">Bootstrap</a></li>
                        <li><a href="{{ route('table_datatable_basic') }}">Datatable</a></li>
                    </ul>
                </li>
                <li><a class="has-arrow " href="javascript:void(0);" aria-expanded="false">
                        <i class="material-symbols-outlined">lab_profile</i>
                        <span class="nav-text">Pages</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="{{ route('page_login') }}">Login</a></li>
                        <li><a href="{{ route('page_register') }}">Register</a></li>
                        <li><a class="has-arrow" href="javascript:void(0);" aria-expanded="false">Error</a>
                            <ul aria-expanded="false">
                                <li><a href="{{ route('page_error_400') }}">Error 400</a></li>
                                <li><a href="{{ route('page_error_403') }}">Error 403</a></li>
                                <li><a href="{{ route('page_error_404') }}">Error 404</a></li>
                                <li><a href="{{ route('page_error_500') }}">Error 500</a></li>
                                <li><a href="{{ route('page_error_503') }}">Error 503</a></li>
                            </ul>
                        </li>
                        <li><a href="{{route('page_lock_screen')}}">Lock Screen</a></li>
                        <li><a href="{{ route('empty_page') }}">Empty Page</a></li>
                    </ul>
                </li>
            </ul>
            <div class="support-box">
                <div class="media">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="40" height="46" viewBox="0 0 40 46">
                            <image id="headphones_1_" data-name="headphones (1)" width="40" height="46"
                                xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAuCAYAAABap1twAAAD90lEQVRYhdWZa4hVVRTHf3fUbOjJkGaSHzITShSitJjsTRQZZga9MSGoDxYmfesBamBp9GWo+VDSe7KXlRWEhUVRNllQiCVMhGXJaAk1Fo1G8pc9rBOL3b7nPs493ukPG9Z+rf2/a5+91tr7ViRRABOAM4DpwFTgRFP1K7ADGAC2A4PNLjG2yXnXANcD3cCUGmMDuX7gNSt/N7RSsGCd5UhJKyTtVPPYLWmNpOPqXbfeLV4ErAYmJfrCdm4Bvgf2ht8MdAGnArOBkxJzfgPuB3qLWnCcpHUJe30r6R5JJ9RhhWMlLZH0VULP29ZfdX6e4kmStkUKv5N0ixszxRbvk7RZ0nYr/ZJelrRM0jQ3foGkrZHOH6IxdRGcIGkwUtTj+hcbkXqxQ9Kdbv7D0bw/JE1thOBApGCR67s3h9RBK9XQF1nTY4+kzphLR+KzfBM4zdUvB55z9U4nBxfypLmd4A8nWzkduBp4Atjlxp8crXOOq08E3q91SG6IftXNCeuOkfSgpKUm1zokHZLuktQrqSvRf2W05tK8LR52A3sb8JFFy8qIZGeK4H1uwK7DSC4r/tA9liLorXddGwheHFlxnCfoT9RgG8ilrHi73Cm+zZ2bnprhpzw86jSH8DoSi4+3ONplHTOBbW0iGNzQTyYfAKYFC17gyO1vI7mAn4EfTR4PXNhhmUWGDe3j9i8+cHIlS7dCJDgTWAP82V5+Iyldj2Xjy4um/KUjFYtHFbI7yY3AKY7YMPAF8MlhIns2cB5wlGsLicjTwTnenZMerZc0tmTn/EzO+g+ELZ6b88sWAhtLtFwfcGtO/6zYzWwGHgHWubZLgEtLIDcLuMnV37C1fU44FN+LXwQeN3kfcIfJ84BNLSY4z8mvA9eavAC4LOuIT/FEJ3/j5DJO+5gqa032g/IWPsbJ/7SOV1Ln0U6u+EGj3g+ORoL/Pwse4ere5fzu5GZfwfLgdQ5V4TBCcL+re6ft76xlY3YVDiN3kvlReHlJ0gtR21UlhLg5ibAah7212eCNOfHw8xLj8Fs56wY8lR2SK4D3Etv4qYW6shCeR97N0a04Yb3IvoFA/LPkW0l1zLT5Ye5HwNcNzA33ovOB3cAyYIa1r2zVVvUltufVJvSE1G6f09HdCnKv5HxD7zSgp1vSkJu7SQ28UVfDWcCXrm+t3WeXuLaQQu2JkoMMBy2LDnrmu3bZ5emXotZ7yLsE176qxunMwwFJ52a6ioY6Hw2GnfxXk/rW20Nof9ZQdIvDyfvY1Vcb0eWu7Xn7e6KSmF+xT2KneYyB/wwoSDDgQ3MvKWwpGjJbkc2E9DyQjBHuN9WI141WviyE21n47y4gWO7Zwpk4cAiPK9af4ZZaXAAAAABJRU5ErkJggg==" />
                        </svg>
                    </span>

                </div>
                <div class="info">
                    <p>Jiade Crypto Trading UI Template</p>
                    <a href="javascript:void(0);" class="btn bg-white text-black w-75 btn-sm">Supports</a>
                </div>
            </div>
            <div class="copyright">
                <p><strong>Jiade Laravel Crypto Trading UI Template</strong> © <span class="current-year">2024</span> All
                    Rights Reserved</p>
                <p class="fs-12">Made with <span class="heart"></span> by DexignLab</p>
            </div>
        </div>
    </div>
</div>
