<?php

use App\Http\Controllers\CRM\ClientController;
use App\Http\Controllers\CRM\DashboardController;
use App\Http\Controllers\CRM\DealController;
use App\Http\Controllers\CRM\LeadController;
use App\Http\Controllers\CRM\PropertyController;
use App\Http\Controllers\CRM\SiteVisitController;

Route::middleware(['auth', 'verified'])
    ->prefix('crm')
    ->name('crm.')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Clients
        Route::resource('clients', ClientController::class);

        // Leads
        Route::resource('leads', LeadController::class);
        Route::patch('leads/{lead}/convert', [LeadController::class, 'convert'])
            ->name('leads.convert');
        Route::patch('leads/{lead}/assign', [LeadController::class, 'assign'])
            ->name('leads.assign');

        // Properties
        Route::resource('properties', PropertyController::class);

        // Deals
        Route::resource('deals', DealController::class);
        Route::patch('deals/{deal}/stage', [DealController::class, 'updateStage'])
            ->name('deals.stage');

        // Site Visits
        Route::resource('site-visits', SiteVisitController::class);
        Route::patch('site-visits/{visit}/complete', [SiteVisitController::class, 'markComplete'])
            ->name('site-visits.complete');

    });
