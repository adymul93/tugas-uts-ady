<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post("/login", [AuthController::class, 'login']);
Route::post("/register", [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('web')
    ->group(function () {
        Route::get("/oauth/register", [AuthController::class, 'googleRegister']);
        Route::get("/oauth/register/callback", [AuthController::class, 'googleRegisterCallback']);
    });

Route::middleware('auth:sanctum')
    ->group(function () {
        Route::middleware('auth.permit:admin')
            ->resource("/categories", CategoryController::class);

        Route::resource("/products", ProductController::class);
    });
