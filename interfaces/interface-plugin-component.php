<?php

namespace MediaWolf\Interfaces;

interface PluginComponentInterface {
    /**
     * Initialize component actions and hooks.
     */
    public static function init(): void;

    /**
     * Register settings for the component.
     */
    public static function register_settings(): void;

    /**
     * Add the plugin settings link in the WP Dashboard.
     */
    public static function dashboard_menu_link(): void;

    /**
     * Get the directory path for the component templates.
     */
    public static function get_component_template_dir(): string;

    /**
     * Render the settings page for the component (if applicable).
     *
     * @return void
     */
    public static function render_settings_page();
}
