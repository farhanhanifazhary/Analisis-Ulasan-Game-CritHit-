<?php
// Selalu mulai session (anti-notice)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Masukkan file koneksi
require 'koneksi.php';

// Ambil semua data artikel
$articles = [];
try {
    // Ambil semua artikel, JOIN dengan tabel users untuk dapat nama penulis
    // Urutkan berdasarkan yang terbaru
    $sql = "SELECT 
                a.id, 
                a.title, 
                a.content, 
                a.image_url, 
                a.created_at, 
                u.name AS author_name 
            FROM article a
            JOIN users u ON a.user_id = u.id
            ORDER BY a.created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $articles = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $error_db = "Error mengambil data artikel: " . $e->getMessage();
}

/**
 * Fungsi helper untuk memotong teks (membuat "snippet")
 * Ini ditaruh di sini agar file-nya mandiri.
 */
function get_snippet($text, $length = 200) {
    $text = strip_tags($text); // Hapus tag HTML jika ada
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' ')); // Potong di spasi terakhir
        $text .= '...'; // Tambahkan elipsis
    }
    return $text;
}

?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita & Artikel - CritHit</title>
</head>
<body class="bg-gray-900 text-white font-sans">

    <?php include 'template/header.php'; ?>

    <main class="container mx-auto px-4 py-12">
        
        <h1 class="text-4xl font-bold mb-8 border-b border-gray-700 pb-4">
            Berita & Artikel Terbaru
        </h1>

        <?php if (isset($error_db)): ?>
            <div class="bg-red-800 text-white p-4 rounded-lg mb-6">
                <?php echo $error_db; ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php if (empty($articles)): ?>
                <p class="text-gray-400 col-span-full text-center py-8">
                    Belum ada artikel yang dipublikasikan.
                </p>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    
                    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden flex flex-col">
                        <a href="article_detail.php?id=<?php echo $article['id']; ?>">
                            <img 
                                src="<?php echo htmlspecialchars($article['image_url']); ?>" 
                                alt="<?php echo htmlspecialchars($article['title']); ?>"
                                class="w-full h-48 object-cover"
                            />
                        </a>
                        
                        <div class="p-6 flex flex-col flex-grow">
                            <h2 class="text-2xl font-bold text-white mb-2">
                                <a href="article_detail.php?id=<?php echo $article['id']; ?>" class="hover:text-blue-400 transition-colors">
                                    <?php echo htmlspecialchars($article['title']); ?>
                                </a>
                            </h2>
                            
                            <div class="text-sm text-gray-400 mb-4">
                                Oleh <?php echo htmlspecialchars($article['author_name']); ?> 
                                <span class="mx-1">|</span>
                                <?php echo date('d F Y', strtotime($article['created_at'])); ?>
                            </div>

                            <p class="text-gray-300 mb-4 flex-grow">
                                <?php 
                                // Panggil fungsi helper untuk membuat snippet
                                echo htmlspecialchars(get_snippet($article['content'], 150)); 
                                ?>
                            </p>
                            
                            <a href="article_detail.php?id=<?php echo $article['id']; ?>" class="text-blue-400 font-bold hover:text-blue-300 self-start">
                                Baca Selengkapnya &rarr;
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
            <?php endif; ?>
            
        </div> </main>

    <?php include 'template/footer.php'; ?>

</body>
</html>