<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    /**
     * Store a new comment on a news article.
     */
    public function store(Request $request, string $slug)
    {
        $news = News::published()->where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'body' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        // Validate parent comment belongs to the same news article
        $parentId = $validated['parent_id'] ?? null;
        if ($parentId) {
            $parentComment = Comment::findOrFail($parentId);
            if ($parentComment->commentable_type !== News::class || $parentComment->commentable_id !== $news->id) {
                return back()->with('error', 'Invalid parent comment.');
            }
        }

        $news->comments()->create([
            'user_id' => Auth::id(),
            'body' => $validated['body'],
            'parent_id' => $parentId,
            'is_approved' => true, // Auto-approve for now
        ]);

        return back()->with('success', 'Comment posted successfully!');
    }

    /**
     * Delete a comment (only the owner can delete).
     */
    public function destroy(Comment $comment)
    {
        // Only allow the comment owner or admin to delete
        if (Auth::id() !== $comment->user_id && ! Auth::user()->hasRole('admin')) {
            return back()->with('error', 'You cannot delete this comment.');
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
