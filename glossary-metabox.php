<?php
// Registra il metadato personalizzato per i termini del glossario utilizzando Meta Box
function glossary_tooltip_register_meta_boxes($meta_boxes) {
  $meta_boxes[] = array(
    'title' => 'Termine del Glossario',
    'id' => 'glossary_term_meta_box',
    'post_types' => array('glossary_term'),
    'context' => 'normal',
    'priority' => 'high',
    'autosave' => true,
    'fields' => array(
      array(
        'name' => 'ID del termine del glossario',
        'id' => 'glossary_term_id',
        'type' => 'number',
      ),
      array(
        'name' => 'Termine del glossario (titolo)',
        'id' => 'glossary_term_title',
        'type' => 'text',
      ),
      array(
        'name' => 'Definizione del termine del glossario (contenuto)',
        'id' => 'glossary_term_content',
        'type' => 'textarea',
      ),
    ),
  );

  return $meta_boxes;
}
add_filter('rwmb_meta_boxes', 'glossary_tooltip_register_meta_boxes');