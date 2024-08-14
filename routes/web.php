<?php

use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
//use App\Models\Chirp;  //Activamos el uso del modelo Chirp para Eloquent

Route::get('/', function () {
    // return view('welcome');
    return ' Hey Lou, I wanna fuck you again';
});

Route::get('/chirps', function () {
    return ' Welcome to the Chirps page!';
})->name('chirps.index');

//Route::get('/chirps/{chirp}', function ($chirp) {
    // return view('welcome');
//    return ' Welcome to the Chirps page: ' . $chirp .'!';
//});

//Route::get('/chirps/{chirp?}', function ($chirp = null) {
    // return view('welcome');
//    return ' Welcome to the Chirps page: > ' . $chirp .'!';
//});

// Redirecciones
//Route::get('/chirps/{chirp}', function ($chirp) {
//    if ($chirp == '2') {
        //return redirect()->route('chirps.index');
//        return to_route('chirps.index');
//    }
//
//    return 'Chirps detail: > ' . $chirp .'!';
//});

// Las agregò el comando breeze install para la autenticación de usuarios



//Route::get('/dashboard', function () {
    // haz algo antes
    //return view('dashboard');
    // haz algo despeus de que se genere algo
//})->middleware(['auth', 'verified'])->name('dashboard');  // aqui no usamos el middleware verified

//})->middleware('auth')->name('dashboard');  // lo simplificamos

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chirps', [ChirpController::class, 'index'] )->name('chirps.index');

    Route::post('/chirps', [ChirpController::class, 'store'])->name('chirps.store');;

    Route::get('/chirps/{chirp}/edit', [ChirpController::class, 'edit'])->name('chirps.edit');

    Route::put('/chirps/{chirp}', [ChirpController::class, 'update'])->name('chirps.update');

    Route::delete('/chirps/{chirp}', [ChirpController::class, 'destroy'])->name('chirps.destroy');

});

require __DIR__.'/auth.php';


