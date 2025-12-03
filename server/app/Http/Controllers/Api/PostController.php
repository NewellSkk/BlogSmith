<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Post::with('user')->latest()->get();
        $posts = PostResource::collection($query);
        return response()->json([
            'success' => true,
            'message' => 'Posts retrieval successful',
            'posts' => $posts

        ], 200);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $incomingFields = [...$incomingFields, 'user_id' => Auth::user()->id];
        $post = Post::create($incomingFields);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $value = new PostResource($post);
        return response()->json([
            'post' => $value
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // VALIDATION
        try {
            $incomingFields = $request->validate([
                'title' => 'required',
                'body' => 'required'
            ]);
        } catch (\Illuminate\Validation\ValidationException $err) {
            return response()->json([
                'errors' => $err->errors(),
                'message' => 'Validation failed, check your input.'
            ]);
        }
        //SANITIZATION
        $sanitized = [
            'title' => strip_tags($incomingFields['title']),
            'body' => strip_tags($incomingFields['body'])
        ];
        // DATABASE UPDATE
        $post->update($incomingFields);
        return response()->json([
            'message'=>'Post update successful'
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id != Auth::user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 401);
        };

        $post->delete();
        return response(null, 201);
    }
}
