<?php

namespace MediaWolf;

use MediaWolf\PostTypes\ServicesPostType;
use MediaWolf\Interfaces\PluginComponentInterface;

class Services implements PluginComponentInterface
{
    const COMPONENT = 'services';

    public static function init(): void
    {
        add_action('init', [ServicesPostType::class, 'register_post_type']);
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);
        add_action('admin_init', [self::class, 'register_page_settings']);

        add_shortcode('list_services', [self::class, 'list_services_shortcode']);
        add_shortcode('related_services', [self::class, 'related_services_shortcode']);
    }

    public static function register_post_type(): void {
        ServicesPostType::register_post_type();
    }

    public static function register_settings(): void
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
        include MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-services-page.php';
    }

    public static function get_component_template_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT . '/';
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
}

Services::init();