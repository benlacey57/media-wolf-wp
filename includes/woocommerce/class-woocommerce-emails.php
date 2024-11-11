<?php
namespace MediaWolf;

use MediaWolf\PluginComponentInterface;

class WooCommerce_Emails implements PluginComponentInterface {
    public static function init(): void {
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);
        add_action('admin_init', [self::class, 'register_page_settings']);
    }

    /**
     * Add a submenu for email customisations under WooCommerce.
     */
    public static function dashboard_menu_link(): void {
        add_submenu_page(
            'woocommerce', 
            __('Email Customisations', 'media-wolf'), 
            __('Email Settings', 'media-wolf'), 
            'manage_options', 
            'media-wolf-woocommerce-emails', 
            [self::class, 'render_settings_page']
        );
    }

    /**
     * Register settings specific to email customisations.
     */
    public static function register_page_settings(): void {
      // Text settings
      register_setting('media-wolf-woocommerce-email-settings', 'media_wolf_email_header_text', ['default' => 'Welcome to Media Wolf']);
      register_setting('media-wolf-woocommerce-email-settings', 'media_wolf_email_footer_text', ['default' => 'Thank you for choosing us!']);

      // Color settings
      register_setting('media-wolf-woocommerce-email-settings', 'media_wolf_email_bg_color', ['default' => '#f7f7f7']);
      register_setting('media-wolf-woocommerce-email-settings', 'media_wolf_email_text_color', ['default' => '#333333']);
      register_setting('media-wolf-woocommerce-email-settings', 'media_wolf_email_link_color', ['default' => '#0073aa']);
      
      // Font settings
      register_setting('media-wolf-woocommerce-email-settings', 'media_wolf_email_font_family', ['default' => 'Arial, sans-serif']);
      
      // Upsell/Cross-sell options
      register_setting('media-wolf-woocommerce-email-settings', 'media_wolf_enable_upsells', ['default' => 1]);
      register_setting('media-wolf-woocommerce-email-settings', 'media_wolf_enable_cross_sells', ['default' => 1]);
  }

    /**
     * Render the email customisations settings page.
     */
    public static function render_settings_page(): void {
        require_once MEDIA_WOLF_PLUGIN_DIR . 'admin/woocommerce/admin-woocommerce-emails-page.php';
    }

    /**
     * Customize WooCommerce email templates.
     */
    public static function customize_email_templates(): void {
      add_filter('woocommerce_email_order_meta', function($order, $sent_to_admin, $plain_text) {
          ob_start();
          include MEDIA_WOLF_PLUGIN_DIR . 'templates/woocommerce/custom-email-template.php';
          echo ob_get_clean();
      }, 10, 3);
  }
  
}
