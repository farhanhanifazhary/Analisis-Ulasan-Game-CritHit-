<?php

$error_db = '';
$publishers = [];
try {
    // Ambil semua publisher, urutkan berdasarkan nama
    $sql = "SELECT * FROM publisher ORDER BY name ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $publishers = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_db = "Error mengambil data publisher: " . $e->getMessage();
}

?>

<a href="index.php?page=dashboard" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Dashboard</a>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Manajemen Publisher</h1>
    <a href="index.php?page=publisher_tambah" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-700">
        + Tambah Publisher Baru
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
                <th class="p-4 text-left">Nama Publisher</th>
                <th class="p-4 text-left">Negara</th>
                <th class="p-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($publishers)): ?>
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-400">
                        Belum ada publisher di database.
                    </td>
                </tr>
            <?php else:
                $no = 1;
                foreach ($publishers as $pub): ?>
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="p-4"><?php echo htmlspecialchars($no); ?></td>
                        <td class="p-4 font-medium"><?php echo htmlspecialchars($pub['name']); ?></td>
                        <td class="p-4"><?php echo htmlspecialchars($pub['country']); ?></td>
                        <td class="p-4">
                            <a href="index.php?page=publisher_edit&id=<?php echo $pub['id']; ?>" class="text-blue-400 hover:text-blue-300 mr-2">Edit</a>
                            <a href="index.php?page=publisher_hapus&id=<?php echo $pub['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Anda yakin ingin menghapus publisher ini? Ini bisa memengaruhi game yang ada.');">Hapus</a>
                        </td>
                    </tr>
                    <?php $no++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>