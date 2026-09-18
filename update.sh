#!/bin/bash
set -e

echo "========================================================"
echo "           Sistem Kontrol Berkas - Update Script        "
echo "========================================================"

# 1. Ambil update terbaru dari repository
echo "[1/3] Mengambil pembaruan terbaru (git pull)..."
git pull

# 2. Periksa apakah ada perubahan pada dependencies (composer.json / composer.lock)
echo "[2/3] Memeriksa dependensi composer..."
if git diff --name-only HEAD@{1} HEAD 2>/dev/null | grep -qE "composer\.(json|lock)"; then
    echo "       -> Terdeteksi perubahan composer. Menjalankan composer install di container..."
    docker compose exec app composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
else
    echo "       -> Tidak ada perubahan dependensi composer."
fi

# 3. Jalankan migrasi database jika ada file migrasi baru
echo "[3/3] Menjalankan migrasi database jika ada..."
docker compose exec app vendor/bin/phinx migrate -e development || true

echo "========================================================"
echo "  Pembaruan Berhasil Diimplementasikan (Tanpa Rebuild)  "
echo "========================================================"
