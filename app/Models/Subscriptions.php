<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscriptions extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';
    protected $fillable = ['created_at', 'updated_at'];

    public function translations(): HasMany
    {
        return $this->hasMany(SubscriptionsTranslation::class);
    }
}
