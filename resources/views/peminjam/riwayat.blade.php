@extends('layouts.peminjam')

@section('title', 'Riwayat Peminjaman')
@section('header-title', 'Riwayat Peminjaman')

@section('content')
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 p-4 text-green-800">{{ session('success') }}</div>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-700">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-5 py-3">Nama</th>
                        <th class="px-5 py-3">Tanggal Pinjam</th>
                        <th class="px-5 py-3">Rencana Kembali</th>
                        <th class="px-5 py-3">Alat</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $peminjaman)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $peminjaman->user->name ?? auth()->user()->name }}</td>
                            <td class="px-5 py-4">{{ $peminjaman->tgl_pinjam?->format('d-m-Y') }}</td>
                            <td class="px-5 py-4">{{ $peminjaman->tgl_kembali_plan?->format('d-m-Y') }}</td>
                            <td class="px-5 py-4">
                                @foreach($peminjaman->detailPinjam as $detail)
                                    <div>{{ $detail->alat->nama_alat ?? '-' }} ({{ $detail->jumlah }})</div>
                                @endforeach
                            </td>
                            <td class="px-5 py-4 font-semibold">
                                @if($peminjaman->status === 'dikembalikan' || $peminjaman->status === 'selesai')
                                    <span class="rounded bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">Selesai</span>
                                @elseif($peminjaman->status === 'dipinjam')
                                    <span class="rounded bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">Dipinjam</span>
                                @elseif($peminjaman->status === 'diajukan')
                                    <span class="rounded bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">Diajukan</span>
                                @else
                                    <span class="rounded bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">{{ ucfirst($peminjaman->status) }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500">Belum ada riwayat peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection