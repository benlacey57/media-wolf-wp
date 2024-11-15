<?php

namespace MediaWolf;

use MediaWolf\ShortcodeInterface;

class RestrictContentShortcode implements ShortcodeInterface {

    /**
     * Register the shortcode with WordPress.
     */
    public static function register_shortcode(): void
    {
        add_shortcode('restrict_content', [self::class, 'render_shortcode']);
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
                'role' => 'subscriber', // Default role to restrict content to
            ],
            $atts,
            'restrict_content'
        );

        if (!is_user_logged_in()) {
            return '<p>You must be logged in to view this content.</p>';
        }

        if (!current_user_can($atts['role'])) {
            return '<p>You do not have permission to view this content.</p>';
        }

        return do_shortcode($content);
    }

    /**
     * Register settings for the shortcode.
     */
    public static function register_settings(): void
    {
        // Register settings for the shortcode
    }

}