<?php
// Selalu mulai session (anti-notice)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security gate
// melindungi SEMUA halaman admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    $_SESSION['error_message'] = "Anda tidak punya hak akses ke halaman ini!";
    header('Location: ../login.php'); // Arahkan keluar folder admin
    exit;
}

// Masukkan file koneksi
require '../koneksi.php';

// Tentukan halaman apa yang akan dimuat
$page = $_GET['page'] ?? 'dashboard';

?>
<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CritHit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="../assets/js/tailwind.js"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
</head>
<body class="bg-gray-900 text-white font-sans">

    <?php 
        // Memasukkan Navbar Global
        include '../template/header.php'; 
    ?>

    <div class="container mx-auto px-4 py-8 flex">
        
        <aside class="w-1/4 lg:w-1/5 pr-8">
            <h2 class="text-xl font-bold mb-4 text-yellow-400">Menu Admin</h2>
            <nav class="flex flex-col space-y-2">
                
                <a href="index.php?page=dashboard" 
                   class="flex items-center p-2 rounded-lg <?php echo ($page === 'dashboard') ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                   <i class="fas fa-home w-6 text-center mr-2"></i> Dashboard
                </a>

                <a href="index.php?page=manage_games" 
                   class="flex items-center p-2 rounded-lg <?php echo (strpos($page, 'game') !== false) ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                   <i class="fas fa-gamepad w-6 text-center mr-2"></i> Game
                </a>
                
                <a href="index.php?page=manage_genres" 
                   class="flex items-center p-2 rounded-lg <?php echo (strpos($page, 'genre') !== false) ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                   <i class="fas fa-tags w-6 text-center mr-2"></i> Genre
                </a>

                <a href="index.php?page=manage_publishers" 
                   class="flex items-center p-2 rounded-lg <?php echo (strpos($page, 'publisher') !== false) ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                   <i class="fas fa-building w-6 text-center mr-2"></i> Publisher
                </a>

                <a href="index.php?page=manage_articles" 
                   class="flex items-center p-2 rounded-lg <?php echo (strpos($page, 'article') !== false) ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                   <i class="fas fa-newspaper w-6 text-center mr-2"></i> Artikel
                </a>

                <a href="index.php?page=manage_users" 
                   class="flex items-center p-2 rounded-lg <?php echo (strpos($page, 'user') !== false) ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                   <i class="fas fa-users w-6 text-center mr-2"></i> Pengguna
                </a>
                
                <a href="index.php?page=manage_reviews" 
                   class="flex items-center p-2 rounded-lg <?php echo (strpos($page, 'review') !== false) ? 'bg-blue-600' : 'hover:bg-gray-700'; ?>">
                   <i class="fas fa-comment-slash w-6 text-center mr-2"></i> Ulasan
                </a>

            </nav>
        </aside>

        <main class="w-3/4 lg:w-4/5 bg-gray-800 p-6 rounded-lg shadow-lg">
            
            <?php
            // Tentukan path file yang aman
            $allowed_pages = [
                'dashboard' => 'dashboard_home.php',
                // games
                'manage_games' => 'games/manage_games.php',
                'game_tambah' => 'games/game_tambah.php',
                'game_edit' => 'games/game_edit.php',
                'game_hapus' => 'games/game_hapus.php',

                // genres
                'manage_genres' => 'genres/manage_genres.php',
                'genre_tambah' => 'genres/genre_tambah.php',
                'genre_edit' => 'genres/genre_edit.php',
                'genre_hapus' => 'genres/genre_hapus.php',

                // publisher
                'manage_publishers' => 'publisher/manage_publishers.php',
                'publisher_tambah' => 'publisher/publisher_tambah.php',
                'publisher_edit' => 'publisher/publisher_edit.php',
                'publisher_hapus' => 'publisher/publisher_hapus.php',

                // article
                'manage_articles' => 'article/manage_articles.php',
                'article_tambah' => 'article/article_tambah.php',
                'article_edit' => 'article/article_edit.php',
                'article_hapus' => 'article/article_hapus.php',

                // users
                'manage_users' => 'users/manage_users.php',
                'user_tambah' => 'users/user_tambah.php',
                'user_edit' => 'users/user_edit.php',
                'user_hapus' => 'users/user_hapus.php',

                // reviews
                'manage_reviews' => 'reviews/manage_reviews.php',
                'review_hapus' => 'reviews/review_hapus.php'
            ];

            // Cek apakah halaman yang diminta ada di daftar 'allowed'
            if (array_key_exists($page, $allowed_pages)) {
                // Jika ya, muat file-nya
                include $allowed_pages[$page];
            } else {
                // Jika tidak (atau default), muat dashboard home
                include 'dashboard_home.php';
            }
            ?>

        </main>
    </div>

    <?php 
        // Memasukkan Footer Global
        include '../template/footer.php'; 
    ?>

</body>
</html>