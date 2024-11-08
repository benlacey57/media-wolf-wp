<?php
namespace MediaWolf;

use MediaWolf\PluginComponentInterface;

class WooCommerce_Cart implements PluginComponentInterface {
    public static function init(): void {
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);
        add_action('admin_init', [self::class, 'register_page_settings']);
    }

    /**
     * Add a submenu for cart customisations under WooCommerce.
     */
    public static function dashboard_menu_link(): void {
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
    public static function register_page_settings(): void {
        register_setting('media-wolf-woocommerce-cart-settings', 'media_wolf_custom_cart_fee', [
          'default' => 5.00
        ]);
    }

    /**
     * Render the cart customisations settings page.
     */
    public static function render_settings_page(): void {
        ?>
        <div class="wrap">
            <h1><?php _e('Cart Customisations', 'media-wolf'); ?></h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields('media-wolf-woocommerce-cart-settings');
                    do_settings_sections('media-wolf-woocommerce-cart-settings');
                ?>

                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Custom Cart Fee (£)', 'media-wolf'); ?></th>
                        <td>
                            <input type="number" step="0.01" name="media_wolf_custom_cart_fee" value="<?php echo esc_attr(get_option('media_wolf_custom_cart_fee', 5.00)); ?>" />
                        </td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
