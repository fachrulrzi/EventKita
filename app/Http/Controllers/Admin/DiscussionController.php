<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    /**
     * Display a listing of all discussions for admin moderation
     */
    public function index(Request $request)
    {
        $query = Discussion::with(['user', 'event', 'replies']);

        // Filter by search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by event
        if ($request->has('event_id') && $request->event_id != '') {
            $query->where('event_id', $request->event_id);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $discussions = $query->paginate(20);

        // Get all events for filter dropdown
        $events = \App\Models\Event::select('id', 'title')->orderBy('title')->get();

        return view('admin.forum', compact('discussions', 'events'));
    }

    /**
     * Display discussion details with all replies
     */
    public function show($id)
    {
        $discussion = Discussion::with(['user', 'event', 'replies.user'])->findOrFail($id);
        
        return view('admin.forum-detail', compact('discussion'));
    }

    /**
     * Delete a discussion (soft moderation)
     */
    public function destroy($id)
    {
        $discussion = Discussion::findOrFail($id);
        
        // Delete all replies first
        $discussion->replies()->delete();
        
        // Delete the discussion
        $discussion->delete();

        return redirect()->route('admin.forum.index')
            ->with('success', 'Diskusi berhasil dihapus.');
    }

    /**
     * Delete a specific reply
     */
    public function destroyReply($id)
    {
        $reply = DiscussionReply::findOrFail($id);
        $discussionId = $reply->discussion_id;
        
        $reply->delete();

        return redirect()->route('admin.forum.show', $discussionId)
            ->with('success', 'Balasan berhasil dihapus.');
    }

    /**
     * Pin/Unpin a discussion
     */
    public function togglePin($id)
    {
        $discussion = Discussion::findOrFail($id);
        $discussion->is_pinned = !$discussion->is_pinned;
        $discussion->save();

        $status = $discussion->is_pinned ? 'dipasang' : 'dilepas';
        
        return redirect()->back()
            ->with('success', "Diskusi berhasil {$status} pin.");
    }

    /**
     * Bulk delete discussions
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'discussion_ids' => 'required|array',
            'discussion_ids.*' => 'exists:discussions,id'
        ]);

        foreach ($request->discussion_ids as $id) {
            $discussion = Discussion::find($id);
            if ($discussion) {
                $discussion->replies()->delete();
                $discussion->delete();
            }
        }

        return redirect()->route('admin.forum.index')
            ->with('success', count($request->discussion_ids) . ' diskusi berhasil dihapus.');
    }
}
