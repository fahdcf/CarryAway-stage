<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestProgress extends Model
{
    use HasUuids;

    protected $fillable = [
        'request_id',
        'stage',
        'status',
        'notes',
    ];

    protected $casts = [
        // No special casts needed for the simplified fields
    ];

    /**
     * Get the user request that owns the progress.
     */
    public function userRequest(): BelongsTo
    {
        return $this->belongsTo(UserRequest::class, 'request_id');
    }

    /**
     * Get the stage display name.
     */
    public function getStageDisplayNameAttribute(): string
    {
        return match($this->stage) {
            'reception' => 'Réception',
            'en_transit' => 'En transit',
            'arrive_destination' => 'Arrivé à destination',
            'livraison' => 'Livraison',
            default => ucfirst(str_replace('_', ' ', $this->stage))
        };
    }

    /**
     * Get the status display name.
     */
    public function getStatusDisplayNameAttribute(): string
    {
        return match($this->status) {
            'en_cours' => 'En cours',
            'termine' => 'Terminé',
            'retarde' => 'Retardé',
            default => ucfirst($this->status)
        };
    }
}
