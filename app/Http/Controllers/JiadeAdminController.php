<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JiadeAdminController extends Controller
{
	public $page_description;
	
    public function index(){
        $page_title = 'Dashboard';
        $page_description = $this->page_description();
        return view('landing.index', compact('page_title', 'page_description'));
    }

    // Dashboard
    public function dashboard(){
        $page_title = 'Dashboard';
        $authUser = auth()->guard('organizer')->user()->load('user');
        $page_description = $this->page_description();
        return view('jiade.dashboard.index', compact('page_title', 'page_description', 'authUser'));
    }
	
	// Dashboard 2
	public function dashboard_2(){
        $page_title = 'Dashboard';
        $authUser = auth()->guard('organizer')->user()->load('user');
        $page_description = $this->page_description();
        return view('jiade.dashboard.index_2', compact('page_title', 'page_description', 'authUser'));
    }
	
	// order-list 
	
	public function market(){
        $page_title = 'Market ';
        $page_description = $this->page_description();
        return view('jiade.dashboard.market ', compact('page_title', 'page_description'));

    }
	
	// order-details 
	
	public function coin_details(){
        $page_title = 'Coin Details';
        $page_description = $this->page_description();
        return view('jiade.dashboard.coin_details', compact('page_title', 'page_description'));
    }

	public function portofolio (){
        $page_title = 'Portofolio';
        $page_description = $this->page_description();
        return view('jiade.dashboard.portofolio ', compact('page_title', 'page_description'));
    }
	public function trading_market  (){
        $page_title = 'Trading Market';
        $main_title = 'Tranding';
        $page_description = $this->page_description();
        return view('jiade.dashboard.trading_market  ', compact('page_title', 'page_description','main_title'));
    }
	public function ico_listing  (){
        $page_title = 'Ico Listing';
        $main_title = 'Tranding';
        $page_description = $this->page_description();
        return view('jiade.dashboard.ico_listing  ', compact('page_title', 'page_description','main_title'));
    }
	// customer list
	
	public function user(){
        $page_title = 'User';
        $main_title = 'Reports';
        $page_description = $this->page_description();
        return view('jiade.dashboard.user', compact('page_title', 'page_description','main_title'));
    }
	
	// analytics 
	
	public function celandar(){
        $page_title = 'Celandar';
        $page_description = $this->page_description();
        return view('jiade.dashboard.celandar', compact('page_title', 'page_description'));
    }

    public function p2p (){
        $page_title = 'P2p ';
        $main_title = 'Tranding';
        $page_description = $this->page_description();
        return view('jiade.dashboard.p2p ', compact('page_title', 'page_description','main_title'));
    }
	
	// Reviews 
	
	public function future(){
        $page_title = 'Future ';
        $main_title = 'Tranding';
        $page_description = $this->page_description();
        return view('jiade.dashboard.future ', compact('page_title', 'page_description','main_title'));
    }
	public function market_watch(){
        $page_title = 'Market Watch';
        $main_title = 'Cryptp';
        $page_description = $this->page_description();
        return view('jiade.dashboard.market_watch  ', compact('page_title', 'page_description','main_title'));
    }
	
	// add blog 
	
	public function exchange (){
        $page_title = 'Exchange';
        $main_title = 'Cryptp';
        $page_description = $this->page_description();
        return view('jiade.dashboard.exchange', compact('page_title', 'page_description','main_title'));
    }
	
	// add email 
	
	public function banking(){
        $page_title = 'Banking ';
        $main_title = 'Cryptp';
        $page_description = $this->page_description();
        return view('jiade.dashboard.banking ', compact('page_title', 'page_description','main_title'));
    }

    public function history (){
        $page_title = 'History ';
        $main_title = 'Reports';
        $page_description = $this->page_description();
        return view('jiade.dashboard.history ', compact('page_title', 'page_description','main_title'));
    }

    public function orders(){
        $page_title = 'Orders';
        $main_title = 'Reports';
        $page_description = $this->page_description();
        return view('jiade.dashboard.orders', compact('page_title', 'page_description','main_title'));
    }

    public function reports(){
        $page_title = 'Reports';
        $main_title = 'Reports';
        $page_description = $this->page_description();
        return view('jiade.dashboard.reports', compact('page_title', 'page_description','main_title'));
    }

    public function add_teacher(){
        $page_title = 'Add Teacher';
        $page_description = $this->page_description();
        return view('jiade.teacher.add_teacher', compact('page_title', 'page_description'));
    }
    public function add_blog(){
        $page_title = 'Add Blog';
        $main_title = 'CMS';
        $page_description = $this->page_description();
        return view('jiade.cms.add_blog', compact('page_title', 'page_description','main_title'));
    }
    public function food_details(){
        $page_title = 'Food Details';
        $page_description = $this->page_description();
        return view('jiade.food.food_details', compact('page_title', 'page_description'));
    }
	
	// app-calender 
	
	public function app_calender(){
        $page_title = 'Calender';
        $main_title = 'App';
        $page_description = $this->page_description();
        return view('jiade.app.calender', compact('page_title', 'page_description','main_title'));
    }
	
	// app-profile-1
	
	public function app_profile(){
        $page_title = 'App Profile';
        $main_title = 'App';
        $page_description = $this->page_description();
        return view('jiade.app.profile', compact('page_title', 'page_description','main_title'));
    }
	public function edit_profile(){
        $page_title = 'Edit Profile';
        $main_title = 'App';
        $page_description = $this->page_description();
        return view('jiade.app.edit_profile', compact('page_title', 'page_description','main_title'));
    }
	
	// blog
	
	public function blog(){
        $page_title = 'Blog';
        $main_title = 'CMS';
        $page_description = $this->page_description();
        return view('jiade.cms.blog', compact('page_title', 'page_description','main_title'));
    }
	public function add_email (){
        $page_title = 'Add Email ';
        $main_title = 'CMS';
        $page_description = $this->page_description();
        return view('jiade.cms.add_email ', compact('page_title', 'page_description','main_title'));
    }
	
	// add catagery
	
	public function blog_category(){
        $page_title = 'Blog Category';
        $main_title = 'CMS';
        $page_description = $this->page_description();
        return view('jiade.cms.blog_category', compact('page_title', 'page_description'));
    }
	
	// chart-chartist
	
	public function chart_chartist(){
        $page_title = 'Chartlist';
        $main_title = 'Chart';
        $page_description = $this->page_description();
        return view('jiade.chart.chartist', compact('page_title', 'page_description','main_title'));
    }
	
	// chart-chartjs
	
	public function chart_chartjs(){
        $page_title = 'Chartjs';
        $main_title = 'Chart';
        $page_description = $this->page_description();
        return view('jiade.chart.chartjs', compact('page_title', 'page_description','main_title'));
    }
	
	// chart-flot
	
	public function chart_flot(){
        $page_title = 'Flot';
        $main_title = 'Chart';
        $page_description = $this->page_description();
        return view('jiade.chart.flot', compact('page_title', 'page_description','main_title'));
    }
	
	// chart-morris
	
	public function chart_morris(){
        $page_title = 'Morris';
        $main_title = 'Chart ';
        $page_description = $this->page_description();
        return view('jiade.chart.morris', compact('page_title', 'page_description'));
    }
	
	// chart-sparkline
	
	public function chart_sparkline(){
        $page_title = 'Sparkline';
        $main_title = 'Chart';
        $page_description = $this->page_description();
        return view('jiade.chart.sparkline', compact('page_title', 'page_description','main_title'));
    }
	
	
	// chart-peity
	
	public function chart_peity(){
        $page_title = 'Chart Peity';
        $main_title = 'Chart';
        $page_description = $this->page_description();
        return view('jiade.chart.peity', compact('page_title', 'page_description','main_title'));
    }
	
	// Contant
	
	public function content(){
        $page_title = 'Content';
        $main_title = 'CMS';
        $page_description = $this->page_description();
        return view('jiade.cms.content', compact('page_title', 'page_description','main_title'));
    }
	
	// Add content
	
	public function content_add(){
        $page_title = 'Content Add';
        $main_title = 'CMS';
        $page_description = $this->page_description();
        return view('jiade.cms.content_add', compact('page_title', 'page_description','main_title'));
    }
	
	// ecom-checkout
	
	public function ecom_checkout(){
        $page_title = 'Ecom Checkout';
        $main_title = 'Shop';
        $page_description = $this->page_description();
        return view('jiade.ecom.checkout', compact('page_title', 'page_description','main_title'));
    }
	
	// ecom-customers
	
	public function ecom_customers(){
        $page_title = 'Customers';
        $main_title = 'Shop';
        $page_description = $this->page_description();
        return view('jiade.ecom.customers', compact('page_title', 'page_description','main_title'));
    }
	
	// ecom-invoice
	
	public function ecom_invoice(){
        $page_title = 'Invoice';
        $main_title = 'Shop';
        $page_description = $this->page_description();
        return view('jiade.ecom.invoice', compact('page_title', 'page_description','main_title'));
    }
	
	// ecom-product-detail
	
	public function ecom_product_detail(){
        $page_title = 'Product Detai';
        $main_title = 'Shop';
        $page_description = $this->page_description();
        return view('jiade.ecom.product_detail', compact('page_title', 'page_description','main_title'));
    }
	
	// ecom-product-grid
	
	public function ecom_product_grid(){
        $page_title = 'Product Grid';
        $main_title = 'Shop';
        $page_description = $this->page_description();
        return view('jiade.ecom.product_grid', compact('page_title', 'page_description','main_title'));
    }
	
	// ecom-product-list
	
	public function ecom_product_list(){
        $page_title = 'Product List';
        $main_title = 'Shop';
        $page_description = $this->page_description();
        return view('jiade.ecom.product_list', compact('page_title', 'page_description','main_title'));
    }
	
	// ecom-product-order
	
	public function ecom_product_order(){
        $page_title = 'Product Order';
        $main_title = 'Shop';
        $page_description = $this->page_description();
        return view('jiade.ecom.product_order', compact('page_title', 'page_description','main_title'));
    }

	
	// email-compose
	
	public function email_compose(){
        $page_title = 'Email Compose';
        $main_title = 'App';
        $page_description = $this->page_description();
        return view('jiade.message.compose', compact('page_title', 'page_description','main_title'));
    }
	
	//email-inbox
	
	public function email_inbox(){
        $page_title = 'Email Inbox';
        $main_title = 'App';
        $page_description = $this->page_description();
        return view('jiade.message.inbox', compact('page_title', 'page_description','main_title'));
    }
	
	//email-read
	
	public function email_read(){
        $page_title = 'Email Read';
        $main_title = 'App';
        $page_description = $this->page_description();
        return view('jiade.message.read', compact('page_title', 'page_description','main_title'));
    }
	
	//email-template
	
	public function email_template(){
        $page_title = 'Email Template';
        $main_title = 'CMS';
        $page_description = $this->page_description();
        return view('jiade.cms.email_template', compact('page_title', 'page_description','main_title'));
    }
	
	//empty-page
	
	public function empty_page(){
        $page_title = 'Empty Page';
        $page_description = $this->page_description();
        return view('jiade.page.empty_page', compact('page_title', 'page_description'));
    }
	
	//Flat icon
	
	public function flat_icons(){
        $page_title = 'Flaticon Icons';
        $page_description = $this->page_description();
        return view('jiade.icon.flat_icons', compact('page_title', 'page_description'));
    }

    //feather icon
    public function feather(){
        $page_title = 'Feather Icons';
        $page_description = $this->page_description();
        return view('jiade.icon.feather', compact('page_title', 'page_description'));
    }
	
	//form-ckeditor
	
	public function form_ckeditor(){
        $page_title = 'Form Ckeditor';
        $main_title = 'Forms';
        $page_description = $this->page_description();
        return view('jiade.form.ckeditor', compact('page_title', 'page_description','main_title'));
    }
	
	//form-summernote
	
	public function form_editor_summernote(){
        $page_title = 'Ckeditor';
        $main_title = 'Forms';
        $page_description = $this->page_description();
        return view('jiade.form.editor_summernote', compact('page_title', 'page_description','main_title'));
    }
	
	//form-element
	
	public function form_element(){
        $page_title = 'Form Element';
        $main_title = 'Forms';
        $page_description = $this->page_description();
        return view('jiade.form.element', compact('page_title', 'page_description','main_title'));
    }
	
	//form-pickers
	
	public function form_pickers(){
        $page_title = 'Form Pickers';
        $main_title = 'Forms';
        $page_description = $this->page_description();
        return view('jiade.form.pickers', compact('page_title', 'page_description','main_title'));
    }
	
	//form-validation
	
	public function form_validation(){
        $page_title = 'Form validation';
        $main_title = 'Forms';
        $page_description = $this->page_description();
        return view('jiade.form.validation', compact('page_title', 'page_description','main_title'));
    }
	
	//form-wizard
	
	public function login(){
        $page_title = 'Login';
        $page_description = $this->page_description();
        return view('admin.auth.login', compact('page_title', 'page_description'));
    }
	
	//login
	
	public function form_wizard(){
        $page_title = 'Form wizard';
        $page_description = $this->page_description();
        return view('jiade.form.wizard', compact('page_title', 'page_description'));
    }
	
	//menu
	
	public function menu(){
        $page_title = 'Menu';
        $main_title = 'CMS';
        $page_description = $this->page_description();
        return view('jiade.cms.menu', compact('page_title', 'page_description','main_title'));
    }
	
	//ap-jqvmap
	
	public function map_jqvmap(){
        $page_title = 'Jqvmap';
        $page_description = $this->page_description();
        return view('jiade.map.jqvmap', compact('page_title', 'page_description'));
    }
	
	
	//page-error-400
	
	public function page_error_400(){
        $page_title = 'Page Error 400';
        $page_description = $this->page_description();
        return view('jiade.page.error_400', compact('page_title', 'page_description'));
    }
	
	//page-error-403
	
	public function page_error_403(){
        $page_title = 'Page Error 403';
        $page_description = $this->page_description();
        return view('jiade.page.error_403', compact('page_title', 'page_description'));
    }
	
	//page-error-404
	
	public function page_error_404(){
        $page_title = 'Page Error 404';
        $page_description = $this->page_description();
        return view('jiade.page.error_404', compact('page_title', 'page_description'));
    }
	
	//page-error-500
	
	public function page_error_500(){
        $page_title = 'Page Error 500';
        $page_description = $this->page_description();
        return view('jiade.page.error_500', compact('page_title', 'page_description'));
    }
	
	//page-error-503
	
	public function page_error_503(){
        $page_title = 'Page Error 503';
        $page_description = $this->page_description();
        return view('jiade.page.error_503', compact('page_title', 'page_description'));
    }
	
	//page-forgot-password
	
	public function page_forgot_password(){
        $page_title = 'Page Forgot Password';
        $page_description = $this->page_description();
        return view('jiade.page.forgot_password', compact('page_title', 'page_description'));
    }
	
	//page-lock-screen
	
	public function page_lock_screen(){
        $page_title = 'Page Lock Screen';
        $page_description = $this->page_description();
        return view('jiade.page.lock_screen', compact('page_title', 'page_description'));
    }
	
	//page-login
	
	public function page_login(){
        $page_title = 'Page Login';
        $page_description = $this->page_description();
        return view('jiade.page.login', compact('page_title', 'page_description'));
    }
	
	//page-register
	
	public function page_register(){
        $page_title = 'Page Register';
        $page_description = $this->page_description();
        return view('jiade.page.register', compact('page_title', 'page_description'));
    }
	
	//svg
	
	public function svg_icons(){
        $page_title = 'Svg Icons';
        $page_description = $this->page_description();
        return view('jiade.icon.svg_icons', compact('page_title', 'page_description'));
    }
	
	//svg icon
	
	public function post_details(){
        $page_title = 'Post Details';
        $page_description = $this->page_description();
        return view('jiade.app.post_details', compact('page_title', 'page_description'));
    }
	
	//table-bootstrap-basic
	public function table_bootstrap_basic(){
        $page_title = 'Bootstrap Basic';
        $page_description = $this->page_description();
        return view('jiade.table.bootstrap_basic', compact('page_title', 'page_description'));
    }
	
	//table-datatable-basic
	
	public function table_datatable_basic(){
        $page_title = 'Datatable Basic';
        $page_description = $this->page_description();
        return view('jiade.table.datatable_basic', compact('page_title', 'page_description'));
    }
	
	//uc-lightgallery
	
	public function uc_lightgallery(){
        $page_title = 'Light Gallery';
        $main_title = 'Plugins';
        $page_description = $this->page_description();
        return view('jiade.uc.lightgallery', compact('page_title', 'page_description','main_title'));
    }
	
	//uc-nestable
	
	public function uc_nestable(){
        $page_title = 'Nestable';
        $main_title = 'Plugins';
        $page_description = $this->page_description();
        return view('jiade.uc.nestable', compact('page_title', 'page_description','main_title'));
    }
	
	//uc-noui-slider
	
	public function uc_noui_slider(){
        $page_title = 'Noui Slider';
        $main_title = 'Plugins';
        $page_description = $this->page_description();
        return view('jiade.uc.noui_slider', compact('page_title', 'page_description','main_title'));
    }
	
	//uc-select2
	
	public function uc_select2(){
        $page_title = 'Select2';
        $main_title = 'Plugins';
        $page_description = $this->page_description();
        return view('jiade.uc.select2', compact('page_title', 'page_description','main_title'));
    }
	
	//uc-sweetalert
	
	public function uc_sweetalert(){
        $page_title = 'Sweetalert';
        $main_title = 'Plugins';
        $page_description = $this->page_description();
        return view('jiade.uc.sweetalert', compact('page_title', 'page_description','main_title'));
    }
	
	//uc-toastr
	
	public function uc_toastr(){
        $page_title = 'Toastr';
        $main_title = 'Plugins';
        $page_description = $this->page_description();
        return view('jiade.uc.toastr', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-accordion
	
	public function ui_accordion(){
        $page_title = 'Accordion';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.accordion', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-alert
	
	public function ui_alert(){
        $page_title = 'Alert';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.alert', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-badge
	
	public function ui_badge(){
        $page_title = 'Badge';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.badge', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-button
	
	public function ui_button(){
        $page_title = 'Button';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.button', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-button-group
	
	public function ui_button_group(){
        $page_title = 'Button';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.button_group', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-button-group
	
	public function ui_card(){
        $page_title = 'Card';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.card', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-carousel
	
	public function ui_carousel(){
        $page_title = 'Carouse';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.carousel', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-dropdown
	
	public function ui_dropdown(){
        $page_title = 'Dropdown';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.dropdown', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-grid
	
	public function ui_grid(){
        $page_title = 'Grid';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.grid', compact('page_title', 'page_description','main_title'));
    }
	
	//media object
	
	public function ui_media_object(){
        $page_title = 'Media Object';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.media_object', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-list-group
	
	public function ui_list_group(){
        $page_title = 'List Group';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.list_group', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-modal
	
	public function ui_modal(){
        $page_title = 'Modal';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.modal', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-pagination
	
	public function ui_pagination(){
        $page_title = 'Pagination';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.pagination', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-popover
	
	public function ui_popover(){
        $page_title = 'Popover';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.popover', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-progressbar
	
	public function ui_progressbar(){
        $page_title = 'Progressbar';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.progressbar', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-tab
	
	public function ui_tab(){
        $page_title = 'Tab';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.tab', compact('page_title', 'page_description','main_title'));
    }
	
	//ui-typography
	
	public function ui_typography(){
        $page_title = 'Tab';
        $main_title = 'Bootstrap';
        $page_description = $this->page_description();
        return view('jiade.ui.typography', compact('page_title', 'page_description','main_title'));
    }
	
	//widget-basic
	public function widget_basic (){
        $page_title = 'Widget Basic';
        $page_description = $this->page_description();
        return view('jiade.widget.widget_basic ', compact('page_title', 'page_description'));
    }

    //widget-basic
	public function widget_chart(){
        $page_title = 'Widget Chart';
        $page_description = $this->page_description();
        return view('jiade.widget.chart', compact('page_title', 'page_description'));
    }

    //widget-basic
	public function widget_list(){
        $page_title = 'Widget List';
        $page_description = $this->page_description();
        return view('jiade.widget.list', compact('page_title', 'page_description'));
    }
	
    //seller_contacts
	public function ajax_contacts(){
        return view('jiade.ajax.contacts');
    }

    //seller_menus
	public function ajax_message(){
        return view('jiade.ajax.message');
    }
	
	//$page_description = $this->page_description();
	private function page_description() {
		return 'Empower your cryptocurrency trading platform with Jiade, the ultimate Crypto Trading UI Admin Bootstrap 5 Template. Seamlessly combining sleek design with the power of Bootstrap 5, Jiade offers a sophisticated and user-friendly interface for managing your crypto assets. Packed with customizable components, responsive charts, and a modern dashboard, Jiade accelerates your development process. Crafted for efficiency and aesthetics, this template is your key to creating a cutting-edge crypto trading experience. Explore Jiade today and elevate your crypto trading platform to new heights with a UI that blends functionality and style effortlessly.';
	}
	
	
}
