# CritHit - Game Review & Sentiment Analysis Platform

**CritHit** adalah sebuah platform web modern untuk mengulas video game yang dilengkapi dengan fitur analisis sentimen berbasis *Machine Learning*. Proyek ini dibangun untuk memenuhi Ujian Tengah Semester mata kuliah Pemrograman Web Lanjut dengan fokus pada arsitektur Native PHP yang bersih, keamanan data, dan integrasi lintas bahasa (PHP-Python).

<div align="center">
  <h3>Demo Aplikasi CritHit</h3>
  <p>Tonton video demonstrasi lengkap fitur CRUD, Analisis Sentimen, dan Alur Pengguna.</p>
  
  <a href="[LINK_VIDEO_YOUTUBE_ANDA](https://youtu.be/39EcyHSML_8)" target="_blank">
    <img src="https://img.youtube.com/vi/39EcyHSML_8/maxresdefault.jpg" 
         alt="Tonton Video Demo" 
         style="width:100%; max-width:800px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
  </a>
  <p><i>Klik gambar di atas untuk memutar video</i></p>
</div>

https://youtu.be/39EcyHSML_8

## Fitur Utama

### Sisi Pengguna (Frontend)
* **Game Library:** Menampilkan daftar game dengan *infinite possibilities* dari berbagai genre dan publisher.
* **Smart Search:** Pencarian universal yang mampu menemukan game dan artikel sekaligus, dilengkapi filter berdasarkan Genre.
* **User Reviews:** Pengguna terdaftar dapat memberikan ulasan (rating bintang & teks).
* **Analisis Sentimen (ML):** Setiap ulasan secara otomatis dianalisis oleh AI (Python) untuk menentukan sentimen (*Positive/Negative*) dan memberikan label pada ulasan tersebut.
* **Artikel & Berita:** Membaca berita terbaru seputar game, yang saling terhubung dengan database game terkait.
* **Sistem Favorit:** Menandai game favorit untuk akses cepat.
* **Manajemen Ulasan:** Pengguna dapat mengedit atau menghapus ulasan mereka sendiri (CRUD).

### Sisi Admin (Backend Dashboard)
* **Dashboard Statistik:** Visualisasi data sentimen ulasan menggunakan **Chart.js**.
* **Manajemen Game (CRUD):** Tambah, edit, hapus game dengan dukungan *multi-genre* dan *multi-publisher*.
* **Manajemen Master Data:** CRUD lengkap untuk Genre dan Publisher.
* **Manajemen Artikel:** CMS sederhana untuk menulis berita dan menautkannya ke game terkait.
* **Manajemen Pengguna:** Kontrol penuh atas akun pengguna terdaftar.
* **Moderasi Ulasan:** Pantau dan hapus ulasan yang tidak pantas.

## Teknologi yang Digunakan

* **Backend:** Native PHP.
* **Frontend:** HTML, Tailwind CSS, JS.
* **Database:** MySQL.
* **Machine Learning & Data Science:** Python 3, Pandas, Scikit-learn, Sastrawi (NLP), Joblib.
* **Data Acquisition & Tools:** Google Play Scraper, Langdetect, Matplotlib, Seaborn, WordCloud.
* **Visualisasi:** Chart.js.
* **Server:** Apache (via XAMPP).

## Instalasi & Cara Menjalankan

Ikuti langkah-langkah ini untuk menjalankan proyek di komputer lokal Anda:

### 1. Persiapan Database
1.  Buka **phpMyAdmin**.
2.  Buat database baru bernama `crithit_db`.
3.  Impor file `database/crithit_db.sql` (Pastikan Anda mengekspor database terakhir Anda ke file ini).

### 2. Konfigurasi Koneksi
1.  Buka file `koneksi.php` di *root* folder.
2.  Sesuaikan konfigurasi jika perlu (default XAMPP):
    ```php
    $host = 'localhost';
    $db   = 'crithit_db';
    $user = 'root';
    $pass = '';
    ```

### 3. Persiapan Machine Learning (Python)

Fitur analisis sentimen CritHit ditenagai oleh Python. Ikuti langkah-langkah ini untuk menyiapkan lingkungan kerja (environment) agar skrip Python dapat berjalan dengan lancar.

#### A. Instalasi Python & Dependencies

1.  **Pastikan Python Terinstall:**
    Pastikan komputer Anda sudah terinstall Python versi **3.10** atau lebih baru. Cek versinya dengan perintah:
    ```bash
    python --version
    ```

2.  **Buat Virtual Environment (Sangat Disarankan):**
    Agar library proyek ini tidak bentrok dengan proyek lain, buatlah lingkungan virtual:
    ```bash
    # Masuk ke folder proyek
    cd CritHit

    # Buat environment bernama 'venv'
    python -m venv venv

    # Aktifkan environment
    # Windows:
    venv\Scripts\activate
    # Mac/Linux:
    source venv/bin/activate
    ```

