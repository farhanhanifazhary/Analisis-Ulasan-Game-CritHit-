<?php
// File ini di-include di index.php dan search.php

try {
    // Hanya ambil genre
    $all_genres = $pdo->query("SELECT * FROM genres ORDER BY name ASC")->fetchAll();
} catch (PDOException $e) {
    $all_genres = [];
}

$current_genre = isset($_GET['genre']) ? (int)$_GET['genre'] : '';
$current_query = isset($_GET['query']) ? htmlspecialchars($_GET['query']) : '';
$form_action = (basename($_SERVER['PHP_SELF']) == 'search.php') ? 'search.php' : 'index.php';

// Cek apakah ada filter aktif
$is_filtering = !empty($current_genre);
?>

<div class="container mx-auto px-4 mb-8 flex flex-col items-end relative z-20">
    
    <button onclick="toggleFilterMenu()" 
            class="rounded-full p-4 shadow-lg transition-all transform hover:scale-110 focus:outline-none
            <?php echo $is_filtering ? 'bg-blue-600 text-white ring-4 ring-blue-900' : 'bg-gray-800 text-gray-400 hover:text-white'; ?>">
        <i class="fas fa-filter text-2xl"></i>
    </button>

    <div id="filterMenu" class="hidden absolute top-20 mt-2 bg-gray-800 p-6 rounded-xl shadow-2xl border border-gray-700 w-full max-w-xs animate-fade-in-down right-5 origin-top-right">
        
        <form action="<?php echo $form_action; ?>" method="GET" class="flex flex-col gap-4">
            
            <?php if (!empty($current_query)): ?>
                <input type="hidden" name="query" value="<?php echo $current_query; ?>">
            <?php endif; ?>

            <div>
                <label class="block text-sm text-gray-400 mb-1 ml-1">Pilih Genre</label>
                <select name="genre" class="w-full bg-gray-900 text-white p-3 rounded-lg border border-gray-600 focus:border-blue-500 outline-none">
                    <option value="">Semua Genre</option>
                    <?php foreach ($all_genres as $g): ?>
                        <option value="<?php echo $g['id']; ?>" <?php if ($current_genre == $g['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($g['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Terapkan
                </button>
                
                <?php if ($is_filtering): ?>
                    <a href="<?php echo $form_action . ($current_query ? '?query=' . $current_query : ''); ?>" 
                       class="flex-1 bg-gray-700 hover:bg-red-600 text-gray-300 hover:text-white font-bold py-2 px-4 rounded-lg text-center transition-colors">
                        Reset
                    </a>
                <?php else: ?>
                    <button type="button" onclick="toggleFilterMenu()" class="flex-1 bg-gray-700 hover:bg-gray-600 text-gray-300 font-bold py-2 px-4 rounded-lg transition-colors">
                        Tutup
                    </button>
                <?php endif; ?>
            </div>

        </form>
        
        <div class="absolute -top-2 right-5 w-4 h-4 bg-gray-800 border-t border-l border-gray-700 rotate-45"></div>
    </div>

</div>

<script>
    function toggleFilterMenu() {
        const menu = document.getElementById('filterMenu');
        menu.classList.toggle('hidden');
    }
</script>