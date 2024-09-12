<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FaqTranslation extends Model
{
    use HasFactory;

    protected $table = 'faq_translations';

    protected $fillable = ['faq_id', 'locale', 'title', 'description', 'created_at', 'updated_at'];

    public function faq(): BelongsTo
    {
        return $this->belongsTo(Faq::class);
    }
}
