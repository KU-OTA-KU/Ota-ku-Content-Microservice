<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionsTranslation extends Model
{
    use HasFactory;

    protected $table = 'subscriptions_translations';

    protected $fillable = ['subscriptions_id', 'locale', 'title', 'description', 'price', 'benefits', 'created_at', 'updated_at'];

    public function faq(): BelongsTo
    {
        return $this->belongsTo(Subscriptions::class);
    }
}
