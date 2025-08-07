<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blacklist extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'reason',
        'blocked_at',
    ];

    protected $casts = [
        'blocked_at' => 'datetime',
    ];

    /**
     * Get the user that is blacklisted.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
