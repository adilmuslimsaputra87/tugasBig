<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    public function index()
    {
        $artists = Artist::all();
        return response()->json($artists);
    }

    public function create()
    {
        return view('admin.artists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'nullable|string|max:100',
            'country' => 'required|in:indonesia,internasional',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string',
            'instagram' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artists', 'public');
            $validated['image'] = $path;
        }

        $artist = Artist::create($validated);

        return view('admin');
    }

    public function show(Artist $artist)
    {
        return response()->json($artist);
    }

    public function edit(Artist $artist)
    {
        return view('admin.artists.edit', compact('artist'));
    }

    public function update(Request $request, Artist $artist)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'genre' => 'nullable|string|max:100',
            'country' => 'required|in:indonesia,internasional',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string',
            'instagram' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('artists', 'public');
            $validated['image'] = $path;
        }

        $artist->update($validated);

        return view('admin');
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();

        return view('admin');
    }
}
