<div class="wrap">
    <h1><?php _e('Services Settings', 'media-wolf'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('media-wolf-services-settings');
        do_settings_sections('media-wolf-services-settings');
        submit_button();
        ?>
    </form>
</div>