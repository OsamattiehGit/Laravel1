<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Course;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Get user's conversations (course chats, private chats, admin support)
     */
    public function getConversations(): JsonResponse
    {
        $user = Auth::user();
        $conversations = [];

        // Get course-based conversations where user is enrolled
        $enrolledCourses = $user->enrollments()->with('course')->get();
        
        foreach ($enrolledCourses as $enrollment) {
            $course = $enrollment->course;
            
            // Get or create course chat
            $courseChat = Conversation::firstOrCreate([
                'type' => 'course',
                'course_id' => $course->id,
            ], [
                'title' => $course->title . ' Chat'
            ]);

            // Ensure user is participant
            if (!$courseChat->hasParticipant($user->id)) {
                $courseChat->participants()->attach($user->id);
            }

            // Get latest message for preview
            $latestMessage = $courseChat->latestMessage()->first();
            
            $conversations[] = [
                'id' => $courseChat->id,
                'type' => 'course',
                'title' => $courseChat->getDisplayTitle(),
                'course_id' => $course->id,
                'course_name' => $course->title,
                'course' => [
                    'id' => $course->id,
                    'title' => $course->title,
                    'image' => $course->image,
                ],
                'latest_message' => $latestMessage ? [
                    'content' => $latestMessage->content,
                    'sender' => $latestMessage->getSenderName(),
                    'time' => $latestMessage->getFormattedTime(),
                    'created_at' => $latestMessage->created_at,
                ] : null,
                'unread_count' => $this->getUnreadCount($courseChat->id, $user->id),
            ];
        }

        // Get private chats
        $privateChats = Conversation::where('type', 'private')
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['latestMessage.sender', 'participants'])
            ->get();

        foreach ($privateChats as $chat) {
            $otherParticipant = $chat->participants()
                ->where('user_id', '!=', $user->id)
                ->first();

            $conversations[] = [
                'id' => $chat->id,
                'type' => 'private',
                'title' => $chat->title ?: 'Chat with ' . $otherParticipant->name,
                'other_user' => [
                    'id' => $otherParticipant->id,
                    'name' => $otherParticipant->name,
                ],
                'latest_message' => $chat->latestMessage ? [
                    'content' => $chat->latestMessage->content,
                    'sender' => $chat->latestMessage->getSenderName(),
                    'time' => $chat->latestMessage->getFormattedTime(),
                    'created_at' => $chat->latestMessage->created_at,
                ] : null,
                'unread_count' => $this->getUnreadCount($chat->id, $user->id),
            ];
        }

        // If user is admin, get admin support conversations
        if ($user->isAdmin()) {
            $adminSupportChats = Conversation::where('type', 'admin-support')
                ->with(['latestMessage.sender', 'participants'])
                ->get();

            foreach ($adminSupportChats as $chat) {
                $conversations[] = [
                    'id' => $chat->id,
                    'type' => 'admin-support',
                    'title' => 'Admin Support',
                    'latest_message' => $chat->latestMessage ? [
                        'content' => $chat->latestMessage->content,
                        'sender' => $chat->latestMessage->getSenderName(),
                        'time' => $chat->latestMessage->getFormattedTime(),
                        'created_at' => $chat->latestMessage->created_at,
                    ] : null,
                    'unread_count' => $this->getUnreadCount($chat->id, $user->id),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'conversations' => $conversations
        ]);
    }

    /**
     * Get messages for a specific conversation
     */
    public function getMessages(int $conversationId): JsonResponse
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);

        // Check if user has access to this conversation
        if (!$conversation->hasParticipant($user->id) && !$user->isAdmin()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // Mark messages as read
        $this->markAsRead($conversationId, $user->id);

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'content' => $message->content,
                    'type' => $message->type,
                    'file_path' => $message->file_path,
                    'file_url' => $message->getFileUrl(),
                    'sender' => [
                        'id' => $message->sender->id,
                        'name' => $message->sender->name,
                        'is_admin' => $message->sender->isAdmin(),
                    ],
                    'time' => $message->getFormattedTime(),
                    'created_at' => $message->created_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'conversation' => [
                'id' => $conversation->id,
                'type' => $conversation->type,
                'title' => $conversation->getDisplayTitle(),
                'course' => $conversation->course ? [
                    'id' => $conversation->course->id,
                    'name' => $conversation->course->title,
                ] : null,
            ],
            'messages' => $messages
        ]);
    }

    /**
     * Send a message to a conversation
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string|max:2000',
            'type' => 'in:text,image,file',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $conversation = Conversation::findOrFail($request->conversation_id);

        // Check if user has access to this conversation
        if (!$conversation->hasParticipant($user->id) && !$user->isAdmin()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $messageData = [
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'content' => $request->content,
            'type' => $request->type ?? 'text',
        ];

        // Handle file uploads
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('chat-files', 'public');
            $messageData['file_path'] = $filePath;
            $messageData['type'] = $this->getFileType($file->getClientMimeType());
        }

        $message = Message::create($messageData);

        // Mark as read for sender
        $this->markAsRead($conversation->id, $user->id);

        // Broadcast message to other participants (we'll implement this next)
        event(new MessageSent($message));

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'content' => $message->content,
                'type' => $message->type,
                'file_path' => $message->file_path,
                'file_url' => $message->getFileUrl(),
                'sender' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'is_admin' => $user->isAdmin(),
                ],
                'time' => $message->getFormattedTime(),
                'created_at' => $message->created_at->toISOString(),
            ]
        ]);
    }

    /**
     * Create a private chat with another user
     */
    public function createPrivateChat(Request $request): JsonResponse
    {
        $request->validate([
            'other_user_id' => 'required|exists:users,id',
            'title' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $otherUser = User::findOrFail($request->other_user_id);

        // Check if users are enrolled in the same course
        $commonCourses = $user->enrollments()
            ->whereIn('course_id', $otherUser->enrollments()->pluck('course_id'))
            ->exists();

        if (!$commonCourses && !$user->isAdmin()) {
            return response()->json(['error' => 'You can only chat with users enrolled in the same courses'], 403);
        }

        // Check if private chat already exists
        $existingChat = Conversation::where('type', 'private')
            ->whereHas('participants', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->whereHas('participants', function ($query) use ($otherUser) {
                $query->where('user_id', $otherUser->id);
            })
            ->first();

        if ($existingChat) {
            return response()->json([
                'success' => true,
                'conversation_id' => $existingChat->id,
                'message' => 'Private chat already exists'
            ]);
        }

        // Create new private chat
        $conversation = Conversation::create([
            'type' => 'private',
            'title' => $request->title ?: 'Chat with ' . $otherUser->name,
        ]);

        // Add both users as participants
        $conversation->participants()->attach([$user->id, $otherUser->id]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'message' => 'Private chat created successfully'
        ]);
    }

    /**
     * Get course mates for a specific course
     */
    public function getCourseMates(int $courseId): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user is enrolled in this course
        if (!$user->enrollments()->where('course_id', $courseId)->exists()) {
            return response()->json(['error' => 'Not enrolled in this course'], 403);
        }

        $courseMates = User::whereHas('enrollments', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })
        ->where('id', '!=', $user->id)
        ->select('id', 'name', 'email')
        ->get();

        return response()->json([
            'success' => true,
            'course_mates' => $courseMates
        ]);
    }

    /**
     * Helper methods
     */
    public function getUnreadCount(int $conversationId, int $userId): int
    {
        $lastRead = DB::table('conversation_user')
            ->where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->value('last_read_at');

        if (!$lastRead) {
            return Message::where('conversation_id', $conversationId)->count();
        }

        return Message::where('conversation_id', $conversationId)
            ->where('created_at', '>', $lastRead)
            ->count();
    }

    public function markAsRead(int $conversationId, int $userId): void
    {
        DB::table('conversation_user')
            ->where('conversation_id', $conversationId)
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);
    }

    public function getFileType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        return 'file';
    }
}
