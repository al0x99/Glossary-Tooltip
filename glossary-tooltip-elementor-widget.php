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

        $this->add_control(
            'glossary_tooltip_id',
            array(
                'label' => __('ID', 'glossary-tooltip'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'number',
                'placeholder' => __('Inserisci l\'ID del termine del glossario', 'glossary-tooltip'),
            )
        );

        $this->add_control(
            'glossary_tooltip_term',
            array(
                'label' => __('Termine', 'glossary-tooltip'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Inserisci il titolo del termine del glossario', 'glossary-tooltip'),
            )
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id = $settings['glossary_tooltip_id'];
        $term = $settings['glossary_tooltip_term'];

        if ($id || $term) {
            echo do_shortcode('[glossary id="' . esc_attr($id) . '" term="' . esc_attr($term) . '"]');
        }
    }

    protected function _content_template() {
        ?>
        <# if (settings.glossary_tooltip_id || settings.glossary_tooltip_term) {
            var id = settings.glossary_tooltip_id || '';
            var term = settings.glossary_tooltip_term || '';
            var shortcode = '[glossary id="' + id + '" term="' + term + '"]';
            print(shortcode);
        } #>
        <?php
    }
}