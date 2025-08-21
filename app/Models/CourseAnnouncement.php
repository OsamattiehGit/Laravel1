<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'admin_id',
        'title',
        'content',
        'priority',
        'is_pinned',
        'expires_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeByPriority($query, $priority = null)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        return $query->orderByRaw("
            CASE 
                WHEN priority = 'urgent' THEN 1
                WHEN priority = 'high' THEN 2
                WHEN priority = 'normal' THEN 3
                WHEN priority = 'low' THEN 4
            END
        ");
    }

    // Methods
    public function isActive(): bool
    {
        return !$this->expires_at || $this->expires_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isUrgent(): bool
    {
        return $this->priority === 'urgent';
    }

    public function isHigh(): bool
    {
        return $this->priority === 'high';
    }

    public function getPriorityColor(): string
    {
        return match($this->priority) {
            'urgent' => '#dc3545',
            'high' => '#fd7e14',
            'normal' => '#0d6efd',
            'low' => '#6c757d',
            default => '#0d6efd',
        };
    }

    public function getPriorityIcon(): string
    {
        return match($this->priority) {
            'urgent' => '🚨',
            'high' => '⚠️',
            'normal' => '📢',
            'low' => 'ℹ️',
            default => '📢',
        };
    }
}
