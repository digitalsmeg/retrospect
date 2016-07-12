<?php

/**
 * Framework deprecated class. All deprecated framework functionality added here will be
 * deleted in a future version.
 *
 * @since 0.1.0
 */
class WPGo_Deprecated {

	/**
	 * WPGo_Deprecated class constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		add_action( 'load-widgets.php', array( &$this, 'load_widgets_script' ) );
	}

	/**
	 * Add custom jQuery script to fix bug when first dragging widget into a widget area.
	 *
	 *
	 * @since 0.1.0
	 */
	public function load_widgets_script() {

		wp_enqueue_script( 'admin_widget_bug_fix', WPGO_THEME_ROOT_URI . '/api/deprecated/widget-bug-fix.js', array( 'jquery' ) );
	}
}