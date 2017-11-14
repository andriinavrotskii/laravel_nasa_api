<?php

use Illuminate\Http\Request;

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


Route::get('/', 'DefaultController@index');

Route::prefix('neo')->group(function() {
    Route::get('hazardous', 'NeoController@hazardous');
    Route::get('fastest', 'NeoController@fastest');
});
