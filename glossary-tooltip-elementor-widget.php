<?php
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


        $glossary_terms = get_posts(array(
            'post_type' => 'glossary_term',
            'numberposts' => -1
        ));

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
            'glossary_tooltip_shortcode',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'content_classes' => 'glossary_tooltip_shortcode',
                'raw' => '<input id="glossary_tooltip_shortcode" readonly>',
            )
        );
        
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
    
        if ($settings['glossary_tooltip_term_select']) {
            echo do_shortcode('[glossary id="' . esc_attr($settings['glossary_tooltip_term_select']) . '"]');
        }
        
        ?>
        <script>
        jQuery(document).ready(function($) {
            var shortcodeInput = $('#glossary_tooltip_shortcode');

            function updateShortcode() {
                var selectedTermId = '<?php echo esc_js($settings['glossary_tooltip_term_select']); ?>';
                var shortcode = '[glossary id="' + selectedTermId + '"]';
                shortcodeInput.val(shortcode);
            }

            updateShortcode();

            <?php if (defined('ELEMENTOR_VERSION')) : ?>
                // Aggiorna lo shortcode nell'editor Elementor
                elementor.channels.editor.on('change', function(view) {
                    updateShortcode();
                });
            <?php endif; ?>
        });
        </script>
        <?php
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
