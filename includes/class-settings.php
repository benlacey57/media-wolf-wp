<?php
namespace MediaWolf;

class Settings {

    /**
     * Initialize the settings.
     */
    public static function init(): void {
        add_action('admin_menu', [self::class, 'add_settings_page']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    /**
     * Add the main settings page and sub-pages.
     */
    public static function add_settings_page(): void {
        add_menu_page('Media Wolf', 'Media Wolf', 'manage_options', 'media-wolf', [self::class, 'render_main_page'], 'dashicons-admin-generic');
        add_submenu_page('media-wolf', 'Content Restriction', 'Content Restriction', 'manage_options', 'media-wolf-content-restriction', [self::class, 'render_content_restriction_page']);
        add_submenu_page('media-wolf', 'Members Content', 'Members Content', 'manage_options', 'media-wolf-members-content', [self::class, 'render_members_content_page']);
    }

    /**
     * Register settings fields for each sub-page.
     */
    public static function register_settings(): void {
        // Content restriction settings
        register_setting('media-wolf-content-restriction', 'media_wolf_login_page');
        register_setting('media-wolf-content-restriction', 'media_wolf_register_page');

        // Security facts settings
        register_setting('media-wolf-security-facts', 'media_wolf_facts_category');

        // Members content settings
        register_setting('media-wolf-members-content', 'media_wolf_member_role');

        // WooCommerce settings
        register_setting('media-wolf-woocommerce', 'media_wolf_woocommerce_customizations');
    }

    /**
     * Render the main settings page.
     */
    public static function render_main_page(): void {
        ?>
        <div class="wrap">
            <h1>Media Wolf Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('media-wolf-settings');
                do_settings_sections('media-wolf-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function render_content_restriction_page(): void {
        ?>
        <div class="wrap">
            <h1>Content Restriction Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('media-wolf-settings');
                do_settings_sections('media-wolf-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function render_members_content_page(): void {
        ?>
        <div class="wrap">
            <h1>Members Content Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('media-wolf-settings');
                do_settings_sections('media-wolf-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Activation hook
     */
    public static function activate(): void {
        // Add default settings and custom roles here
    }

    /**
     * Deactivation hook
     */
    public static function deactivate(): void {
        // Remove roles or any cleanup actions needed
    }
}
