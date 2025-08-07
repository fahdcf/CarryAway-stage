<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'birth_date',
        'gender',
        'address',
        'city',
        'cin',
        'profile_picture_url',
        'verified',
        'id_document_front',
        'id_document_back',
        'id_verification_status',
        'id_verification_notes',
        'id_verified_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'verified' => 'boolean',
        'id_verified_at' => 'datetime',
    ];

    /**
     * Get the user that owns the details.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if ID verification is pending.
     */
    public function isIdVerificationPending(): bool
    {
        return $this->id_verification_status === 'pending';
    }

    /**
     * Check if ID is verified.
     */
    public function isIdVerified(): bool
    {
        return $this->id_verification_status === 'verified';
    }

    /**
     * Check if ID verification is rejected.
     */
    public function isIdVerificationRejected(): bool
    {
        return $this->id_verification_status === 'rejected';
    }
}
