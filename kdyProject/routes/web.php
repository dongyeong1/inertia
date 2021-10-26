<?php

use App\Http\Controllers\postController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::post('/trance', [postController::class, 'trance']);

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::post('/clickNotLike', [postController::class, 'clickNotLike']);

Route::post('/clickLike', [postController::class, 'clickLike']);

Route::get('/dong', [postController::class, 'index'])->name('dong')->middleware('auth');

Route::get('/createPage', [postController::class, 'createPage'])->name('createPage');

Route::post('/create', [postController::class, 'create']);

Route::post('/update', [postController::class, 'update']);

Route::post('/delete', [postController::class, 'delete']);

Route::post('/imageDelete', [postController::class, 'imageDelete']);

Route::post('/getShowContent', [postController::class, 'showContent']);


Route::get('/get', [postController::class, 'get']);

Route::get('/modal', [postController::class, 'modal']);



Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
