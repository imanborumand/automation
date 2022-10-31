<?php

use App\Http\Controllers\Api\V1\Document\DocumentController;
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

Route::prefix('documents')->group(function() {
    Route::get('/assign-list', [DocumentController::class , 'assignList'])
         ->name('documents.assign.list');

    Route::post('/assign', [DocumentController::class , 'assign'])
         ->name('documents.assign');
});


