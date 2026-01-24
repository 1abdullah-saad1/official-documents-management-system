<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('welcome');
});

// Auth routes without registration
Auth::routes(['register' => false]);

// Super Admin routes (Livewire components)
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/institutions', \App\Livewire\Superadmin\InstitutionsManager::class)
        ->name('superadmin.institutions');

    Route::get('/superadmin/users', \App\Livewire\Superadmin\UserManager::class)
        ->name('superadmin.users');
});

// Public Home page (Livewire)
Route::get('/home', \App\Livewire\Home::class)->name('home');

// Parties management per institution (authorization enforced inside component)
Route::middleware(['auth'])
    ->get('/institutions/{institution}/parties', \App\Livewire\PartiesManager::class)
    ->name('institutions.parties');
