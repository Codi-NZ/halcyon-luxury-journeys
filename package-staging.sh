#!/bin/bash
# ─── Package for Staging Deploy ───────────────────────────────────
# Zips all required files/folders for manual staging push.
# Output: staging-deploy-YYYY-MM-DD-HHMMSS.zip in the project root.

TIMESTAMP=$(date +"%Y-%m-%d-%H%M%S")
FILENAME="staging-deploy-${TIMESTAMP}.zip"

echo "📦 Packaging for staging..."

zip -r "$FILENAME" \
  config \
  modules \
  node_modules \
  src \
  storage \
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
  -x "storage/logs/*" \
  -x "storage/runtime/*" \
  -x "web/cpresources/*" \
  -x "web/dist/*" \
  -x "*.DS_Store" \
  > /dev/null 2>&1

SIZE=$(du -h "$FILENAME" | cut -f1)
echo "✅ Created: $FILENAME ($SIZE)"
