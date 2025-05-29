<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Application Name
	|--------------------------------------------------------------------------
	|
	| This value is the name of your application. This value is used when the
	| framework needs to place the application's name in a notification or
	| any other location as required by the application or its packages.
	|
	*/

	'name' => env('APP_NAME', 'Jiade Laravel'),

	'public' => [
		'global' => [
			'css' => [
					'vendor/bootstrap-select/dist/css/bootstrap-select.min.css',
					
					
			],
			'js' => [
				'top'=> [
					'vendor/global/global.min.js',
					'vendor/bootstrap-select/dist/js/bootstrap-select.min.js',
				],
				'bottom'=> [
					'js/custom.min.js',
					'js/dlabnav-init.js',
				],
			],
		],
		'pagelevel' => [
			'css' => [
				'JiadeAdminController_dashboard' => [
					'vendor/swiper/css/swiper-bundle.min.css',
					
					
				],
				'JiadeAdminController_dashboard_2' => [
					'vendor/swiper/css/swiper-bundle.min.css',
				],
				'JiadeAdminController_market' => [
					'vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
				],
				'JiadeAdminController_app_calender' => [
					'vendor/fullcalendar/css/main.min.css'
				],
				'JiadeAdminController_celandar' => [
					'vendor/fullcalendar-5.11.0/lib/main.css',
					'vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
				],
				'JiadeAdminController_coin_details'=>[
					'vendor/bootstrap-daterangepicker/daterangepicker.css'
				],
				'JiadeAdminController_portofolio'=>[
					'vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css'
				],
				'JiadeAdminController_trading_market'=>[
					'vendor/datatables/css/jquery.dataTables.min.css'
				],
				'JiadeAdminController_p2p'=>[
					'vendor/datatables/css/jquery.dataTables.min.css',
				],
				'JiadeAdminController_future'=>[
					'vendor/datatables/css/jquery.dataTables.min.css',
				],
				'JiadeAdminController_market_watch'=>[
					'vendor/swiper/css/swiper-bundle.min.css',
				],
				'JiadeAdminController_history'=>[
					'vendor/datatables/css/jquery.dataTables.min.css',
				],
				'JiadeAdminController_edit_profile' => [
					'vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css',
					
				],
				'JiadeAdminController_app_profile' => [
					'vendor/lightgallery/dist/css/lightgallery.css',
					'vendor/lightgallery/dist/css/lg-thumbnail.css',
					'vendor/lightgallery/dist/css/lg-zoom.css',
					
				],
				
				'JiadeAdminController_post_details' => [
					'vendor/lightgallery/dist/css/lightgallery.css',
					'vendor/lightgallery/dist/css/lg-thumbnail.css',
					'vendor/lightgallery/dist/css/lg-zoom.css',
					
				],
				'JiadeAdminController_add_student' => [
					'vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css',
					
				],
				'JiadeAdminController_add_teacher' => [
					'vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css',
					
				],
				'JiadeAdminController_blog_category' => [
					'vendor/datatables/css/jquery.dataTables.min.css',
					
				],

				'JiadeAdminController_chart_chartist' => [
					'vendor/chartist/css/chartist.min.css'
				],
				'JiadeAdminController_chart_chartjs' => [
				],
				'JiadeAdminController_chart_flot' => [
				],
				'JiadeAdminController_chart_morris' => [
				],
				'JiadeAdminController_chart_peity' => [
				],
				'JiadeAdminController_chart_sparkline' => [
				],
				'JiadeAdminController_ecom_checkout' => [
				],
				'JiadeAdminController_ecom_customers' => [
				],
				'JiadeAdminController_ecom_invoice' => [
					
				],
				'JiadeAdminController_ecom_product_detail' => [
					'vendor/star-rating/star-rating-svg.css',
					'vendor/owl-carousel/owl.carousel.css',
				],
				'JiadeAdminController_ecom_product_grid' => [
				],
				'JiadeAdminController_ecom_product_list' => [
					'vendor/star-rating/star-rating-svg.css'
				],
				'JiadeAdminController_ecom_product_order' => [
				],
				'JiadeAdminController_email_compose' => [
					'vendor/dropzone/dist/dropzone.css'
				],
				'JiadeAdminController_menu' => [
					'vendor/nestable2/css/jquery.nestable.min.css'
				],
				'JiadeAdminController_email_inbox' => [
					
				],
				'JiadeAdminController_email_read' => [
				],
				'JiadeAdminController_editor' => [
					
				],
				'JiadeAdminController_form_element' => [
				],
				'JiadeAdminController_form_pickers' => [
					'vendor/bootstrap-daterangepicker/daterangepicker.css',
					'vendor/clockpicker/css/bootstrap-clockpicker.min.css',
					'vendor/jquery-asColorPicker/css/asColorPicker.min.css',
					'vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css',
					'vendor/pickadate/themes/default.css',
					'vendor/pickadate/themes/default.date.css',
					'https://fonts.googleapis.com/icon?family=Material+Icons',
				],
				'JiadeAdminController_email_template' => [
					'vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css',
				],
				'JiadeAdminController_blog' => [
					'vendor/datatables/css/jquery.dataTables.min.css',
					'vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css',
				],
				'JiadeAdminController_content' => [
					'vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css',
					'vendor/select2/css/select2.min.css',
					'vendor/datatables/css/jquery.dataTables.min.css',
				],
				'JiadeAdminController_content_add' => [
					'vendor/bootstrap-datepicker-master/css/bootstrap-datepicker.min.css',
				],
				'JiadeAdminController_add_email' => [
					'vendor/select2/css/select2.min.css',
				],
				'JiadeAdminController_form_validation_jquery' => [
				],
				'JiadeAdminController_form_wizard' => [
					'vendor/jquery-smartwizard/dist/css/smart_wizard.min.css',
					'vendor/dropzone/dist/dropzone.css',
				],
				'JiadeAdminController_map_jqvmap' => [
					'vendor/jqvmap/css/jqvmap.min.css'
				],
				'JiadeAdminController_page_error_400' => [
					'vendor/bootstrap-select/dist/css/bootstrap-select.min.css'
				],
				'JiadeAdminController_table_bootstrap_basic' => [
					
				],
				'JiadeAdminController_table_datatable_basic' => [
					'vendor/datatables/css/jquery.dataTables.min.css',
					'vendor/datatables/responsive/responsive.css',
					
				],
				'JiadeAdminController_uc_lightgallery' => [
					'vendor/lightgallery/dist/css/lightgallery.css',
					'vendor/lightgallery/dist/css/lg-thumbnail.css',
					'vendor/lightgallery/dist/css/lg-zoom.css',
					
				],
				'JiadeAdminController_uc_nestable' => [
					'vendor/nestable2/css/jquery.nestable.min.css'
				],
				'JiadeAdminController_uc_noui_slider' => [
					'vendor/nouislider/nouislider.min.css'
				],
				'JiadeAdminController_uc_select2' => [
					'vendor/select2/css/select2.min.css'
				],
				'JiadeAdminController_uc_sweetalert' => [
					'vendor/sweetalert2/dist/sweetalert2.min.css'
				],
				'JiadeAdminController_uc_toastr' => [
					'vendor/toastr/css/toastr.min.css'
				],
				'JiadeAdminController_add_blog' => [
					'vendor/select2/css/select2.min.css'
				],
				
				'JiadeAdminController_ui_accordion' => [
				],
				'JiadeAdminController_ui_alert' => [
				],
				'JiadeAdminController_ui_badge' => [
				],
				'JiadeAdminController_ui_button' => [
				],
				'JiadeAdminController_ui_button_group' => [
				],
				'JiadeAdminController_ui_card' => [
				],
				'JiadeAdminController_ui_carousel' => [
				],
				'JiadeAdminController_ui_dropdown' => [
				],
				'JiadeAdminController_ui_grid' => [
				],
				'JiadeAdminController_ui_list_group' => [
				],
				'JiadeAdminController_ui_media_object' => [
				],
				'JiadeAdminController_ui_modal' => [
				],
				'JiadeAdminController_ui_pagination' => [
				],
				'JiadeAdminController_ui_popover' => [
				],
				'JiadeAdminController_ui_progressbar' => [
				],
				'JiadeAdminController_ui_tab' => [
				],
				'JiadeAdminController_ui_typography' => [
				],
				'JiadeAdminController_widget_basic' => [
					'vendor/chartist/css/chartist.min.css',
				],
			],
			'js' => [
				'JiadeAdminController_dashboard' => [
					'vendor/apexchart/apexchart.js',
					'vendor/chart-js/chart.bundle.min.js',
					'vendor/counter/counter.min.js',
					'vendor/counter/waypoint.min.js',
					'vendor/peity/jquery.peity.min.js',
					'vendor/swiper/js/swiper-bundle.min.js',
					'js/dashboard/dashboard-1.js',
					
				],
				'JiadeAdminController_dashboard_2' => [
					'vendor/apexchart/apexchart.js',
					'vendor/chart-js/chart.bundle.min.js',
					'vendor/counter/counter.min.js',
					'vendor/counter/waypoint.min.js',
					'vendor/peity/jquery.peity.min.js',
					'vendor/swiper/js/swiper-bundle.min.js',
					'js/dashboard/dashboard-1.js',
				],
				'JiadeAdminController_student'=>[
					'vendor/datatables/js/jquery.dataTables.min.js',
					'js/plugins-init/datatables.init.js',
					'vendor/wow-master/dist/wow.min.js',
				],
				'JiadeAdminController_market' => [
					'vendor/apexchart/apexchart.js',
					'vendor/peity/jquery.peity.min.js',
					'js/dashboard/market.js',
				],
				'JiadeAdminController_student_detail'=>[
					'vendor/datatables/js/jquery.dataTables.min.js',
					'js/plugins-init/datatables.init.js',
					'vendor/wow-master/dist/wow.min.js',
				],
				'JiadeAdminController_celandar' => [
					'vendor/fullcalendar-5.11.0/lib/main.js',
					'vendor/wow-master/dist/wow.min.js',
					'js/calendar-2.js',
				],
				'JiadeAdminController_blog_category' => [
					'js/dashboard/cms.js',
					
				],
				'JiadeAdminController_content' => [
					'vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js',
					'js/dashboard/cms.js',
				],
				'JiadeAdminController_blog' => [
					'vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js',
					'js/dashboard/cms.js',
				],
				'JiadeAdminController_add_blog' => [
					'vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js',
					'vendor/ckeditor/ckeditor.js',
					'vendor/select2/js/select2.full.min.js',
					'js/plugins-init/select2-init.js',
					'js/dashboard/cms.js',
				],
				'JiadeAdminController_email_template' => [
					'vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js',
					'js/dashboard/cms.js',
				],
				'JiadeAdminController_add_email' => [
					'vendor/ckeditor/ckeditor.js',
					'js/dashboard/cms.js',
				],
				'JiadeAdminController_menu' => [
					'vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js',
					'js/dashboard/cms.js',
					'vendor/nestable2/js/jquery.nestable.min.js',
					'js/plugins-init/nestable-init.js',
					'vendor/ckeditor/ckeditor.js',
				],
				'JiadeAdminController_content_add' => [
					'vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js',
					'js/dashboard/cms.js',
					'vendor/ckeditor/ckeditor.js',
				],
				'JiadeAdminController_portofolio' => [
					'vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js',
					'vendor/peity/jquery.peity.min.js',
					'vendor/apexchart/apexchart.js',
					'js/dashboard/portfolio.js',
				],
				'JiadeAdminController_trading_market' => [
					'vendor/datatables/js/jquery.dataTables.min.js',
					'js/plugins-init/datatables.init.js',
					'vendor/chart-js/chart.bundle.min.js',
					'vendor/peity/jquery.peity.min.js',
					'js/dashboard/trading-market.js',
				],
				'JiadeAdminController_p2p' => [
					'vendor/datatables/js/jquery.dataTables.min.js',
					'js/plugins-init/datatables.init.js',
					
				],
				'JiadeAdminController_exchange' => [
					'vendor/chart-js/chart.bundle.min.js',
					'vendor/apexchart/apexchart.js',
					'js/dashboard/ticketing.js',
					
				],
				'JiadeAdminController_banking' => [
					'vendor/chart-js/chart.bundle.min.js',
					'vendor/apexchart/apexchart.js',
					'vendor/peity/jquery.peity.min.js',
					'js/user.js',
					
				],
				'JiadeAdminController_future' => [
					'https://s3.tradingview.com/tv.js',
					'js/dashboard/future.js',
					'vendor/datatables/js/jquery.dataTables.min.js',
					'js/plugins-init/datatables.init.js',
					
				],
				'JiadeAdminController_history' => [
					'vendor/datatables/js/jquery.dataTables.min.js',
					'js/plugins-init/datatables.init.js',
				],
				'JiadeAdminController_market_watch' => [
					'vendor/apexchart/apexchart.js',
					'js/dashboard/crypto-watch.js',
					'vendor/swiper/js/swiper-bundle.min.js',
					
					
				],
				'JiadeAdminController_coin_details' => [
					'vendor/chart-js/chart.bundle.min.js',
					'vendor/apexchart/apexchart.js',
					'vendor/peity/jquery.peity.min.js',
					'js/dashboard/coin.js',
					'vendor/bootstrap-datetimepicker/js/moment.js',
					'vendor/bootstrap-daterangepicker/daterangepicker.js',
				],

				'JiadeAdminController_app_calender' => [
					'vendor/fullcalendar/js/main.min.js',
					'js/plugins-init/fullcalendar-init.js',
				],
				'JiadeAdminController_add_student' => [
					'vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js',
					
				],
				'JiadeAdminController_add_teacher' => [
					'vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js',
					
				],
				
				'JiadeAdminController_app_profile' => [
					'vendor/lightgallery/dist/lightgallery.min.js',
					'vendor/lightgallery/dist/plugins/thumbnail/lg-thumbnail.min.js',
					'vendor/lightgallery/dist/plugins/zoom/lg-zoom.min.js',
					
				],
				'JiadeAdminController_post_details' => [
					'vendor/lightgallery/dist/lightgallery.min.js',
					'vendor/lightgallery/dist/plugins/thumbnail/lg-thumbnail.min.js',
					'vendor/lightgallery/dist/plugins/zoom/lg-zoom.min.js',
					
				],
				'JiadeAdminController_chart_chartist' => [
					'vendor/apexchart/apexchart.js',
					'vendor/chartist/js/chartist.min.js',
					'vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js',
					'js/plugins-init/chartist-init.js',
				],
				'JiadeAdminController_chart_chartjs' => [
					'vendor/chart-js/chart.bundle.min.js',
					'js/plugins-init/chartjs-init.js',
				],
				'JiadeAdminController_chart_flot' => [
					'vendor/flot/jquery.flot.js',
					'vendor/flot/jquery.flot.pie.js',
					'vendor/flot/jquery.flot.resize.js',
					'vendor/flot-spline/jquery.flot.spline.min.js',
					'js/plugins-init/flot-init.js',
					
				],
				'JiadeAdminController_chart_morris' => [
					
					'vendor/apexchart/apexchart.js',
					'vendor/raphael/raphael.min.js',
					'vendor/morris/morris.min.js',
					'js/plugins-init/morris-init.js',
				],
				'JiadeAdminController_chart_peity' => [
					'vendor/peity/jquery.peity.min.js',
					'js/plugins-init/piety-init.js',
				],
				'JiadeAdminController_chart_sparkline' => [
					'vendor/apexchart/apexchart.js',
					'vendor/jquery-sparkline/jquery.sparkline.min.js',
					'js/plugins-init/sparkline-init.js',
				],
				'JiadeAdminController_ecom_checkout' => [
				],
				'JiadeAdminController_ecom_customers' => [
				],
				'JiadeAdminController_ecom_invoice' => [

				],
				'JiadeAdminController_ecom_product_detail' => [
					'vendor/star-rating/jquery.star-rating-svg.js',
					'vendor/owl-carousel/owl.carousel.js',
				],
				'JiadeAdminController_ecom_product_grid' => [
				],
				'JiadeAdminController_ecom_product_list' => [
					'vendor/star-rating/jquery.star-rating-svg.js'
				],
				'JiadeAdminController_ecom_product_order' => [
				],
				'JiadeAdminController_email_compose' => [
					'vendor/dropzone/dist/dropzone.js'
				],
				'JiadeAdminController_email_inbox' => [
					
				],
				'JiadeAdminController_email_read' => [
				],
				'JiadeAdminController_form_ckeditor' => [
					'vendor/ckeditor/ckeditor.js',
				],
				'JiadeAdminController_form_element' => [
				],
				'JiadeAdminController_form_pickers' => [
					'vendor/moment/moment.min.js',
					'vendor/bootstrap-daterangepicker/daterangepicker.js',
					'vendor/clockpicker/js/bootstrap-clockpicker.min.js',
					'vendor/jquery-asColor/jquery-asColor.min.js',
					'vendor/jquery-asGradient/jquery-asGradient.min.js',
					'vendor/jquery-asColorPicker/js/jquery-asColorPicker.min.js',
					'vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js',
					'vendor/pickadate/picker.js',
					'vendor/pickadate/picker.time.js',
					'vendor/pickadate/picker.date.js',
					'js/plugins-init/bs-daterange-picker-init.js',
					'js/plugins-init/clock-picker-init.js',
					'js/plugins-init/jquery-asColorPicker.init.js',
					'js/plugins-init/material-date-picker-init.js',
					'js/plugins-init/pickadate-init.js',
				],
				'JiadeAdminController_form_validation_jquery' => [
				],
				'JiadeAdminController_form_wizard' => [
					'vendor/jquery-steps/build/jquery.steps.min.js',
					'vendor/jquery-validation/jquery.validate.min.js',
					'js/plugins-init/jquery.validate-init.js',
					'vendor/jquery-smartwizard/dist/js/jquery.smartWizard.js',
					'vendor/dropzone/dist/dropzone.js',
					
				],
				'JiadeAdminController_map_jqvmap' => [
					'vendor/jqvmap/js/jquery.vmap.min.js',
					'vendor/jqvmap/js/jquery.vmap.world.js',
					'vendor/jqvmap/js/jquery.vmap.usa.js',
					'js/plugins-init/jqvmap-init.js',
				],
				'JiadeAdminController_page_error_400' => [
				],
				'JiadeAdminController_page_error_403' => [
				],
				'JiadeAdminController_page_error_404' => [
				],
				'JiadeAdminController_page_error_500' => [
				],
				'JiadeAdminController_page_error_503' => [
				],
				'JiadeAdminController_page_forgot_password' => [
				],
				'JiadeAdminController_page_lock_screen' => [
					'vendor/deznav/deznav.min.js'
				],
				'JiadeAdminController_page_login' => [
				],
				'JiadeAdminController_page_register' => [
				],
				'JiadeAdminController_table_bootstrap_basic' => [
					'js/highlight.min.js',

				],
				'JiadeAdminController_table_datatable_basic' => [
					'vendor/datatables/js/jquery.dataTables.min.js',
					'vendor/datatables/responsive/responsive.js',
					'js/plugins-init/datatables.init.js',
					'js/highlight.min.js',
					
				],
				'JiadeAdminController_edit_profile' => [
					'vendor/bootstrap-datepicker-master/js/bootstrap-datepicker.min.js',
					
				],
				'JiadeAdminController_uc_lightgallery' => [
					'vendor/lightgallery/dist/lightgallery.min.js',
					'vendor/lightgallery/dist/plugins/thumbnail/lg-thumbnail.min.js',
					'vendor/lightgallery/dist/plugins/zoom/lg-zoom.min.js',
				],
				'JiadeAdminController_uc_nestable' => [
					'vendor/nestable2/js/jquery.nestable.min.js',
					'js/plugins-init/nestable-init.js',
				],
				'JiadeAdminController_uc_noui_slider' => [
					'vendor/nouislider/nouislider.min.js',
					'vendor/wnumb/wNumb.js',
					'js/plugins-init/nouislider-init.js',
				],
				'JiadeAdminController_uc_select2' => [
					'vendor/select2/js/select2.full.min.js',
					'js/plugins-init/select2-init.js',
				],
				'JiadeAdminController_uc_sweetalert' => [
					'vendor/sweetalert2/dist/sweetalert2.min.js',
					'js/plugins-init/sweetalert.init.js',
				],
				'JiadeAdminController_uc_toastr' => [
					'vendor/toastr/js/toastr.min.js',
					'js/plugins-init/toastr-init.js',
				],
				'JiadeAdminController_ui_accordion' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_alert' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_badge' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_button' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_button_group' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_card' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_carousel' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_dropdown' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_grid' => [
				],
				'JiadeAdminController_ui_list_group' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_media_object' => [
				],
				'JiadeAdminController_ui_modal' => [
				],
				'JiadeAdminController_ui_pagination' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_popover' => [
				],
				'JiadeAdminController_ui_progressbar' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_tab' => [
					'js/highlight.min.js',
				],
				'JiadeAdminController_ui_typography' => [
				],
				'JiadeAdminController_widget_basic ' => [
				],
				'JiadeAdminController_widget_basic' => [
					'vendor/chart-js/chart.bundle.min.js',
					'vendor/apexchart/apexchart.js',
					'vendor/chartist/js/chartist.min.js',
					'vendor/chartist-plugin-tooltips/js/chartist-plugin-tooltip.min.js',
					'vendor/flot/jquery.flot.js',
					'vendor/flot/jquery.flot.pie.js',
					'vendor/flot/jquery.flot.resize.js',
					'vendor/flot-spline/jquery.flot.spline.min.js',
					'vendor/jquery-sparkline/jquery.sparkline.min.js',
					'js/plugins-init/sparkline-init.js',
					'vendor/peity/jquery.peity.min.js',
					'js/plugins-init/piety-init.js',
					'vendor/counter/counter.min.js',
					'vendor/counter/waypoint.min.js',
					'js/dashboard/dashboard-1.js',
					'js/plugins-init/widgets-script-init.js',
				],
				
				
			]
		],
	]
];
