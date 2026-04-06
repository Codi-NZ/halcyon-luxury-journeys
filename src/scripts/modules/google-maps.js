import { Loader } from '@googlemaps/js-api-loader'

const additionalOptions = {
    libraries: ['marker'],
}

const gmapsLoader = new Loader({
    apiKey: window.gMapsPublicApiKey,
    version: 'weekly',
    ...additionalOptions,
})

/**
 * initMap
 *
 * Renders a Google Map onto the selected element
 *
 * This is our traditional map renderer, using the Google Maps JS API and element data attributes.
 * We can instead use the new Google Maps Web Component, which is more up-to-date and easier to use.
 * (see https://developers.google.com/maps/documentation/javascript/add-google-map#gmp-map-element)
 * This is likely legacy code and will be removed in the future.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   HTMLElement el The HTML element.
 * @param   object mapArgs optional map arguments/overrides
 * @return  object The map instance.
 */
const initMap = (el, mapArgs = {}) => {
    const zoom = parseInt(el.dataset.zoom) || 16
    const center =
        el.dataset.centerLat && el.dataset.centerLng
            ? { lat: parseFloat(el.dataset.centerLat), lng: parseFloat(el.dataset.centerLng) }
            : { lat: -34.397, lng: 150.644 }
    const mapId = el.dataset.mapId || null
    const markers = JSON.parse(el.dataset.markers || '[]')

    // merge default map args with any passed in
    mapArgs = {
        zoom: zoom,
        center: center,
        mapId: mapId,
        ...mapArgs,
    }
    const map = new google.maps.Map(el, mapArgs)

    // Add markers if they exist
    markers.forEach((markerSettings) => {
        // if markerSettings is an object and has at least the position property, then it's valid
        if (typeof markerSettings === 'object' && markerSettings.position) {
            // cast each item in the position property to a float
            markerSettings.position = {
                lat: parseFloat(markerSettings.position.lat),
                lng: parseFloat(markerSettings.position.lng),
            }
            initMarker(markerSettings, map)
        }
    })

    // Center map based on markers.
    // _centerMap(map)

    // Return map instance.
    return map
}

/**
 * initMarker
 *
 * Creates a marker for the given HTML element and map.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   object markerSettings The marker settings.
 * @param   object map The map instance.
 * @return  object The marker instance.
 */
const initMarker = (markerSettings, map) => {
    // merge default settings with any passed in
    const theSettings = {
        map: map,
        // icon: "/dist/images/map-marker.svg",
        ...markerSettings,
    }

    // Create marker instance on the map.
    const marker = new google.maps.Marker(theSettings)

    // If marker contains HTML, add it to an infoWindow.
    if (theSettings.content) {
        // Create info window.
        const infowindow = new google.maps.InfoWindow({
            content: theSettings.content,
        })

        // Show info window when marker is clicked.
        marker.addListener('click', () => {
            infowindow.open(map, marker)
        })
    }
}

/**
 * centerMap
 *
 * Centers the map showing all markers in view.
 *
 * @date    22/10/19
 * @since   5.8.6
 *
 * @param   object The map instance.
 * @return  void
 */
const _centerMap = (map) => {
    // Create map boundaries from all map markers.
    const bounds = new google.maps.LatLngBounds()
    map.markers.forEach((marker) => {
        bounds.extend({
            lat: marker.getPosition().lat(),
            lng: marker.getPosition().lng(),
        })
    })

    // Case: Single marker.
    if (map.markers.length === 1) {
        map.setCenter(bounds.getCenter())
        // Case: Multiple markers.
    } else {
        map.fitBounds(bounds)
    }
}

// GO Time for maps
gmapsLoader.load().then(async () => {
    await google.maps.importLibrary('maps')

    // Render maps where found, via our traditional page element lookup.
    document.querySelectorAll('.g-map').forEach((el) => {
        if (!el) return
        initMap(el)
    })
})
