<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, $restaurantId)
    {
        $request->validate([
            'body' => 'required',
        ]);

        Comment::create([
            'body' => $request->body,
            'user_id' => Auth::id(),
            'restaurant_id' => $restaurantId
        ]);

        return back()->with('success', 'Comment posted successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $comment = Comment::where('id', $id)->where('user_id', Auth::id())->first();

        if ($comment) {
            $comment->update([
                'body' => $request->body
            ]);

            return back()->with('success', 'Comment updated successfully.');
        }

        return back()->with('error', 'Comment not found or you do not have permission to edit it.');
    }

    public function destroy($id)
    {
        $comment = Comment::where('id', $id)->where('user_id', Auth::id())->first();
        if ($comment) {
            $comment->delete();
            return back()->with('success', 'Comment deleted successfully.');
        }

        return back()->with('error', 'Your comment could not be deleted');
    }
}
