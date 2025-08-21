<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'content',
        'type',
        'file_path',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Scopes
    public function scopeTextMessages($query)
    {
        return $query->where('type', 'text');
    }

    public function scopeFileMessages($query)
    {
        return $query->whereIn('type', ['image', 'file']);
    }

    public function scopeAnnouncements($query)
    {
        return $query->where('type', 'announcement');
    }

    // Methods
    public function isText(): bool
    {
        return $this->type === 'text';
    }

    public function isImage(): bool
    {
        return $this->type === 'image';
    }

    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    public function isAnnouncement(): bool
    {
        return $this->type === 'announcement';
    }

    public function getFileUrl(): ?string
    {
        if ($this->file_path) {
            return asset('storage/' . $this->file_path);
        }
        return null;
    }

    public function getSenderName(): string
    {
        return $this->sender ? $this->sender->name : 'Unknown User';
    }

    public function getFormattedTime(): string
    {
        return $this->created_at->diffForHumans();
    }
}
