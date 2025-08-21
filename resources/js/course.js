document.addEventListener('DOMContentLoaded', () => {
  // Initialize enrollment functionality
  initializeEnrollment();
  
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
          // Reset button state
          btn.disabled = false;
          btn.innerHTML = '<svg>...</svg><span>Enroll Now</span>';
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
