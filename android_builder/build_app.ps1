# build_app.ps1
# Automates the migration and build process for the Android app

Write-Host "--- Starting Build Process ---" -ForegroundColor Cyan

# 1. Run Migration
Write-Host "[1/3] Migrating templates from root to public/..." -ForegroundColor Yellow
python migrate.py
if ($LASTEXITCODE -ne 0) {
    Write-Host "Migration failed!" -ForegroundColor Red
    exit $LASTEXITCODE
}

# 2. Vite Build
Write-Host "[2/3] Building Vite project (generating dist/)..." -ForegroundColor Yellow
npm run build
if ($LASTEXITCODE -ne 0) {
    Write-Host "Vite build failed!" -ForegroundColor Red
    exit $LASTEXITCODE
}

# 3. Capacitor Sync
Write-Host "[3/3] Syncing with Capacitor Android project..." -ForegroundColor Yellow
npx cap sync android
if ($LASTEXITCODE -ne 0) {
    Write-Host "Capacitor sync failed!" -ForegroundColor Red
    exit $LASTEXITCODE
}

Write-Host "--- Build Complete! ---" -ForegroundColor Green
Write-Host "You can now open the project in Android Studio and run it." -ForegroundColor White
