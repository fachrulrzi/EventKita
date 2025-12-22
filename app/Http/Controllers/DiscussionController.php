<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\DiscussionReply;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    // Forum Umum
    public function index()
    {
        $discussions = Discussion::whereNull('event_id')
            ->with(['user', 'replies'])
            ->withCount('replies')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('forum.index', compact('discussions'));
    }

    // Forum per Event
    public function eventForum($eventSlug)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $discussions = Discussion::where('event_id', $event->id)
            ->with(['user', 'replies'])
            ->withCount('replies')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('forum.event', compact('discussions', 'event'));
    }

    // Detail diskusi dengan reply
    public function show($id)
    {
        $discussion = Discussion::with(['user', 'event', 'replies.user'])
            ->withCount('replies')
            ->findOrFail($id);
        
        return view('forum.show', compact('discussion'));
    }

    // Store diskusi baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'event_id' => 'nullable|exists:events,id',
        ]);

        $discussion = Discussion::create([
            'user_id' => Auth::id(),
            'event_id' => $validated['event_id'] ?? null,
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        if ($discussion->event_id) {
            return redirect()->route('forum.event', $discussion->event->slug)
                ->with('success', 'Diskusi berhasil dibuat!');
        }

        return redirect()->route('forum.index')
            ->with('success', 'Diskusi berhasil dibuat!');
    }

    // Store reply
    public function storeReply(Request $request, $discussionId)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $discussion = Discussion::findOrFail($discussionId);

        DiscussionReply::create([
            'discussion_id' => $discussion->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
        ]);

        return redirect()->route('forum.show', $discussion->id)
            ->with('success', 'Balasan berhasil ditambahkan!');
    }

    // Delete diskusi
    public function destroy($id)
    {
        $discussion = Discussion::findOrFail($id);
        
        // Only creator can delete
        if ($discussion->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus diskusi ini!');
        }

        $isEventForum = $discussion->event_id !== null;
        $eventSlug = $discussion->event?->slug;
        
        $discussion->delete();

        if ($isEventForum && $eventSlug) {
            return redirect()->route('forum.event', $eventSlug)
                ->with('success', 'Diskusi berhasil dihapus!');
        }

        return redirect()->route('forum.index')
            ->with('success', 'Diskusi berhasil dihapus!');
    }

    // Delete reply
    public function destroyReply($replyId)
    {
        $reply = DiscussionReply::findOrFail($replyId);
        
        // Only creator can delete
        if ($reply->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus balasan ini!');
        }

        $discussionId = $reply->discussion_id;
        $reply->delete();

        return redirect()->route('forum.show', $discussionId)
            ->with('success', 'Balasan berhasil dihapus!');
    }
}
