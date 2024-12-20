<?php

namespace MediaWolf\Includes;

use MediaWolf\Interfaces\PluginComponentInterface;

class Content_Restriction implements PluginComponentInterface
{
    const COMPONENT = 'content-restriction';

    public static function init(): void
    {
        // Admin
        // add_action('init', [self::class, 'register_post_type']);  
        add_action('admin_init', [self::class, 'register_settings']);
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);

        // Assets
        if (function_exists('enqueue_assets')) {
            add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        }

        if (function_exists('enqueue_admin_assets')) {
            add_action('admin_enqueue_scripts', [self::class, 'enqueue_admin_assets']);
        }

        // TODO: REPLACE THIS WITH SHORTCODE CLASS
        add_shortcode('restrict_content', [self::class, 'restrict_content_shortcode']);
        add_filter('the_content', [self::class, 'restrict_post_content_filter']);
    }

    /**
     * Add the Content Restriction settings page under Media Wolf in the dashboard
     */
    public static function dashboard_menu_link(): void
    {
        add_submenu_page(
            'media-wolf',
            __('Content Restriction', 'media-wolf'),
            __('Content Restriction', 'media-wolf'),
            'manage_options',
            'media-wolf',
            [
                self::class,
                'render_settings_page'
            ]
        );
    }

    /**
     * Register settings fields for each sub-page.
     */
    public static function register_settings(): void
    {
        register_setting('media-wolf-content-restriction', 'media_wolf_login_page');
        register_setting('media-wolf-content-restriction', 'media_wolf_register_page');
    }

    public static function render_settings_page(): void
    {
        include MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-' . self::COMPONENT . '-page.php';
    }

    public static function get_component_includes_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'includes/' . self::COMPONENT . '/';
    }

    public static function get_component_template_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT . '/';
    }

    /**
     * Enqueue CSS and JS assets for the component
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

    public static function restricted_content_notice(): string
    {
        $restricted_content_block = get_option('media_wolf_restricted_content_block');

        ob_start();
        include self::get_component_template_dir() . '/front-end-notice.php';
        return ob_get_clean();
    }

    public static function restrict_content_shortcode($atts, $content = ''): string
    {
        if (!is_user_logged_in()) {
            $login_url = esc_url(get_permalink(get_option('media_wolf_login_page')));
            $register_url = esc_url(get_permalink(get_option('media_wolf_register_page')));

            ob_start();
            include self::get_component_template_dir() . '/shortcode-message.php';
            return ob_get_clean();
        }

        return $content;
    }


    /**
     * Filter the post content to restrict it if necessary.
     */
    public static function restrict_post_content_filter($content): string
    {
        global $post;
        $is_restricted = get_post_meta($post->ID, 'restricted_post', true);
        $word_limit = get_option('media_wolf_restricted_word_count', 50); // Default to 50 words

        if ($is_restricted && !is_user_logged_in()) {
            if (strpos($content, '<!--readmore-->') !== false) {
                $parts = explode('<!--readmore-->', $content);
                return $parts[0] . self::restricted_content_notice();
            } else {
                $trimmed_content = wp_trim_words($content, $word_limit);
                return $trimmed_content . self::restricted_content_notice();
            }
        }
        return $content;
    }
}

Content_Restriction::init();
