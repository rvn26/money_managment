<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertemanan extends Model
{
    /** @use HasFactory<\Database\Factories\PertemananFactory> */
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_teman',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function teman()
    {
        return $this->belongsTo(User::class, 'id_teman');
    }
}
