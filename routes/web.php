<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('guest.welcome');
})->name('/');

Auth::routes();

 Route::get('admin/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('admin.dashboard');
 Route::get('admin/kategori', function () {
        return view('admin.kategori'); 
    })->name('admin.kategori');


    
 Route::get('user/dashboard', function () {
        return view('user.dashboard'); 
    })->name('user.dashboard');
 Route::get('kategori', function () {
        return view('guest.event'); 
    })->name('kategori');
 Route::get('kota', function () {
        return view('guest.event'); 
    })->name('kota');
 Route::get('search', function () {
        return view('guest.search'); 
    })->name('search');
 Route::get('events/{slug}', function (string $slug) {
        return view('guest.event-detail', ['slug' => $slug]);
    })->name('event.detail');