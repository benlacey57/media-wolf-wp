?php

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
            'public' => true,
            'supports' => ['title', 'editor', 'custom-fields'],
            'show_in_rest' => true,
        ]);
    }

    public static function register_acf_fields(): void {
        // Register ACF fields here
    }

    public static function add_meta_boxes(): void {
        // Add meta boxes here
    }

    public static function customize_admin_columns(array $columns): array {
        // Customize admin columns here
        return $columns;
    }

    public static function populate_admin_columns(string $column, int $post_id): void {
        // Populate admin columns here
    }
}

// Initialize the post type immediately after declaring it
Enquiries::init();