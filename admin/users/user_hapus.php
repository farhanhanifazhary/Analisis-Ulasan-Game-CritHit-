<?php

// Cek apakah ID user ada di URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error_message'] = "Permintaan tidak valid. ID Pengguna tidak ditemukan.";
    header('Location: index.php?page=manage_users');
    exit;
}

$user_id = (int)$_GET['id'];

// Jaring Pengaman
// Cek apakah user mencoba menghapus dirinya sendiri
if ($user_id === $_SESSION['user_id']) {
    $_SESSION['error_message'] = "Anda tidak dapat menghapus akun Anda sendiri!";
    header('Location: index.php?page=manage_users');
    exit;
}

// Database Transactional
try {
    // Mulai "mode aman"
    $pdo->beginTransaction();

    // --- Langkah 1: Hapus dari 'reviews' ---
    // (Hapus semua ulasan yang ditulis user ini)
    $stmt_reviews = $pdo->prepare("DELETE FROM reviews WHERE user_id = ?");
    $stmt_reviews->execute([$user_id]);

    // --- Langkah 2: Hapus dari 'favorites' ---
    // (Hapus semua favorit yang dimiliki user ini)
    $stmt_fav = $pdo->prepare("DELETE FROM favorites WHERE user_id = ?");
    $stmt_fav->execute([$user_id]);

    // --- Langkah 3: Hapus dari 'article' ---
    // (Hapus semua artikel yang ditulis user ini)
    $stmt_article = $pdo->prepare("DELETE FROM article WHERE user_id = ?");
    $stmt_article->execute([$user_id]);

    // --- Langkah 4 (Terakhir): Hapus dari 'users' ---
    // (Setelah semua data anaknya dihapus, baru kita hapus induknya)
    $stmt_user = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt_user->execute([$user_id]);

    // --- Langkah 5: Jika semua berhasil, "Kunci" perubahannya ---
    $pdo->commit();

    // Beri pesan sukses dan kembalikan ke dashboard
    $_SESSION['success_message'] = "Pengguna (ID: $user_id) dan semua data terkaitnya (ulasan, favorit, artikel) berhasil dihapus.";
    header('Location: index.php?page=manage_users');
    exit;

} catch (PDOException $e) {
    // --- Langkah 6: Jika ada SATU saja error, batalkan SEMUA ---
    $pdo->rollBack();

    // Beri pesan error dan kembalikan ke dashboard
    $_SESSION['error_message'] = "Gagal menghapus pengguna: " . $e->getMessage();
    header('Location: index.php?page=manage_users');
    exit;
}
?>