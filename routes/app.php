<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

// DASHBOARD #############################################################################
Route::get ('/dashboard', [AppController::class,'showDashboard'])->name('home');

// INFO ##################################################################################
Route::get ('/info', [AppController::class,'showPropertyInfo']);

// CP12 ##################################################################################
Route::get ('/cp12', [AppController::class,'showCP12']);

// AGENT #################################################################################
Route::get ('/agent', [AppController::class,'showAgentDetails']);

// REPORT ################################################################################
Route::get ('/report', [AppController::class,'showReportForm']);
Route::post('/report', [AppController::class,'submitReport']);

// RENT ##################################################################################
Route::get ('/rent', [AppController::class,'showRentStatement']);

// BOND ##################################################################################
Route::get ('/bond', [AppController::class,'showBondStatement']);

// INSPECTIONS ###########################################################################
Route::get ('/inspections', [AppController::class,'showInspections']);
Route::get ('/inspections/{id}/signature', [AppController::class,'getSignatureImage']);

// UPDATE #################################################################################
Route::get ('/update', [AppController::class,'showUpdateDetailsForm']);
Route::post ('/update', [AppController::class,'submitUpdateDetails']);
