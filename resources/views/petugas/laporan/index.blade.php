@extends('layouts.app')

@section('title', 'Laporan Peminjaman - Dashboard Petugas')
@section('header-title', 'Laporan Peminjaman')

@section('content')
    <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200">
        <div class="p-5 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-gray-800">Laporan Data Peminjaman</h3>
                <p class="text-sm text-gray-500">Menampilkan seluruh riwayat peminjaman untuk keperluan monitoring dan cetak laporan.</p>
            </div>

            <a href="{{ route('petugas.laporan.cetak') }}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                Cetak Laporan
            </a>
        </div>

        <div class="p-5">
            <form action="{{ route('petugas.laporan.index') }}" method="GET" class="flex w-full md:w-80 mb-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama peminjam..." class="w-full px-3 py-2 text-sm border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2 text-sm font-semibold rounded-r-lg transition">Cari</button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-sm uppercase tracking-wider">
                            <th class="py-3 px-4 border-b">Peminjam</th>
                            <th class="py-3 px-4 border-b">Tanggal Pinjam</th>
                            <th class="py-3 px-4 border-b">Jadwal Kembali</th>
                            <th class="py-3 px-4 border-b">Status</th>
                            <th class="py-3 px-4 border-b">Detail Alat</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @forelse($peminjamans as $item)
                            <tr class="hover:bg-gray-50 transition align-top">
                                <td class="py-3 px-4 border-b font-medium text-gray-900">
                                    {{ $item->user->name ?? 'User Dihapus' }}
                                </td>
                                <td class="py-3 px-4 border-b">{{ $item->tgl_pinjam }}</td>
                                <td class="py-3 px-4 border-b">{{ $item->tgl_kembali_plan }}</td>
                                <td class="py-3 px-4 border-b">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($item->status === 'diajukan') bg-yellow-100 text-yellow-800
                                        @elseif($item->status === 'dipinjam') bg-blue-100 text-blue-800
                                        @elseif($item->status === 'selesai') bg-emerald-100 text-emerald-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 border-b">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($item->detailPinjam as $detail)
                                            <li>
                                                <span class="font-semibold">{{ $detail->alat->nama_alat ?? 'Alat Dihapus' }}</span>
                                                <span class="text-xs bg-gray-200 px-1.5 py-0.5 rounded">({{ $detail->jumlah }} pcs)</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-500">
                                    Tidak ada data laporan peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
