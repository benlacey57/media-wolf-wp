<?php

namespace MediaWolf\PostTypes;

use MediaWolf\Interfaces\PostTypeInterface;

class SecurityFactsPostType implements PostTypeInterface
{
    const POST_TYPE = 'security_facts';

    public static function init(): void
    {
        add_action('init', [self::class, 'register_post_type']);
    }

    public static function register_post_type(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name' => __('Security Facts', 'media-wolf'),
                'singular_name' => __('Security Fact', 'media-wolf')
            ],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor'],
        ]);
    }

    public static function get_post_type(): string
    {
        return self::POST_TYPE;
    }

    /**
     * Retrieve all Security Facts posts.
     *
     * @param int $limit
     * @return array|null
     */
    public static function get_all_posts(int $limit = -1): ?array
    {
        $query = new \WP_Query([
            'post_type' => self::POST_TYPE,
            'posts_per_page' => $limit,
            'post_status' => 'publish',
        ]);

        return $query->have_posts() ? $query->posts : null;
    }
}