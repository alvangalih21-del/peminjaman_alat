<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class PetugasController extends Controller
{
    /**
     * Relasi standar yang dipakai di hampir semua query Peminjaman.
     */
    private const RELASI_DEFAULT = ['user', 'detailPinjam.alat'];

    public function dashboard(): View
    {
        $jumlahMenunggu = Peminjaman::where('status', 'diajukan')->count();
        $jumlahDipinjam = Peminjaman::whereIn('status', ['dipinjam', 'telat'])->count();
        $jumlahTelat = Peminjaman::where('status', 'telat')->count();
        $jumlahSelesai = Peminjaman::whereIn('status', ['dikembalikan', 'selesai'])->count();
        $pengajuanTerbaru = Peminjaman::with(['user', 'detailPinjam.alat'])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('petugas.dashboard', compact(
            'jumlahMenunggu',
            'jumlahDipinjam',
            'jumlahTelat',
            'jumlahSelesai',
            'pengajuanTerbaru'
        ));
    }

    // ==========================================
    // PERSETUJUAN PEMINJAMAN
    // ==========================================

    /**
     * Menampilkan daftar pengajuan peminjaman (status: diajukan).
     */
    public function indexPeminjaman(Request $request): View
    {
        $search = $request->input('search');

        $peminjamans = $this->queryPeminjaman($search)
            ->where('status', 'diajukan')
            ->get();

        return view('petugas.peminjaman.index', compact('peminjamans', 'search'));
    }

    /**
     * Menyetujui peminjaman: ubah status jadi 'dipinjam' dan kurangi stok alat.
     */
    public function setujuiPeminjaman(int $id): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::with('detailPinjam')->findOrFail($id);

            $peminjaman->update(['status' => 'dipinjam']);

            $this->ubahStokAlat($peminjaman, kurangi: true);

            DB::commit();

            return redirect()->back()->with(
                'success',
                'Peminjaman disetujui dan stok alat dikurangi.'
            );
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Terjadi kesalahan: ' . $e->getMessage()
            );
        }
    }

    /**
     * Menolak peminjaman yang statusnya masih 'diajukan'.
     */
    public function tolakPeminjaman(int $id): RedirectResponse
    {
        try {
            $peminjaman = Peminjaman::findOrFail($id);

            if ($peminjaman->status !== 'diajukan') {
                return redirect()->back()->with(
                    'error',
                    'Status peminjaman sudah berubah.'
                );
            }

            $peminjaman->delete();

            return redirect()->back()->with(
                'success',
                'Pengajuan peminjaman berhasil ditolak.'
            );
        } catch (Throwable $e) {
            return redirect()->back()->with(
                'error',
                'Terjadi kesalahan: ' . $e->getMessage()
            );
        }
    }

    // ==========================================
    // PEMANTAUAN PENGEMBALIAN
    // ==========================================

    /**
     * Menampilkan peminjaman yang sedang dipinjam atau telat.
     */
    public function indexPengembalian(Request $request): View
    {
        $search = $request->input('search');

        $peminjamans = $this->queryPeminjaman($search)
            ->whereIn('status', ['dipinjam', 'telat'])
            ->get();

        return view('petugas.pengembalian.index', compact('peminjamans', 'search'));
    }

    /**
     * Memproses pengembalian: catat data pengembalian, ubah status jadi
     * 'selesai', dan kembalikan stok alat.
     */
    public function prosesPengembalian(Request $request, int $peminjamanId): RedirectResponse
    {
        $validated = $request->validate([
            'kondisi_kembali' => 'required|string',
            'denda' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            $peminjaman = Peminjaman::with(['detailPinjam', 'pengembalian'])->findOrFail($peminjamanId);

            $pengembalian = $peminjaman->pengembalian()->first();

            if ($pengembalian) {
                $pengembalian->update([
                    'tgl_kembali' => now(),
                    'kondisi_kembali' => $validated['kondisi_kembali'],
                    'denda' => (int) ($validated['denda'] ?? 0),
                    'petugas_id' => auth()->id(),
                ]);
            } else {
                $pengembalian = Pengembalian::create([
                    'peminjaman_id' => $peminjaman->id,
                    'tgl_kembali' => now(),
                    'kondisi_kembali' => $validated['kondisi_kembali'],
                    'denda' => (int) ($validated['denda'] ?? 0),
                    'petugas_id' => auth()->id(),
                ]);
            }

            $statusBaru = $peminjaman->tgl_kembali_plan && $peminjaman->tgl_kembali_plan->lt(now())
                ? 'telat'
                : 'dikembalikan';

            $peminjaman->update(['status' => $statusBaru]);

            $this->ubahStokAlat($peminjaman, kurangi: false);

            DB::commit();

            return redirect()->back()->with(
                'success',
                'Pengembalian berhasil dicatat dan data otomatis dibuat untuk peminjam.'
            );
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()->with(
                'error',
                'Terjadi kesalahan: ' . $e->getMessage()
            );
        }
    }

    // ==========================================
    // CETAK LAPORAN
    // ==========================================

    /**
     * Menampilkan halaman laporan dengan filter search, status, dan rentang tanggal.
     */
    public function laporan(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $peminjamans = $this->queryPeminjaman($search)
            ->when($status && $status !== 'semua', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($tanggalMulai, function ($query, $tanggalMulai) {
                $query->whereDate('tgl_pinjam', '>=', $tanggalMulai);
            })
            ->when($tanggalSelesai, function ($query, $tanggalSelesai) {
                $query->whereDate('tgl_pinjam', '<=', $tanggalSelesai);
            })
            ->get();

        return view('petugas.laporan.index', compact(
            'peminjamans',
            'search',
            'status',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }

    /**
     * Menampilkan halaman cetak laporan dengan filter yang aktif.
     */
    public function cetakLaporan(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        $peminjamans = $this->queryPeminjaman($search)
            ->when($status && $status !== 'semua', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($tanggalMulai, function ($query, $tanggalMulai) {
                $query->whereDate('tgl_pinjam', '>=', $tanggalMulai);
            })
            ->when($tanggalSelesai, function ($query, $tanggalSelesai) {
                $query->whereDate('tgl_pinjam', '<=', $tanggalSelesai);
            })
            ->get();

        return view('petugas.laporan.cetak', compact(
            'peminjamans',
            'search',
            'status',
            'tanggalMulai',
            'tanggalSelesai'
        ));
    }

    // ==========================================
    // HELPER PRIVATE
    // ==========================================

    /**
     * Query dasar Peminjaman + relasi default + filter pencarian nama user,
     * diurutkan dari yang terbaru. Dipakai bersama oleh beberapa method di
     * atas supaya tidak duplikasi kode.
     */
    private function queryPeminjaman(?string $search)
    {
        return Peminjaman::with(self::RELASI_DEFAULT)
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest();
    }

    /**
     * Menambah atau mengurangi stok alat berdasarkan detail peminjaman.
     */
    private function ubahStokAlat(Peminjaman $peminjaman, bool $kurangi): void
    {
        foreach ($peminjaman->detailPinjam as $detail) {
            $alat = Alat::findOrFail($detail->alat_id);

            $alat->stok += $kurangi ? -$detail->jumlah : $detail->jumlah;
            $alat->save();
        }
    }
}