<?php

namespace MediaWolf\PostTypes;

use MediaWolf\PostTypeInterface;

class SecurityFacts implements PostTypeInterface {
    const POST_TYPE = 'security_fact';

    public static function init(): void {
        self::register();
        add_action('acf/init', [self::class, 'register_acf_fields']);
        add_filter("manage_" . self::POST_TYPE . "_posts_columns", [self::class, 'customize_admin_columns']);
        add_action("manage_" . self::POST_TYPE . "_posts_custom_column", [self::class, 'populate_admin_columns'], 10, 2);
    }

    public static function register(): void {
        register_post_type(self::POST_TYPE, [
            'label' => __('Security Facts', 'media-wolf'),
            'public' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'custom-fields'],
            'menu_icon' => 'dashicons-shield',
        ]);
    }

    public static function register_acf_fields(): void {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_security_fact_details',
                'title' => 'Security Fact Details',
                'fields' => [
                    ['key' => 'field_fact_url', 'label' => 'Fact URL', 'name' => 'fact_url', 'type' => 'url'],
                    ['key' => 'field_category', 'label' => 'Category', 'name' => 'category', 'type' => 'text'],
                ],
                'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => self::POST_TYPE]]],
            ]);
        }
    }

    public static function customize_admin_columns(array $columns): array {
        $columns['category'] = __('Category', 'media-wolf');
        return $columns;
    }

    public static function populate_admin_columns(string $column, int $post_id): void {
        if ($column === 'category') {
            echo esc_html(get_field('category', $post_id));
        }
    }
}

SecurityFacts::