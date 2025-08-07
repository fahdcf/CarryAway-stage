<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelerAnnouncementDetail extends Model
{
    use HasUuids;

    protected $fillable = [
        'announcement_id',
        'transport_mode',
        'estimated_arrival_date',
        'price_per_kg',
        'extra_notes',
    ];

    protected $casts = [
        'estimated_arrival_date' => 'date',
        'price_per_kg' => 'decimal:2',
    ];

    /**
     * Get the traveler announcement that owns the details.
     */
    public function announcement(): BelongsTo
    {
        return $this->belongsTo(TravelerAnnouncement::class, 'announcement_id');
    }
}
