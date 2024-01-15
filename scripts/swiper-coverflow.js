function initializeSwiper() {
    var swiper = new Swiper(".swiper-coverflow", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        slidesPerView: "3",
        loop: true,
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: false,
        },
        pagination: {
            el: ".swiper-pagination",
        },
        breakpoints: {
            320: {
                slidesPerView: "1",
            },
            640: {
                slidesPerView: "1",
            },
            760: {
                slidesPerView: "2",
            },
            1024: {
                slidesPerView: "3",
            },
        }
    });

    var slideIndex = 2;
    swiper.slideTo(slideIndex);
}

document.addEventListener("DOMContentLoaded", initializeSwiper);