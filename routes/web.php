<?php

use App\Http\Controllers\AuthController;
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

Route::get('doc-uklupl/{id}', [ExportDocument::class, 'ExportUklUpl']);
Route::get('berita-acara/{id}/{type}', [ExportDocument::class, 'ExportBA']);
//Route::get('form-ka/{id}/pdf', [ExportDocument::class, 'ExportKA']);
Route::post('upload-map', [WebgisController::class, 'store']);
// Route::get('project-map', [ProjectMapAttachmentController::class, 'index']);

Route::group(['middleware' => 'web'], function () {
    Route::get(env('LARAVUE_PATH'), [LaravueController::class, 'index'])->where('any', '.*')->name('laravue');
});

Route::get('auth/new-password',  [AuthController::class, 'resetPasswordRedirect'])->name('password.reset');
Route::get('#/oss/new-password',  [AuthController::class, 'resetPasswordRedirect'])->name('password.reset.redirect');
