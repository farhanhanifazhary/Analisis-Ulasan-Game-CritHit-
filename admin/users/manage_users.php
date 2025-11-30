<?php
$error_db = '';
$users = [];
try {
    // Ambil semua data user, urutkan berdasarkan yang terbaru daftar
    $sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_db = "Error mengambil data pengguna: " . $e->getMessage();
}

// Ambil ID admin yang sedang login dari session
$current_admin_id = $_SESSION['user_id'];

?>

<a href="index.php?page=dashboard" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Dashboard</a>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Manajemen Pengguna</h1>
    <a href="index.php?page=user_tambah" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-700">
        + Tambah Pengguna Baru
    </a>
</div>

<?php
if (isset($_SESSION['success_message'])) {
    echo '<div class="bg-green-800 text-white p-4 rounded-lg mb-4">'.htmlspecialchars($_SESSION['success_message']).'</div>';
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo '<div class="bg-red-800 text-white p-4 rounded-lg mb-4">'.htmlspecialchars($_SESSION['error_message']).'</div>';
    unset($_SESSION['error_message']);
}
if ($error_db) {
     echo '<div class="bg-red-800 text-white p-4 rounded-lg mb-4">'.$error_db.'</div>';
}
?>

<div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-700">
            <tr>
                <th class="p-4 text-left">NO</th>
                <th class="p-4 text-left">Nama</th>
                <th class="p-4 text-left">Email</th>
                <th class="p-4 text-left">Role</th>
                <th class="p-4 text-left">Bergabung</th>
                <th class="p-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-400">
                        Belum ada pengguna yang terdaftar.
                    </td>
                </tr>
            <?php else:
                $no = 1;
                foreach ($users as $user): ?>
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="p-4"><?php echo htmlspecialchars($no); ?></td>
                        <td class="p-4 font-medium"><?php echo htmlspecialchars($user['name']); ?></td>
                        <td class="p-4"><?php echo htmlspecialchars($user['email']); ?></td>
                        <td class="p-4">
                            <?php if ($user['role'] == 'admin'): ?>
                                <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                                    <?php echo $user['role']; ?>
                                </span>
                            <?php else: ?>
                                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                    <?php echo $user['role']; ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4"><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
                        <td class="p-4">
                            <a href="index.php?page=user_edit&id=<?php echo $user['id']; ?>" class="text-blue-400 hover:text-blue-300 mr-2">Edit</a>
                            
                            <?php 
                            // Logika PENTING:
                            // Jangan biarkan admin menghapus dirinya sendiri!
                            if ($user['id'] != $current_admin_id): 
                            ?>
                                <a href="index.php?page=user_hapus&id=<?php echo $user['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Anda yakin ingin menghapus pengguna ini? Semua ulasan dan data terkaitnya AKAN HILANG.');">Hapus</a>
                            <?php else: ?>
                                <span class="text-gray-500 cursor-not-allowed">Hapus</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $no++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>