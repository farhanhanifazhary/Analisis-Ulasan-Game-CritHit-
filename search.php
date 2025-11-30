<?php
// Selalu mulai session (anti-notice)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Masukkan file koneksi
require 'koneksi.php';

// Fungsi Helper (Bantu)
function get_snippet($text, $length = 150) {
    $text = strip_tags($text);
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' '));
        $text .= '...';
    }
    return $text;
}

// Ambil dan Amankan Istilah Pencarian
$search_query = htmlspecialchars($_GET['query'] ?? '');
$like_query = "%$search_query%";

// Inisialisasi variabel
$search_games = [];
$search_articles = [];
$article_game_map = [];
$error_db = '';

if (!empty($search_query)) {
    try {
        // --- Kueri 1: Cari Game ---
        
        // Base Query
        $sql_games = "SELECT DISTINCT g.* FROM games g
                      LEFT JOIN game_publishers gp_search ON g.id = gp_search.game_id
                      LEFT JOIN publisher p_search ON gp_search.publisher_id = p_search.id";
        
        $wheres = [];
        $params = [];

        // Logika Pencarian Teks
        $wheres[] = "(g.title LIKE ? OR p_search.name LIKE ?)";
        $params[] = $like_query;
        $params[] = $like_query;

        // Logika Filter Dropdown (Hanya genre)
        if (isset($_GET['genre']) && !empty($_GET['genre'])) {
            $sql_games .= " JOIN game_genres gg_filter ON g.id = gg_filter.game_id";
            $wheres[] = "gg_filter.genre_id = ?";
            $params[] = $_GET['genre'];
        }

        $sql_games .= " WHERE " . implode(" AND ", $wheres);
        $sql_games .= " GROUP BY g.id ORDER BY g.release_date DESC";
        
        $stmt_games = $pdo->prepare($sql_games);
        $stmt_games->execute($params);
        $search_games = $stmt_games->fetchAll();

        // --- Kueri 2: Cari Artikel ---
        $sql_articles = "SELECT a.*, u.name AS author_name 
                         FROM article a
                         JOIN users u ON a.user_id = u.id
                         LEFT JOIN article_games ag ON a.id = ag.article_id
                         LEFT JOIN games g ON ag.game_id = g.id
                         
                         -- JOIN: Hubungkan Game ke Publisher --
                         LEFT JOIN game_publishers gp ON g.id = gp.game_id
                         LEFT JOIN publisher p ON gp.publisher_id = p.id
                         
                         -- JOIN: Untuk Filter Genre --
                         LEFT JOIN game_genres gg ON g.id = gg.game_id"; 
        
        $art_wheres = [];
        $art_params = [];

        //  Pencarian Teks (Judul Artikel OR Judul Game OR NAMA PUBLISHER)
        $art_wheres[] = "(a.title LIKE ? OR g.title LIKE ? OR p.name LIKE ?)";
        $art_params[] = $like_query;
        $art_params[] = $like_query;
        $art_params[] = $like_query; // Parameter ke-3 untuk publisher

        // Filter Genre
        if (isset($_GET['genre']) && !empty($_GET['genre'])) {
            $art_wheres[] = "gg.genre_id = ?";
            $art_params[] = $_GET['genre'];
        }

        $sql_articles .= " WHERE " . implode(" AND ", $art_wheres);
        $sql_articles .= " GROUP BY a.id ORDER BY a.created_at DESC"; // Penting agar artikel tidak muncul ganda
        
        $stmt_articles = $pdo->prepare($sql_articles);
        $stmt_articles->execute($art_params);
        $search_articles = $stmt_articles->fetchAll();

        // --- Ambil Game Terkait untuk Artikel Hasil Search ---
        // kumpulkan ID artikel yang ditemukan
        $article_ids = array_column($search_articles, 'id');

        if (!empty($article_ids)) {
            // Siapkan placeholder (?,?,?)
            $placeholders = implode(',', array_fill(0, count($article_ids), '?'));
            
            $sql_map = "SELECT g.id, g.title, ag.article_id 
                        FROM games g
                        JOIN article_games ag ON g.id = ag.game_id
                        WHERE ag.article_id IN ($placeholders)";
            
            $stmt_map = $pdo->prepare($sql_map);
            $stmt_map->execute($article_ids);
            $relations = $stmt_map->fetchAll();

            // Petakan hasilnya: [id_artikel] => [game1, game2]
            foreach ($relations as $row) {
                $article_game_map[$row['article_id']][] = [
                    'id' => $row['id'],
                    'title' => $row['title']
                ];
            }
        }

    } catch (PDOException $e) {
        $error_db = "Terjadi error saat mencari: " . $e->getMessage();
    }
}

