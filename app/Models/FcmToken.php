<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FcmToken extends Model
{
    protected $fillable = [
        'id_user',
        'token',
        'device_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
