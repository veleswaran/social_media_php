<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Like or Unlike a Post
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);

        $existingLike = Like::where('post_id', $request->post_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Post unliked successfully!',
            ], 200);
        }

        $like = Like::create([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Post liked successfully!',
            'data' => $like
        ], 201);
    }


    /**
     * Get all Likes of a post.
     */
    public function index($postId)
    {
        $shares = Like::with('user')->where('post_id', $postId)->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Shares retrieved successfully!',
            'data' => $shares
        ]);
    }

   
}
