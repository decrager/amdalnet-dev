<?php

use App\Http\Controllers\ExportDocument;
use App\Http\Controllers\LaravueController;
use App\Http\Controllers\WebgisController;
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
    return view('welcome');
});

Route::get('doc-uklupl/{id}', [ExportDocument::class, 'ExportUklUpl']);
Route::get('form-ka/{id}/{doc}', [ExportDocument::class, 'ExportKA']);
//Route::get('form-ka/{id}/pdf', [ExportDocument::class, 'ExportKA']);
Route::post('upload-map', [WebgisController::class, 'store']);

Route::group(['middleware' => 'web'], function () {
    Route::get(env('LARAVUE_PATH'), [LaravueController::class, 'index'])->where('any', '.*')->name('laravue');
});
