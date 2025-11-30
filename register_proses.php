<?php
// Selalu mulai session di awal
session_start();

// Masukkan file koneksi
require 'koneksi.php';

// Cek apakah data dikirim via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ambil data dari form dan amankan
    // htmlspecialchars() penting untuk mencegah XSS
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    // Validasi data
    $errors = []; // Pakai array untuk menampung semua error

    // Validasi 1: Cek apakah ada field yang kosong
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "Semua field wajib diisi.";
    }

    // Validasi 2: Cek format email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Format email tidak valid.";
    }

    // Validasi 3: Cek panjang password (contoh: minimal 8 karakter)
    if (strlen($password) < 8) {
        $errors[] = "Password minimal harus 8 karakter.";
    }

    // Validasi 4: Cek apakah password & konfirmasi sama
    if ($password !== $confirm_password) {
        $errors[] = "Password dan Konfirmasi Password tidak cocok.";
    }

    // Validasi 5 (Kritis): Cek apakah email sudah terdaftar di database
    // Pakai try...catch untuk semua query
    try {
        $sql_check = "SELECT id FROM users WHERE email = ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$email]); // Kirim email sebagai parameter

        if ($stmt_check->rowCount() > 0) {
            $errors[] = "Email ini sudah terdaftar. Silakan gunakan email lain.";
        }
    } catch (PDOException $e) {
        // Jika ada error query, simpan sebagai error
        $errors[] = "Error database: " . $e->getMessage();
    }


    // Proses hasil validasi
    
    if (!empty($errors)) {
        // Jika ada error:
        // Simpan pesan error ke session
        $_SESSION['errors'] = $errors;

        // Kembalikan user ke halaman register
        header('Location: register.php');
        exit; // Pastikan script berhenti setelah redirect

    } else {
        // Jika ada error (Validasi Lolos):
        
        // Hash Password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Masukkan user baru ke database
        try {
            $sql_insert = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
            $stmt_insert = $pdo->prepare($sql_insert);
            
            // Eksekusi dengan data yang sudah aman
            $stmt_insert->execute([$name, $email, $hashed_password]);

            //  Beri pesan sukses
            $_SESSION['success_message'] = "Pendaftaran berhasil! Silakan login.";

            // Arahkan ke halaman login
            header('Location: login.php');
            exit;

        } catch (PDOException $e) {
            // Jika insert gagal
            $_SESSION['errors'] = ["Gagal mendaftarkan akun: " . $e->getMessage()];
            header('Location: register.php');
            exit;
        }
    }

} else {
    // Jika file diakses langsung (bukan via POST)
    // Tendang balik ke homepage
    header('Location: index.php');
    exit;
}
?>