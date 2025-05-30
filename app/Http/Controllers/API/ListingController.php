<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(
            Listing::with(['metal', 'user'])->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'metal_id' => 'required|exists:metals,id',
            'quality' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $listing = Listing::create([
            ...$validated,
            'user_id' => Auth::id(),
        ]);

        return response()->json($listing->load(['metal', 'user']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $listing = Listing::with(['metal', 'user'])->findOrFail($id);
        return response()->json($listing);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $listing = Listing::findOrFail($id);

        $validated = $request->validate([
            'quality' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:1',
        ]);

        $listing->update($validated);

        return response()->json($listing->load(['metal', 'user']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        Listing::destroy($id);
        return response()->noContent();
    }
}
