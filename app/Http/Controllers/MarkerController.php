<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        Marker::create([
           
            'name' => $request->title,
            'description' => $request->description,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,


            // 'name' => 'test',
            // 'description' => 'test',
            // 'latitude' => 0.0,
            // 'longitude' => 0.0,
        ]);
        
        //   Post::create($request->validate([
        //     'title' => 'required|max:255',
        //     'description' => 'required',
        // ]));
            return redirect()-> to(route('markers.index'));
        // //peale postitamist viib avalehele
        // return redirect()-> to(route('posts.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Marker $marker)
    {
         
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marker $marker)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marker $marker)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marker $marker)
    {
        //
    }
}
