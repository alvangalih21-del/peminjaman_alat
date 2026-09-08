<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kategori\StoreKategoriRequest;
use App\Http\Requests\Kategori\UpdateKategoriRequest;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class KategoriController extends Controller
{
    /**
     * Menampilkan semua data kategori
     */
    public function index(): JsonResponse
    {
        $kategori = Kategori::latest()->get();

        return response()->json([
            'message' => 'Data kategori berhasil diambil.',
            'data' => KategoriResource::collection($kategori),
        ]);
    }

    /**
     * Menyimpan data kategori baru
     */
    public function store(StoreKategoriRequest $request): JsonResponse
    {
        $kategori = DB::transaction(function () use ($request) {
            return Kategori::create($request->validated());
        });

        return response()->json([
            'message' => 'Kategori berhasil ditambahkan.',
            'data' => new KategoriResource($kategori),
        ], 201);
    }

    /**
     * Menampilkan detail kategori
     */
    public function show(Kategori $kategori): JsonResponse
    {
        return response()->json([
            'message' => 'Detail kategori berhasil diambil.',
            'data' => new KategoriResource($kategori),
        ]);
    }

    /**
     * Mengubah data kategori
     */
    public function update(UpdateKategoriRequest $request, Kategori $kategori): JsonResponse
    {
        DB::transaction(function () use ($request, $kategori) {
            $kategori->update($request->validated());
        });

        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'data' => new KategoriResource($kategori->fresh()),
        ]);
    }

    /**
     * Menghapus data kategori
     */
    public function destroy(Kategori $kategori): JsonResponse
    {
        DB::transaction(function () use ($kategori) {
            $kategori->delete();
        });

        return response()->json([
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}