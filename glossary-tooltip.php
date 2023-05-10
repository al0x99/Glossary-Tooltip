<?php
/*
Plugin Name: Glossary Tooltip
Description: Un semplice plugin per creare tooltip di glossario in Elementor
Version: 1.0
Author: Alin Sfirschi
*/

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
    $post = get_post($atts['id']);

    // Restituisce il termine del glossario con la tooltip
    if ($post) {
        return '<span class="glossary-term" title="' . $post->post_content . '">' . $post->post_title . '</span>';
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
    </style>
    ';
}
add_action('wp_head', 'glossary_tooltip_css');
?>
