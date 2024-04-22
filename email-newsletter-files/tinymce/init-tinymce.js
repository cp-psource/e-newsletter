// Initialisierung des TinyMCE-Editors
jQuery(document).ready(function($) {
    tinymce.init({
        selector: '.your-textarea-class', // Klasse der Textbereiche, die TinyMCE verwenden sollen
        // Weitere Konfigurationsoptionen hier nach Bedarf
    });
});