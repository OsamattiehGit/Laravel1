let selectedType = null;

window.choosePlan = function(type) {
  const planNames = {
    A: "College Program",
    B: "Employee Program",
    C: "Complete Transformation Program"
  };
  selectedType = type;
  document.getElementById("modal-message").textContent =
    `Are you sure you want to subscribe to Plan ${planNames[type]}?`;
  document.getElementById("subscription-modal").style.display = "flex";
};

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
      // if Laravel redirected you to HTML (login page or error),
      // res.text() will contain a "<!DOCTYPE html>…"
      const text = await res.text();
      if (text.includes('<!DOCTYPE html>')) {
        throw new Error('Authentication error – please log in again.');
      }
      throw new Error(`Server error ${res.status}: ${res.statusText}`);
    }
    return res.json();
  })
  .then(data => {
    alert(`${data.message}\nNew Balance: ${data.new_balance}`);
    window.location.reload();
  })
  .catch(err => {
    console.error("Subscription failed:", err.message);
    alert("Subscription failed:\n" + err.message);
  })
  .finally(() => {
    document.getElementById("subscription-modal").style.display = "none";
  });
});

document.getElementById("cancel-subscribe").addEventListener("click", () => {
  document.getElementById("subscription-modal").style.display = "none";
});
