<?php
namespace MediaWolf;

use MediaWolf\PluginComponentInterface;

class WooCommerce_Customisations implements PluginComponentInterface {

    public static function init(): void {
        // Admin
        // add_action('init', [self::class, 'register_post_type']);  
        add_action('admin_init', [self::class, 'register_settings']);
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);

        // Assets
        // add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        // add_action('admin_enqueue_scripts', [self::class, 'enqueue_admin_assets']);
        

        // Add WooCommerce customization hooks
        self::product_tabs();
        self::customise_checkout_fields();
        self::custom_order_fields();
        // self::customize_email_templates();
        // self::customize_cart_and_checkout();
        // self::customize_account_pages();
        // self::customize_discounts_and_rules();        
    }

    /**
     * Add WooCommerce settings page under Media Wolf.
     */
    public static function dashboard_menu_link(): void
    {
        add_submenu_page('media-wolf', 'WooCommerce', 'WooCommerce Customisations', 'manage_options', 'media-wolf-woocommerce', [self::class, 'render_settings_page']);
    }

    /**
     * Register WooCommerce settings fields.
     */
    public static function register_page_settings(): void {
        // Register custom checkout field label
        register_setting('media-wolf-woocommerce-settings', 'media_wolf_custom_checkout_label', ['default' => 'Custom Field']);

        // Register custom discount percentage setting
        register_setting('media-wolf-woocommerce-settings', 'media_wolf_discount_percentage', ['default' => 10]);
    }

    /**
     * Render the WooCommerce customizations settings page.
     */
    public static function render_woocommerce_settings(): void {
        echo get_template_part(MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-woocommerce-page');
    }

    /**
     * Add a custom tabs to WooCommerce product pages.
     */
    public static function product_tabs(): void {
        add_filter('woocommerce_product_tabs', function($tabs) {
            $tabs['custom_tab_1'] = array(
                'title'    => __('Custom Tab', 'woocommerce'),
                'priority' => 30,
                'callback' => function() {
                    return get_template_part(MEDIA_WOLF_PLUGIN_DIR . 'includes/partials/woocommerce/tabs/custom-tab-1');
                }
            );

            $tabs['custom_tab_2'] = array(
                'title'    => __('Custom Tab 2', 'woocommerce'),
                'priority' => 40,
                'callback' => function() {
                    return get_template_part(MEDIA_WOLF_PLUGIN_DIR . 'includes/partials/woocommerce/tabs/custom-tab-2');
                }
            );
            return $tabs;
        });
    }

    /**
     * Customize checkout fields.
     */
    public static function customise_checkout_fields(): void {
        add_filter('woocommerce_checkout_fields', function($fields) {
            $label = get_option('media_wolf_custom_checkout_label', 'Custom Field');
            $fields['billing']['billing_custom_field'] = array(
                'type'        => 'text',
                'label'       => __($label, 'woocommerce'),
                'required'    => false,
                'class'       => array('form-row-wide'),
                'priority'    => 50,
            );
            return $fields;
        });
    }

    /**
     * Custom order fields in the WooCommerce admin area.
     */
    public static function custom_order_fields(): void {
        add_action('woocommerce_admin_order_data_after_billing_address', function($order) {
            echo '<p><strong>' . __('Custom Field') . ':</strong> ' . get_post_meta($order->get_id(), '_custom_field', true) . '</p>';
        });
    }

    /**
     * Customize WooCommerce email templates.
     */
    public static function customize_email_templates(): void {
        add_filter('woocommerce_email_order_meta', function($order, $sent_to_admin, $plain_text) {
            echo '<p><strong>' . __('Custom Message') . ':</strong> Thank you for shopping with us!</p>';
        }, 10, 3);
    }

    /**
     * Customize the cart and checkout pages.
     */
    public static function customize_cart_and_checkout(): void {
        add_action('woocommerce_cart_calculate_fees', function() {
            global $woocommerce;
            if (is_admin() && !defined('DOING_AJAX')) return;

            $custom_fee = 5.00; // Custom flat fee
            $woocommerce->cart->add_fee(__('Custom Fee', 'woocommerce'), $custom_fee, false, '');
        });
    }

    /**
     * Customize WooCommerce account pages.
     */
    public static function customize_account_pages(): void {
        add_filter('woocommerce_account_menu_items', function($items) {
            $items['custom-endpoint'] = __('Custom Endpoint', 'woocommerce');
            return $items;
        });

        add_action('woocommerce_account_custom-endpoint_endpoint', function() {
            echo '<h3>Custom Account Page</h3>';
            echo '<p>Here is some custom content for logged-in users.</p>';
        });
    }

    /**
     * Automatically apply voucher codes to the cart via URL parameter.
     * 
     * Example: https://example.com/cart/?apply_coupon=SUMMER10
     */
    public static function apply_voucher_via_url_parameter(): void {
        add_action('init', function() {
            $coupon_code_parameter = sanitize_text_field($_GET['apply_coupon']);

            // Check we have the coupon parameter in the request and check it's not already applied.
            // @TODO: Add a nonce check here to prevent CSRF attacks.
            // @TODO: Add a check to ensure the coupon is valid.
            if (
                isset($coupon_code_parameter) && 
                !WC()->cart->has_discount(sanitize_text_field($_GET['apply_coupon']))):
                WC()->cart->apply_coupon(sanitize_text_field($_GET['apply_coupon']));
            endif;
        });
}
