<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $records = Genre::paginate(config('custom.pagination_size'));

        $paginateData = [
            'total' => $records->total(),
            'per_page' => $records->perPage(),
            'current_page' => $records->currentPage(),
            'last_page' => $records->lastPage(),
            'next_page_url' => $records->nextPageUrl(),
            'prev_page_url' => $records->previousPageUrl(),
            'from' => $records->firstItem(),
            'to' => $records->lastItem(),
        ];

        return response()->json([
            'status' => true,
            'message' => 'List of genres',
            'pagination' => $paginateData,
            'data' => $records->items(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'slug' => 'required|string|max:255|unique:genres',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $genre = Genre::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Genre created',
            'data' => $genre,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Genre $genre): JsonResponse
    {
        $genre->load('books');

        return response()->json([
            'status' => true,
            'message' => 'Genre found',
            'data' => $genre,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genre $genre): JsonResponse
    {
        $validatedData = $request->validate([
            'slug' => 'required|string|max:255|unique:genres,slug,' . $genre->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $genre->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Genre updated',
            'data' => $genre,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genre $genre): JsonResponse
    {
        $genre->delete();

        return response()->json([
            'status' => true,
            'message' => 'Genre deleted',
            'data' => null,
        ], 204);
    }
}
