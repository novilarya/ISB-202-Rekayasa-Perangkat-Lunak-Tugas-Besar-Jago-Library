document.addEventListener("DOMContentLoaded", () => {
    let currentIndex = 0;
    const books = document.querySelectorAll('.book-card');
    const container = document.querySelector('.book-slider');

    function showSlide(index) {
        const cardWidth = books[0].offsetWidth + 40;
        const scrollPosition = index * cardWidth;

        container.scrollTo({
            left: scrollPosition,
            behavior: 'smooth'
        });
    }

    function prevSlide() {
        currentIndex--;
        if (currentIndex < 0) currentIndex = books.length - 1;
        showSlide(currentIndex);
    }

    function nextSlide() {
        currentIndex++;
        if (currentIndex >= books.length) currentIndex = 0;
        showSlide(currentIndex);
    }

    showSlide(currentIndex);

    document.querySelector(".left-btn").addEventListener("click", prevSlide);
    document.querySelector(".right-btn").addEventListener("click", nextSlide);
});
