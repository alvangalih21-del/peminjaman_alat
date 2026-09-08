<?php

namespace App\Http\Controllers;

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Http\Requests\Pengembalian\UpdatePengembalianRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    /**
     * Menampilkan daftar pengembalian
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $tab = $request->input('tab', 'menunggu');

        $menungguPengembalian = Peminjaman::with(['user', 'detailPinjam.alat'])
            ->where('status', 'dipinjam')
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10, ['*'], 'menunggu_page')
            ->withQueryString();

        $riwayatPengembalian = Pengembalian::with([
            'peminjaman.user',
            'petugas',
        ])
        ->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('peminjaman.user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('petugas', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                })
                ->orWhere('kondisi_kembali', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10, ['*'], 'riwayat_page')
        ->withQueryString();

        return view('admin.pengembalian.index', compact(
            'menungguPengembalian',
            'riwayatPengembalian',
            'search',
            'tab'
        ));
    }

    public function menunggu(Request $request)
    {
        $request->merge(['tab' => 'menunggu']);

        return $this->index($request);
    }

    public function riwayat(Request $request)
    {
        $request->merge(['tab' => 'riwayat']);

        return $this->index($request);
    }

    /**
     * Form edit
     */
    public function edit(Pengembalian $pengembalian)
    {
        $pengembalian->load('peminjaman.user');

        return view(
            'admin.pengembalian.edit',
            compact('pengembalian')
        );
    }

    /**
     * Update pengembalian
     */
    public function update(UpdatePengembalianRequest $request, Pengembalian $pengembalian)
    {
        $pengembalian->update([
            'tgl_kembali' => $request->tgl_kembali ?: $pengembalian->tgl_kembali,
            'kondisi_kembali' => $request->kondisi_kembali,
            'denda' => $request->denda ?? 0,
        ]);

        return redirect()
            ->route('admin.pengembalian.riwayat')
            ->with('success', 'Data pengembalian berhasil diperbarui.');
    }

    /**
     * Hapus pengembalian dan kembalikan peminjaman ke status aktif.
     */
    public function destroy(Pengembalian $pengembalian)
    {
        DB::transaction(function () use ($pengembalian) {
            $peminjaman = Peminjaman::with('detailPinjam.alat')
                ->lockForUpdate()
                ->find($pengembalian->peminjaman_id);

            if ($peminjaman) {
                foreach ($peminjaman->detailPinjam as $detail) {
                    $detail->alat?->decrement('stok', $detail->jumlah);
                }

                $peminjaman->update(['status' => 'dipinjam']);
            }

            $pengembalian->delete();
        });

        return redirect()
            ->route('admin.pengembalian.riwayat')
            ->with('success', 'Data pengembalian berhasil dihapus.');
    }

}