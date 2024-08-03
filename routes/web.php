<?php

use App\Http\Controllers\AdminController;
use App\Models\Livres;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $livres=Livres::paginate(10);
    return view('welcome',compact('livres'));
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard',[AdminController::class,'index'])->name('dashboard');
    Route::get('/addLivres',[AdminController::class,'addLivres'])->name('add.Livres');
    Route::post('/storeLivres',[AdminController::class,'store'])->name('store.livres');
    Route::post('/updateLivres',[AdminController::class,'update'])->name('update.livres');
    Route::get('/detailsLivres/{id}',[AdminController::class,'show'])->name('livres.edit');
    Route::get('/deleteLivres/{id}',[AdminController::class,'delete'])->name('livres.delete');

});
