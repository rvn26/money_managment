<?php

use App\Models\FcmToken;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

function authenticatedHeaders(User $user): array
{
    $token = JWTAuth::fromUser($user);

    return [
        'Authorization' => "Bearer {$token}",
        'Accept' => 'application/json',
    ];
}

test('user dapat menyimpan fcm token', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/fcm-token', [
        'token' => 'fcm-device-token-123',
        'device_name' => 'Samsung Galaxy S24',
    ], authenticatedHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('msg', 'FCM token berhasil disimpan.');

    $this->assertDatabaseHas('fcm_tokens', [
        'id_user' => $user->id,
        'token' => 'fcm-device-token-123',
        'device_name' => 'Samsung Galaxy S24',
    ]);
});

test('user tidak bisa menyimpan token duplikat', function () {
    $user = User::factory()->create();
    $headers = authenticatedHeaders($user);

    // Simpan pertama kali
    $this->postJson('/api/fcm-token', [
        'token' => 'fcm-token-same',
        'device_name' => 'Device A',
    ], $headers);

    // Simpan kedua kali dengan token yang sama (upsert)
    $this->postJson('/api/fcm-token', [
        'token' => 'fcm-token-same',
        'device_name' => 'Device A Updated',
    ], $headers);

    // Hanya ada 1 record di database
    $this->assertDatabaseCount('fcm_tokens', 1);
    $this->assertDatabaseHas('fcm_tokens', [
        'id_user' => $user->id,
        'token' => 'fcm-token-same',
        'device_name' => 'Device A Updated',
    ]);
});

test('user dapat menghapus fcm token', function () {
    $user = User::factory()->create();

    FcmToken::create([
        'id_user' => $user->id,
        'token' => 'fcm-token-to-delete',
    ]);

    $response = $this->deleteJson('/api/fcm-token', [
        'token' => 'fcm-token-to-delete',
    ], authenticatedHeaders($user));

    $response->assertStatus(200)
        ->assertJsonPath('msg', 'FCM token berhasil dihapus.');

    $this->assertDatabaseMissing('fcm_tokens', [
        'id_user' => $user->id,
        'token' => 'fcm-token-to-delete',
    ]);
});

test('token wajib diisi saat menyimpan', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/fcm-token', [], authenticatedHeaders($user));

    $response->assertStatus(422);
});
