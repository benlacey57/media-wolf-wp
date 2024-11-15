<div class="wrap">
  <h1>Login Settings</h1>
  <form method="post" action="options.php">
    <?php
    settings_fields('media-wolf-login-settings');
    do_settings_sections('media-wolf-login-settings');
    submit_button();
    ?>
  </form>
</div>