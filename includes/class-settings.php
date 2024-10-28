<?php
namespace MediaWolf;

use MediaWolf\PluginComponentInterface;
class Settings implements PluginComponentInterface {

    /**
     * Initialize the settings.
     */
    public static function init(): void {
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    /**
     * Add the main settings page and sub-pages.
     */
    public static function dashboard_menu_link(): void {
        add_menu_page('Media Wolf', 'Media Wolf', 'manage_options', 'media-wolf', [self::class, 'render_settings_page'], 'dashicons-admin-generic');
    }

    /**
     * Register settings fields for each sub-page.
     */
    public static function register_settings(): void {
        // General settings
        register_setting('media-wolf-general', 'media_wolf_logo');
        register_setting('media-wolf-general', 'media_wolf_favicon');
    }

    /**
     * Render the main settings page.
     */
    public static function render_settings_page(): void{
        echo get_template_part(MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-settings-page');
    }

    /**
     * Render the plugin css and js files on the front-end.
     */
    public static function enqueue_assets(): void {
        // Enqueue any assets needed for the settings page
    }

    /**
     * Enqueue the plugin css and js files in the dashboard.
     */
}
