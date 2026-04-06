// Vendor imports
import '@fancyapps/ui'
import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'
import focus from '@alpinejs/focus'
import intersect from '@alpinejs/intersect'
import breakpoint from 'alpinejs-breakpoints'
Alpine.plugin(intersect)
Alpine.plugin(breakpoint)
Alpine.plugin(collapse)
Alpine.plugin(focus)
window.Alpine = Alpine
window.AlpineBreakpointPluginBreakpointsList = [
    'xs',
    'sm',
    'md',
    'lg',
    'xl',
    '2xl',
    '3xl',
    'max',
    'wide',
]

// Stores (must register before Alpine.start)
import './modules/utm-tracking'

window.Alpine.start()

import 'lazysizes/plugins/respimg/ls.respimg'
import 'lazysizes/plugins/parent-fit/ls.parent-fit'
import 'lazysizes'
import 'lazysizes/plugins/object-fit/ls.object-fit'

// Module imports
import './modules/carousel'
import './modules/fancyapps'
import './modules/filters'
import './modules/svgIconSprite'
import './modules/video-fit'
import './modules/halcyon-scroll-reveal'

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        window.dispatchEvent(new Event('resize'))
    }, 100)
})
