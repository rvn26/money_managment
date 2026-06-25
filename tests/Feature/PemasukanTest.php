<?php

use App\Models\Pemasukan;
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

test('user dapat melihat daftar pemasukan milik sendiri', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    // Pemasukan milik user
    Pemasukan::factory()->count(3)->create([
        'id_user' => $user->id,
        'tanggal' => now()->format('Y-m-d'),
    ]);

    // Pemasukan milik user lain
    Pemasukan::factory()->count(2)->create([
        'id_user' => $otherUser->id,
        'tanggal' => now()->format('Y-m-d'),
    ]);

    $response = $this->getJson('/api/pemasukan', authHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('statuscode', 200)
        ->assertJsonPath('msg', 'Data pemasukan berhasil diambil.')
        ->assertJsonCount(3, 'data');
});

test('daftar pemasukan dipaginasi', function () {
    $user = User::factory()->create();

    Pemasukan::factory()->count(15)->create([
        'id_user' => $user->id,
        'tanggal' => now()->format('Y-m-d'),
    ]);

    $response = $this->getJson('/api/pemasukan?limit=10', authHeaders($user));

    $response->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'statuscode',
            'msg',
            'data',
            'pagination' => [
                'current_page',
                'last_page',
                'per_page',
                'total',
            ],
        ])
        ->assertJsonPath('pagination.total', 15)
        ->assertJsonPath('pagination.per_page', 10)
        ->assertJsonPath('pagination.current_page', 1)
        ->assertJsonPath('pagination.last_page', 2);
});

test('user dapat memfilter daftar pemasukan berdasarkan periode', function () {
    $user = User::factory()->create();

    // Set waktu ke hari Rabu di tengah bulan untuk menghindari edge cases minggu/bulan
    Carbon::setTestNow(Carbon::parse('2026-06-17')); // Hari Rabu

    // 1. Pemasukan hari ini (minggu ini, bulan ini, semua)
    Pemasukan::factory()->create([
        'id_user' => $user->id,
        'tanggal' => '2026-06-17',
    ]);

    // 2. Pemasukan 10 hari yang lalu (bulan ini, semua, bukan minggu ini)
    Pemasukan::factory()->create([
        'id_user' => $user->id,
        'tanggal' => '2026-06-07',
    ]);

    // 3. Pemasukan bulan lalu (semua, bukan bulan ini, bukan minggu ini, custom bulan lalu)
    Pemasukan::factory()->create([
        'id_user' => $user->id,
        'tanggal' => '2026-05-15',
    ]);

    // Test filter 'semua'
    $responseSemua = $this->getJson('/api/pemasukan?periode=semua', authHeaders($user));
    $responseSemua->assertStatus(200)->assertJsonCount(3, 'data');

    // Test filter 'bulan_ini' (default jika tidak dikirim)
    $responseBulanIni = $this->getJson('/api/pemasukan', authHeaders($user));
    $responseBulanIni->assertStatus(200)->assertJsonCount(2, 'data');

    // Test filter 'minggu_ini'
    $responseMingguIni = $this->getJson('/api/pemasukan?periode=minggu_ini', authHeaders($user));
    $responseMingguIni->assertStatus(200)->assertJsonCount(1, 'data');

    // Test filter 'custom' ke bulan lalu (Mei 2026)
    $responseCustom = $this->getJson('/api/pemasukan?periode=custom&bulan_custom=2026-05', authHeaders($user));
    $responseCustom->assertStatus(200)->assertJsonCount(1, 'data');

    Carbon::setTestNow(); // Reset mock time
});

test('user dapat melihat detail pemasukan miliknya sendiri', function () {
    $user = User::factory()->create();
    $pemasukan = Pemasukan::factory()->create(['id_user' => $user->id]);

    $response = $this->getJson("/api/pemasukan/{$pemasukan->id}", authHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('statuscode', 200)
        ->assertJsonPath('msg', 'Detail pemasukan berhasil diambil.')
        ->assertJsonPath('data.id', $pemasukan->id);
});

test('user tidak dapat melihat detail pemasukan milik orang lain', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $pemasukan = Pemasukan::factory()->create(['id_user' => $otherUser->id]);

    $response = $this->getJson("/api/pemasukan/{$pemasukan->id}", authHeaders($user));

    $response->assertStatus(404)
        ->assertJsonPath('statuscode', 404)
        ->assertJsonPath('msg', 'Pemasukan tidak ditemukan.');
});

