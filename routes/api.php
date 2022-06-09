
<?php

use App\Http\Controllers\NitroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//dd("a");

Route::post('/auth/send-otp',[NitroController::class,'sendOtp']);
Route::post('/auth/login',[NitroController::class,'login']);
Route::get('/auth/logout',[NitroController::class,'logout'])->middleware('checkToken');
Route::get('/auth/failed-logout',[NitroController::class,'failed_logout'])->name('failed_logout');
Route::get('/products',[NitroController::class,'index']);
Route::get('/products/category',[NitroController::class,'category']);
Route::post('/orders/new',[NitroController::class,'newOrder']);
Route::get('/orders',[NitroController::class,'orders']);
Route::get('/dashboard',[NitroController::class,'dashboard']);
Route::get('/payments',[NitroController::class,'Payment']);
Route::get('/payments/new',[NitroController::class,'newPayment']);










