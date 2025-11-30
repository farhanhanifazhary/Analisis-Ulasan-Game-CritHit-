<?php

// Data Chart

// Inisialisasi variabel untuk data chart
$chart_labels = [];
$chart_data = [];
$chart_colors = [];
$total_reviews = 0;
$error_db = '';

try {
    // Kueri untuk menghitung jumlah setiap sentimen
    $sql = "SELECT sentiment, COUNT(*) as count 
            FROM reviews 
            GROUP BY sentiment";
    
    $stmt = $pdo->query($sql);
    $sentiment_data = $stmt->fetchAll();

    // Tentukan pemetaan warna dan label
    $color_map = [
        'positive' => 'rgba(75, 192, 192, 0.8)', // Hijau
        'negative' => 'rgba(255, 99, 132, 0.8)', // Merah
        'neutral'  => 'rgba(201, 203, 207, 0.8)'  // Abu-abu
    ];
    $label_map = [
        'positive' => 'Positif',
        'negative' => 'Negatif',
        'neutral'  => 'Netral'
    ];

    // Proses data hasil kueri
    foreach ($sentiment_data as $row) {
        // Atasi jika 'sentiment' adalah NULL (misal: data lama)
        $sentiment = $row['sentiment'] ?? 'neutral'; 
        $count = (int)$row['count'];

        // Masukkan ke array data chart
        $chart_labels[] = $label_map[$sentiment];
        $chart_data[] = $count;
        $chart_colors[] = $color_map[$sentiment];
        $total_reviews += $count;
    }

} catch (PDOException $e) {
    $error_db = "Error mengambil data chart: " . $e->getMessage();
}

// Encode data PHP ke format JSON agar bisa dibaca JavaScript
$chart_labels_json = json_encode($chart_labels);
$chart_data_json = json_encode($chart_data);
$chart_colors_json = json_encode($chart_colors);
?>

<h1 class="text-3xl font-bold mb-6">Selamat Datang, <?php echo htmlspecialchars($user_name); ?>!</h1>
<p class="text-lg text-gray-300 mb-8">
    Berikut adalah ringkasan sentimen untuk total 
    <span class="font-bold text-white"><?php echo $total_reviews; ?></span> 
    ulasan yang ada di platform.
</p>

<?php if ($error_db): ?>
    <div class="bg-red-800 text-white p-4 rounded-lg mb-6">
        <?php echo $error_db; ?>
    </div>
<?php endif; ?>

<div class="bg-gray-700 p-6 rounded-lg shadow-lg max-w-3xl mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center">Analisis Sentimen Ulasan</h2>
    
    <?php if (empty($chart_data)): ?>
        <p class="text-center text-gray-400">Belum ada data ulasan untuk ditampilkan.</p>
    <?php else: ?>
        <div class="relative h-64 md:h-80">
            <canvas id="sentimentChart"></canvas>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    <?php if (!empty($chart_data)): ?>
    // Jalankan skrip hanya jika ada data

    // Ambil data yang sudah kita encode dari PHP
    const labels = <?php echo $chart_labels_json; ?>;
    const dataValues = <?php echo $chart_data_json; ?>;
    const colors = <?php echo $chart_colors_json; ?>;

    // Dapatkan elemen "kanvas"
    const ctx = document.getElementById('sentimentChart').getContext('2d');

    // Buat chart baru
    new Chart(ctx, {
        type: 'doughnut', // Tipe: 'pie' (solid) atau 'doughnut' (dengan lubang)
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Ulasan',
                data: dataValues,
                backgroundColor: colors,
                borderColor: '#374151', // bg-gray-700
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Penting agar pas di 'div'
            plugins: {
                legend: {
                    position: 'right', // Pindahkan legenda ke kanan
                    labels: {
                        color: '#E5E7EB' // text-gray-200
                    }
                },
                title: {
                    display: true,
                    text: 'Distribusi Sentimen Ulasan',
                    color: '#E5E7EB',
                    font: {
                        size: 16
                    }
                }
            }
        }
    });

    <?php endif; ?>
</script>