<?php

/**
 * Customize Textarea Control Class
 *
 * Enables a textarea to be added to the theme customizer.
 *
 */
class WPGo_Customize_Textarea_Control extends WP_Customize_Control {
	public $type = 'textarea';

	public function render_content() {
		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<textarea class="regular-text code" rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
		</label>
	<?php
	}
}