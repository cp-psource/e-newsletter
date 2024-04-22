<?php
class Builder_TinyMCE_Control extends WP_Customize_Control {
    public $type = 'tinymce';

    // Diese Funktion lädt den TinyMCE-Editor
    public function load_tinymce_editor() {
        // Pfad zum Verzeichnis der TinyMCE-Dateien innerhalb deines Plugins
        $plugin_url = plugin_dir_url( __FILE__ );
        $tinymce_url = $plugin_url . 'email-newsletter-files/tinymce/';

        // TinyMCE-JavaScript-Datei registrieren und einbinden
        wp_register_script( 'tinymce', $tinymce_url . 'tinymce.min.js', array(), false, true );
        wp_enqueue_script( 'tinymce' );

        // TinyMCE-CSS-Datei registrieren und einbinden
        wp_register_style( 'tinymce', $tinymce_url . 'skins/ui/oxide/skin.min.css', array(), false, 'all' );
        wp_enqueue_style( 'tinymce' );
    }

    public function render_content() {
        global $enewsletter_tinymce;
        ?>
        <span class="customize-control-title"><?php echo $this->label; ?></span>
        <textarea id="<?php echo $this->id; ?>" style="display:none" <?php echo $this->link(); ?>><?php echo esc_textarea($this->value()); ?></textarea>
        <?php
        echo $enewsletter_tinymce;
        ?>
        
        <script type="text/javascript">
            // Diese Funktion initialisiert den TinyMCE-Editor
            function init_tinymce_editor() {
                tinymce.init({
                    selector: '#<?php echo $this->id; ?>', // Selector für das Textarea-Element
                    setup: function(editor) {
                        var content = 0;
                        editor.on('init', function() {
                            // Unser Code, der nach der vollständigen Initialisierung des Editors ausgeführt wird
                            setInterval(function() {
                                var check_content = editor.getContent({format: 'raw'});
                                
                                if (check_content !== content && check_content !== '<p><br data-mce-bogus="1"></p>') {
                                    content = check_content;
                                    jQuery('#<?php echo $this->id; ?>').val(content).trigger('change');
                                }
                            }, 2000);
                        });

                        // Enables resizing of email content box
                        var resize;
                        var prev_emce_width = 0;
                        jQuery('#accordion-section-builder_email_content').on('mousedown', '.mce-i-resize, #content_tinymce_resize', function(){
                            resize_start();
                        });
                        jQuery('#accordion-section-builder_email_content h3').on('click', function(){
                            resize_start();
                        });
                        jQuery("body").on('mouseup', function() {
                            clearInterval(resize);
                        });

                        function resize_start() {
                            resize = setInterval(function() {
                                emce_width = jQuery('#content_tinymce_ifr').width() + 65;
                                
                                if (emce_width >= '490' && emce_width != prev_emce_width) {
                                    jQuery('#customize-controls').css("-webkit-animation", "none");
                                    jQuery('#customize-controls').css("-moz-animation", "none");
                                    jQuery('#customize-controls').css("-ms-animation", "none");
                                    jQuery('#customize-controls').css("animation", "none");
                                    prev_emce_width = emce_width;
                                    jQuery('#customize-controls, #customize-footer-actions').css("width", emce_width + "px");
                                    jQuery('.wp-full-overlay').css("margin-left", emce_width + "px");
                                    jQuery('.wp-full-overlay-sidebar').css("margin-left", "-" + emce_width + "px");
                                }
                            }, 50);    
                        }
                    }
                });
            }

            // TinyMCE-Editor nur laden, wenn der Code aktiv ist
            if (is_active_widget( false, false, 'builder_tinymce_control', true )) {
                // TinyMCE-Editor laden
                load_tinymce_editor();
                // TinyMCE-Editor initialisieren
                init_tinymce_editor();
            }
        </script>
        <?php
    }
}
?>

