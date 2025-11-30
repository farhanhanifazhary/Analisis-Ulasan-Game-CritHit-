<?php

// 1Cek apakah ID genre ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Permintaan tidak valid. ID Genre tidak ditemukan.";
    header('Location: index.php?page=manage_genres');
    exit;
}

$genre_id = (int)$_GET['id'];

// Logika Penghapusan yang aman
try {
    // Langkah 1: Cek apakah genre ini sedang dipakai oleh game
    $sql_check = "SELECT COUNT(*) FROM game_genres WHERE genre_id = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$genre_id]);
    
    // fetchColumn() mengambil nilai dari kolom pertama (COUNT(*))
    $count = $stmt_check->fetchColumn(); 

    if ($count > 0) {
        // Jika genre sedang dipakai: Jangan dihapus
        $_SESSION['error_message'] = "Gagal menghapus! Genre ini sedang digunakan oleh $count game. Hapus dulu relasinya di 'Manajemen Game'.";
        header('Location: index.php?page=manage_genres');
        exit;
        
    } else {
        // Jika genre tidak dipakai
        $sql_delete = "DELETE FROM genres WHERE id = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$genre_id]);

        // Beri pesan sukses
        $_SESSION['success_message'] = "Genre (ID: $genre_id) berhasil dihapus karena tidak terpakai.";
        header('Location: index.php?page=manage_genres');
        exit;
    }

} catch (PDOException $e) {
    // Jika ada error database lain
    $_SESSION['error_message'] = "Gagal menghapus genre: " . $e->getMessage();
    header('Location: index.php?page=manage_genres');
    exit;
}
?>