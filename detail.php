<?php
// Selalu mulai session (anti-notice)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Masukkan file koneksi
require 'koneksi.php';

// Ambil ID Game
$game_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($game_id <= 0) {
    header('Location: index.php');
    exit;
}

// Inisialisasi Variabel
$game = null;
$genres = [];
$publishers = [];
$reviews = [];
$is_favorited = false;
$related_articles = [];
$user_review = null;

// Cek status login dan role untuk tombol/form
$is_logged_in = isset($_SESSION['user_id']);
$user_role = $_SESSION['user_role'] ?? 'user';
$user_id = $_SESSION['user_id'] ?? 0;

// Cek apakah sedang dalam mode edit
$edit_mode = isset($_GET['action']) && $_GET['action'] === 'edit';

try {
    // Kueri 1: Ambil data game utama
    $sql_game = "SELECT * FROM games WHERE id = ?";
    $stmt_game = $pdo->prepare($sql_game);
    $stmt_game->execute([$game_id]);
    $game = $stmt_game->fetch();

    if (!$game) {
        die("Game tidak ditemukan!");
    }

    // Kueri 2: Ambil data genre
    $sql_genres = "SELECT g.name 
                   FROM genres g
                   JOIN game_genres gg ON g.id = gg.genre_id
                   WHERE gg.game_id = ?";
    $stmt_genres = $pdo->prepare($sql_genres);
    $stmt_genres->execute([$game_id]);
    $genres = $stmt_genres->fetchAll(PDO::FETCH_COLUMN); // Ambil sebagai array ['RPG', 'Action']

    // Kueri 3: Ambil SEMUA publisher untuk game ini
    $sql_pubs = "SELECT p.name 
                 FROM publisher p
                 JOIN game_publishers gp ON p.id = gp.publisher_id
                 WHERE gp.game_id = ?";
    $stmt_pubs = $pdo->prepare($sql_pubs);
    $stmt_pubs->execute([$game_id]);
    $publishers = $stmt_pubs->fetchAll(PDO::FETCH_COLUMN); // Ambil sebagai array ['Publisher A', 'Publisher B']

    // Kueri 4: Ulasan Publik
    // PENTING: Tambahkan 'AND r.user_id != ?' untuk menyembunyikan ulasan sendiri
    $sql_reviews = "SELECT r.rating, r.comment_text, r.created_at, u.name AS user_name 
                    FROM reviews r JOIN users u ON r.user_id = u.id
                    WHERE r.game_id = ? AND r.user_id != ? 
                    ORDER BY r.created_at DESC";
    
    $stmt_reviews = $pdo->prepare($sql_reviews);
    
    // Masukkan $user_id ke parameter kedua (untuk pengecualian)
    $stmt_reviews->execute([$game_id, $user_id]); 
    
    $reviews = $stmt_reviews->fetchAll();

    // Kueri 5: Cek apakah user ini sudah memfavoritkan game ini
    if ($is_logged_in && $user_role === 'user') {
        $sql_fav_check = "SELECT id FROM favorites WHERE user_id = ? AND game_id = ?";
        $stmt_fav_check = $pdo->prepare($sql_fav_check);
        $stmt_fav_check->execute([$user_id, $game_id]);
        if ($stmt_fav_check->rowCount() > 0) {
            $is_favorited = true; // Set status jadi true
        }
    }

    // Kueri 6: Ambil SEMUA artikel yang terkait dengan game ini
    $sql_articles = "SELECT a.id, a.title, a.image_url
                     FROM article a
                     JOIN article_games ag ON a.id = ag.article_id
                     WHERE ag.game_id = ?
                     ORDER BY a.created_at DESC
                     LIMIT 6"; // Batasi 6 artikel saja
    $stmt_articles = $pdo->prepare($sql_articles);
    $stmt_articles->execute([$game_id]);
    $related_articles = $stmt_articles->fetchAll();

    // Kueri 7: Cek apakah user ini sudah pernah mengulas
    if ($is_logged_in && $user_role === 'user') {
        $sql_user_review = "SELECT id, rating, comment_text, sentiment, created_at FROM reviews WHERE user_id = ? AND game_id = ?";
        $stmt_user_review = $pdo->prepare($sql_user_review);
        $stmt_user_review->execute([$user_id, $game_id]);
        $user_review = $stmt_user_review->fetch(); // Ambil ulasan (atau 'false' jika tidak ada)
    }

    // --- Kueri 8: Hitung Rating & Sentimen untuk Game Ini ---
    $stmt_stats = $pdo->prepare("SELECT rating, sentiment FROM reviews WHERE game_id = ?");
    $stmt_stats->execute([$game_id]);
    $stats_data = $stmt_stats->fetchAll();

    $avg_rating = 0;
    $dominant_sentiment = null;
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
        // Ambil kunci dengan nilai tertinggi
        $dominant_sentiment = array_keys($sentiment_counts, max($sentiment_counts))[0];
    }
    // --- AKHIR KODE BARU ---

} catch (PDOException $e) {
    die("Error mengambil data: " . $e->getMessage());
}

