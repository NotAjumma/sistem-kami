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
use App\Http\Controllers\OrganizerBusinessController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\SuperadminController;
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

// Route::get('/send-test-mail', function () {
//     Mail::to('muhamadakmal9973@gmail.com')->send(new TestMail());
//     return 'Test email sent!';
// });

Route::get('/migrate-images', function () {

    $source = public_path('images/uploads');
    $destination = storage_path('app/public/uploads');

    if (!File::exists($destination)) {
        File::makeDirectory($destination, 0755, true);
    }

    File::copyDirectory($source, $destination);

    return 'Images migrated successfully!';
});

Route::get('/link-storage', function () {
    Artisan::call('storage:link');
    return 'linked';
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

// ── Superadmin ───────────────────────────────────────────────────────────────
Route::prefix('superadmin')->group(function () {
    // Public
    Route::get('/login',  [SuperadminController::class, 'showLogin'])->name('superadmin.login');
    Route::post('/login', [SuperadminController::class, 'login'])->name('superadmin.login.post');

    // Protected
    Route::middleware('superadmin')->group(function () {
        Route::post('/logout',                              [SuperadminController::class, 'logout'])->name('superadmin.logout');
        Route::get('/dashboard',                            [SuperadminController::class, 'dashboard'])->name('superadmin.dashboard');
        Route::get('/organizers',                           [SuperadminController::class, 'organizers'])->name('superadmin.organizers');
        Route::get('/organizers/create',                    [SuperadminController::class, 'createOrganizer'])->name('superadmin.organizer.create');
        Route::post('/organizers',                          [SuperadminController::class, 'storeOrganizer'])->name('superadmin.organizer.store');
        Route::get('/organizers/{id}',                      [SuperadminController::class, 'organizerDetail'])->name('superadmin.organizer.detail');
        Route::get('/organizers/{id}/edit',                 [SuperadminController::class, 'editOrganizer'])->name('superadmin.organizer.edit');
        Route::patch('/organizers/{id}',                    [SuperadminController::class, 'updateOrganizer'])->name('superadmin.organizer.update');
        Route::delete('/organizers/{id}',                   [SuperadminController::class, 'destroyOrganizer'])->name('superadmin.organizer.destroy');
        Route::get('/organizers/{id}/impersonate',          [SuperadminController::class, 'impersonate'])->name('superadmin.impersonate');
        Route::get('/stop-impersonate',                     [SuperadminController::class, 'stopImpersonate'])->name('superadmin.stop-impersonate');
        Route::get('/settings',                             [SuperadminController::class, 'showSettings'])->name('superadmin.settings');
        Route::post('/settings',                            [SuperadminController::class, 'saveSettings'])->name('superadmin.settings.save');
        Route::get('/upload-image',                         [SuperadminController::class, 'showUploadImage'])->name('superadmin.upload-image');
        Route::post('/upload-image',                        [ImageUploadController::class, 'upload'])->name('superadmin.upload-image.post');
        Route::get('/health-check',                         [SuperadminController::class, 'healthCheck'])->name('superadmin.health-check');
        Route::get('/reminders',                            [SuperadminController::class, 'showReminders'])->name('superadmin.reminders');
        Route::post('/reminders/trigger',                   [SuperadminController::class, 'triggerReminders'])->name('superadmin.reminders.trigger');
        Route::get('/commands',                             [SuperadminController::class, 'showCommands'])->name('superadmin.commands');
        Route::post('/commands/run',                        [SuperadminController::class, 'runCommand'])->name('superadmin.commands.run');
        Route::get('/commands/log/{key}',                   [SuperadminController::class, 'readCommandLog'])->name('superadmin.commands.log');
    });
});

// Used route
Route::get('/checkout2', [ToyyibpayController::class, 'createBill'])->name('toyyibpay.checkout');
Route::get('/toyyibpay-status', [ToyyibpayController::class, 'paymentStatus'])->name('toyyibpay.status');
Route::get('/toyyibpay-callback', [ToyyibpayController::class, 'callback'])->name('toyyibpay.callback');
Route::get('/toyyibpay/callback', [BookingController::class, 'handleCallback'])->name('toyyibpay.booking.callback');
Route::get('/booking/receipt/{booking_code}', [BookingController::class, 'bookingReceipt'])->name('booking.receipt');
Route::get('/receipt/{booking_code}', [BookingController::class, 'bookingReceiptPackage'])->name('booking.receipt.package');
Route::post('/webform-booking', [BookingController::class, 'webFormBooking'])->name('webform.booking');
Route::post('/tickets/select', [BookingController::class, 'storeSelection'])->name('tickets.select');
Route::get('/checkout', [BookingController::class, 'showCheckout'])->name('checkout');
// ── Locale-aware public routes ────────────────────────────────────────────────
$homeRoutes = function () {
    Route::get('/',                     [HomeController::class, 'index'])->name('index');
    Route::get('/about',                [HomeController::class, 'about'])->name('about');
    Route::get('/faq',                  [HomeController::class, 'faq'])->name('faq');
    Route::get('/privacy-policy',       [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
    Route::get('/terms-and-conditions', [HomeController::class, 'terms'])->name('terms');
    Route::get('/search',               [HomeController::class, 'search'])->name('search');
};

// Bahasa Melayu home routes first (fixed bm/ prefix, more specific)
Route::group(['prefix' => 'bm', 'as' => 'bm.', 'locale' => 'ms', 'middleware' => 'setlocale'], $homeRoutes);

// English home routes (no prefix)
Route::group(['locale' => 'en', 'middleware' => 'setlocale'], $homeRoutes);
Route::get('/qr/{slug}', function ($slug) {
    $organizer = \App\Models\Organizer::where('slug', $slug)->whereNotNull('payment_qr_path')->firstOrFail();
    $path      = \Illuminate\Support\Facades\Storage::disk('public')->path($organizer->payment_qr_path);
    $mime      = mime_content_type($path) ?: 'image/jpeg';
    return response()->file($path, ['Content-Type' => $mime]);
})->name('organizer.payment.qr');

Route::get('/{slug}/leaderboard', [EventController::class, 'showFishingLeaderboard'])->name('event.fishing.leaderboard');
Route::get('/{slug}', [EventController::class, 'showBySlug'])->name('event.slug');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/receipt/pdf/{booking_code}', function ($booking_code) {

    $booking = Booking::with('vendorTimeSlots')
        ->where('booking_code', $booking_code)
        ->firstOrFail();

    $pdf = PDF::loadView('emails.package_pdf', [
        'booking' => $booking
    ]);

    return $pdf->stream($booking_code . '_receipt.pdf');
    // return view('emails.package_pdf', compact('booking'));

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
    Route::match(['get', 'post'], '/fishing/keyinweight', 'fishingKeyInWeight')->name('worker.fishing.key_in_weight');
    Route::get('/fishing/leaderboard', 'showFishingLeaderboard')->name('worker.fishing.leaderboard');
});
Route::get('/fishing/leaderboard/latest-update', function () {
    $timestamp = \App\Models\FishingLeaderboardResult::max('updated_at');
    return response()->json(['updated_at' => $timestamp]);
});
// Route::get('/fishing/leaderboard/partial', 'App\Http\Controllers\Worker\FishingController@renderLeaderboardPartial'); // disabled: controller missing

Route::prefix('organizer')->middleware('auth:organizer')->controller(OrganizerController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('organizer.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('organizer.logout');
    Route::get('/bookings', 'bookings')->name('organizer.bookings');
    Route::get('/booking/{id}', [OrganizerController::class, 'showBooking'])->name('organizer.booking.show');
    Route::get('/booking/{id}/edit', [OrganizerController::class, 'editBooking'])->name('organizer.booking.edit');
    Route::patch('/booking/{id}/update', [OrganizerController::class, 'updateBooking'])->name('organizer.booking.update');
    Route::patch('/booking/{id}/verify', [OrganizerController::class, 'verifyPayment'])->name('organizer.booking.verify');
    Route::get('/tickets/confirmed', 'ticketsConfirmed')->name('organizer.tickets.confirmed');
    Route::patch('/ticket/{id}/check-in', 'ticketCheckin')->name('organizer.ticket.checkin');


    Route::get('/preview-ticket/{booking}', function ($bookingId) {
        $booking = Booking::findOrFail($bookingId);

        \Log::info($booking);
        $pdf = PDF::loadView('emails.ticket_pdf', ['booking' => $booking]);

        return $pdf->stream('ticket.pdf'); // open inline in browser
    });
    // Similar for organizer, marshal, participant...
});
Route::get('/calendar/holidays', [OrganizerBusinessController::class, 'holidays']);
Route::post('/track/whatsapp', [BusinessController::class, 'whatsappNow']);
Route::post('/visitor-log', [HomeController::class, 'log'])->name('visitor.log');

Route::prefix('organizer/business')->middleware('auth:organizer')->controller(OrganizerBusinessController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('organizer.business.dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('organizer.business.logout');
    Route::get('/bookings', 'bookings')->name('organizer.business.bookings');
    Route::get('/calender', 'calender')->name('organizer.business.calender');
    Route::get('/booking/create', 'showCreateBooking')->name('organizer.business.booking.create');
    Route::post('/booking/create', [BookingController::class, 'webFormBookingPackageByAdmin'])->name('organizer.business.booking.create.send');
    Route::get('/bookings/json', [OrganizerBusinessController::class, 'getBookingsJson']);
    Route::patch('/booking/{id}/cancel', [OrganizerBusinessController::class, 'cancelBooking'])->name('organizer.business.booking.cancel');
    Route::patch('/booking/{id}/send-receipt', [OrganizerBusinessController::class, 'sendReceipt'])->name('organizer.business.booking.send-receipt');
    Route::get('/packages/{package}/form-fields', [BookingController::class, 'getFormFields']);
    
    // Package
    Route::get('/packages/create', 'showCreatePackage')->name('organizer.business.package.create');
    Route::post('/packages/create', 'storePackage')->name('organizer.business.package.store');
    Route::get('/packages', 'showPackages')->name('organizer.business.packages');
    Route::get('/packages/{id}/edit', 'showEditPackage')->name('organizer.business.package.edit');
    Route::patch('/packages/{id}', 'updatePackage')->name('organizer.business.package.update');
    Route::delete('/packages/{id}', 'destroyPackage')->name('organizer.business.package.destroy');
    Route::get('/packages/{id}/calendar-data', 'fetchCalendarData')->name('organizer.business.package.calendar.data');
    Route::post('/packages/{id}/upload-image', 'uploadPackageImage')->name('organizer.business.package.upload-image');
    Route::post('/packages/upload-temp-image', 'uploadTempImage')->name('organizer.business.package.upload-temp-image');

    Route::get('/bookings/json-public', [OrganizerBusinessController::class, 'getBookingsJson']);
    Route::get('/booking/{id}', [OrganizerBusinessController::class, 'showBooking'])->name('organizer.business.booking.show');
    Route::get('/booking/{id}/edit', [OrganizerBusinessController::class, 'editBooking'])->name('organizer.business.booking.edit');
    Route::patch('/booking/{id}/update', [OrganizerBusinessController::class, 'updateBooking'])->name('organizer.business.booking.update');
    Route::patch('/booking/{id}/verify', [OrganizerController::class, 'verifyPayment'])->name('organizer.business.booking.verify');
    Route::patch('/booking/{id}/full_payment', 'makeFullPayment')->name('organizer.business.booking.full_payment');
    Route::get('/tickets/confirmed', 'ticketsConfirmed')->name('organizer.business.tickets.confirmed');
    Route::patch('/ticket/{id}/check-in', 'ticketCheckin')->name('organizer.business.ticket.checkin');
    // Route::patch('/booking/{id}/cancel', [OrganizerController::class, 'cancelBooking'])->name('organizer.business.booking.cancel');

    Route::get('/report/overview', 'overviewReport')->name('organizer.business.report.overview');
    Route::get('/report/package-chart', 'packageChartData')->name('organizer.business.report.package.chart');
    Route::get('/report/package-addon-chart', 'addOnChartData')->name('organizer.business.report.package.addon.chart');
    Route::get('/report/slot-chart', 'slotChartData')->name('organizer.business.report.slot.chart');


    Route::get('commission/setup', [\App\Http\Controllers\Admin\CommissionSetupController::class, 'index'])->name('commission.setup');
    Route::post('commission/setup', [\App\Http\Controllers\Admin\CommissionSetupController::class, 'store'])->name('commission.setup.store');
    Route::post('commission/setup/promoter', [\App\Http\Controllers\Admin\CommissionSetupController::class, 'savePromoter'])->name('commission.setup.promoter');
    Route::put('commission/rule/{commissionRule}', [\App\Http\Controllers\Admin\CommissionSetupController::class, 'update'])->name('commission.rule.update');
    Route::delete('commission/rule/{commissionRule}', [\App\Http\Controllers\Admin\CommissionSetupController::class, 'destroy'])->name('commission.rule.delete');
    Route::get('/package/{id}/addons', [\App\Http\Controllers\Admin\CommissionSetupController::class, 'getAddons']);

    Route::get('commission/report', [\App\Http\Controllers\Admin\CommissionReportController::class, 'index'])->name('commission.report');
    Route::get('/transactions', [OrganizerBusinessController::class, 'getTransactions'])->name('organizer.transactions.index');
    Route::post('/wallet/withdraw', [OrganizerBusinessController::class, 'storeWithdraw'])->name('organizer.withdraw.store');
    Route::get('/settings', [OrganizerBusinessController::class, 'showSettings'])->name('organizer.business.settings');
    Route::post('/settings', [OrganizerBusinessController::class, 'updateSettings'])->name('organizer.business.settings.update');
    Route::post('/settings/off-days', [OrganizerBusinessController::class, 'storeOffDay'])->name('organizer.business.settings.off-days.store');
    Route::delete('/settings/off-days/{id}', [OrganizerBusinessController::class, 'destroyOffDay'])->name('organizer.business.settings.off-days.destroy');

    // Time Slots
    Route::get('/time-slots', [OrganizerBusinessController::class, 'showTimeSlots'])->name('organizer.business.time-slots');
    Route::post('/time-slots', [OrganizerBusinessController::class, 'storeTimeSlot'])->name('organizer.business.time-slots.store');
    Route::patch('/time-slots/{id}', [OrganizerBusinessController::class, 'updateTimeSlot'])->name('organizer.business.time-slots.update');
    Route::delete('/time-slots/{id}', [OrganizerBusinessController::class, 'destroyTimeSlot'])->name('organizer.business.time-slots.destroy');
    Route::post('/time-slots/{id}/images', [OrganizerBusinessController::class, 'uploadSlotImage'])->name('organizer.business.time-slots.image.upload');
    Route::delete('/time-slots/{slotId}/images/{imageId}', [OrganizerBusinessController::class, 'destroySlotImage'])->name('organizer.business.time-slots.image.destroy');

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

// Public profile
Route::get('/packages/{id}/calendar-data', [BusinessController::class, 'fetchCalendarData'])->name('business.profile.package.calendar.data');
Route::get('/private/{slug}', [BusinessController::class, 'showProfile'])->name('business.profile.private');
Route::get('/private/{organizerSlug}/{packageSlug}', [BusinessController::class, 'showPackage'])->name('business.package.private');
Route::get('/checkout/package', [BookingController::class, 'showCheckoutPackage'])->name('business.checkout_package');
Route::post('/select/package', [BookingController::class, 'storeSelectionPackage'])->name('business.select_package');
Route::post('/webform/booking', [BookingController::class, 'webFormBookingPackage'])->name('webform.booking_package');

// Locale-aware profile routes — BM first (fixed prefix, more specific) then EN (wildcard)
$profileRoutes = function () {
    Route::get('/{slug}',                                [BusinessController::class, 'showProfile'])->name('business.profile');
    Route::get('/{organizerSlug}/{packageSlug}',         [BusinessController::class, 'showPackage'])->name('business.package');
    Route::get('/{organizerSlug}/{packageSlug}/booking', [BusinessController::class, 'showBooking'])->name('business.booking');
};
// BM registered first so bm/{slug} is matched before EN's /{organizerSlug}/{packageSlug} can catch bm/xxx
Route::group(['prefix' => 'bm', 'as' => 'bm.', 'locale' => 'ms', 'middleware' => 'setlocale'], $profileRoutes);
Route::group(['locale' => 'en', 'middleware' => 'setlocale'], $profileRoutes);
Route::get('/organizer/{id}/banners', [BusinessController::class, 'getBanners']);
Route::get('/organizer/{id}/packages/images', [BusinessController::class, 'getPackageImages']);
Route::get('/organizer/{id}/slots/images', [BusinessController::class, 'getSlotImages']);


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
