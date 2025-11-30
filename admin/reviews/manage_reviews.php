<?php
$error_db = '';
$reviews = [];
try {
    // Ambil semua ulasan, kita JOIN dengan tabel games dan users
    // untuk mendapatkan nama game dan nama user
    $sql = "SELECT 
                r.id, 
                r.comment_text, 
                r.rating, 
                r.sentiment, 
                r.created_at,
                g.title AS game_title,
                u.name AS user_name
            FROM reviews r
            JOIN games g ON r.game_id = g.id
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC"; // Tampilkan yang terbaru dulu
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $reviews = $stmt->fetchAll();
    
} catch (PDOException $e) {
    $error_db = "Error mengambil data ulasan: " . $e->getMessage();
}

?>

<a href="index.php?page=dashboard" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Dashboard</a>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Moderasi Ulasan</h1>
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
                <th class="p-4 text-left w-1/12">NO</th>
                <th class="p-4 text-left w-2/12">Game</th>
                <th class="p-4 text-left w-2/12">User</th>
                <th class="p-4 text-left w-1/12">Rating</th>
                <th class="p-4 text-left w-4/12">Komentar</th>
                <th class="p-4 text-left w-1/12">Sentimen (ML)</th>
                <th class="p-4 text-left w-1/12">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($reviews)): ?>
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-400">
                        Belum ada ulasan di database.
                    </td>
                </tr>
            <?php else:
                $no = 1;
                foreach ($reviews as $review): ?>
                    <tr class="border-b border-gray-700 hover:bg-gray-700">
                        <td class="p-4"><?php echo htmlspecialchars($no); ?></td>
                        <td class="p-4 align-top font-medium"><?php echo htmlspecialchars($review['game_title']); ?></td>
                        <td class="p-4 align-top"><?php echo htmlspecialchars($review['user_name']); ?></td>
                        <td class="p-4 align-top text-yellow-400"><?php echo $review['rating']; ?> ★</td>
                        <td class="p-4 align-top text-sm text-gray-300">
                            <?php echo nl2br(htmlspecialchars($review['comment_text'])); ?>
                        </td>
                        <td class="p-4 align-top">
                            <?php 
                            $sentiment = $review['sentiment'];
                            if ($sentiment == 'positive') {
                                echo '<span class="text-green-400">Positif</span>';
                            } elseif ($sentiment == 'negative') {
                                echo '<span class="text-red-400">Negatif</span>';
                            } else {
                                echo '<span class="text-gray-500">Netral</span>';
                            }
                            ?>
                        </td>
                        <td class="p-4 align-top">
                            <a href="index.php?page=review_hapus&id=<?php echo $review['id']; ?>" class="text-red-400 hover:text-red-300" onclick="return confirm('Anda yakin ingin menghapus ulasan ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php $no++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>