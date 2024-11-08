<div class="wrap">
  <h1><?php _e('Checkout Customisations', 'media-wolf'); ?></h1>
  <form method="post" action="options.php">
    <?php
    settings_fields('media-wolf-woocommerce-checkout-settings');
    do_settings_sections('media-wolf-woocommerce-checkout-settings');
    ?>

    <table class="form-table">
      <tr valign="top">
        <th scope="row"><?php _e('Custom Checkout Field Label', 'media-wolf'); ?></th>
        <td>
          <input type="text" name="media_wolf_custom_checkout_label" value="<?php echo esc_attr(get_option('media_wolf_custom_checkout_label', 'Custom Field')); ?>" />
        </td>
      </tr>
    </table>

    <?php submit_button(); ?>
  </form>
</div>