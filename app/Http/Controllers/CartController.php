<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $carts = Cart::with(['product.photos'])->where('user_id', request()->user()->id)->get();
        return response()->json($carts, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $product_id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $validated['product_id'] = (int) $product_id;
        $validated['user_id'] = $request->user()->id;

        $cart = Cart::create($validated);
        $cart->load('product.photos');

        return response()->json($cart, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'carts' => 'required|array|min:1',
            'carts.*.id' => 'required|integer|exists:carts,id',
            'carts.*.quantity' => 'required|integer|min:1',
        ]);

        $updated = [];

        foreach ($validated['carts'] as $item) {
            $cart = Cart::where('id', $item['id'])
                ->where('user_id', $request->user()->id) // pastikan cart milik user
                ->firstOrFail();

            $cart->update([
                'quantity' => $item['quantity']
            ]);

            $updated[] = $cart;
        }

        return response()->json($updated, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cart = Cart::findOrFail($id);

        // Optional: Check if the cart belongs to the authenticated user
        if (request()->user()->id !== $cart->user_id) {
            abort(403, 'Unauthorized action.');
        }

        $cart->delete();

        return response()->json(['message' => 'Cart item deleted']);
    }
}
