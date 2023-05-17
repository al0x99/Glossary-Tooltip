<?php
// Verifica se l'editor Elementor è attivo
if (!did_action('elementor/loaded')) {
    return;
}

class Glossary_Tooltip_Elementor_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'glossary-tooltip';
    }

    public function get_title() {
        return 'Glossary Tooltip';
    }

    public function get_icon() {
        return 'eicon-shortcode';
    }

    public function get_categories() {
        return array('general');
    }

    protected function _register_controls() {
                
        $this->start_controls_section(
            'glossary_tooltip_section',
            array(
                'label' => __('Glossary Tooltip', 'glossary-tooltip'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        // Ottiene tutti i termini del glossario
        $glossary_terms = get_posts(array(
            'post_type' => 'glossary_term',
            'numberposts' => -1
        ));

        // Prepara un array di termini del glossario per il controllo della casella di selezione
        $glossary_term_options = array();
        foreach ($glossary_terms as $term) {
            $glossary_term_options[$term->ID] = $term->post_title;
        }

        
        $this->add_control(
            'glossary_tooltip_term_select',
            array(
                'label' => __('Termine', 'glossary-tooltip'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $glossary_term_options,
                'multiple' => false,
                'label_block' => true,
                'placeholder' => __('Seleziona un termine del glossario', 'glossary-tooltip'),
            )
        );

        $this->add_control(
            'glossary_tooltip_term_select',
            array(
                'label' => __('Termine', 'glossary-tooltip'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $glossary_term_options,
                'multiple' => false,
                'label_block' => true,
                'placeholder' => __('Seleziona un termine del glossario', 'glossary-tooltip'),
            )
        );
        
        $this->add_control(
            'glossary_tooltip_shortcode',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'glossary_tooltip_shortcode',
                'raw' => '<input id="glossary_tooltip_shortcode" readonly>',
            )
        );
        
        add_action('elementor/editor/after_enqueue_scripts', function() {
            ?>
            <script>
            jQuery(document).ready(function($) {
                elementor.channels.editor.on('change', function(view) {
                    var id = view.model.attributes.settings.attributes.glossary_tooltip_term_select;
                    $('#glossary_tooltip_shortcode').val('[glossary id="' + id + '"]');
                });
            });
            </script>
            <?php
        });
        
        

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
    
        if ($settings['glossary_tooltip_term_select']) {
            echo do_shortcode('[glossary id="' . esc_attr($settings['glossary_tooltip_term_select']) . '"]');
        }
    }
    

    protected function _content_template() {
        ?>
        <# if (settings.glossary_tooltip_term_select) {
            var shortcode = '[glossary id="' + settings.glossary_tooltip_term_select + '"]';
            print(shortcode);
        } #>
        <?php
    }
}