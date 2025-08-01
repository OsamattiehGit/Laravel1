document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll(".carousel-slide");
    const dots = document.querySelectorAll(".carousel-dots .dot");
    let index = 0;

    function showSlide(i) {
        slides.forEach((slide, idx) => {
            slide.style.display = idx === i ? "block" : "none";
            dots[idx].classList.toggle("active", idx === i);
        });
    }

    function nextSlide() {
        index = (index + 1) % slides.length;
        showSlide(index);
    }

    showSlide(index);
    setInterval(nextSlide, 4000);
});
