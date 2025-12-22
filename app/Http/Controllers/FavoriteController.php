<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite (add/remove)
     */
    public function toggle($eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);
        
        // Check if already favorited
        if ($user->favoriteEvents()->where('event_id', $eventId)->exists()) {
            // Remove from favorites
            $user->favoriteEvents()->detach($eventId);
            return redirect()->back()->with('success', 'Event dihapus dari favorit!');
        } else {
            // Add to favorites
            $user->favoriteEvents()->attach($eventId);
            return redirect()->back()->with('success', 'Event ditambahkan ke favorit!');
        }
    }

    /**
     * Remove from favorites
     */
    public function destroy($eventId)
    {
        $user = Auth::user();
        $user->favoriteEvents()->detach($eventId);
        
        return redirect()->back()->with('success', 'Event dihapus dari favorit!');
    }
}
