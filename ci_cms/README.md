## Deskripsi
Proyek ini adalah sebuah Sistem Manajemen Konten (CMS) yang terintegrasi dengan fitur E-Commerce. Proyek ini dibangun di atas framework CodeIgniter 4 dan mengadopsi arsitektur Model-Service-Controller-View (MSCV). Fungsionalitas utamanya meliputi manajemen inventaris produk, pembuatan pesanan (add to cart), sistem simulasi pembayaran yang sangat interaktif (gateway simulation), pencatatan riwayat transaksi yang aman menggunakan _Database Transaction_ untuk menghindari race-condition stok, serta fitur cetak Struk Pembelian (PDF/Thermal receipt).

## Teknologi / Stack
- PHP (`^8.2`)
- CodeIgniter 4 (`codeigniter4/framework: ^4.7`)
- Basis Data (MySQL / MariaDB via MySQLi)
- Composer (sebagai Dependency Manager)
- Bootstrap 5 (CSS/UI) & Vanilla JavaScript

## Cara Instalasi
1. Pastikan Anda telah menginstal PHP 8.2+ dan Composer di sistem Anda, beserta server MySQL (misal menggunakan XAMPP/Laragon).
2. Clone atau unduh repositori ini.
3. Buka terminal di dalam folder `ci_cms/`.
4. Install semua dependensi CodeIgniter menggunakan Composer:
   ```bash
   composer install
   ```
5. Siapkan konfigurasi Environment:
   - Copy atau duplikat file `env` dan ubah namanya menjadi `.env`.
   - Buka `.env` dan atur URL:
     `app.baseURL = 'http://localhost:8080/'`
   - Pada file `.env`, atur bagian Database Configuration (buang tanda `#` di awal baris):
     ```env
     database.default.hostname = localhost
     database.default.database = nama_database_anda
     database.default.username = root
     database.default.password = 
     database.default.DBDriver = MySQLi
     ```

## Cara Menjalankan
1. Pastikan servis database MySQL di XAMPP/Laragon sudah berjalan.
2. Pertama, lakukan migrasi struktur tabel database. Di terminal jalankan:
   ```bash
   php spark migrate
   ```
3. Mulai server *development* bawaan CodeIgniter dengan perintah:
   ```bash
   php spark serve
   ```
*(Aplikasi web akan secara otomatis berjalan di port default: `http://localhost:8080`)*

## Struktur Folder
```text
ci_cms/
├── app/
│   ├── Config/          # Berisi konfigurasi aplikasi, database, dan routing (Routes.php)
│   ├── Controllers/     # Logic Controller standar HTTP Request
│   ├── Database/        # Skrip Migration untuk tabel database
│   ├── Models/          # Model Database (ProductModel, TransactionModel, dsb.)
│   ├── Services/        # Logic Bisnis (MSCV pattern, misal: TransactionService.php)
│   └── Views/           # Berkas antarmuka HTML/PHP untuk Web CMS
├── public/              # Document root (aset gambar produk, index.php utama web server)
├── tests/               # Unit testing environment
├── vendor/              # Folder dependensi pihak ketiga hasil install Composer
├── writable/            # Folder sementara untuk cache, session, dan log CodeIgniter
├── .env                 # Environment configuration (konfigurasi rahasia)
├── check_db.php         # Script utilitas kecil (optional check)
├── composer.json        # Manifest Dependency Composer
└── README.md            # Dokumentasi proyek ini
```

## Catatan Tambahan
- **Arsitektur MSCV:** Berbeda dari standar MVC biasa, seluruh *business logic* rumit (seperti pengecekan stok beruntun, pemotongan kuantitas, validasi) tidak ditempatkan di dalam Controller maupun Model, melainkan dibungkus dalam folder `app/Services/`. Controller hanya menerima *request* HTTP dan berinteraksi langsung dengan Service.
- **Database Transaction:** Pemotongan stok bersifat transaksional. Bila proses *insert* baris pesanan atau saat *update* gagal di pertengahan jalan, sistem akan me-*Rollback* seluruh transaksi, sehingga data pesanan batal dan stok asli tak akan pernah hangus/terpotong tanpa alasan.
