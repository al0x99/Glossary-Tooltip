<?php
/*
Plugin Name: Glossario White Paper
Version: 2.2.3
Author: Alin Sfirschi
*/

function glossary_tooltip() {
    register_post_type('glossary_term',
        array(
            'labels' => array(
                'name' => 'Termini del Glossario',
                'singular_name' => 'Termine del Glossario'
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor'),
            'rewrite' => array('slug' => 'glossario'),
        )
    );
}

add_action('init', 'glossary_tooltip');

function glossary_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'id' => '', 
            'term' => '',
        ),
        $atts,
        'glossary'
    );

    if (!empty($atts['id'])) {
        $post = get_post($atts['id']);
    } elseif (!empty($atts['term'])) {
        $post = get_page_by_title($atts['term'], OBJECT, 'glossary_term');
    } else {
        $post = null;
    }

    if ($post) {
        $url = get_permalink(get_page_by_path('glossario')) . '#' . $post->post_name;
        return '<a href="' . $url . '" class="glossary-term" title="' . $post->post_content . '">' . $post->post_title . '</a>';
    }
}
add_shortcode('glossary', 'glossary_shortcode');


function glossary_tooltip_options_page() {
    add_options_page('Opzioni Tooltip Glossario', 'Tooltip Glossario', 'manage_options', 'glossary-tooltip', 'glossary_tooltip_options_page_html');
}
add_action('admin_menu', 'glossary_tooltip_options_page');

function glossary_tooltip_options_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    if (isset($_POST['glossary_tooltip_options_nonce']) && wp_verify_nonce($_POST['glossary_tooltip_options_nonce'], 'glossary_tooltip_options')) {
        update_option('glossary_tooltip_bg_color', sanitize_text_field($_POST['glossary_tooltip_bg_color']));
        update_option('glossary_tooltip_text_color', sanitize_text_field($_POST['glossary_tooltip_text_color']));
        update_option('glossary_tooltip_font_size', sanitize_text_field($_POST['glossary_tooltip_font_size']));
    }
    $bg_color = get_option('glossary_tooltip_bg_color', '#333');
    $text_color = get_option('glossary_tooltip_text_color', '#fff');
    $font_size = get_option('glossary_tooltip_font_size', '14px');

    ?>
    <div class="wrap">
        <h1>Opzioni Tooltip Glossario</h1>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="glossary_tooltip_bg_color">Colore di sfondo</label></th>
                    <td><input type="text" id="glossary_tooltip_bg_color" name="glossary_tooltip_bg_color" value="<?php echo esc_attr($bg_color); ?>" /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="glossary_tooltip_text_color">Colore del testo</label></th>
                    <td><input type="text" id="glossary_tooltip_text_color" name="glossary_tooltip_text_color" value="<?php echo esc_attr($text_color); ?>" /></td>
                </tr>
                <tr>
                  <th scope="row"><label for="glossary_tooltip_font_size">Dimensione del testo</label></th>
                    <td><input type="text" id="glossary_tooltip_font_size" name="glossary_tooltip_font_size" value="<?php echo esc_attr($font_size); ?>" /></td>
                </tr>
            </table>
            <?php wp_nonce_field('glossary_tooltip_options', 'glossary_tooltip_options_nonce'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
function glossary_tooltip_enqueue_styles() {
    wp_register_style('glossary-tooltip', plugins_url('glossary-tooltip.css', __FILE__));
    wp_enqueue_style('glossary-tooltip');
}
add_action('wp_enqueue_scripts', 'glossary_tooltip_enqueue_styles');

function glossary_tooltip_css() {
    $bg_color = get_option('glossary_tooltip_bg_color', '#333');
    $text_color = get_option('glossary_tooltip_text_color', '#fff');
    $font_size = get_option('glossary_tooltip_font_size', '14px');
}
add_action('wp_head', 'glossary_tooltip_css');

function glossary_tooltip_elementor() {
    if (\Elementor\Plugin::instance()->editor->is_edit_mode()) {
        wp_enqueue_script(
            'glossary-tooltip-js',
            plugin_dir_url(__FILE__) . 'glossary-tooltip.js',  
            array('jquery'),
            '1.0.0',
            true
        );
    }

    require_once plugin_dir_path(__FILE__) . 'glossary-tooltip-elementor-widget.php';

    add_action('elementor/widgets/widgets_registered', function($widgets_manager) {
        $widgets_manager->register_widget_type(new Glossary_Tooltip_Elementor_Widget());
    });
}
add_action('init', 'glossary_tooltip_elementor');
