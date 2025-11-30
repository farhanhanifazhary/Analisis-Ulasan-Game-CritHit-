<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'koneksi.php';

// Cek Login
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    $_SESSION['error_message'] = "Anda harus login sebagai user.";
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil Data
    $user_id = (int)$_SESSION['user_id'];
    $game_id = (int)$_POST['game_id'];
    $rating = (int)$_POST['rating'];
    $comment_text = $_POST['comment_text'];
    // Ambil action (create/update) dari form hidden input
    $action = $_POST['action'] ?? 'create'; 

    // Validasi Dasar
    if ($game_id <= 0 || $rating < 1 || $rating > 5 || empty($comment_text)) {
        $_SESSION['error_message'] = "Data tidak valid.";
        header('Location: detail.php?id=' . $game_id);
        exit;
    }

    // --- Integrasi Machine Learning ---
    $sentiment = 'neutral'; // Default value

    // Menentukan command python
    $script_path = __DIR__ . '/ml_engine/analyze.py';
    $command = "python " . $script_path; 
    
    // Setup pipa komunikasi
    $descriptorspec = [
       0 => ["pipe", "r"],  // STDIN (PHP nulis ke Python)
       1 => ["pipe", "w"],  // STDOUT (PHP baca dari Python)
       2 => ["pipe", "w"]   // STDERR (Log Error)
    ];

    $process = proc_open($command, $descriptorspec, $pipes);

    if (is_resource($process)) {
        // Kirim teks ulasan ke Python
        fwrite($pipes[0], $comment_text);
        fclose($pipes[0]);

        // Baca hasil prediksi (positive/negative)
        $output = stream_get_contents($pipes[1]);
        fclose($pipes[1]);

        // Baca error (jika ada) untuk debugging
        $error_output = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        $return_value = proc_close($process);
        
        // Bersihkan whitespace (penting!)
        $clean_output = trim($output);

        // Validasi output dari Python
        if ($clean_output === 'positive' || $clean_output === 'negative') {
            $sentiment = $clean_output;
        } else {
            // Jika python, set sentiment sebagai 'gagal;
            $sentiment = "gagal";
        }
    }
    // --- END INTEGRASI ---

    // --- LOGIKA Database (ROUTER) ---
    try {
        if ($action === 'create') {
            // Cek dulu apakah sudah pernah review (Mencegah Duplikat)
            $sql_check = "SELECT id FROM reviews WHERE user_id = ? AND game_id = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$user_id, $game_id]);
            
            if ($stmt_check->rowCount() > 0) {
                $_SESSION['error_message'] = "Anda sudah pernah memberi ulasan. Silakan edit ulasan lama Anda.";
                header('Location: detail.php?id=' . $game_id);
                exit;
            }

            // Insert Baru
            $sql_insert = "INSERT INTO reviews (game_id, user_id, rating, comment_text, sentiment) 
                           VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$game_id, $user_id, $rating, $comment_text, $sentiment]);
            
            $_SESSION['success_message'] = "Ulasan berhasil ditambahkan!";

        } elseif ($action === 'update') {
            // Update data yang sudah ada berdasarkan user_id dan game_id
            $sql_update = "UPDATE reviews SET rating = ?, comment_text = ?, sentiment = ? 
                           WHERE user_id = ? AND game_id = ?";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([$rating, $comment_text, $sentiment, $user_id, $game_id]);
            
            $_SESSION['success_message'] = "Ulasan berhasil diperbarui!";
        }

        // Kembali ke halaman detail
        header('Location: detail.php?id=' . $game_id . '#reviews');
        exit;

    } catch (PDOException $e) {
        $_SESSION['error_message'] = "Database Error: " . $e->getMessage();
        header('Location: detail.php?id=' . $game_id . '#reviews');
        exit;
    }
}else {
    header('Location: index.php');
    exit;
}
?>