<?php
// --- Konfigurasi Database ---
$host     = 'localhost';
$db       = 'crithit_db';
$user     = 'root';
$pass     = '';
$charset  = 'utf8mb4';

// DSN (Data Source Name)
// Ini adalah "alamat" lengkap ke database.
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opsi untuk koneksi PDO
$options = [
    // Ini adalah "aturan main" koneksi:
    
    // 1. Tampilkan error sebagai 'exception'
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    
    // 2. Ambil data sebagai 'array asosiatif'
    // Artinya data Anda akan jadi $row['nama'], bukan $row[0]
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    
    // 3. Matikan emulasi prepared statements
    // Ini memaksa database (MySQL) untuk melakukan 'prepare' yang sesungguhnya
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Blok 'try...catch' adalah bagian keamanan terpenting
try {
     // Coba buat koneksi baru
     $pdo = new PDO($dsn, $user, $pass, $options);
     
} catch (\PDOException $e) {
     // Jika koneksi GAGAL:
     die("Maaf, terjadi masalah koneksi ke database.");
}
?>