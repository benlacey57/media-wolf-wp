<?php
namespace MediaWolf;

class Content_Restriction {

    public static function init(): void {
        add_shortcode('restrict_content', [self::class, 'restrict_content_shortcode']);
        add_filter('the_content', [self::class, 'restrict_post_content']);
    }

    /**
     * Shortcode to restrict content visibility based on membership status.
     */
    public static function restrict_content_shortcode($atts, $content = ''): string {
        if (!is_user_logged_in()) {
            $login_url = esc_url(get_permalink(get_option('media_wolf_login_page')));
            $register_url = esc_url(get_permalink(get_option('media_wolf_register_page')));
            return '<p>This content is restricted. Please <a href="' . $login_url . '">login</a> or <a href="' . $register_url . '">register</a> to view the full content.</p>';
        }
        return $content;
    }

    /**
     * Filter the post content to restrict it if necessary.
     */
    public static function restrict_post_content($content): string {
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

    /**
     * Display the restricted content notice, with the content defined in settings.
     */
    private static function restricted_content_notice(): string {
        $restricted_content_block = get_option('media_wolf_restricted_content_block');
        return '<div class="restricted-content-notice">' . apply_filters('the_content', $restricted_content_block) . '</div>';
    }
}
