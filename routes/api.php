<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\Auth\ApiOtpController;
use App\Http\Controllers\API\Auth\WilayaController;
use App\Http\Controllers\API\BloodRequestController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\Auth\RecoverPhoneNumberController;
/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
 */

Broadcast::routes(['middleware' => ['auth:sanctum']]);
 
Route::post('/accepteDemande', [BloodRequestController::class, 'accepterDemande']);
Route::post('/find-donors', [BloodRequestController::class, 'findNearbyDonors']); 
Route::post('/update-location', [BloodRequestController::class, 'updateLocation']);
Route::post('/updatePhone', [BloodRequestController::class, 'updatePhone']);


Route::post('login', [LoginController::class, 'login']);
Route::post('register',  [RegisterController::class, 'register']);
Route::post('checkPhoneExists',  [RegisterController::class, 'checkPhoneExists']);

Route::post('/request-otp', [ApiOtpController::class, 'sendOtp']);
Route::post('/verify-otp', [ApiOtpController::class, 'verifyOtp']);


Route::get('/communes/{wilayaCode}', [WilayaController::class, 'getCommunesByWilaya']);
Route::get('/wilayas', [WilayaController::class, 'getWilayas']);
Route::get('/get-pseudos/{idUser}', [RecoverPhoneNumberController::class, 'getPseudos']);
Route::get('/get-last-dons/{idUser}', [RecoverPhoneNumberController::class, 'getLastDons']);
Route::post('/validate-info-recover', [RecoverPhoneNumberController::class, 'validateInfoRecover']);
Route::post('/recover-validate-number', [RecoverPhoneNumberController::class, 'recoverValidateInfo']);
 


/*
Route::group(['middleware' => 'api.auth'], function () {
    Route::get('user', [LoginController::class, 'details']);
    Route::get('logout', [LoginController::class, 'logout']);


});*/
