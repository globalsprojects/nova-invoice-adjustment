<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/config', 'GlobalsProjects\InvoiceAdjustment\Http\Controllers\InvoiceAdjustmentController@config');
Route::post('/apply', 'GlobalsProjects\InvoiceAdjustment\Http\Controllers\InvoiceAdjustmentController@apply');