test('user dapat menambahkan pemasukan baru', function () {
    $user = User::factory()->create();

    $payload = [
        'tanggal' => now()->format('Y-m-d'),
        'jenis' => 'gaji',
        'total' => 2500000.50,
        'metode_pembayaran' => 'Bank',
        'status' => 'lunas',
        'deskripsi' => 'Gaji bulanan kantor',
    ];

    $response = $this->postJson('/api/pemasukan', $payload, authHeaders($user));

    $response->assertStatus(201)
        ->assertJsonPath('statuscode', 201)
        ->assertJsonPath('msg', 'Pemasukan berhasil ditambahkan.')
        ->assertJsonPath('data.total', 2500000.50)
        ->assertJsonPath('data.jenis', 'gaji')
        ->assertJsonPath('data.id_user', $user->id);

    $this->assertDatabaseHas('pemasukans', [
        'id_user' => $user->id,
        'jenis' => 'gaji',
        'total' => 2500000.50,
    ]);
});

test('tambah pemasukan gagal jika validasi tidak lolos', function () {
    $user = User::factory()->create();

    // Payload kosong
    $responseEmpty = $this->postJson('/api/pemasukan', [], authHeaders($user));
    $responseEmpty->assertStatus(422)
        ->assertJsonPath('statuscode', 422)
        ->assertJsonPath('msg', 'Validation Error.');

    // Payload tidak valid (tanggal di masa depan, total <= 0, enum tidak sesuai)
    $payloadInvalid = [
        'tanggal' => now()->addDay()->format('Y-m-d'), // masa depan
        'jenis' => 'tidak-valid',
        'total' => -100,
        'metode_pembayaran' => 'Bitcoin',
        'status' => 'done',
        'deskripsi' => '',
    ];

    $responseInvalid = $this->postJson('/api/pemasukan', $payloadInvalid, authHeaders($user));
    $responseInvalid->assertStatus(422)
        ->assertJsonStructure([
            'statuscode',
            'msg',
            'data' => [
                'tanggal',
                'jenis',
                'total',
                'metode_pembayaran',
                'status',
                'deskripsi',
            ],
        ]);
});

test('user dapat mengubah pemasukan miliknya sendiri', function () {
    $user = User::factory()->create();
    $pemasukan = Pemasukan::factory()->create([
        'id_user' => $user->id,
        'jenis' => 'bonus',
        'total' => 500000,
    ]);

    $payload = [
        'tanggal' => now()->format('Y-m-d'),
        'jenis' => 'investasi',
        'total' => 750000,
        'metode_pembayaran' => 'Dana',
        'status' => 'lunas',
        'deskripsi' => 'Dividen saham',
    ];

    $response = $this->putJson("/api/pemasukan/{$pemasukan->id}", $payload, authHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('statuscode', 200)
        ->assertJsonPath('msg', 'Pemasukan berhasil diupdate.')
        ->assertJsonPath('data.total', 750000)
        ->assertJsonPath('data.jenis', 'investasi');

    $this->assertDatabaseHas('pemasukans', [
        'id' => $pemasukan->id,
        'jenis' => 'investasi',
        'total' => 750000,
    ]);
});

test('user tidak dapat mengubah pemasukan milik orang lain', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $pemasukan = Pemasukan::factory()->create(['id_user' => $otherUser->id]);

    $payload = [
        'tanggal' => now()->format('Y-m-d'),
        'jenis' => 'investasi',
        'total' => 750000,
        'metode_pembayaran' => 'Dana',
        'status' => 'lunas',
        'deskripsi' => 'Dividen saham',
    ];

    $response = $this->putJson("/api/pemasukan/{$pemasukan->id}", $payload, authHeaders($user));

    $response->assertStatus(404)
        ->assertJsonPath('statuscode', 404)
        ->assertJsonPath('msg', 'Pemasukan tidak ditemukan.');
});

test('user dapat menghapus pemasukan miliknya sendiri', function () {
    $user = User::factory()->create();
    $pemasukan = Pemasukan::factory()->create(['id_user' => $user->id]);

    $response = $this->deleteJson("/api/pemasukan/{$pemasukan->id}", [], authHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('statuscode', 200)
        ->assertJsonPath('msg', 'Pemasukan berhasil dihapus.');

    $this->assertDatabaseMissing('pemasukans', [
        'id' => $pemasukan->id,
    ]);
});

test('user tidak dapat menghapus pemasukan milik orang lain', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $pemasukan = Pemasukan::factory()->create(['id_user' => $otherUser->id]);

    $response = $this->deleteJson("/api/pemasukan/{$pemasukan->id}", [], authHeaders($user));

    $response->assertStatus(404)
        ->assertJsonPath('statuscode', 404)
        ->assertJsonPath('msg', 'Pemasukan tidak ditemukan.');

    $this->assertDatabaseHas('pemasukans', [
        'id' => $pemasukan->id,
    ]);
});
