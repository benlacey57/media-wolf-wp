<div class="wrap">
    <h1><?php _e('WooCommerce Settings', 'media-wolf'); ?></h1>
    <form method="post" action="options.php">
        <?php
            settings_fields('media-wolf-woocommerce-settings');
            do_settings_sections('media-wolf-woocommerce-settings');
        ?>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Enable Checkout Customisations', 'media-wolf'); ?></th>
                <td>
                    <input type="checkbox" name="enable_checkout_customisations" value="1" <?php checked(1, get_option('enable_checkout_customizations'), true); ?> />
                    <label for="enable_checkout_customisations"><?php _e('Enable custom checkout fields and layout', 'media-wolf'); ?></label>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Enable Cart Customisations', 'media-wolf'); ?></th>
                <td>
                    <input type="checkbox" name="enable_cart_customisations" value="1" <?php checked(1, get_option('enable_cart_customizations'), true); ?> />
                    <label for="enable_cart_customisations"><?php _e('Enable custom cart fees and voucher handling', 'media-wolf'); ?></label>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Enable Email Customisations', 'media-wolf'); ?></th>
                <td>
                    <input type="checkbox" name="enable_email_customisations" value="1" <?php checked(1, get_option('enable_email_customizations'), true); ?> />
                    <label for="enable_email_customisations"><?php _e('Customise WooCommerce email templates', 'media-wolf'); ?></label>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Enable Product Customisations', 'media-wolf'); ?></th>
                <td>
                    <input type="checkbox" name="enable_product_customisations" value="1" <?php checked(1, get_option('enable_product_display_customizations'), true); ?> />
                    <label for="enable_product_customisations"><?php _e('Add custom tabs and features to product pages', 'media-wolf'); ?></label>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><?php _e('Enable Account Customisations', 'media-wolf'); ?></th>
                <td>
                    <input type="checkbox" name="enable_account_customisations" value="1" <?php checked(1, get_option('enable_account_customizations'), true); ?> />
                    <label for="enable_account_customisations"><?php _e('Add custom account pages and options', 'media-wolf'); ?></label>
                </td>
            </tr>
        </table>

        <?php submit_button(); ?>
    </form>
</div>
