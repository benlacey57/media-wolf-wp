<div class="wrap">
  <h1>Media Wolf Settings</h1>
  <form method="post" action="options.php">
    <?php
    settings_fields('media-wolf-settings');
    do_settings_sections('media-wolf-settings');
    submit_button();
    ?>
  </form>
</div>