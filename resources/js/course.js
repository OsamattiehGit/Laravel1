document.addEventListener('DOMContentLoaded', () => {
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
          // 3) POST with credentials + JSON (this code stays)
          const res = await fetch(
            `${window.enrollUrlBase}/${courseId}/enroll`,
            {
              method: 'POST',
              credentials: 'same-origin',
              headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              body: JSON.stringify({})
            }
          );

          // Success: 2xx codes only
          if (res.ok) {
            const data = await res.json();
            showToast(`${data.message}<br>Remaining slots: <b>${data.balance}</b>`, true);
            window.courseBalance = data.balance;
          } else {
            // Error: get message or fallback
            const error = await res.json().catch(() => ({}));
           showToast("You are already enrolled in this course.", false);
          }
        } catch (e) {
          showToast('Network error: ' + (e.message || 'Unknown'), false);
        }
      });
  });
});

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
