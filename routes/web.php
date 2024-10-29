<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\GenerationController;
use App\Http\Controllers\NewReportController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NewGenerationController;
use App\Http\Controllers\FileController;


Route::get('/upload', [FileController::class, 'showUploadForm']);
Route::post('/upload', [FileController::class, 'uploadFile']);
Route::post('/process', [FileController::class, 'processData']);

Route::get('/', [ReportController::class, 'glavniy'])->name('index');

Route::get('/create-report', [ReportController::class, 'index'])->name('report.create');
Route::post('/save-report', [ReportController::class, 'saveReport']);
Route::post('/generate-pdf', [ReportController::class, 'generatePDF']);


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
});

Route::get('/report', [NewReportController::class, 'index'])->name('report.index');
Route::get('/report/{id}', [NewReportController::class, 'show'])->name('report.show');
Route::post('/report', [NewReportController::class, 'store'])->name('report.store')->middleware('auth');




Route::get('/reports', [GenerationController::class, 'index'])->name('reports.index');
Route::post('/reports/upload', [GenerationController::class, 'upload'])->name('reports.upload');
Route::post('/reports/uploadAdditional', [ReportController::class, 'uploadAdditional'])->name('reports.uploadAdditional');
Route::post('/reports/save-chart', [GenerationController::class, 'saveChart'])->name('reports.saveChart');
Route::post('/reports/generate-pdf', [GenerationController::class, 'generatePdf'])->name('reports.generatePdf');



Route::get('/upload-reports', [NewGenerationController::class, 'showUploadForm'])->name('upload.form');
Route::post('/upload-reports', [NewGenerationController::class, 'upload'])->name('report.upload');
Route::get('/compare-reports', [NewGenerationController::class, 'compare'])->name('reports.compare');




Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/gantt', function(){
    return view('gant');
});

require __DIR__.'/auth.php';
