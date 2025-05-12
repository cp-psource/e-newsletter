<?php
class Builder_TextArea_Control extends WP_Customize_Control {
	public $type = 'textarea';
	
	public function render_content() {
		// Diese settings-IDs sollen 4 Zeilen bekommen
		$expanded_ids = array( 'branding_html', 'contact_info' );

		$rows = in_array( $this->id, $expanded_ids ) ? 4 : 1;
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea rows="<?php echo $rows; ?>" style="width:98%;" <?php $this->link(); ?>>
				<?php echo esc_textarea( $this->value() ); ?>
			</textarea>
		</label>
		<?php
	}
}