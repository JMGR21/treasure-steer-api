<?php

use App\Http\Controllers\Api\UserController;

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

Route::middleware('auth:sanctum')->group(function(){
    Route::post('user/logout',[UserController::class, 'logout']);
    Route::get('user',function(Request $request){
        return response()->json([
            "status" => "ok",
            "data" => $request->user()
        ]);
    });
});

// Users
Route::post('user/register', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'login']);