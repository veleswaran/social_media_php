<?php

namespace App\Http\Controllers;

use App\Models\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    /**
     * Share a post with another user.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'shared_with' => 'required|exists:users,id|different:user_id',
        ]);

        $existingShare = Share::where('user_id', Auth::id())
            ->where('post_id', $request->post_id)
            ->where('shared_with', $request->shared_with)
            ->first();

        if ($existingShare) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have already shared this post with this user!'
            ], 409);
        }

        $share = Share::create([
            'user_id' => Auth::id(),
            'post_id' => $request->post_id,
            'shared_with' => $request->shared_with,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Post shared successfully!',
            'data' => $share
        ], 201);
    }

    /**
     * Get all shares of a post.
     */
    public function index($postId)
    {
        $shares = Share::with(['user', 'sharedWithUser'])
            ->where('post_id', $postId)
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Shares retrieved successfully!',
            'data' => $shares
        ]);
    }

    /**
     * Get all posts shared by the authenticated user.
     */
    public function myShares()
    {
        $shares = Share::with('post', 'sharedWithUser')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Your shared posts retrieved successfully!',
            'data' => $shares
        ]);
    }

    /**
     * Get all posts shared with the authenticated user.
     */
    public function receivedShares()
    {
        $shares = Share::with('post', 'user')
            ->where('shared_with', Auth::id())
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Posts shared with you retrieved successfully!',
            'data' => $shares
        ]);
    }

    /**
     * Delete a share (if the user is the owner).
     */
    public function destroy($id)
    {
        $share = Share::findOrFail($id);

        if ($share->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized action'
            ], 403);
        }

        $share->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Share removed successfully!'
        ]);
    }
}
