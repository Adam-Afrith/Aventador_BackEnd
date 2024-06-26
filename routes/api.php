<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\FileController;

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

//Resources:

Route::resources([
    'company' => CompanyController::class,
    'bike' => BikeController::class,
    'owner' => OwnerController::class,
    'file' => FileController::class,
]);


//APIs:

Route::get('companylist',[CompanyController::class,'getCompanyList']);
Route::get('bikelist',[BikeController::class,'getAllBikeList']);
Route::get('bike/list/{id}',[BikeController::class,'getBikeList']);
Route::get('bikemodel/list',[BikeController::class,'getBikeModel']);

Route::post('fileupload',[FileController::class,'fileupload']);
Route::put('updatefile',[FileController::class,'updateFile']);
Route::get('download/{id}/{filename}',[FileController::class,'download']);

//Auth - Login & Register
Route::post('custom-login', [AuthenticationController::class, 'customLogin']); 
Route::get('signout', [AuthenticationController::class, 'signOut'])->name('signout');
