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

    // 2) Confirm
    if (!confirm(`Enroll in “${courseTitle}”?`)) {
      return;
    }

    try {
      // 3) POST with credentials + JSON
      const res = await fetch(
        `${window.enrollUrlBase}/${courseId}/enroll`,
        {
          method: 'POST',
          credentials: 'same-origin',  // ◀️ send laravel_session
          headers: {
            'Accept':       'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
              .querySelector('meta[name="csrf-token"]')
              .content
          },
          body: JSON.stringify({})     // ◀️ CSRF needs a body
        }
      );

      // 4) Non-200 → pull out JSON error
      if (!res.ok) {
        const err = await res.json().catch(() => null);
        throw new Error(err?.error || res.statusText);
      }

      // 5) All good
      const data = await res.json();
      alert(`${data.message}\nRemaining slots: ${data.balance}`);
      window.courseBalance = data.balance;

    } catch (e) {
      console.error('Enrollment failed:', e);
      alert(e.message);
    }
  });
});
