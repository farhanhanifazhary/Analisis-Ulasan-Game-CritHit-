<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CritHit</title>
</head>
<body class="bg-gray-900 text-white font-sans">

    <?php 
        // Memasukkan Navbar
        include 'template/header.php'; 
    ?>

    <main class="container mx-auto px-4 py-16 flex justify-center">
        
        <div class="w-full max-w-md">
            
            <div class="bg-gray-800 rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-center mb-6">Login ke CritHit</h2>

                <?php
                // Cek pesan sukses dari register
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="bg-green-800 border border-green-700 text-white p-4 rounded-lg mb-4">';
                    echo htmlspecialchars($_SESSION['success_message']);
                    echo '</div>';
                    // Hapus pesan setelah ditampilkan
                    unset($_SESSION['success_message']);
                }

                // Cek pesan error dari proses login
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="bg-red-800 border border-red-700 text-white p-4 rounded-lg mb-4">';
                    echo htmlspecialchars($_SESSION['error_message']);
                    echo '</div>';
                    // Hapus pesan setelah ditampilkan
                    unset($_SESSION['error_message']);
                }
                ?>
                <form action="login_proses.php" method="POST">
                    
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

                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="block w-full p-3 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500"
                            placeholder="Masukkan password Anda" 
                            required 
                        />
                    </div>

                    <div class="mb-4">
                        <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg font-bold hover:bg-blue-700 transition-colors">
                            Login
                        </button>
                    </div>

                </form>

                <p class="text-center text-gray-400">
                    Belum punya akun? 
                    <a href="register.php" class="text-blue-400 hover:text-blue-300">Daftar di sini</a>
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