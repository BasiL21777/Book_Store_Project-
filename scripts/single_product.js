let currentSlide = 0;
const slides = document.querySelectorAll('.slide');
const totalSlides = slides.length;

document.querySelector('.prev').addEventListener('click', () => {
    changeSlide(-1);
});

document.querySelector('.next').addEventListener('click', () => {
    changeSlide(1);
});

function showSlide(index) {
    slides.forEach(slide => {
        slide.classList.remove('active');
    });
    slides[index].classList.add('active');
}

function changeSlide(step) {
    currentSlide += step;
    if (currentSlide >= totalSlides) {
        currentSlide = 0;
    } else if (currentSlide < 0) {
        currentSlide = totalSlides - 1;
    }
    showSlide(currentSlide);
}

showSlide(currentSlide); // Initialize the slider by showing the first slide
