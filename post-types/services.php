<?php

namespace MediaWolf\PostTypes;

use MediaWolf\PostTypeInterface;

class Services implements PostTypeInterface {
    const POST_TYPE = 'service';

    public static function init(): void {
        self::register();
        add_action('acf/init', [self::class, 'register_acf_fields']);
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_filter("manage_" . self::POST_TYPE . "_posts_columns", [self::class, 'customize_admin_columns']);
        add_action("manage_" . self::POST_TYPE . "_posts_custom_column", [self::class, 'populate_admin_columns'], 10, 2);
    }

    public static function register(): void {
        register_post_type(self::POST_TYPE, [
            'label' => __('Services', 'media-wolf'),
            'public' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'menu_icon' => 'dashicons-hammer',
            'has_archive' => true,
        ]);
    }

    public static function register_acf_fields(): void {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_service_details',
                'title' => 'Service Details',
                'fields' => [
                    ['key' => 'field_service_description', 'label' => 'Service Description', 'name' => 'service_description', 'type' => 'textarea'],
                    ['key' => 'field_price', 'label' => 'Price', 'name' => 'price', 'type' => 'number'],
                ],
                'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => self::POST_TYPE]]],
            ]);
        }
    }

    public static function add_meta_boxes(): void {
        add_meta_box('service_info', 'Service Info', [self::class, 'render_service_meta_box'], self::POST_TYPE, 'side', 'default');
    }

    public static function render_service_meta_box($post): void {
        echo '<p>' . __('Service Price: ', 'media-wolf') . get_field('price', $post->ID) . '</p>';
    }

    public static function customize_admin_columns(array $columns): array {
        $columns['price'] = __('Price', 'media-wolf');
        return $columns;
    }

    public static function populate_admin_columns(string $column, int $post_id): void {
        if ($column === 'price') {
            echo esc_html(get_field('price', $post_id));
        }
    }
}

Services::init();