@extends('layouts.app')

@section('title', 'Dashboard Petugas')
@section('header-title', 'Dashboard Petugas')

@section('content')
    <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-5 text-emerald-900">
        <h1 class="text-xl font-bold">Selamat datang, {{ auth()->user()->name }}!</h1>
        <p class="mt-1 text-sm">Pantau pengajuan peminjaman dan proses pengembalian alat.</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-5 shadow-sm">
            <p class="text-sm text-yellow-700">Menunggu Persetujuan</p>
            <p class="mt-2 text-3xl font-bold text-yellow-900">{{ $jumlahMenunggu }}</p>
        </div>
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-5 shadow-sm">
            <p class="text-sm text-blue-700">Sedang Dipinjam</p>
            <p class="mt-2 text-3xl font-bold text-blue-900">{{ $jumlahDipinjam }}</p>
        </div>
        <div class="rounded-lg border border-red-200 bg-red-50 p-5 shadow-sm">
            <p class="text-sm text-red-700">Terlambat</p>
            <p class="mt-2 text-3xl font-bold text-red-900">{{ $jumlahTelat }}</p>
        </div>
        <div class="rounded-lg border border-green-200 bg-green-50 p-5 shadow-sm">
            <p class="text-sm text-green-700">Selesai</p>
            <p class="mt-2 text-3xl font-bold text-green-900">{{ $jumlahSelesai }}</p>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-gray-200 bg-gray-50 px-5 py-4">
            <h2 class="font-bold text-gray-800">5 Pengajuan Terbaru</h2>
            <a href="{{ route('petugas.peminjaman.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-700">
                <thead class="text-xs uppercase text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Peminjam</th>
                        <th class="px-5 py-3">Tanggal Pengajuan</th>
                        <th class="px-5 py-3">Rencana Kembali</th>
                        <th class="px-5 py-3">Alat</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuanTerbaru as $peminjaman)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $peminjaman->user->name ?? '-' }}</td>
                            <td class="px-5 py-4">{{ $peminjaman->created_at?->format('d-m-Y H:i') }}</td>
                            <td class="px-5 py-4">{{ $peminjaman->tgl_kembali_plan?->format('d-m-Y') }}</td>
                            <td class="px-5 py-4">
                                @forelse($peminjaman->detailPinjam as $detail)
                                    <div>{{ $detail->alat->nama_alat ?? 'Alat dihapus' }} ({{ $detail->jumlah }})</div>
                                @empty
                                    <span class="text-gray-500">-</span>
                                @endforelse
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-semibold {{ $peminjaman->status === 'diajukan' ? 'text-yellow-700' : ($peminjaman->status === 'selesai' ? 'text-green-700' : 'text-blue-700') }}">
                                    {{ ucfirst($peminjaman->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('petugas.peminjaman.index') }}" class="font-semibold text-blue-600 hover:text-blue-800">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-5 py-8 text-center text-gray-500">Belum ada pengajuan peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection