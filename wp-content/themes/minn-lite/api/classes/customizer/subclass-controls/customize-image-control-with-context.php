<?php

/**
 * Customize Image Control Class with Added Context
 *
 * Extend WP_Customize_Image_Control allowing access to uploads made within the same context.
 *
 */
class WPGo_Customize_Image_Control_WithContext extends WP_Customize_Image_Control {

	public function __construct( $manager, $id, $args = array() ) {

		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Search for images within the defined context.
	 *
	 */
	public function tab_uploaded() {
		$wpgo_context_uploads = get_posts( array(
			'post_type'  => 'attachment',
			'meta_key'   => '_wp_attachment_context',
			'meta_value' => $this->context,
			'orderby'    => 'post_date',
			'nopaging'   => true,
		) );
		?>

		<div class="uploaded-target"></div>

		<?php
		if ( empty( $wpgo_context_uploads ) ) {
			return;
		}

		foreach ( (array) $wpgo_context_uploads as $wpgo_context_upload ) {
			$this->print_tab_image( esc_url_raw( $wpgo_context_upload->guid ) );
		}
	}
}