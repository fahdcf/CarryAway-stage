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
        'request_id',
        'departure_city',
        'arrival_city',
        'departure_date',
        'departure_time',
        'transport_mode',
        'estimated_arrival_date',
        'estimated_arrival_time',
        'transport_type',
        'price_per_kg',
        'description',
        'extra_notes',
    ];

    protected $casts = [
        'departure_date' => 'date',
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

    /**
     * Get the user request that owns the details.
     */
    public function userRequest(): BelongsTo
    {
        return $this->belongsTo(UserRequest::class, 'request_id');
    }

    /**
     * Check if this detail is linked to a traveler announcement.
     */
    public function isForTravelerAnnouncement(): bool
    {
        return !is_null($this->announcement_id);
    }

    /**
     * Check if this detail is linked to a sender request.
     */
    public function isForSenderRequest(): bool
    {
        return !is_null($this->request_id);
    }
}
