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
                        <th class="px-5 py-3">Tanggal Pinjam</th>
                        <th class="px-5 py-3">Rencana Kembali</th>
                        <th class="px-5 py-3">Alat</th>
                        <th class="px-5 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $peminjaman)
                        <tr class="border-t border-gray-100">
                            <td class="px-5 py-4">{{ $peminjaman->tgl_pinjam?->format('d-m-Y') }}</td>
                            <td class="px-5 py-4">{{ $peminjaman->tgl_kembali_plan?->format('d-m-Y') }}</td>
                            <td class="px-5 py-4">
                                @foreach($peminjaman->detailPinjam as $detail)
                                    <div>{{ $detail->alat->nama_alat ?? '-' }} ({{ $detail->jumlah }})</div>
                                @endforeach
                            </td>
                            <td class="px-5 py-4 font-semibold">{{ ucfirst($peminjaman->status) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-8 text-center text-gray-500">Belum ada riwayat peminjaman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection