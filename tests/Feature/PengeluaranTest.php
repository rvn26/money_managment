<?php

use App\Models\Kategori;
use App\Models\Pengeluaran;
use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

if (! function_exists('authHeaders')) {
    /**
     * Helper to generate JWT Authorization headers.
     */
    function authHeaders(User $user): array
    {
        $token = JWTAuth::fromUser($user);

        return [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ];
    }
}

test('user dapat melihat daftar pengeluaran milik sendiri beserta kategorinya', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $kategoriUser = Kategori::factory()->create(['id_user' => $user->id]);
    $kategoriOtherUser = Kategori::factory()->create(['id_user' => $otherUser->id]);

    // Pengeluaran milik user
    Pengeluaran::factory()->count(3)->create([
        'id_user' => $user->id,
        'id_kategori' => $kategoriUser->id,
        'tanggal_pengeluaran' => now()->format('Y-m-d'),
    ]);

    // Pengeluaran milik user lain
    Pengeluaran::factory()->count(2)->create([
        'id_user' => $otherUser->id,
        'id_kategori' => $kategoriOtherUser->id,
        'tanggal_pengeluaran' => now()->format('Y-m-d'),
    ]);

    $response = $this->getJson('/api/pengeluaran', authHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('statuscode', 200)
        ->assertJsonPath('msg', 'Data pengeluaran berhasil diambil.')
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'id_user',
                    'id_kategori',
                    'tanggal_pengeluaran',
                    'total',
                    'description',
                    'tujuan',
                    'metode_pembayaran',
                    'status',
                    'kategori' => [
                        'id',
                        'nama',
                        'emoji',
                        'warna',
                        'deskripsi',
                    ],
                ],
            ],
        ]);
});

test('daftar pengeluaran dipaginasi', function () {
    $user = User::factory()->create();
    $kategori = Kategori::factory()->create(['id_user' => $user->id]);

    Pengeluaran::factory()->count(15)->create([
        'id_user' => $user->id,
        'id_kategori' => $kategori->id,
        'tanggal_pengeluaran' => now()->format('Y-m-d'),
    ]);

    $response = $this->getJson('/api/pengeluaran?limit=10', authHeaders($user));

    $response->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('pagination.total', 15)
        ->assertJsonPath('pagination.per_page', 10)
        ->assertJsonPath('pagination.current_page', 1)
        ->assertJsonPath('pagination.last_page', 2);
});

test('user dapat memfilter daftar pengeluaran berdasarkan periode', function () {
    $user = User::factory()->create();
    $kategori = Kategori::factory()->create(['id_user' => $user->id]);

    // Set waktu ke hari Rabu di tengah bulan untuk menghindari edge cases minggu/bulan
    Carbon::setTestNow(Carbon::parse('2026-06-17')); // Hari Rabu

    // 1. Pengeluaran hari ini (minggu ini, bulan ini, semua)
    Pengeluaran::factory()->create([
        'id_user' => $user->id,
        'id_kategori' => $kategori->id,
        'tanggal_pengeluaran' => '2026-06-17',
    ]);

    // 2. Pengeluaran 10 hari yang lalu (bulan ini, semua, bukan minggu ini)
    Pengeluaran::factory()->create([
        'id_user' => $user->id,
        'id_kategori' => $kategori->id,
        'tanggal_pengeluaran' => '2026-06-07',
    ]);

    // 3. Pengeluaran bulan lalu (semua, bukan bulan ini, bukan minggu ini, custom bulan lalu)
    Pengeluaran::factory()->create([
        'id_user' => $user->id,
        'id_kategori' => $kategori->id,
        'tanggal_pengeluaran' => '2026-05-15',
    ]);

    // Test filter 'semua'
    $responseSemua = $this->getJson('/api/pengeluaran?periode=semua', authHeaders($user));
    $responseSemua->assertStatus(200)->assertJsonCount(3, 'data');

    // Test filter 'bulan_ini' (default jika tidak dikirim)
    $responseBulanIni = $this->getJson('/api/pengeluaran', authHeaders($user));
    $responseBulanIni->assertStatus(200)->assertJsonCount(2, 'data');

    // Test filter 'minggu_ini'
    $responseMingguIni = $this->getJson('/api/pengeluaran?periode=minggu_ini', authHeaders($user));
    $responseMingguIni->assertStatus(200)->assertJsonCount(1, 'data');

    // Test filter 'custom' ke bulan lalu (Mei 2026)
    $responseCustom = $this->getJson('/api/pengeluaran?periode=custom&bulan_custom=2026-05', authHeaders($user));
    $responseCustom->assertStatus(200)->assertJsonCount(1, 'data');

    Carbon::setTestNow(); // Reset mock time
});

