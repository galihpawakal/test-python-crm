## Deskripsi
Proyek ini mengimplementasikan deteksi objek buah (Object Detection) menggunakan model YOLOv8 (You Only Look Once versi 8). Proyek ini dirancang untuk mendeteksi dan mengklasifikasikan 9 jenis buah berbeda, yaitu: Apel (Apple), Pisang (Banana), Anggur (Grapes), Kiwi, Mangga (Mango), Jeruk (Orange), Nanas (Pineapple), Srikaya (Sugerapple), dan Semangka (Watermelon).

## Teknologi / Stack
- Python (>= 3.x)
- Ultralytics YOLOv8 (`ultralytics>=8.0.0`)
- OpenCV (`opencv-python>=4.6.0`)
- Model: `yolov8n.pt` (Pre-trained Nano Model)

## Cara Instalasi
1. Pastikan Python 3 sudah terinstal di sistem Anda. Disarankan menggunakan lingkungan dengan dukungan GPU NVIDIA (CUDA).
2. Clone atau unduh repositori ini.
3. Buka terminal di dalam folder `fruits_project/`.
4. (Opsional) Buat dan aktifkan virtual environment:
   ```bash
   python -m venv venv
   # Di Windows:
   venv\Scripts\activate
   # Di Linux/Mac:
   source venv/bin/activate
   ```
5. Install dependensi standar melalui pip:
   ```bash
   pip install -r requirements.txt
   ```
6. Ekstrak data dataset pelatihan Anda ke dalam folder `dataset/` (pastikan struktur di dalamnya sesuai standar format dataset YOLO).

## Cara Menjalankan
Proyek ini terbagi menjadi dua skrip utama (Training dan Inference):

**Untuk melakukan Training Model (Melatih model baru):**
```bash
python train.py
```
*(Proses ini akan melatih `yolov8n.pt` selama 30 epochs sesuai konfigurasi di dalam file. Model terbaik otomatis tersimpan di `runs/fruits_detection/weights/best.pt`)*

**Untuk melakukan Inference (Testing deteksi gambar):**
```bash
python inference.py
```
*(Skrip ini akan membuka jendela OpenCV untuk menampilkan hasil deteksi berupa kotak/bounding box, label, dan persentase skor kepercayaan (confidence). Tekan sembarang tombol untuk beralih ke gambar selanjutnya, atau `ESC` untuk keluar.)*

## Struktur Folder
```text
fruits_project/
├── dataset/                # Folder dataset yang berisi gambar train/valid/test beserta label (format YOLO)
├── runs/                   # Folder hasil output YOLO (menyimpan weight model terbaik, log, dan grafik metrik hasil training)
├── __pycache__/            # Folder cache Python
├── inference.py            # Skrip untuk menguji model (inference) menggunakan gambar tes
├── README.md               # Dokumentasi proyek ini
├── requirements.txt        # Daftar dependensi library
├── train.py                # Skrip utama untuk proses pelatihan model (fine-tuning) YOLOv8
└── yolov8n.pt              # File weight (model dasar pre-trained YOLOv8 versi Nano)
```

## Catatan Tambahan
- **Dukungan GPU (Opsional namun Sangat Disarankan):** Jika Anda menggunakan sistem operasi Windows dan memiliki kartu grafis NVIDIA yang didukung, pastikan Anda menginstal versi PyTorch yang mendukung CUDA agar proses *training* jauh lebih cepat. Anda dapat menginstalnya secara terpisah dengan perintah:
  `pip3 install torch torchvision torchaudio --index-url https://download.pytorch.org/whl/cu118` (atau versi CUDA yang sesuai).
- File model hasil latih terbaik tidak ditimpa ke `yolov8n.pt`, melainkan selalu berada pada hierarki folder `runs/`. Pastikan Anda mereferensikan _path_ tersebut pada `inference.py` bila ingin menggunakan model yang paling baru.
