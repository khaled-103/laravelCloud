<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('uploadImage');
})->name('uploadImage');
Route::view('/getImage','getImage')->name('getImage');

Route::controller(ImageController::class)->group(function(){
    Route::get('/allKeys','allKeys')->name('allKeys');
    Route::get('/configration','configration')->name('configration');
    Route::get('/statistics','statistics')->name('statistics');
    Route::post('/storeImage','storeImage')->name('storeImage');
    Route::post('/getImage','getImage')->name('getImage');
    Route::post('/configration','storeConfig')->name('storeConfig');
    Route::post('/storeStatistics','storeStatistics');
    Route::post('/clearCache','clearCache')->name('clearCache');
});