$total_games = count($search_games);
$total_articles = count($search_articles);

?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian untuk "<?php echo $search_query; ?>"</title>
</head>
<body class="bg-gray-900 text-white font-sans">

    <?php include 'template/header.php'; ?>

    <div class="container mx-auto px-4 py-16 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Temukan Ulasan. Berikan Penilaian.</h1>
        <form action="search.php" method="GET" class="max-w-2xl mx-auto">
            <div class="relative">
                <input type="search" name="query" class="block w-full p-4 text-lg rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-400" 
                       placeholder="Cari game... (Misal: Elden Ring)" 
                       value="<?php echo $search_query; ?>" required />
                <button type="submit" class="absolute top-0 right-0 h-full p-4 text-white bg-blue-600 rounded-r-lg hover:bg-blue-700">Cari</button>
            </div>
        </form>
    </div>

    <?php if ($error_db): ?>
        <div class="container mx-auto px-4"><div class="bg-red-800 text-white p-4 rounded-lg mb-6"><?php echo $error_db; ?></div></div>
    <?php endif; ?>

    <?php include 'template/game_filter.php'; ?>

    <main class="container mx-auto px-4 pb-12">
        
        <h2 class="text-3xl font-bold mb-6 border-b border-gray-700 pb-2">
            Hasil Pencarian Game (<?php echo $total_games; ?>)
        </h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            <?php 
            if ($total_games == 0) {
                echo "<p class='text-gray-400 col-span-full text-center py-8'>Tidak ada game yang ditemukan.</p>";
            } else {
                foreach ($search_games as $game) {
    
                    // Reset variabel
                    $avg_rating = 0; 
                    $dominant_sentiment = null;

                    // Ambil publisher dan genre
                    $stmt_pubs = $pdo->prepare("SELECT p.name FROM publisher p JOIN game_publishers gp ON p.id = gp.publisher_id WHERE gp.game_id = ?");
                    $stmt_pubs->execute([$game['id']]);
                    $publishers = $stmt_pubs->fetchAll(PDO::FETCH_COLUMN); 
                    
                    $stmt_genres = $pdo->prepare("SELECT g.name FROM genres g JOIN game_genres gg ON g.id = gg.genre_id WHERE gg.game_id = ?");
                    $stmt_genres->execute([$game['id']]);
                    $genres = $stmt_genres->fetchAll(PDO::FETCH_COLUMN);

                    // Hitung rating dan sentimen
                    $stmt_stats = $pdo->prepare("SELECT rating, sentiment FROM reviews WHERE game_id = ?");
                    $stmt_stats->execute([$game['id']]);
                    $stats_data = $stmt_stats->fetchAll();

                    $review_count = count($stats_data);

                    if ($review_count > 0) {
                        $total_rating = array_sum(array_column($stats_data, 'rating'));
                        $avg_rating = round($total_rating / $review_count, 1);

                        $sentiment_counts = ['positive' => 0, 'negative' => 0, 'neutral' => 0];
                        foreach ($stats_data as $row) {
                            if (!empty($row['sentiment'])) {
                                $sentiment_counts[$row['sentiment']]++;
                            }
                        }
                        $dominant_sentiment = array_keys($sentiment_counts, max($sentiment_counts))[0];
                    }

                    include 'template/game_card.php';
                }
            }
            ?>
        </div> 

        <h2 class="text-3xl font-bold mt-16 mb-6 border-b border-gray-700 pb-2">
            Hasil Pencarian Artikel (<?php echo $total_articles; ?>)
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php 
            if ($total_articles == 0) {
                echo "<p class='text-gray-400 col-span-full text-center py-8'>Tidak ada artikel yang ditemukan.</p>";
            } else {
                foreach ($search_articles as $article) {
                    // Ambil data game terkait dari Map
                    $related_games = $article_game_map[$article['id']] ?? [];

                    // Panggil template kartu artikel
                    include 'template/article_card.php';
                }
            }
            ?>
        </div>

    </main>

    <?php include 'template/footer.php'; ?>

</body>
</html>