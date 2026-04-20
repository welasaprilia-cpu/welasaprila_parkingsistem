<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SpotsController;
use App\Http\Controllers\ReservationsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\ReportController;


// Guest
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Auth
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');

    Route::resource('users', \App\Http\Controllers\UsersController::class);
    Route::resource('spots', \App\Http\Controllers\SpotsController::class)->only(['index']);
    Route::resource('reservations', \App\Http\Controllers\ReservationsController::class);
    Route::resource('payments', \App\Http\Controllers\PaymentsController::class)->only(['index', 'show'])->names('payments');
    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');

        
    Route::get('/parking', [App\Http\Controllers\ParkingController::class, 'index'])->name('parking.index');
    Route::post('/parking', [App\Http\Controllers\ParkingController::class, 'store'])->name('parking.store');
    Route::delete('/parkings/{parking}', [App\Http\Controllers\ParkingController::class, 'exit'])->name('parkings.exit');
});
