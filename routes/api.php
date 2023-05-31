<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('shipments', 'App\Http\Controllers\ShipmentsController@store');

Route::put('shipments', 'App\Http\Controllers\ShipmentsController@update');

Route::get('shipments', 'App\Http\Controllers\ShipmentsController@index');

Route::get('shipments/{tracking_number}', 'App\Http\Controllers\ShipmentsController@show');


Route::post('shipment-items', 'App\Http\Controllers\ShipmentItemsController@store');

Route::put('shipment-items', 'App\Http\Controllers\ShipmentItemsController@update');

Route::get('shipment-items/{shipment_id}', 'App\Http\Controllers\ShipmentItemsController@shipmentItems');


Route::post('shipment-tracker', 'App\Http\Controllers\ShipmentTrackerController@store');

Route::put('shipment-tracker', 'App\Http\Controllers\ShipmentTrackerController@update');

Route::get('shipment-tracker/{shipment_id}', 'App\Http\Controllers\ShipmentTrackerController@show');



