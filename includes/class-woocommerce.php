<?php
namespace MediaWolf;

use MediaWolf\PluginComponentInterface;

class WooCommerce implements PluginComponentInterface {

    public static function init(): void {
        add_action('admin_init', [self::class, 'register_page_settings']);
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);

        // Load other WooCommerce classes based on settings
        self::load_customizations();
    }

    /**
     * Add WooCommerce settings page under Media Wolf.
     */
    public static function dashboard_menu_link(): void {
        add_submenu_page(
            'media-wolf', 
            __('WooCommerce', 'media-wolf'),
            __('WooCommerce Settings', 'media-wolf'),
            'manage_options', 
            'media-wolf-woocommerce', 
            [self::class, 'render_settings_page']
        );
    }

    /**
     * Register WooCommerce settings fields.
     */
    public static function register_page_settings(): void {
        // Enable/Disable settings for various features
        register_setting('media-wolf-woocommerce-settings', 'enable_checkout_customizations');
        register_setting('media-wolf-woocommerce-settings', 'enable_cart_customizations');
        register_setting('media-wolf-woocommerce-settings', 'enable_email_customizations');
        register_setting('media-wolf-woocommerce-settings', 'enable_product_display_customizations');
        register_setting('media-wolf-woocommerce-settings', 'enable_account_customizations');
    }

    /**
     * Render the WooCommerce customizations settings page.
     */
    public static function render_settings_page(): void {
        include MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-woocommerce-page.php';
    }

    /**
     * Load other WooCommerce classes based on settings.
     */
    private static function load_customizations(): void {
        if (get_option('enable_checkout_customizations')) {
            require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/woocommerce/class-woocommerce-checkout.php';
            WooCommerce_Checkout::init();
        }

        if (get_option('enable_cart_customizations')) {
            require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/woocommerce/class-woocommerce-cart.php';
            WooCommerce_Cart::init();
        }

        if (get_option('enable_email_customizations')) {
            require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/woocommerce/class-woocommerce-emails.php';
            WooCommerce_Emails::init();
        }

        if (get_option('enable_product_display_customizations')) {
            require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/woocommerce/class-woocommerce-product-display.php';
            WooCommerce_Product_Display::init();
        }

        if (get_option('enable_account_customizations')) {
            require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/woocommerce/class-woocommerce-account.php';
            WooCommerce_Account::init();
        }
    }
}
