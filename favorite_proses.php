<?php
// Selalu mulai session (anti-notice)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Masukkan file koneksi
require 'koneksi.php';

// Security Gate
// Hanya user yang login yang bisa
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    $_SESSION['error_message'] = "Anda harus login sebagai user untuk melakukan ini.";
    header('Location: login.php');
    exit;
}

// Cek apakah data dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil data
    $user_id = (int)$_SESSION['user_id'];
    $game_id = (int)$_POST['game_id'];

    if ($game_id <= 0) {
        // Jika game_id tidak valid, tendang balik
        header('Location: index.php');
        exit;
    }

    // --- Logika Toogle ---
    try {
        // Cek apakah user sudah memfavoritkan game ini
        $sql_check = "SELECT id FROM favorites WHERE user_id = ? AND game_id = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$user_id, $game_id]);

        if ($stmt_check->rowCount() > 0) {
            // --- KASUS 1: SUDAH ADA (Hapus) ---
            $sql_delete = "DELETE FROM favorites WHERE user_id = ? AND game_id = ?";
            $stmt_delete = $pdo->prepare($sql_delete);
            $stmt_delete->execute([$user_id, $game_id]);
            
        } else {
            // --- KASUS 2: BELUM ADA (Tambah) ---
            $sql_insert = "INSERT INTO favorites (user_id, game_id) VALUES (?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$user_id, $game_id]);
        }

        // Kembalikan user ke halaman game
        header('Location: detail.php?id=' . $game_id);
        exit;

    } catch (PDOException $e) {
        // Jika ada error database
        $_SESSION['error_message'] = "Terjadi error database: " . $e->getMessage();
        header('Location: detail.php?id=' . $game_id);
        exit;
    }

} else {
    // Jika file diakses langsung (bukan via POST)
    header('Location: index.php');
    exit;
}
?>