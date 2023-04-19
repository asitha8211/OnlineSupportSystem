<?php

use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TicketsController;

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

Route::post('/ticket', [TicketsController::class, 'openTicket']);
Route::get('/ticket/{reference_id}', [TicketsController::class, 'getTicket']);
Route::post('/login', [UserController::class, 'login']);

Route::group(
    ['middleware' => 'auth:api'],
    function () {
        Route::get('/tickets', [TicketsController::class, 'getTickets'])->middleware('is.supportAgent');
        Route::post('/ticket/{reference_id}/reply', [TicketsController::class, 'sendReply'])->middleware('is.supportAgent');
    });
