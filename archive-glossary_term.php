<?php
get_header();

$args = array(
    'post_type' => 'glossary_term',
    'orderby' => 'title',
    'order' => 'ASC'
);

$glossary_query = new WP_Query($args);

if ($glossary_query->have_posts()) {
    echo '<div id="glossary-container">'; 
    while ($glossary_query->have_posts()) {
        $glossary_query->the_post();
        echo '<h2 id="' . get_the_ID() . '"><a href="#' . get_the_ID() . '">' . get_the_title() . '</a></h2>';
        the_content();
    }
    echo '</div>'; 
} else {
    echo '<p>Nessun termine del glossario trovato.</p>';
}

get_footer();
