<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\OrganizerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Google Sync
Route::post('/google-form-sync', [BookingController::class, 'gFormSync']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/admin/login', function(Request $request) {
    return app(AuthController::class)->login($request, 'admin');
});

Route::post('/organizer/login', function(Request $request) {
    return app(AuthController::class)->login($request, 'organizer');
});

Route::post('/marshal/login', function(Request $request) {
    return app(AuthController::class)->login($request, 'marshal');
});

Route::post('/login', function(Request $request) {
    return app(AuthController::class)->login($request, null);
});

Route::get('/packages/{package}/available-slots', [BusinessController::class, 'getAvailableSlots']);
Route::get('/action-logs-chart', [OrganizerController::class, 'actionLogsChart']);