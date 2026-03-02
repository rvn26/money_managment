<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriFactory> */
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi', 'id_user'];

    public function pengeluaran()
    {
        return $this->hasMany(Pengeluaran::class, 'id_kategori');
    }
}
