<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'status',
        'approved_at',
        'approved_by',
        'rejection_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * Get the user details.
     */
    public function userDetail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }

    /**
     * Get the traveler announcements for the user.
     */
    public function travelerAnnouncements(): HasMany
    {
        return $this->hasMany(TravelerAnnouncement::class);
    }

    /**
     * Get the sender announcements for the user.
     */
    public function senderAnnouncements(): HasMany
    {
        return $this->hasMany(SenderAnnouncement::class);
    }

    /**
     * Get the user requests initiated by the user.
     */
    public function initiatedRequests(): HasMany
    {
        return $this->hasMany(UserRequest::class, 'initiator_user_id');
    }

    /**
     * Get the reviews given by the user.
     */
    public function givenReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    /**
     * Get the reviews received by the user.
     */
    public function receivedReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'reviewed_user_id');
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the messages sent by the user.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    /**
     * Get the blacklist entry for the user.
     */
    public function blacklist(): HasOne
    {
        return $this->hasOne(Blacklist::class);
    }

    /**
     * Get the admin who approved this user.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get users approved by this admin.
     */
    public function approvedUsers(): HasMany
    {
        return $this->hasMany(User::class, 'approved_by');
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if user account is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user account is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if user account is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if user account is suspended.
     */
    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if user can login (approved and not suspended).
     */
    public function canLogin(): bool
    {
        return $this->isApproved() && !$this->isSuspended();
    }

    /**
     * Get unread notifications count
     */
    public function unreadNotificationsCount(): int
    {
        return $this->notifications()->unread()->count();
    }

    /**
     * Get latest notifications
     */
    public function latestNotifications(int $limit = 10)
    {
        return $this->notifications()->latest()->limit($limit)->get();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead(): void
    {
        $this->notifications()->unread()->update(['is_read' => true]);
    }
}
