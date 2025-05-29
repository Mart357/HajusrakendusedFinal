<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('post/Index', [
            'posts' => Post::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    return Inertia::render('post/Create');
    }

    public function store(Request $request)
    {
        // dd($request);
        //validate, et oleks väljadel sisu
        //peale valideerimist salvestab postituse andmebaasi

        Post::create($request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
        ]));

        //peale postitamist viib avalehele
        return redirect()-> to(route('posts.index'));
        
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return Inertia::render('post/Show', [
            'post' => $post->loadMissing([
                'comments' => fn ($query) => $query->with('user')->orderByDesc('created_at'),
            ]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
{
    return Inertia::render('post/Edit', [
        'post' => $post,
    ]);
}

public function update(Request $request, Post $post)
{
    // Validate the request
    $validated = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
    ]);

    // Update the post
    $post->update($validated);

    // Redirect back to the posts index
    return redirect()->route('posts.index')->with('success', 'Post updated successfully!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->back();
    }


}
