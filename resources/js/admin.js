document.addEventListener("DOMContentLoaded", () => {
  const API_URL    = "/api/courses";
  const form       = document.getElementById("course-form");
  const courseList = document.getElementById("course-list");
  const clearBtn   = document.getElementById("clear-form");

  // ────────────────────────────────────────────────────────────────
  // 1) Category → “New Category” toggle
  const categorySelect  = document.getElementById("category_id");
  const newCatContainer = document.getElementById("new-category-container");
  const newCatInput     = document.getElementById("new_category");
  if (categorySelect) {
    categorySelect.addEventListener("change", () => {
      if (categorySelect.value === "__new__") {
        newCatContainer.style.display = "block";
      } else {
        newCatContainer.style.display = "none";
        newCatInput.value = "";
      }
    });
  }

  // ────────────────────────────────────────────────────────────────
  // 2) Repeater helper & setup
  function addRepeater(wrapperId) {
    const wrapper = document.getElementById(wrapperId);
    const items   = wrapper.querySelectorAll(".repeatable-item");
    const newIndex= items.length;
    const clone   = items[0].cloneNode(true);

    // Clear inputs and re-name them
    clone.querySelectorAll("input").forEach(input => {
      const name = input.getAttribute("name");
      input.value = "";
      input.setAttribute("name", name.replace(/\[\d+\]/, `[${newIndex}]`));
    });

    wrapper.appendChild(clone);
  }

  // Projects repeater
  document.getElementById("add-project")
          .addEventListener("click", () => addRepeater("projects-wrapper"));
  document.getElementById("projects-wrapper")
          .addEventListener("click", e => {
    if (e.target.matches(".remove-project")) {
      e.target.closest(".repeatable-item").remove();
    }
  });

  // Tools repeater
  document.getElementById("add-tool")
          .addEventListener("click", () => addRepeater("tools-wrapper"));
  document.getElementById("tools-wrapper")
          .addEventListener("click", e => {
    if (e.target.matches(".remove-tool")) {
      e.target.closest(".repeatable-item").remove();
    }
  });

  // ────────────────────────────────────────────────────────────────
  // 3) Form submit handler (Create / Update)
  if (form) {
    form.addEventListener("submit", async e => {
      e.preventDefault();
      const id = form.querySelector("#course-id")?.value;

      const formData = new FormData(form);
      // If updating, tell Laravel it's a PUT
      if (id) formData.append("_method", "PUT");

      const url    = id ? `${API_URL}/${id}` : API_URL;
      const method = id ? "POST" : "POST";

      try {
        const res = await fetch(url, { method, body: formData });
        if (!res.ok) throw res;
        const data = await res.json();
        console.log("✅ Saved:", data);
        resetForm();
        loadCourses();
      } catch (err) {
        console.error("❌ Save failed:", err);
        alert("Error saving course — check console.");
      }
    });
  }

  // ────────────────────────────────────────────────────────────────
  // 4) Helpers: reset, load, edit, delete
  function resetForm() {
    form.reset();
    form.querySelector("#course-id").value = "";
    // hide new-category input if shown
    if (newCatContainer) newCatContainer.style.display = "none";
  }

  async function loadCourses() {
    if (!courseList) return;
    courseList.innerHTML = "";
    try {
      const res  = await fetch(API_URL);
      const json = await res.json();
      json.data.forEach(c => {
        const card = document.createElement("div");
        card.className = "course-card col-md-4";
        card.innerHTML = `
          ${c.image ? `<img src="/storage/${c.image}" class="img-fluid mb-2">` : ""}
          <h5>${c.title}</h5>
          <p>${c.description}</p>
          <small>Instructor: ${c.instructor} | Enrollments: ${c.enrollments_count}</small>
          <p><strong>Objectives:</strong> ${Array.isArray(c.objectives)?c.objectives.join(", "):"N/A"}</p>
          <p><strong>Content:</strong> ${Array.isArray(c.course_content)?c.course_content.join(", "):"N/A"}</p>
          <button onclick="editCourse(${c.id})"   class="btn btn-sm btn-warning me-2">Edit</button>
          <button onclick="deleteCourse(${c.id})" class="btn btn-sm btn-danger">Delete</button>
        `;
        courseList.appendChild(card);
      });
    } catch (err) {
      console.error("❌ loadCourses failed:", err);
    }
  }

  window.editCourse = async function(id) {
    // if there's an existing curriculum file
if (c.curriculum) {
  const link = document.createElement('a');
  link.href = `/storage/${c.curriculum}`;
  link.textContent = "Download Existing Curriculum";
  link.target = "_blank";
  form.querySelector("#curriculum")?.parentNode.appendChild(link);
}

    try {
      const res  = await fetch(`${API_URL}/${id}`);
      const json = await res.json();
      const c    = json.data;
      form.querySelector("#course-id").value       = c.id;
      form.querySelector("input[name=title]").value= c.title;
      form.querySelector("textarea[name=description]").value = c.description;
      form.querySelector("input[name=instructor]").value     = c.instructor;
      form.querySelector("select[name=category_id]").value   = c.category_id;
      form.querySelector("select[name=status]").value        = c.status;
      // Note: your repeater inputs aren’t filled by this snippet;
      // for full two‐way bind you’d need to remove existing items & rebuild them from c.projects / c.tools
      window.scrollTo(0,0);
    } catch (err) {
      console.error("❌ editCourse failed:", err);
    }
  };

  window.deleteCourse = async function(id) {
    if (!confirm("Delete this course?")) return;
    try {
      await fetch(`${API_URL}/${id}`, { method: "DELETE" });
      loadCourses();
    } catch (err) {
      console.error("❌ deleteCourse failed:", err);
    }
  };

  // Clear button (if you have one)
  if (clearBtn) clearBtn.addEventListener("click", resetForm);

  // Initial load
  loadCourses();
});
