<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'initiator_user_id',
        'initiator_role',
        'sender_announcement_id',
        'traveler_announcement_id',
        'proposed_price',
        'status',
        'pickup_location',
        'delivery_location',
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
    ];

    /**
     * Get the user who initiated the request.
     */
    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiator_user_id');
    }

    /**
     * Get the sender announcement if applicable.
     */
    public function senderAnnouncement(): BelongsTo
    {
        return $this->belongsTo(SenderAnnouncement::class, 'sender_announcement_id');
    }

    /**
     * Get the traveler announcement if applicable.
     */
    public function travelerAnnouncement(): BelongsTo
    {
        return $this->belongsTo(TravelerAnnouncement::class, 'traveler_announcement_id');
    }

    /**
     * Get the packages for this request.
     */
    public function packages(): HasMany
    {
        return $this->hasMany(Package::class, 'request_id');
    }

    /**
     * Get the chat for this request.
     */
    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class, 'request_id');
    }
}
