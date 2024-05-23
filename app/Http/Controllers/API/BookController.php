<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $records = Book::with(['authors', 'genres'])->paginate(config('custom.pagination_size'));

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
            'message' => 'List of books',
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
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|max:255',
            'publication_year' => 'required|date_format:Y',
            'genre_ids' => 'array|exists:genres,id',
        ]);

        $book = Book::create($validatedData);

        if ($request->has('genre_ids')) {
            $book->genres()->sync($request->input('genre_ids'));
        }

        $book->load(['authors', 'genres']);

        return response()->json([
            'status' => true,
            'message' => 'Book created',
            'data' => $book,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book): JsonResponse
    {
        $book->load(['authors', 'genres', 'users']);

        return response()->json([
            'status' => true,
            'message' => 'Book found',
            'data' => $book,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book): JsonResponse
    {
        $validatedData = $request->validate([
            'author_id' => 'required|exists:authors,id',
            'title' => 'required|string|max:255',
            'publication_year' => 'required|date_format:Y',
            'genre_ids' => 'array|exists:genres,id',
        ]);

        $book->update($validatedData);

        if ($request->has('genre_ids')) {
            $book->genres()->sync($request->input('genre_ids'));
        }

        $book->load(['authors', 'genres']);

        return response()->json([
            'status' => true,
            'message' => 'Book updated',
            'data' => $book,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return response()->json([
            'status' => true,
            'message' => 'Book deleted',
            'data' => null,
        ], 204);
    }
}
