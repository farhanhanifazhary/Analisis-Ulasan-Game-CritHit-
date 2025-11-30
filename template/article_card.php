
<div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden flex flex-col">
    <a href="article_detail.php?id=<?php echo $article['id']; ?>">
        <img 
            src="<?php echo htmlspecialchars($article['image_url']); ?>" 
            alt="<?php echo htmlspecialchars($article['title']); ?>"
            class="w-full h-48 object-cover"
        />
    </a>
    
    <div class="p-6 flex flex-col flex-grow">
        <h2 class="text-2xl font-bold text-white mb-2">
            <a href="article_detail.php?id=<?php echo $article['id']; ?>" class="hover:text-blue-400 transition-colors">
                <?php echo htmlspecialchars($article['title']); ?>
            </a>
        </h2>

        <?php if (!empty($related_games)): ?>
            <div class="mb-2 text-sm text-gray-400"> <?php
                // Buat link untuk setiap game
                $game_links = [];
                foreach ($related_games as $game) {
                    $game_links[] = '<p" class="hover">'.htmlspecialchars($game['title']).'</p>';
                }
                // Gabungkan dengan koma
                echo implode(', ', $game_links);
                ?>
            </div>
        <?php endif; ?>
        
        <div class="text-sm text-gray-400 mb-4">
            Oleh <?php echo htmlspecialchars($article['author_name']); ?> 
            <span class="mx-1">|</span>
            <?php echo date('d F Y', strtotime($article['created_at'])); ?>
        </div>

        <p class="text-gray-300 mb-4 flex-grow">
            <?php 
            // Panggil fungsi helper
            echo htmlspecialchars(get_snippet($article['content'], 150)); 
            ?>
        </p>
        
        <a href="article_detail.php?id=<?php echo $article['id']; ?>" class="text-blue-400 font-bold hover:text-blue-300 self-start">
            Baca Selengkapnya &rarr;
        </a>
    </div>
</div>