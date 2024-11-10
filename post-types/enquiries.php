<?php

namespace MediaWolf\PostTypes;

use MediaWolf\PostTypeInterface;

class Enquiries implements PostTypeInterface {
    const POST_TYPE = 'enquiry';

    public static function init(): void {
        self::register();
        add_action('acf/init', [self::class, 'register_acf_fields']);
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_filter("manage_" . self::POST_TYPE . "_posts_columns", [self::class, 'customize_admin_columns']);
        add_action("manage_" . self::POST_TYPE . "_posts_custom_column", [self::class, 'populate_admin_columns'], 10, 2);
    }

    public static function register(): void {
        register_post_type(self::POST_TYPE, [
            'label' => __('Enquiries', 'media-wolf'),
            'public' => false,
            'show_ui' => true,
            'supports' => ['title', 'editor', 'custom-fields'],
            'menu_icon' => 'dashicons-phone',
            'has_archive' => false,
        ]);
    }

    public static function register_acf_fields(): void {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_enquiry_details',
                'title' => 'Enquiry Details',
                'fields' => [
                    ['key' => 'field_name', 'label' => 'Name', 'name' => 'name', 'type' => 'text'],
                    ['key' => 'field_email', 'label' => 'Email', 'name' => 'email', 'type' => 'email'],
                    ['key' => 'field_phone', 'label' => 'Phone', 'name' => 'phone', 'type' => 'text'],
                    ['key' => 'field_business_name', 'label' => 'Business Name', 'name' => 'business_name', 'type' => 'text'],
                    ['key' => 'field_service', 'label' => 'Service', 'name' => 'service', 'type' => 'select', 'choices' => ['Service 1', 'Service 2']],
                    ['key' => 'field_address', 'label' => 'Address', 'name' => 'address', 'type' => 'google_map'],
                    ['key' => 'field_utm', 'label' => 'UTM Parameters', 'name' => 'utm_parameters', 'type' => 'text'],
                    ['key' => 'field_referrer_url', 'label' => 'Referrer URL', 'name' => 'referrer_url', 'type' => 'url'],
                    ['key' => 'field_pages_visited', 'label' => 'Pages Visited', 'name' => 'pages_visited', 'type' => 'textarea'],
                ],
                'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => self::POST_TYPE]]],
            ]);
        }
    }

    public static function add_meta_boxes(): void {
        add_meta_box('enquiry_info', 'Enquiry Info', [self::class, 'render_enquiry_meta_box'], self::POST_TYPE, 'side', 'default');
    }

    public static function render_enquiry_meta_box($post): void {
        echo '<p>' . __('Device: ', 'media-wolf') . get_post_meta($post->ID, '_device_type', true) . '</p>';
        echo '<p>' . __('Device Name: ', 'media-wolf') . get_post_meta($post->ID, '_device_name', true) . '</p>';
    }

    public static function customize_admin_columns(array $columns): array {
        $columns['service'] = __('Service', 'media-wolf');
        $columns['utm'] = __('UTM Parameters', 'media-wolf');
        $columns['pages_visited'] = __('Pages Visited', 'media-wolf');
        return $columns;
    }

    public static function populate_admin_columns(string $column, int $post_id): void {
        if ($column === 'service') {
            echo esc_html(get_field('service', $post_id));
        } elseif ($column === 'utm') {
            echo esc_html(get_field('utm_parameters', $post_id));
        } elseif ($column === 'pages_visited') {
            echo count(explode("\n", get_field('pages_visited', $post_id)));
        }
    }
}

Enquiries::init();