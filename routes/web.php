<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $categories = Category::orderBy('name')->get();

    return view('guest.welcome', compact('categories'));
})->name('/');

Auth::routes();

// Group Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', function () {
        return view('admin.dashboard'); 
    })->name('admin.dashboard');

    Route::resource('admin/kategori', CategoryController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->parameters([
            'kategori' => 'kategori',
        ])->names([
            'index' => 'admin.kategori.index',
            'store' => 'admin.kategori.store',
            'update' => 'admin.kategori.update',
            'destroy' => 'admin.kategori.destroy',
        ]);
});

// Group User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('user/dashboard', function () {
        // Jika admin mencoba akses dashboard user, lempar ke dashboard admin
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('user.dashboard'); 
    })->name('user.dashboard');
});

Route::get('kategori', function () {
        $categories = Category::orderBy('name')->get();

        return view('guest.event', compact('categories')); 
    })->name('kategori');
Route::get('kota', function () {
        $categories = Category::orderBy('name')->get();

        return view('guest.event', compact('categories')); 
    })->name('kota');
Route::get('search', function () {
        return view('guest.search'); 
    })->name('search');
 Route::get('events/{slug}', function (string $slug) {
        return view('guest.event-detail', ['slug' => $slug]);
    })->name('event.detail');