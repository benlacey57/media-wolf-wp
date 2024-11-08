<?php
$website_name = bloginfo( 'site_name' );
$bg_color = esc_attr(get_option('media_wolf_email_bg_color', '#f7f7f7'));
$text_color = esc_attr(get_option('media_wolf_email_text_color', '#333333'));
$header_text = esc_html(get_option('media_wolf_email_header_text', 'Welcome to Media Wolf'));
$logo_file = MEDIA_WOLF_PLUGIN_DIR . 'assets/images/logo.webp';
?>

<div style="background-color: <?php echo $bg_color; ?>; padding: 20px; text-align: center;">
  <div style="margin-bottom: 15px;">
    <?php
    if (file_exists($logo_file)):
        echo '<img src="' . esc_url($logo_file) . '" alt="' . $website_name . ' Logo" style="max-width: 150px;"></div>';
    else:
        echo '<p style="color: red;"></p>';
    endif;
    ?>
  </div>

  <h1 style="color: <?php echo $text_color; ?>; margin: 0; font-size: 24px;"><?php echo $header_text; ?></h1>
</div>
