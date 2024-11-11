<div class="security-facts-list">
    <?php foreach ($facts as $fact): ?>
        <?php
            $fact_url = esc_url(get_post_meta($fact->ID, 'fact_url', true));
            $category = esc_html(get_post_meta($fact->ID, 'category', true));
            $content = esc_html($fact->post_content);
            $title = esc_html($fact->post_title);
        ?>
        <div class="security-fact">
            <h3><?php echo $title; ?></h3>
            <p><?php echo $content; ?></p>
            <?php if (!empty($category)): ?>
                <span class="category"><?php echo $category; ?></span>
            <?php endif; ?>
            <?php if (!empty($fact_url)): ?>
                <a href="<?php echo $fact_url; ?>" target="_blank" rel="noopener noreferrer" class="fact-link">
                    <?php esc_html_e('Learn More', 'media-wolf'); ?>
                </a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
