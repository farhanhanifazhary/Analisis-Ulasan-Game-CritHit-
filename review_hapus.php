<?php
// Selalu mulai session (anti-notice)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Masukkan file koneksi
require 'koneksi.php';

// Security Gate
// Hanya user yang login yang bisa menghapus ulasan (admin punya file hapus sendiri di folder admin)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    $_SESSION['error_message'] = "Anda harus login untuk menghapus ulasan.";
    header('Location: login.php');
    exit;
}

// Ambil ID Ulasan dari URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php'); // Arahkan ke home jika ID tidak ada
    exit;
}

$review_id = (int)$_GET['id'];
$user_id = (int)$_SESSION['user_id'];

try {
    // Cek Kepemilikan dan ambil game_id
    // Kita harus memastikan ulasan ini benar-benar milik user yang sedang login.
    // Kita juga butuh game_id untuk redirect kembali ke halaman game yang benar.
    $stmt_get = $pdo->prepare("SELECT game_id FROM reviews WHERE id = ? AND user_id = ?");
    $stmt_get->execute([$review_id, $user_id]);
    $review = $stmt_get->fetch();

    if ($review) {
        // --- PENGGUNA SAH: Hapus ulasan ---
        $game_id = $review['game_id'];
        
        $sql_delete = "DELETE FROM reviews WHERE id = ? AND user_id = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$review_id, $user_id]);
        
        $_SESSION['success_message'] = "Ulasan Anda berhasil dihapus.";
        
        // Redirect kembali ke halaman detail game (ke bagian #reviews)
        header('Location: detail.php?id=' . $game_id . '#reviews');
        exit;
        
    } 

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Gagal menghapus ulasan: " . $e->getMessage();
    header('Location: index.php');
    exit;
}
?>