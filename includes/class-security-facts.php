<?php

namespace MediaWolf;

class Security_Facts {

    public static function init(): void {
        add_action('init', [self::class, 'register_post_type']);

        // Render Security Facts
        add_action('wp_footer', [self::class, 'display_random_facts_carousel']);
        
        // Shortcode to display the random facts carousel
        add_shortcode('security_facts_carousel', function() {
            return Security_Facts_Plugin::display_random_facts_carousel();
        });
        
        // Shortcode to display random security facts
        add_shortcode('security_facts_list', function(){
            // Set default value for count to 1 if not provided
            $atts = shortcode_atts(
                array(
                    'count' => 1, // Default to 1 fact
                ), 
                $atts
            );
            echo Security_Facts_Plugin::display_random_facts_list($atts['count']); 
        });
        
        // Front-End Assets
        add_action('wp_enqueue_scripts', [self::class, 'enqueue_assets']);
        
        // WordPress Dashboard
        add_action('admin_menu', [self::class, 'add_settings_page']);
        add_action('admin_init', [self::class, 'register_settings']);
    }

    public static function register_post_type(): void {
        register_post_type('security_facts', [
            'labels' => [
                'name' => 'Security Facts',
                'singular_name' => 'Security Fact'
            ],
            'public' => false,
            'show_ui' => true,
            'supports' => ['title', 'editor', 'custom-fields'],
        ]);
    }

    public static function display_random_facts_list($count = 1) {
        // Fetch the security facts based on the passed count
        $facts = get_posts([
            'post_type' => 'security_facts',
            'posts_per_page' => intval($count),
            'orderby' => 'rand'
        ]);
    
        $output = '<div class="security-facts-list">';
        if (!empty($facts)) {
            foreach ($facts as $fact) {
                $fact_url = get_post_meta($fact->ID, 'fact_url', true);
                $category = get_post_meta($fact->ID, 'category', true);
                $content = $fact->post_content;
                $title = $fact->post_title;
    
                // Output each fact as a list item
                $output .= '<div class="security-fact">';
                $output .= '<h3>' . esc_html($title) . '</h3>';
                $output .= '<p>' . esc_html($content) . '</p>';
                if (!empty($category)) {
                    $output .= '<span class="category">' . esc_html($category) . '</span>';
                }
                if (!empty($fact_url)) {
                    $output .= '<a href="' . esc_url($fact_url) . '" target="_blank" class="fact-link">Learn More</a>';
                }
                $output .= '</div>';
            }
        } else {
            $output = 'No Security Facts To Display';
        }
        $output .= '</div>';
    
        return $output;
    }


    public static function display_random_facts_carousel() {
        $facts = get_posts([
            'post_type' => 'security_facts',
            'posts_per_page' => get_option('media_wolf_facts_carousel_count') ? get_option('media_wolf_facts_carousel_count') : 5,
            'orderby' => 'rand'
        ]);
    
        $output = '<div class="owl-carousel security-facts-carousel">';
        if (!empty($facts)):
            foreach ($facts as $fact):
                $fact_url = get_post_meta($fact->ID, 'fact_url', true);
                $category = get_post_meta($fact->ID, 'category', true);
                $content = $fact->post_content;
                $title = $fact->post_title;
    
                // Output each fact as a carousel item
                $output .= '<div class="item">';
                    $output .= '<h3>' . esc_html($title) . '</h3>';
                    $output .= '<p>' . esc_html($content) . '</p>';
                    if (!empty($category)) {
                        $output .= '<span class="category">' . esc_html($category) . '</span>';
                    }
                    if (!empty($fact_url)) {
                        $output .= '<a href="' . esc_url($fact_url) . '" target="_blank" class="fact-link">Learn More</a>';
                    }
                $output .= '</div>';
            endforeach;
        else:
            $output = 'No Security Facts To Display';
        endif;
        $output .= '</div>';
    
        return $output;
    }


    // Enqueue JS and CSS assets for the carousel
    public static function enqueue_assets(): void {
        // Enqueue Owl Carousel CSS and JS from CDN
        wp_enqueue_style('owl-carousel-css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css');
        wp_enqueue_style('owl-carousel-theme-css', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css');
        wp_enqueue_script('owl-carousel-js', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), null, true);
        
        // Enqueue custom CSS and JS from plugin assets folder
        wp_enqueue_style('security-facts-carousel-css', MEDIA_WOLF_PLUGIN_PATH . 'assets/security-facts/style.css');
        wp_enqueue_script('security-facts-carousel-js', MEDIA_WOLF_PLUGIN_PATH . 'assets/security-facts/script.js', array('jquery', 'owl-carousel-js'), null, true);
    }

    /**
     * Add the main settings page.
     */
    public static function add_settings_page(): void {
        add_submenu_page('media-wolf', 'Security Facts Settings', 'Security Facts', 'manage_options', 'media-wolf-security-facts', [self::class, 'render_security_facts_page']);
    }

    public static function render_security_facts_page(): void {
        ?>
        <div class="wrap">
            <h1>Security Facts Settings</h1>
            <form method="post" action="options.php">
                <?php
                    settings_fields('media-wolf-settings');
                    do_settings_sections('media-wolf-security-facts');
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register settings fields for the security facts
     */
    public static function register_settings(): void {
        // Carousel Count Field
        register_setting('media-wolf-settings', 'media_wolf_facts_carousel_count');
        add_settings_section('media_wolf_carousel_section', 'Carousel Settings', null, 'media-wolf-security-facts');
        add_settings_field('carousel_count', 'Carousel Count', [self::class, 'render_carousel_count_field'], 'media-wolf-security-facts', 'media_wolf_carousel_section');

        // Import Sample Data Field
        register_setting('media-wolf-settings', 'media_wolf_facts_import_sample_data');
        add_settings_section('media_wolf_import_section', 'Import Settings', null, 'media-wolf-security-facts');
        add_settings_field('import_sample_data', 'Import Sample Data', [self::class, 'render_import_sample_data_field'], 'media-wolf-security-facts', 'media_wolf_import_section');

        // Handle import on save
        if (get_option('media_wolf_facts_import_sample_data') === 'yes') {
            self::import_security_facts();
            update_option('media_wolf_facts_import_sample_data', 'no'); // Reset after import
        }
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
        $json_file = plugin_dir_path(__FILE__) . '/../json/security-facts.json';

        // Check if the file exists
        if (!file_exists($json_file)) {
            return new \WP_Error('file_not_found', 'The security-facts.json file was not found.');
        }
        
        // Get the contents of the file
        $json_data = file_get_contents($json_file);
        $facts = json_decode($json_data, true);

        // Error handling for JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return new \WP_Error('json_decode_error', 'Error decoding the JSON file.');
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
