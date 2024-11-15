<?php

namespace MediaWolf\Includes\WooCommerce;

use MediaWolf\Interfaces\PluginComponentInterface;

class WooCommerce_Account implements PluginComponentInterface
{
    const COMPONENT = 'woocommerce-account';

    public static function init(): void
    {
        add_filter('woocommerce_account_menu_items', [self::class, 'add_custom_account_menu_items']);
        add_action('woocommerce_account_custom-endpoint_endpoint', [self::class, 'custom_account_page_content']);
    }

    public static function dashboard_menu_link(): void
    {
        add_submenu_page(
            'woocommerce',
            __('Account', 'media-wolf'),
            __('Account', 'media-wolf'),
            'manage_options',
            'media-wolf-woocommerce-account',
            [self::class, 'render_settings_page']
        );
    }

    public static function register_settings(): void
    {
        register_setting('media-wolf-woocommerce-account-settings', 'media_wolf_custom_account_field', ['default' => 'Custom Field']);
    }

    public static function render_settings_page(): void
    {
        require_once MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-woocommerce-account-page.php';
    }

    /**
     * Get the directory path for the component templates.
     */
    public static function get_component_template_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT;
    }

    public static function add_custom_account_menu_items($items): array
    {
        $items['custom-endpoint'] = __('Custom Endpoint', 'woocommerce');
        return $items;
    }

    public static function custom_account_page_content(): void
    {
        include self::get_component_template_dir() . '/account-page.php';
    }
}
