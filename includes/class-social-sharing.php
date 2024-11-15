<?php

namespace MediaWolf\Includes;

use MediaWolf\Interfaces\PluginComponentInterface;

class Social_Sharing implements PluginComponentInterface
{
  const COMPONENT = 'social-sharing';

  public static function init(): void
  {
    // Admin menu link
    add_action('admin_menu', [self::class, 'dashboard_menu_link']);

    // Register settings
    add_action('admin_init', [self::class, 'register_settings']);

    // Enqueue assets
    if (function_exists('enqueue_assets')) {
      add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
    }

    // Add sharing buttons to content
    add_action('the_content', [self::class, 'display_sharing_buttons']);
  }


  /**
   * Add the main settings page and sub-pages.
   */
  public static function dashboard_menu_link(): void
  {
    add_menu_page(
      __('Social Sharing', 'media-wolf'),
      __('Social Sharing', 'media-wolf'),
      'manage_options',
      'media-wolf',
      [self::class, 'render_settings_page'],
      'dashicons-admin-generic'
    );
  }

  /**
   * Render the main settings page.
   */
  public static function render_settings_page(): void
  {
    include MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-social-sharing-page.php';
  }

  /**
   * Register settings for the Social Sharing page.
   */
  public static function register_settings(): void
  {
    register_setting('media-wolf-social-sharing-settings', 'media_wolf_social_platforms');
    register_setting('media-wolf-social-sharing-settings', 'media_wolf_social_post_types');
    register_setting('media-wolf-social-sharing-settings', 'media_wolf_social_display_location');

    // Social platforms
    add_settings_field('social_platforms', __('Social Platforms', 'media-wolf'), function () {
      $platforms = [
        'facebook' => 'Facebook',
        'twitter' => 'Twitter',
        'linkedin' => 'LinkedIn',
        'pinterest' => 'Pinterest',
        'reddit' => 'Reddit',
        'email' => 'Email',
      ];
      $selected_platforms = get_option('media_wolf_social_platforms', []);

      foreach ($platforms as $key => $label) {
        $checked = in_array($key, $selected_platforms) ? 'checked' : '';
        echo '<label><input type="checkbox" name="media_wolf_social_platforms[]" value="' . esc_attr($key) . '" ' . $checked . '> ' . esc_html($label) . '</label><br>';
      }
    }, 'media-wolf-social-sharing', 'general_settings');

    // Post types
    add_settings_field('social_post_types', __('Enable on Post Types', 'media-wolf'), function () {
      $post_types = PostTypes::get_post_types();
      $selected_post_types = get_option('media_wolf_social_post_types', []);

      foreach ($post_types as $post_type) {
        $checked = in_array($post_type->name, $selected_post_types) ? 'checked' : '';
        echo '<label><input type="checkbox" name="media_wolf_social_post_types[]" value="' . esc_attr($post_type->name) . '" ' . $checked . '> ' . esc_html($post_type->label) . '</label><br>';
      }
    }, 'media-wolf-social-sharing', 'general_settings');

    // Display location
    add_settings_field('display_location', __('Display Location', 'media-wolf'), function () {
      $locations = [
        'top' => 'Top of Page',
        'bottom' => 'Bottom of Page',
        'top_and_bottom' => 'Top and Bottom of Page',
        'fixed_left' => 'Fixed Left',
        'fixed_right' => 'Fixed Right',
      ];
      $selected_location = get_option('media_wolf_social_display_location', 'bottom');

      echo '<select name="media_wolf_social_display_location">';
      foreach ($locations as $value => $label) {
        echo '<option value="' . esc_attr($value) . '" ' . selected($selected_location, $value, false) . '>' . esc_html($label) . '</option>';
      }
      echo '</select>';
    }, 'media-wolf-social-sharing', 'general_settings');
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

  /**
     * Get the directory path for the component templates.
     */
    public static function get_component_template_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT;
    }

  // Include the sharing buttons partial
  public static function display_sharing_buttons($content)
  {
    // Get settings
    $enabled_post_types = get_option('media_wolf_social_post_types', []);
    $display_location = get_option('media_wolf_social_display_location', 'top');
    $platforms = get_option('media_wolf_social_platforms', []);

    // Check if sharing is enabled for this post type
    if (!in_array(get_post_type(), $enabled_post_types)) {
      return $content;
    }

    // Include the sharing buttons partial
    ob_start();
    include self::get_component_template_dir() . '/buttons.php';
    $buttons = ob_get_clean();

    // Add buttons based on display location
    if ($display_location === 'top') {
      return $buttons . $content;
    } elseif ($display_location === 'bottom') {
      return $content . $buttons;
    } elseif ($display_location === 'top_and_bottom') {
        return $buttons . $content . $buttons;
    } elseif ($display_location === 'fixed_left' || $display_location === 'fixed_right') {
      $buttons = '<div class="media-wolf-social-fixed ' . esc_attr($display_location) . '">';
        $buttons .= $buttons;
      $buttons .= '</div>';

      add_action('wp_footer', function () use ($buttons) {
        echo $buttons;
      });

      return $content;
    }
  }

  public static function get_share_url($platform, $url, $title, $location)
  {
    $utm_source = 'social_' . $platform;
    $utm_medium = 'share_button';
    $utm_campaign = 'article_share';
    $utm_content = 'share_' . $location;

    switch ($platform) {
      case 'facebook':
        return 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url) . '&utm_source=' . $utm_source . '&utm_medium=' . $utm_medium . '&utm_campaign=' . $utm_campaign . '&utm_content=' . $utm_content;
      case 'twitter':
        return 'https://twitter.com/intent/tweet?text=' . urlencode($title) . '&url=' . urlencode($url) . '&utm_source=' . $utm_source . '&utm_medium=' . $utm_medium . '&utm_campaign=' . $utm_campaign . '&utm_content=' . $utm_content;
      case 'linkedin':
        return 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($url) . '&utm_source=' . $utm_source . '&utm_medium=' . $utm_medium . '&utm_campaign=' . $utm_campaign . '&utm_content=' . $utm_content;
      case 'pinterest':
        return 'https://pinterest.com/pin/create/button/?url=' . urlencode($url) . '&description=' . urlencode($title) . '&utm_source=' . $utm_source . '&utm_medium=' . $utm_medium . '&utm_campaign=' . $utm_campaign . '&utm_content=' . $utm_content;
      case 'reddit':
        return 'https://reddit.com/submit?url=' . urlencode($url) . '&title=' . urlencode($title) . '&utm_source=' . $utm_source . '&utm_medium=' . $utm_medium . '&utm_campaign=' . $utm_campaign . '&utm_content=' . $utm_content;
      case 'email':
        return 'mailto:?subject=' . rawurlencode($title) . '&body=' . rawurlencode($url) . '%0A%0A&utm_source=' . $utm_source . '&utm_medium=' . $utm_medium . '&utm_campaign=' . $utm_campaign . '&utm_content=' . $utm_content;
      default:
        return '';
    }
  }
}

Social_Sharing::init();