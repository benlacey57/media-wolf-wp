<?php

namespace MediaWolf;

interface PluginComponentInterface {
    /**
     * Initialize component actions and hooks.
     */
    public static function init(): void;

    /**
     * Register settings for the component.
     */
    public static function register_page_settings(): void;

    /**
     * Add the plugin settings link in the WP Dashboard.
     */
    public static function dashboard_menu_link(): void;

    /**
     * Render the settings page for the component (if applicable).
     *
     * @return void
     */
    public static function render_settings_page(): void;
}
