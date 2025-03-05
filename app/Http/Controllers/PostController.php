<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
 
class PostController extends Controller
{
    /**
     * Display all posts.
     */
    public function index()
    {
        $posts = Post::with('user','likes', 'comments', 'shares')->latest()->get();
        return response()->json($posts);
    }

    /**
     * Store a new post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi,mkv|max:20480',
        ]);

        $filePath = $request->file('file') ? $request->file('file')->store('uploads/posts',"public") : null;

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'file' => $filePath,
        ]);

        return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);
    }

    /**
     * Show a specific post.
     */
    public function show($id)
    {
        $post = Post::with('user', 'likes', 'comments', 'shares')->findOrFail($id);
        return response()->json($post);
    }

    /** 
     * Show Posts specific User  
     */

    public function postUser($userId){
        $posts = Post::with('user', 'likes', 'comments', 'shares')->where("user_id",$userId)->latest()->get();
        return response()->json($posts);
    }

    /**
     * Update a post.
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'content' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,avi,mkv|max:20480',
        ]);

        if ($request->hasFile('file')) {
            Storage::delete($post->file);
            $filePath = $request->file('file')->store('uploads/posts');
            $post->file = $filePath;
        }

        $post->content = $request->content;
        $post->save();

        return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
    }

    /**
     * Delete a post.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
