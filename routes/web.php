<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\DiscussionController as AdminDiscussionController;
use App\Models\Category;
use App\Models\City;
use App\Models\Event;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Models\User;
use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/test-s3', function () {
    return Storage::disk('s3')->exists('categories/test.jpg')
        ? 'FILE ADA'
        : 'FILE TIDAK ADA';
});


Route::get('/', function () {
    $categories = Category::orderBy('name')->get();
    $events = Event::with(['category', 'cityRelation', 'ticketCategories'])
        ->where('status', 'published')
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->latest()->get();
    $featuredEvents = Event::with(['category', 'cityRelation', 'ticketCategories'])
        ->where('is_featured', true)
        ->where('status', 'published')
        ->whereNotNull('slug')
        ->where('slug', '!=', '')
        ->get();
    $cities = City::withCount(['events' => function ($query) {
        $query->where('status', 'published');
    }])->having('events_count', '>', 0)->orderBy('name')->get();

    return view('guest.welcome', compact('categories', 'events', 'featuredEvents', 'cities'));
})->name('/');

Auth::routes();

// Group Admin Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('admin/dashboard', function () {
        $categories = Category::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $events = Event::with(['category', 'ticketCategories'])->latest()->get();
        
        // Statistik Dashboard
        $totalEvents = Event::count();
        $publishedEvents = Event::where('status', 'published')->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalCategories = Category::count();
        $totalCities = City::count();
        
        // Statistik tiket dan revenue
        $totalTicketsSold = Ticket::whereHas('order', function($q) {
            $q->where('status', 'paid');
        })->count();
        $totalRevenue = Order::where('status', 'paid')->sum('total_price');
        
        // Event minggu ini
        $eventsThisWeek = Event::whereBetween('created_at', [
            now()->startOfWeek(), 
            now()->endOfWeek()
        ])->count();
        
        // Order pending
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // Diskusi yang perlu moderasi (contoh: diskusi terbaru)
        $pendingDiscussions = Discussion::latest()->take(5)->count();
        
        // Data untuk Aktivitas Terbaru
        $recentOrders = Order::with(['user', 'event'])
            ->where('status', 'paid')
            ->latest()
            ->take(5)
            ->get();
        
        $recentEvents = Event::with(['category'])
            ->latest()
            ->take(3)
            ->get();
        
        // Data untuk Jadwal Minggu Ini
        $upcomingEvents = Event::with(['category', 'cityRelation'])
            ->where('date', '>=', now())
            ->where('date', '<=', now()->addDays(7))
            ->orderBy('date', 'asc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'categories', 'cities', 'events',
            'totalEvents', 'publishedEvents', 'totalUsers', 'totalCategories', 'totalCities',
            'totalTicketsSold', 'totalRevenue', 'eventsThisWeek', 'pendingOrders', 'pendingDiscussions',
            'recentOrders', 'recentEvents', 'upcomingEvents'
        )); 
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

    Route::get('admin/kota', function () {
        $cities = City::withCount('events')->orderBy('name')->get();
        return view('admin.kota', compact('cities'));
    })->name('admin.kota.index');
    Route::post('admin/kota', [CityController::class, 'store'])->name('admin.kota.store');
    Route::put('admin/kota/{city}', [CityController::class, 'update'])->name('admin.kota.update');
    Route::delete('admin/kota/{city}', [CityController::class, 'destroy'])->name('admin.kota.destroy');
    
    Route::post('admin/event', [EventController::class, 'store'])->name('admin.event.store');
    Route::put('admin/event/{event}', [EventController::class, 'update'])->name('admin.event.update');
    Route::delete('admin/event/{event}', [EventController::class, 'destroy'])->name('admin.event.destroy');
    // Halaman Kelola Event (list & manajemen)
    Route::get('admin/events', [EventController::class, 'index'])->name('admin.events');

    // Forum/Discussion Management Routes
    Route::get('admin/forum', [AdminDiscussionController::class, 'index'])->name('admin.forum.index');
    Route::get('admin/forum/{id}', [AdminDiscussionController::class, 'show'])->name('admin.forum.show');
    Route::delete('admin/forum/{id}', [AdminDiscussionController::class, 'destroy'])->name('admin.forum.destroy');
    Route::delete('admin/forum/reply/{id}', [AdminDiscussionController::class, 'destroyReply'])->name('admin.forum.reply.destroy');
    Route::post('admin/forum/{id}/toggle-pin', [AdminDiscussionController::class, 'togglePin'])->name('admin.forum.toggle-pin');
    Route::post('admin/forum/bulk-destroy', [AdminDiscussionController::class, 'bulkDestroy'])->name('admin.forum.bulk-destroy');
});

