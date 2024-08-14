<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/api/detail/{id}', [\App\Http\Controllers\UserController::class, 'detail'])->name('detail');

Route::get('/api/list', [\App\Http\Controllers\UserController::class, 'list'])->name('list');

Route::post('/api/report', [\App\Http\Controllers\ReportController::class, 'generateReport'])->name('generateReport');


Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

Route::post('/api/login', [\App\Http\Controllers\AuthenticationController::class, 'login'])->name('login');

Route::post('/api/create', [\App\Http\Controllers\UserController::class, 'create'])->name('create');

Route::post('/api/set-availability', [\App\Http\Controllers\UserController::class, 'setAvailability'])->name('set_availability');

Route::post('/api/upload', [\App\Http\Controllers\UploadController::class, 'upload'])->name('upload');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/api/schedule/{id}', [\App\Http\Controllers\ScheduleSessionController::class, 'create'])->name('session.create');
    Route::post('/api/logout', [\App\Http\Controllers\AuthenticationController::class, 'logout'])->name('logout');
    Route::post('/api/rating', [\App\Http\Controllers\RatingController::class, 'create'])->name('rating.create');
});
