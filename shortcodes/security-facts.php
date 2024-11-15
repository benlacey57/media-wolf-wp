<?php

namespace MediaWolf\Shortcodes;

use MediaWolf\Interfaces\ShortcodeInterface;

class SecurityFactsShortcode implements ShortcodeInterface
{
    /**
     * Register the shortcode with WordPress.
     */
    public static function register_shortcode(): void
    {
        add_shortcode('security_facts', [self::class, 'render_shortcode']);
    }

    /**
     * Render the shortcode output.
     *
     * @param array $atts Attributes passed to the shortcode.
     * @param string|null $content Content within the shortcode tags, if any.
     * @return string The shortcode HTML content.
     */
    public static function render_shortcode($atts, $content = null): string
    {
        $atts = shortcode_atts(
            [
                'display' => 'list',   // Default display format: list or carousel
                'count' => 5,          // Default number of security facts to display
            ],
            $atts,
            'security_facts'
        );

        // Fetch security facts
        $facts = get_posts([
            'post_type' => 'security_facts',
            'posts_per_page' => intval($atts['count']),
            'orderby' => 'rand'
        ]);

        if (empty($facts)) {
            return '<p>No security facts available.</p>';
        }

        ob_start();

        // Load the template file based on display type
        if ($atts['display'] === 'carousel') {
            include MEDIA_WOLF_PLUGIN_DIR . 'templates/security-facts/carousel.php';
        } else {
            include MEDIA_WOLF_PLUGIN_DIR . 'templates/security-facts/list.php';
        }

        return ob_get_clean();
    }

    /**
     * Register settings for the shortcode.
     */
    public static function register_settings(): void
    {
        // Add settings in the admin panel for customizing the shortcode behavior
        register_setting('media-wolf-settings', 'security_facts_shortcode_count');
        register_setting('media-wolf-settings', 'security_facts_shortcode_display');
    }

    /**
     * Enqueue assets for frontend, if needed.
     */
    public static function enqueue_assets(): void
    {
        wp_enqueue_style('media-wolf-security-facts', MEDIA_WOLF_PLUGIN_PATH . 'assets/css/security-facts.css');

        // Enqueue JavaScript for carousel display if needed
        if (get_option('security_facts_shortcode_display', 'list') === 'carousel') {
            wp_enqueue_script('media-wolf-owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', ['jquery'], null, true);
            wp_enqueue_script('media-wolf-security-facts-carousel', MEDIA_WOLF_PLUGIN_PATH . 'assets/js/security-facts-carousel.js', ['jquery'], null, true);
        }
    }
}
