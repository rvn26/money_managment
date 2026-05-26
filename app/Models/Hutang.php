<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hutang extends Model
{
    /** @use HasFactory<\Database\Factories\HutangFactory> */
    use HasFactory;

    protected $fillable = [
        'id_user',
        'nama',
        'jumlah',
        'tanggal_pinjaman',
        'status',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
