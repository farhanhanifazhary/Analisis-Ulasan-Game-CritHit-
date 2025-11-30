<?php

// Cek apakah ID game ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Permintaan tidak valid. ID Game tidak ditemukan.";
    header('Location: index.php?page=manage_games');
    exit;
}

$game_id = (int)$_GET['id'];

// Database Transactional
// Menghapus data dalam urutan yang benar (dari tabel anak ke tabel induk)
try {
    // Mulai "mode aman"
    $pdo->beginTransaction();

    // --- Langkah 1: Hapus dari 'game_genres' ---
    $stmt_genres = $pdo->prepare("DELETE FROM game_genres WHERE game_id = ?");
    $stmt_genres->execute([$game_id]);

    // --- Langkah 2: Hapus dari 'game_publishers' (BARU) ---
    $stmt_pubs = $pdo->prepare("DELETE FROM game_publishers WHERE game_id = ?");
    $stmt_pubs->execute([$game_id]);

    // --- Langkah 3: Hapus dari 'reviews' ---
    $stmt_reviews = $pdo->prepare("DELETE FROM reviews WHERE game_id = ?");
    $stmt_reviews->execute([$game_id]);

    // --- Langkah 4: Hapus dari 'favorites' ---
    $stmt_fav = $pdo->prepare("DELETE FROM favorites WHERE game_id = ?");
    $stmt_fav->execute([$game_id]);

    // --- Langkah 5): Hapus dari 'games' ---
    $stmt_game = $pdo->prepare("DELETE FROM games WHERE id = ?");
    $stmt_game->execute([$game_id]);

    // --- Langkah 6: Jika semua berhasil, "Kunci" perubahannya ---
    $pdo->commit();

    // Beri pesan sukses dan kembalikan ke dashboard
    $_SESSION['success_message'] = "Game (ID: $game_id) dan semua data terkait berhasil dihapus.";
    header('Location: index.php?page=manage_games');
    exit;

} catch (PDOException $e) {
    // --- Langkah 7: Jika ada SATU saja error, batalkan SEMUA ---
    $pdo->rollBack();

    // Beri pesan error dan kembalikan ke dashboard
    $_SESSION['error_message'] = "Gagal menghapus game: " . $e->getMessage();
    header('Location: index.php?page=manage_games');
    exit;
}
?>