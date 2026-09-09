# Sistem Kontrol Berkas Perkara

## Cara Install (Manual / Lokal)

1. Clone repository ini.
2. Jalankan terminal / cmd, arahkan ke folder root dan ketik `composer install`.
3. Salin `.env.example` ke `.env` dan sesuaikan konfigurasinya.
4. Jalankan migrasi dan seeder:
   ```bash
   vendor/bin/phinx migrate
   vendor/bin/phinx seed:run
   ```

---

## Cara Deploy Menggunakan Docker

Pastikan Docker dan Docker Compose sudah terpasang di sistem.

1. **Sesuaikan Konfigurasi Environment:**
   Buka dan sesuaikan kredensial serta pengaturan di file `.env.docker` jika diperlukan (misal port, akun database, dan koneksi server SIPP).

2. **Build & Jalankan Container:**
   ```bash
   docker compose --env-file .env.docker up -d --build
   ```
   *Catatan: Container akan otomatis menunggu database siap, lalu menjalankan migrasi dan seeding data awal via Phinx.*

3. **Akses Aplikasi:**
   - **Web App:** [http://localhost:8085](http://localhost:8085)
   - **Database MariaDB:** `localhost:3307` (User: `root`, Password sesuai `.env.docker`)

4. **Perintah Berguna:**
   - Melihat log aplikasi:
     ```bash
     docker compose --env-file .env.docker logs -f app
     ```
   - Menghentikan container:
     ```bash
     docker compose --env-file .env.docker stop
     ```
   - Mematikan dan menghapus container:
     ```bash
     docker compose --env-file .env.docker down
     ```

---

#### ps: Lihat todo.md untuk progress pengembangan aplikasi

