<div class="wrap">
  <h1><?php _e('Email Customisations', 'media-wolf'); ?></h1>
  <form method="post" action="options.php">
    <?php
    settings_fields('media-wolf-woocommerce-email-settings');
    do_settings_sections('media-wolf-woocommerce-email-settings');
    ?>

    <h2><?php _e('Email Text', 'media-wolf'); ?></h2>
    <table class="form-table">
      <tr>
        <th scope="row"><?php _e('Header Text', 'media-wolf'); ?></th>
        <td><input type="text" name="media_wolf_email_header_text" value="<?php echo esc_attr(get_option('media_wolf_email_header_text')); ?>" /></td>
      </tr>
      <tr>
        <th scope="row"><?php _e('Footer Text', 'media-wolf'); ?></th>
        <td><input type="text" name="media_wolf_email_footer_text" value="<?php echo esc_attr(get_option('media_wolf_email_footer_text')); ?>" /></td>
      </tr>
    </table>

    <h2><?php _e('Email Appearance', 'media-wolf'); ?></h2>
    <table class="form-table">
      <tr>
        <th scope="row"><?php _e('Background Colour', 'media-wolf'); ?></th>
        <td><input type="color" name="media_wolf_email_bg_color" value="<?php echo esc_attr(get_option('media_wolf_email_bg_color')); ?>" /></td>
      </tr>
      <tr>
        <th scope="row"><?php _e('Text Colour', 'media-wolf'); ?></th>
        <td><input type="color" name="media_wolf_email_text_color" value="<?php echo esc_attr(get_option('media_wolf_email_text_color')); ?>" /></td>
      </tr>
      <tr>
        <th scope="row"><?php _e('Link Colour', 'media-wolf'); ?></th>
        <td><input type="color" name="media_wolf_email_link_color" value="<?php echo esc_attr(get_option('media_wolf_email_link_color')); ?>" /></td>
      </tr>
      <tr>
        <th scope="row"><?php _e('Font Family', 'media-wolf'); ?></th>
        <td><input type="text" name="media_wolf_email_font_family" value="<?php echo esc_attr(get_option('media_wolf_email_font_family')); ?>" /></td>
      </tr>
    </table>

    <h2><?php _e('Product Recommendations', 'media-wolf'); ?></h2>
    <table class="form-table">
      <tr>
        <th scope="row"><?php _e('Enable Upsells', 'media-wolf'); ?></th>
        <td><input type="checkbox" name="media_wolf_enable_upsells" value="1" <?php checked(1, get_option('media_wolf_enable_upsells'), true); ?> /></td>
      </tr>
      <tr>
        <th scope="row"><?php _e('Enable Cross-Sells', 'media-wolf'); ?></th>
        <td><input type="checkbox" name="media_wolf_enable_cross_sells" value="1" <?php checked(1, get_option('media_wolf_enable_cross_sells'), true); ?> /></td>
      </tr>
    </table>

    <?php submit_button(); ?>
  </form>
</div>