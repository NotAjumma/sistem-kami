<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ToyyibpayController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JiadeAdminController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\DB;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/send-test-mail', function () {
    Mail::to('muhamadakmal9973@gmail.com')->send(new TestMail());
    return 'Test email sent!';
});

Route::get('/env', function () {
    return response()->json([
        'APP_ENV' => env('APP_ENV'),
        'GOOGLE_DRIVE_CREDENTIALS' => env('GOOGLE_DRIVE_CREDENTIALS'),
        'APP_URL' => env('APP_URL'),
        'MAILGUN_SECRET' => env('MAILGUN_SECRET'),
        'MAILGUN_ENDPOINT' => env('MAILGUN_ENDPOINT'),
        'MAILGUN_DOMAIN' => env('MAILGUN_DOMAIN'),
        // Add any other keys you want to check
    ]);
});


Route::get('/db-check', function () {
    try {
        DB::connection()->getPdo();

        $events = DB::table('events')->get();

        return response()->json([
            'status' => '✅ DB connected!',
            'events' => $events,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => '❌ Connection failed',
            'error' => $e->getMessage(),
        ], 500);
    }
});



// Used route
Route::get('/checkout2', [ToyyibpayController::class, 'createBill'])->name('toyyibpay.checkout');
Route::get('/toyyibpay-status', [ToyyibpayController::class, 'paymentStatus'])->name('toyyibpay.status');
Route::get('/toyyibpay-callback', [ToyyibpayController::class, 'callback'])->name('toyyibpay.callback');
Route::get('/toyyibpay/callback', [BookingController::class, 'handleCallback'])->name('toyyibpay.callback');
Route::get('/booking/receipt/{booking_code}', [BookingController::class, 'bookingReceipt'])->name('booking.receipt');
Route::post('/webform-booking', [BookingController::class, 'webFormBooking'])->name('webform.booking');
Route::post('/tickets/select', [BookingController::class, 'storeSelection'])->name('tickets.select');
Route::get('/checkout', [BookingController::class, 'showCheckout'])->name('checkout');
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/{slug}', [EventController::class, 'showBySlug'])->name('event.slug');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('business')->group(function () {
    // Public profile
    Route::get('/checkout/package', [BookingController::class, 'showCheckoutPackage'])->name('business.checkout_package');
    Route::post('/select/package', [BookingController::class, 'storeSelectionPackage'])->name('business.select_package');
    Route::get('/{slug}', [BusinessController::class, 'showProfile'])->name('business.profile');
    Route::get('/{organizerSlug}/{packageSlug}', [BusinessController::class, 'showPackage'])->name('business.package');
    Route::get('/{organizerSlug}/{packageSlug}/booking', [BusinessController::class, 'showBooking'])->name('business.booking');
    Route::post('/webform/booking', [BookingController::class, 'webFormBookingPackage'])->name('webform.booking_package');
});

Route::get('/admin/login', [AuthController::class, 'showLoginAdmin'])->name('admin.login');
Route::prefix('admin')->middleware('auth')->controller(AdminController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('admin.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Similar for organizer, marshal, participant...
});


// Organizer Route
Route::get('/organizer/login', [AuthController::class, 'showLoginOrganizer'])->name('organizer.login');
Route::get('/worker/login', [AuthController::class, 'showLoginOrganizerWorker'])->name('organizer.worker.login');
Route::get('/organizer/register', [AuthController::class, 'showRegisterOrganizer'])->name('organizer.register');
Route::post('/organizer/register', [AuthController::class, 'submitRegisterOrganizer'])->name('organizer.submit_register');
Route::post('/{role}/login', [AuthController::class, 'login'])->name('role.login');

