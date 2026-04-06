const filters = document.querySelector('[data-filters]')

const getFilterData = (filterElements) => {
    let obj = {},
        checked,
        checkedValues

    filterElements.forEach((target, _index) => {
        const { type, name, value } = target

        if (type === 'radio' || type === 'checkbox') {
            if (!obj[name]) {
                checked = filters.querySelectorAll(`input[name='${name}']:checked`)
                checkedValues = []
                checked.forEach((c) => {
                    checkedValues.push(c.value)
                })

                obj = {
                    ...obj,
                    [name]: checkedValues.join(','),
                }
            }
        } else {
            obj = {
                ...obj,
                [name]: value,
            }
        }
    })
    return obj
}

const obj2QueryString = (obj) => {
    return Object.keys(obj)
        .map((key) => {
            const val = obj[key].split(',')
            if (val.length > 1) {
                return val
                    .map((v) => {
                        return encodeURIComponent(key) + '=' + v
                    })
                    .join('&')
            } else {
                return encodeURIComponent(key) + '=' + val[0]
            }
        })
        .join('&')
}

const _queryString2Obj = (str) => {
    if (!str.length) return {}
    const arr = str.split('&')
    const obj = arr.reduce((o, v) => {
        o[v.split('=')[0]] = v.split('=')[1]
        return o
    }, {})
    return obj
}

const getContent = (filterElements) => {
    const data = getFilterData(filterElements)
    const newQ = obj2QueryString(data)

    const oldListingContainer = document.querySelector('[data-filtered-content]')
    const loader = document.createElement('div')
    loader.setAttribute('class', 'loader')
    if (oldListingContainer) {
        oldListingContainer.prepend(loader)
    } else {
        console.warn('Container for prepend not found!')
    }

    const newLocation = location.origin + location.pathname + '?' + newQ

    fetch(newLocation)
        .then((response) => {
            history.pushState('', '', newLocation)
            return response.text()
        })
        .then((data) => {
            const parser = new DOMParser()
            const htmlResponse = parser.parseFromString(data, 'text/html')
            const newListingContainer =
                htmlResponse.documentElement.querySelector('[data-filtered-content]')
            if (oldListingContainer) {
                oldListingContainer.innerHTML = newListingContainer.innerHTML
            } else {
                console.warn('Container for innerHTML not found!')
            }

            // init_calendars()
        })
        .catch((error) => {
            console.error('Filter fetch failed:', error)
        })
}

const initListeners = (filterElements) => {
    Array.prototype.forEach.call(filterElements, (element) => {
        element.addEventListener(
            'change',
            () => {
                getContent(filterElements)
            },
            false
        )
    })

    document.body.addEventListener('click', function (event) {
        if (event.target.dataset.filtersUpdate !== undefined) {
            getContent(filterElements)
        }
    })

    // Add event listener for button type='submit'
    const submitButtons = document.querySelectorAll("button[type='submit']")
    submitButtons.forEach((button) => {
        button.addEventListener(
            'click',
            (event) => {
                event.preventDefault()
                getContent(filterElements)
            },
            false
        )
    })

    // Add event listener for button type='button'
    const buttonButtons = document.querySelectorAll("button[type='button'].option")
    buttonButtons.forEach((button) => {
        button.addEventListener(
            'click',
            (event) => {
                setTimeout(() => {
                    event.preventDefault()
                    getContent(filterElements)
                }, 200)
            },
            false
        )
    })
}

const initFilters = (filters) => {
    if (!filters) {
        return
    }

    const filterElements = filters.querySelectorAll(`select, input`)

    if (!filterElements.length) {
        return
    }

    getFilterData(filterElements)
    initListeners(filterElements)
}

initFilters(filters)
