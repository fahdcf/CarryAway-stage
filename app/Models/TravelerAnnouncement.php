<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TravelerAnnouncement extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'departure_city',
        'arrival_city',
        'departure_date',
        'available_kg',
        'description',
        'status',
        'is_active',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'available_kg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the announcement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the announcement details.
     */
    public function details(): HasOne
    {
        return $this->hasOne(TravelerAnnouncementDetail::class, 'announcement_id');
    }

    /**
     * Get the user requests for this announcement.
     */
    public function userRequests(): HasMany
    {
        return $this->hasMany(UserRequest::class, 'traveler_announcement_id');
    }
}