Route::prefix('worker')->middleware('auth:worker')->controller(WorkerController::class)->group(function () {
    Route::get('/tickets/confirmed', 'ticketsConfirmed')->name('worker.tickets.confirmed');
    Route::patch('/ticket/{id}/check-in', 'ticketCheckin')->name('worker.ticket.checkin');

});
Route::prefix('organizer')->middleware('auth:organizer')->controller(OrganizerController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('organizer.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('organizer.logout');
    Route::get('/bookings', 'bookings')->name('organizer.bookings');
    Route::get('/booking/{id}/edit', [OrganizerController::class, 'editBooking'])->name('organizer.booking.edit');
    Route::patch('/booking/{id}/verify', [OrganizerController::class, 'verifyPayment'])->name('organizer.booking.verify');
    Route::get('/tickets/confirmed', 'ticketsConfirmed')->name('organizer.tickets.confirmed');
    Route::patch('/ticket/{id}/check-in', 'ticketCheckin')->name('organizer.ticket.checkin');
    Route::patch('/booking/{id}/cancel', [OrganizerController::class, 'cancelBooking'])->name('organizer.booking.cancel');


    Route::get('/preview-ticket/{booking}', function ($bookingId) {
        $booking = Booking::findOrFail($bookingId);

        $pdf = PDF::loadView('emails.ticket_pdf', ['booking' => $booking]);

        return $pdf->stream('ticket.pdf'); // open inline in browser
    });
    // Similar for organizer, marshal, participant...
});

// Route::get('/organizer/login', [AuthController::class, 'organizer'])->name('organizer.login');
// Route::get('/marshal/login', [AuthController::class, 'marshal'])->name('marshal.login');
// Route::get('/login', [AuthController::class, 'participant'])->name('participant.login');

