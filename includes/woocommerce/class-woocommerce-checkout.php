<?php
namespace MediaWolf;

use MediaWolf\PluginComponentInterface;
class WooCommerce_Checkout implements PluginComponentInterface {
    public static function init(): void {
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);
        add_action('admin_init', [self::class, 'register_page_settings']);
    }

    /**
     * Add a submenu for checkout customisations under WooCommerce.
     */
    public static function dashboard_menu_link(): void
    {
        add_submenu_page(
            'woocommerce', 
            __('Checkout', 'media-wolf'), 
            __('Checkout', 'media-wolf'), 
            'manage_options', 
            'media-wolf-woocommerce-checkout', 
            [self::class, 'render_settings_page']
        );
    }

    /**
     * Register settings specific to checkout customisations.
     */
    public static function register_page_settings(): void {
        register_setting('media-wolf-woocommerce-checkout-settings', 'media_wolf_custom_checkout_label', ['default' => 'Custom Field']);
    }

    /**
     * Render the checkout customisations settings page.
     */
    public static function render_settings_page(): void {
        require_once MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-woocommerce-checkout-page.php';
    }
}
