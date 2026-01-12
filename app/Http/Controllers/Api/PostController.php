<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'status' => true,
            'user'   => $request->user(), // authenticated user
            'data'   => Post::latest()->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
        ]);

        $post = Post::create([
            'user_id' => $request->user()->id, // token owner
            'title'   => $request->title,
            'body'    => $request->body,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Post created successfully',
            'data'    => $post
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
