<?php

use App\Helpers\AdminFeatures;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminProxyController;
use App\Http\Controllers\AdminSearchController;
use App\Http\Controllers\Api\AppSiteSearchController;
use App\Http\Controllers\Api\TagSearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomingInvoiceController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('app/search', AppSiteSearchController::class)->name('app.search');

Route::redirect('/', '/dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::post('admins/{admin}/reinvite', [AdminController::class, 'reinvite'])->name('admins.reinvite');
Route::post('admins/{admin}/unblock', [AdminController::class, 'unblock'])->name('admins.unblock');
Route::patch('admins/{admin}/restore', [AdminController::class, 'restore'])->name('admins.restore');
Route::delete('admins/{admin}/force-delete', [AdminController::class, 'forceDelete'])->name('admins.force-delete');
Route::delete('admins/{admin}/delete-photo', [AdminController::class, 'deletePhoto'])->name('admins.deletePhoto');
Route::resource('admins', AdminController::class);

// Invoices
Route::delete('incoming/{incoming}/force-delete', [IncomingInvoiceController::class, 'forceDelete'])->withTrashed()->name('incoming.force-delete');
Route::patch('incoming/{incoming}/restore', [IncomingInvoiceController::class, 'restore'])->withTrashed()->name('incoming.restore');
Route::resource('incoming', IncomingInvoiceController::class)->only('index', 'create', 'edit', 'store', 'update', 'destroy');

Route::delete('invoices/{invoice}/force-delete', [InvoiceController::class, 'forceDelete'])->withTrashed()->name('invoices.force-delete');
Route::patch('invoices/{invoice}/restore', [InvoiceController::class, 'restore'])->withTrashed()->name('invoices.restore');
Route::resource('invoices', InvoiceController::class)->only('index', 'create', 'edit', 'store', 'update', 'destroy');

if(AdminFeatures::hasLogs()) {
	Route::resource('logs', LogController::class)->only('index');
}
// Impersonation
Route::get('/admin-proxy-enter/{id}', [AdminProxyController::class, 'enter'])->name('admin-proxy.enter');
Route::get('/admin-proxy-exit', [AdminProxyController::class, 'exit'])->name('admin-proxy.exit');

Route::get('admins-search', AdminSearchController::class)->name('admins.search');

// Tags search - TagCombobox.vue
Route::get('/tags/search/{locale}/{type?}', TagSearchController::class)->name('tags.search');

