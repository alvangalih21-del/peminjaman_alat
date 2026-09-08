<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold">Laporan Peminjaman Alat</h1>
            <p class="text-sm text-gray-500">Dicetak pada {{ now()->translatedFormat('d F Y') }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 px-3 py-2 text-left">Peminjam</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">Tanggal Pinjam</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">Jadwal Kembali</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">Status</th>
                        <th class="border border-gray-300 px-3 py-2 text-left">Alat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $item)
                        <tr>
                            <td class="border border-gray-300 px-3 py-2">{{ $item->user->name ?? 'User Dihapus' }}</td>
                            <td class="border border-gray-300 px-3 py-2">{{ $item->tgl_pinjam }}</td>
                            <td class="border border-gray-300 px-3 py-2">{{ $item->tgl_kembali_plan }}</td>
                            <td class="border border-gray-300 px-3 py-2">{{ ucfirst($item->status) }}</td>
                            <td class="border border-gray-300 px-3 py-2">
                                @foreach($item->detailPinjam as $detail)
                                    {{ $detail->alat->nama_alat ?? 'Alat Dihapus' }} ({{ $detail->jumlah }} pcs) <br>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="border border-gray-300 px-3 py-4 text-center text-gray-500">Tidak ada data laporan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
