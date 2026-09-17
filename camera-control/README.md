## Deskripsi
Proyek ini adalah sebuah aplikasi Python untuk mengontrol kamera (webcam/UVC) secara langsung dari desktop atau perangkat embedded. Aplikasi ini mendukung fitur pratinjau (live preview), pengambilan foto (single capture), mode jepretan beruntun (burst capture), hingga pendeteksian wajah (face detection) secara real-time. Selain itu, proyek ini memiliki kontrol manual terhadap Exposure dan ISO, yang dibantu dengan simulasi software apabila hardware tidak mendukungnya secara native.

## Teknologi / Stack
- Python (>= 3.x)
- OpenCV (`opencv-python<5.0.0`)
- Pynput (`pynput>=1.7.6`)
- Numpy (`numpy>=1.21.0`)

## Cara Instalasi
1. Pastikan Python 3 sudah terinstal di sistem Anda.
2. Clone atau unduh repositori ini.
3. Buka terminal di dalam folder `camera-control/`.
4. (Opsional) Buat dan aktifkan virtual environment:
   ```bash
   python -m venv venv
   # Di Windows:
   venv\Scripts\activate
   # Di Linux/Mac:
   source venv/bin/activate
   ```
5. Install semua dependensi menggunakan pip:
   ```bash
   pip install -r requirements.txt
   ```

## Cara Menjalankan
Jalankan script utama dengan perintah berikut di terminal:
```bash
python main.py
```
*(Tidak ada port khusus yang digunakan karena ini adalah aplikasi desktop/GUI berbasis OpenCV)*

## Struktur Folder
```text
camera-control/
├── captures/               # Folder tempat menyimpan hasil jepretan foto (auto-generated)
├── __pycache__/            # Folder cache Python
├── camera_controller.py    # Class inti pengontrol kamera dan simulasi exposure/ISO
├── config.py               # File konfigurasi parameter default (resolusi, shutter, dll)
├── cv2_dir.txt             # Berisi mapping Haar Cascades (resource internal)
├── main.py                 # File utama yang berisi loop UI OpenCV dan event listener keyboard
├── README.md               # Dokumentasi proyek ini
└── requirements.txt        # Daftar dependensi library
```

## Catatan Tambahan
- **Keybindings Utama saat aplikasi berjalan:**
  - `SPACE` = Ambil 1 foto
  - Tahan `B` = Burst capture (ambil foto beruntun)
  - `F` = Aktifkan/matikan Face Detection
  - `+` / `-` = Naikkan/turunkan Exposure
  - `[` / `]` = Turunkan/naikkan ISO
  - `Q` / `ESC` = Keluar dari aplikasi
- **Face Detection** di aplikasi ini menggunakan optimasi skala resolusi, sehingga tidak memperlambat FPS dan kotak hijau pendeteksi hanya muncul di layar preview, tidak ikut tersimpan pada foto di folder `captures/`.
- File gambar akan tersimpan berformat JPG dengan nama berdasarkan *timestamp* di folder `captures/`.
