<?php

/**
 * Theme Customizer class.
 *
 * Manager class to handle all framework theme customizer functionality.
 *
 * @since 0.1.0
 */
class WPGo_Theme_Customizer {

	/* Declare class type properties. */
	protected $_customize_footer_links_class;
	protected $_customize_theme_colors;
	protected $_customize_column_layout;
	protected $_customize_site_title_and_tagline;

	/**
	 * Theme Customizer class constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		/* Initialize framework and theme specific customizer defaults. */
		$this->initialize_customizer_defaults();

		/* Instantiate customizer classes. */
		$this->_customize_footer_links_class     = new WPGo_Customize_Footer_Links();
		$this->_customize_theme_colors           = new WPGo_Customize_Theme_Colors();
		$this->_customize_column_layout          = new WPGo_Customize_Column_Layout();
		$this->_customize_site_title_and_tagline = new WPGo_Customize_Site_Title();
	}

	/**
	 * Define and initialize theme customizer defaults array.
	 *
	 * @since 0.1.0
	 */
	public function initialize_customizer_defaults() {

		global $wpgo_customizer_defaults;
		$wpgo_customizer_defaults = array(); // initialize to empty array

		/* Load theme customizer defaults. Priority is set to 14 so all the framework add_action() calls are sure to fire BEFORE do_action calls.
		   Otherwise, framework hooks won't work if this order isn't observed. */
		add_action( 'after_setup_theme', array( &$this, 'add_customizer_defaults' ), 14 );
		add_action( 'after_setup_theme', array( &$this, 'customizer_supported_features' ), 12 );
	}

	/**
	 * Customizer supported features.
	 *
	 * @since 0.2.0
	 */
	public function customizer_supported_features() {

		//add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_customizer_styles' ), 8 ); // enqueue before main style sheet
	}

	/**
	 * Hook to allow further customizer defaults to be defined via other framework locations or a Plugin.
	 *
	 * @since 0.1.0
	 */
	public function add_customizer_defaults() {

		/* Add theme specific default settings via this hook. */
		WPGo_Hooks::wpgo_theme_customizer_defaults();
	}

	/**
	 * Get customizer theme option.
	 *
	 * If no customizer theme option exists then use default.
	 *
	 * @since 0.1.0
	 */
	public static function get_customizer_theme_option( $opt, $wpgo_customize_db_name = WPGO_CUSTOMIZE_DB_NAME ) {

		global $wpgo_customizer_defaults;

		$options = get_option( $wpgo_customize_db_name );

		return isset( $options[$opt] ) ? $options[$opt] : $wpgo_customizer_defaults[$opt];
	}

	/**
	 * Set customizer theme option.
	 *
	 * @since 0.1.0
	 */
	public static function set_customizer_theme_option( $opt, $value, $wpgo_customize_db_name = WPGO_CUSTOMIZE_DB_NAME ) {

		$options       = get_option( $wpgo_customize_db_name );
		$options[$opt] = $value;

		update_option( $wpgo_customize_db_name, $options );
	}
}