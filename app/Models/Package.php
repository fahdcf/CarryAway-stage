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
        'breakable',
        'image_url',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'image_url' => 'array',
    ];

    /**
     * Get the formatted images array.
     */
    public function getImagesAttribute()
    {
        if (is_array($this->image_url)) {
            return $this->image_url;
        }
        
        if (is_string($this->image_url)) {
            $decoded = json_decode($this->image_url, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }

    /**
     * Get the user request that owns the package.
     */
    public function userRequest(): BelongsTo
    {
        return $this->belongsTo(UserRequest::class, 'request_id');
    }
}
