import Alpine from 'alpinejs'
import Cookies from 'js-cookie'

const COOKIE_NAME = 'utm_params'
const COOKIE_DAYS = 30
const UTM_KEYS = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content']

function buildUtmString(data) {
    return UTM_KEYS.filter((k) => data[k])
        .map((k) => data[k])
        .join(' / ')
}

// Read existing cookie
let stored = {}
try {
    stored = JSON.parse(Cookies.get(COOKIE_NAME) || '{}')
} catch (e) {
    stored = {}
}

// Merge any new URL params (first-touch preserved, new params override)
const params = new URLSearchParams(window.location.search)
let hasNew = false

UTM_KEYS.forEach((key) => {
    const val = params.get(key)
    if (val) {
        stored[key] = val
        hasNew = true
    }
})

if (hasNew) {
    stored.utm_string = buildUtmString(stored)
    Cookies.set(COOKIE_NAME, JSON.stringify(stored), {
        expires: COOKIE_DAYS,
        path: '/',
        sameSite: 'Lax',
    })
}

// Register Alpine store — available as $store.utm in templates
Alpine.store('utm', {
    source: stored.utm_source || '',
    medium: stored.utm_medium || '',
    campaign: stored.utm_campaign || '',
    term: stored.utm_term || '',
    content: stored.utm_content || '',
    string: stored.utm_string || '',

    get hasData() {
        return !!(this.source || this.medium || this.campaign || this.term || this.content)
    },
})
