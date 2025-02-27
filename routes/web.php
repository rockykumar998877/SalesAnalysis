<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
Route::post('/sales/import', [SalesController::class, 'importCSV']);
Route::get('/sales/chart-data', [SalesController::class, 'getSalesChartData']);

Route::get('/sales-report', [SalesController::class, 'processSalesData'])->name('sales.report');

