/**
 * Get all parents by selector
 * @param {Element} el
 * @param {String} selector
 */
const elGetParents = (el, selector) => {
    const parentEls = []
    for (; el; el = el.parentNode) {
        if (el.matches && el.matches(selector)) {
            parentEls.push(el)
        }
    }
    return parentEls
}

/**
 * Get all siblings
 * @param {Element} el
 */
const elGetSiblings = (el) => {
    return Array.from(el.parentNode.children).filter((sibling) => {
        return sibling !== el
    })
}

/**
 * Get element offset
 * @param {Element} el
 */
const elGetOffset = (el) => {
    const rect = el.getBoundingClientRect()
    return {
        left: rect.left + window.scrollX,
        top: rect.top + window.scrollY,
    }
}

/**
 * Set CSS
 * @param {Element} el
 * @param {Object} properties
 */
const elSetCSS = (el, properties) => {
    for (const [key, value] of Object.entries(properties)) {
        el.style.setProperty(key, value)
    }
}

/**
 * Toggle classes
 * @param {Element} el
 * @param {Object} classes
 */
const elToggleClasses = (el, classes) => {
    for (const [key, value] of Object.entries(classes)) {
        el.classList.toggle(key, value)
    }
}

export { elGetParents, elGetSiblings, elGetOffset, elSetCSS, elToggleClasses }
