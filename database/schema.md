# Skema Database — Sistem Monitoring Sanitasi & Penyaluran Air

> **Terakhir diperbarui:** 2026-02-22
> **Framework:** Laravel 11 · **Konsep:** Monitoring evaluasi wilayah terdampak — kondisi sanitasi & distribusi bantuan air bersih

---

## Gambaran Konsep

Sistem ini memantau **wilayah yang terdampak** masalah sanitasi dan ketersediaan air bersih. Petugas dapat:
- Mencatat kondisi fasilitas sanitasi (MCK, IPAL, dsb.) di setiap wilayah.
- Merekam distribusi **bantuan air bersih** yang disalurkan ke wilayah terdampak.
- Membuat laporan hasil inspeksi lapangan secara berkala.

---

## Diagram Relasi

```
users
  └ laporan_kondisi ┐
                         │ (wilayah_id)
wilayahs ┼ sanitasi
                         └ penyaluran_air
```

---

## Detail Tabel

### 1. `users`
Akun pengguna sistem. Dua peran: **admin** dan **petugas** lapangan.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | PK, AI | Primary key |
| `name` | `VARCHAR(255)` | NOT NULL | Nama lengkap |
| `username` | `VARCHAR(255)` | UNIQUE | Username login |
| `email` | `VARCHAR(255)` | UNIQUE | Email |
| `photo_path` | `VARCHAR(255)` | NULLABLE | Foto profil |
| `password` | `VARCHAR(255)` | NOT NULL | Password (hash) |
| `role` | `ENUM` | DEFAULT `petugas` | `admin` / `petugas` |
| `status` | `ENUM` | DEFAULT `active` | `active` / `inactive` |
| `remember_token` | `VARCHAR(100)` | NULLABLE | Token sesi |
| `created_at` | `TIMESTAMP` | NULLABLE | — |
| `updated_at` | `TIMESTAMP` | NULLABLE | — |

> Login menggunakan `username`. Pendaftaran hanya oleh admin.

---

### 2. `wilayahs`
Master data wilayah yang dipantau/terdampak.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | PK, AI | Primary key |
| `nama` | `VARCHAR(255)` | NOT NULL | Nama wilayah / kelurahan |
| `kecamatan` | `VARCHAR(255)` | NULLABLE | Nama kecamatan |
| `status` | `ENUM` | DEFAULT `terdampak` | `terdampak` / `tidak terdampak` |
| `deskripsi` | `TEXT` | NULLABLE | Keterangan kondisi umum wilayah |
| `deleted_at` | `TIMESTAMP` | NULLABLE | Soft delete |
| `created_at` | `TIMESTAMP` | NULLABLE | — |
| `updated_at` | `TIMESTAMP` | NULLABLE | — |

**Relasi:** Satu wilayah → banyak `sanitasi`, `penyaluran_air`, `laporan_kondisi`.

---

### 3. `sanitasi`
Kondisi fasilitas sanitasi di suatu wilayah.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | PK, AI | Primary key |
| `wilayah_id` | `BIGINT UNSIGNED` | FK → `wilayahs.id`, NULLABLE | Wilayah terkait |
| `jenis` | `VARCHAR(255)` | NOT NULL | MCK Umum, Jamban Keluarga, IPAL Komunal, dsb. |
| `status` | `ENUM` | NOT NULL | `baik` / `rusak` / `tidak ada` |
| `lokasi` | `VARCHAR(255)` | NOT NULL | Alamat/titik lokasi fasilitas |
| `keterangan` | `TEXT` | NULLABLE | Catatan kondisi detail |
| `deleted_at` | `TIMESTAMP` | NULLABLE | Soft delete |
| `created_at` | `TIMESTAMP` | NULLABLE | — |
| `updated_at` | `TIMESTAMP` | NULLABLE | — |

---

### 4. `penyaluran_air`
Distribusi bantuan air bersih ke wilayah terdampak.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | PK, AI | Primary key |
| `wilayah_id` | `BIGINT UNSIGNED` | FK → `wilayahs.id`, NULLABLE | Wilayah penerima bantuan |
| `sumber_air` | `VARCHAR(255)` | NOT NULL | PDAM, Tandon, Sumur Bor, dsb. |
| `volume_liter` | `INT` | NULLABLE | Jumlah bantuan air yang disalurkan (liter) |
| `tanggal_distribusi` | `DATE` | NOT NULL | Tanggal penyaluran bantuan |
| `status` | `ENUM` | DEFAULT `belum terdistribusi` | `terdistribusi` / `belum terdistribusi` |
| `keterangan` | `TEXT` | NULLABLE | Catatan tambahan |
| `deleted_at` | `TIMESTAMP` | NULLABLE | Soft delete |
| `created_at` | `TIMESTAMP` | NULLABLE | — |
| `updated_at` | `TIMESTAMP` | NULLABLE | — |

---

### 5. `laporan_kondisi`
Riwayat laporan/inspeksi lapangan oleh petugas. Berfungsi sebagai **audit trail** monitoring berkala.

| Kolom | Tipe | Constraint | Keterangan |
|---|---|---|---|
| `id` | `BIGINT UNSIGNED` | PK, AI | Primary key |
| `wilayah_id` | `BIGINT UNSIGNED` | FK → `wilayahs.id`, CASCADE DELETE | Wilayah yang diinspeksi |
| `petugas_id` | `BIGINT UNSIGNED` | FK → `users.id`, CASCADE DELETE | Petugas yang membuat laporan |
| `tanggal_inspeksi` | `DATE` | NOT NULL | Tanggal kunjungan lapangan |
| `catatan` | `TEXT` | NULLABLE | Hasil temuan / narasi inspeksi |
| `foto_path` | `VARCHAR(255)` | NULLABLE | Dokumentasi foto |
| `created_at` | `TIMESTAMP` | NULLABLE | — |
| `updated_at` | `TIMESTAMP` | NULLABLE | — |

---

## Tabel Sistem Laravel (Built-in)

| Tabel | Fungsi |
|---|---|
| `sessions` | Sesi login pengguna |
| `password_reset_tokens` | Token reset password |
| `cache` / `cache_locks` | Cache sistem |
| `jobs` / `job_batches` / `failed_jobs` | Queue / antrian job |
