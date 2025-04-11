<?php

namespace App\Http\Controllers;

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
        return Inertia::render('post/Index');

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
            'post' => $post
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        return Inertia::render('post/Edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
