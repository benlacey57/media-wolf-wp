<div class="wrap">
    <h1>Post Types</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('media_wolf_post_types');
        foreach ($post_types as $post_type) {
            $enabled = get_option("enable_{$post_type}", true);
            ?>
            <label>
                <input type="checkbox" name="enable_<?php echo esc_attr($post_type); ?>" <?php checked($enabled); ?>>
                <?php echo esc_html($post_type); ?>
            </label><br>
            <?php
        }
        submit_button(__('Save Changes', 'media-wolf'));
        ?>
    </form>
</div>