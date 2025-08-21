let selectedType = null;

window.choosePlan = function(type) {
  // Check if user is authenticated
  if (!window.isAuthenticated) {
    showLoginModal('You must be logged in to choose a plan.');
    return;
  }
  
  const planNames = {
    A: "Complete Transformation Program",
    B: "Employee Program", 
    C: "College Program"
  };
  selectedType = type;
  document.getElementById("modal-message").textContent =
    `Are you sure you want to subscribe to the ${planNames[type]}?`;
  document.getElementById("subscription-modal").style.display = "flex";
};

function showPricingToast(msg, isSuccess = true) {
  const toast = document.getElementById('pricing-toast');
  toast.innerHTML = msg;
  toast.className = isSuccess ? 'toast-success' : 'toast-error';
  toast.style.display = 'block';
  setTimeout(() => { toast.style.display = 'none'; }, 2500);
}

document.getElementById("confirm-subscribe").addEventListener("click", () => {
  if (!selectedType) return;

  fetch('/subscribe', {
    method: 'POST',
    credentials: 'same-origin',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      'Content-Type': 'application/json',
      'Accept': 'application/json'
    },
    body: JSON.stringify({ type: selectedType })
  })
  .then(async res => {
    if (!res.ok) {
      const text = await res.text();
      if (text.includes('<!DOCTYPE html>')) {
        throw new Error('Authentication error – please log in again.');
      }
      throw new Error(`Server error ${res.status}: ${res.statusText}`);
    }
    return res.json();
  })
  .then(data => {
    showPricingToast(
      `${data.message}<br><b>New Balance:</b> ${data.new_balance}`,
      true
    );
    setTimeout(() => window.location.reload(), 2100); // Reload after toast
  })
  .catch(err => {
    // Handle authentication errors
    if (err.message && (err.message.includes('Authentication error') || err.message.includes('401'))) {
      showLoginModal('Your session has expired. Please log in again.');
    } else {
      showPricingToast("Subscription failed:<br>" + err.message, false);
    }
  })
  .finally(() => {
    document.getElementById("subscription-modal").style.display = "none";
  });
});

document.getElementById("cancel-subscribe").addEventListener("click", () => {
  document.getElementById("subscription-modal").style.display = "none";
});