Route::prefix('jiade')
    // ->middleware('auth:organizer')
    ->controller(JiadeAdminController::class)
    ->group(function () {
        Route::get('/', 'dashboard');
        Route::get('/index', 'dashboard')->name('dashboard');
        Route::get('/index-2', 'dashboard_2')->name('dashboard_2');
        Route::get('/banking', 'banking')->name('banking');
        Route::get('/coin-details', 'coin_details')->name('coin_details');
        Route::get('/exchange', 'exchange')->name('exchange');
        Route::get('/future', 'future')->name('future');
        Route::get('/ico-listing', 'ico_listing')->name('ico_listing');
        Route::get('/market', 'market')->name('market');
        Route::get('/market-watch', 'market_watch')->name('market_watch');
        Route::get('/p2p', 'p2p')->name('p2p');
        Route::get('/reports', 'reports')->name('reports');
        Route::get('/portofolio', 'portofolio')->name('portofolio');
        Route::get('/add-blog', 'add_blog')->name('add_blog');
        Route::get('/add-email', 'add_email')->name('add_email');
        Route::get('/app-calender', 'app_calender')->name('app_calender');
        Route::get('/app-profile', 'app_profile')->name('app_profile');
        Route::get('/blog', 'blog')->name('blog');
        Route::get('/blog-category', 'blog_category')->name('blog_category');
        Route::get('/chart-chartist', 'chart_chartist')->name('chart_chartist');
        Route::get('/chart-chartjs', 'chart_chartjs')->name('chart_chartjs');
        Route::get('/chart-flot', 'chart_flot')->name('chart_flot');
        Route::get('/chart-morris', 'chart_morris')->name('chart_morris');
        Route::get('/chart-peity', 'chart_peity')->name('chart_peity');
        Route::get('/chart-sparkline', 'chart_sparkline')->name('chart_sparkline');
        Route::get('/content', 'content')->name('content');
        Route::get('/content-add', 'content_add')->name('content_add');
        Route::get('/ecom-checkout', 'ecom_checkout')->name('ecom_checkout');
        Route::get('/ecom-customers', 'ecom_customers')->name('ecom_customers');
        Route::get('/ecom-invoice', 'ecom_invoice')->name('ecom_invoice');
        Route::get('/ecom-product-detail', 'ecom_product_detail')->name('ecom_product_detail');
        Route::get('/ecom-product-grid', 'ecom_product_grid')->name('ecom_product_grid');
        Route::get('/ecom-product-list', 'ecom_product_list')->name('ecom_product_list');
        Route::get('/ecom-product-order', 'ecom_product_order')->name('ecom_product_order');
        Route::get('/edit-profile', 'edit_profile')->name('edit_profile');
        Route::match(['get', 'post'], '/email-compose', 'email_compose')->name('email_compose');
        Route::get('/email-inbox', 'email_inbox')->name('email_inbox');
        Route::get('/email-read', 'email_read')->name('email_read');
        Route::get('/email-template', 'email_template')->name('email_template');
        Route::get('/empty-page', 'empty_page')->name('empty_page');
        Route::get('/exchange', 'exchange')->name('exchange');
        Route::get('/form-ckeditor', 'form_ckeditor')->name('form_ckeditor');
        Route::get('/form-element', 'form_element')->name('form_element');
        Route::get('/form-pickers', 'form_pickers')->name('form_pickers');
        Route::get('/form-validation', 'form_validation')->name('form_validation');
        Route::get('/form-wizard', 'form_wizard')->name('form_wizard');
        Route::get('/future', 'future')->name('future');
        Route::get('/history', 'history')->name('history');
        Route::get('/map-jqvmap', 'map_jqvmap')->name('map_jqvmap');
        Route::get('/menu', 'menu')->name('menu');
        Route::get('/orders', 'orders')->name('orders');
        Route::get('/page-error-400', 'page_error_400')->name('page_error_400');
        Route::get('/page-error-403', 'page_error_403')->name('page_error_403');
        Route::get('/page-error-404', 'page_error_404')->name('page_error_404');
        Route::get('/page-error-500', 'page_error_500')->name('page_error_500');
        Route::get('/page-error-503', 'page_error_503')->name('page_error_503');
        Route::get('/page-forgot-password', 'page_forgot_password')->name('page_forgot_password');
        Route::get('/page-lock-screen', 'page_lock_screen')->name('page_lock_screen');
        Route::get('/page-login', 'page_login')->name('page_login');
        Route::get('/page-register', 'page_register')->name('page_register');
        Route::match(['get', 'post'], '/post-details', 'post_details')->name('post_details');
        Route::get('/table-bootstrap-basic', 'table_bootstrap_basic')->name('table_bootstrap_basic');
        Route::get('/table-datatable-basic', 'table_datatable_basic')->name('table_datatable_basic');
        Route::get('/trading-market', 'trading_market')->name('trading_market');
        Route::get('/uc-lightgallery', 'uc_lightgallery')->name('uc_lightgallery');
        Route::get('/uc-nestable', 'uc_nestable')->name('uc_nestable');
        Route::get('/uc-noui-slider', 'uc_noui_slider')->name('uc_noui_slider');
        Route::get('/uc-select2', 'uc_select2')->name('uc_select2');
        Route::get('/uc-sweetalert', 'uc_sweetalert')->name('uc_sweetalert');
        Route::get('/uc-toastr', 'uc_toastr')->name('uc_toastr');
        Route::get('/ui-accordion', 'ui_accordion')->name('ui_accordion');
        Route::get('/ui-alert', 'ui_alert')->name('ui_alert');
        Route::get('/ui-badge', 'ui_badge')->name('ui_badge');
        Route::get('/ui-button', 'ui_button')->name('ui_button');
        Route::get('/ui-button-group', 'ui_button_group')->name('ui_button_group');
        Route::get('/ui-card', 'ui_card')->name('ui_card');
        Route::get('/ui-carousel', 'ui_carousel')->name('ui_carousel');
        Route::get('/ui-dropdown', 'ui_dropdown')->name('ui_dropdown');
        Route::get('/ui-grid', 'ui_grid')->name('ui_grid');
        Route::get('/ui-list-group', 'ui_list_group')->name('ui_list_group');
        Route::get('/ui-modal', 'ui_modal')->name('ui_modal');
        Route::get('/ui-pagination', 'ui_pagination')->name('ui_pagination');
        Route::get('/ui-popover', 'ui_popover')->name('ui_popover');
        Route::get('/ui-progressbar', 'ui_progressbar')->name('ui_progressbar');
        Route::get('/ui-tab', 'ui_tab')->name('ui_tab');
        Route::get('/ui-typography', 'ui_typography')->name('ui_typography');
        Route::get('/user', 'user')->name('user');
        Route::get('/widget-basic', 'widget_basic')->name('widget_basic');


        Route::post('/ajax/contacts', 'ajax_contacts')->name('ajax_contacts');
        Route::post('/ajax/message', 'ajax_message')->name('ajax_message');

    });
