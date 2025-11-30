<?php

$error_db = '';
$articles = [];
try {
    // Ambil semua artikel, kita JOIN dengan tabel users untuk dapat nama penulis
    $sql = "SELECT a.id, a.title, a.created_at, u.name AS author_name 
            FROM article a
            JOIN users u ON a.user_id = u.id
            ORDER BY a.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $articles = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_db = "Error mengambil data artikel: " . $e->getMessage();
}

?>

<a href="index.php?page=dashboard" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Dashboard</a>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Manajemen Artikel</h1>
    <a href="index.php?page=article_tambah" class="bg-green-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-green-700">
        + Tulis Artikel Baru
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
                <th class="p-4 text-left">Judul Artikel</th>
                <th class="p-4 text-left">Penulis (Admin)</th>
                <th class="p-4 text-left">Tanggal Publikasi</th>
                <th class="p-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($articles)): ?>
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-400">
                        Belum ada artikel yang ditulis.
                    </td>
                </tr>
            <?php else:
                $no = 1;
                foreach ($articles as $article): ?>
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="p-4"><?php echo htmlspecialchars($no); ?></td>
                        <td class="p-4 font-medium"><?php echo htmlspecialchars($article['title']); ?></td>
                        <td class="p-4"><?php echo htmlspecialchars($article['author_name']); ?></td>
                        <td class="p-4"><?php echo date('d M Y, H:i', strtotime($article['created_at'])); ?></td>
                        <td class="p-4">
                            <a href="index.php?page=article_edit&id=<?php echo $article['id']; ?>" class="text-blue-400 hover:text-blue-300 mr-2">Edit</a>
                            <a href="index.php?page=article_hapus&id=<?php echo $article['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Anda yakin ingin menghapus artikel ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php $no++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>