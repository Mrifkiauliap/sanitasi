#!/bin/bash

# ==================================================
# SANITASI PRODUCTION DEPLOY SCRIPT (SAFE MODE)
# - Zero data loss (volumes protected)
# - Auto image cleanup
# - Non-interactive (CI/CD safe)
# ==================================================

set -euo pipefail

APP_NAME="sanitasi"
export COMPOSE_PROJECT_NAME=$APP_NAME
SERVICE_APP="app"
SERVICE_DB="db"

echo "=================================================="
echo "🚀 DEPLOYING PROJECT: $APP_NAME"
echo "=================================================="

# --------------------------------------------------
# 1. PRE-DEPLOY CHECKS
# --------------------------------------------------
echo "🔍 Running Pre-deploy Checks..."

# Docker
command -v docker >/dev/null || { echo "❌ Docker not installed"; exit 1; }

# Docker Compose
docker compose version >/dev/null || { echo "❌ Docker Compose missing"; exit 1; }

# .env (checks parent directory)
[ -f ../.env ] || { echo "❌ .env file missing in project root (../.env)"; exit 1; }

# Cloudflared (optional)
if [ ! -f cloudflared/config.yml ]; then
  echo "⚠️  Cloudflared config not found (non-blocking)"
fi

# Disk space
FREE_SPACE=$(df -h . | awk 'NR==2 {print $4}')
echo "💾 Free disk space: $FREE_SPACE"

echo "✅ Pre-deploy checks OK"
echo "--------------------------------------------------"

# --------------------------------------------------
# 2. BUILD & DEPLOY
# --------------------------------------------------
echo "📦 Building & starting containers..."
docker compose up -d --build
echo "✅ Containers started"

# --------------------------------------------------
# 3. WAIT FOR CONTAINERS
# --------------------------------------------------
echo "⏳ Waiting for containers to be ready..."

# Wait for DB to be healthy (MySQL specific)
echo "🗄️  Waiting for Database service..."
DB_ATTEMPTS=0
MAX_DB_ATTEMPTS=30
until docker compose exec -T $SERVICE_DB mysqladmin ping -h 127.0.0.1 -u sanitasi_user -pXXXX --silent >/dev/null 2>&1; do
  DB_ATTEMPTS=$((DB_ATTEMPTS+1))
  if [ $DB_ATTEMPTS -ge $MAX_DB_ATTEMPTS ]; then
    echo "❌ Database failed to become ready"
    docker compose logs $SERVICE_DB
    exit 1
  fi
  echo -n "."
  sleep 2
done
echo -e "\n✅ Database is ready"
sleep 5 # Jeda tambahan agar MySQL benar-benar stabil

# Wait for App
echo "🌐 Waiting for App container..."
APP_ATTEMPTS=0
MAX_APP_ATTEMPTS=30 # Increased to 60s (migrations + seeding take time)
until docker compose ps $SERVICE_APP --format "{{.Status}}" 2>/dev/null | grep -iqE "running|up"; do
  APP_ATTEMPTS=$((APP_ATTEMPTS+1))
  if [ $APP_ATTEMPTS -ge $MAX_APP_ATTEMPTS ]; then
    echo -e "\n❌ App container failed to start within ${MAX_APP_ATTEMPTS} attempts"
    docker compose logs --tail=50 $SERVICE_APP
    exit 1
  fi
  echo -n "."
  sleep 2
done
echo -e "\n✅ App container is running"

# --------------------------------------------------
# 4. POST-DEPLOY TASKS
# --------------------------------------------------

# APP KEY (checks parent directory)
if ! grep -q "APP_KEY=base64:" ../.env; then
  echo "🔑 Generating APP_KEY..."
  docker compose exec -T $SERVICE_APP php artisan key:generate
else
  echo "✅ APP_KEY exists"
fi

# Storage permission
echo "🔐 Fixing storage permissions..."
docker compose exec -T $SERVICE_APP \
  chown -R www-data:www-data storage bootstrap/cache

# Database check (Cek status migrasi yang dijalankan di entrypoint)
echo "🚀 Checking migration status..."
docker compose exec -T $SERVICE_APP php artisan migrate:status

# Clear Caches (Ensures new features/configs are loaded)
echo "🧹 Clearing Laravel caches..."
docker compose exec -T $SERVICE_APP php artisan cache:clear
docker compose exec -T $SERVICE_APP php artisan view:clear
docker compose exec -T $SERVICE_APP php artisan config:clear
docker compose exec -T $SERVICE_APP php artisan route:clear

echo "✅ Deployment tasks completed"

# --------------------------------------------------
# 5. HEALTH LOG CHECK
# --------------------------------------------------
echo "📝 Checking recent logs for errors..."
# Use if/else to avoid set -e termination if no errors found
if docker compose logs --tail=50 $SERVICE_APP | grep -iE "error|exception|fatal"; then
  echo "⚠️ Potential issues detected in logs"
else
  echo "✅ No critical errors found in recent logs"
fi

# --------------------------------------------------
# 6. SAFE CLEANUP (NO VOLUMES TOUCHED)
# --------------------------------------------------
echo "🧹 Cleaning unused Docker artifacts (SAFE)..."
docker image prune -f
docker builder prune -f
echo "✅ Cleanup done (Images and Builder Cache)"

# --------------------------------------------------
# 7. FINAL STATUS
# --------------------------------------------------
echo "--------------------------------------------------"
docker compose ps
docker volume ls
echo "--------------------------------------------------"
echo "✨ DEPLOYMENT SUCCESSFUL ✨"
echo "🚫 Volumes untouched"
echo "🚫 No data loss possible"
echo "=================================================="
