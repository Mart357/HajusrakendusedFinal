<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $myMovies = \App\Models\Subject::all();

    // Võta teise isiku filmid API-st
    $otherMovies = [];
    try {
        $response = \Illuminate\Support\Facades\Http::get('https://hajus.ta23raamat.itmajakas.ee/api/movies');
        if ($response->ok()) {
            $otherMovies = $response->json();
        }
    } catch (\Exception $e) {
        // Võid logida vea või jätta lihtsalt tühjaks
        $otherMovies = [];
    }

    return inertia('Subject', [
        'movies' => $myMovies,
        'otherMovies' => $otherMovies,
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('SubjectCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|string',
        'author' => 'nullable|string|max:255',
        'publication_year' => 'nullable|string|max:10',
    ]);
    \App\Models\Subject::create($validated);
    return redirect()->route('subjects.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Subject $subject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        //
    }
}
