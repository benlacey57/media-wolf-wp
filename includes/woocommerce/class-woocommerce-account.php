<?php
namespace MediaWolf;

class WooCommerce_Account {
    public static function init(): void {
        add_filter('woocommerce_account_menu_items', [self::class, 'add_custom_account_menu_items']);
        add_action('woocommerce_account_custom-endpoint_endpoint', [self::class, 'custom_account_page_content']);
    }

    public static function add_custom_account_menu_items($items): array {
        $items['custom-endpoint'] = __('Custom Endpoint', 'woocommerce');
        return $items;
    }

    public static function custom_account_page_content(): void {
        echo '<h3>Custom Account Page</h3>';
        echo '<p>Here is some custom content for logged-in users.</p>';
    }
}
