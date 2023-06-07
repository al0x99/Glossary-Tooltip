jQuery(document).ready(function($) {
    var shortcodeInput = $('#glossary_tooltip_shortcode');

    function updateShortcode() {
        var selectedTermId = shortcodeInput.data('term-id');
        var shortcode = '[glossary id="' + selectedTermId + '"]';
        shortcodeInput.val(shortcode);
    }

    updateShortcode();

    // Se Elementor è definito
    if (typeof elementor !== 'undefined') {
        // Aggiorna lo shortcode nell'editor Elementor
        elementor.channels.editor.on('change', function(view) {
            updateShortcode();
        });
    }
});
