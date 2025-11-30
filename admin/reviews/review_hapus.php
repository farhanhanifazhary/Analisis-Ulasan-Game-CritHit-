<?php

// Cek apakah ID ulasan ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Permintaan tidak valid. ID Ulasan tidak ditemukan.";
    header('Location: index.php?page=manage_reviews');
    exit;
}

$review_id = (int)$_GET['id'];

// Logika Penghapusan
try {
    // Langsung hapus ulasan berdasarkan ID
    $sql_delete = "DELETE FROM reviews WHERE id = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([$review_id]);

    // Cek apakah ada baris yang terhapus
    if ($stmt_delete->rowCount() > 0) {
        // Beri pesan sukses
        $_SESSION['success_message'] = "Ulasan (ID: $review_id) berhasil dihapus.";
    } else {
        // ID tidak ditemukan (mungkin sudah dihapus)
        $_SESSION['error_message'] = "Ulasan (ID: $review_id) tidak ditemukan.";
    }
    
    // Kembalikan ke halaman daftar ulasan
    header('Location: index.php?page=manage_reviews');
    exit;

} catch (PDOException $e) {
    // Jika ada error database lain
    $_SESSION['error_message'] = "Gagal menghapus ulasan: " . $e->getMessage();
    header('Location: index.php?page=manage_reviews');
    exit;
}
?>