@forelse($announcements as $announcement)
    <tr>
        <td>{{ $announcement->course->title ?? 'N/A' }}</td>
        <td>{{ $announcement->title }}</td>
        <td>
            <span class="badge bg-{{ $announcement->priority === 'high' ? 'danger' : ($announcement->priority === 'medium' ? 'warning' : 'success') }}">
                {{ ucfirst($announcement->priority) }}
            </span>
        </td>
        <td>
            @if($announcement->is_pinned)
                <span class="badge bg-primary">Pinned</span>
            @endif
            @if($announcement->expires_at && $announcement->expires_at->isPast())
                <span class="badge bg-secondary">Expired</span>
            @else
                <span class="badge bg-success">Active</span>
            @endif
        </td>
        <td>{{ $announcement->created_at->format('M d, Y H:i') }}</td>
        <td>
            <button class="btn btn-sm btn-outline-primary" onclick="editAnnouncement({{ $announcement->id }}, '{{ $announcement->course_id }}', '{{ $announcement->title }}', '{{ $announcement->content }}', '{{ $announcement->priority }}', '{{ $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '' }}', {{ $announcement->is_pinned ? 'true' : 'false' }})">
                Edit
            </button>
            <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $announcement->id }})">
                Delete
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center">No announcements found.</td>
    </tr>
@endforelse
