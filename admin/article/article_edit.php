<?php

// Inisialisasi Variabel
$title = '';
$content = '';
$image_url = '';
$posted_games = [];
$errors = [];

// Tentukan ID Artikel.
$article_id = (int)($_POST['article_id'] ?? $_GET['id'] ?? 0);

if ($article_id <= 0) {
    $_SESSION['error_message'] = "ID Artikel tidak valid.";
    header('Location: index.php?page=manage_articles');
    exit;
}

// Ambil data master untuk form (dibutuhkan di GET dan POST)
$all_games = [];
try {
    $stmt_games = $pdo->query("SELECT id, title FROM games ORDER BY title ASC");
    $all_games = $stmt_games->fetchAll();
} catch (PDOException $e) {
    $errors[] = "Error mengambil data game: " . $e->getMessage();
}


// --- Logika Proses Form (Hanya jika method = POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dari form
    $title = htmlspecialchars($_POST['title']);
    $content = $_POST['content'];
    $image_url = htmlspecialchars($_POST['image_url']);
    $posted_games = $_POST['games'] ?? []; // <-- Ambil game yang baru dicentang

    // Validasi
    if (empty($title)) {
        $errors[] = "Judul artikel wajib diisi.";
    }
    if (empty($content)) {
        $errors[] = "Konten artikel wajib diisi.";
    }

    // Jika tidak ada error
    if (empty($errors)) {
        try {
            // Mulai Transaction
            $pdo->beginTransaction();

            // Langkah 1: UPDATE tabel 'article'
            $sql_article = "UPDATE article SET title = ?, content = ?, image_url = ?
                            WHERE id = ?";
            $stmt_article = $pdo->prepare($sql_article);
            $stmt_article->execute([$title, $content, $image_url, $article_id]);
            
            // Langkah 2: HAPUS SEMUA relasi game LAMA
            $stmt_del_games = $pdo->prepare("DELETE FROM article_games WHERE article_id = ?");
            $stmt_del_games->execute([$article_id]);

            // Langkah 3: INSERT relasi game BARU
            if (!empty($posted_games)) {
                $sql_insert_game = "INSERT INTO article_games (article_id, game_id) VALUES (?, ?)";
                $stmt_insert = $pdo->prepare($sql_insert_game);
                foreach ($posted_games as $game_id) {
                    $stmt_insert->execute([$article_id, (int)$game_id]);
                }
            }

            // Sukses! Simpan perubahan
            $pdo->commit();

            // Redirect dengan pesan sukses
            $_SESSION['success_message'] = "Artikel '". htmlspecialchars($title) ."' berhasil di-update!";
            header('Location: index.php?page=manage_articles');
            exit;

        } catch (PDOException $e) {
            // Gagal! Batalkan semua
            $pdo->rollBack();
            $errors[] = "Database error: " . $e->getMessage();
        }
    }

} else {
    // Logika jika method = GET (Bukan POST)
    // User baru datang, ambil data lama dari DB
    try {
        // Ambil data artikel
        $stmt_article = $pdo->prepare("SELECT title, content, image_url FROM article WHERE id = ?");
        $stmt_article->execute([$article_id]);
        $article = $stmt_article->fetch();

        if (!$article) {
            $_SESSION['error_message'] = "Data artikel (ID: $article_id) tidak ditemukan.";
            header('Location: index.php?page=manage_articles');
            exit;
        }

        // Isi variabel sticky form dari data DB
        $title = $article['title'];
        $content = $article['content'];
        $image_url = $article['image_url'];

        // Ambil game yang SAAT INI terkait dengan artikel ini
        $stmt_games = $pdo->prepare("SELECT game_id FROM article_games WHERE article_id = ?");
        $stmt_games->execute([$article_id]);
        $posted_games = $stmt_games->fetchAll(PDO::FETCH_COLUMN, 0); // Ambil [1, 5]

    } catch (PDOException $e) {
        $errors[] = "Gagal mengambil data artikel: " . $e->getMessage();
    }
}
?>

<a href="index.php?page=manage_articles" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Manajemen Artikel</a>
<h1 class="text-3xl font-bold mb-6">Edit Artikel: <?php echo htmlspecialchars($title); ?></h1>

<?php
if (!empty($errors)) {
    echo '<div class="bg-red-800 border border-red-700 text-white p-4 rounded-lg mb-4">';
    echo '<ul class="list-disc pl-5">';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul></div>';
}
?>

<div class="bg-gray-700 rounded-lg shadow-lg p-8">
    <form action="index.php?page=article_edit" method="POST">
        
        <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
        
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Judul Artikel</label>
            <input type="text" id="title" name="title"
                   value="<?php echo htmlspecialchars($title); ?>"
                   class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500" required>
        </div>

        <div class="mb-4">
            <label for="image_url" class="block text-sm font-medium text-gray-300 mb-2">URL Gambar Header</label>
            <input type="text" id="image_url" name="image_url"
                   value="<?php echo htmlspecialchars($image_url); ?>"
                   class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500" placeholder="https://...">
        </div>
        
        <div class="mb-4">
            <label for="content" class="block text-sm font-medium text-gray-300 mb-2">Konten Artikel</label>
            <textarea id="content" name="content" rows="15"
                      class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
                      required><?php echo htmlspecialchars($content); ?></textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-300 mb-2">Game Terkait (Opsional)</label>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 max-h-48 overflow-y-auto bg-gray-600 p-2 rounded-lg">
                <?php foreach ($all_games as $game): ?>
                    <?php 
                    // Cek apakah game ini harus dicentang
                    $is_checked = in_array($game['id'], $posted_games); 
                    ?>
                    <label class="flex items-center space-x-2 bg-gray-700 p-2 rounded-lg">
                        <input type="checkbox" name="games[]" value="<?php echo $game['id']; ?>"
                               class="rounded" <?php if ($is_checked) echo 'checked'; ?>>
                        <span><?php echo htmlspecialchars($game['title']); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="flex justify-end space-x-4 mt-6">
            <a href="index.php?page=manage_articles" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-500">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">
                Update Artikel
            </button>
        </div>

    </form>
</div>