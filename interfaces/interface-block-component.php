<?php

namespace MediaWolf\Interfaces;

interface BlockComponentInterface {
    /**
     * Registers the block type with WordPress.
     * @return void
     */
    public static function register_block(): void;

    /**
     * Registers settings for the block (if applicable).
     * @return void
     */
    public static function register_settings(): void;

    /**
     * Renders the block content.
     *
     * @param array $attributes The block attributes.
     * @return string The block HTML content.
     */
    public static function render_block($attributes): string;

    /**
     * Enqueues block assets (CSS/JS) for frontend.
     * @return void
     */
    public static function enqueue_assets(): void;

    /**
     * Enqueues editor assets (CSS/JS) for block editor.
     * @return void
     */
    public static function enqueue_editor_assets(): void;
}
