import { requestNotificationPermission, showNotification } from './desktop-notifications';

document.addEventListener('DOMContentLoaded', () => {
  // Request notification permission when course page loads
  requestNotificationPermission();

  // Initialize enrollment functionality only if not already enrolled
  if (!document.querySelector('.enrollment-success')) {
    initializeEnrollment();
  }
  

  // Initialize course content interactions
  initializeCourseContent();
  

  // Initialize download tracking
  initializeDownloadTracking();
});
function initializeEnrollment() {
  const btn = document.getElementById('enroll-btn');
  if (!btn) return;

  // Pull in the real course ID & title from data-attrs:
  const courseId    = btn.dataset.courseId;
  const courseTitle = btn.dataset.courseTitle;

  btn.addEventListener('click', async () => {
    // 1) Out of credits → pricing page
    if (window.courseBalance <= 0) {
      return window.location.href = window.pricingUrl;
    }

    // 2) Custom Modal
    showEnrollModal(courseTitle)
      .then(async (confirmed) => {
        if (!confirmed) return;

        try {
          // Show loading state
          btn.disabled = true;
          btn.innerHTML = '<span class="loading-spinner"></span> Enrolling...';
          
          const result = await AjaxRequest.post(`${window.enrollUrlBase}/${courseId}/enroll`, {});

          if (result.success) {
            const data = result.data;
            Toast.success(`${data.message}<br>Remaining credits: <b>${data.balance}</b>`);
            showNotification('Enrollment Successful', `${data.message} Remaining credits: ${data.balance}`);
            window.courseBalance = data.balance;
            
            // Update UI to show enrolled state
            updateEnrollmentState(true);
            
            // Reload page after short delay to show new content
            setTimeout(() => location.reload(), 2000);
          } else {
            Toast.error(result.error || "Enrollment failed. Please try again.");
          }
        } catch (e) {
          console.error('Enrollment error:', e);
          Toast.error('Network error. Please check your connection and try again.');
        } finally {
          // Reset button state with proper SVG icon
          btn.disabled = false;
          btn.innerHTML = `<svg width="30" height="30" viewBox="0 0 30 37" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M4.22064 15.8512V4.06131C3.21156 4.06131 2.39352 3.26145 2.39352 2.2748C2.39352 1.28814 3.21156 0.488281 4.22064 0.488281H26.1461C27.1552 0.488281 27.9732 1.28814 27.9732 2.2748C27.9732 3.26145 27.1552 4.06131 26.1461 4.06131V15.8512C27.8486 17.6747 28.7627 19.4863 29.2498 20.9149C29.5166 21.6976 29.654 22.3615 29.7248 22.8462C29.7603 23.0888 29.7791 23.2869 29.7892 23.4336C29.7941 23.507 29.7968 23.5676 29.7985 23.6144L29.7996 23.6562L29.7999 23.6742L29.8003 23.6964V23.7053V23.7092V23.7112C29.8003 23.7112 29.7919 23.4766 29.8003 23.713C29.8003 24.6997 28.9823 25.4995 27.9732 25.4995H17.0105V34.4321C17.0105 35.4188 16.1925 36.2186 15.1834 36.2186C14.1742 36.2186 13.3562 35.4188 13.3562 34.4321V25.4995H2.39352C1.38444 25.4995 0.566406 24.6997 0.566406 23.713C0.566406 22.8197 0.566406 23.7112 0.566406 23.7112V23.7092L0.566425 23.7053L0.566479 23.6964L0.566753 23.6742C0.567009 23.6578 0.567484 23.6378 0.568252 23.6144C0.569786 23.5676 0.572582 23.507 0.577589 23.4336C0.587583 23.2869 0.606438 23.0888 0.641866 22.8462C0.712685 22.3615 0.850121 21.6976 1.11695 20.9149C1.60399 19.4863 2.51821 17.6747 4.22064 15.8512ZM22.4918 4.06131H7.87488V16.5669C7.87488 17.0407 7.68238 17.4952 7.33974 17.8302C5.79213 19.3433 5.01685 20.8285 4.62495 21.9265H25.7417C25.3498 20.8285 24.5746 19.3433 23.027 17.8302C22.6844 17.4952 22.4918 17.0407 22.4918 16.5669V4.06131Z" fill="#F98149"/>

</svg><span>Enroll Now</span>`;
        }
      });
  });
}

