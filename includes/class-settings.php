<?php

namespace MediaWolf\Includes;

use MediaWolf\Interfaces\PluginComponentInterface;

class Settings implements PluginComponentInterface {
    const COMPONENT = 'settings';

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
        add_menu_page(
            __('Media Wolf', 'media-wolf'), 
            __('Media Wolf', 'media-wolf'), 
            'manage_options', 
            'media-wolf', 
            [self::class, 'render_settings_page'], 
            'dashicons-admin-generic'
        );
    }

    /**
     * Register settings fields for each sub-page.
     */
    public static function register_settings(): void {
        // General settings
        register_setting('media-wolf-settings', 'media_wolf_logo');
        register_setting('media-wolf-settings', 'media_wolf_favicon');
    }

    /**
     * Render the main settings page.
     */
    public static function render_settings_page(): void{
        include MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-settings-page.php';
    }

    /**
     * Render the plugin css and js files on the front-end.
     */
    public static function enqueue_assets(): void {
        // Enqueue any assets needed for the settings page
        enqueue_style('media-wolf', MEDIA_WOLF_PLUGIN_URL . 'assets/css/styles.css');
        enqueue_script('media-wolf', MEDIA_WOLF_PLUGIN_URL . 'assets/js/scripts.js');
    }

    /**
     * Enqueue the plugin css and js files in the dashboard.
     */
    public static function enqueue_admin_assets(): void {
        // Enqueue any assets needed for the settings page
        wp_enqueue_style('media-wolf-admin', MEDIA_WOLF_PLUGIN_URL . 'assets/css/dashboard.css');
        wp_enqueue_script('media-wolf-admin', MEDIA_WOLF_PLUGIN_URL . 'assets/js/dashboard.js');
    }

    public static function get_component_template_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT . '/';
    }
}

Settings::init();
