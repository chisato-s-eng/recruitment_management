<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\RecliteController;
use App\Http\Controllers\AnalyticsController;


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
    return redirect(route('login'));
});

Route::get('/top', [RecordController::class, 'get_records'])->middleware(['auth'])->name('top');

require __DIR__.'/auth.php';

Route::prefix('applicant')->name('applicant.')->middleware(['auth'])->group(function() {
    Route::get('', [ApplicantController::class, 'get_applicants'])->name('list');
    Route::get('/new', [ApplicantController::class, 'new_applicant'])->name('new');
    Route::post('/create', [ApplicantController::class, 'insert_applicant'])->name('create');
    Route::get('/{id}', [ApplicantController::class, 'get_applicant_info'])->name('info');
    Route::get('/{id}/edit', [ApplicantController::class, 'edit_applicant'])->name('edit');
    Route::post('/{id}', [ApplicantController::class, 'update_applicant'])->name('update');
    Route::post('/{id}/delete', [ApplicantController::class, 'delete_applicant'])->name('delete');
    Route::get('/{id}/{file_id}/{filename}', [ApplicantController::class, 'download_file'])->name('file.download');
    Route::post('/{id}/{file_id}/delete', [ApplicantController::class, 'delete_file'])->name('filedelete');
});

Route::prefix('reclite')->name('reclite.')->middleware(['auth'])->group(function() {
    Route::get('', [RecliteController::class, 'get_reclites'])->name('list');
    Route::get('/new', [RecliteController::class, 'new_reclite'])->name('new');
    Route::post('/create', [RecliteController::class, 'insert_reclite'])->name('create');
    Route::get('/{id}', [RecliteController::class, 'get_reclite_info'])->name('info');
    Route::get('/{id}/edit', [RecliteController::class, 'edit_reclite'])->name('edit');
    Route::post('/{id}', [RecliteController::class, 'update_reclite'])->name('update');
    Route::post('/{id}/delete', [RecliteController::class, 'delete_reclite'])->name('delete');
});


Route::prefix('analytics')->name('analytics.')->middleware(['auth'])->group(function() {
    Route::get('', [AnalyticsController::class, 'index'])->name('index');
    Route::post('/ajax/department', [AnalyticsController::class, 'show_data_for_department'])->name('ajax.department');
    Route::post('/ajax/status', [AnalyticsController::class, 'show_probability_for_change_status'])->name('ajax.status');
});
