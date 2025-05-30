<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Listing;
use App\Models\Metal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $listings = Listing::with(['metal', 'user'])
            ->latest()
            ->paginate(10) // 10 elementos por página
            ->withQueryString(); // Mantener los parámetros de consulta

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $listings->items(),
                'meta' => [
                    'current_page' => $listings->currentPage(),
                    'last_page' => $listings->lastPage(),
                    'per_page' => $listings->perPage(),
                    'total' => $listings->total(),
                ],
                'links' => [
                    'first' => $listings->url(1),
                    'last' => $listings->url($listings->lastPage()),
                    'prev' => $listings->previousPageUrl(),
                    'next' => $listings->nextPageUrl(),
                ]
            ]);
        }

        return Inertia::render('Listings/Index', [
            'listings' => $listings->items(),
            'meta' => [
                'current_page' => $listings->currentPage(),
                'last_page' => $listings->lastPage(),
                'per_page' => $listings->perPage(),
                'total' => $listings->total(),
            ],
            'links' => [
                'first' => $listings->url(1),
                'last' => $listings->url($listings->lastPage()),
                'prev' => $listings->previousPageUrl(),
                'next' => $listings->nextPageUrl(),
            ],
            'flash' => session('flash')
        ]);
    }

    public function create()
    {
        return Inertia::render('Listings/Create', [
            'metals' => Metal::all(),
            'flash' => session('flash')
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metal_id' => 'required|exists:metals,id',
            'quality' => 'required|string|max:100',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|numeric|min:0.1',
        ]);

        try {
            $listing = new Listing($validated);
            $listing->user_id = Auth::id();
            $listing->save();

            return redirect()->route('listings.index')
                ->with('flash', [
                    'success' => 'Oferta creada exitosamente.'
                ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('flash', [
                    'error' => 'Error al crear la oferta. Por favor, inténtalo de nuevo.'
                ]);
        }
    }

    public function destroy(Listing $listing)
    {
        // Verificar que el usuario es el propietario del listado
        if ($listing->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'No autorizado.'
            ], 403);
        }

        try {
            $listing->delete();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Oferta eliminada correctamente.'
                ]);
            }

            return redirect()->route('listings.index')
                ->with('flash', [
                    'success' => 'Oferta eliminada correctamente.'
                ]);
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la oferta.'
                ], 500);
            }

            return redirect()->back()
                ->with('flash', [
                    'error' => 'Error al eliminar la oferta. Por favor, inténtalo de nuevo.'
                ]);
        }
    }
}
