import pandas as pd
from google_play_scraper import Sort, reviews
from datetime import datetime
from langdetect import detect, LangDetectException

# --- Konfigurasi ---
TARGET_APPS = [
    {'name': 'Arknights', 'id': 'com.YoStarEN.Arknights'},
    {'name': 'Nikke', 'id': 'com.proximabeta.nikke'},
    {'name': 'Wuthering Waves', 'id': 'com.kurogame.wutheringwaves.global'},
    {'name': 'Blue Archive',    'id': 'com.nexon.bluearchive'},
    {'name': 'ZZZ',             'id': 'com.HoYoverse.Nap'},
    {'name': 'Reverse 1999',    'id': 'com.bluepoch.m.en.reverse1999'}
]

TARGET_LANG = 'id' 
TARGET_COUNTRY = 'id'
JUMLAH_DATA = 3000 # Jumlah ulasan per game

def scrape_google_play(apps, lang, country, count):
    all_reviews = []
    
    print(f"--- MULAI SCRAPING ({datetime.now().strftime('%H:%M:%S')}) ---")
    
    for app in apps:
        print(f"Sedang mengambil data untuk: {app['name']}...")
        
        try:
            result, continuation_token = reviews(
                app['id'],
                lang=lang, 
                country=country, 
                sort=Sort.NEWEST, # Ambil yang paling baru
                count=count
            )
            
            # Ubah ke DataFrame sementara
            df_temp = pd.DataFrame(result)
            
            # Tambahkan kolom identitas game (PENTING untuk scalability)
            df_temp['game_name'] = app['name']
            
            # Ambil kolom yang relevan saja untuk NLP
            # content = ulasan text
            # score = bintang 1-5
            # at = tanggal ulasan
            df_temp = df_temp[['game_name', 'content', 'score', 'at']]
            
            all_reviews.append(df_temp)
            print(f"  -> Berhasil dapat {len(df_temp)} ulasan.")
            
        except Exception as e:
            print(f"  -> Gagal mengambil data {app['name']}: {e}")

    # Gabungkan semua data
    if all_reviews:
        final_df = pd.concat(all_reviews, ignore_index=True)
        return final_df
    else:
        return pd.DataFrame()

# --- Eksekusi ---
df_hasil = scrape_google_play(TARGET_APPS, TARGET_LANG, TARGET_COUNTRY, JUMLAH_DATA)

# Fungsi untuk mendeteksi bahasa Indonesia dengan aman
def is_indonesian(text):
    try:
        # Pastikan teks tidak kosong dan berupa string
        if isinstance(text, str) and len(text.strip()) > 0:
            return detect(text) == 'id'
        return False
    except LangDetectException:
        # Jika library gagal mendeteksi, anggap bukan bahasa Indonesia
        return False

# Terapkan fungsi deteksi ke kolom 'content'
df_indonesian_only = df_hasil[df_hasil['content'].apply(is_indonesian)].copy()

# --- Simpan ---
if not df_indonesian_only.empty:
    filename = 'dataset_crithit.csv'
    df_indonesian_only.to_csv(filename, index=False)
    
    print(f"\nSUKSES! Data tersimpan di '{filename}'")
    print(f"Total Data: {len(df_indonesian_only)} baris")
    print("\nContoh 5 Data Teratas:")
    print(df_indonesian_only.head())
else:
    print("Gagal mendapatkan data.")