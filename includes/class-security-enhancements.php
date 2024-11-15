<?php

namespace MediaWolf\Includes;

use MediaWolf\Interfaces\PluginComponentInterface;

class SecurityEnhancements implements PluginComponentInterface
{
  const COMPONENT = 'security-enhancements';

  /**
   * Initialize security actions and hooks.
   */
  public static function init(): void
  {
    add_action('admin_menu', [self::class, 'dashboard_menu_link']);
    add_action('admin_init', [self::class, 'register_page_settings']);

    add_action('init', [self::class, 'remove_wordpress_version_query_strings']);

    // Core security features
    if (get_option('apply_csp_headers')) {
      add_action('wp_head', [self::class, 'apply_content_security_policy']);
    }

    if (get_option('disable_xml_rpc')) {
      add_action('init', [self::class, 'disable_xml_rpc']);
    }

    if(get_option('disable_wordpress_version')) {
      add_action('init', [self::class, 'disable_wordpress_version']);
    }

    if(get_option('disable_plugin_management')) {
      add_action('init', [self::class, 'disable_plugin_management']);
    }

    if(get_option('disable_file_editing')) {
      add_action('init', [self::class, 'disable_file_editing']);
    }
    
    // Apply settings-based security options
    self::apply_security_options();
  }

  /**
   * Add Security Options page under the Media Wolf menu.
   */
  public static function dashboard_menu_link(): void
  {
    add_submenu_page(
      'media-wolf',
      __('Security Options', 'media-wolf'),
      __('Security Options', 'media-wolf'),
      'manage_options',
      'media-wolf',
      [self::class, 'render_settings_page']
    );
  }

  /**
   * Register settings for the security options.
   */
  public static function register_settings(): void
  {
    register_setting('media-wolf-security-settings', 'disable_file_editing');
    register_setting('media-wolf-security-settings', 'disable_plugin_management');
    register_setting('media-wolf-security-settings', 'disable_wordpress_version');
    register_setting('media-wolf-security-settings', 'disable_xml_rpc');
    register_setting('media-wolf-security-settings', 'apply_csp_headers');
  }

  /**
   * Render the Security Options admin page.
   */
  public static function render_settings_page(): void
  {
    include MEDIA_WOLF_PLUGIN_DIR . '/admin/admin-' . self::COMPONENT . '-page.php';
  }

    public static function get_component_template_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT . '/';
    }

  /**
   * Disable file editing within the WordPress dashboard.
   */
  public static function disable_file_editing(): void
  {
    if (!defined('DISALLOW_FILE_EDIT')) {
      define('DISALLOW_FILE_EDIT', true);
    }
  }

  /**
   * Disable plugin management within the WordPress dashboard.
   */
  public static function disable_plugin_management(): void
  {
    if (!defined('DISALLOW_FILE_MODS')) {
      define('DISALLOW_FILE_MODS', true);
    }
  }

  /**
   * Remove WordPress version from the header.
   */
  public static function disable_wordpress_version(): void
  {
    add_filter('the_generator', '__return_empty_string');
  }

  /**
   * Remove WordPress version from query strings in scripts and styles.
   */
  public static function remove_wordpress_version_query_strings(): void
  {
    add_filter('script_loader_src', [self::class, 'remove_query_strings']);
    add_filter('style_loader_src', [self::class, 'remove_query_strings']);
  }

  public static function remove_query_strings($src)
  {
    return remove_query_arg('ver', $src);
  }

  /**
   * Disable XML-RPC to reduce the risk of brute-force attacks.
   */
  public static function disable_xml_rpc(): void
  {

    add_filter('xmlrpc_enabled', '__return_false');
  }

  /**
   * Apply Content Security Policy (CSP) headers to secure content.
   */
  public static function apply_content_security_policy(): void
  {
    $csp = [];

    $csp[] = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'";
    $csp[] = "img-src 'self'";
    $csp[] = "style-src 'self' 'unsafe-inline'";

    // Convert the array to a single string using implode
    $csp_string = implode('; ', $csp);

    // Set the CSP header
    header("Content-Security-Policy: $csp_string");
  }

  /**
   * Render a checkbox for each setting in the admin settings page.
   *
   * @param array $args Array with the name of the setting.
   */
  public static function render_checkbox($args)
  {
    $name = $args['name'];
    $value = get_option($name);
    echo '<input type="checkbox" name="' . esc_attr($name) . '" value="1" ' . checked(1, $value, false) . ' />';
  }
}

SecurityEnhancements::init();