<?php
$site_name = esc_html(get_bloginfo('name'));
$email_heading = esc_html($email_heading);
?>

<div style="text-align: center;">
    <img src="<?php echo esc_url(get_option('media_wolf_email_logo')); ?>" alt="<?php echo $site_name; ?>" width="150" />
</div>
<h1><?php echo $email_heading; ?></h1>
<div style="padding: 20px; background-color: #f9f9f9;">
    <p><?php esc_html_e('Hello,', 'media-wolf'); ?></p>
    <p><?php esc_html_e('Thank you for your purchase! Here is your order summary.', 'media-wolf'); ?></p>
</div>
<footer style="padding: 10px; background-color: #333; color: #fff;">
    <p style="text-align: center;"><?php echo esc_html(sprintf(__('Thank you for choosing %s', 'media-wolf'), $site_name)); ?></p>
</footer>
