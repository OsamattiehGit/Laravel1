document.addEventListener('DOMContentLoaded', () => {
    const el = document.querySelector('#aiPeek.gallery');
    if (!el) return;
    
    // Make sure we don't init twice on re-renders
    if (el._flickity) { 
        el._flickity.destroy(); 
    }

    el._flickity = new Flickity(el, {
        cellAlign: 'left',
        contain: true, // Contain slides to prevent overflow
        wrapAround: false, // Disable wrap around to prevent sliding rest to left
        autoPlay: 3000,               // 3s
        pauseAutoPlayOnHover: true,
        prevNextButtons: false,
        pageDots: true,
        draggable: '>1',              // drag only when more than 1 cell
        selectedAttraction: 0.03,     // smooth
        friction: 0.28,
        adaptiveHeight: false,
        percentPosition: false, // Use pixel positioning for more precise control
        setGallerySize: true
    });
    
    // Force a resize to ensure proper positioning
    setTimeout(() => {
        el._flickity.resize();
    }, 100);
    
    // Handle reaching the last slide
    el._flickity.on('change', function(index) {
        const slides = el._flickity.slides;
        if (index === slides.length - 1) {
            // Stop autoplay when reaching the last slide
            el._flickity.stopPlayer();
        }
    });
});