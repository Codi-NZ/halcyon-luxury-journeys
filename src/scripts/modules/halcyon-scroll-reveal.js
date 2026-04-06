/**
 * Halcyon Scroll Reveal
 * Watches [data-reveal] elements and adds .revealed when they enter the viewport.
 * Supports data-delay="ms" for staggered animations.
 */
export function initScrollReveal() {
    const els = document.querySelectorAll('[data-reveal]')
    if (!els.length) return

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return
                const el = entry.target
                const delay = parseInt(el.dataset.delay || '0', 10)
                setTimeout(() => {
                    el.classList.add('revealed')
                }, delay)
                observer.unobserve(el)
            })
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    )

    els.forEach((el) => observer.observe(el))
}

document.addEventListener('DOMContentLoaded', initScrollReveal)
