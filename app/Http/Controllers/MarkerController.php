<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    // Markerite nimekirja laadimine ja filtreerimine
    public function index(Request $request)
    {
        $query = Marker::query();

        // Filtrimine otsingu järgi
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return $query->get()->map(function ($marker) {
            return [
                'id' => $marker->id,
                'title' => $marker->name,
                'description' => $marker->description,
                'lat' => $marker->latitude,
                'lng' => $marker->longitude,
            ];
        });
    }

    // Markerite lisamine
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $marker = Marker::create([
            'name' => $validated['title'],
            'description' => $validated['description'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json([
            'id' => $marker->id,
            'title' => $marker->name,
            'description' => $marker->description,
            'lat' => $marker->latitude,
            'lng' => $marker->longitude,
        ]);
    }

    // Markeri uuendamine
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ]);

        $marker = Marker::findOrFail($id);
        $marker->update([
            'name' => $validated['title'],
            'description' => $validated['description'],
        ]);

        return response()->json(['message' => 'Marker updated']);
    }

    // Markeri kustutamine
    public function destroy($id)
    {
        Marker::findOrFail($id)->delete();
        return response()->json(['message' => 'Marker deleted']);
    }
}
