<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriTagihan extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriTagihanFactory> */
    use HasFactory;

    protected $fillable = ['nama', 'emoji', 'warna', 'deskripsi', 'id_user'];

    public function tagihan()
    {
        return $this->hasMany(Tagihan::class, 'id_kategori');
    }
}
