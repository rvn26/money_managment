<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tagihan extends Model
{
    /** @use HasFactory<\Database\Factories\TagihanFactory> */
    use HasFactory;

    protected $fillable = [
        'id_user', 'kategori', 'nama', 'nominal', 'jatuh_tempo',
        'status', 'metode_pembayaran', 'pengulangan', 'catatan',
    ];

    protected $casts = [
        'jatuh_tempo' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke Kategori
    public function kategori_tagihan()
    {
        return $this->belongsTo(KategoriTagihan::class, 'kategori');
    }
}
