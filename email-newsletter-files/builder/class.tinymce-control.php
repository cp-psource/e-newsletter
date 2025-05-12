<?php
class Builder_TinyMCE_Control extends WP_Customize_Control {
	public $type = 'tinymce';

	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea id="<?php echo esc_attr( $this->id ); ?>" style="width:98%;" rows="10"<?php echo $this->link(); ?>>
				<?php echo esc_textarea( $this->value() ); ?>
			</textarea>
		</label>

		<script type="text/javascript">
			jQuery(function($) {
				if (tinymce.get('<?php echo esc_js( $this->id ); ?>')) {
					tinymce.get('<?php echo esc_js( $this->id ); ?>').remove();
				}

				tinymce.init({
					selector: '#<?php echo esc_js( $this->id ); ?>',
					menubar: false,
					toolbar: 'formatselect bold italic underline | bullist numlist | link code | forecolor backcolor | charmap',
					plugins: 'lists link paste wordpress colorpicker textcolor charmap',
					setup: function (editor) {
						editor.on('change keyup', function () {
							editor.save();
							$('#<?php echo esc_js( $this->id ); ?>').trigger('change');
						});
					}
				});
			});
		</script>
		<?php
	}
}
?>