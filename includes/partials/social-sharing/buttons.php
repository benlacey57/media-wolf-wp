<div class="media-wolf-social-buttons">
    <?php foreach ($platforms as $platform): ?>
      <?php
      $url = get_permalink();
      $title = get_the_title();
      $share_url = self::get_share_url($platform, $url, $title);
      ?>
      <a 
        href="<?php esc_url($share_url); ?>" 
        target="_blank" 
        class="social-button social<?php echo '-' . esc_attr($platform); ?>">
        <?php echo ucfirst($platform); ?>
      </a>
    <?php endforeach; ?>
</div>