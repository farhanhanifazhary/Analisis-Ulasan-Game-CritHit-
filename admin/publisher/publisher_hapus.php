<?php
// Cek apakah ID publisher ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Permintaan tidak valid. ID Publisher tidak ditemukan.";
    header('Location: index.php?page=manage_publishers');
    exit;
}

$publisher_id = (int)$_GET['id'];

// Logika Penghapusan yang aman
try {
    // Langkah 1: Cek apakah publisher ini sedang dipakai oleh game
    $sql_check = "SELECT COUNT(*) FROM game_publishers WHERE publisher_id = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$publisher_id]);
    
    // fetchColumn() mengambil nilai dari kolom pertama (COUNT(*))
    $count = $stmt_check->fetchColumn(); 

    if ($count > 0) {
        // Jika publisher sedang dipakai: Jangan dihapus
        $_SESSION['error_message'] = "Gagal menghapus! Publisher ini sedang digunakan oleh $count game. Hapus dulu relasinya di 'Manajemen Game'.";
        header('Location: index.php?page=manage_publishers');
        exit;
        
    } else {
        // Jika publisher tidak dipakai: hapus
        $sql_delete = "DELETE FROM publisher WHERE id = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$publisher_id]);

        // Beri pesan sukses
        $_SESSION['success_message'] = "Publisher (ID: $publisher_id) berhasil dihapus karena tidak terpakai.";
        header('Location: index.php?page=manage_publishers');
        exit;
    }

} catch (PDOException $e) {
    // Jika ada error database lain
    $_SESSION['error_message'] = "Gagal menghapus publisher: " . $e->getMessage();
    header('Location: index.php?page=manage_publishers');
    exit;
}
?>