<?php
namespace MediaWolf;

class WooCommerce_Customizations {

    public static function init(): void {
        add_action('admin_menu', [self::class, 'add_settings_page']);
        add_action('admin_init', [self::class, 'register_settings']);
        
        // Add WooCommerce customization hooks
        self::customize_checkout_fields();
        self::customize_order_fields();
        self::customize_email_templates();
        self::customize_product_pages();
        self::customize_cart_and_checkout();
        self::customize_account_pages();
        self::customize_discounts_and_rules();
    }

    /**
     * Add WooCommerce settings page under Media Wolf.
     */
    public static function add_settings_page(): void {
        add_submenu_page('media-wolf', 'WooCommerce Customizations', 'WooCommerce Customizations', 'manage_options', 'media-wolf-woocommerce', [self::class, 'render_woocommerce_settings']);
    }

    /**
     * Register WooCommerce settings fields.
     */
    public static function register_settings(): void {
        // Register custom checkout field label
        register_setting('media-wolf-woocommerce-settings', 'media_wolf_custom_checkout_label', ['default' => 'Custom Field']);

        // Register custom discount percentage setting
        register_setting('media-wolf-woocommerce-settings', 'media_wolf_discount_percentage', ['default' => 10]);

        // Register custom tab toggle setting
        register_setting('media-wolf-woocommerce-settings', 'media_wolf_enable_custom_tab', ['default' => 1]);
    }

    /**
     * Render the WooCommerce customizations settings page.
     */
    public static function render_woocommerce_settings(): void {
        ?>
        <div class="wrap">
            <h1>WooCommerce Customizations</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('media-wolf-woocommerce-settings');
                do_settings_sections('media-wolf-woocommerce-settings');

                // Custom Checkout Field Label
                echo '<h3>Checkout Customizations</h3>';
                echo '<label for="media_wolf_custom_checkout_label">Checkout Field Label:</label>';
                echo '<input type="text" name="media_wolf_custom_checkout_label" value="' . esc_attr(get_option('media_wolf_custom_checkout_label')) . '">';

                // Discount Percentage
                echo '<h3>Discount Settings</h3>';
                echo '<label for="media_wolf_discount_percentage">Discount Percentage:</label>';
                echo '<input type="number" name="media_wolf_discount_percentage" value="' . esc_attr(get_option('media_wolf_discount_percentage')) . '" min="0" max="100">';

                // Enable Custom Product Tab
                echo '<h3>Product Page Customizations</h3>';
                echo '<label for="media_wolf_enable_custom_tab">Enable Custom Tab:</label>';
                echo '<input type="checkbox" name="media_wolf_enable_custom_tab" value="1"' . checked(1, get_option('media_wolf_enable_custom_tab'), false) . '>';
                
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    // WooCommerce Customizations

    /**
     * Customize checkout fields.
     */
    public static function customize_checkout_fields(): void {
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
     * Customize order fields in the WooCommerce admin area.
     */
    public static function customize_order_fields(): void {
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
     * Customize the front-end shop and product pages.
     */
    public static function customize_product_pages(): void {
        $enable_custom_tab = get_option('media_wolf_enable_custom_tab', 1);
        
        if ($enable_custom_tab) {
            add_filter('woocommerce_product_tabs', function($tabs) {
                $tabs['custom_tab'] = array(
                    'title'    => __('Custom Tab', 'woocommerce'),
                    'priority' => 50,
                    'callback' => function() {
                        echo '<h2>' . __('Custom Product Information', 'woocommerce') . '</h2>';
                        echo '<p>Here is some additional information about this product.</p>';
                    }
                );
                return $tabs;
            });
        }
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
     * Apply product-specific discounts or rules.
     */
    public static function customize_discounts_and_rules(): void {
        add_action('woocommerce_before_calculate_totals', function($cart) {
            $discount_percentage = get_option('media_wolf_discount_percentage', 10);
            
            foreach ($cart->get_cart() as $cart_item) {
                if ($cart_item['quantity'] >= 2) {
                    $discounted_price = $cart_item['data']->get_price() * ((100 - $discount_percentage) / 100);
                    $cart_item['data']->set_price($discounted_price); // Apply discount
                }
            }
        });
    }
}