function updateEnrollmentState(isEnrolled) {
  const enrollBtn = document.getElementById('enroll-btn');
  if (enrollBtn && isEnrolled) {
    enrollBtn.style.display = 'none';
    
    // Show success message in place of button
    const successDiv = document.createElement('div');
    successDiv.className = 'enrollment-success';
    successDiv.innerHTML = `
      <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 8px; text-align: center;">
        <strong>✓ Successfully Enrolled!</strong><br>
        <small>You now have access to all course content.</small>
      </div>
    `;
    enrollBtn.parentNode.insertBefore(successDiv, enrollBtn);
  }
}

function initializeCourseContent() {
  // Add progress tracking for course content
  const accordionItems = document.querySelectorAll('.acc-item');
  
  accordionItems.forEach((item, index) => {
    const toggle = item.querySelector('.acc-toggle');
    if (toggle) {
      toggle.addEventListener('change', () => {
        if (toggle.checked) {
          trackContentView(index + 1);
        }
      });
    }
  });
}

function initializeDownloadTracking() {
  const downloadLinks = document.querySelectorAll('a[href*="download"], a[href*="curriculum"]');
  
  downloadLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      const fileName = link.href.split('/').pop();
      trackDownload(fileName);
      
      // Show download toast
      Toast.info('Download started...', 2000);
    });
  });
}

async function trackContentView(sectionNumber) {
  try {
    await AjaxRequest.post('/api/track-content-view', {
      section: sectionNumber,
      course_id: window.courseId || document.querySelector('[data-course-id]')?.dataset.courseId
    });
  } catch (error) {
    console.log('Content tracking failed:', error);
  }
}

async function trackDownload(fileName) {
  try {
    await AjaxRequest.post('/api/track-download', {
      file_name: fileName,
      course_id: window.courseId || document.querySelector('[data-course-id]')?.dataset.courseId
    });
  } catch (error) {
    console.log('Download tracking failed:', error);
  }
}

// --- Modal & Toast Functions, unchanged ---
function showEnrollModal(courseTitle) {
  return new Promise((resolve) => {
    const modal = document.getElementById('enroll-modal');
    const message = document.getElementById('enroll-modal-message');
    message.innerHTML = `Are you sure you want to enroll in <b>“${courseTitle}”</b>?`;
    modal.style.display = 'flex';

    function cleanup(result) {
      modal.style.display = 'none';
      confirmBtn.removeEventListener('click', yesHandler);
      cancelBtn.removeEventListener('click', noHandler);
    }

    const confirmBtn = document.getElementById('enroll-confirm-btn');
    const cancelBtn = document.getElementById('enroll-cancel-btn');

    function yesHandler() { cleanup(true); resolve(true); }
    function noHandler()  { cleanup(false); resolve(false); }

    confirmBtn.addEventListener('click', yesHandler);
    cancelBtn.addEventListener('click', noHandler);

    // Dismiss on backdrop click
    modal.onclick = (e) => {
      if (e.target === modal) cleanup(false), resolve(false);
    };
  });
}

function showToast(msg, success = true) {
  let toast = document.getElementById('custom-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'custom-toast';
        document.body.appendChild(toast);
    }
  toast.innerHTML = msg;
  toast.className = 'custom-toast ' + (success ? 'toast-success' : 'toast-error');
  toast.style.display = 'block';
  setTimeout(() => { toast.style.display = 'none'; }, 2500);
}

// Function to show enrollment required message
function showEnrollmentRequired() {
  console.log('showEnrollmentRequired called'); // Debug log
  showToast("You have to be enrolled to access Course content", false);
  
  // Fallback alert in case toast doesn't work
  if (typeof showToast === 'undefined') {
    alert("You have to be enrolled to access Course content");
  }
}

// Make function globally available
window.showEnrollmentRequired = showEnrollmentRequired;
// Smooth accordion open/close (figma-like)
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.acc-item').forEach(item => {
    const chk = item.querySelector('.acc-toggle');
    const body = item.querySelector('.acc-body');
    if (!chk || !body) return;

    const sync = () => {
      if (chk.checked) {
        body.classList.add('is-open');
        body.style.maxHeight = body.scrollHeight + 'px';
      } else {
        body.style.maxHeight = 0;
        body.classList.remove('is-open');
      }
    };
    chk.addEventListener('change', sync);
    // initialize closed
    sync();
  });
});
