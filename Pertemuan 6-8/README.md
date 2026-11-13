import pypandoc

content = r"""<div align="center">

# 💗 PINK DISCOUNT

### *Belanja lebih hemat, hitung diskon lebih cepat!*

Aplikasi web sederhana untuk menghitung potongan harga dan mencatat transaksi belanja dengan tampilan pastel pink yang manis dan mudah digunakan.

![PHP](https://img.shields.io/badge/PHP-Backend-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![HTML](https://img.shields.io/badge/HTML-Structure-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS](https://img.shields.io/badge/CSS-Styling-1572B6?style=for-the-badge&logo=css3&logoColor=white)

</div>

---

## 🌷 Tentang Pink Discount

**Pink Discount** merupakan aplikasi berbasis web yang membantu pengguna menghitung diskon berdasarkan jumlah belanja. Pengguna dapat melihat rincian perhitungan terlebih dahulu, kemudian memilih untuk menyimpan transaksi ke dalam riwayat atau membatalkannya.

Aplikasi ini dikembangkan sebagai latihan penerapan pemrograman web menggunakan **PHP** dan pengelolaan basis data **MySQL**.

## ✨ Fitur Utama

| Fitur | Keterangan |
|---|---|
| 🧮 Hitung Diskon | Menghitung potongan harga secara otomatis berdasarkan total belanja. |
| 🧾 Rincian Pembayaran | Menampilkan total belanja, persentase diskon, dan total yang harus dibayar. |
| 💾 Simpan Transaksi | Menyimpan transaksi yang sudah dikonfirmasi ke database. |
| 📚 Riwayat Transaksi | Menampilkan daftar transaksi yang pernah disimpan. |
| 🗑️ Hapus Transaksi | Menghapus transaksi tertentu dari riwayat. |
| 🧹 Hapus Semua Riwayat | Menghapus seluruh catatan transaksi. |

## 🎀 Aturan Diskon

Diskon ditentukan berdasarkan total belanja dengan ketentuan berikut:

| Total Belanja | Potongan |
|---:|---:|
| Rp100.000 ke atas | **10%** |
| Rp50.000 – Rp99.999 | **5%** |
| Di bawah Rp50.000 | **0%** |

## 🛠️ Teknologi

- **PHP** — memproses perhitungan diskon dan transaksi.
- **MySQL** — menyimpan data transaksi.
- **HTML** — menyusun struktur halaman aplikasi.
- **CSS** — mengatur tampilan dan nuansa pastel pink. CSS ditulis langsung di dalam file PHP, bukan sebagai file `style.css` terpisah.
- **XAMPP** — menjalankan server lokal Apache dan MySQL.
- **phpMyAdmin** — membantu mengelola database MySQL.

## 📂 Struktur Project

```text
program_diskon/
│
├── index.php       # Halaman utama dan kalkulator diskon
├── riwayat.php     # Halaman riwayat transaksi
├── koneksi.php     # Pengaturan koneksi database
├── db_toko.sql     # File ekspor database
└── README.md       # Dokumentasi project
```

## 🚀 Cara Menjalankan Project

Ikuti langkah berikut untuk menjalankan Pink Discount di komputer:

### 1. Siapkan XAMPP

Pastikan XAMPP sudah terpasang, kemudian aktifkan:
- Apache
- MySQL

### 2. Masukkan folder project

Salin folder `program_diskon` ke dalam direktori:

```text
C:\xampp\htdocs\
```

### 3. Buat dan import database

1. Buka `http://localhost/phpmyadmin`.
2. Buat database dengan nama **db_toko**.
3. Pilih database `db_toko`.
4. Buka tab **Import**.
5. Pilih file `db_toko.sql`.
6. Jalankan proses import.

### 4. Periksa koneksi database

Pastikan konfigurasi pada `koneksi.php` sesuai dengan pengaturan MySQL lokal. Konfigurasi default XAMPP yang umum digunakan:

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_toko";
```

Jika pengaturan MySQL di komputer berbeda, sesuaikan nilainya.

### 5. Buka aplikasi

Jalankan melalui browser:

```text
http://localhost/program_diskon/
```

Selesai! Pink Discount siap digunakan. 🌸

## 🧁 Cara Menggunakan

1. Buka halaman utama Pink Discount.
2. Masukkan nominal belanja.
3. Klik **Hitung Diskon**.
4. Periksa rincian total belanja, diskon, dan total pembayaran.
5. Klik **Simpan ke Riwayat** jika ingin menyimpan transaksi.
6. Pilih **Batalkan** atau **Hapus Hasil** jika tidak ingin menyimpan transaksi.
7. Buka halaman riwayat untuk melihat atau mengelola transaksi yang tersimpan.

## 🗃️ Database

Project ini menggunakan database **`db_toko`** dengan tabel **`transaksi`**.

| Kolom | Fungsi |
|---|---|
| `id` | ID transaksi dan primary key |
| `total_belanja` | Nominal belanja sebelum diskon |
| `diskon` | Persentase diskon yang diperoleh |
| `total_bayar` | Jumlah yang harus dibayar setelah diskon |
| `tanggal` | Waktu transaksi dicatat |

## 🎯 Tujuan Pengembangan

Melalui project ini, beberapa konsep dasar pengembangan aplikasi web diterapkan, antara lain:

- Mengolah input dari pengguna.
- Menggunakan kondisi untuk menentukan persentase diskon.
- Menghubungkan aplikasi PHP dengan database MySQL.
- Menyimpan, menampilkan, dan menghapus data transaksi (CRUD).
- Membuat antarmuka web dengan HTML dan CSS.

## 💌 Catatan

- Aplikasi ini ditujukan untuk dijalankan di lingkungan lokal menggunakan XAMPP.
- File `db_toko.sql` digunakan untuk membantu proses pembuatan database.
- Pastikan konfigurasi database sudah sesuai sebelum menjalankan aplikasi.

---

<div align="center">

**Made with 💗 and a little bit of PHP**

*Pink Discount — Simple calculation, sweeter shopping!*

</div>
"""

output_path = "/mnt/data/README.md"
pypandoc.convert_text(content, "md", format="md", outputfile=output_path, extra_args=["--standalone"])
print("README.md berhasil dibuat:", output_path)