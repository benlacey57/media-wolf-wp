<div class="media-wolf-social-buttons">
    <?php foreach ($platforms as $platform): ?>
      <?php
      $url = get_permalink();
      $title = get_the_title();
      $location = get_option('media_wolf_social_display_location', 'bottom');
      $share_url = self::get_share_url($platform, $url, $title, $location);
      ?>
      <a 
        href="<?php esc_url($share_url); ?>" 
        target="_blank" 
        class="social-button social<?php echo '-' . esc_attr($platform); ?>">
        <?php echo ucfirst($platform); ?>
      </a>&nbsp;&nbsp;
    <?php endforeach; ?>
</div>