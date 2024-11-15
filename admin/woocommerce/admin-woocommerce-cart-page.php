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