<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Login/logout
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/logout', [AuthController::class, 'logout']);

Route::middleware('cookie')->group(function () {
    // DEFAULT App entrypoint
    Route::get('/', [AuthController::class, 'default'])->name('default');

    // DASHBOARD
    Route::get('/dashboard', [AppController::class, 'showDashboard'])->name('home');

    // INFO
    Route::get('/info', [AppController::class, 'showPropertyInfo']);

    // CP12
    Route::get('/cp12', [AppController::class, 'showCP12']);

    // AGENT
    Route::get('/agent', [AppController::class, 'showAgentDetails']);

    // REPORT
    Route::get('/report', [AppController::class, 'showReportForm']);
    Route::post('/report', [AppController::class, 'submitReport']);

    // RENT
    Route::get('/rent', [AppController::class, 'showRentStatement']);

    // BOND
    Route::get('/bond', [AppController::class, 'showBondStatement']);
    Route::get('/bond-data', [AppController::class, 'getBondData']);

    // INSPECTIONS
    Route::get('/inspections', [AppController::class, 'showInspections']);
    Route::get('/inspections/{id}/signature', [AppController::class, 'getSignatureImage']);

    // UPDATE
    Route::get('/update', [AppController::class, 'showUpdateDetailsForm']);
    Route::post('/update', [AppController::class, 'submitUpdateDetails']);

});
