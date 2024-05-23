<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $records = User::paginate(config('custom.pagination_size'));

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
            'message' => 'List of users',
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
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::create($validatedData);

        return response()->json([
            'status' => true,
            'message' => 'User created',
            'data' => $user->load('books'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResponse
    {
        $user->load('books');

        return response()->json([
            'status' => true,
            'message' => 'User found',
            'data' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
                'data' => null,
            ]);
        }

        $user = auth()->user();

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        if ($request->has('book_ids')) {
            $user->books()->sync($request->input('book_ids'));
        }

        return response()->json([
            'status' => true,
            'message' => 'User updated',
            'data' => $user->load('books'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
                'data' => null,
            ]);
        }

        $user = auth()->user();

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted',
            'data' => null,
        ], 204);
    }
}
