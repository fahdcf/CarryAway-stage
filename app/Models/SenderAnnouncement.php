<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SenderAnnouncement extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'pickup_city',
        'delivery_city',
        'preferred_date',
        'package_weight',
        'description',
        'status',
        'is_active',
        'pictures',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'package_weight' => 'decimal:2',
        'is_active' => 'boolean',
        'pictures' => 'array',
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
        return $this->hasOne(SenderAnnouncementDetail::class, 'announcement_id');
    }

    /**
     * Get the user requests for this announcement.
     */
    public function userRequests(): HasMany
    {
        return $this->hasMany(UserRequest::class, 'sender_announcement_id');
    }
}
