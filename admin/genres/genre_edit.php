<?php

// Inisialisasi Variabel
$name = '';
$errors = [];

// Tentukan ID genre. Ambil dari POST (saat validasi gagal) atau GET (saat pertama dibuka)
$genre_id = (int)($_POST['genre_id'] ?? $_GET['id'] ?? 0);

if ($genre_id <= 0) {
    $_SESSION['error_message'] = "ID Genre tidak valid.";
    header('Location: index.php?page=manage_genres');
    exit;
}

// Logika Proses Form (Hanya jika method = POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dari form
    $name = htmlspecialchars($_POST['name']);

    // Validasi
    if (empty($name)) {
        $errors[] = "Nama genre wajib diisi.";
    } else {
        // Validasi tambahan: Cek apakah genre sudah ada
        // (tapi abaikan ID kita sendiri)
        try {
            $sql_check = "SELECT id FROM genres WHERE name = ? AND id != ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$name, $genre_id]);
            if ($stmt_check->rowCount() > 0) {
                $errors[] = "Nama genre '".htmlspecialchars($name)."' sudah ada di database.";
            }
        } catch (PDOException $e) {
            $errors[] = "Error database: " . $e->getMessage();
        }
    }

    // C. Jika TIDAK ADA ERROR (Validasi Lolos)
    if (empty($errors)) {
        try {
            // Update database
            $sql_update = "UPDATE genres SET name = ? WHERE id = ?";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([$name, $genre_id]);

            // Beri pesan sukses dan REDIRECT ke halaman daftar
            $_SESSION['success_message'] = "Genre '". htmlspecialchars($name) ."' berhasil di-update!";
            header('Location: index.php?page=manage_genres');
            exit; // Penting!

        } catch (PDOException $e) {
            // Jika update gagal
            $errors[] = "Gagal meng-update database: " . $e->getMessage();
        }
    }

} else {
    // Logika jika method = GET
    // User baru datang, ambil data dari DB
    try {
        $sql_get = "SELECT name FROM genres WHERE id = ?";
        $stmt_get = $pdo->prepare($sql_get);
        $stmt_get->execute([$genre_id]);
        $genre = $stmt_get->fetch();

        if ($genre) {
            $name = $genre['name'];
        } else {
            // Jika ID-nya tidak ditemukan
            $_SESSION['error_message'] = "Genre dengan ID $genre_id tidak ditemukan.";
            header('Location: index.php?page=manage_genres');
            exit;
        }
    } catch (PDOException $e) {
        $errors[] = "Gagal mengambil data: " . $e->getMessage();
    }
}
?>

<a href="index.php?page=manage_genres" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Manajemen Genre</a>
<h1 class="text-3xl font-bold mb-6">Edit Genre</h1>

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
    <form action="index.php?page=genre_edit" method="POST">
        
        <input type="hidden" name="genre_id" value="<?php echo $genre_id; ?>">

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Genre</label>
            <input 
                type="text" id="name" name="name"
                value="<?php echo htmlspecialchars($name); ?>"
                class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
                required 
            />
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <a href="index.php?page=manage_genres" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-500">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">
                Update Genre
            </button>
        </div>

    </form>
</div>