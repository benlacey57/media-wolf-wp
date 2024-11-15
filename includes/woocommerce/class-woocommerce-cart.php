<?php

namespace MediaWolf\Includes\WooCommerce;

use MediaWolf\Interfaces\PluginComponentInterface;

class WooCommerce_Cart implements PluginComponentInterface
{
    const COMPONENT = 'woocommerce-cart';

    public static function init(): void
    {
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    /**
     * Add a submenu for cart customisations under WooCommerce.
     */
    public static function dashboard_menu_link(): void
    {
        add_submenu_page(
            'woocommerce',
            __('Cart', 'media-wolf'),
            __('Cart', 'media-wolf'),
            'manage_options',
            'media-wolf-woocommerce-cart',
            [self::class, 'render_settings_page']
        );
    }

    /**
     * Register settings specific to cart customisations.
     */
    public static function register_settings(): void
    {
        register_setting('media-wolf-woocommerce-cart-settings', 'media_wolf_custom_cart_fee', [
            'default' => 5.00
        ]);
    }

    /**
     * Get the directory path for the component templates.
     */
    public static function get_component_template_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT;
    }

    /**
     * Render the cart customisations settings page.
     */
    public static function render_settings_page(): void
    {
        require_once MEDIA_WOLF_PLUGIN_DIR . 'admin/woocommeerce/admin-woocommerce-cart-page.php';
    }
}
