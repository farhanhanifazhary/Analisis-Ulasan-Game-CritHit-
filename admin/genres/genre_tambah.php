<?php
// Inisialisasi Variabel
$name = '';
$errors = [];

// Logika Proses Form (Hanya jika method = POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dari form
    $name = htmlspecialchars($_POST['name']);

    // Validasi
    if (empty($name)) {
        $errors[] = "Nama genre wajib diisi.";
    } else {
        // Validasi tambahan: Cek apakah genre sudah ada
        try {
            $sql_check = "SELECT id FROM genres WHERE name = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$name]);
            if ($stmt_check->rowCount() > 0) {
                $errors[] = "Nama genre '". htmlspecialchars($name) ."' sudah ada di database.";
            }
        } catch (PDOException $e) {
            $errors[] = "Error database: " . $e->getMessage();
        }
    }

    // C. Jika tidak ada error (Validasi Lolos)
    if (empty($errors)) {
        try {
            // Masukkan ke database
            $sql_insert = "INSERT INTO genres (name) VALUES (?)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([$name]);

            // Beri pesan sukses dan REDIRECT ke halaman daftar
            $_SESSION['success_message'] = "Genre '". htmlspecialchars($name) ."' berhasil ditambahkan!";
            // Redirect kembali ke halaman utama manajemen genre
            header('Location: index.php?page=manage_genres');
            exit; // Penting!

        } catch (PDOException $e) {
            // Jika insert gagal
            $errors[] = "Gagal menyimpan ke database: " . $e->getMessage();
        }
    }
}
?>

<a href="index.php?page=manage_genres" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Manajemen Genre</a>
<h1 class="text-3xl font-bold mb-6">Tambah Genre Baru</h1>

<?php
if (!empty($errors)) {
    echo '<div class="bg-red-800 border border-red-700 text-white p-4 rounded-lg mb-4">';
    echo '<ul class="list-disc pl-5">';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>'; // $error sudah di-htmlspecialchars saat dibuat
    }
    echo '</ul>';
    echo '</div>';
}
?>
<div class="bg-gray-700 rounded-lg shadow-lg p-8 max-w-lg">
    <form action="index.php?page=genre_tambah" method="POST">
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Genre</label>
            <input 
                type="text" id="name" name="name"
                value="<?php echo htmlspecialchars($name); ?>"
                class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
                placeholder="Contoh: RPG, FPS, Strategy"
                required 
            />
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <a href="index.php?page=manage_genres" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-500">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">
                Simpan Genre
            </button>
        </div>

    </form>
</div>