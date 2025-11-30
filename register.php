<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - CritHit</title>
</head>
<body class="bg-gray-900 text-white font-sans">

    <?php 
        // Memasukkan Navbar
        include 'template/header.php'; 
    ?>

    <main class="container mx-auto px-4 py-16 flex justify-center">
        
        <div class="w-full max-w-md">
            
            <div class="bg-gray-800 rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-6">Buat Akun Baru</h2>

                <?php
                // Cek apakah ada pesan error di session
                if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
                    echo '<div class="bg-red-800 border border-red-700 text-white p-4 rounded-lg mb-4">';
                    echo '<ul class="list-disc pl-5">';
                    foreach ($_SESSION['errors'] as $error) {
                        echo '<li>' . htmlspecialchars($error) . '</li>';
                    }
                    echo '</ul>';
                    echo '</div>';
                    
                    // Hapus pesan error setelah ditampilkan
                    // agar tidak muncul lagi saat di-refresh
                    unset($_SESSION['errors']);
                }
                ?>

                <form action="register_proses.php" method="POST">
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Lengkap</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="block w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                            placeholder="Masukkan nama Anda" 
                            required 
                        />
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="block w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                            placeholder="nama@email.com" 
                            required 
                        />
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="block w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                            placeholder="Minimal 8 karakter" 
                            required 
                        />
                    </div>

                    <div class="mb-6">
                        <label for="confirm_password" class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password</label>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            class="block w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                            placeholder="Ulangi password Anda" 
                            required 
                        />
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg font-bold hover:bg-blue-700 transition-colors">
                            Daftar
                        </button>
                    </div>

                </form>

                <p class="text-center text-gray-400">
                    Sudah punya akun? 
                    <a href="login.php" class="text-blue-400 hover:text-blue-300">Login di sini</a>
                </p>

            </div>
        </div>

    </main>
    <?php 
        // Memasukkan Footer
        include 'template/footer.php'; 
    ?>

</body>
</html>