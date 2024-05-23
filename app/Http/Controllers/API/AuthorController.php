<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $records = Author::paginate(config('custom.pagination_size'));

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
            'message' => 'List of author',
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
            'name' => 'required|string|max:255',
            'pseudo' => 'nullable|string|max:255',
            'country_code' => 'required|string|max:3',
            'wikipedia_url' => 'nullable|url|max:255',
        ]);

        $author = Author::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Author created',
            'data' => $author,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author): JsonResponse
    {
        $author->load('books');

        return response()->json([
            'status' => true,
            'message' => 'Author found',
            'data' => $author,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'pseudo' => 'nullable|string|max:255',
            'country_code' => 'required|string|max:3',
            'wikipedia_url' => 'nullable|url|max:255',
        ]);

        $author->update($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'Author created',
            'data' => $author,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author): JsonResponse
    {
        $author->delete();

        return response()->json([
            'status' => true,
            'message' => 'Author deleted',
            'data' => null,
        ], 204);
    }
}
