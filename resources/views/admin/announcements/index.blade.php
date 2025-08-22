@extends('layouts.app')

@section('title', 'Manage Announcements')

@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('styles')
    @vite('resources/css/admin.css')
    
    <style>
    /* Simplified modal styles for better performance */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal.show {
        display: block;
    }
    
    .modal-dialog {
        position: relative;
        width: 90%;
        max-width: 600px;
        margin: 2rem auto;
        pointer-events: auto;
    }
    
    .modal-content {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        overflow: hidden;
    }
    
    .modal-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .modal-body {
        padding: 1.5rem;
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }
    
    .btn-close {
        background: transparent;
        border: 0;
        font-size: 1.5rem;
        cursor: pointer;
        opacity: 0.6;
    }
    
    .btn-close:hover {
        opacity: 1;
    }
    
    /* Ensure form elements work */
    .modal input,
    .modal select,
    .modal textarea,
    .modal button {
        pointer-events: auto !important;
    }
    
    @media (max-width: 575.98px) {
        .modal-dialog {
            width: 95%;
            margin: 1rem auto;
        }
    }
    </style>
@endpush

@push('scripts')
<script>
// Make functions globally available immediately
window.editAnnouncement = function(id, courseId, title, content, priority, expiresAt, isPinned) {
    const editModal = document.getElementById('editAnnouncementModal');
    if (editModal) {
        editModal.style.display = 'block'; // Simplified display
        editModal.classList.add('show');
    }
    
    // Populate form fields
    document.getElementById('edit_course_id').value = courseId;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_content').value = content;
    document.getElementById('edit_priority').value = priority;
    document.getElementById('edit_expires_at').value = expiresAt;
    document.getElementById('edit_is_pinned').checked = isPinned;
    
    // Set form action
    document.getElementById('editAnnouncementForm').action = '{{ route("admin.announcements.update", ":id") }}'.replace(':id', id);
};

window.confirmDelete = function(id) {
    const deleteModal = document.getElementById('deleteConfirmModal');
    if (deleteModal) {
        deleteModal.style.display = 'block'; // Simplified display
        deleteModal.classList.add('show');
    }
    
    // Store the announcement ID for deletion
    window.announcementToDelete = id;
};

// Function to delete announcement via AJAX
window.deleteAnnouncement = function() {
    if (!window.announcementToDelete) {
        showToast('No announcement selected for deletion', 'error');
        return;
    }
    
    // Call the global deleteAnnouncement function from admin.js
    if (typeof deleteAnnouncement === 'function') {
        deleteAnnouncement(window.announcementToDelete);
    } else {
        // Fallback to direct AJAX call
        fetch(`/admin/announcements/${window.announcementToDelete}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                showToast(result.message, 'success');
                closeModal(document.getElementById('deleteConfirmModal'));
                // Refresh the page to show updated list
                location.reload();
            } else {
                showToast(result.message || 'Error deleting announcement', 'error');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            showToast('Network error. Please try again.', 'error');
        });
    }
};

// Toast notification function
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `admin-toast admin-toast-${type}`;
    toast.innerHTML = `
        <div class="admin-toast-content">
            <span class="admin-toast-message">${message}</span>
            <button class="admin-toast-close" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing announcements page...');
    
    // Check if Bootstrap is available
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    console.log('Bootstrap Modal available:', typeof bootstrap?.Modal !== 'undefined');
    
    // Check if our modal elements exist
    console.log('Edit modal exists:', !!document.getElementById('editAnnouncementModal'));
    console.log('Delete modal exists:', !!document.getElementById('deleteConfirmModal'));
    
    // Initialize form event listeners
    initializeFormListeners();
    
    // Initialize modal close buttons
    initializeModalCloseButtons();
    
    // Add click-to-close on modal backdrop
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal(modal);
            }
        });
    });
    
    // Debug route generation
    console.log('Available routes:');
    console.log('Edit route:', '{{ route("admin.announcements.update", ":id") }}');
    console.log('Delete route:', '{{ route("admin.announcements.delete", ":id") }}');
    console.log('Store route:', '{{ route("admin.announcements.store") }}');
});

function initializeFormListeners() {
    // Handle form submission for edit
    const editForm = document.getElementById('editAnnouncementForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            console.log('Edit form submitted');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            // Let the form submit normally
            // No preventDefault() - let it go through
        });
    }
    
    // Handle form submission for delete
    const deleteForm = document.getElementById('deleteForm');
    if (deleteForm) {
        editForm.addEventListener('submit', function(e) {
            console.log('Delete form submitted');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            
            // Let the form submit normally
            // No preventDefault() - let it go through
        });
    }
}

// Utility function to close modals
function closeModal(modalElement) {
    if (modalElement) {
        modalElement.style.display = 'none';
        modalElement.classList.remove('show');
    }
}

// Add close functionality to all close buttons
function initializeModalCloseButtons() {
    const closeButtons = document.querySelectorAll('.btn-close, .btn-secondary');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal);
            }
        });
    });
}

function showAlert(message, type) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at the top of the page
    const container = document.querySelector('.container');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
@endpush

@section('content')
<div class="container mt-4">
    {{-- Back to Dashboard Button --}}
    <div class="admin-back-nav mb-3">
        <a href="{{ route('admin.dashboard') }}" class="admin-back-btn">
            ← Back to Dashboard
        </a>
    </div>

    {{-- Admin Navigation --}}
    <div class="admin-navigation mb-4">
        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link">Dashboard</a>
        <a href="{{ route('admin.courses.index') }}" class="admin-nav-link">Courses</a>
        <a href="{{ route('admin.announcements.index') }}" class="admin-nav-link active">Announcements</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Course Announcements</h4>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#announcementModal">
                        + Add Announcement
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Course</th>
                                    <th>Title</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                            @if($announcement->isActive())
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Announcement Modal -->
<div class="modal fade" id="announcementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.announcements.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Course</label>
                        <select name="course_id" id="course_id" class="form-select" required>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" id="content" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select name="priority" id="priority" class="form-select" required>
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expires_at" class="form-label">Expires At (Optional)</label>
                                <input type="datetime-local" name="expires_at" id="expires_at" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_pinned" id="is_pinned" class="form-check-input" value="1">
                            <label for="is_pinned" class="form-check-label">Pin this announcement</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Announcement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Announcement Modal -->
<div class="modal fade" id="editAnnouncementModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAnnouncementForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_course_id" class="form-label">Course</label>
                        <select name="course_id" id="edit_course_id" class="form-select" required>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Title</label>
                        <input type="text" name="title" id="edit_title" class="form-control" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_content" class="form-label">Content</label>
                        <textarea name="content" id="edit_content" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_priority" class="form-label">Priority</label>
                                <select name="priority" id="edit_priority" class="form-select" required>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_expires_at" class="form-label">Expires At (Optional)</label>
                                <input type="datetime-local" name="expires_at" id="edit_expires_at" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_pinned" id="edit_is_pinned" class="form-check-input" value="1">
                            <label for="edit_is_pinned" class="form-check-label">Pin this announcement</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Announcement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h6>Are you sure you want to delete this announcement?</h6>
                    <p class="text-muted">This action cannot be undone.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="deleteAnnouncement()">Delete Announcement</button>
            </div>
        </div>
    </div>
</div>

@endsection