// Group User Routes
Route::middleware(['auth'])->group(function () {
    Route::get('user/dashboard', function () {
        // Jika admin mencoba akses dashboard user, lempar ke dashboard admin
        if (Auth::user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // Load favorite events untuk user
        $favoriteEvents = Auth::user()->favoriteEvents()
            ->where('status', 'published')
            ->with(['category', 'cityRelation'])
            ->latest('favorites.created_at')
            ->get();
        
        return view('user.dashboard', compact('favoriteEvents')); 
    })->name('user.dashboard');
});

Route::get('kategori', function () {
        $categories = Category::orderBy('name')->get();
        $cities = City::withCount(['events' => function ($query) {
            $query->where('status', 'published');
        }])->having('events_count', '>', 0)->orderBy('name')->get();
        
        $query = Event::where('status', 'published')->with(['category', 'cityRelation']);
        $cityName = null;
        
        // Filter by city name (from "Temukan di Kotamu")
        if (request('city')) {
            $city = City::where('name', request('city'))->first();
            if ($city) {
                $query->where('city_id', $city->id);
                $cityName = $city->name;
            }
        }
        // Filter by city_id (from dropdown)
        elseif (request('city_id')) {
            $query->where('city_id', request('city_id'));
        }
        
        $events = $query->latest()->get();
        return view('guest.event', compact('categories', 'events', 'cities', 'cityName')); 
    })->name('kategori');
Route::get('kategori/{slug}', function (string $slug) {
        $category = Category::where('slug', $slug)->firstOrFail();
        $categories = Category::orderBy('name')->get();
        $cities = City::withCount(['events' => function ($query) use ($category) {
            $query->where('status', 'published')->where('category_id', $category->id);
        }])->having('events_count', '>', 0)->orderBy('name')->get();
        
        $query = Event::where('category_id', $category->id)->where('status', 'published')->with(['category', 'cityRelation']);
        $cityName = null;
        
        // Filter by city name (from "Temukan di Kotamu")
        if (request('city')) {
            $city = City::where('name', request('city'))->first();
            if ($city) {
                $query->where('city_id', $city->id);
                $cityName = $city->name;
            }
        }
        // Filter by city_id (from dropdown)
        elseif (request('city_id')) {
            $query->where('city_id', request('city_id'));
        }
        
        $events = $query->latest()->get();
        return view('guest.event', compact('categories', 'events', 'category', 'cities', 'cityName')); 
    })->name('kategori.filter');
Route::get('kota', function () {
        $categories = Category::orderBy('name')->get();
        $cityName = request('city');
        $cityId = request('city_id');
        $categorySlug = request('category');
        $category = null;
        
        // Build query
        $query = Event::where('status', 'published')->with(['category', 'cityRelation']);
        
        // Filter by city name (dari "Temukan di Kotamu")
        if ($cityName) {
            $city = City::where('name', $cityName)->first();
            if ($city) {
                $query->where('city_id', $city->id);
                $cityId = $city->id;
            }
        }
        // Filter by city_id (dari dropdown filter)
        elseif ($cityId) {
            $query->where('city_id', $cityId);
        }
        
        // Filter by category
        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }
        
        $events = $query->latest()->get();
        
        // Get cities for dropdown
        $cities = City::withCount(['events' => function ($q) {
            $q->where('status', 'published');
        }])->having('events_count', '>', 0)->orderBy('name')->get();

        return view('guest.event', compact('categories', 'events', 'cityName', 'cities', 'category')); 
    })->name('kota');
Route::get('search', function () {
        $query = request('q');
        $events = collect();
        
        if ($query) {
            $events = Event::where('status', 'published')
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', '%' . $query . '%')
                      ->orWhere('description', 'like', '%' . $query . '%')
                      ->orWhere('location', 'like', '%' . $query . '%')
                      ->orWhere('tags', 'like', '%' . $query . '%');
                })
                ->with(['category', 'cityRelation'])
                ->latest()
                ->get();
        }
        
        return view('guest.search', compact('events', 'query')); 
    })->name('search');
Route::get('events/{slug}', function (string $slug) {
        // Load event with necessary relations
        $event = Event::with(['category', 'cityRelation', 'ticketCategories'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        return view('guest.event-detail', ['event' => $event]);
    })->name('event.detail');

// Forum Routes (Wajib Login)
use App\Http\Controllers\DiscussionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;

Route::middleware(['auth'])->group(function () {
    // Order & Payment Routes
    Route::get('checkout/{slug}', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('checkout/{slug}', [OrderController::class, 'createOrder'])->name('order.create');
    Route::post('order/finish/{orderId}', [OrderController::class, 'finishPayment'])->name('order.finish');
    Route::get('order/mock-success/{orderId}', [OrderController::class, 'mockPaymentSuccess'])->name('order.mock.success');
    Route::get('order/success/{orderId}', [OrderController::class, 'success'])->name('order.success');
    Route::get('order/print/{orderId}', [OrderController::class, 'printTicket'])->name('order.print');
    Route::get('my-orders', [OrderController::class, 'myOrders'])->name('my.orders');
    

    // Forum Umum
    Route::get('forum', [DiscussionController::class, 'index'])->name('forum.index');
    Route::post('forum', [DiscussionController::class, 'store'])->name('forum.store');
    Route::get('forum/{id}', [DiscussionController::class, 'show'])->name('forum.show');
    Route::delete('forum/{id}', [DiscussionController::class, 'destroy'])->name('forum.destroy');
    
    // Reply
    Route::post('forum/{id}/reply', [DiscussionController::class, 'storeReply'])->name('forum.reply.store');
    Route::delete('reply/{id}', [DiscussionController::class, 'destroyReply'])->name('forum.reply.destroy');
    
    // Forum per Event
    Route::get('events/{slug}/forum', [DiscussionController::class, 'eventForum'])->name('forum.event');
    
    // Favorites
    Route::post('favorites/{event}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('favorites/{event}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

// Midtrans Callback (tanpa auth middleware)
Route::post('midtrans/callback', [OrderController::class, 'callback'])->name('midtrans.callback');