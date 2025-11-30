<?php
// Selalu mulai session di awal
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Masukkan file koneksi
require 'koneksi.php';

// Ambil ID user dari session
$user_id = (int)$_SESSION['user_id'];

// Ambil semua data game yang difavoritkan user ini
$favorite_games = [];
try {
    // JOIN tabel games (g) dengan favorites (f)
    // dimana f.user_id cocok dengan user yang sedang login
    $sql = "SELECT g.* FROM games g
            JOIN favorites f ON g.id = f.game_id
            WHERE f.user_id = ?
            ORDER BY f.id DESC"; // Tampilkan yang terbaru difavoritkan

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $favorite_games = $stmt->fetchAll();

} catch (PDOException $e) {
    // Tangani error jika query gagal
    $error_db = "Error mengambil data favorit: " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Favorit Saya - CritHit</title>
</head>
<body class="bg-gray-900 text-white font-sans">

    <?php 
        // Masukkan header
        include 'template/header.php'; 
    ?>

    <main class="container mx-auto px-4 py-12">
        
        <h1 class="text-3xl font-bold mb-6 border-b border-gray-700 pb-2">
            Game Favorit Saya
        </h1>

        <?php if (isset($error_db)): ?>
            <div class="bg-red-800 text-white p-4 rounded-lg mb-4">
                <?php echo $error_db; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">

            <?php if (empty($favorite_games)): ?>
                <p class="text-gray-400 col-span-full text-center py-8">
                    Anda belum memfavoritkan game apapun.
                </p>
            <?php else: ?>
                <?php 
                // Looping
                foreach ($favorite_games as $game):
                    
                    // Reset Variabel
                    $avg_rating = 0; 
                    $dominant_sentiment = null;

                    // Ambil Publisher
                    $stmt_pubs = $pdo->prepare("SELECT p.name FROM publisher p JOIN game_publishers gp ON p.id = gp.publisher_id WHERE gp.game_id = ?");
                    $stmt_pubs->execute([$game['id']]);
                    $publishers = $stmt_pubs->fetchAll(PDO::FETCH_COLUMN); 

                    // Ambil Genre
                    $stmt_genres = $pdo->prepare("SELECT g.name FROM genres g JOIN game_genres gg ON g.id = gg.genre_id WHERE gg.game_id = ?");
                    $stmt_genres->execute([$game['id']]);
                    $genres = $stmt_genres->fetchAll(PDO::FETCH_COLUMN);

                    // Hitung Rating dan Sentimen
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

                    // Panggil template
                    include 'template/game_card.php';

                endforeach; 
                ?>
            <?php endif; ?>
            
        </div> </main>

    <?php 
        // Masukkan footer
        include 'template/footer.php'; 
    ?>

</body>
</html>