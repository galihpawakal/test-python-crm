# Repositori Tugas Teknis

Repositori ini berisi kumpulan proyek tugas teknis, yang masing-masing dipisahkan ke dalam direktorinya sendiri. Proyek-proyek ini mencakup pengembangan web, visi komputer, dan pembelajaran mesin.

## Struktur Proyek

Repositori ini terdiri dari tiga proyek utama:

- [**ci_cms**](./ci_cms): Sistem Manajemen Konten (CMS) yang dibangun menggunakan framework CodeIgniter 4. Proyek ini dilengkapi dengan fitur simulasi E-commerce yang mencakup keranjang belanja dinamis, simulasi gerbang pembayaran (payment gateway), serta fitur cetak struk (receipt).
- [**camera-control**](./camera-control): Skrip berbasis Python yang digunakan untuk berinteraksi dengan dan mengontrol kamera menggunakan pustaka OpenCV.
- [**fruits_project**](./fruits_project): Proyek Pembelajaran Mesin (Machine Learning) dan Visi Komputer (Computer Vision) untuk mendeteksi buah-buahan menggunakan arsitektur YOLOv8.

## Panduan Instalasi & Penggunaan

Berikut adalah panduan lengkap untuk mengatur dan menjalankan masing-masing proyek di lingkungan lokal Anda.

### 1. Sistem Manajemen Konten CodeIgniter (`ci_cms`)
Proyek ini adalah aplikasi web CMS dan simulasi e-commerce.
- **Persyaratan Sistem**: PHP 8.1 atau lebih baru, Composer, MySQL/MariaDB
- **Langkah-langkah Instalasi**:
  1. Buka terminal atau command prompt dan arahkan ke direktori proyek: `cd ci_cms/`
  2. Instal semua dependensi menggunakan Composer: `composer install`
  3. Salin file konfigurasi lingkungan: salin file `env` menjadi `.env`.
  4. Buka file `.env`, hilangkan tanda komentar (`#`), dan atur konfigurasi basis data Anda pada bagian `database.default` (seperti `hostname`, `database`, `username`, dan `password`).
  5. Jalankan migrasi untuk membuat struktur tabel di basis data: `php spark migrate`
  6. Jalankan server pengembangan lokal bawaan CodeIgniter: `php spark serve`
  7. Buka browser dan akses aplikasi melalui `http://localhost:8080`.

### 2. Kontrol Kamera (`camera-control`)
Proyek ini menangani aliran video dan interaksi dengan perangkat kamera.
- **Persyaratan Sistem**: Python 3.10 atau lebih baru
- **Langkah-langkah Instalasi**:
  1. Arahkan terminal ke direktori proyek: `cd camera-control/`
  2. Buat lingkungan virtual (virtual environment) Python agar dependensi proyek terisolasi: `python -m venv venv`
  3. Aktifkan lingkungan virtual:
     - Untuk Windows: `venv\Scripts\activate`
     - Untuk Linux/Mac: `source venv/bin/activate`
  4. Instal semua pustaka Python yang dibutuhkan: `pip install -r requirements.txt`
  5. Jalankan aplikasi utama: `python main.py`

### 3. Deteksi Buah (`fruits_project`)
Proyek ini difokuskan pada pelatihan dan inferensi model pendeteksi objek.
- **Persyaratan Sistem**: Python 3.10 atau lebih baru
- **Langkah-langkah Instalasi**:
  1. Arahkan terminal ke direktori proyek: `cd fruits_project/`
  2. Buat lingkungan virtual (virtual environment) Python: `python -m venv venv`
  3. Aktifkan lingkungan virtual:
     - Untuk Windows: `venv\Scripts\activate`
     - Untuk Linux/Mac: `source venv/bin/activate`
  4. Instal semua dependensi yang diperlukan: `pip install -r requirements.txt`
  5. Untuk menjalankan pelatihan model (training) atau inferensi (pengujian), silakan merujuk pada instruksi spesifik di dalam direktori proyek ini atau jalankan skrip utama, misalnya `python train.py`.

## Spesifikasi Sistem

Proyek-proyek di dalam repositori ini dikembangkan dan telah diuji pada lingkungan dengan spesifikasi berikut:
- **Sistem Operasi**: Windows 11
- **Perangkat Lunak & Alat Utama**: 
  - PHP 8.1+
  - Composer
  - Python 3.10+
  - CodeIgniter 4
  - YOLOv8 (Ultralytics)
  - OpenCV

---
*Silakan jelajahi masing-masing folder proyek untuk melihat kode sumber dan informasi yang lebih spesifik.*
