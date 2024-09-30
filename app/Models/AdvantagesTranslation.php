<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvantagesTranslation extends Model
{
    use HasFactory;

    protected $table = 'advantages_translations';

    protected $fillable = ['advantages_id', 'locale', 'title', 'icon', 'description', 'created_at', 'updated_at'];

    public function advantages(): BelongsTo
    {
        return $this->belongsTo(Advantages::class);
    }
}
