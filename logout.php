<?php
// memulai session
session_start();

//Hancurkan semua data session
// Ini "melupakan" siapa yang login
$_SESSION = []; // Kosongkan array session
session_unset(); // Cara lama, tapi amankan
session_destroy(); // Hancurkan file session di server

// Arahkan kembali ke homepage
// User sekarang jadi "tamu" lagi
header('Location: index.php');
exit;
?>