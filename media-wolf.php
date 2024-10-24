<?php
/**
 * Plugin Name: Media Wolf
 * Description: A custom plugin with features for content restriction, member content, and security facts.
 * Version: 1.0
 * Author: Ben Lacey
 * Requires PHP: 8.2
 */

defined('ABSPATH') || exit;

define('MEDIA_WOLF_PLUGIN_PATH', plugin_dir_path(__FILE__));

require_once MEDIA_WOLF_PLUGIN_PATH . 'includes/class-settings.php';
require_once MEDIA_WOLF_PLUGIN_PATH . 'includes/class-content-restriction.php';
require_once MEDIA_WOLF_PLUGIN_PATH . 'includes/class-security-facts.php';
require_once MEDIA_WOLF_PLUGIN_PATH . 'includes/class-woocommerce.php';

// Activation & deactivation hooks
register_activation_hook(__FILE__, ['MediaWolf\\Settings', 'activate']);
register_deactivation_hook(__FILE__, ['MediaWolf\\Settings', 'deactivate']);

// Initialize plugin
function media_wolf_init() {
    MediaWolf\Settings::init();
    MediaWolf\Content_Restriction::init();
    MediaWolf\Security_Facts::init();
    MediaWolf\WooCommerce_Customizations::init();
}
add_action('plugins_loaded', 'media_wolf_init');
