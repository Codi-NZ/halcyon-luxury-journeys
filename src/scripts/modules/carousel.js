import Swiper from 'swiper/bundle'
import 'swiper/css/bundle'

new Swiper('.default-carousel', {
    slidesPerView: 1.25,
    spaceBetween: 24,
    loop: true,
    slidesOffsetBefore: 0,
    slidesOffsetAfter: 0,
    breakpoints: {
        768: {
            slidesPerView: 1.5,
            spaceBetween: 48,
        },
        1024: {
            slidesPerView: 2.25,
        },
        1280: {
            slidesPerView: 2.5,
            spaceBetween: 64,
        },
    },
    navigation: {
        prevEl: '.prev-slide',
        nextEl: '.next-slide',
    },
})

new Swiper('.image-carousel', {
    slidesPerView: 1.25,
    spaceBetween: 16,
    loop: true,
    centeredSlides: true,
    breakpoints: {
        768: {
            slidesPerView: 1.5,
        },
    },
    navigation: {
        prevEl: '.prev-slide',
        nextEl: '.next-slide',
    },
})
