<?php

namespace MediaWolf;

class Subpages
{
    const COMPONENT = 'subpages';

    public static function init(): void
    {
        add_shortcode('subpages', [self::class, 'render_subpages_shortcode']);
    }

    public static function get_component_template_dir(): string
    {
        return MEDIA_WOLF_PLUGIN_DIR . 'templates/' . self::COMPONENT . '/';
    }

    public static function render_subpages_shortcode($atts)
    {
        $atts = shortcode_atts([
            'columns' => 1,
            'display' => 'list' // 'list' or 'card'
        ], $atts);

        $subpages = get_pages(['child_of' => get_the_ID(), 'sort_column' => 'menu_order']);

        ob_start();

        switch ($atts['display']) {
            case 'card':
                include self::get_component_template_dir() . '/card.php';
                break;
            case 'list':
            default:
                include self::get_component_template_dir() . '/list.php';
                break;
        }

        return ob_get_clean();
    }
}
