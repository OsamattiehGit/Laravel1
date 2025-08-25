{{-- resources/views/profile.blade.php --}}
@extends('layouts.app')

@push('styles')
<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .profile-header {
        background: #003366;
        color: #fff;
        border-radius: 0 0 36px 0;
        padding: 48px 0 38px 0;
        margin-bottom: 40px;
        text-align: left;
    }
    .profile-header .profile-title {
        font-family: 'Poppins', sans-serif;
        font-size: 2.4rem;
        font-weight: 700;
        margin-bottom: 10px;
        letter-spacing: -1px;
    }
    .profile-header .profile-meta {
        font-size: 1.2rem;
        color: #fff8ee;
        margin-bottom: 7px;
    }
    .profile-balance {
        background: #ff942f;
        color: #fff;
        font-weight: 600;
        padding: 8px 24px;
        border-radius: 10px;
        display: inline-block;
        font-size: 1.11rem;
        margin-top: 7px;
        letter-spacing: 1px;
    }
    .enrolled-section-title {
        font-family: 'Poppins', sans-serif;
        color: #003366;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 28px;
        letter-spacing: 1px;
    }
    .enrolled-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 22px;
    }
    .enrolled-card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 18px 0 rgba(44, 62, 80, 0.10);
        padding: 25px 16px 18px 16px;
        text-align: center;
        transition: box-shadow .22s;
        position: relative;
    }
    .enrolled-card:hover {
        box-shadow: 0 8px 30px 0 rgba(44, 62, 80, 0.13);
    }
    .enrolled-img {
            display: block;
    margin-left: auto;
    margin-right: auto;
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px;
        background: #f4f4fa;
        margin-bottom: 15px;
        border: 2px solid #e4e8ef;
    }
    .enrolled-title {
        color: #003366;
        font-family: 'Poppins', sans-serif;
        font-size: 1.13rem;
        font-weight: 600;
        margin-bottom: 6px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .enrolled-desc {
        font-size: 0.97rem;
        color: #555b6a;
        min-height: 36px;
        margin-bottom: 12px;
    }
    .enrolled-btn {
        display: inline-block;
        background: #ff942f;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: 1rem;
        padding: 7px 21px;
        margin-top: 8px;
        text-decoration: none;
        transition: background .15s;
    }
    .enrolled-btn:hover {
        background: #f37021;
        color: #fff;
    }
    
    .enrolled-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 8px;
    }
    
    .drop-btn {
        background: #dc3545 !important;
        font-size: 0.9rem;
        padding: 6px 18px;
    }
    
    .drop-btn:hover {
        background: #c82333 !important;
    }
    
    /* Modal Styles */
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
        max-width: 500px;
        margin: 2rem auto;
        pointer-events: auto;
    }
    
    .modal-content {
        background-color: #fff;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
        border: none;
    }
    
    .modal-header {
        padding: 1.5rem 1.5rem 1rem;
        border-bottom: 1px solid #e9ecef;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .modal-title {
        color: #003366;
        font-weight: 600;
        font-size: 1.25rem;
        margin: 0;
    }
    
    .modal-body {
        padding: 1.5rem;
        text-align: center;
    }
    
    .modal-footer {
        padding: 1rem 1.5rem 1.5rem;
        border-top: 1px solid #e9ecef;
        background: #f8f9fa;
        display: flex;
        gap: 0.75rem;
        justify-content: center;
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
    
    .btn {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 500;
        min-width: 120px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
    }
    
    .btn-danger {
        background: #dc3545;
        color: white;
    }
    
    .btn-danger:hover {
        background: #c82333;
    }
    
    .text-warning {
        color: #ffc107 !important;
    }
    
    .text-muted {
        color: #6c757d !important;
    }
    @media (max-width: 767px) {
        .profile-header {
            padding: 35px 0 25px 0;
            border-radius: 0 0 20px 0;
        }
        .profile-header .profile-title { font-size: 1.5rem; }
        .enrolled-section-title { font-size: 1.15rem; }
        .enrolled-img { width: 56px; height: 56px; }
        
        .enrolled-actions {
            gap: 6px;
        }
        
        .enrolled-btn, .drop-btn {
            font-size: 0.9rem;
            padding: 6px 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-header mb-4">
    <div class="container">
        <div class="profile-title mb-2">
            <i class="bi bi-person-fill" style="font-size: 2rem; vertical-align: middle; color: #ff942f;"></i>
            Profile
        </div>
        <div class="profile-meta">
            <strong>Username:</strong> {{ auth()->user()->name ?? 'User' }}
        </div>
        <div class="profile-meta">
            <strong>Email:</strong> {{ auth()->user()->email }}
        </div>
        <div class="profile-balance">
            Remaining Balance:
            <span>{{ auth()->user()->course_balance ?? 0 }}</span> credits
        </div>
    </div>
</div>

<div class="container mb-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-bell text-warning me-2"></i>
                        Desktop Notifications
                    </h6>
                    <p class="card-text small text-muted mb-3">
                        Enable desktop notifications to stay updated on course enrollments, drops, and other important updates.
                    </p>
                    <button id="notificationPermissionBtn" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-bell me-2"></i>
                        Enable Notifications
                    </button>
                    <button id="testNotificationBtn" class="btn btn-outline-info btn-sm ms-2" style="display: none;">
                        <i class="fas fa-paper-plane me-2"></i>
                        Test Notification
                    </button>
                    <div id="notificationStatus" class="mt-2 small"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <div class="enrolled-section-title">Your Enrolled Courses</div>
    @if(count($enrollments ?? []) > 0)
    <div class="enrolled-grid">
        @foreach($enrollments as $enrollment)
            @php $course = $enrollment->course; @endphp
            <div class="enrolled-card">
                <img src="{{ $course->image ? Storage::url($course->image) : asset('images/default-course.png') }}"
                     class="enrolled-img"
                     alt="{{ $course->title }}">
                <div class="enrolled-title">{{ $course->title }}</div>
                <div class="enrolled-desc">
                    {{ \Illuminate\Support\Str::limit($course->description, 80) }}
                </div>
                <div class="enrolled-actions">
                    <a href="{{ route('course.show', $course->id) }}" class="enrolled-btn">Go to Course</a>
                    <button type="button" class="enrolled-btn drop-btn" onclick="confirmDropCourse({{ $course->id }}, '{{ $course->title }}')">
                        Drop Course
                    </button>
                </div>
            </div>
        @endforeach
    </div>
    @else
        <div class="alert alert-info text-center">
            You are not enrolled in any courses yet.
        </div>
    @endif
</div>

<!-- Drop Course Confirmation Modal -->
<div id="dropCourseModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Drop Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h6>Are you sure you want to drop this course?</h6>
                    <p class="text-muted" id="dropCourseName"></p>
                    <p class="text-muted small">This action cannot be undone. You will lose access to all course content.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="dropCourseForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Drop Course</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/desktop-notifications.js')
<script>
function confirmDropCourse(courseId, courseTitle) {
    // Set the course name in the modal
    document.getElementById('dropCourseName').textContent = courseTitle;
    
    // Set the form action
    document.getElementById('dropCourseForm').action = '{{ route("course.drop", ":id") }}'.replace(':id', courseId);
    
    // Show the modal using Bootstrap 5
    const modal = new bootstrap.Modal(document.getElementById('dropCourseModal'));
    modal.show();
}

// Handle form submission
document.addEventListener('DOMContentLoaded', function() {
    // Initialize notification permission button
    initializeNotificationButton();
    
    // Request notification permission on page load
    if (window.requestNotificationPermission) {
        window.requestNotificationPermission();
    }
    
    // Show success notification if there's a session message
    @if(session('success'))
        if (window.showDesktopNotification) {
            window.showDesktopNotification('Course Dropped', @json(session('success')));
        }
    @endif
    
    const dropForm = document.getElementById('dropCourseForm');
    if (dropForm) {
        dropForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Dropping...';
            submitBtn.disabled = true;
            
            // Submit the form
            this.submit();
        });
    }
    
    // Add click-to-close on modal backdrop
    const modal = document.getElementById('dropCourseModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) {
                    bsModal.hide();
                }
            }
        });
    }
});

