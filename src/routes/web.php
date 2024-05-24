<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WorkController;
use App\Http\Controllers\AttendanceController;

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

//fortify
Route::middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home.index');
});

//勤務開始
Route::post('/', [WorkController::class, 'store'])->name('work.store');


//日付別勤怠ページ
Route::get('/attendance', [AttendanceController::class, 'attendance'])->name('attendance.attendance');

//日付変更のルート
Route::get('/attendance/changeData/{date}', [AttendanceController::class, 'changeDate'])->name('attendance.changeDate');
