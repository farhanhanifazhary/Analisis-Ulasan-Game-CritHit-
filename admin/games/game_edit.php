<?php

// Inisialisasi Variabel
$errors = []; // Array untuk menampung error validasi

// Tentukan ID game. Ambil dari POST jika ada (saat validasi gagal),
// jika tidak, ambil dari GET (saat pertama kali dibuka).
$game_id = (int)($_POST['game_id'] ?? $_GET['id'] ?? 0);

if ($game_id <= 0) {
    $_SESSION['error_message'] = "ID Game tidak valid.";
    header('Location: index.php?page=manage_games');
    exit;
}

// Ambil data master untuk form (dibutuhkan di GET dan POST)
$all_genres = [];
$all_publishers = [];
try {
    $stmt_genres = $pdo->query("SELECT * FROM genres ORDER BY name ASC");
    $all_genres = $stmt_genres->fetchAll();
    
    $stmt_pubs = $pdo->query("SELECT * FROM publisher ORDER BY name ASC");
    $all_publishers = $stmt_pubs->fetchAll();
} catch (PDOException $e) {
    $errors[] = "Error mengambil data master: " . $e->getMessage();
}

// Inisialisasi variabel sticky form
$title = '';
$description = '';
$release_date = '';
$image_url = '';
$posted_genres = [];
$posted_publishers = [];


// Logika Proses Form (Hanya jika method = POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil data dari form
    $title = htmlspecialchars($_POST['title'] ?? '');
    $description = htmlspecialchars($_POST['description'] ?? '');
    $release_date = $_POST['release_date'] ?? null;
    $image_url = htmlspecialchars($_POST['image_url'] ?? '');
    $posted_genres = $_POST['genres'] ?? [];
    $posted_publishers = $_POST['publishers'] ?? []; // <-- DATA BARU

    // Validasi
    if (empty($title)) {
        $errors[] = "Judul game wajib diisi.";
    }
    if (empty($posted_publishers)) {
        $errors[] = "Minimal satu publisher harus dipilih.";
    }

    // Jikda tidak ada error
    if (empty($errors)) {
        try {
            // Mulai Transaction
            $pdo->beginTransaction();

            // Langkah 1: UPDATE tabel 'games'
            $sql_game = "UPDATE games SET title = ?, description = ?, release_date = ?, image_url = ?
                         WHERE id = ?";
            $stmt_game = $pdo->prepare($sql_game);
            $stmt_game->execute([$title, $description, $release_date, $image_url, $game_id]);
            
            // Langkah 2: HAPUS SEMUA relasi publisher LAMA
            $stmt_del_pubs = $pdo->prepare("DELETE FROM game_publishers WHERE game_id = ?");
            $stmt_del_pubs->execute([$game_id]);
            
            // Langkah 3: HAPUS SEMUA relasi genre LAMA
            $stmt_del_genres = $pdo->prepare("DELETE FROM game_genres WHERE game_id = ?");
            $stmt_del_genres->execute([$game_id]);

            // Langkah 4: INSERT relasi publisher BARU
            if (!empty($posted_publishers)) {
                $sql_pub = "INSERT INTO game_publishers (game_id, publisher_id) VALUES (?, ?)";
                $stmt_pub = $pdo->prepare($sql_pub);
                foreach ($posted_publishers as $pub_id) {
                    $stmt_pub->execute([$game_id, (int)$pub_id]);
                }
            }
            
            // Langkah 5: INSERT relasi genre BARU
            if (!empty($posted_genres)) {
                $sql_genre = "INSERT INTO game_genres (game_id, genre_id) VALUES (?, ?)";
                $stmt_genre = $pdo->prepare($sql_genre);
                foreach ($posted_genres as $genre_id) {
                    $stmt_genre->execute([$game_id, (int)$genre_id]);
                }
            }

            // Sukses! Simpan perubahan
            $pdo->commit();

            // Redirect dengan pesan sukses
            $_SESSION['success_message'] = "Game '". $title ."' (ID: $game_id) berhasil di-update!";
            header('Location: index.php?page=manage_games');
            exit;

        } catch (PDOException $e) {
            // Gagal! Batalkan semua
            $pdo->rollBack();
            $errors[] = "Database error: " . $e->getMessage();
        }
    }

} else {
    // Logika jika method = GET
    // User baru datang, ambil data LAMA dari DB
    try {
        // Ambil data game
        $stmt_game = $pdo->prepare("SELECT * FROM games WHERE id = ?");
        $stmt_game->execute([$game_id]);
        $game = $stmt_game->fetch();

        if (!$game) {
            $_SESSION['error_message'] = "Data game (ID: $game_id) tidak ditemukan.";
            header('Location: index.php?page=manage_games');
            exit;
        }

        // Isi variabel sticky form dari data DB
        $title = $game['title'];
        $description = $game['description'];
        $release_date = $game['release_date'];
        $image_url = $game['image_url'];

        // Ambil genre game ini
        $stmt_genres = $pdo->prepare("SELECT genre_id FROM game_genres WHERE game_id = ?");
        $stmt_genres->execute([$game_id]);
        $posted_genres = $stmt_genres->fetchAll(PDO::FETCH_COLUMN, 0); // Ambil [1, 3, 5]

        //Ambil publisher game ini
        $stmt_pubs = $pdo->prepare("SELECT publisher_id FROM game_publishers WHERE game_id = ?");
        $stmt_pubs->execute([$game_id]);
        $posted_publishers = $stmt_pubs->fetchAll(PDO::FETCH_COLUMN, 0); // Ambil [1, 2]

    } catch (PDOException $e) {
        $errors[] = "Gagal mengambil data game: " . $e->getMessage();
    }
}
?>

