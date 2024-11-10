<?php

namespace MediaWolf;

class Subpages implements PluginComponentInterface
{
    public static function init(): void
    {
        add_shortcode('subpages', [self::class, 'render_subpages']);
    }

    public static function render_subpages($atts)
    {
        $atts = shortcode_atts([
            'columns' => 1,
            'style' => 'list' // 'list' or 'card'
        ], $atts);

        $subpages = get_pages(['child_of' => get_the_ID(), 'sort_column' => 'menu_order']);

        ob_start();
        ?>
        <div class="subpages subpages-<?php echo esc_attr($atts['style']); ?> columns-<?php echo esc_attr($atts['columns']); ?>">
            <?php foreach ($subpages as $page): ?>
                <div class="subpage-item">
                    <a href="<?php echo get_permalink($page->ID); ?>">
                        <?php echo esc_html($page->post_title); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <style>
            .subpages.columns-2 .subpage-item { width: 50%; }
            .subpages.columns-3 .subpage-item { width: 33.33%; }
            /* Additional CSS for layout */
        </style>
        <?php
        return ob_get_clean();
    }
}