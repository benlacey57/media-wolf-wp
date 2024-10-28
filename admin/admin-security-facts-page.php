<div class="wrap">
  <h1>Security Facts Settings</h1>
  <form method="post" action="options.php">
    <?php
    settings_fields('media-wolf-settings');
    do_settings_sections('media-wolf-security-facts');
    submit_button();
    ?>
  </form>
</div>