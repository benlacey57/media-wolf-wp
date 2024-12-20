<?php

namespace MediaWolf;

use MediaWolf\Interfaces\PluginComponentInterface;
use MediaWolf\PostTypes\SecurityFactsPostType;
use MediaWolf\Shortcodes\SecurityFactsShortcode;

class Security_Facts implements PluginComponentInterface {
    const COMPONENT = 'security-facts';

    public static function init(): void {
        // Admin
        add_action('admin_init', [self::class, 'register_page_settings']);
        add_action('admin_menu', [self::class, 'dashboard_menu_link']);
        add_action('init', [self::class, 'register_post_type']);         

        // Assets
        if (function_exists('enqueue_assets')) {
            add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        }
        
        // Security Shortcodes
        SecurityFactsShortcode::register_shortcode();
    }

    public static function register_post_type(): void {       
        SecurityFactsPostType::register_post_type();
    }

    /**
     * Add the Security Facts settings page under Media Wolf in the dashboard
     */
    public static function dashboard_menu_link(): void {
        add_submenu_page(
            'media-wolf', 
            __('Security Facts', 'media-wolf'), 
            __('Security Facts', 'media-wolf'), 
            'manage_options', 
            'media-wolf', 
            [self::class, 'render_settings_page']
        );
    }

    public static function render_settings_page() {
        return MEDIA_WOLF_PLUGIN_DIR . 'admin/admin-security-facts-page.php';
    }

    public static function get_component_includes_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'includes/' . self::COMPONENT . '/';
    }

    public static function get_component_template_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT . '/';
    }

    /**
     * Enqueue CSS and JS assets for the component
     */
    public static function enqueue_assets(): void
    {
        $is_dev = strpos(home_url(), 'localhost') !== false || strpos(home_url(), 'staging') !== false;
        $file_suffix = $is_dev ? '' : '.min';

        
    }

    /**
     * Register settings fields for the security facts
     */
    public static function register_settings(): void {
        // Carousel Title Field
        register_setting('media-wolf-settings', 'media_wolf_facts_carousel_title');
        add_settings_field('carousel_count', 'Carousel Count', [self::class, 'render_carousel_title_field'], 'media-wolf-security-facts', 'media_wolf_carousel_section');

        // Carousel Count Field
        register_setting('media-wolf-settings', 'media_wolf_facts_carousel_count');
        add_settings_field('carousel_count', 'Carousel Count', [self::class, 'render_carousel_count_field'], 'media-wolf-security-facts', 'media_wolf_carousel_section');

        add_settings_section('media_wolf_carousel_section', 'Carousel Settings', null, 'media-wolf-security-facts');

        // Import Sample Data Field
        register_setting('media-wolf-settings', 'media_wolf_facts_import_sample_data');
        add_settings_section('media_wolf_import_section', 'Import Settings', null, 'media-wolf-security-facts');
        add_settings_field('import_sample_data', 'Import Sample Data', [self::class, 'render_import_sample_data_field'], 'media-wolf-security-facts', 'media_wolf_import_section');

        // Handle import on save
        if (get_option('media_wolf_facts_import_sample_data') === 'yes') {
            self::import_security_facts();
            update_option('media_wolf_facts_import_sample_data', 'no');
        }
    }

    // Enqueue JS and CSS assets for the carousel
    public static function enqueue_assets(): void {
        $is_dev = strpos(home_url(), 'localhost') !== false || strpos(home_url(), 'staging') !== false;
        $file_suffix = $is_dev ? '' : '.min';
    
        // Enqueue Owl Carousel CSS and JS from CDN
        wp_enqueue_style('owl-carousel-css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css');
        wp_enqueue_style('owl-carousel-theme-css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css');
        wp_enqueue_script('owl-carousel-js', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), null, true);

        wp_enqueue_style(
            'media-wolf-' . self::COMPONENT,
            MEDIA_WOLF_PLUGIN_PATH . "assets/css/" . self::COMPONENT . "$file_suffix.css"
        );
    }

    public static function render_carousel_title_field(): void {
        $value = get_option('media_wolf_facts_carousel_title', "Interesting Cyber Security Facts");
        echo '<input type="text" name="media_wolf_facts_carousel_title" value="' . esc_attr($value) . '" />';
    }

    public static function render_carousel_count_field(): void {
        $value = get_option('media_wolf_facts_carousel_count', 5); // Default to 5
        echo '<input type="number" name="media_wolf_facts_carousel_count" value="' . esc_attr($value) . '" />';
    }

    public static function render_import_sample_data_field(): void {
        $value = get_option('media_wolf_facts_import_sample_data', 'no');
        echo '<select name="media_wolf_facts_import_sample_data">
                <option value="no"' . selected($value, 'no', false) . '>No</option>
                <option value="yes"' . selected($value, 'yes', false) . '>Yes</option>
              </select>';
    }

    private static function import_security_facts() {
        $json_file = MEDIA_WOLF_PLUGIN_DIR . 'json/security-facts.json';

        // Check if the file exists
        if (!file_exists($json_file)) {
            return new WP_Error('file_not_found', 'The security facts.json file was not found.');
        }
        
        // Get the contents of the file
        $json_data = file_get_contents($json_file);
        $facts = json_decode($json_data, true);

        // Error handling for JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new WP_Error('json_decode_error', 'Error decoding the JSON file.');
        }

        foreach ($facts as $fact) {
            // Check if the fact already exists by title
            $existing_fact = get_page_by_title($fact['title'], OBJECT, 'security_facts');

            if ($existing_fact) {
                continue;
            }

            // Insert post
            $post_id = wp_insert_post(array(
                'post_title'   => sanitize_text_field($fact['title']),
                'post_content' => sanitize_textarea_field($fact['content']),
                'post_status'  => 'publish',
                'post_type'    => 'security_facts'
            ));

            // Check for errors during post creation
            if (is_wp_error($post_id)) {
                return $post_id;
            }

            // Add custom fields
            update_post_meta($post_id, 'fact_url', esc_url($fact['fact_url']));
            update_post_meta($post_id, 'category', sanitize_text_field($fact['category']));
            update_post_meta($post_id, 'date_created', sanitize_text_field($fact['date_created']));
        }

        return true;
    }
}

Security_Facts::init();