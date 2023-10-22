<?php

use App\Modules\Invoices\Application\Controller\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Modules\Approval\Application\Controller\ApproveController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', static function () {
    return 'test';
});

Route::get('/invoice/{invoice}', [InvoiceController::class,'show'])->name('invoice.show');
Route::get('/invoice', [InvoiceController::class,'index'])->name('invoice.index');
Route::get('/invoice/approve/{invoice}', [InvoiceController::class,'approve'])->name('invoice.approve');
Route::get('/invoice/reject/{invoice}', [InvoiceController::class,'reject'])->name('invoice.reject');
