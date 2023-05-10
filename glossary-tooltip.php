<?php
/*
Plugin Name: Glossary Tooltip
Description: Un semplice plugin per creare tooltip di glossario in Elementor
Version: 1.1
Author: Alin Sfirschi
*/

// Registra un nuovo tipo di post personalizzato per i termini del glossario
function glossary_tooltip() {
    register_post_type('glossary_term',
        array(
            'labels' => array(
                'name' => 'Termini del Glossario',
                'singular_name' => 'Termine del Glossario'
            ),
            'public' => true,
            'has_archive' => 'glossario',
            'supports' => array('title', 'editor')
        )
    );
}
add_action('init', 'glossary_tooltip');

// Registra lo shortcode per i termini del glossario
function glossary_shortcode($atts) {
    // Estrae gli attributi dello shortcode
    $atts = shortcode_atts(
        array(
            'id' => '',      // L'ID del termine del glossario
            'term' => '',    // Il titolo del termine del glossario
        ),
        $atts,
        'glossary'
    );

    // Ottiene il termine del glossario dal database
    if (!empty($atts['id'])) {
        $post = get_post($atts['id']);
    } elseif (!empty($atts['term'])) {
        $post = get_page_by_title($atts['term'], OBJECT, 'glossary_term');
    } else {
        $post = null;
    }

    // Restituisce il termine del glossario con la tooltip
    if ($post) {
        $url = get_permalink($post->ID);
        return '<a href="' . $url . '" class="glossary-term" title="' . $post->post_content . '">' . $post->post_title . '</a>';
    }
}
add_shortcode('glossary', 'glossary_shortcode');

// Aggiunge l'interfaccia di amministrazione per le opzioni del plugin
function glossary_tooltip_options_page() {
    add_options_page('Opzioni Tooltip Glossario', 'Tooltip Glossario', 'manage_options', 'glossary-tooltip', 'glossary_tooltip_options_page_html');
}
add_action('admin_menu', 'glossary_tooltip_options_page');

// Mostra il contenuto della pagina delle opzioni del plugin
function glossary_tooltip_options_page_html() {
    // Verifica i permessi
    if (!current_user_can('manage_options')) {
        return;
    }

    // Salva le opzioni se il modulo è stato inviato
    if (isset($_POST['glossary_tooltip_options_nonce']) && wp_verify_nonce($_POST['glossary_tooltip_options_nonce'], 'glossary_tooltip_options')) {
        update_option('glossary_tooltip_bg_color', sanitize_text_field($_POST['glossary_tooltip_bg_color']));
        update_option('glossary_tooltip_text_color', sanitize_text_field($_POST['glossary_tooltip_text_color']));
        update_option('glossary_tooltip_font_size', sanitize_text_field($_POST['glossary_tooltip_font_size']));
    }

    // Ricava le opzioni dal database
    $bg_color = get_option('glossary_tooltip_bg_color', '#333');
    $text_color = get_option('glossary_tooltip_text_color', '#fff');
    $font_size = get_option('glossary_tooltip_font_size', '14px');

    // Mostra il modulo delle opzioni
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

// Registra e mette in coda il tuo file CSS
function glossary_tooltip_enqueue_styles() {
    wp_register_style('glossary-tooltip', plugins_url('glossary-tooltip.css', __FILE__));
    wp_enqueue_style('glossary-tooltip');
}
add_action('wp_enqueue_scripts', 'glossary_tooltip_enqueue_styles');


// Aggiunge i CSS personalizzati per le tooltip in base alle opzioni del plugin
function glossary_tooltip_css() {
    $bg_color = get_option('glossary_tooltip_bg_color', '#333');
    $text_color = get_option('glossary_tooltip_text_color', '#fff');
    $font_size = get_option('glossary_tooltip_font_size', '14px');
}
add_action('wp_head', 'glossary_tooltip_css');

// Carica l'editor di Elementor e registra il nostro widget personalizzato
function glossary_tooltip_elementor() {
    // Verifica se l'editor Elementor è attivo
    if (!did_action('elementor/loaded')) {
        return;
    }

    // Carica il file PHP contenente la classe del nostro widget personalizzato
    require_once plugin_dir_path(__FILE__) . 'glossary-tooltip-elementor-widget.php';

    // Registra il widget personalizzato in Elementor
    add_action('elementor/widgets/widgets_registered', function($widgets_manager) {
        $widgets_manager->register_widget_type(new Glossary_Tooltip_Elementor_Widget());
    });
}
add_action('init', 'glossary_tooltip_elementor');

