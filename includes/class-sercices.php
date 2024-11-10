<?php

namespace MediaWolf;

use MediaWolf\PostTypes\Services as ServicesPostType;
use MediaWolf\PluginComponentInterface;

class Services implements PluginComponentInterface
{
    public static function init(): void
    {
        add_action('init', [ServicesPostType::class, 'register_post_type']);
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);
        add_action('admin_init', [self::class, 'register_page_settings']);
        add_shortcode('list_services', [self::class, 'list_services_shortcode']);
        add_shortcode('related_services', [self::class, 'related_services_shortcode']);
    }

    public static function register_page_settings(): void
    {
        register_setting('media-wolf-services-settings', 'enable_linked_products');
    }

    public static function dashboard_menu_link(): void
    {
        add_submenu_page(
            'media-wolf',
            __('Services', 'media-wolf'),
            __('Services Settings', 'media-wolf'),
            'manage_options',
            'media-wolf-services',
            [self::class, 'render_settings_page']
        );
    }

    public static function render_settings_page(): void
    {
        ?>
        <div class="wrap">
            <h1><?php _e('Services Settings', 'media-wolf'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('media-wolf-services-settings');
                do_settings_sections('media-wolf-services-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public static function enqueue_assets(): void
    {
        // Optional asset enqueue logic specific to Services
    }

    public static function load_custom_templates($template): string
    {
        if (is_post_type_archive(ServicesPostType::get_post_type())) {
            $custom_template = MEDIA_WOLF_PLUGIN_DIR . '/templates/services/archive.php';
        } elseif (is_singular(ServicesPostType::get_post_type())) {
            $custom_template = MEDIA_WOLF_PLUGIN_DIR . '/templates/services/single.php';
        }

        return file_exists($custom_template) ? $custom_template : $template;
    }

    public static function list_services_shortcode($atts)
    {
        $services = Services::get_all_posts();
        ob_start();
        include MEDIA_WOLF_PLUGIN_DIR . 'includes/partials/services/list.php';
        return ob_get_clean();
    }

public static function related_services_shortcode()
{
    if (!is_singular('services')) return '';

    $related_services = Services::get_related_services(get_the_ID());
    ob_start();
    include MEDIA_WOLF_PLUGIN_DIR . 'includes/partials/services/related.php';
    return ob_get_clean();
}
}