// Cek status login dan role untuk form ulasan
$is_logged_in = isset($_SESSION['user_id']);
$user_role = $_SESSION['user_role'] ?? 'user';

?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($game['title']); ?> - CritHit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-900 text-white font-sans">

    <?php include 'template/header.php'; ?>

    <main class="container mx-auto px-4 py-12">
        <div class="flex flex-col md:flex-row gap-8">
            
            <div class="w-full md:w-1/3">
                <img 
                    src="<?php echo htmlspecialchars($game['image_url'] ?? 'https://via.placeholder.com/400x500.png?text=No+Image'); ?>" 
                    alt="<?php echo htmlspecialchars($game['title']); ?>"
                    class="w-full h-auto rounded-lg shadow-lg"
                />
            </div>

            <div class="w-full md:w-2/3">
                <h1 class="text-4xl md:text-5xl font-bold mb-2">
                    <?php echo htmlspecialchars($game['title']); ?>
                </h1>
                
                <div class="text-xl text-gray-400 mb-4">
                    Oleh 
                    <?php 
                    if (!empty($publishers)) {
                        echo htmlspecialchars(implode(', ', $publishers));
                    } else {
                        echo 'Publisher Tidak Diketahui';
                    }
                    ?>
                </div>
                <div class="mb-6">
                    <?php if (!empty($genres)): ?>
                        <?php foreach ($genres as $genre_name): ?>
                            <span class.="bg-gray-700 text-gray-300 text-sm font-medium mr-2 px-2.5 py-0.5 rounded">
                                <?php echo htmlspecialchars($genre_name); ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-gray-500">Genre tidak terdaftar</span>
                    <?php endif; ?>
                </div>

                <p class="text-lg text-gray-300 leading-relaxed mb-4">
                    Tanggal Rilis: <?php echo date('d F Y', strtotime($game['release_date'])); ?>
                </p>

                <?php if ($is_logged_in && $user_role === 'user'): ?>
                    <div class="mt-4 mb-6">
                        <form action="favorite_proses.php" method="POST">
                            <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
                            <?php if ($is_favorited): ?>
                                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-700 transition-colors">
                                    <i class="fas fa-heart-crack mr-2"></i> Hapus dari Favorit
                                </button>
                            <?php else: ?>
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-700 transition-colors">
                                    <i class="fas fa-heart mr-2"></i> Tambah ke Favorit
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                <?php endif; ?>

                <h2 class="text-2xl font-bold mt-8 mb-3">Deskripsi</h2>
                <div class="prose prose-invert max-w-none text-gray-300 leading-relaxed">
                    <?php echo nl2br(htmlspecialchars($game['description'])); ?>
                </div>
                <div class="flex items-center gap-2 justify-end mt-3">
                    <?php if ($avg_rating > 0): ?>
                        
                        <?php if ($dominant_sentiment): ?>
                            <?php
                                $badge_color = 'bg-gray-600 text-gray-200'; 
                                $icon = 'fa-minus';
                                if ($dominant_sentiment === 'positive') {
                                    $badge_color = 'bg-green-900 text-green-300 border border-green-700';
                                    $icon = 'fa-smile';
                                } elseif ($dominant_sentiment === 'negative') {
                                    $badge_color = 'bg-red-900 text-red-300 border border-red-700';
                                    $icon = 'fa-frown';
                                }
                            ?>
                            <div class="<?php echo $badge_color; ?> px-3 py-1.5 rounded-lg text-sm font-semibold flex items-center gap-2 shadow-xl backdrop-blur-sm bg-opacity-90">
                                <i class="fas <?php echo $icon; ?>"></i>
                                <span class="capitalize"><?php echo $dominant_sentiment; ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="bg-gray-900 bg-opacity-90 px-3 py-1.5 rounded-lg text-lg font-bold text-yellow-400 flex items-center gap-1 shadow-xl border border-gray-700">
                            <i class="fas fa-star"></i>
                            <span><?php echo $avg_rating; ?></span>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>

        
    </main>
    <?php if (!empty($related_articles)): // Hanya tampilkan jika ada artikel terkait ?>
    <section class="container mx-auto px-4 pb-12">
        <h2 class="text-3xl font-bold mb-6 border-b border-gray-700 pb-2">Artikel Terkait</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($related_articles as $article): ?>
                <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden group">
                    <a href="article_detail.php?id=<?php echo $article['id']; ?>" class="block">
                        <img src="<?php echo htmlspecialchars($article['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($article['title']); ?>" 
                             class="w-full h-40 object-cover group-hover:opacity-75 transition-opacity"/>
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-white truncate group-hover:text-blue-400">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </h3>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section id="reviews" class="container mx-auto px-4 pb-16">
        <div class="bg-gray-800 rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-bold mb-6">Ulasan Pengguna</h2>
            
            <div class="mb-10">
                <?php if ($is_logged_in && $user_role === 'user'): ?>
                    
                    <?php if ($user_review && !$edit_mode): ?>
                        <div class="bg-blue-900 bg-opacity-20 border border-blue-700 rounded-lg p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-blue-300 mb-1">Ulasan Anda</h3>
                                    <p class="text-sm text-gray-400">Diposting pada <?php echo date('d F Y', strtotime($user_review['created_at'])); ?></p>
                                </div>
                                <div class="text-yellow-400 text-lg">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?php echo ($i <= $user_review['rating']) ? 'fas' : 'far'; ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            
                            <p class="text-gray-200 text-lg mb-6 italic">
                                "<?php echo nl2br(htmlspecialchars($user_review['comment_text'])); ?>"
                            </p>

                            <div class="flex space-x-4">
                                <a href="detail.php?id=<?php echo $game_id; ?>&action=edit#reviews" 
                                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 font-medium transition-colors">
                                    <i class="fas fa-edit mr-2"></i> Edit Ulasan
                                </a>
                                <a href="review_hapus.php?id=<?php echo $user_review['id']; ?>" 
                                   class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 font-medium transition-colors"
                                   onclick="return confirm('Yakin ingin menghapus ulasan Anda?');">
                                    <i class="fas fa-trash-alt mr-2"></i> Hapus
                                </a>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="bg-gray-700 rounded-lg p-6 border border-gray-600">
                            <h3 class="text-2xl font-bold mb-4 text-white">
                                <?php echo ($user_review) ? 'Edit Ulasan Anda' : 'Beri Ulasan'; ?>
                            </h3>
                            
                            <form action="review_proses.php" method="POST">
                                <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
                                <input type="hidden" name="action" value="<?php echo ($user_review) ? 'update' : 'create'; ?>">

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Rating Anda</label>
                                    <div class="flex flex-row-reverse justify-end text-2xl text-gray-500 w-fit">
                                        <?php for ($i = 5; $i >= 1; $i--): 
                                            $checked = ($user_review && $user_review['rating'] == $i) ? 'checked' : ''; 
                                        ?>
                                            <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" class="hidden peer" <?php echo $checked; ?> required>
                                            <label for="star<?php echo $i; ?>" class="peer-hover:text-yellow-400 peer-checked:text-yellow-400 cursor-pointer hover:scale-110 transition-transform"><i class="fas fa-star"></i></label>
                                        <?php endfor; ?>
                                    </div>
                                    <style> input:checked ~ label, label:hover, label:hover ~ label { color: #FACC15; } </style>
                                </div>

                                <div class="mb-4">
                                    <label for="comment_text" class="block text-sm font-medium text-gray-300 mb-2">Ulasan Anda</label>
                                    <textarea id="comment_text" name="comment_text" rows="4" 
                                              class="block w-full p-3 rounded-lg bg-gray-800 border border-gray-500 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none" 
                                              placeholder="Ceritakan pengalaman Anda..." required><?php echo htmlspecialchars($user_review['comment_text'] ?? ''); ?></textarea>
                                </div>

                                <div class="flex justify-between items-center">
                                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition-colors">
                                        <?php echo ($user_review) ? 'Simpan Perubahan' : 'Kirim Ulasan'; ?>
                                    </button>
                                    
                                    <?php if ($edit_mode): ?>
                                        <a href="detail.php?id=<?php echo $game_id . '#reviews'; ?>" class="text-gray-400 hover:text-white">Batal</a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>

                <?php elseif ($is_logged_in && $user_role === 'admin'): ?>
                    <div class="bg-gray-700 p-4 rounded text-center text-gray-400">Admin tidak dapat memberi ulasan.</div>
                <?php else: ?>
                    <div class="bg-gray-700 p-4 rounded text-center text-gray-400">Silakan <a href="login.php" class="text-blue-400 hover:underline">Login</a> untuk memberi ulasan.</div>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <?php if (empty($reviews)): ?>
                    <p class="text-gray-400 text-center py-4">Belum ada ulasan untuk game ini. Jadilah yang pertama!</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="border-b border-gray-700 pb-4">
                            <div class="flex items-center mb-2">
                                <span class="font-bold text-lg"><?php echo htmlspecialchars($review['user_name']); ?></span>
                                <span class="text-yellow-400 ml-3">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="<?php echo ($i <= $review['rating']) ? 'fas' : 'far'; ?> fa-star"></i>
                                    <?php endfor; ?>
                                </span>
                            </div>
                            <p class="text-gray-300 mb-2"><?php echo nl2br(htmlspecialchars($review['comment_text'])); ?></p>
                            <p class="text-sm text-gray-500">Ditulis pada <?php echo date('d F Y', strtotime($review['created_at'])); ?></p>
                        </div> 
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'template/footer.php'; ?>
</body>
</html>