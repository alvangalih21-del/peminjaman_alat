<?php

namespace Tests\Feature;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\LogAktivitas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogAktivitasTest extends TestCase
{
    use RefreshDatabase;

    public function test_activity_is_logged_when_an_item_is_created(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $kategori = Kategori::create([
            'nama_kategori' => 'Elektronik',
        ]);

        $this->actingAs($admin);

        Alat::create([
            'kategori_id' => $kategori->id,
            'nama_alat' => 'Laptop Baru',
            'stok' => 5,
            'status_kondisi' => 'Baik',
            'deskripsi' => 'Laptop test',
        ]);

        $this->assertDatabaseHas('log_aktivitas', [
            'user_id' => $admin->id,
        ]);

        $this->assertTrue(LogAktivitas::query()->where('aktivitas', 'like', '%Laptop Baru%')->exists());
    }
}
