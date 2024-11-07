<?php

namespace MediaWolf;

use MediaWolf\PluginComponentInterface;

class Login implements PluginComponentInterface {

    public static function init(): void {
        // Customize error message
        add_filter('login_errors', [self::class, 'custom_login_error_message']);

        // Customize login logo
        add_action('login_enqueue_scripts', [self::class, 'customize_login_logo']);

        // Enqueue custom login styles and background
        add_action('login_enqueue_scripts', [self::class, 'enqueue_custom_login_styles']);

        // Customize login logo URL and title
        add_filter('login_headerurl', [self::class, 'custom_login_url']);
        add_filter('login_headertext', [self::class, 'custom_login_title']);

        // Custom redirect after login
        // add_filter('login_redirect', [self::class, 'custom_login_redirect'], 10, 3);

        // Remove "Remember Me" and set custom cookie expiration
        add_filter('login_form_bottom', [self::class, 'remove_remember_me']);
        add_filter('auth_cookie_expiration', [self::class, 'custom_cookie_expiration']);

        // Add custom footer text
        add_action('login_footer', [self::class, 'custom_footer_text']);
    }

    /**
     * Register empty methods to satisfy the interface requirements
     */
    public static function register_page_settings(): void {
        // Not used in Login class
    }

    public static function dashboard_menu_link(): void {
        // Not used in Login class
    }

    public static function render_settings_page(): void {
        // Not used in Login class
    }

    /**
     * Generic login error message for security.
     */
    public static function custom_login_error_message(): string {
        return __('Login information is incorrect. Please try again.', 'media-wolf');
    }

    /**
     * Customize the login logo to use the plugin's logo.
     */
    public static function customize_login_logo(): void {
        ?>
        <style type="text/css">
            .login h1 a {
                background-image: url('<?php echo esc_url('https://media-wolf.co.uk/wp-content/uploads/2024/09/Media-Wolf_20240922_102156_0000-70x70.png'); ?>');
                background-size: contain;
                width: 100%;
                height: 80px;
            }
        </style>
        <?php
    }

    /**
     * Enqueue custom styles for the login page.
     */
    public static function enqueue_assets(): void {
        $is_dev = strpos(home_url(), 'localhost') !== false || strpos(home_url(), 'staging') !== false;
        $file_suffix = $is_dev ? '' : '.min';
    
        wp_enqueue_style('media-wolf-login', MEDIA_WOLF_PLUGIN_PATH . "/assets/css/login$file_suffix.css");
    }   

    /**
     * Customize the URL for the login logo link.
     */
    public static function custom_login_url(): string {
        return home_url();
    }

    /**
     * Customize the title attribute for the login logo link.
     */
    public static function custom_login_title(): string {
        return get_bloginfo('name');
    }

    /**
     * Redirect users after login based on their role.
     */
    public static function custom_login_redirect($redirect_to, $request, $user): string {
        // Redirect based on user role
        if (isset($user->roles) && in_array('administrator', $user->roles)) {
            return admin_url(); // Admins to dashboard
        } elseif (isset($user->roles) && in_array('editor', $user->roles)) {
            return home_url('/editor-dashboard'); // Editors to custom dashboard
        } else {
            return home_url('/user-dashboard'); // All others to general dashboard
        }
    }

    /**
     * Remove the "Remember Me" checkbox.
     */
    public static function remove_remember_me(): string {
        return '';
    }

    /**
     * Custom login session length if "Remember Me" is disabled.
     */
    public static function custom_cookie_expiration($expiration): int {
        return 86400; // Set to 1 day (86400 seconds)
    }

    /**
     * Add custom footer text below the login form.
     */
    public static function custom_footer_text(): void {
        echo '<p style="text-align: center; color: #666;">' . esc_html__('Welcome to Media Wolf Security! Contact support if you need assistance.', 'media-wolf') . '</p>';
    }
}
