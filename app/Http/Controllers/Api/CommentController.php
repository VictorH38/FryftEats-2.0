<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Display a listing of comments for a specific restaurant.
     */
    public function index($restaurantId)
    {
        $comments = Comment::where('restaurant_id', $restaurantId)->with('user')->latest()->get();

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created comment in storage for a specific restaurant.
     */
    public function store(Request $request, $restaurantId)
    {
        $user = $request->user();

        $request->validate([
            'body' => 'required|string',
        ]);
    
        if (!Restaurant::find($restaurantId)) {
            throw ValidationException::withMessages([
                'restaurant_id' => ['No restaurant found with the provided ID.']
            ]);
        }
    
        $comment = Comment::create([
            'user_id' => $user->id,
            'restaurant_id' => $restaurantId,
            'body' => $request->body
        ]);
    
        return response()->json([
            'message' => 'Comment created successfully.',
            'comment' => $comment
        ], 201);
    }

    /**
     * Update the specified comment in storage.
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found.'], 404);
        }

        $request->validate([
            'body' => 'required|string'
        ]);

        $comment->update([
            'body' => $request->body
        ]);

        return response()->json([
            'message' => 'Comment updated successfully.',
            'comment' => $comment
        ]);
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return response()->json(['message' => 'Comment not found.'], 404);
        }

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully.'], 204);
    }
}
