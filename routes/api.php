<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MflController;
use App\Http\Controllers\ECHISWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// MFL API Routes
Route::prefix('mfl')->group(function () {
    Route::get('/facilities', [MflController::class, 'getFacilities']);
    Route::get('/facilities/{id}', [MflController::class, 'getFacility']);
    Route::get('/community-health-units', [MflController::class, 'getCommunityHealthUnits']);
    Route::post('/sync', [MflController::class, 'syncFacilities']);
});

// eCHIS Webhook Route
Route::post('webhooks/echis', [ECHISWebhookController::class, 'handle'])
    ->name('webhooks.echis'); 