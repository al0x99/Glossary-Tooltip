<?php
get_header();

$args = array(
    'post_type' => 'glossary_term',
    'orderby' => 'title',
    'order' => 'ASC'
);

$glossary_query = new WP_Query($args);

if ($glossary_query->have_posts()) {
    while ($glossary_query->have_posts()) {
        $glossary_query->the_post();
        
        // Qui puoi mostrare il contenuto del termine del glossario come preferisci.
        // Ad esempio, potresti mostrare il titolo e il link a ogni termine del glossario.
        echo '<h2><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></h2>';
    }
} else {
    echo '<p>Nessun termine del glossario trovato.</p>';
}

get_footer();
