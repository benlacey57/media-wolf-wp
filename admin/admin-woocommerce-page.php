<div class="wrap">
  <h1>WooCommerce Customisations</h1>
  <form method="post" action="options.php">
    <?php
    settings_fields('media-wolf-woocommerce-settings');
    do_settings_sections('media-wolf-woocommerce-settings');

    // Custom Checkout Field Label
    echo '<h3>Checkout</h3>';
    echo '<label for="media_wolf_custom_checkout_label">Checkout Field Label:</label>';
    echo '<input type="text" name="media_wolf_custom_checkout_label" value="' . esc_attr(get_option('media_wolf_custom_checkout_label')) . '">';

    // Discount Percentage
    echo '<h3>Discount Settings</h3>';
    echo '<label for="media_wolf_discount_percentage">Discount Percentage:</label>';
    echo '<input type="number" name="media_wolf_discount_percentage" value="' . esc_attr(get_option('media_wolf_discount_percentage')) . '" min="0" max="100">';

    submit_button();
    ?>
  </form>
</div>