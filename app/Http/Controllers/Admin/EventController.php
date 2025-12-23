<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Models\EventTicketCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Tampilkan halaman kelola event.
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $events = Event::with(['category', 'cityRelation', 'ticketCategories'])
            ->latest()
            ->get();

        return view('admin.kelola-event', compact('categories', 'cities', 'events'));
    }

    /**
     * Simpan event baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'time_start' => 'nullable|date_format:H:i',
            'time_end' => 'nullable|date_format:H:i|after:time_start',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'website_url' => 'nullable|url',
            'ticket_categories' => 'required|array|min:1',
            'ticket_categories.*.name' => 'required|string|max:255',
            'ticket_categories.*.price' => 'required|numeric|min:0',
            'ticket_categories.*.stock' => 'nullable|integer|min:1',
            'ticket_categories.*.description' => 'nullable|string|max:500',
        ]);

        /** ✅ UPLOAD KE S3 (MINIO) */
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store(
                'events',
                's3',
                ['visibility' => 'public']
            );
        }

        $event = Event::create([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'city_id' => $validated['city_id'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? null,
            'date' => $validated['date'],
            'time_start' => $validated['time_start'] ?? null,
            'time_end' => $validated['time_end'] ?? null,
            'price_min' => 0,
            'price_max' => 0,
            'image_path' => $imagePath,
            'is_featured' => $request->has('is_featured'),
            'tags' => $validated['tags'] ?? null,
            'website_url' => $validated['website_url'] ?? null,
            'status' => 'published',
        ]);

        foreach ($validated['ticket_categories'] as $category) {
            EventTicketCategory::create([
                'event_id' => $event->id,
                'category_name' => $category['name'],
                'price' => $category['price'],
                'stock' => $category['stock'] ?? null,
                'description' => $category['description'] ?? null,
            ]);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Event berhasil ditambahkan!');
    }

    /**
     * Update event.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'time_start' => 'nullable|date_format:H:i',
            'time_end' => 'nullable|date_format:H:i|after:time_start',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'website_url' => 'nullable|url|max:500',
            'ticket_categories' => 'required|array|min:1',
            'ticket_categories.*.id' => 'nullable|exists:event_ticket_categories,id',
            'ticket_categories.*.name' => 'required|string|max:255',
            'ticket_categories.*.price' => 'required|numeric|min:0',
            'ticket_categories.*.stock' => 'nullable|integer|min:1',
            'ticket_categories.*.description' => 'nullable|string|max:500',
            'delete_categories' => 'nullable|array',
            'delete_categories.*' => 'exists:event_ticket_categories,id',
        ]);

        $data = [
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
            'city_id' => $validated['city_id'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'] ?? null,
            'date' => $validated['date'],
            'time_start' => $validated['time_start'] ?? null,
            'time_end' => $validated['time_end'] ?? null,
            'price_min' => 0,
            'price_max' => 0,
            'is_featured' => $request->has('is_featured'),
            'tags' => $validated['tags'] ?? null,
            'website_url' => $validated['website_url'] ?? null,
        ];

        /** ✅ UPDATE IMAGE KE S3 */
        if ($request->hasFile('image')) {
            if ($event->image_path) {
                Storage::disk('s3')->delete($event->image_path);
            }

            $data['image_path'] = $request->file('image')->store(
                'events',
                's3',
                ['visibility' => 'public']
            );
        }

        $event->update($data);

        if ($request->has('delete_categories')) {
            EventTicketCategory::whereIn('id', $validated['delete_categories'])
                ->where('event_id', $event->id)
                ->delete();
        }

        foreach ($validated['ticket_categories'] as $category) {
            if (!empty($category['id'])) {
                EventTicketCategory::where('id', $category['id'])
                    ->where('event_id', $event->id)
                    ->update([
                        'category_name' => $category['name'],
                        'price' => $category['price'],
                        'stock' => $category['stock'] ?? null,
                        'description' => $category['description'] ?? null,
                    ]);
            } else {
                EventTicketCategory::create([
                    'event_id' => $event->id,
                    'category_name' => $category['name'],
                    'price' => $category['price'],
                    'stock' => $category['stock'] ?? null,
                    'description' => $category['description'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Event berhasil diperbarui!');
    }

    /**
     * Hapus event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        if ($event->image_path) {
            Storage::disk('s3')->delete($event->image_path);
        }

        $event->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Event berhasil dihapus!');
    }
}