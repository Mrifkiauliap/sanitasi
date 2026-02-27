#!/bin/bash

# --- CONFIG ---
PORT="14322"
USER="deploy"
IP="51.79.160.49" # Ganti dengan IP server kamu
DEST="/var/www/sanitasi"
FILE="sanitasi.tar.gz"

echo "📦 Membungkus project..."
# Gunakan flag --no-same-owner --no-same-permissions untuk menghindari error permission di Windows/WSL
tar --no-same-owner --no-same-permissions \
    --exclude='node_modules' \
    --exclude='vendor' \
    --exclude='.git' \
    --exclude='cloudflared' \
    --exclude='.env' \
    --exclude='storage/*' \
    --exclude='public/storage' \
    --exclude='push.sh' \
    --exclude='push.ps1' \
    --exclude='GEMINI.md' \
    --exclude='system_design_plan.md' \
    --exclude='pembelajaran.md' \
    -czvf $FILE .

echo "🚀 Mengirim ke server..."
scp -P $PORT $FILE $USER@$IP:$DEST

echo "🛠️ Menjalankan update di server..."
# Unzip, jalankan deploy script, lalu bersihkan sampah tar
ssh -p $PORT $USER@$IP "cd $DEST && tar -xzvf $FILE && cd deploy && bash deploy.sh && rm ../$FILE"

echo "✨ Update Selesai!"
