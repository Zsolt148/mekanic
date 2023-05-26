<?php

use App\Http\Controllers\Api\LogTableController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\IncomingInvoiceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\Api\ServiceTableController;
use Illuminate\Support\Facades\Route;

Route::get('logs/table', LogTableController::class)->name('logs.table');
Route::get('services/table', ServiceTableController::class)->name('services.table');

Route::get('incoming/table', [IncomingInvoiceController::class, 'getTableData'])->name('incoming.table');
Route::get('invoices/table', [InvoiceController::class, 'getTableData'])->name('invoices.table');

