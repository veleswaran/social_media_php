<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    /**
     * Get all comments.
     */
    public function index1(){
        return response()->json(Comment::with('user')->get());
    }
    /**
     * Store a new comment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'comment' => $request->comment,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment created successfully!',
            'data' => $comment
        ], 201);
    }

    /**
     * Get comments for a specific post.
     */
    public function index($postId)
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('post_id', $postId)
            ->whereNull('parent_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Comments retrieved successfully!',
            'data' => $comments
        ]);
    }

    /**
     * Get a specific comment with its replies.
     */
    public function show($id)
    {
        $comment = Comment::with(['user', 'replies.user'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment retrieved successfully!',
            'data' => $comment
        ]);
    }

    /**
     * Reply to a comment.
     */
    public function reply(Request $request, $commentId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $parentComment = Comment::findOrFail($commentId);

        $reply = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $parentComment->post_id,
            'comment' => $request->comment,
            'parent_id' => $commentId,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Reply added successfully!',
            'data' => $reply
        ], 201);
    }

    /**
     * Update a comment (if the user is the owner).
     */
    public function update(Request $request, $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action'
            ], 403);
        }

        $request->validate([
            'comment' => 'required|string',
        ]);

        $comment->update([
            'comment' => $request->comment
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment updated successfully!',
            'data' => $comment
        ]);
    }

    /**
     * Delete a comment (if the user is the owner).
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully!'
        ]);
    }
}
