<?php
$bg_color = esc_attr(get_option('media_wolf_email_bg_color', '#f7f7f7'));
?>

<div style="background-color: <?php echo $bg_color; ?>; width: 100%; max-width: 600px; margin: 0 auto;">

    <?php
    // Include header with file existence check
    $header_file = MEDIA_WOLF_PLUGIN_DIR . 'includes/partials/emails/header.php';
    if (file_exists($header_file)) {
        include $header_file;
    } else {
        echo '<p style="color: red; text-align: center;">Header file is missing.</p>';
    }
    ?>

    <?php
    // Set up the main email body content
    ob_start();
    do_action('woocommerce_email_content'); // Assuming WooCommerce passes main content here
    $email_body_content = ob_get_clean();

    // Include body with file existence check
    $body_file = MEDIA_WOLF_PLUGIN_DIR . 'includes/partials/emails/body.php';
    if (file_exists($body_file)) {
        include $body_file;
    } else {
        echo '<p style="color: red; text-align: center;">Body file is missing.</p>';
    }
    ?>

    <?php
    // Include footer with file existence check
    $footer_file = MEDIA_WOLF_PLUGIN_DIR . 'includes/partials/emails/footer.php';
    if (file_exists($footer_file)) {
        include $footer_file;
    } else {
        echo '<p style="color: red; text-align: center;">Footer file is missing.</p>';
    }
    ?>

</div>
