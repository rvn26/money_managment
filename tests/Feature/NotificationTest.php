<?php

use App\Models\Notifikasi;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

function notifHeaders(User $user): array
{
    $token = JWTAuth::fromUser($user);

    return [
        'Authorization' => "Bearer {$token}",
        'Accept' => 'application/json',
    ];
}

test('user dapat melihat daftar notifikasi', function () {
    $user = User::factory()->create();

    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Test Notifikasi',
        'pesan' => 'Ini adalah test notifikasi.',
        'tipe' => 'pertemanan',
    ]);

    $response = $this->getJson('/api/notifikasi', notifHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('msg', 'Daftar notifikasi berhasil diambil.')
        ->assertJsonCount(1, 'data');
});

test('user dapat melihat jumlah notifikasi belum dibaca', function () {
    $user = User::factory()->create();

    // 2 belum dibaca, 1 sudah dibaca
    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Notif 1',
        'pesan' => 'Pesan 1',
        'tipe' => 'pertemanan',
    ]);
    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Notif 2',
        'pesan' => 'Pesan 2',
        'tipe' => 'hutang',
    ]);
    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Notif 3',
        'pesan' => 'Pesan 3',
        'tipe' => 'tagihan',
        'dibaca_at' => now(),
    ]);

    $response = $this->getJson('/api/notifikasi/belum-dibaca', notifHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('data.count', 2);
});

test('user dapat menandai notifikasi sebagai dibaca', function () {
    $user = User::factory()->create();

    $notifikasi = Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Test',
        'pesan' => 'Pesan',
        'tipe' => 'pertemanan',
    ]);

    $response = $this->putJson("/api/notifikasi/{$notifikasi->id}/baca", [], notifHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('msg', 'Notifikasi ditandai sudah dibaca.');

    $this->assertNotNull($notifikasi->fresh()->dibaca_at);
});

test('user dapat menandai semua notifikasi sebagai dibaca', function () {
    $user = User::factory()->create();

    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Notif 1',
        'pesan' => 'Pesan 1',
        'tipe' => 'pertemanan',
    ]);
    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Notif 2',
        'pesan' => 'Pesan 2',
        'tipe' => 'hutang',
    ]);

    $response = $this->putJson('/api/notifikasi/baca-semua', [], notifHeaders($user));

    $response->assertStatus(200);

    $unreadCount = Notifikasi::where('id_user', $user->id)->belumDibaca()->count();
    expect($unreadCount)->toBe(0);
});

test('user tidak bisa menandai notifikasi milik user lain', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $notifikasi = Notifikasi::create([
        'id_user' => $otherUser->id,
        'judul' => 'Test',
        'pesan' => 'Pesan',
        'tipe' => 'pertemanan',
    ]);

    $response = $this->putJson("/api/notifikasi/{$notifikasi->id}/baca", [], notifHeaders($user));

    $response->assertStatus(404);
});

test('filter semua menampilkan semua notifikasi', function () {
    $user = User::factory()->create();

    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Belum dibaca',
        'pesan' => 'Pesan',
        'tipe' => 'pertemanan',
    ]);
    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Sudah dibaca',
        'pesan' => 'Pesan',
        'tipe' => 'hutang',
        'dibaca_at' => now(),
    ]);

    $response = $this->getJson('/api/notifikasi?filter=semua', notifHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('filter', 'semua')
        ->assertJsonCount(2, 'data');
});

test('filter dibaca hanya menampilkan notifikasi yang sudah dibaca', function () {
    $user = User::factory()->create();

    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Belum dibaca',
        'pesan' => 'Pesan',
        'tipe' => 'pertemanan',
    ]);
    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Sudah dibaca',
        'pesan' => 'Pesan',
        'tipe' => 'hutang',
        'dibaca_at' => now(),
    ]);

    $response = $this->getJson('/api/notifikasi?filter=dibaca', notifHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('filter', 'dibaca')
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.judul', 'Sudah dibaca');
});

test('filter belum_dibaca hanya menampilkan notifikasi yang belum dibaca', function () {
    $user = User::factory()->create();

    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Belum dibaca',
        'pesan' => 'Pesan',
        'tipe' => 'pertemanan',
    ]);
    Notifikasi::create([
        'id_user' => $user->id,
        'judul' => 'Sudah dibaca',
        'pesan' => 'Pesan',
        'tipe' => 'hutang',
        'dibaca_at' => now(),
    ]);

    $response = $this->getJson('/api/notifikasi?filter=belum_dibaca', notifHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('filter', 'belum_dibaca')
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.judul', 'Belum dibaca');
});

test('filter tidak valid mengembalikan error 422', function () {
    $user = User::factory()->create();

    $response = $this->getJson('/api/notifikasi?filter=invalid', notifHeaders($user));

    $response->assertStatus(422);
});
