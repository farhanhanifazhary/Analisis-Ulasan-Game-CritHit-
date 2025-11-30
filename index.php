<?php
// Selalu mulai session (anti-notice)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Masukkan file koneksi
require 'koneksi.php';

// Fungsi Helper (Bantu)
function get_snippet($text, $length = 150) {
    $text = strip_tags($text); // Hapus tag HTML
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' ')); // Potong di spasi terakhir
        $text .= '...'; // Tambahkan elipsis
    }
    return $text;
}

// Inisialisasi variabel data
$games = [];
$articles = [];
$article_game_map = [];
$error_db = '';

try {
    // --- Kueri 1: Ambil Data Game (Filter Genre) ---
    $sql_base = "SELECT DISTINCT g.* FROM games g";
    $joins = "";
    $wheres = [];
    $params = [];

    // Cek Filter Genre
    if (isset($_GET['genre']) && !empty($_GET['genre'])) {
        $joins .= " JOIN game_genres gg ON g.id = gg.game_id";
        $wheres[] = "gg.genre_id = ?";
        $params[] = $_GET['genre'];
    }

    $sql_final = $sql_base . $joins;
    if (!empty($wheres)) {
        $sql_final .= " WHERE " . implode(" AND ", $wheres);
    }
    
    $sql_final .= " ORDER BY g.release_date DESC";

    $stmt_games = $pdo->prepare($sql_final);
    $stmt_games->execute($params);
    $games_data = $stmt_games->fetchAll();

    // --- Kueri 2: Ambil Data Artikel (Ikut Filter Genre) ---
    $sql_art_base = "SELECT DISTINCT 
                        a.id, a.title, a.content, a.image_url, a.created_at, 
                        u.name AS author_name 
                     FROM article a
                     JOIN users u ON a.user_id = u.id";
    
    $art_joins = "";
    $art_wheres = [];
    $art_params = [];

    // Jika ada filter genre, join ke tabel game & genre
    if (isset($_GET['genre']) && !empty($_GET['genre'])) {
        $art_joins .= " JOIN article_games ag_filter ON a.id = ag_filter.article_id";
        $art_joins .= " JOIN games g_filter ON ag_filter.game_id = g_filter.id";
        $art_joins .= " JOIN game_genres gg_filter ON g_filter.id = gg_filter.game_id";
        
        $art_wheres[] = "gg_filter.genre_id = ?";
        $art_params[] = $_GET['genre'];
    }

    $sql_articles_final = $sql_art_base . $art_joins;
    if (!empty($art_wheres)) {
        $sql_articles_final .= " WHERE " . implode(" AND ", $art_wheres);
    }
    
    $sql_articles_final .= " ORDER BY a.created_at DESC";

    $stmt_articles = $pdo->prepare($sql_articles_final);
    $stmt_articles->execute($art_params);
    $articles = $stmt_articles->fetchAll();
    // Kueri 3: Ambil semua game terkait untuk artikel
    
    $article_ids = array_column($articles, 'id');

    if (!empty($article_ids)) {
        // Buat placeholder '?' sebanyak ID
        $placeholders = implode(',', array_fill(0, count($article_ids), '?'));
        
        $sql_game_map = "SELECT g.id, g.title, ag.article_id 
                         FROM games g
                         JOIN article_games ag ON g.id = ag.game_id
                         WHERE ag.article_id IN ($placeholders)";
        
        $stmt_game_map = $pdo->prepare($sql_game_map);
        $stmt_game_map->execute($article_ids);
        $game_relations = $stmt_game_map->fetchAll();

        // Proses hasil kueri menjadi map yang rapi
        // Hasilnya: $article_game_map[id_artikel] = [ ['id'=>1, 'title'=>'Game A'], ... ]
        foreach ($game_relations as $relation) {
            $article_game_map[$relation['article_id']][] = [
                'title' => $relation['title']
            ];
        }
    }
    
} catch (PDOException $e) {
    $error_db = "Terjadi error saat mengambil data: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CritHit - Ulasan Game Anda</title>
</head>
<body class="bg-gray-900 text-white font-sans">

    <?php 
    include 'template/header.php';
    ?>

    <div class="container mx-auto px-4 py-16 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Temukan Ulasan. Berikan Penilaian.</h1>
        <p class="text-xl text-gray-400 mb-8">Platform ulasan game independen Anda.</p>
        
        <form action="search.php" method="GET" class="max-w-2xl mx-auto">
            <div class="relative">
                <input 
                    type="search" 
                    name="query" 
                    class="block w-full p-4 text-lg rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-400" 
                    placeholder="Cari game... (Misal: Elden Ring)" 
                    required 
                />
                <button type="submit" class="absolute top-0 right-0 h-full p-4 text-white bg-blue-600 rounded-r-lg hover:bg-blue-700">
                    Cari
                </button>
            </div>
        </form>
    </div>

    <?php include 'template/game_filter.php'; ?>

    <main class="container mx-auto px-4 pb-12">
        <h2 class="text-3xl font-bold mb-6 border-b border-gray-700 pb-2">Game Populer</h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">

            <?php 
            foreach ($games_data as $game) {
    
                // === [PENTING!] RESET VARIABEL DI SINI ===
                // Kita wajib mengosongkan variabel ini di AWAL setiap putaran
                // agar data game sebelumnya tidak "bocor" ke game yang tidak punya ulasan.
                $avg_rating = 0; 
                $dominant_sentiment = null;
                // =========================================

                // Ambil Publisher
                $stmt_pubs = $pdo->prepare("SELECT p.name FROM publisher p JOIN game_publishers gp ON p.id = gp.publisher_id WHERE gp.game_id = ?");
                $stmt_pubs->execute([$game['id']]);
                $publishers = $stmt_pubs->fetchAll(PDO::FETCH_COLUMN); 

                // Ambil Genre
                $stmt_genres = $pdo->prepare("SELECT g.name FROM genres g JOIN game_genres gg ON g.id = gg.genre_id WHERE gg.game_id = ?");
                $stmt_genres->execute([$game['id']]);
                $genres = $stmt_genres->fetchAll(PDO::FETCH_COLUMN);

                // Hitung Rating & Sentimen
                $stmt_stats = $pdo->prepare("SELECT rating, sentiment FROM reviews WHERE game_id = ?");
                $stmt_stats->execute([$game['id']]);
                $stats_data = $stmt_stats->fetchAll();

                $review_count = count($stats_data);

                if ($review_count > 0) {
                    // Hitung Rata-rata
                    $total_rating = array_sum(array_column($stats_data, 'rating'));
                    $avg_rating = round($total_rating / $review_count, 1);

                    // Hitung Sentimen Dominan
                    $sentiment_counts = ['positive' => 0, 'negative' => 0];
                    foreach ($stats_data as $row) {
                        if (!empty($row['sentiment'])) {
                            $sentiment_counts[$row['sentiment']]++;
                        }
                    }
                    $dominant_sentiment = array_keys($sentiment_counts, max($sentiment_counts))[0];
                }
                
                // Panggil template
                include "template/game_card.php";
            }
            ?>
            
        </div> 
    </main>

    <section class="container mx-auto px-4 pb-16">
        <h2 class="text-3xl font-bold mb-6 border-b border-gray-700 pb-2">Artikel Terbaru</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if (empty($articles)): ?>
                <p class="text-gray-400 col-span-full">Belum ada artikel yang dipublikasikan.</p>
            <?php else: ?>
                <?php 
                // panggil template article card
                foreach ($articles as $article) {
                    // Ambil game terkait dari map yang sudah kita buat
                    $related_games = $article_game_map[$article['id']] ?? [];

                    include 'template/article_card.php';
                } 
                ?>
            <?php endif; ?>
        </div>
    </section>

    <?php 
        include 'template/footer.php'; 
    ?>

</body>
</html>