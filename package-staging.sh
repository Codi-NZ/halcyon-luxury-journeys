#!/bin/bash
# ─── Package for Staging Deploy ───────────────────────────────────
# Builds vendor + frontend assets locally and zips everything the
# server needs so no composer install / npm build / project-config
# apply is required on the server.
#
# After upload + extract on staging, apply pending project config
# and migrations from Craft CP → Utilities.

set -e

TIMESTAMP=$(date +"%Y-%m-%d-%H%M%S")
FILENAME="staging-deploy-${TIMESTAMP}.zip"

echo "📦 Packaging for staging..."

# 1) Install production PHP deps (no dev, optimized autoloader)
echo "→ Installing composer deps (--no-dev)..."
ddev composer install --no-dev --optimize-autoloader --no-interaction

# 2) Build frontend assets → web/dist/
echo "→ Building frontend assets..."
ddev exec npm run build

# 3) Create the deploy zip
echo "→ Creating zip..."
rm -f "$FILENAME"
zip -r "$FILENAME" \
  config \
  modules \
  src \
  templates \
  vendor \
  web \
  bootstrap.php \
  composer.json \
  composer.lock \
  craft \
  package-lock.json \
  package.json \
  phpcs.xml \
  README.md \
  svg.js \
  -x "web/cpresources/*" \
  -x "web/index.php.bak" \
  -x "*.DS_Store" \
  -x ".env" \
  -x ".env.*" \
  -x ".git/*" \
  -x "node_modules/*" \
  -x "storage/*" \
  > /dev/null

# 4) Restore dev deps so local work isn't broken
echo "→ Restoring local dev deps..."
ddev composer install --no-interaction > /dev/null

SIZE=$(du -h "$FILENAME" | cut -f1)
echo ""
echo "✅ Created: $FILENAME ($SIZE)"
echo ""
echo "Next steps:"
echo "  1. Upload $FILENAME via hosting file manager & extract on staging"
echo "  2. Craft CP → Utilities → Project Config → Apply Yaml Changes"
echo "  3. Craft CP → Utilities → Migrations → Run pending migrations"
echo "  4. Craft CP → Utilities → Caches → Clear all caches"
