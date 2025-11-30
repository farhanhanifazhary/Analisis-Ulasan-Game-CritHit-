<?php
// Inisialisasi Variabel
$name = '';
$email = '';
$role = 'user'; // Default role
$errors = [];

// Logika Proses Form (Hanya jika method = POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dari form
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = htmlspecialchars($_POST['role']);

    // Validasi
    if (empty($name)) {
        $errors[] = "Nama wajib diisi.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email tidak valid.";
    }
    if (empty($password)) {
        $errors[] = "Password wajib diisi.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password minimal harus 8 karakter.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Password dan Konfirmasi Password tidak cocok.";
    }
    if (!in_array($role, ['user', 'admin'])) {
        $errors[] = "Role tidak valid.";
    }

    // Validasi duplikat email (hanya jika error lain tidak ada)
    if (empty($errors)) {
        try {
            $sql_check = "SELECT id FROM users WHERE email = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$email]);
            if ($stmt_check->rowCount() > 0) {
                $errors[] = "Email '".htmlspecialchars($email)."' sudah terdaftar.";
            }
        } catch (PDOException $e) {
            $errors[] = "Error database: " . $e->getMessage();
        }
    }

    // Jika tidak ada error (Validasi Lolos)
    if (empty($errors)) {
        try {
            // Hash password sebelum disimpan
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Masukkan ke database
            $sql_insert = "INSERT INTO users (name, email, password, role) 
                           VALUES (?, ?, ?, ?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$name, $email, $hashed_password, $role]);

            // Beri pesan sukses dan redirect ke halaman daftar
            $_SESSION['success_message'] = "Pengguna '". htmlspecialchars($name) ."' berhasil ditambahkan!";
            header('Location: index.php?page=manage_users');
            exit;

        } catch (PDOException $e) {
            // Jika insert gagal
            $errors[] = "Gagal menyimpan ke database: " . $e->getMessage();
        }
    }
}
?>

<a href="index.php?page=manage_users" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Manajemen Pengguna</a>
<h1 class="text-3xl font-bold mb-6">Tambah Pengguna Baru</h1>

<?php
if (!empty($errors)) {
    echo '<div class="bg-red-800 border border-red-700 text-white p-4 rounded-lg mb-4">';
    echo '<ul class="list-disc pl-5">';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul>';
    echo '</div>';
}
?>
<div class="bg-gray-700 rounded-lg shadow-lg p-8 max-w-lg">
    <form action="index.php?page=user_tambah" method="POST">
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama</label>
            <input 
                type="text" id="name" name="name"
                value="<?php echo htmlspecialchars($name); ?>"
                class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
                required 
            />
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
            <input 
                type="email" id="email" name="email"
                value="<?php echo htmlspecialchars($email); ?>"
                class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
                required 
            />
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password Baru</label>
            <input 
                type="password" id="password" name="password"
                class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
                placeholder="Minimal 8 karakter"
                required 
            />
        </div>

        <div class="mb-4">
            <label for="confirm_password" class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password</label>
            <input 
                type="password" id="confirm_password" name="confirm_password"
                class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
                required 
            />
        </div>

        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-300 mb-2">Role</label>
            <select id="role" name="role" class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500">
                <option value="user" <?php if ($role == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($role == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <a href="index.php?page=manage_users" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-500">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">
                Simpan Pengguna
            </button>
        </div>

    </form>
</div>