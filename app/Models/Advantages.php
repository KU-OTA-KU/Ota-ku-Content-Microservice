<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\hasMany;

class Advantages extends Model
{
    use HasFactory;

    protected $table = 'advantages';
    protected $fillable = ['created_at', 'updated_at'];

    public function translations(): HasMany
    {
        return $this->hasMany(AdvantagesTranslation::class);
    }
}
