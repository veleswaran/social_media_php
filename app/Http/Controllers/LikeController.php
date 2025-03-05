<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
   /**
     * Share a post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
        ]);
        $check  = Like::where("post_id",$request->post_id)->get();
        if($check[0]->user_id != Auth::id()){
            $share = Like::create([
                'user_id' => Auth::id(),
                'post_id' => $request->post_id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Post shared successfully!',
                'data' => $share
            ], 201);
        }
    }

    /**
     * Get all shares of a post.
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

    /**
     * Delete a share (if the user is the owner).
     */
    public function destroy($id)
    {
        $share = Like::findOrFail($id);

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
