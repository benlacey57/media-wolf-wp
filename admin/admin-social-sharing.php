<div class="wrap">
  <h1>Social Share Settings</h1>
  <form method="post" action="options.php">
    <?php
        settings_fields('media_wolf_social_sharing_settings');
        do_settings_sections('media-wolf-social-sharing');
        submit_button();
      ?>
  </form>
</div>