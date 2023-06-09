<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Models\Product;
use App\Models\Transaction;
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

Route::resource('transaction', TransactionController::class);
Route::get('product', [ProductController::class, 'getProduct']);
Route::get('transaction/search/{name?}', [TransactionController::class, 'search']);
