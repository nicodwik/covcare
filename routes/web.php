<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FrontpageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.main');
});
Route::get('/daftar', [FrontpageController::class, 'register']);
Route::get('/cek-status', function () {
    return view('pages.check');
})->name('check.page');
Route::get('/status-check', [BookingController::class, 'check'])->name('check');
Route::get('/quota-check', [BookingController::class, 'quotaCheck'])->name('quota.check');
Route::get('confirm-atendee', [BookingController::class, 'confirmAtendee'])->name('confirm.atendee');
Route::get('/login', function () {
    return view('pages.login');
})->name('login.page');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::prefix('admin')->middleware(['auth.user'])->group(function () {
    Route::get('/cek-status', function () {
        return view('pages.admin-check');
    })->name('admin.check.page');
    Route::get('dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});
