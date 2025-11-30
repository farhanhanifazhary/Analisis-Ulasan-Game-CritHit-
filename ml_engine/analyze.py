import sys
import joblib
import re
import os
from Sastrawi.Stemmer.StemmerFactory import StemmerFactory
from Sastrawi.StopWordRemover.StopWordRemoverFactory import StopWordRemoverFactory

# --- Konfigurasi Path Dinamis ---
# Ini akan mengambil lokasi folder tempat analyze.py berada
current_dir = os.path.dirname(os.path.abspath(__file__))

# Gabungkan folder tersebut dengan nama file modelmu
VECTORIZER_FILE = os.path.join(current_dir, 'crithit_vectorizer.pkl')
MODEL_FILE = os.path.join(current_dir, 'crithit_model.pkl')

# --- Setup Engine ---
try:
    stemmer = StemmerFactory().create_stemmer()
    stop_factory = StopWordRemoverFactory()
    negation_words = {'tidak', 'gak', 'ga', 'gk', 'bukan', 'jangan', 'enggak', 'tak', 'jan', 'kurang'}
    all_stopwords = set(stop_factory.get_stop_words()) - negation_words 
except Exception as e:
    # Tangkap error jika Sastrawi gagal loading
    with open(os.path.join(current_dir, "error_log.txt"), "w") as f:
        f.write(f"Error Loading Library: {str(e)}")
    print("neutral")
    sys.exit()

# --- Kamus Antonim ---
antonym_map = {
    # Positif -> Negatif (Jika didahului kata 'tidak')
    'bagus': 'buruk',
    'baik': 'jahat',
    'suka': 'benci',
    'puas': 'kecewa',
    'senang': 'sedih',
    'seru': 'membosankan',
    'keren': 'jelek',
    'epik': 'biasa',
    'worth': 'rugi',
    'layak': 'rugi',
    'ramah': 'jutek',
    'stabil': 'lag',
    'lancar': 'macet',
    'cepat': 'lambat',
    'pro': 'noob',
    'gg': 'noob',
    'wangi': 'ampas',
    'hoki': 'sial',
    'adil': 'curang',
    'bersih': 'kotor',
    'murah': 'mahal',
    'download': 'hindari',
    'main': 'hindari',
    
    # Negatif -> Positif (Jika didahului kata 'tidak')
    'buruk': 'bagus',
    'jelek': 'bagus',
    'kacau': 'rapi',
    'rusak': 'normal',
    'gagal': 'berhasil',
    'benci': 'suka',
    'kesal': 'senang',
    'bosan': 'seru',
    'susah': 'mudah',
    'ribet': 'mudah',
    'parah': 'bagus',
    'kecewa': 'puas',
    'nyesel': 'puas',
    'sial': 'beruntung',
    'mahal': 'murah',
    'pelit': 'baik',
    'curang': 'adil',
    'lag': 'lancar',
    'lemot': 'cepat',
    'berat': 'ringan',
    'patah': 'lancar',
    'ampas': 'wangi',
    'bug': 'lancar',
    'error': 'normal'
}

# --- Fungsi Logika ---
def handle_negation_advanced(text):
    words = text.split()
    new_words = []
    skip_next = False
    
    for i, w in enumerate(words):
        if skip_next:
            skip_next = False
            continue
            
        if w in negation_words and i + 1 < len(words):
            next_word = words[i+1]
            if next_word in antonym_map:
                replacement = antonym_map[next_word]
                new_words.append(replacement)
                skip_next = True 
            else:
                new_token = f"{w}_{next_word}"
                new_words.append(new_token)
                skip_next = True
        else:
            new_words.append(w)
            
    return " ".join(new_words)

def final_preprocess(text):
    if not isinstance(text, str): return ""
    text = re.sub(r'[^a-zA-Z\s]', ' ', text)
    text = text.lower()
    text = stemmer.stem(text)     
    text = handle_negation_advanced(text)  
    words = text.split()          
    filtered = [w for w in words if w not in all_stopwords]
    return " ".join(filtered)

# --- Eksekusi ---
try:
    # Cek keberadaan file dulu (Debugging Step)
    if not os.path.exists(MODEL_FILE):
        raise FileNotFoundError(f"File model tidak ditemukan di: {MODEL_FILE}")
    
    # Muat Model
    vectorizer = joblib.load(VECTORIZER_FILE)
    model = joblib.load(MODEL_FILE)

    # Baca Input
    input_text = sys.stdin.read()

    # Preprocessing
    clean_text = final_preprocess(input_text)

    # Prediksi
    if not clean_text:
        print("neutral")
    else:
        vector = vectorizer.transform([clean_text])
        prediction = model.predict(vector)[0]
        
        if prediction == 1:
            print("positive")
        else:
            print("negative")

except Exception as e:
    # Fallback output agar website tidak error
    print("gagal") 