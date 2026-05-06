<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function index()
    {
        // Get all photos
    }

    public function show($id)
    {
        // Get a single photo by ID
    }

    public function store(Request $request, $id)
    {

        $product = Product::with('creator')->findOrFail($id);

        // Cek apakah user yang login adalah pemilik data
        if ($request->user()->id !== $product->creator_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'url' => 'required|array|min:1',
            'url.*' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $createdPhotos = [];
        foreach ($request->file('url') as $file) {

            // 1. Buat record Photo dengan url sementara
            $photo = Photo::create([
                'url' => '', // sementara kosong
                'product_id' => $id,
            ]);

            // 2. Buat nama file berdasarkan id photo dan ekstensi file
            $extension = $file->getClientOriginalExtension();
            $fileName = 'photo_' . $photo->id . '.' . $extension;

            // 3. Simpan file ke storage (storage/app/public/img)
            $file->storeAs('img', $fileName, 'public');
            // 4. Update record photo dengan nama file yang benar
            $photo->url = 'img/' . $fileName;
            $photo->save();
            $createdPhotos[] = $photo;
        }
        return response()->json($createdPhotos, 201);
    }

    public function update(Request $request, $product_id, $id)
    {
        // $request->validate([
        //     'file' => 'required|image|mimes:jpeg,png,jpg',
        // ]);

        // $photo = Photo::findOrFail($id);

        // // Delete old file if exists
        // if ($photo->url && Storage::disk('public')->exists($photo->url)) {
        //     Storage::disk('public')->delete($photo->url);
        // }

        // // Store new file
        // $file = $request->file('file');
        // $extension = $file->getClientOriginalExtension();
        // $fileName = 'photo_' . $photo->id . '.' . $extension;
        // $file->storeAs('img', $fileName, 'public');

        // // Update photo record
        // $photo->url = 'img/' . $fileName;
        // $photo->save();

        // return response()->json($photo);
    }

    public function destroy(Request $request, $product_id)
    {

        $product = Product::with('creator')->findOrFail($product_id);

        // Cek apakah user yang login adalah pemilik data
        if ($request->user()->id !== $product->creator_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'photo_ids' => 'required|array|min:1',
            'photo_ids.*' => 'required|exists:photos,id',
        ]);

        $deleted = [];

        foreach ($request->photo_ids as $photoId) {
            $photo = Photo::where('product_id', $product_id)
                ->where('id', $photoId)
                ->first();

            if ($photo) {
                // Hapus file fisik dari storage
                if (Storage::disk('public')->exists($photo->url)) {
                    Storage::disk('public')->delete($photo->url);
                }

                // Hapus record dari database
                $photo->delete();

                $deleted[] = $photoId;
            }
        }

        return response()->json([
            'message' => 'Foto berhasil dihapus',
            'deleted_ids' => $deleted,
        ], 200);
    }
}
