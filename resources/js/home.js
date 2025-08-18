document.addEventListener('DOMContentLoaded', () => {
    const el = document.querySelector('#aiPeek.gallery');
    if (!el) return;
    
    // Destroy existing instance if any
    if (el._flickity) { 
        el._flickity.destroy(); 
    }

    // Initialize Flickity with settings that match Figma design
    el._flickity = new Flickity(el, {
        cellAlign: 'left',
        contain: false, // Allow overflow for peek effect
        wrapAround: true, // Enable cycling through slides
        autoPlay: 4000, // 4 seconds per slide
        pauseAutoPlayOnHover: true,
        prevNextButtons: false, // No arrow buttons
        pageDots: true, // Show navigation dots
        draggable: true, // Allow dragging
        selectedAttraction: 0.025, // Smooth transitions
        friction: 0.28,
        adaptiveHeight: false,
        percentPosition: false,
        setGallerySize: true,
        // Custom settings for peek effect
        cellSelector: '.gallery-cell',
        // Ensure proper slide width for peek effect
        on: {
            ready: function() {
                // Set up peek effect after initialization
                setupPeekEffect();
            }
        }
    });
    
    // Function to set up the peek effect
    function setupPeekEffect() {
        const cells = el.querySelectorAll('.gallery-cell');
        const containerWidth = el.offsetWidth;
        
        // Responsive peek amounts
        let peekAmount;
        if (containerWidth >= 1200) {
            peekAmount = 120; // Desktop
        } else if (containerWidth >= 768) {
            peekAmount = 80; // Tablet
        } else if (containerWidth >= 576) {
            peekAmount = 60; // Mobile
        } else {
            peekAmount = 40; // Small mobile
        }
        
        cells.forEach(cell => {
            cell.style.width = `calc(100% - ${peekAmount}px)`;
            cell.style.marginRight = '0';
        });
        
        // Force Flickity to recalculate
        el._flickity.resize();
    }
    
    // Handle window resize
    window.addEventListener('resize', () => {
        if (el._flickity) {
            setupPeekEffect();
        }
    });
    
    // Handle slide change events
    el._flickity.on('change', function(index) {
        // Optional: Add any slide change logic here
        console.log('Slide changed to:', index);
    });
    
    // Handle reaching the last slide (for wrap-around behavior)
    el._flickity.on('settle', function(index) {
        // This ensures smooth cycling through all slides
        if (index === el._flickity.slides.length - 1) {
            // If we're at the last slide, the next auto-play will go to first slide
            // due to wrapAround: true
        }
    });
});