<?php
// Mulai session di bagian paling atas
if (session_status() === PHP_SESSION_NONE) {
    session_start();
} 

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']); 

// Ambil data user jika dia login
$user_name = $_SESSION['user_name'] ?? 'Tamu';
$user_role = $_SESSION['user_role'] ?? 'user'; // Ambil role
?>

<script src="assets/js/tailwind.js"></script>
<script>
    tailwind.config = { darkMode: 'class' }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<header class="bg-gray-800 shadow-md">
    <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
        
        <a href="/CritHit/" class="text-2xl font-bold text-white">
            CritHit
        </a>

        <div class="flex items-center space-x-4">
            
            <?php if ($is_logged_in): ?>
                
                <span class="text-white">Halo, <?php echo htmlspecialchars($user_name); ?></span>
                <span class="text-gray-400">|</span>

                <?php 
                // Tampilkan link BERDASARKAN ROLE
                if ($user_role === 'admin'): 
                ?>
                    <a href="admin/index.php" class="text-yellow-400 font-bold hover:text-yellow-300">
                        [Admin Dashboard]
                    </a>
                <?php else: ?>
                    <a href="favorites.php" class="text-gray-300 hover:text-white">
                        Favorit Saya
                    </a>
                <?php endif; ?>
                
                <span class="text-gray-400">|</span>
                <a href="/CritHit/logout.php" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700">
                    Logout
                </a>
            <?php else: ?>

                <a href="login.php" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                    Login
                </a>
                <a href="register.php" class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700">
                    Register
                </a>

            <?php endif; ?>

        </div>
    </nav>
</header>