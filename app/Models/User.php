<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_user');
    }

    public function pemasukan()
    {
        return $this->hasMany(Pemasukan::class, 'id_user');
    }

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_user');
    }

    public function hutang()
    {
        return $this->hasMany(Hutang::class, 'id_user');
    }

    /**
     * FCM device tokens milik user ini.
     */
    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class, 'id_user');
    }

    /**
     * Notifikasi milik user ini.
     */
    public function notifikasis()
    {
        return $this->hasMany(Notifikasi::class, 'id_user');
    }

    /**
     * Hutang yang dicatat oleh teman dengan saya sebagai yang berhutang.
     */
    public function hutangSaya()
    {
        return $this->hasMany(Hutang::class, 'id_teman');
    }

    /**
     * Permintaan pertemanan yang saya kirim.
     */
    public function pertemananDikirim()
    {
        return $this->hasMany(Pertemanan::class, 'id_user');
    }

    /**
     * Permintaan pertemanan yang saya terima.
     */
    public function pertemananDiterima()
    {
        return $this->hasMany(Pertemanan::class, 'id_teman');
    }

    /**
     * Semua user yang sudah berteman dengan saya (timbal balik, status accepted).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function teman()
    {
        $dikirim = self::query()
            ->whereIn('id', Pertemanan::query()
                ->where('id_user', $this->id)
                ->where('status', 'accepted')
                ->select('id_teman'));

        $diterima = self::query()
            ->whereIn('id', Pertemanan::query()
                ->where('id_teman', $this->id)
                ->where('status', 'accepted')
                ->select('id_user'));

        return $dikirim->union($diterima)->get();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
