# Release Notes for Craft CMS 5

### CHANGES

## 08-02-2026
- **Added** UTM tracking Alpine.js store (`$store.utm`) — reads UTM params from URL, persists to cookie (30 days), exposes reactive values for Formie hidden fields via `x-model="$store.utm.source"` etc. Blitz-safe (client-side only).

## 11-01-2026
- Implemented caching for global site data in SimpleVariable to improve performance and reduce database load. (https://simple-team.atlassian.net/browse/SCSD-25)
