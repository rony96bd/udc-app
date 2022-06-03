<?php

use App\Http\Controllers\pages\HomeController;
use App\Http\Controllers\pages\UserController;
use App\Http\Controllers\pages\PayController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Redirect::route('login');
});

Route::get('users/{id}', function ($id) {
    $user = User::where('id', $id)->get();

    return response()->json($user);
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/alluser', [UserController::class, 'index'])->name('allUser');
    Route::get('/payinfo', [PayController::class, 'index'])->name('payinfo');
    Route::post('/add', [HomeController::class, 'addData'])->name('addBr');
    Route::post('/reject_all', [HomeController::class, 'rejectAll'])->name('rejectAll');
    Route::post('/approve_all', [HomeController::class, 'approveAll'])->name('approveAll');
    Route::post('/delete_all', [HomeController::class, 'deleteAll'])->name('deleteAll');
    Route::post('/update', [HomeController::class, 'updateData'])->name('updBr');
    Route::delete('/delete_br/{id}', [HomeController::class, 'deleteData'])->name('deleteBr');
    Route::get('/change-password', [HomeController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [HomeController::class, 'updatePassword'])->name('update-password');
    Route::get('/add-payment', [PayController::class, 'paymentShow'])->name('payment-show');
    Route::post('/add-payment', [PayController::class, 'addPayment'])->name('add-payment');
    Route::post('/update-payment', [PayController::class, 'updatePayment'])->name('update-payment');
    Route::post('/update-status', [PayController::class, 'updateStatus'])->name('update-status');
});



require __DIR__ . '/auth.php';
