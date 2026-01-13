<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class kategori_tagihan extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriTagihanFactory> */
    use HasFactory;
    public function tagihan()
    {
        return $this->hasMany(tagihan::class, 'id_kategori');
    }
}
