<?php

// Inisialisasi Variabel
$name = '';
$email = '';
$role = 'user';
$errors = [];

// Tentukan ID User. Ambil dari POST (saat validasi gagal) atau GET (saat pertama dibuka)
$user_id = (int)($_POST['user_id'] ?? $_GET['id'] ?? 0);

if ($user_id <= 0) {
    $_SESSION['error_message'] = "ID Pengguna tidak valid.";
    header('Location: index.php?page=manage_users');
    exit;
}

// Keamanan: Jangan biarkan admin mengedit dirinya sendiri di sini
// (Mencegah admin mengunci dirinya sendiri atau mengubah role-nya sendiri)
if ($user_id === $_SESSION['user_id']) {
    $_SESSION['error_message'] = "Anda tidak dapat mengedit akun Anda sendiri dari halaman ini.";
    header('Location: index.php?page=manage_users');
    exit;
}

// Logika Proses Form (Hanya jika method = POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dari form
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $role = htmlspecialchars($_POST['role']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi Data Inti
    if (empty($name)) {
        $errors[] = "Nama wajib diisi.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email tidak valid.";
    }
    if (!in_array($role, ['user', 'admin'])) {
        $errors[] = "Role tidak valid.";
    }

    // Validasi duplikat email (abaikan ID kita sendiri)
    try {
        $sql_check = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt_check = $pdo->prepare($sql_check);
        $stmt_check->execute([$email, $user_id]);
        if ($stmt_check->rowCount() > 0) {
            $errors[] = "Email '".htmlspecialchars($email)."' sudah digunakan akun lain.";
        }
    } catch (PDOException $e) {
        $errors[] = "Error database: " . $e->getMessage();
    }

    // Validasi Password (Hanya jika diisi)
    $hashed_password = null; // Default, tidak update password
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $errors[] = "Password baru minimal harus 8 karakter.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Password baru dan Konfirmasi tidak cocok.";
        }
        // Jika lolos validasi password
        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        }
    }

    // Jika tidak ada error (Validasi Lolos)
    if (empty($errors)) {
        try {
            // Siapkan query UPDATE
            if ($hashed_password !== null) {
                // --- Query JIKA PASSWORD DIUBAH ---
                $sql_update = "UPDATE users SET name = ?, email = ?, role = ?, password = ? 
                               WHERE id = ?";
                $params = [$name, $email, $role, $hashed_password, $user_id];
            } else {
                // --- Query JIKA PASSWORD TIDAK DIUBAH ---
                $sql_update = "UPDATE users SET name = ?, email = ?, role = ? 
                               WHERE id = ?";
                $params = [$name, $email, $role, $user_id];
            }
            
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute($params);

            // Beri pesan sukses dan redirect ke halaman daftar
            $_SESSION['success_message'] = "Pengguna '". htmlspecialchars($name) ."' berhasil di-update!";
            header('Location: index.php?page=manage_users');
            exit;

        } catch (PDOException $e) {
            // Jika update gagal
            $errors[] = "Gagal meng-update database: " . $e->getMessage();
        }
    }

} else {
    // Logika jika method = GET
    // User baru datang, ambil data dari DB
    try {
        // Ambil data (TANPA PASSWORD)
        $sql_get = "SELECT name, email, role FROM users WHERE id = ?";
        $stmt_get = $pdo->prepare($sql_get);
        $stmt_get->execute([$user_id]);
        $user = $stmt_get->fetch();

        if ($user) {
            // Isi variabel 'sticky form' dari data DB
            $name = $user['name'];
            $email = $user['email'];
            $role = $user['role'];
        } else {
            // Jika ID-nya tidak ditemukan
            $_SESSION['error_message'] = "Pengguna dengan ID $user_id tidak ditemukan.";
            header('Location: index.php?page=manage_users');
            exit;
        }
    } catch (PDOException $e) {
        $errors[] = "Gagal mengambil data: " . $e->getMessage();
    }
}
?>

<a href="index.php?page=manage_users" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Manajemen Pengguna</a>
<h1 class="text-3xl font-bold mb-6">Edit Pengguna: <?php echo htmlspecialchars($name); ?></h1>

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
    <form action="index.php?page=user_edit" method="POST">
        
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

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
            <label for="role" class="block text-sm font-medium text-gray-300 mb-2">Role</label>
            <select id="role" name="role" class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500">
                <option value="user" <?php if ($role == 'user') echo 'selected'; ?>>User</option>
                <option value="admin" <?php if ($role == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
        </div>

        <hr class="border-gray-600 my-6">
        
        <p class="text-sm text-gray-400 mb-4">Isi *field* di bawah ini HANYA jika Anda ingin mengubah password pengguna.</p>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password Baru (Opsional)</label>
            <input 
                type="password" id="password" name="password"
                class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
                placeholder="Kosongkan jika tidak ingin diubah"
            />
        </div>

        <div class="mb-4">
            <label for="confirm_password" class="block text-sm font-medium text-gray-300 mb-2">Konfirmasi Password Baru</l abel>
            <input 
                type="password" id="confirm_password" name="confirm_password"
                class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
            />
        </div>

        <div class="flex justify-end space-x-4 mt-8">
            <a href="index.php?page=manage_users" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-500">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">
                Update Pengguna
            </button>
        </div>

    </form>
</div>