# Sistem Perpustakaan Laravel

## Identitas
- Nama: Aghitsna Yashiiva A. A.
- Mata Kuliah: Pemrograman Web 2
- Tugas: Migration, Seeder, Accessor & Scope

---

# Fitur
- Migration tabel kategori
- Seeder kategori buku
- Accessor Buku
- Accessor Anggota
- Scope Query Buku
- Scope Query Anggota

---

# Screenshot

## Migration
![Migration](screenshots/migrate.png)

## Daftar Buku
![Buku](screenshots/buku.png)

## Daftar Anggota
![Anggota](screenshots/dftrangg.png)

## Test Query
![Query](screenshots/query.png)

## Test Accessor Scope
![Accessor](screenshots/accesor.png)

---

# Cara Menjalankan Project

```bash
php artisan migrate
php artisan db:seed --class=KategoriSeeder
php artisan serve
```