test('user dapat melihat detail pengeluaran miliknya sendiri beserta kategori', function () {
    $user = User::factory()->create();
    $kategori = Kategori::factory()->create(['id_user' => $user->id]);
    $pengeluaran = Pengeluaran::factory()->create([
        'id_user' => $user->id,
        'id_kategori' => $kategori->id,
    ]);

    $response = $this->getJson("/api/pengeluaran/{$pengeluaran->id}", authHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('statuscode', 200)
        ->assertJsonPath('msg', 'Detail pengeluaran berhasil diambil.')
        ->assertJsonPath('data.id', $pengeluaran->id)
        ->assertJsonStructure([
            'data' => [
                'kategori',
            ],
        ]);
});

test('user tidak dapat melihat detail pengeluaran milik orang lain', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $kategori = Kategori::factory()->create(['id_user' => $otherUser->id]);
    $pengeluaran = Pengeluaran::factory()->create([
        'id_user' => $otherUser->id,
        'id_kategori' => $kategori->id,
    ]);

    $response = $this->getJson("/api/pengeluaran/{$pengeluaran->id}", authHeaders($user));

    $response->assertStatus(404)
        ->assertJsonPath('statuscode', 404)
        ->assertJsonPath('msg', 'Pengeluaran tidak ditemukan.');
});

test('user dapat menambahkan pengeluaran baru dengan kategori miliknya', function () {
    $user = User::factory()->create();
    $kategori = Kategori::factory()->create(['id_user' => $user->id]);

    $payload = [
        'id_kategori' => $kategori->id,
        'tanggal_pengeluaran' => now()->format('Y-m-d'),
        'total' => 150000.00,
        'description' => 'Makan malam bersama teman',
        'tujuan' => 'Restoran Sunda',
        'metode_pembayaran' => 'Qris',
        'status' => 'paid',
    ];

    $response = $this->postJson('/api/pengeluaran', $payload, authHeaders($user));

    $response->assertStatus(201)
        ->assertJsonPath('statuscode', 201)
        ->assertJsonPath('msg', 'Pengeluaran berhasil ditambahkan.')
        ->assertJsonPath('data.total', 150000)
        ->assertJsonPath('data.id_kategori', $kategori->id)
        ->assertJsonStructure([
            'data' => [
                'kategori',
            ],
        ]);

    $this->assertDatabaseHas('pengeluarans', [
        'id_user' => $user->id,
        'id_kategori' => $kategori->id,
        'total' => 150000.00,
        'tujuan' => 'Restoran Sunda',
    ]);
});

test('tambah pengeluaran gagal jika kategori milik user lain', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $kategoriOtherUser = Kategori::factory()->create(['id_user' => $otherUser->id]);

    $payload = [
        'id_kategori' => $kategoriOtherUser->id,
        'tanggal_pengeluaran' => now()->format('Y-m-d'),
        'total' => 50000.00,
        'description' => 'Membeli bensin',
        'tujuan' => 'Pertamina',
        'metode_pembayaran' => 'Cash',
        'status' => 'paid',
    ];

    $response = $this->postJson('/api/pengeluaran', $payload, authHeaders($user));

    $response->assertStatus(422)
        ->assertJsonPath('statuscode', 422)
        ->assertJsonPath('msg', 'Kategori tidak ditemukan.')
        ->assertJsonPath('data.id_kategori.0', 'Kategori pengeluaran tidak ditemukan. Silakan buat kategori pengeluaran terlebih dahulu.');
});

test('tambah pengeluaran gagal jika validasi tidak lolos', function () {
    $user = User::factory()->create();

    $responseEmpty = $this->postJson('/api/pengeluaran', [], authHeaders($user));
    $responseEmpty->assertStatus(422)
        ->assertJsonPath('statuscode', 422)
        ->assertJsonPath('msg', 'Validation Error.');

    $payloadInvalid = [
        'id_kategori' => 9999, // tidak terdaftar
        'tanggal_pengeluaran' => 'bukan-tanggal',
        'total' => -50, // kurang dari 0
        'tujuan' => '',
        'metode_pembayaran' => 'Ovo', // tidak ada di enum
        'status' => 'pending', // tidak ada di enum status pengeluaran (draft, approved, paid)
    ];

    $responseInvalid = $this->postJson('/api/pengeluaran', $payloadInvalid, authHeaders($user));
    $responseInvalid->assertStatus(422)
        ->assertJsonStructure([
            'statuscode',
            'msg',
            'data' => [
                'id_kategori',
                'tanggal_pengeluaran',
                'total',
                'tujuan',
                'metode_pembayaran',
                'status',
            ],
        ]);
});

