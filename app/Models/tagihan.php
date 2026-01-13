<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tagihan extends Model
{
    /** @use HasFactory<\Database\Factories\TagihanFactory> */
    use HasFactory;


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
        return $this->belongsTo(kategori_tagihan::class, 'kategori');
    }
}
