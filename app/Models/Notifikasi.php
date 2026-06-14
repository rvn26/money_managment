<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = [
        'id_user',
        'judul',
        'pesan',
        'tipe',
        'data',
        'dibaca_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
            'dibaca_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Scope untuk notifikasi yang belum dibaca.
     */
    public function scopeBelumDibaca($query)
    {
        return $query->whereNull('dibaca_at');
    }

    /**
     * Scope untuk notifikasi yang sudah dibaca.
     */
    public function scopeSudahDibaca($query)
    {
        return $query->whereNotNull('dibaca_at');
    }
}
