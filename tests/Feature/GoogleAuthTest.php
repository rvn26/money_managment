<?php

use App\Models\User;
use Google\Auth\AccessToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->mockPayload = [
        'sub' => 'google-id-12345',
        'email' => 'test@example.com',
        'name' => 'Test User',
        'email_verified' => true,
        'aud' => 'test-client-id',
    ];

    // Set config agar audience validation cocok dengan mock payload
    config(['services.google.client_id' => 'test-client-id']);
});

function mockGoogleVerifier(array $payload): void
{
    $mock = Mockery::mock(AccessToken::class);
    $mock->shouldReceive('verify')
        ->andReturn($payload);

    app()->instance(AccessToken::class, $mock);
}

function mockGoogleVerifierFail(): void
{
    $mock = Mockery::mock(AccessToken::class);
    $mock->shouldReceive('verify')
        ->andThrow(new \Exception('Invalid token'));

    app()->instance(AccessToken::class, $mock);
}

it('registers a new user with a valid google id token', function () {
    mockGoogleVerifier($this->mockPayload);

    $response = $this->postJson('/api/auth/google', [
        'id_token' => 'valid-google-id-token',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'statuscode',
            'msg',
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
                'user',
            ],
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'google_id' => 'google-id-12345',
    ]);
});

it('logs in an existing user with a valid google id token', function () {
    $existingUser = User::factory()->create([
        'email' => 'test@example.com',
        'google_id' => 'google-id-12345',
    ]);

    mockGoogleVerifier($this->mockPayload);

    $response = $this->postJson('/api/auth/google', [
        'id_token' => 'valid-google-id-token',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'statuscode',
            'msg',
            'data' => [
                'access_token',
                'token_type',
                'expires_in',
                'user',
            ],
        ]);

    expect(User::count())->toBe(1);
});

it('updates google_id for existing user without one', function () {
    $existingUser = User::factory()->create([
        'email' => 'test@example.com',
        'google_id' => null,
    ]);

    mockGoogleVerifier($this->mockPayload);

    $response = $this->postJson('/api/auth/google', [
        'id_token' => 'valid-google-id-token',
    ]);

    $response->assertStatus(200);

    $existingUser->refresh();
    expect($existingUser->google_id)->toBe('google-id-12345');
});

it('marks email as verified for unverified existing user', function () {
    $existingUser = User::factory()->create([
        'email' => 'test@example.com',
        'google_id' => 'google-id-12345',
        'email_verified_at' => null,
    ]);

    mockGoogleVerifier($this->mockPayload);

    $response = $this->postJson('/api/auth/google', [
        'id_token' => 'valid-google-id-token',
    ]);

    $response->assertStatus(200);

    $existingUser->refresh();
    expect($existingUser->email_verified_at)->not->toBeNull();
});

it('returns validation error when id_token is missing', function () {
    $response = $this->postJson('/api/auth/google', []);

    $response->assertStatus(422);
});

it('returns error when google token verification fails', function () {
    mockGoogleVerifierFail();

    $response = $this->postJson('/api/auth/google', [
        'id_token' => 'invalid-token',
    ]);

    $response->assertStatus(401);
});

it('returns error when audience does not match', function () {
    $payloadWithWrongAud = [
        'sub' => 'google-id-12345',
        'email' => 'test@example.com',
        'name' => 'Test User',
        'aud' => 'wrong-client-id',
    ];

    mockGoogleVerifier($payloadWithWrongAud);

    $response = $this->postJson('/api/auth/google', [
        'id_token' => 'valid-google-id-token',
    ]);

    $response->assertStatus(401)
        ->assertJson([
            'msg' => 'Token Google tidak valid.',
        ]);
});
