<div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden group flex flex-col h-full relative">
    <a href="detail.php?id=<?php echo $game['id']; ?>" class="flex-grow block">
        
        <img 
            src="<?php echo htmlspecialchars($game['image_url'] ?? 'https://via.placeholder.com/300x400.png?text=No+Image'); ?>" 
            alt="<?php echo htmlspecialchars($game['title']); ?>"
            class="w-full h-48 sm:h-64 object-cover group-hover:opacity-75 transition-opacity"
        />
        
        <div class="p-4 pb-12"> <h3 class="text-lg font-bold text-white truncate mb-1">
                <?php echo htmlspecialchars($game['title']); ?>
            </h3>
            
            <p class="text-sm text-gray-400 truncate mb-2" title="<?php echo htmlspecialchars(implode(', ', $publishers)); ?>">
                <?php 
                if (!empty($publishers)) {
                    echo htmlspecialchars(implode(', ', $publishers));
                } else {
                    echo 'Publisher Tidak Diketahui';
                }
                ?>
            </p>

            <div class="flex flex-wrap gap-1 h-6 overflow-hidden">
                <?php if (!empty($genres)): ?>
                    <?php foreach ($genres as $genre_name): ?>
                        <span class="text-[10px] bg-gray-700 text-gray-300 px-2 py-0.5 rounded border border-gray-600">
                            <?php echo htmlspecialchars($genre_name); ?>
                        </span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="text-xs text-gray-500">Tanpa Genre</span>
                <?php endif; ?>
            </div>
            
        </div>
    </a>

    <div class="absolute bottom-3 right-3 flex items-center gap-2">
        
        <?php if ($avg_rating > 0): ?>
            
            <?php if ($dominant_sentiment): ?>
                <?php
                    // Tentukan warna berdasarkan sentimen
                    $badge_color = 'bg-gray-600 text-gray-200'; // Default
                    $icon = 'fa-minus';
                    
                    if ($dominant_sentiment === 'positive') {
                        $badge_color = 'bg-green-900 text-green-300 border border-green-700';
                        $icon = 'fa-smile';
                    } elseif ($dominant_sentiment === 'negative') {
                        $badge_color = 'bg-red-900 text-red-300 border border-red-700';
                        $icon = 'fa-frown';
                    }
                ?>
                <div class="<?php echo $badge_color; ?> px-2 py-1 rounded text-xs font-semibold flex items-center gap-1 shadow-md">
                    <i class="fas <?php echo $icon; ?>"></i>
                    <span class="capitalize"><?php echo $dominant_sentiment; ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-gray-900 bg-opacity-90 px-2 py-1 rounded text-sm font-bold text-yellow-400 flex items-center gap-1 shadow-md border border-gray-700">
                <i class="fas fa-star"></i>
                <span><?php echo $avg_rating; ?></span>
            </div>

        <?php else: ?>
            <span class="text-xs text-gray-500 italic pr-1">Belum ada ulasan</span>
        <?php endif; ?>

    </div>
    </div>