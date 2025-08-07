<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasUuids;

    protected $fillable = [
        'request_id',
        'user1_id',
        'user2_id',
    ];

    /**
     * Get the user request that owns the chat.
     */
    public function userRequest(): BelongsTo
    {
        return $this->belongsTo(UserRequest::class, 'request_id');
    }

    /**
     * Get the first user in the chat.
     */
    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    /**
     * Get the second user in the chat.
     */
    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    /**
     * Get the messages for this chat.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