function initializeNotificationButton() {
    const btn = document.getElementById('notificationPermissionBtn');
    const status = document.getElementById('notificationStatus');
    const testBtn = document.getElementById('testNotificationBtn');
    
    if (!btn || !status || !testBtn) return;
    
    // Update button based on current permission
    updateNotificationButton();
    
    // Add click handler
    btn.addEventListener('click', function() {
        if (Notification.permission === 'default') {
            Notification.requestPermission().then(permission => {
                updateNotificationButton();
                if (permission === 'granted') {
                    showNotificationStatus('Notifications enabled! You\'ll receive updates for course activities.', 'success');
                } else if (permission === 'denied') {
                    showNotificationStatus('Notifications blocked. You can enable them in your browser settings.', 'warning');
                }
            });
        } else if (Notification.permission === 'denied') {
            showNotificationStatus('Notifications are blocked. Please enable them in your browser settings.', 'warning');
        }
    });

    // Add test notification button handler
    testBtn.addEventListener('click', function() {
        if (window.testNotification) {
            const success = window.testNotification();
            if (success) {
                showNotificationStatus('Test notification sent! Check your desktop.', 'success');
            }
        }
    });
}

function updateNotificationButton() {
    const btn = document.getElementById('notificationPermissionBtn');
    const status = document.getElementById('notificationStatus');
    const testBtn = document.getElementById('testNotificationBtn');
    
    if (!btn || !status || !testBtn) return;
    
    switch (Notification.permission) {
        case 'granted':
            btn.innerHTML = '<i class="fas fa-check text-success me-2"></i>Notifications Enabled';
            btn.className = 'btn btn-success btn-sm';
            btn.disabled = true;
            showNotificationStatus('Desktop notifications are enabled!', 'success');
            testBtn.style.display = 'inline-block'; // Show test button
            break;
        case 'denied':
            btn.innerHTML = '<i class="fas fa-times text-danger me-2"></i>Notifications Blocked';
            btn.className = 'btn btn-danger btn-sm';
            btn.disabled = true;
            showNotificationStatus('Notifications are blocked. Enable them in browser settings.', 'warning');
            testBtn.style.display = 'none'; // Hide test button
            break;
        default:
            btn.innerHTML = '<i class="fas fa-bell me-2"></i>Enable Notifications';
            btn.className = 'btn btn-outline-warning btn-sm';
            btn.disabled = false;
            showNotificationStatus('Click to enable desktop notifications', 'info');
            testBtn.style.display = 'none'; // Hide test button
    }
}

function showNotificationStatus(message, type) {
    const status = document.getElementById('notificationStatus');
    if (!status) return;
    
    const alertClass = type === 'success' ? 'text-success' : type === 'warning' ? 'text-warning' : 'text-info';
    status.innerHTML = `<span class="${alertClass}"><i class="fas fa-info-circle me-1"></i>${message}</span>`;
}
</script>
@endpush
