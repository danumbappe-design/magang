<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['creator', 'photos', 'comments'])->get();
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::with('creator', 'photos', 'comments')->findOrFail($id);
        return new ProductResource($product);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:3',
        ]);

        $validated['creator_id'] = FacadesAuth::id();
        $product = Product::create($validated);
        return new ProductResource($product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::with('creator')->findOrFail($id);

        // Cek apakah user yang login adalah pemilik data
        if ($request->user()->id !== $product->creator_id) {
            abort(403, 'Unauthorized action.');
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:3',
        ]);

        // Update data
        $product->update($validated);

        // Kembalikan response resource
        return new ProductResource($product);
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::with('creator')->findOrFail($id);

        if ($request->user()->id !== $product->creator_id) {
            abort(403, 'Unauthorized action.');
        }

        $product->delete();
        return ProductResource::collection([$product]);
    }
}
