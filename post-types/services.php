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

        // Linked Products
        add_filter('the_content', [self::class, 'display_linked_products']);
        add_filter('template_include', [self::class, 'load_custom_templates']);
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
    
    /**
     * Register ACF fields for linking products.
     */
    public static function register_acf_fields(): void
    {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_services_linked_products',
                'title' => 'Linked Products',
                'fields' => [
                    [
                        'key' => 'field_linked_products',
                        'label' => 'Linked Products',
                        'name' => 'linked_products',
                        'type' => 'relationship',
                        'post_type' => ['product'],
                        'filters' => ['search'],
                        'min' => 0,
                        'max' => 5,
                        'return_format' => 'id',
                    ],
                ],
                'location' => [
                    [
                        [
                            'param' => 'post_type',
                            'operator' => '==',
                            'value' => 'services',
                        ],
                    ],
                ],
            ]);
        }
    }

    /**
     * Display linked products on single service template.
     *
     * @param string $content
     * @return string
     */
    public static function display_linked_products($content): string
    {
        if (is_singular('services') && is_main_query()) {
            $linked_products = get_field('linked_products');

            if ($linked_products) {
                ob_start();
                include MEDIA_WOLF_PLUGIN_DIR . '/templates/services/linked-products.php';
                $products_content = ob_get_clean();

                $content .= $products_content;
            }
        }

        return $content;
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

    /**
     * Display linked products on single service template.
     *
     * @param string $content
     * @return string
     */
    public static function display_linked_products($content): string
    {
        if (is_singular('services') && is_main_query()) {
            $linked_products = get_field('linked_products');

            if ($linked_products) {
                ob_start();
                include MEDIA_WOLF_PLUGIN_DIR . '/templates/services/linked-products.php';
                $products_content = ob_get_clean();

                $content .= $products_content;
            }
        }

        return $content;
    }

    /**
     * Load custom templates for the Services post type.
     *
     * @param string $template
     * @return string
     */
    public static function load_custom_templates($template): string
    {
        if (is_post_type_archive('services')) {
            $custom_template = MEDIA_WOLF_PLUGIN_DIR . '/templates/services/archive.php';
        } elseif (is_singular('services')) {
            $custom_template = MEDIA_WOLF_PLUGIN_DIR . '/templates/services/single.php';
        }

        return file_exists($custom_template) ? $custom_template : $template;
    }
}

Services::init();