<?php

// Inisialisasi Variabel
$title = '';
$content = '';
$image_url = '';
$posted_games = [];
$errors = [];

// Ambil data master untuk form
$all_games = [];
try {
    // Ambil semua game untuk ditampilkan di checkbox
    $stmt_games = $pdo->query("SELECT id, title FROM games ORDER BY title ASC");
    $all_games = $stmt_games->fetchAll();
} catch (PDOException $e) {
    $errors[] = "Error mengambil data game: " . $e->getMessage();
}


// Logika Proses Form (Hanya jika method = POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dari form
    $title = htmlspecialchars($_POST['title']);
    $content = $_POST['content'];
    $image_url = htmlspecialchars($_POST['image_url']);
    $user_id = $_SESSION['user_id']; 
    $posted_games = $_POST['games'] ?? [];

    // Validasi
    if (empty($title)) {
        $errors[] = "Judul artikel wajib diisi.";
    }
    if (empty($content)) {
        $errors[] = "Konten artikel wajib diisi.";
    }

    // Jika tidak ada error (Validasi Lolos)
    if (empty($errors)) {
        try {
            $pdo->beginTransaction(); // Mulai Transaction

            // Langkah 1: Masukkan ke tabel 'article'
            $sql_insert = "INSERT INTO article (user_id, title, content, image_url) 
                           VALUES (?, ?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$user_id, $title, $content, $image_url]);
            
            // Dapatkan ID artikel yang baru saja di-insert
            $new_article_id = $pdo->lastInsertId();

            // Langkah 2: Masukkan ke tabel 'article_games'
            if (!empty($posted_games)) {
                $sql_game_rel = "INSERT INTO article_games (article_id, game_id) VALUES (?, ?)";
                $stmt_game_rel = $pdo->prepare($sql_game_rel);
                foreach ($posted_games as $game_id) {
                    $stmt_game_rel->execute([$new_article_id, (int)$game_id]);
                }
            }

            $pdo->commit(); // Kunci semua perubahan

            // Beri pesan sukses dan REDIRECT
            $_SESSION['success_message'] = "Artikel '". htmlspecialchars($title) ."' berhasil dipublikasikan!";
            header('Location: index.php?page=manage_articles');
            exit; 

        } catch (PDOException $e) {
            $pdo->rollBack(); // Batalkan semua jika ada error
            $errors[] = "Gagal menyimpan ke database: " . $e->getMessage();
        }
    }
}
?>

<a href="index.php?page=manage_articles" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Manajemen Artikel</a>
<h1 class="text-3xl font-bold mb-6">Tulis Artikel Baru</h1>

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
    <form action="index.php?page=article_tambah" method="POST">
        
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
                    <?php $is_checked = in_array($game['id'], $posted_games); ?>
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
                Publikasikan
            </button>
        </div>

    </form>
</div>