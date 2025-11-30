<?php

// Cek apakah ID artikel ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Permintaan tidak valid. ID Artikel tidak ditemukan.";
    header('Location: index.php?page=manage_articles');
    exit;
}

$article_id = (int)$_GET['id'];

// Logika Penghapusan
try {
    // Langsung hapus artikel berdasarkan ID
    $sql_delete = "DELETE FROM article WHERE id = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([$article_id]);

    // Cek apakah ada baris yang terhapus
    if ($stmt_delete->rowCount() > 0) {
        // Beri pesan sukses
        $_SESSION['success_message'] = "Artikel (ID: $article_id) berhasil dihapus.";
    } else {
        // ID tidak ditemukan (mungkin sudah dihapus)
        $_SESSION['error_message'] = "Artikel (ID: $article_id) tidak ditemukan.";
    }
    
    // Kembalikan ke halaman daftar
    header('Location: index.php?page=manage_articles');
    exit;

} catch (PDOException $e) {
    // Jika ada error database lain
    $_SESSION['error_message'] = "Gagal menghapus artikel: " . $e->getMessage();
    header('Location: index.php?page=manage_articles');
    exit;
}
?>