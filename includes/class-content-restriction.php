<?php
namespace MediaWolf;

use MediaWolf\PluginComponentInterface;

class Content_Restriction implements PluginComponentInterface {

    public static function init(): void {
        // Admin
        // add_action('init', [self::class, 'register_post_type']);  
        add_action('admin_init', [self::class, 'register_settings']);
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);

        // Assets
        // add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        // add_action('admin_enqueue_scripts', [self::class, 'enqueue_admin_assets']);

        add_shortcode('restrict_content', [self::class, 'restrict_content_shortcode']);
        add_filter('the_content', [self::class, 'restrict_post_content_filter']);
    }

    /**
     * Add the Content Restriction settings page under Media Wolf in the dashboard
     */
    public static function dashboard_menu_link(): void {
        add_submenu_page('media-wolf', 'Content Restriction', 'Content Restriction', 'manage_options', 'media-wolf-content-restriction', [self::class, 'render_settings_page']);
    }

    /**
     * Register settings fields for each sub-page.
     */
    public static function register_page_settings(): void {
        register_setting('media-wolf-content-restriction', 'media_wolf_login_page');
        register_setting('media-wolf-content-restriction', 'media_wolf_register_page');
    }

    public static function render_settings_page(): void{
        echo get_template_part(MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-content-restriction-page');
    }

    private static function restricted_content_notice(): string {
        $restricted_content_block = get_option('media_wolf_restricted_content_block');
    
        ob_start();
        include MEDIA_WOLF_PLUGIN_DIR . 'includes/partials/restricted-content-notice.php';
        return ob_get_clean();
    }

    public static function restrict_content_shortcode($atts, $content = ''): string {
        if (!is_user_logged_in()) {
            $login_url = esc_url(get_permalink(get_option('media_wolf_login_page')));
            $register_url = esc_url(get_permalink(get_option('media_wolf_register_page')));
    
            ob_start();
            include MEDIA_WOLF_PLUGIN_DIR . 'includes/partials/restrict-content-shortcode.php';
            return ob_get_clean();
        }
    
        return $content;
    }
    

    /**
     * Filter the post content to restrict it if necessary.
     */
    public static function restrict_post_content_filter($content): string {
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
