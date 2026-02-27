# --- CONFIG ---
$PORT = "14322"
$USER = "deploy"
$IP = "51.79.160.49"
$DEST = "/var/www/sanitasi"
$FILE = "sanitasi.tar.gz"

Write-Host "📦 Membungkus project..." -ForegroundColor Cyan
# PowerShell support tar. Exclude sensitive config, heavy folders, and local tools.
tar --no-same-owner --no-same-permissions `
    --exclude='node_modules' `
    --exclude='vendor' `
    --exclude='.git' `
    --exclude='cloudflared' `
    --exclude='.env' `
    --exclude='storage/*' `
    --exclude='public/storage' `
    --exclude='push.sh' `
    --exclude='push.ps1' `
    --exclude='GEMINI.md' `
    --exclude='system_design_plan.md' `
    --exclude='pembelajaran.md' `
    -czvf $FILE .

Write-Host "🚀 Mengirim ke server..." -ForegroundColor Cyan
scp -P $PORT $FILE "$USER@$($IP):$DEST"

Write-Host "🛠️ Menjalankan update di server..." -ForegroundColor Cyan
# Jalankan remote command via SSH
ssh -p $PORT "$USER@$($IP)" "cd $DEST && tar -xzvf $FILE && cd deploy && bash deploy.sh && rm ../$FILE"

Write-Host "✨ Update Selesai!" -ForegroundColor Green

# Bersihkan file lokal setelah kirim (opsional)
# Remove-Item $FILE
