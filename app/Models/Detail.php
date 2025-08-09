<?php

namespace App\Models;

use App\Enums\DetailKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Detail extends Model
{
    protected $fillable = [
        'key',
        'value',
        'user_id',
    ];

    protected $casts = [
        'key' => DetailKey::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
