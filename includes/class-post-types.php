<?php

namespace MediaWolf;

class PostTypes implements PluginComponentInterface
{
    public static function init(): void
    {
        add_action('admin_menu', [self::class, 'register_submenu']);
    }

    public static function dashboard_menu_link(): void
    {
        add_submenu_page(
            'media-wolf',
            __('Post Types', 'media-wolf'),
            __('Post Types', 'media-wolf'),
            'manage_options',
            'media-wolf-post-types',
            [self::class, 'render_post_types_page']
        );
    }

    public static function render_settings_page(): void
    {
        include MEDIA_WOLF_PLUGIN_DIR . '/admin/admin-post-types-page.php';
    }

    public static function register_page_settings(): void
    {
        $post_types = self::get_available_post_types();
        foreach ($post_types as $post_type) {
            register_setting('media_wolf_post_types', "enable_{$post_type}");
        }
    }
    public static function render_post_types_page(): void
    {
        $post_types = self::get_available_post_types();
        ?>
        <div class="wrap">
            <h1><?php _e('Manage Post Types', 'media-wolf'); ?></h1>
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
        <?php
    }

    public static function get_available_post_types(): array
    {
        $post_types = [];
        $files = glob(MEDIA_WOLF_PLUGIN_DIR . '/post-types/class-*.php');
        foreach ($files as $file) {
            $post_types[] = basename($file, '.php');
        }
        return $post_types;
    }
}