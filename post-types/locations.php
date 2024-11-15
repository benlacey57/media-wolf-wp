<?php

namespace MediaWolf\PostTypes;

use MediaWolf\Interfaces\PostTypeInterface;

class LocationsPostType implements PostTypeInterface
{
    const POST_TYPE = 'locations';

    public static function register_post_type(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name' => __('Locations', 'media-wolf'),
                'singular_name' => __('Location', 'media-wolf')
            ],
            'public' => true,
            'has_archive' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => self::POST_TYPE],
        ]);
    }

    public static function get_post_type(): string
    {
        return self::POST_TYPE;
    }

    public static function register_acf_fields(): void
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_location_details',
                'title' => 'Location Details',
                'fields' => [
                    [
                        'key' => 'field_location_address',
                        'label' => 'Address',
                        'name' => 'address',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_location_phone',
                        'label' => 'Phone',
                        'name' => 'phone',
                        'type' => 'text',
                    ],
                    [
                        'key' => 'field_location_email',
                        'label' => 'Email',
                        'name' => 'email',
                        'type' => 'email',
                    ],
                    [
                        'key' => 'field_location_hours',
                        'label' => 'Hours',
                        'name' => 'hours',
                        'type' => 'textarea',
                    ],
                    [
                        'key' => 'field_location_map',
                        'label' => 'Map',
                        'name' => 'map',
                        'type' => 'url',
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => self::POST_TYPE,
                        ],
                    ],
                ],
            ]);
        }
    }  

    /**
     * Retrieve all posts for the Services post type.
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
     * Retrieve posts by meta key and value.
     *
     * @param string $meta_key
     * @param mixed $meta_value
     * @param int $limit
     * @return array|null
     */
    public static function get_posts_by_meta(string $meta_key, $meta_value, int $limit = -1): ?array
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

    /**
     * Retrieve posts by taxonomy term.
     *
     * @param string $taxonomy
     * @param mixed $term
     * @param int $limit
     * @return array|null
     */
    public static function get_posts_by_taxonomy(string $taxonomy, $term, int $limit = -1): ?array
    {
        $query = new \WP_Query([
            'post_type' => self::POST_TYPE,
            'tax_query' => [
                [
                    'taxonomy' => $taxonomy,
                    'field' => is_numeric($term) ? 'term_id' : 'slug',
                    'terms' => $term,
                ]
            ],
            'posts_per_page' => $limit,
            'post_status' => 'publish',
        ]);

        return $query->have_posts() ? $query->posts : null;
    }

    /**
     * Retrieve related products for a specific service post.
     *
     * @param int $post_id
     * @return array|null
     */
    public static function get_related_products(int $post_id): ?array
    {
        // Ensure ACF is active
        if (!function_exists('get_field')) return null;

        $products = get_field('related_products', $post_id);
        return !empty($products) ? $products : null;
    }
}
