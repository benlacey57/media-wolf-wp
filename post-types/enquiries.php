<?php

namespace MediaWolf\PostTypes;

class Enquiries
{
    const POST_TYPE = 'enquiries';

    public static function register_post_type(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name' => __('Enquiries', 'media-wolf'),
                'singular_name' => __('Enquiry', 'media-wolf')
            ],
            'public' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor'],
        ]);
    }

    public static function get_post_type(): string
    {
        return self::POST_TYPE;
    }

    /**
     * Retrieve all Enquiries posts.
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

    /**
     * Retrieve enquiries by a meta key and value (e.g., referrer URL).
     *
     * @param string $meta_key
     * @param mixed $meta_value
     * @param int $limit
     * @return array|null
     */
    public static function get_enquiries_by_meta(string $meta_key, $meta_value, int $limit = -1): ?array
    {
        $query = new \WP_Query([
            'post_type' => self::POST_TYPE,
            'meta_key' => $meta_key,
            'meta_value' => $meta_value,
            'posts_per_page' => $limit,
            'post_status' => 'publish',
        ]);

        return $query->have_posts() ? $query->posts : null;
    }
}