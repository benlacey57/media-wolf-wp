<?php
namespace MediaWolf;

class WooCommerce_Products {
    public static function init(): void {
        add_filter('woocommerce_product_tabs', [self::class, 'add_custom_product_tabs']);
    }

    public static function add_custom_product_tabs($tabs): array {
        $tabs['custom_tab_1'] = array(
            'title'    => __('Custom Tab', 'woocommerce'),
            'priority' => 30,
            'callback' => function() {
                return get_template_part(MEDIA_WOLF_PLUGIN_DIR . 'templates/woocommerce/tabs/custom-tab-1');
            }
        );

        $tabs['custom_tab_2'] = array(
            'title'    => __('Custom Tab 2', 'woocommerce'),
            'priority' => 40,
            'callback' => function() {
                return get_template_part(MEDIA_WOLF_PLUGIN_DIR . 'templates/woocommerce/tabs/custom-tab-2');
            }
        );
        return $tabs;
    }
}
