<div class="wrap">
  <h1>Content Restriction Settings</h1>
  <form method="post" action="options.php">
    <?php
    settings_fields('media-wolf-content-restriction');
    do_settings_sections('media-wolf-content-restriction');
    submit_button();
    ?>
  </form>
</div>