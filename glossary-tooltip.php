<?php
/*
Plugin Name: Glossary Tooltip
Description: Un semplice plugin per creare tooltip di glossario in Elementor
<<<<<<< HEAD
Version: 1.2dev
Author: Alin Sfirschi
*/

// Registra un nuovo tipo di post personalizzato per i termini del glossario

// Includi il file per creare il meta box personalizzato
require_once plugin_dir_path(__FILE__) . 'glossary-metabox.php';

=======
Version: 1.0
Author: Alin Sfirschi
*/

>>>>>>> parent of 90a24e5 (elementor integration)
function glossary_tooltip() {
    // Registra un nuovo tipo di post personalizzato per i termini del glossario
    register_post_type('glossary_term',
        array(
            'labels' => array(
                'name' => 'Termini del Glossario',
                'singular_name' => 'Termine del Glossario'
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor')
        )
    );
}
add_action('init', 'glossary_tooltip');

<<<<<<< HEAD

// Registra lo shortcode per i termini del glossario tramite metabox
=======
>>>>>>> parent of 90a24e5 (elementor integration)
function glossary_shortcode($atts) {
    // Estrae gli attributi dello shortcode
    $atts = shortcode_atts(
        array(
            'id' => '',  // l'ID del termine del glossario
        ),
        $atts,
        'glossary'
    );

    // Ottiene il termine del glossario dal database
<<<<<<< HEAD
    if (!empty($atts['id'])) {
        $args = array(
            'post_type' => 'glossary_term',
            'meta_query' => array(
                array(
                    'key' => 'glossary_term_id',
                    'value' => $atts['id'],
                    'compare' => '=',
                ),
            ),
        );
        $query = new WP_Query($args);
        $post = $query->have_posts() ? $query->posts[0] : null;
    } elseif (!empty($atts['term'])) {
        $post = get_page_by_title($atts['term'], OBJECT, 'glossary_term');
    } else {
        $post = null;
    }

    // Restituisce il termine del glossario con la tooltip
    if ($post) {
        $definition = rwmb_meta('glossary_term_content', array('object_type' => 'post'), $post->ID);
        return '<span class="glossary-term" title="' . esc_attr($definition) . '">' . esc_html($post->post_title) . '</span>';
=======
    $post = get_post($atts['id']);

    // Restituisce il termine del glossario con la tooltip
    if ($post) {
        return '<span class="glossary-term" title="' . $post->post_content . '">' . $post->post_title . '</span>';
>>>>>>> parent of 90a24e5 (elementor integration)
    }
}
add_shortcode('glossary', 'glossary_shortcode');

function glossary_tooltip_css() {
    echo '
    <style>
    .glossary-term {
        position: relative;
        cursor: help;
    }
    
    .glossary-term::after {
        content: attr(title);
        display: none;
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 5px;
        background: #333;
        color: #fff;
    }
    
    .glossary-term:hover::after {
        display: block;
    }

    h1 .glossary-term::after,
    h2 .glossary-term::after,
    h3 .glossary-term::after,
    h4 .glossary-term::after,
    h5 .glossary-term::after,
    h6 .glossary-term::after {
        white-space: normal;
        max-width: 250px;
    }
    </style>
    ';
}

add_action('wp_head', 'glossary_tooltip_css');
?>
