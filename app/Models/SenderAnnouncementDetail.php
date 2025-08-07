<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SenderAnnouncementDetail extends Model
{
    use HasUuids;

    protected $fillable = [
        'announcement_id',
        'item_type',
        'dimensions',
        'declared_value',
        'additional_info',
    ];

    protected $casts = [
        'declared_value' => 'decimal:2',
    ];

    /**
     * Get the sender announcement that owns the details.
     */
    public function announcement(): BelongsTo
    {
        return $this->belongsTo(SenderAnnouncement::class, 'announcement_id');
    }
}
