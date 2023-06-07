jQuery(document).ready(function($) {
    var shortcodeInput = $('#glossary_tooltip_shortcode');
    function updateShortcode() {
        var selectedTermId = shortcodeInput.data('term-id');
        var shortcode = '[glossary id="' + selectedTermId + '"]';
        shortcodeInput.val(shortcode);
    }
    updateShortcode();
    if (typeof elementor !== 'undefined') {
        elementor.channels.editor.on('change', function(view) {
            updateShortcode();
        });
    }
});
