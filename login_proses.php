<?php
// Selalu mulai session di awal
session_start();

// Masukkan file koneksi
require 'koneksi.php';

// Cek apakah data dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil data dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validasi dasar (apakah kosong?)
    if (empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Email dan password wajib diisi.";
        header('Location: login.php');
        exit;
    }

    // --- Logika Inti: Cek ke Database ---
    try {
        // Cari user berdasarkan email
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        // Ambil data user (jika ada)
        $user = $stmt->fetch(); // $user akan 'false' jika email tidak ditemukan

        // Verifikasi User dan Password
        // $user -> Cek apakah emailnya ada
        // password_verify(...) -> Cek apakah password yang diketik user
        //                          cocok dengan HASH di database
        
        if ($user && password_verify($password, $user['password'])) {
            // --- Login Berhasil ---

            // Regenerasi session ID. Wajib untuk keamanan.
            //    (Mencegah serangan Session Fixation)
            session_regenerate_id(true);

            // Simpan data user ke dalam SESSION
            //    pakai ini di semua halaman untuk 'mengingat' user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role']; // 'admin' or 'user'

            // Arahkan ke halaman utama
            header('Location: index.php');
            exit;

        } else {
            // --- Login Gagal ---
            // Email tidak ditemukan atau password salah
            $_SESSION['error_message'] = "Email atau password salah.";
            header('Location: login.php');
            exit;
        }

    } catch (PDOException $e) {
        // Jika query database-nya yang error
        $_SESSION['error_message'] = "Terjadi masalah dengan database. Silakan coba lagi.";
        // Untuk debugging, Anda bisa pakai:
        // $_SESSION['error_message'] = $e->getMessage();
        header('Location: login.php');
        exit;
    }

} else {
    // Jika file diakses langsung (bukan via POST)
    header('Location: index.php');
    exit;
}
?>