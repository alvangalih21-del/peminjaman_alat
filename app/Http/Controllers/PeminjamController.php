<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\DetailPinjam;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamController extends Controller
{
    public function dashboard()
    {
        $jumlahPengajuan = Peminjaman::where('user_id', auth()->id())->count();
        $jumlahDiajukan = Peminjaman::where('user_id', auth()->id())
            ->where('status', 'diajukan')
            ->count();
        $jumlahDipinjam = Peminjaman::where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->count();
        $jumlahSelesai = Peminjaman::where('user_id', auth()->id())
            ->where('status', 'selesai')
            ->count();

        return view('peminjam.dashboard', compact(
            'jumlahPengajuan',
            'jumlahDiajukan',
            'jumlahDipinjam',
            'jumlahSelesai'
        ));
    }

    // Melihat daftar/katalog alat yang tersedia
    public function katalogAlat(Request $request)
    {
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $kategoris = \App\Models\Kategori::orderBy('nama_kategori')->get();
        $alats = Alat::with('kategori')
            ->where('stok', '>', 0)
            ->when($search, fn ($query) => $query->where('nama_alat', 'like', "%{$search}%"))
            ->when($kategori, fn ($query) => $query->where('kategori_id', $kategori))
            ->latest()
            ->get();

        return view('peminjam.katalog', compact('alats', 'kategoris', 'search', 'kategori'));
    }

    public function ajukanPeminjaman(Request $request)
    {
        $request->validate([
            'tgl_kembali_plan' => 'required|date|after:today',
            'alat_id' => 'required|array',
            'jumlah' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // Buat header peminjaman
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tgl_pinjam' => now(),
                'tgl_kembali_plan' => $request->tgl_kembali_plan,
                'status' => 'diajukan',
            ]);

            // Masukkan daftar alat yang dipinjam ke detail_pinjam
            foreach ($request->alat_id as $alatId) {
                $jumlah = (int) ($request->input("jumlah.{$alatId}") ?? 0);
                $alat = Alat::findOrFail($alatId);

                if ($jumlah < 1 || $jumlah > $alat->stok) {
                    throw new \RuntimeException("Jumlah {$alat->nama_alat} melebihi stok tersedia.");
                }

                DetailPinjam::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $alatId,
                    'jumlah' => $jumlah,
                ]);
            }

            DB::commit();
            return redirect()->route('peminjam.riwayat')->with('success', 'Pengajuan peminjaman berhasil dikirim.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal mengajukan peminjaman: ' . $e->getMessage());
        }
    }

    public function ajukanPengembalian(int $id)
    {
        $peminjaman = Peminjaman::where('user_id', auth()->id())
            ->whereIn('status', ['dipinjam', 'telat'])
            ->findOrFail($id);

        if ($peminjaman->pengembalian()->exists()) {
            return back()->with('error', 'Pengembalian untuk peminjaman ini sudah diajukan.');
        }

        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'tgl_kembali' => now(),
            'kondisi_kembali' => 'Menunggu pemeriksaan petugas',
            'denda' => 0,
            'petugas_id' => null,
            'status' => 'diajukan',
        ]);

        return back()->with('success', 'Permintaan pengembalian berhasil dikirim ke petugas.');
    }

    // Melihat riwayat peminjaman user yang sedang login
    public function riwayatPeminjaman()
    {
        $peminjamans = Peminjaman::with(['detailPinjam.alat', 'pengembalian'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('peminjam.riwayat', compact('peminjamans'));
    }
}