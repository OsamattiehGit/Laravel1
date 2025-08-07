// Just one module: search + filter + sort + pagination
document.addEventListener('DOMContentLoaded', () => {
  const grid    = document.getElementById('course-list');
  const search  = document.getElementById('search-box');
  const filters = document.querySelectorAll('.filter-btn');
  const sortSel = document.getElementById('sort-select');
  const pager   = document.getElementById('pagination');

  if (!grid) return;

  // wire up events
  search.addEventListener('input', debounce(() => load(1), 300));
  filters.forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelector('.filter-btn.active').classList.remove('active');
      btn.classList.add('active');
      load(1);
    });
  });
  sortSel.addEventListener('change', () => load(1));

  // initial load
  load(1);

  // core loader
  function load(page) {
    const q      = search.value.trim();
    const filter = document.querySelector('.filter-btn.active').dataset.filter;
    const sort   = sortSel.value;
    const params = new URLSearchParams({ page, q, filter, sort });

    fetch(`${window.location.origin}/api/courses?${params}`)
      .then(r => {
        if (!r.ok) throw new Error(`HTTP ${r.status}`);
        return r.json();
      })
      .then(json => {
        render(json.data);
        renderPager(json.meta);
      })
      .catch(err => {
        console.error('Error loading courses:', err);
        grid.innerHTML = `<p style="text-align:center;color:red;">
                            Failed to load courses.
                          </p>`;
      });
  }

  // render grid
  function render(courses) {
    grid.innerHTML = '';
    if (!courses.length) {
      grid.innerHTML = `<p style="text-align:center;">No courses found.</p>`;
      return;
    }
    courses.forEach(c => {
      const url = c.image
        ? `${window.location.origin}/storage/${c.image}`
        : '/images/default-course.png';

      const div = document.createElement('div');
      div.className = 'course-card';
      div.innerHTML = `
        <div class="course-card-inner">
          <div class="course-card-top">
            <img src="${url}" alt="${c.title}">
          </div>
          <div class="course-card-bottom">
            <h4>${c.title}</h4>
            <p>${c.description.slice(0,100)}…</p>
            <div class="course-card-actions">
              <a href="/course/${c.id}"       class="btn-demo">
                <i class="fa fa-play-circle"></i>

                📺Live Demo
              </a>
              <a href="/course/${c.id}" class="btn-enroll">
                <i class="fa fa-user-plus"></i>


                📌Enroll Now
              </a>
            </div>
            <a href="/course/${c.id}" class="btn-download">
              <i class="fa fa-download"></i>
              <svg width="30" height="30" viewBox="0 0 59 60" fill="#ffffffff" xmlns="http://www.w3.org/2000/svg">
<path d="M26.5029 6.60362C20.0446 6.60362 14.8093 11.839 14.8093 18.2972C14.8093 18.392 14.8107 18.4909 14.8132 18.5961C14.845 19.9555 13.9352 21.1574 12.6182 21.4958C8.83325 22.4684 6.03916 25.9071 6.03916 29.9907C6.03916 34.8345 9.96577 38.7609 14.8093 38.7609H17.7327C19.3473 38.7609 20.6561 40.0697 20.6561 41.6843C20.6561 43.2989 19.3473 44.6077 17.7327 44.6077H14.8093C6.73671 44.6077 0.192383 38.0634 0.192383 29.9907C0.192383 23.9611 3.84136 18.7896 9.04809 16.554C9.92306 7.68512 17.4037 0.756836 26.5029 0.756836C33.0033 0.756836 38.6727 4.29221 41.7016 9.53648C51.1199 9.8434 58.6602 17.5748 58.6602 27.0673C58.6602 36.7546 50.8074 44.6077 41.1198 44.6077C39.5053 44.6077 38.1965 43.2989 38.1965 41.6843C38.1965 40.0697 39.5053 38.7609 41.1198 38.7609C47.5782 38.7609 52.8134 33.5254 52.8134 27.0673C52.8134 20.6091 47.5782 15.3738 41.1198 15.3738C40.7962 15.3738 40.4767 15.3869 40.1604 15.4124C38.9124 15.5134 37.7386 14.8084 37.2417 13.6592C35.4438 9.50286 31.3087 6.60362 26.5029 6.60362ZM29.4263 24.144C31.0409 24.144 32.3497 25.4528 32.3497 27.0673V49.2436L33.2059 48.3873C34.3475 47.2458 36.1986 47.2458 37.3402 48.3873C38.4818 49.5289 38.4818 51.38 37.3402 52.5216L31.4934 58.3684C30.9453 58.9165 30.2016 59.2246 29.4263 59.2246C28.651 59.2246 27.9073 58.9165 27.3592 58.3684L21.5123 52.5216C20.3707 51.38 20.3707 49.5289 21.5123 48.3873C22.654 47.2458 24.505 47.2458 25.6466 48.3873L26.5029 49.2436V27.0673C26.5029 25.4528 27.8117 24.144 29.4263 24.144Z" fill="white"/>
</svg>

              Download Curriculum
            </a>
          </div>
        </div>`;
      grid.appendChild(div);
    });
  }

  // render pagination
  function renderPager(meta) {
    pager.innerHTML = '';
    if (meta.last_page <= 1) return;

    for (let i = 1; i <= meta.last_page; i++) {
      const b = document.createElement('button');
      b.textContent = i;
      if (i === meta.current_page) b.classList.add('active');
      b.addEventListener('click', () => load(i));
      pager.appendChild(b);
    }
  }

  // simple debounce
  function debounce(fn, delay) {
    let t;
    return (...a) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...a), delay);
    };
  }
});
// In your existing courses.js file, update the course card generation
// Change the "Enroll Now" button to link to the course detail page instead of direct enrollment

function generateCourseCard(course) {
    return `
        <div class="course-card">
            <div class="course-image">
                ${course.image ?
                    `<img src="/storage/${course.image}" alt="${course.title}">` :
                    `<div class="course-placeholder">${course.title.charAt(0)}</div>`
                }
                <div class="course-status ${course.status}">${course.status}</div>
            </div>

            <div class="course-content">
                <h3 class="course-title">${course.title}</h3>
                <p class="course-description">${course.description.substring(0, 100)}...</p>
                <p class="course-instructor">By ${course.instructor || 'Unknown'}</p>

                <div class="course-actions">
                    <button class="btn-demo">🎬 Live Demo</button>
                    <a href="/courses/${course.id}" class="btn-enroll">📚 Enroll Now</a>
                </div>

                <button class="btn-download">☁️ Download Curriculum</button>
            </div>
        </div>
    `;
}

