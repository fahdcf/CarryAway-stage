<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'initiator_user_id',
        'initiator_role',
        'sender_announcement_id',
        'traveler_announcement_id',
        'announcement_owner_id',
        'proposed_price',
        'status',
        'package_details_completed',
        'form_requested',
        'pickup_location',
        'delivery_location',
        'meeting_code',
        'delivery_code',
        'meeting_verified_at',
        'delivery_verified_at',
    ];

    protected $casts = [
        'proposed_price' => 'decimal:2',
        'package_details_completed' => 'boolean',
        'form_requested' => 'boolean',
        'meeting_verified_at' => 'datetime',
        'delivery_verified_at' => 'datetime',
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
     * Get the single package details attached to this request (if using 1:1 model).
     */
    public function package(): HasOne
    {
        return $this->hasOne(Package::class, 'request_id');
    }

    /**
     * Get the chat for this request.
     */
    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class, 'request_id');
    }

    /**
     * Get the progress updates for this request.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(RequestProgress::class, 'request_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get the latest progress update.
     */
    public function latestProgress(): HasOne
    {
        return $this->hasOne(RequestProgress::class, 'request_id')->latestOfMany();
    }

    /**
     * Check if request has any progress updates.
     */
    public function hasProgress(): bool
    {
        return $this->progress()->exists();
    }

    /**
     * Get the user who owns the announcement.
     */
    public function announcementOwner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'announcement_owner_id');
    }

    /**
     * Generate unique verification codes for this request.
     */
    public function generateVerificationCodes(): void
    {
        // Generate unique 8-digit meeting code (both people get this)
        do {
            $this->meeting_code = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (UserRequest::where('meeting_code', $this->meeting_code)->where('id', '!=', $this->id)->exists());

        // Generate unique 8-digit delivery code ONLY for the announcement owner
        // This is the person who owns the package/service (announcement_owner_id)
        do {
            $this->delivery_code = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (UserRequest::where('delivery_code', $this->delivery_code)->where('id', '!=', $this->id)->exists());

        $this->save();
    }

    /**
     * Verify meeting code
     */
    public function verifyMeetingCode(string $code): bool
    {
        if ($this->meeting_code === strtoupper($code)) {
            $this->meeting_verified_at = now();
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Verify delivery code
     */
    public function verifyDeliveryCode(string $code): bool
    {
        if ($this->delivery_code && $this->delivery_code === strtoupper($code)) {
            $this->delivery_verified_at = now();
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Check if meeting is verified
     */
    public function isMeetingVerified(): bool
    {
        return !is_null($this->meeting_verified_at);
    }

    /**
     * Check if delivery is verified
     */
    public function isDeliveryVerified(): bool
    {
        return !is_null($this->delivery_verified_at);
    }

    /**
     * Check if request can be marked as completed
     */
    public function canBeCompleted(): bool
    {
        // Both meeting code (from both people) and delivery code (from announcement owner) must be verified
        return $this->isMeetingVerified() && $this->isDeliveryVerified();
    }

    /**
     * Get the verification codes for display
     */
    public function getVerificationCodes(): array
    {
        return [
            'meeting_code' => $this->meeting_code,
            'meeting_verified' => $this->isMeetingVerified(),
            'delivery_code' => $this->delivery_code,
            'delivery_verified' => $this->isDeliveryVerified(),
        ];
    }
}
