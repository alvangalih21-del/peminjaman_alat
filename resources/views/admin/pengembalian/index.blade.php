@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Data Pengembalian</h1>
        <p class="text-gray-500">Kelola data menunggu dan riwayat pengembalian alat</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-5">{{ session('success') }}</div>
    @endif

    <form action="{{ $tab === 'menunggu' ? route('admin.pengembalian.menunggu') : route('admin.pengembalian.riwayat') }}" method="GET" class="mb-5 flex gap-2">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama peminjam, petugas, atau kondisi..." class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-2 rounded-lg">Cari</button>
        @if($search)
            <a href="{{ $tab === 'menunggu' ? route('admin.pengembalian.menunggu') : route('admin.pengembalian.riwayat') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg">Reset</a>
        @endif
    </form>

    @if($tab === 'menunggu')
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100"><tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Peminjam</th>
                        <th class="py-3 px-4 text-left">Rencana Kembali</th>
                        <th class="py-3 px-4 text-left">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr></thead>
                    <tbody>
                        @forelse($menungguPengembalian as $peminjaman)
                            <tr class="border-b">
                                <td class="py-3 px-4">{{ $menungguPengembalian->firstItem() + $loop->index }}</td>
                                <td class="py-3 px-4">{{ $peminjaman->user?->name ?? 'Nama peminjam tidak tersedia' }}</td>
                                <td class="py-3 px-4">{{ $peminjaman->tgl_kembali_plan?->format('d-m-Y') ?? '-' }}</td>
                                <td class="py-3 px-4"><span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">Menunggu</span></td>
                                <td class="py-3 px-4 text-center">
                                    <form action="{{ route('admin.peminjaman.updateStatus', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Tandai peminjaman ini sudah selesai?')">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="dikembalikan">
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">Tandai Selesai</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-8 text-gray-500">Tidak ada peminjaman yang menunggu pengembalian.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5">{{ $menungguPengembalian->links() }}</div>
    @else
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100"><tr>
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Peminjam</th>
                        <th class="py-3 px-4 text-left">Tanggal Kembali</th>
                        <th class="py-3 px-4 text-left">Kondisi</th>
                        <th class="py-3 px-4 text-left">Denda</th>
                        <th class="py-3 px-4 text-left">Petugas</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr></thead>
                    <tbody>
                        @forelse($riwayatPengembalian as $pengembalian)
                            <tr class="border-b">
                                <td class="py-3 px-4">{{ $riwayatPengembalian->firstItem() + $loop->index }}</td>
                                <td class="py-3 px-4">{{ $pengembalian->peminjaman?->user?->name ?? 'Nama peminjam tidak tersedia' }}</td>
                                <td class="py-3 px-4">{{ $pengembalian->tgl_kembali?->format('d-m-Y') ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $pengembalian->kondisi_kembali }}</td>
                                <td class="py-3 px-4">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">{{ $pengembalian->petugas?->name ?? 'Petugas tidak tersedia' }}</td>
                                <td class="py-3 px-4"><div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.pengembalian.edit', $pengembalian->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">Edit</a>
                                    <form action="{{ route('admin.pengembalian.destroy', $pengembalian->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Hapus</button>
                                    </form>
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-8 text-gray-500">Belum ada riwayat pengembalian.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-5">{{ $riwayatPengembalian->links() }}</div>
    @endif
</div>
@endsection
