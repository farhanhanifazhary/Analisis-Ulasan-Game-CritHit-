<?php
// Inisialisasi Variabel
$name = '';
$country = '';
$errors = [];

// Tentukan ID. Ambil dari POST (saat validasi gagal) atau GET (saat pertama dibuka)
$publisher_id = (int)($_POST['publisher_id'] ?? $_GET['id'] ?? 0);

if ($publisher_id <= 0) {
    $_SESSION['error_message'] = "ID Publisher tidak valid.";
    header('Location: index.php?page=manage_publishers');
    exit;
}

// Logika Proses Form (Hanya jika method = POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil data dari form
    $name = htmlspecialchars($_POST['name']);
    $country = htmlspecialchars($_POST['country']);

    // Validasi
    if (empty($name)) {
        $errors[] = "Nama publisher wajib diisi.";
    } else {
        // Validasi tambahan: Cek apakah nama sudah ada (tapi abaikan ID kita sendiri)
        try {
            $sql_check = "SELECT id FROM publisher WHERE name = ? AND id != ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$name, $publisher_id]);
            if ($stmt_check->rowCount() > 0) {
                $errors[] = "Nama publisher '".htmlspecialchars($name)."' sudah ada di database.";
            }
        } catch (PDOException $e) {
            $errors[] = "Error database: " . $e->getMessage();
        }
    }

    // C. Jika tidak ada error (Validasi Lolos)
    if (empty($errors)) {
        try {
            // Update database
            $sql_update = "UPDATE publisher SET name = ?, country = ? WHERE id = ?";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([$name, $country, $publisher_id]);

            // Beri pesan sukses dan REDIRECT ke halaman daftar
            $_SESSION['success_message'] = "Publisher '". htmlspecialchars($name) ."' berhasil di-update!";
            header('Location: index.php?page=manage_publishers');
            exit; // Penting!

        } catch (PDOException $e) {
            // Jika update gagal
            $errors[] = "Gagal meng-update database: " . $e->getMessage();
        }
    }

} else {
    // Logika jika method = GET
    try {
        $sql_get = "SELECT name, country FROM publisher WHERE id = ?";
        $stmt_get = $pdo->prepare($sql_get);
        $stmt_get->execute([$publisher_id]);
        $publisher = $stmt_get->fetch();

        if ($publisher) {
            $name = $publisher['name'];
            $country = $publisher['country'];
        } else {
            // Jika ID-nya tidak ditemukan
            $_SESSION['error_message'] = "Publisher dengan ID $publisher_id tidak ditemukan.";
            header('Location: index.php?page=manage_publishers');
            exit;
        }
    } catch (PDOException $e) {
        $errors[] = "Gagal mengambil data: " . $e->getMessage();
    }
}
?>

<a href="index.php?page=manage_publishers" class="text-blue-400 hover:text-blue-300 mb-6 inline-block">&larr; Kembali ke Manajemen Publisher</a>
<h1 class="text-3xl font-bold mb-6">Edit Publisher</h1>

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
    <form action="index.php?page=publisher_edit" method="POST">
        
        <input type="hidden" name="publisher_id" value="<?php echo $publisher_id; ?>">

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nama Publisher</label>
            <input 
                type="text" id="name" name="name"
                value="<?php echo htmlspecialchars($name); ?>"
                class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
                required 
            />
        </div>

        <div class="mb-4">
            <label for="country" class="block text-sm font-medium text-gray-300 mb-2">Negara Asal</label>
            <input 
                type="text" id="country" name="country"
                value="<?php echo htmlspecialchars($country); ?>"
                class="block w-full p-3 rounded-lg bg-gray-600 border border-gray-500"
            />
        </div>

        <div class="flex justify-end space-x-4 mt-6">
            <a href="index.php?page=manage_publishers" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-500">
                Batal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700">
                Update Publisher
            </button>
        </div>

    </form>
</div>