<?php
/**
 * Plugin Name: Media Wolf
 * Description: A custom plugin with features for content restriction, member content, and security facts.
 * Version: 1.0
 * Author: Ben Lacey
 * Requires PHP: 8.2
 */

defined('ABSPATH') || exit;

// For URLs
if (!defined('MEDIA_WOLF_PLUGIN_PATH')) {
    define('MEDIA_WOLF_PLUGIN_PATH', plugin_dir_url(__FILE__));
}

// For including PHP files
if (!defined('MEDIA_WOLF_PLUGIN_DIR')) {
    define('MEDIA_WOLF_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

// Include Interfaces
require_once MEDIA_WOLF_PLUGIN_DIR . 'interfaces/interface-plugin-component.php';

// Include Classes
require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/class-settings.php';
require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/class-content-restriction.php';
require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/class-security-facts.php';
require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/class-woocommerce.php';
require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/class-social-sharing.php';
require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/class-login.php';
require_once MEDIA_WOLF_PLUGIN_DIR . 'includes/class-security-enhancements.php';

// Autoload post type classes from the post-types directory
add_action('init', function () {
    $post_type_dir = MEDIA_WOLF_PLUGIN_PATH . 'post-types/';
    
    foreach (glob($post_type_dir . 'class-*.php') as $file) {
        require_once $file;
    }
});

// Activation & deactivation hooks
register_activation_hook(__FILE__, ['MediaWolf\\Settings', 'activate']);
register_deactivation_hook(__FILE__, ['MediaWolf\\Settings', 'deactivate']);

// Initialise plugin
function media_wolf_init() {
    MediaWolf\Settings::init();
    MediaWolf\Content_Restriction::init();
    MediaWolf\Security_Facts::init();
    MediaWolf\WooCommerce::init();
    MediaWolf\Social_Sharing::init();
    MediaWolf\Login::init();
    MediaWolf\SecurityEnhancements::init();
}
add_action('plugins_loaded', 'media_wolf_init');
