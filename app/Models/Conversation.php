<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'course_id',
        'title',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_user')
                    ->withPivot('last_read_at')
                    ->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage(): HasMany
    {
        return $this->hasMany(Message::class)->latest();
    }

    // Scopes
    public function scopeCourseChats($query)
    {
        return $query->where('type', 'course');
    }

    public function scopePrivateChats($query)
    {
        return $query->where('type', 'private');
    }

    public function scopeAdminSupport($query)
    {
        return $query->where('type', 'admin-support');
    }

    // Methods
    public function isCourseChat(): bool
    {
        return $this->type === 'course';
    }

    public function isPrivateChat(): bool
    {
        return $this->type === 'private';
    }

    public function isAdminSupport(): bool
    {
        return $this->type === 'admin-support';
    }

    public function hasParticipant(int $userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    public function getDisplayTitle(): string
    {
        if ($this->isCourseChat() && $this->course) {
            return $this->course->title . ' Chat';
        }
        
        if ($this->isPrivateChat()) {
            return $this->title ?: 'Private Chat';
        }
        
        if ($this->isAdminSupport()) {
            return 'Admin Support';
        }
        
        return 'Chat';
    }
}
