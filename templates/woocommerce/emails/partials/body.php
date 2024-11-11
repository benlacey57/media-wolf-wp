<?php
$text_color = esc_attr(get_option('media_wolf_email_text_color', '#333333'));
$font_family = esc_attr(get_option('media_wolf_email_font_family', 'Arial, sans-serif'));
?>

<div style="font-family: <?php echo $font_family; ?>; color: <?php echo $text_color; ?>; padding: 20px;">
    <?php
    // Display main content or fallback message if missing
    if(isset($email_body_content)):
      echo $email_body_content;
    endif;
    ?>
</div>