<a href="index.php?page=manage_games" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Manajemen Game</a>
<h1 class="text-3xl font-bold mb-6">Edit Game: <?php echo htmlspecialchars($title); ?></h1>

<?php
if (!empty($errors)) {
    echo '<div class="bg-red-800 border border-red-700 text-white p-4 rounded-lg mb-4">';
    echo '<ul class="list-disc pl-5">';
    foreach ($errors as $error) {
        echo '<li>' . htmlspecialchars($error) . '</li>';
    }
    echo '</ul>';
    echo '</div>';
}
?>

<div class="bg-gray-700 p-8 rounded-lg shadow-lg">
    <form action="index.php?page=game_edit" method="POST">
        
        <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
        
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-300 mb-2">Judul Game</label>
            <input type="text" id="title" name="title"
                   value="<?php echo htmlspecialchars($title); ?>"
                   class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500" required>
        </div>

        <div class="mb-4">
            <label for="release_date" class="block text-sm font-medium text-gray-300 mb-2">Tanggal Rilis</label>
            <input type="date" id="release_date" name="release_date"
                   value="<?php echo htmlspecialchars($release_date); ?>"
                   class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500">
        </div>

        <div class="mb-4">
            <label for="image_url" class="block text-sm font-medium text-gray-300 mb-2">URL Gambar Sampul</label>
            <input type="text" id="image_url" name="image_url"
                   value="<?php echo htmlspecialchars($image_url); ?>"
                   class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
                   placeholder="https://...">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-300 mb-2">Deskripsi</label>
            <textarea id="description" name="description" rows="5"
                      class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"><?php echo htmlspecialchars($description); ?></textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-300 mb-2">Publisher (Wajib pilih min. 1)</label>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 max-h-48 overflow-y-auto bg-gray-600 p-2 rounded-lg">
                <?php foreach ($all_publishers as $pub): ?>
                    <?php $is_checked = in_array($pub['id'], $posted_publishers); ?>
                    <label class="flex items-center space-x-2 bg-gray-700 p-2 rounded-lg">
                        <input type="checkbox" name="publishers[]" value="<?php echo $pub['id']; ?>"
                               class="rounded" <?php if ($is_checked) echo 'checked'; ?>>
                        <span><?php echo htmlspecialchars($pub['name']); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-300 mb-2">Genre</label>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 max-h-48 overflow-y-auto bg-gray-600 p-2 rounded-lg">
                <?php foreach ($all_genres as $genre): ?>
                    <?php $is_checked = in_array($genre['id'], $posted_genres); ?>
                    <label class="flex items-center space-x-2 bg-gray-700 p-2 rounded-lg">
                        <input type="checkbox" name="genres[]" value="<?php echo $genre['id']; ?>"
                               class="rounded" <?php if ($is_checked) echo 'checked'; ?>>
                        <span><?php echo htmlspecialchars($genre['name']); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="index.php?page=manage_games" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-500">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">
                Update Game
            </button>
        </div>

    </form>
</div>