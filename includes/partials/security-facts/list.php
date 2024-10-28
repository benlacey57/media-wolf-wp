<div class="security-facts-list">
    <?php foreach ($facts as $fact): ?>
        <?php
            $fact_url = get_post_meta($fact->ID, 'fact_url', true);
            $category = get_post_meta($fact->ID, 'category', true);
            $content = $fact->post_content;
            $title = $fact->post_title;
        ?>
        <div class="security-fact">
            <h3><?php echo esc_html($title); ?></h3>
            <p><?php echo esc_html($content); ?></p>
            <?php if (!empty($category)): ?>
                <span class="category"><?php echo esc_html($category); ?></span>
            <?php endif; ?>
            <?php if (!empty($fact_url)): ?>
                <a href="<?php echo esc_url($fact_url); ?>" target="_blank" class="fact-link">Learn More</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
