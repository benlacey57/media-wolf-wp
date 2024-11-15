<?php

namespace MediaWolf\Interfaces;

interface ShortcodeInterface {
    /**
     * Registers the shortcode with WordPress.
     * @return void
     */
    public static function register_shortcode(): void;

    /**
     * Renders the shortcode output.
     *
     * @param array $atts Attributes passed to the shortcode.
     * @param string|null $content Content within the shortcode tags, if any.
     * @return string The shortcode HTML content.
     */
    public static function render_shortcode($atts, $content = null): string;

    /**
     * Registers settings for the shortcode (if applicable).
     * @return void
     */
    public static function register_settings(): void;

    /**
     * Enqueues assets (CSS/JS) for frontend, if needed.
     * @return void
     */
    public static function enqueue_assets(): void;
}
