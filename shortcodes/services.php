<?php

namespace MediaWolf\Shortcodes;

use MediaWolf\PostTypes\ServicesPostType;
use MediaWolf\PostTypes\Services;
use MediaWolf\Interfaces\ShortcodeInterface;

class ServicesShortcode implements ShortcodeInterface
{
  const COMPONENT = 'services';

  /**
   * Register the shortcodes with WordPress.
   */
  public static function register_shortcode(): void
  {
    add_shortcode(self::COMPONENT, [self::class, 'render_shortcode']);
  }

  /**
   * Get the directory path for the component templates.
   */
  public static function get_component_template_dir(): string
  {
    return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT;
  }

  public static function list_services_shortcode($atts)
  {
    $services = ServicesPostType::get_all_posts();

    ob_start();
    include self::get_component_template_dir() . '/list.php';
    return ob_get_clean();
  }

  public static function related_services_shortcode()
  {
    if (!is_singular('services')) return '';

    $related_services = Services::get_related_services(get_the_ID());
    ob_start();
    include self::get_component_template_dir() . '/related.php';
    return ob_get_clean();
  }

  /**
   * Render the shortcode output.
   *
   * @param array $atts Attributes passed to the shortcode.
   * @param string|null $content Content within the shortcode tags, if any.
   * @return string The shortcode HTML content.
   */
  public static function render_shortcode($atts, $content = null): string
  {
    $atts = shortcode_atts(
      [
        'display' => 'list',   // Default display format: list. grid, carousel or image-text
        'count' => 12,         // Default number of security facts to display
        'pagination' => true   // Default pagination
      ],
      $atts,
      self::COMPONENT
    );

    // Fetch services
    $facts = get_posts([
      'post_type' => self::COMPONENT,
      'posts_per_page' => intval($atts['count']),
      'orderby' => 'rand'
    ]);

    if (empty($facts)): return [];
    endif;

    ob_start();

    // Load the template file based on display type
    if ($atts['display'] === 'grid') {
      include self::get_component_template_dir() . '/carousel.php';
    } elseif ($atts['display'] === 'image-text') {
      include self::get_component_template_dir() . '/image-text.php';
    } else {
      include self::get_component_template_dir() . '/default.php';
    }

    return ob_get_clean();
  }

  /**
   * Register settings for the shortcode.
   */
  public static function register_settings(): void
  {
    // Add settings in the admin panel for customising the shortcode behavior
    register_setting('media-wolf-settings', 'enable_related_services');
    register_setting('media-wolf-settings', 'related_services_post_count');
  }

  /**
   * Enqueue CSS for social sharing buttons.
   */
  public static function enqueue_assets(): void
  {
    $is_dev = strpos(home_url(), 'localhost') !== false || strpos(home_url(), 'staging') !== false;
    $file_suffix = $is_dev ? '' : '.min';

    wp_enqueue_style(
      'media-wolf-' . self::COMPONENT,
      MEDIA_WOLF_PLUGIN_PATH . "assets/css/" . self::COMPONENT . "$file_suffix.css"
    );
  }
}
