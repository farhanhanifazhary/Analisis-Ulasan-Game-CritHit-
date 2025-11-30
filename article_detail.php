<?php
// Selalu mulai session (anti-notice)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Masukkan file koneksi
require 'koneksi.php';

// Ambil ID Artikel dari URL
$article_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($article_id <= 0) {
    // Jika ID tidak valid, tendang ke homepage
    header('Location: index.php');
    exit;
}

// Ambil data artikel spesifik
$article = null;
$related_games = [];

try {
    // Ambil artikel, JOIN dengan users untuk dapat nama penulis
    $sql = "SELECT 
                a.title, 
                a.content, 
                a.image_url, 
                a.created_at, 
                u.name AS author_name 
            FROM article a
            JOIN users u ON a.user_id = u.id
            WHERE a.id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$article_id]);
    $article = $stmt->fetch();
    
    // Jika artikel dengan ID itu tidak ditemukan
    if (!$article) {
        // Kita bisa buat halaman 404, tapi untuk sekarang:
        die("Artikel tidak ditemukan!");
    }

    // Kueri 2: Ambil Game Terkait (Hanya ID dan Judul)
    $sql_games = "SELECT g.id, g.title
                  FROM games g
                  JOIN article_games ag ON g.id = ag.game_id
                  WHERE ag.article_id = ?";
    $stmt_games = $pdo->prepare($sql_games);
    $stmt_games->execute([$article_id]);
    $related_games = $stmt_games->fetchAll(); // Ambil semua game terkait

} catch (PDOException $e) {
    die("Error mengambil data artikel: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?> - CritHit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
</head>
<body class="bg-gray-900 text-white font-sans">

    <?php include 'template/header.php'; ?>

    <main class="container mx-auto px-4 py-12 max-w-4xl"> <h1 class="text-4xl md:text-5xl font-bold mb-4">
            <?php echo htmlspecialchars($article['title']); ?>
        </h1>

        <?php if (!empty($related_games)): ?>
            <div class="mb-4 text-lg text-gray-400">
                <?php
                // buat array berisi link, lalu gabungkan dengan koma
                $game_links = [];
                foreach ($related_games as $game) {
                    $game_links[] = '<a href="detail.php?id='.$game['id'].'" class="text-blue-400 hover:underline">'.htmlspecialchars($game['title']).'</a>';
                }
                echo implode(', ', $game_links);
                ?>
            </div>
        <?php endif; ?>
        
        <div class="text-lg text-gray-400 mb-6 border-b border-gray-700 pb-4">
            Oleh <?php echo htmlspecialchars($article['author_name']); ?>
            <span class="mx-2">|</span>
            Dipublikasikan pada <?php echo date('d F Y', strtotime($article['created_at'])); ?>
        </div>
        
        <img 
            src="<?php echo htmlspecialchars($article['image_url']); ?>" 
            alt="<?php echo htmlspecialchars($article['title']); ?>"
            class="w-full h-auto max-h-[500px] object-cover rounded-lg shadow-lg mb-8"
        />

        <div class="prose prose-invert lg:prose-xl max-w-none text-gray-300 leading-relaxed">
            <?php 
                // nl2br() PENTING!
                // Ini mengubah baris baru (\n) yang disimpan di DB
                // menjadi tag HTML <br> agar paragrafnya tidak menyatu
                echo nl2br(htmlspecialchars($article['content'])); 
            ?>
        </div>

    </main>

    <?php include 'template/footer.php'; ?>

</body>
</html>