document.addEventListener('DOMContentLoaded', () => {
    // Initialize carousel
    initializeCarousel();
    
    // Initialize live search
    initializeLiveSearch();
});

function initializeCarousel() {
    const container = document.querySelector('.carousel-container');
    const track     = container?.querySelector('.carousel-track');
    const slides    = track ? Array.from(track.children) : [];
    const dots      = container ? Array.from(container.querySelectorAll('.carousel-dot')) : [];
    if (!container || !track || slides.length === 0) return;
  
    let index = 0, anim = false, timer;
  
    const gapPx  = () => parseFloat(getComputedStyle(track).gap || 0) || 0;
    const stepPx = () => slides[0].getBoundingClientRect().width + gapPx();
    const peekPx = () => parseFloat(getComputedStyle(container).getPropertyValue('--peek')) || 0;
  
    const offsetFor = (i) => {
      // base distance
      let px = i * stepPx();
      // avoid sub-pixel gaps
      return Math.round(px);
    };
  
    function go(to){
      if (anim) return;
      index = (to + slides.length) % slides.length;
      anim = true;
      track.style.transform = `translateX(${-offsetFor(index)}px)`;
      dots.forEach((d,i)=>d.classList.toggle('active', i===index));
      setTimeout(()=>anim=false, 600);
    }
  
    const next = () => go(index + 1);
    const prev = () => go(index - 1);
  
    dots.forEach((dot,i)=>dot.addEventListener('click',()=>go(i)));
  
    // touch / drag
    let startX=0, dragging=false;
    container.addEventListener('touchstart', e=>{ startX=e.touches[0].clientX; stop(); }, {passive:true});
    container.addEventListener('touchend',   e=>{
      const dx = startX - e.changedTouches[0].clientX;
      if (Math.abs(dx)>50) (dx>0?next():prev());
      start();
    }, {passive:true});
    container.addEventListener('mousedown', e=>{ dragging=true; startX=e.clientX; stop(); container.style.cursor='grabbing'; });
    document.addEventListener('mouseup', e=>{
      if (!dragging) return; dragging=false; container.style.cursor='grab';
      const dx = startX - e.clientX;
      if (Math.abs(dx)>50) (dx>0?next():prev());
      start();
    });
  
    function start(){ if (!timer) timer = setInterval(next, 2000); }
    function stop(){ if (timer){ clearInterval(timer); timer=null; } }
  
    container.addEventListener('mouseenter', stop);
    container.addEventListener('mouseleave', start);
  
    // keep alignment on resize
    window.addEventListener('resize', () => {
      track.style.transition = 'none';
      track.style.transform  = `translateX(${-offsetFor(index)}px)`;
      requestAnimationFrame(()=> track.style.transition = 'transform .6s ease');
    }, {passive:true});
  
    // init
    container.style.cursor='grab';
    track.style.transform='translateX(0)';
    dots.forEach((d,i)=>d.classList.toggle('active', i===0));
    start();
}

function initializeLiveSearch() {
    const searchInput = document.getElementById('homepage-search-input');
    const searchForm = document.getElementById('homepage-search-form');
    const suggestionsContainer = document.getElementById('search-suggestions');
    const suggestionsList = suggestionsContainer?.querySelector('.suggestions-list');
    
    if (!searchInput || !suggestionsContainer || !suggestionsList) return;

    let searchTimeout;
    let selectedIndex = -1;

    // Live search with debouncing
    searchInput.addEventListener('input', debounce((e) => {
        const query = e.target.value.trim();
        selectedIndex = -1;
        
        if (query.length < 2) {
            hideSuggestions();
            return;
        }
        
        performLiveSearch(query);
    }, 300));

    // Keyboard navigation
    searchInput.addEventListener('keydown', (e) => {
        const suggestions = suggestionsList.querySelectorAll('.suggestion-item');
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, suggestions.length - 1);
                updateSelection(suggestions);
                break;
            case 'ArrowUp':
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(suggestions);
                break;
            case 'Enter':
                if (selectedIndex >= 0 && suggestions[selectedIndex]) {
                    e.preventDefault();
                    suggestions[selectedIndex].click();
                }
                break;
            case 'Escape':
                hideSuggestions();
                searchInput.blur();
                break;
        }
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
            hideSuggestions();
        }
    });

    // Prevent form submission if suggestions are visible
    searchForm.addEventListener('submit', (e) => {
        if (suggestionsContainer.style.display !== 'none') {
            const suggestions = suggestionsList.querySelectorAll('.suggestion-item');
            if (suggestions.length > 0 && selectedIndex >= 0) {
                e.preventDefault();
                suggestions[selectedIndex].click();
            }
        }
    });

    async function performLiveSearch(query) {
        try {
            const result = await AjaxRequest.get(`/api/search-courses?q=${encodeURIComponent(query)}`);
            
            if (result.success) {
                displaySuggestions(result.data, query);
            } else {
                console.error('Search failed:', result.error);
                hideSuggestions();
            }
        } catch (error) {
            console.error('Search error:', error);
            hideSuggestions();
        }
    }

    function displaySuggestions(courses, query) {
        if (!courses || courses.length === 0) {
            suggestionsList.innerHTML = '<div class="no-suggestions">No courses found matching "' + query + '"</div>';
            showSuggestions();
            return;
        }

        suggestionsList.innerHTML = courses.map((course, index) => `
            <div class="suggestion-item" onclick="selectCourse('${course.id}', '${escapeHtml(course.title)}')" data-index="${index}">
                <div class="suggestion-icon">📚</div>
                <div class="suggestion-content">
                    <div class="suggestion-title">${highlightText(course.title, query)}</div>
                    <div class="suggestion-category">${course.category?.name || 'Course'}</div>
                </div>
            </div>
        `).join('');

        showSuggestions();
    }

    function updateSelection(suggestions) {
        suggestions.forEach((item, index) => {
            item.classList.toggle('selected', index === selectedIndex);
        });
        
        if (selectedIndex >= 0 && suggestions[selectedIndex]) {
            suggestions[selectedIndex].scrollIntoView({ block: 'nearest' });
        }
    }

    function showSuggestions() {
        suggestionsContainer.style.display = 'block';
    }

    function hideSuggestions() {
        suggestionsContainer.style.display = 'none';
        selectedIndex = -1;
    }

    function highlightText(text, query) {
        if (!query) return escapeHtml(text);
        
        const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
        return escapeHtml(text).replace(regex, '<strong>$1</strong>');
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function escapeRegex(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
}

// Global function to handle course selection
window.selectCourse = function(courseId, courseTitle) {
    const searchInput = document.getElementById('homepage-search-input');
    const suggestionsContainer = document.getElementById('search-suggestions');
    
    // Fill the search input
    if (searchInput) {
        searchInput.value = courseTitle;
    }
    
    // Hide suggestions
    if (suggestionsContainer) {
        suggestionsContainer.style.display = 'none';
    }
    
    // Redirect to course page
    window.location.href = `/course/${courseId}`;
};
  