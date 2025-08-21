<?php

namespace App\Events;

use App\Models\CourseAnnouncement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseAnnouncementCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $announcement;

    /**
     * Create a new event instance.
     */
    public function __construct(CourseAnnouncement $announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('course.' . $this->announcement->course_id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'content' => $this->announcement->content,
            'priority' => $this->announcement->priority,
            'priority_color' => $this->announcement->getPriorityColor(),
            'priority_icon' => $this->announcement->getPriorityIcon(),
            'is_pinned' => $this->announcement->is_pinned,
            'admin' => [
                'id' => $this->announcement->admin->id,
                'name' => $this->announcement->admin->name,
            ],
            'course_id' => $this->announcement->course_id,
            'created_at' => $this->announcement->created_at->toISOString(),
            'formatted_time' => $this->announcement->created_at->diffForHumans(),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'announcement.created';
    }
}
