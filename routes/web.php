<?php

use App\Livewire\Tramites\CreateTramite;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', CreateTramite::class)->name('create-tramite');
