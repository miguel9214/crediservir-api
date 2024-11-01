<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\WaitingListController;
use App\Http\Controllers\AditionalController;

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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']); // Si necesitas registro
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me'])->middleware('auth:api');
});

Route::group(['middleware' => 'auth'], function () {
    // Gestión de eventos
    Route::resource('events', EventController::class);

    // Gestión de asistentes
    Route::resource('attendees', AttendeeController::class);

    //Categorias
    Route::resource('categories', CategoryController::class);

    Route::resource('aditionalPrice', AditionalController::class);

    //Lista de espera
    Route::resource('waitings', WaitingListController::class);

    //Codigo de descuetos
    Route::resource('discounts', DiscountCodeController::class);
    Route::post('/discounts/validate', [DiscountCodeController::class, 'validateCode']);


    // Pagos
    Route::get('events/{id}/details', [PaymentController::class, 'showEvent']);
    Route::post('events/{id}/purchase', [PaymentController::class, 'purchaseTicket']);

    Route::get('purchases', [PaymentController::class, 'getPurchases']);
    // Route::get('purchases', [PaymentController::class, 'getDiscount']);
});


