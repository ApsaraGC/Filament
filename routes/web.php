<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\Auth\Register;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/','admin');


// Route::get('/admin/register', Register::class)->name('admin.register');
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profile', function () {
//         // Assuming Filament provides a page for profile, replace this with the correct route
//         return view('profile');
//     })->name('profile.show');
// });

