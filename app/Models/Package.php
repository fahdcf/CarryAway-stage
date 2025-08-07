<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Package extends Model
{
    use HasUuids;

    protected $fillable = [
        'request_id',
        'description',
        'weight',
        'item_type',
        'dimensions',
        'image_url',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
    ];

    /**
     * Get the user request that owns the package.
     */
    public function userRequest(): BelongsTo
    {
        return $this->belongsTo(UserRequest::class, 'request_id');
    }
}
