document.addEventListener("DOMContentLoaded", () => {
  // 1) Category → "New Category" toggle
  const categorySelect  = document.getElementById("category_id");
  const newCatContainer = document.getElementById("new-category-container");
  const newCatInput     = document.getElementById("new_category");

  if (categorySelect && newCatContainer && newCatInput) {
    categorySelect.addEventListener("change", () => {
      if (categorySelect.value === "__new__") {
        newCatContainer.style.display = "block";
        newCatInput.focus();
      } else {
        newCatContainer.style.display = "none";
        newCatInput.value = "";
      }
    });
  }

  // 2) Projects/Tools repeater
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
  const addProjectBtn = document.getElementById("add-project");
  if (addProjectBtn) {
    addProjectBtn.addEventListener("click", () => addRepeater("projects-wrapper"));
  }

  const projectsWrapper = document.getElementById("projects-wrapper");
  if (projectsWrapper) {
    projectsWrapper.addEventListener("click", e => {
      if (e.target.matches(".remove-project")) {
        e.target.closest(".repeatable-item").remove();
      }
    });
  }

  // Tools repeater
  const addToolBtn = document.getElementById("add-tool");
  if (addToolBtn) {
    addToolBtn.addEventListener("click", () => addRepeater("tools-wrapper"));
  }

  const toolsWrapper = document.getElementById("tools-wrapper");
  if (toolsWrapper) {
    toolsWrapper.addEventListener("click", e => {
      if (e.target.matches(".remove-tool")) {
        e.target.closest(".repeatable-item").remove();
      }
    });
  }

  // 3) Course Content Builder
  const container = document.getElementById('course-content-details');
  const addSectionBtn = document.getElementById('add-section');

  if (!container || !addSectionBtn) return;

  let sectionIndex = 0;

  function createContentItem(sectionIdx, itemIdx, item = { type: 'text', value: '' }) {
    const itemDiv = document.createElement('div');
    itemDiv.className = 'content-item border rounded p-2 mb-2';
    itemDiv.dataset.itemIndex = itemIdx;

    const isText = item.type === 'text';
    const isImage = item.type === 'image';
    const isVideo = item.type === 'video';

    itemDiv.innerHTML = `
      <div class="d-flex gap-2 align-items-start flex-wrap">
        <select name="course_content_details[${sectionIdx}][items][${itemIdx}][type]"
                class="form-select item-type" style="min-width:120px">
          <option value="text" ${isText ? 'selected' : ''}>Text</option>
          <option value="image" ${isImage ? 'selected' : ''}>Image</option>
          <option value="video" ${isVideo ? 'selected' : ''}>Video</option>
        </select>

        <!-- Text Input -->
        <input type="text"
               class="form-control item-value ${isText ? '' : 'd-none'}"
               name="course_content_details[${sectionIdx}][items][${itemIdx}][value]"
               placeholder="Enter text content..."
               value="${isText ? (item.value || '') : ''}"
               style="min-width:250px">

        <!-- File Input -->
        <input type="file"
               class="form-control item-file ${isText ? 'd-none' : ''}"
               name="course_content_details[${sectionIdx}][items][${itemIdx}][file]"
               accept="${isImage ? 'image/*' : (isVideo ? 'video/*' : 'image/*,video/*')}"
               style="min-width:250px">

        <!-- Hidden field for existing file path -->
        <input type="hidden"
               name="course_content_details[${sectionIdx}][items][${itemIdx}][_existing]"
               value="${item.value || ''}">

        <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
      </div>
    `;

    // Add preview for existing files
    if (item.value && (isImage || isVideo)) {
      const preview = document.createElement('div');
      preview.className = 'mt-2 ms-1';

      if (isImage) {
        preview.innerHTML = `<img src="/storage/${item.value}" style="max-height:80px; border:1px solid #ddd; padding:2px; border-radius:4px" alt="Preview">`;
      } else {
        preview.innerHTML = `<video src="/storage/${item.value}" style="max-height:80px; border-radius:4px" controls></video>`;
      }
      itemDiv.appendChild(preview);
    }

    // Handle type change
    const typeSelect = itemDiv.querySelector('.item-type');
    const textInput = itemDiv.querySelector('.item-value');
    const fileInput = itemDiv.querySelector('.item-file');

    typeSelect.addEventListener('change', (e) => {
      const type = e.target.value;
      if (type === 'text') {
        textInput.classList.remove('d-none');
        fileInput.classList.add('d-none');
        fileInput.value = '';
      } else {
        textInput.classList.add('d-none');
        fileInput.classList.remove('d-none');
        fileInput.setAttribute('accept', type === 'image' ? 'image/*' : 'video/*');
      }
    });

    // Remove item
    itemDiv.querySelector('.remove-item').addEventListener('click', () => {
      itemDiv.remove();
    });

    return itemDiv;
  }

  function createSection(sectionIdx, section = { section: '', items: [] }) {
    const sectionDiv = document.createElement('div');
    sectionDiv.className = 'border rounded p-3 mb-3 section-group';
    sectionDiv.dataset.sectionIndex = sectionIdx;

    sectionDiv.innerHTML = `
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Section ${sectionIdx + 1}</h6>
        <button type="button" class="btn btn-danger btn-sm remove-section">Remove Section</button>
      </div>

      <div class="mb-3">
        <label class="form-label">Section Title</label>
        <input type="text"
               class="form-control"
               name="course_content_details[${sectionIdx}][section]"
               value="${section.section || ''}"
               placeholder="Enter section title...">
      </div>

      <div class="items-container mb-3"></div>

      <button type="button" class="btn btn-secondary btn-sm add-item">+ Add Content Item</button>
    `;

    const itemsContainer = sectionDiv.querySelector('.items-container');
    const addItemBtn = sectionDiv.querySelector('.add-item');
    const removeSectionBtn = sectionDiv.querySelector('.remove-section');

    // Preload existing items
    if (Array.isArray(section.items) && section.items.length > 0) {
      section.items.forEach((item, itemIdx) => {
        itemsContainer.appendChild(createContentItem(sectionIdx, itemIdx, item));
      });
    } else {
      // Add at least one empty item
      itemsContainer.appendChild(createContentItem(sectionIdx, 0));
    }

    // Add new item
    addItemBtn.addEventListener('click', () => {
      const itemIdx = itemsContainer.children.length;
      itemsContainer.appendChild(createContentItem(sectionIdx, itemIdx));
    });

    // Remove section
    removeSectionBtn.addEventListener('click', () => {
      sectionDiv.remove();
    });

    return sectionDiv;
  }

  // Initialize with preloaded data
  const preloadRaw = document.getElementById('preload-content-details')?.value || '[]';
  let data = [];

  try {
    data = JSON.parse(preloadRaw) || [];
  } catch(e) {
    data = [];
  }

  // Ensure we have at least one section
  if (!Array.isArray(data) || data.length === 0) {
    data = [{ section: '', items: [] }];
  }

  // Render sections
  data.forEach((section, idx) => {
    container.appendChild(createSection(idx, section));
    sectionIndex = idx + 1;
  });

  // Add new section
  addSectionBtn.addEventListener('click', () => {
    container.appendChild(createSection(sectionIndex));
    sectionIndex++;
  });
});
