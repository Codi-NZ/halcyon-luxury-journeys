# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Aurora is a Craft CMS 5 starter project by Simple Integrated Marketing. It uses DDEV for local development, Vite for frontend builds, Tailwind CSS for styling, and Alpine.js for JavaScript interactivity.

## Development Commands

All commands run through DDEV (Docker-based development environment).

### Initial Setup
```bash
make install    # Start DDEV, copy .env, install dependencies, generate app-id/security-key
```

### Daily Development
```bash
make dev        # Start DDEV and Vite dev server (includes SVG sprite watcher)
make setup      # Pull latest, update dependencies, run migrations - use when joining existing project
make up         # Apply project config and migrations (ddev exec php craft up)
```

### Database
```bash
ddev import-db --file=aurora-db.sql    # Import database
make dbPull                             # Pull database from staging via Swiff
```

### Production Build
```bash
make prod       # Build assets for production
```

### Maintenance
```bash
make clean      # Remove vendor/ and node_modules/, clear caches
make update     # Run craft update all
```

### Linting & Formatting
```bash
npm run lint          # Check JS for errors (ESLint)
npm run lint:fix      # Auto-fix JS errors
npm run format        # Format JS/CSS files (Prettier)
npm run format:check  # Check formatting without changes
```

## Architecture

### Frontend Stack
- **Vite** - Build tool with hot reload, outputs to `web/dist/`
- **Tailwind CSS 4** - Utility-first CSS
- **Alpine.js** - Lightweight reactive JavaScript (with collapse, focus, intersect plugins)
- **GSAP** - Animation library
- **Swiper** - Carousel/slider
- **Fancybox** - Lightbox gallery

### Source Structure (`src/`)
```
src/
├── index.js           # Vite entry point
├── styles/            # CSS files
│   ├── site.css       # Main stylesheet
│   ├── tailwind-config.css
│   ├── base/          # Base styles
│   ├── components/    # Component styles
│   └── utilities/     # Utility classes
├── scripts/           # JavaScript
│   ├── site.js        # Main JS file
│   ├── modules/       # Feature modules (carousel, filters, google-maps, etc.)
│   └── utils/         # Utility functions
├── icons/             # SVG icons (auto-compiled to sprite.svg)
└── static/            # Static assets copied to dist
```

### Templates (Atomic Design)
Templates use [Atomic Design](https://atomicdesign.bradfrost.com/) methodology:
```
templates/
├── _atoms/        # Small reusable components
├── _molecules/    # Component groups
├── _organisms/    # Complex sections
├── _exceptions/   # Error pages
├── blocks/        # CMS content blocks
├── layouts/       # Page layouts
├── macros/        # Twig macros
└── sections/      # Section-specific templates
```

### Custom Module (`modules/simpleModule/`)
A Yii-based module providing:
- `craft.simple` - Global site data with caching
- `headingTag`, `headingTag1-6` - Twig globals for semantic heading levels (auto-increment or force specific level)
- `craft.heading` - State storage used by `macros/headingState.twig` for advanced heading control (push/pop, scoped, reset)
- News service for content handling
- CP protection against config changes when pending project config exists

**Heading system usage:**
```twig
{# Simple: use globals (auto-tracks open/close pairs) #}
<{{ headingTag1 }}>Page Title</{{ headingTag1 }}>
<{{ headingTag }}>Next Heading</{{ headingTag }}>

{# Advanced: use macros for scoped/reset behavior #}
{% import 'macros/headingState' as headingState %}
{% set tag = headingState.resolveHeading(2) %}
```

### Key Configuration
- `/config/general.php` - Craft general config
- `/config/project/` - Craft project config (YAML files for fields, sections, etc.)
- `/vite.config.js` - Vite build configuration
- `/.ddev/config.yaml` - DDEV environment (PHP 8.2, MySQL 8.0, Node 20)

## SVG Icons

Place SVG files in `src/icons/`. The dev server watches this directory and auto-compiles them into `src/sprite.svg`. Use in templates via the SVG sprite.

## Changelog Guidelines

Update `CHANGELOG.md` using these prefixes:
- **Added** - New features, modules, functionality
- **Changed** - Updates to existing behavior, templates, styles
- **Removed** - Deprecated or removed files/features
- **Fixed** - Bug fixes
- **Devops** - Backend/deployment/config updates, CMS version bumps

## Commit Messages

Use the `/commit` skill to generate Conventional Commit messages for this project.
