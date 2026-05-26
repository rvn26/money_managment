<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    /** @use HasFactory<\Database\Factories\PengeluaranFactory> */
    use HasFactory;

    protected $table = 'pengeluarans';

    protected $fillable = [
        'id_user',
        'id_kategori',
        'tanggal_pengeluaran',
        'total',
        'description',
        'tujuan',
        'metode_pembayaran',
        'status'
    ];

    protected $casts = [
        'tanggal_pengeluaran' => 'date',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
