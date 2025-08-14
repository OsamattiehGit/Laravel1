document.addEventListener("DOMContentLoaded", () => {
  const API_URL    = "/api/courses";
  const form       = document.getElementById("course-form");
  const courseList = document.getElementById("course-list");
  const clearBtn   = document.getElementById("clear-form");

  // 1) Category → “New Category” toggle
  const categorySelect  = document.getElementById("category_id");
  const newCatContainer = document.getElementById("new-category-container");
  const newCatInput     = document.getElementById("new_category");
  if (categorySelect && newCatContainer && newCatInput) {
    categorySelect.addEventListener("change", () => {
      if (categorySelect.value === "__new__") {
        newCatContainer.style.display = "block";
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

  // 3) Form submit handler (Create / Update)
  if (form) {
    form.addEventListener("submit", async e => {
      // Normal submission (handled by Laravel)
    });
  }

  // 4) Helpers (reset, load, edit, delete) — these are for AJAX admin panels (optional)
  function resetForm() {
    if (form) {
      form.reset();
      if (form.querySelector("#course-id")) form.querySelector("#course-id").value = "";
      if (newCatContainer) newCatContainer.style.display = "none";
    }
  }
  if (clearBtn) clearBtn.addEventListener("click", resetForm);

  // ----------- Sectioned Course Content Builder (jQuery) -----------
  // (We use jQuery for event delegation & easier DOM building)
  let sectionIndex = 0;

  function sectionHtml(idx, title = '', items = []) {
      let html = `
      <div class="border rounded p-3 my-2 section-group" data-section-index="${idx}">
          <label>Section Title</label>
          <input type="text" name="course_content_details[${idx}][section]" class="form-control mb-2" required value="${title}">
          <div class="content-items">`;
      items.forEach((item, j) => {
          html += itemHtml(idx, j, item.type, item.value);
      });
      html += `</div>
          <button type="button" class="btn btn-sm btn-secondary add-content-item">+ Add Content Item</button>
          <button type="button" class="btn btn-danger btn-sm float-end remove-section">Remove Section</button>
      </div>`;
      return html;
  }

  function itemHtml(idx, itemIdx, type = 'text', value = '') {
      return `
      <div class="content-item my-2" data-item-index="${itemIdx}">
          <select name="course_content_details[${idx}][items][${itemIdx}][type]" class="form-select d-inline-block w-auto me-2" style="width:120px">
              <option value="text" ${type==='text'?'selected':''}>Text</option>
              <option value="image" ${type==='image'?'selected':''}>Image</option>
              <option value="video" ${type==='video'?'selected':''}>Video</option>
          </select>
          <input type="text" name="course_content_details[${idx}][items][${itemIdx}][value]" class="form-control d-inline-block w-50" placeholder="Enter text, image URL, or video link" value="${value||''}" />
          <button type="button" class="btn btn-danger btn-sm remove-content-item">Remove</button>
      </div>`;
  }

  // Add Section
  $('#add-section').on('click', function() {
      $('#course-content-details').append(sectionHtml(sectionIndex));
      sectionIndex++;
  });

  // Add Content Item
  $(document).on('click', '.add-content-item', function(){
      const group = $(this).closest('.section-group');
      const idx = group.data('section-index');
      const itemIdx = group.find('.content-item').length;
      group.find('.content-items').append(itemHtml(idx, itemIdx));
  });

  // Remove Section
  $(document).on('click', '.remove-section', function(){
      $(this).closest('.section-group').remove();
  });
  // Remove Content Item
  $(document).on('click', '.remove-content-item', function(){
      $(this).closest('.content-item').remove();
  });

  // Repopulate if editing or on validation error
  let preload = $('#preload-content-details').val();
  if (preload && preload !== "null") {
      let data = [];
      try { data = JSON.parse(preload); } catch(e) {}
      data.forEach(function(section, i) {
          $('#course-content-details').append(sectionHtml(i, section.section ?? '', section.items ?? []));
          sectionIndex = i + 1;
      });
  }
});
// resources/js/admin.js
document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('course-content-details');
  const preloadRaw = document.getElementById('preload-content-details')?.value || '[]';
  let data = [];
  try { data = JSON.parse(preloadRaw) || []; } catch(e) { data = []; }
  if (!Array.isArray(data) || data.length === 0) data = [{ section: '', items: [] }];

  const addSectionBtn = document.getElementById('add-section');

  function el(html) {
    const tmp = document.createElement('template');
    tmp.innerHTML = html.trim();
    return tmp.content.firstChild;
  }

  function itemRow(sIdx, iIdx, item = { type: 'text', value: '' }) {
    const id = `s${sIdx}i${iIdx}`;

    const acceptImg = 'image/*';
    const acceptVid = 'video/mp4,video/webm,video/ogg';

    const isText  = item.type === 'text';
    const isImage = item.type === 'image';
    const isVideo = item.type === 'video';

    const row = el(`
      <div class="d-flex gap-2 align-items-start mb-2 content-item-row">

        <select name="course_content_details[${sIdx}][items][${iIdx}][type]"
                class="form-select item-type" style="max-width:130px">
          <option value="text"  ${isText  ? 'selected' : ''}>Text</option>
          <option value="image" ${isImage ? 'selected' : ''}>Image</option>
          <option value="video" ${isVideo ? 'selected' : ''}>Video</option>
        </select>

        <!-- Text -->
        <input type="text"
               class="form-control item-value ${isText ? '' : 'd-none'}"
               name="course_content_details[${sIdx}][items][${iIdx}][value]"
               placeholder="Write text…"
               value="${isText ? (item.value || '') : ''}"
               style="min-width:260px">

        <!-- File (image/video) -->
        <input type="file"
               class="form-control item-file ${isText ? 'd-none' : ''}"
               name="course_content_details[${sIdx}][items][${iIdx}][file]"
               accept="${isImage ? acceptImg : (isVideo ? acceptVid : `${acceptImg},${acceptVid}`)}"
               style="min-width:260px">

        <!-- keep existing path if any (when no new file chosen) -->
        <input type="hidden"
               name="course_content_details[${sIdx}][items][${iIdx}][_existing]"
               value="${item.value ? item.value : ''}">

        <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
      </div>
    `);

    // tiny preview (existing)
    const preview = el(`<div class="mb-2 ms-1"></div>`);
    if (item.value && (isImage || isVideo)) {
      if (isImage) {
        preview.innerHTML = `<img src="/storage/${item.value}" style="max-height:70px;border:1px solid #ddd;padding:2px;border-radius:4px">`;
      } else {
        preview.innerHTML = `
          <video src="/storage/${item.value}" style="max-height:70px;border-radius:4px" controls></video>
        `;
      }
      row.appendChild(preview);
    }

    // toggle fields when type changes
    const typeSel = row.querySelector('.item-type');
    const valInput = row.querySelector('.item-value');
    const fileInput = row.querySelector('.item-file');

    typeSel.addEventListener('change', (e) => {
      const t = e.target.value;
      if (t === 'text') {
        valInput.classList.remove('d-none');
        fileInput.classList.add('d-none');
        fileInput.value = ''; // clear file
      } else {
        valInput.classList.add('d-none');
        fileInput.classList.remove('d-none');
        fileInput.setAttribute('accept', t === 'image' ? acceptImg : acceptVid);
      }
    });

    // remove button
    row.querySelector('.remove-item').addEventListener('click', () => {
      row.parentElement.remove();
    });

    return row;
  }

  function sectionBlock(sIdx, section = { section: '', items: [] }) {
    const block = el(`
      <div class="border rounded p-3 mb-3">
        <label class="form-label">Section Title</label>
        <input type="text" class="form-control mb-2"
               name="course_content_details[${sIdx}][section]"
               value="${section.section || ''}">

        <div class="items-wrap"></div>

        <button type="button" class="btn btn-secondary btn-sm mt-2 add-item">+ Add Content Item</button>
        <button type="button" class="btn btn-danger btn-sm mt-2 remove-section float-end">Remove Section</button>
        <div class="clearfix"></div>
      </div>
    `);

    const wrap = block.querySelector('.items-wrap');

    // preload items
    if (Array.isArray(section.items) && section.items.length) {
      section.items.forEach((it, iIdx) => {
        wrap.appendChild(itemRow(sIdx, iIdx, it));
      });
    }

    // add item
    block.querySelector('.add-item').addEventListener('click', () => {
      const iIdx = wrap.children.length;
      wrap.appendChild(itemRow(sIdx, iIdx));
    });

    // remove section
    block.querySelector('.remove-section').addEventListener('click', () => {
      block.remove();
      // (we don't renumber names on delete—Laravel will still read array entries)
    });

    return block;
  }

  // render sections
  data.forEach((sec, sIdx) => container.appendChild(sectionBlock(sIdx, sec)));

  addSectionBtn?.addEventListener('click', () => {
    const sIdx = container.children.length;
    container.appendChild(sectionBlock(sIdx));
  });
});
