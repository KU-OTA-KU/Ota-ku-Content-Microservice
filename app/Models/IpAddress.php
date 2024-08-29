<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IpAddress extends Model
{
    use HasFactory;

    protected $fillable = ['ip_address'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_ip')->withTimestamps();
    }
}
