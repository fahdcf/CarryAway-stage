<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\UserRequest;

class Notification extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'type',
        'request_id',
        'content',
        'data',
        'action_url',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    /**
     * Get the user that owns the notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the request related to this notification.
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(UserRequest::class);
    }

    /**
     * Create a notification for request acceptance with verification codes
     */
    public static function createRequestAcceptedNotification(UserRequest $request, User $user, array $codes): self
    {
        $content = "Votre demande a été acceptée ! ";
        
        // Check if this user is the announcement owner or requester
        $isAnnouncementOwner = ($user->id === $request->announcement_owner_id);
        
        if ($request->sender_announcement_id) {
            // Sender announcement: Sender (owner) gets 2 codes, requester gets 1 code
            if ($isAnnouncementOwner) {
                // Sender (owner) gets both meeting and delivery codes
                $content .= "Code de rencontre: {$codes['meeting_code']} | Code de livraison: {$codes['delivery_code']}";
                $userCodes = $codes; // All codes
            } else {
                // Requester gets only meeting code
                $content .= "Code de rencontre: {$codes['meeting_code']}";
                $userCodes = [
                    'meeting_code' => $codes['meeting_code'],
                    'meeting_verified' => $codes['meeting_verified'] ?? false,
                ];
            }
        } else {
            // Traveler announcement: Traveler (owner) gets 1 code, requester (sender) gets 2 codes
            if ($isAnnouncementOwner) {
                // Traveler (owner) gets only meeting code
                $content .= "Code de rencontre: {$codes['meeting_code']}";
                $userCodes = [
                    'meeting_code' => $codes['meeting_code'],
                    'meeting_verified' => $codes['meeting_verified'] ?? false,
                ];
            } else {
                // Requester (sender) gets both meeting and delivery codes
                $content .= "Code de rencontre: {$codes['meeting_code']} | Code de livraison: {$codes['delivery_code']}";
                $userCodes = $codes; // All codes
            }
        }

        return self::create([
            'user_id' => $user->id,
            'type' => 'request_accepted',
            'request_id' => $request->id,
            'content' => $content,
            'data' => $userCodes,
            'action_url' => route('requests.index', ['tab' => 'sent']),
        ]);
    }

    /**
     * Create a notification for meeting verification
     */
    public static function createMeetingVerifiedNotification(UserRequest $request, User $user): self
    {
        return self::create([
            'user_id' => $user->id,
            'type' => 'meeting_verified',
            'request_id' => $request->id,
            'content' => "Code de rencontre vérifié avec succès !",
            'action_url' => route('requests.index', ['tab' => 'sent']),
        ]);
    }

    /**
     * Create a notification for delivery verification
     */
    public static function createDeliveryVerifiedNotification(UserRequest $request, User $user): self
    {
        return self::create([
            'user_id' => $user->id,
            'type' => 'delivery_verified',
            'request_id' => $request->id,
            'content' => "Code de livraison vérifié avec succès !",
            'action_url' => route('requests.index', ['tab' => 'sent']),
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    /**
     * Mark notification as unread
     */
    public function markAsUnread(): void
    {
        $this->update(['is_read' => false]);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }
}
