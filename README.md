# SIREKOWIS v2.0 - Sistem Rekomendasi Destinasi Wisata Kota Banjarbaru

> **Aplikasi Web Resmi Mitra DISPORABUDPAR Kota Banjarbaru**  
> Menggunakan Metode **Content-Based Filtering (CBF)** dengan **Weighted Cosine Similarity** & **TF-IDF Vectorization**.  
> Dirancang sesuai dengan **Pedoman PKL FTI UNISKA Banjarmasin** (Multiuser Database, 6 Modul Laporan PDF, Evaluasi Precision@5 & SUS).

---

## 🚀 Fitur Unggulan Sistem

### 1. Portal Publik (Wisatawan)
- **Beranda Interaktif:** Hero banner dinamis, pencarian cepat, ringkasan statistik pariwisata, dan sebaran lokasi wisata pada peta OpenStreetMap (Leaflet.js).
- **Katalog Wisata & Filter Lengkap:** Filter cerdas berdasarkan kata kunci teks, kategori, fasilitas (multi-select), wilayah kecamatan di Banjarbaru, dan batas anggaran tiket masuk.
- **Detail Wisata & Peta Interaktif:** Peta Leaflet.js dengan marker koordinat nyata, informasi jam operasional, rating Google Maps, serta tombol navigasi Google Maps.
- **Engine Rekomendasi CBF ("Destinasi Wisata Serupa"):** Menampilkan tempat wisata yang paling relevan secara real-time berdasarkan 5 atribut pembobotan, dilengkapi indikator persentase kemiripan (*similarity meter*).
- **Simulator CBF Interaktif:** Halaman edukasi untuk menguji kalkulasi matematis kemiripan antara dua destinasi secara transparan (*sangat berguna saat sidang PKL*).

### 2. Panel Administrasi DISPORABUDPAR
- **Dashboard Statistik:** Ringkasan total destinasi, sebaran kategori, 5 destinasi paling sering direkomendasikan, 5 destinasi rating tertinggi, dan log aktivitas real-time.
- **CRUD Destinasi Wisata:** Form input lengkap dengan upload foto, sinkronisasi fasilitas, dan *interactive click-to-pick Leaflet map* untuk menentukan koordinat latitude & longitude otomatis.
- **CRUD Kategori:** Pengelolaan klasifikasi wisata (Alam, Edukasi, Budaya, Buatan).
- **CRUD Sarana & Prasarana Fasilitas:** Manajemen fasilitas lengkap dengan ikon FontAwesome.
- **Autentikasi Multiuser:** Login terproteksi menggunakan Laravel Breeze (Role: `admin` & `operator`).

### 3. Modul 6 Laporan PDF Resmi (Syarat Pedoman UNISKA Bab II.B.8)
Dilengkapi **Kop Surat Resmi Dinas Kepemudaan, Olahraga, Kebudayaan dan Pariwisata Kota Banjarbaru** dan tanda tangan pengesahan pejabat:
1. **L-01:** Laporan Data Destinasi & Kategori Wisata (Portrait A4)
2. **L-02:** Laporan Matriks Kemiripan (Similarity Score) Antar-Wisata (Landscape A4)
3. **L-03:** Laporan Riwayat & Log Rekomendasi Pengunjung (Portrait A4)
4. **L-04:** Laporan Destinasi Terpopuler & Rating Google Maps (Portrait A4)
5. **L-05:** Laporan Rekapitulasi Fasilitas Destinasi Wisata (Landscape A4)
6. **L-06:** Laporan Statistik Preferensi & Pengguna Terdaftar (Laporan Cadangan, Portrait A4)

---

## 🧮 Spesifikasi Bobot Algoritma Content-Based Filtering

Rumus Weighted Cosine Similarity:
$$\text{Sim}(A, B) = w_1 \cdot \text{Kat}(A,B) + w_2 \cdot \text{Fas}(A,B) + w_3 \cdot \text{Hrg}(A,B) + w_4 \cdot \text{Rat}(A,B) + w_5 \cdot \text{TFIDF}(A,B)$$

| Fitur / Parameter | Bobot ($w$) | Metode Perhitungan |
|---|---|---|
| **Kategori Wisata** | 0.30 (30%) | *One-Hot Encoding* (1.0 jika sama, 0.0 jika beda) |
| **Fasilitas Wisata** | 0.30 (30%) | *Multi-Hot Binary Vector Cosine Similarity* |
| **Harga Tiket** | 0.15 (15%) | *Min-Max Normalization* & Selisih Absolut: $1 - \frac{\|harga_A - harga_B\|}{range}$ |
| **Rating Google Maps** | 0.15 (15%) | Skala Normalisasi 0.0 – 5.0: $1 - \frac{\|rat_A - rat_B\|}{5.0}$ |
| **Deskripsi & Kata Kunci** | 0.10 (10%) | *TF-IDF Vector Cosine Similarity* (Case folding, Stopwords Removal Bahasa Indonesia) |

---

## 📍 6 Destinasi Nyata Kota Banjarbaru (Seeder)

1. **Danau Seran** (Kec. Landasan Ulin) – Wisata Alam / Danau Eksotis
2. **Kebun Raya Banua** (Kec. Cempaka) – Wisata Edukasi & Botani Plasma Nutfah
3. **Hutan Pinus Mentaos** (Kec. Banjarbaru Utara) – Wisata Alam Perkotaan Rindang
4. **Museum Lambung Mangkurat** (Kec. Banjarbaru Utara) – Wisata Sejarah & Budaya Banjar-Dayak
5. **Kampung Pelangi Banjarbaru** (Kec. Banjarbaru Selatan) – Wisata Kreatif Swafoto Tematik
6. **Amanah Park Banjarbaru** (Kec. Banjarbaru Selatan) – Wisata Rekreasi Keluarga & Waterpark

---

## 🔑 Akun Login Staf DISPORABUDPAR

- **URL Login:** `http://127.0.0.1:8000/login`
- **Email Admin:** `admin@sirekowis.id`
- **Password:** `password`
- **Email Operator:** `operator@sirekowis.id`
- **Password:** `password`

---

## 💻 Cara Menjalankan Server Lokal

Buka terminal PowerShell pada direktori proyek (`C:\Users\USER\.gemini\antigravity\scratch\sirekowis`) dan jalankan:

```powershell
php artisan serve
```

Aplikasi siap diakses di peramban (browser) melalui alamat:
`http://127.0.0.1:8000`
