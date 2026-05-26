<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemasukan extends Model
{
    /** @use HasFactory<\Database\Factories\PemasukanFactory> */
    use HasFactory;

    protected $fillable = [
        'id_user', 'tanggal', 'jenis', 'total',
        'metode_pembayaran', 'status', 'deskripsi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
