<div class="wrap">
  <h1>Security Settings</h1>
  <form method="post" action="options.php">
    <?php
    settings_fields('media-wolf-security-settings');
    do_settings_sections('media-wolf-security-settings');
    submit_button();
    ?>
  </form>
</div>