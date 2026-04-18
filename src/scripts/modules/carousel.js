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

document.querySelectorAll('.image-carousel').forEach((carousel) => {
    const container = carousel.parentElement
    const slideCount =
        parseInt(carousel.getAttribute('data-slide-count') || '0', 10) ||
        carousel.querySelectorAll('.swiper-slide').length

    const prevEl = container?.querySelector('.prev-slide')
    const nextEl = container?.querySelector('.next-slide')

    new Swiper(carousel, {
        slidesPerView: 1,
        spaceBetween: 16,
        centeredSlides: true,
        rewind: slideCount > 1,
        watchOverflow: true,
        navigation:
            prevEl && nextEl
                ? {
                      prevEl,
                      nextEl,
                  }
                : undefined,
    })
})
