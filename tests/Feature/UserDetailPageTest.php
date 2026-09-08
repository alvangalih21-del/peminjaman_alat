<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\DetailPinjam;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserDetailPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_user_detail_and_borrowing_history(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $user = User::factory()->create([
            'role' => 'peminjam',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 10',
            'foto_profile' => 'profiles/test-avatar.jpg',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Elektronik',
        ]);

        $alat = Alat::create([
            'kategori_id' => $kategori->id,
            'nama_alat' => 'Laptop',
            'stok' => 5,
            'status_kondisi' => 'Baik',
            'deskripsi' => 'Laptop untuk kebutuhan kerja',
        ]);

        $peminjaman = Peminjaman::create([
            'user_id' => $user->id,
            'tgl_pinjam' => '2026-08-01',
            'tgl_kembali_plan' => '2026-08-03',
            'status' => 'dipinjam',
        ]);

        DetailPinjam::create([
            'peminjaman_id' => $peminjaman->id,
            'alat_id' => $alat->id,
            'jumlah' => 2,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.user.show', $user->id));

        $response->assertOk();
        $response->assertSee($user->name);
        $response->assertSee($user->email);
        $response->assertSee('Riwayat Peminjaman');
        $response->assertSee($alat->nama_alat);
    }
}