test('user dapat mengubah pengeluaran miliknya sendiri', function () {
    $user = User::factory()->create();
    $kategori = Kategori::factory()->create(['id_user' => $user->id]);
    $kategoriBaru = Kategori::factory()->create(['id_user' => $user->id]);

    $pengeluaran = Pengeluaran::factory()->create([
        'id_user' => $user->id,
        'id_kategori' => $kategori->id,
        'total' => 20000.00,
    ]);

    $payload = [
        'id_kategori' => $kategoriBaru->id,
        'tanggal_pengeluaran' => now()->format('Y-m-d'),
        'total' => 30000.00,
        'description' => 'Mengedit pengeluaran',
        'tujuan' => 'Warung Kopi Baru',
        'metode_pembayaran' => 'Bank',
        'status' => 'approved',
    ];

    $response = $this->putJson("/api/pengeluaran/{$pengeluaran->id}", $payload, authHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('statuscode', 200)
        ->assertJsonPath('msg', 'Pengeluaran berhasil diupdate.')
        ->assertJsonPath('data.total', 30000)
        ->assertJsonPath('data.id_kategori', $kategoriBaru->id)
        ->assertJsonPath('data.tujuan', 'Warung Kopi Baru');

    $this->assertDatabaseHas('pengeluarans', [
        'id' => $pengeluaran->id,
        'id_kategori' => $kategoriBaru->id,
        'total' => 30000.00,
        'tujuan' => 'Warung Kopi Baru',
    ]);
});

test('ubah pengeluaran gagal jika kategori baru milik user lain', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $kategoriUser = Kategori::factory()->create(['id_user' => $user->id]);
    $kategoriOtherUser = Kategori::factory()->create(['id_user' => $otherUser->id]);

    $pengeluaran = Pengeluaran::factory()->create([
        'id_user' => $user->id,
        'id_kategori' => $kategoriUser->id,
    ]);

    $payload = [
        'id_kategori' => $kategoriOtherUser->id,
        'tanggal_pengeluaran' => now()->format('Y-m-d'),
        'total' => 30000.00,
        'description' => 'Mengubah ke kategori orang lain',
        'tujuan' => 'Toko',
        'metode_pembayaran' => 'Bank',
        'status' => 'approved',
    ];

    $response = $this->putJson("/api/pengeluaran/{$pengeluaran->id}", $payload, authHeaders($user));

    $response->assertStatus(422)
        ->assertJsonPath('statuscode', 422)
        ->assertJsonPath('msg', 'Kategori tidak ditemukan.')
        ->assertJsonPath('data.id_kategori.0', 'Kategori pengeluaran tidak ditemukan. Silakan buat kategori pengeluaran terlebih dahulu.');
});

test('user tidak dapat mengubah pengeluaran milik orang lain', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $kategoriUser = Kategori::factory()->create(['id_user' => $user->id]);
    $kategoriOtherUser = Kategori::factory()->create(['id_user' => $otherUser->id]);

    $pengeluaran = Pengeluaran::factory()->create([
        'id_user' => $otherUser->id,
        'id_kategori' => $kategoriOtherUser->id,
    ]);

    $payload = [
        'id_kategori' => $kategoriUser->id,
        'tanggal_pengeluaran' => now()->format('Y-m-d'),
        'total' => 30000.00,
        'description' => 'Mencoba mengubah pengeluaran orang lain',
        'tujuan' => 'Toko',
        'metode_pembayaran' => 'Bank',
        'status' => 'approved',
    ];

    $response = $this->putJson("/api/pengeluaran/{$pengeluaran->id}", $payload, authHeaders($user));

    $response->assertStatus(404)
        ->assertJsonPath('statuscode', 404)
        ->assertJsonPath('msg', 'Pengeluaran tidak ditemukan.');
});

test('user dapat menghapus pengeluaran miliknya sendiri', function () {
    $user = User::factory()->create();
    $kategori = Kategori::factory()->create(['id_user' => $user->id]);
    $pengeluaran = Pengeluaran::factory()->create([
        'id_user' => $user->id,
        'id_kategori' => $kategori->id,
    ]);

    $response = $this->deleteJson("/api/pengeluaran/{$pengeluaran->id}", [], authHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('statuscode', 200)
        ->assertJsonPath('msg', 'Pengeluaran berhasil dihapus.');

    $this->assertDatabaseMissing('pengeluarans', [
        'id' => $pengeluaran->id,
    ]);
});

test('user tidak dapat menghapus pengeluaran milik orang lain', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $kategoriOtherUser = Kategori::factory()->create(['id_user' => $otherUser->id]);
    $pengeluaran = Pengeluaran::factory()->create([
        'id_user' => $otherUser->id,
        'id_kategori' => $kategoriOtherUser->id,
    ]);

    $response = $this->deleteJson("/api/pengeluaran/{$pengeluaran->id}", [], authHeaders($user));

    $response->assertStatus(404)
        ->assertJsonPath('statuscode', 404)
        ->assertJsonPath('msg', 'Pengeluaran tidak ditemukan.');

    $this->assertDatabaseHas('pengeluarans', [
        'id' => $pengeluaran->id,
    ]);
});
