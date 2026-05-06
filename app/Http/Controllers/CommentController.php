<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'text_comment' => 'required|string|max:255',
        ]);
        $validated['product_id'] = $product_id;
        $validated['user_id'] = $request->user()->id;

        $comment = Comment::create($validated);

        return response()->json($comment, 201);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $product_id, string $id)
    {
        // Cari komentar berdasarkan ID dan product_id sekaligus
        $comment = Comment::where('id', $id)
            ->where('product_id', $product_id)
            ->firstOrFail();

        // Cek apakah user yang login adalah pemilik komentar
        if ($request->user()->id !== $comment->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus komentar
        $comment->delete();

        return response()->json([
            'message' => 'Komentar berhasil dihapus.',
            'deleted_comment' => $comment
        ], 200);
    }
}
