<?php
$footer_text = esc_html(get_option('media_wolf_email_footer_text', 'Thank you for choosing us!'));
$bg_color = esc_attr(get_option('media_wolf_email_bg_color', '#f7f7f7'));
$text_color = esc_attr(get_option('media_wolf_email_text_color', '#333333'));
$font_family = esc_attr(get_option('media_wolf_email_font_family', 'Arial, sans-serif'));

// Get WooCommerce and WordPress policy pages
$privacy_policy_url = get_privacy_policy_url();

$terms_page_id = get_option('woocommerce_terms_page_id');
$terms_page_url = $terms_page_id ? get_permalink($terms_page_id) : null;

$returns_page = get_page_by_title('Returns Policy'); // Customize title if needed
$returns_page_url = $returns_page ? get_permalink($returns_page->ID) : null;
?>

<div style="background-color: <?php echo $bg_color; ?>; padding: 20px; text-align: center; font-family: <?php echo $font_family; ?>;">
    <p style="color: <?php echo $text_color; ?>; font-size: 14px; margin: 0;">
        <?php echo $footer_text; ?>
    </p>

    <!-- Conditional policy links -->
    <div style="margin-top: 10px;">
        <?php if ($privacy_policy_url) : ?>
            <a href="<?php echo esc_url($privacy_policy_url); ?>" style="color: <?php echo $text_color; ?>; text-decoration: none; font-size: 12px; margin: 0 10px;">
                <?php _e('Privacy Policy', 'media-wolf'); ?>
            </a>
        <?php endif; ?>

        <?php if ($terms_page_url) : ?>
            <a href="<?php echo esc_url($terms_page_url); ?>" style="color: <?php echo $text_color; ?>; text-decoration: none; font-size: 12px; margin: 0 10px;">
                <?php _e('Terms and Conditions', 'media-wolf'); ?>
            </a>
        <?php endif; ?>

        <?php if ($returns_page_url) : ?>
            <a href="<?php echo esc_url($returns_page_url); ?>" style="color: <?php echo $text_color; ?>; text-decoration: none; font-size: 12px; margin: 0 10px;">
                <?php _e('Returns Policy', 'media-wolf'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>