3.  **Install Library dari `requirements.txt`:**
    Semua library yang dibutuhkan sudah didaftarkan di file `ml_research/requirements.txt`. Install semuanya sekaligus dengan perintah:
    ```bash
    pip install -r ml_research/requirements.txt
    ```
    *Proses ini mungkin memakan waktu beberapa menit karena akan mengunduh library data science seperti Pandas dan Scikit-learn.*

#### B. Setup Folder `ml_engine`
Folder `ml_engine` adalah "otak" yang akan dipanggil oleh website PHP. Pastikan folder ini ada di *root* proyek dan berisi 3 file hasil training:

1.  `analyze.py` (Skrip jembatan PHP <-> Python).
2.  `crithit_model.pkl` (Model AI yang sudah pintar).
3.  `crithit_vectorizer.pkl` (Kamus penerjemah teks ke angka).

> **Tip:** Jika file `.pkl` belum ada, Anda bisa membuatnya sendiri (Retraining) dengan menjalankan notebook yang ada di folder `ml_research`.

#### C. Konfigurasi Path di PHP
Agar website tahu di mana Python Anda berada, edit file `review_proses.php`:

1.  Buka `review_proses.php`.
2.  Cari baris kode `$python_exec = ...`.
3.  Ubah sesuai lokasi Python di komputer Anda (terutama jika pakai Virtual Environment):

    **Contoh jika pakai Venv (Windows):**
    ```php
    $python_exec = "C:/xampp/htdocs/CritHit/venv/Scripts/python.exe";
    ```
    **Contoh jika pakai Venv (Mac/Linux):**
    ```php
    $python_exec = "/var/www/html/CritHit/venv/bin/python";
    ```
    **Contoh jika pakai Python Global:**
    ```php
    $python_exec = "python";
    ```

## Struktur Folder

```text
CritHit/
├── admin/                  # Halaman-halaman Dashboard Admin
│   ├── article/            # CRUD Artikel (tambah, edit, hapus)
│   ├── games/              # CRUD Game (tambah, edit, hapus)
│   ├── genres/             # CRUD Genre (tambah, edit, hapus)
│   ├── publisher/          # CRUD Publisher (tambah, edit, hapus)
│   ├── reviews/            # Moderasi Ulasan (hapus)
│   ├── users/              # CRUD User (tambah, edit, hapus)
│   ├── dashboard_home.php  # Halaman utama dashboard (Chart)
│   └── index.php           # Router/Template utama Admin (Sidebar)
├── assets/                 # Aset Statis
│   └── js/
│       └── tailwind.js     # Tailwind CSS (Offline)
├── database/
│   └── crithit_db.sql      # Database
├── ml_engine/              # Mesin Machine Learning (Backend)
│   ├── analyze.py          # Skrip jembatan Python untuk prediksi
│   ├── crithit_model.pkl   # Model ML yang sudah dilatih
│   └── crithit_vectorizer.pkl # Vectorizer teks
├── ml_research/            # Riset & Pengembangan ML (Jupyter Notebooks)
│   ├── 1_Data_Preprocessing.ipynb
│   ├── 2_Model_Training.ipynb
│   ├── dataset_crithit.csv
│   ├── requirements.txt
│   └── scrape_reviews.py
├── template/               # Komponen UI (Header, Footer, Cards, Filter)
│   ├── article_card.php
│   ├── footer.php
│   ├── game_card.php
│   ├── game_filter.php
│   └── header.php
├── koneksi.php             # Koneksi Database PDO
├── index.php               # Homepage (Landing Page)
├── search.php              # Halaman Pencarian & Filter
├── detail.php              # Halaman Detail Game
├── article.php             # Halaman Daftar Artikel
├── article_detail.php      # Halaman Detail Artikel
├── favorites.php           # Halaman Game Favorit User
├── login.php               # Halaman Login
├── login_proses.php        # Logika Login
├── register.php            # Halaman Registrasi
├── register_proses.php     # Logika Registrasi
├── logout.php              # Logika Logout
├── review_proses.php       # Logika Submit Ulasan (PHP <-> Python)

└── review_hapus.php        # Logika Hapus Ulasan User
```

### Email:Password user:
* aku@gmail.com:Aku kaya (Admin)
* hanif@gmail.com:Tolong aku
* talulah@gmail.com:Draco_victorian
* cecil@gmail.com:Eden_spear
* Skadi@gmail.com:Abyssal_hunter
* Amiyi@gmail.com:Lord_of_Fiend
