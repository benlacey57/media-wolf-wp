<?php

namespace MediaWolf\Includes;

use MediaWolf\Interfaces\PluginComponentInterface;

class PostTypes implements PluginComponentInterface
{
    const COMPONENT = 'post-types';

    public static function init(): void
    {
        // Admin
        add_action('admin_init', [self::class, 'register_settings']);
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);

        // Assets
        if(function_exists('enqueue_assets')) {
            add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        }

        if (function_exists('enqueue_admin_assets')) {
            add_action('admin_enqueue_scripts', [self::class, 'enqueue_admin_assets']);
        }
    }

    public static function dashboard_menu_link(): void
    {
        add_submenu_page(
            'media-wolf',
            __('Post Types', 'media-wolf'),
            __('Post Types', 'media-wolf'),
            'manage_options',
            'media-wolf',
            [self::class, 'render_settings_page']
        );
    }

    public static function render_settings_page(): void
    {
        include MEDIA_WOLF_PLUGIN_DIR . '/admin/admin-' . self::COMPONENT . '-page.php';
    }

    public static function register_settings(): void
    {
        $post_types = self::get_post_types();
        foreach ($post_types as $post_type) {
            register_setting('media_wolf_post_types', "enable_{$post_type}");
        }
    }

    public static function get_component_template_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT . '/';
    }

    public static function get_post_types(): array
    {
        $post_types = [];
        $files = glob(MEDIA_WOLF_PLUGIN_DIR . '/post-types/*.php');

        if(!$files) { return $post_types; }

        foreach ($files as $file):
            $post_types[] = basename($file, '.php');
        endforeach;

        return $post_types;
    }
}
