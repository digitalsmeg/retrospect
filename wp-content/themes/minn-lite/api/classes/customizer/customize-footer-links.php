<?php

/**
 * Customize Footer Links Control Class
 *
 * Displays a textarea control to manage footer link HTML via the theme customizer.
 *
 * @since 0.2.0
 *
 */
class WPGo_Customize_Footer_Links {

	/**
	 * Class constructor.
	 *
	 * @since 0.2.0
	 */
	public function __construct() {

		/* Setup class default features. */
		add_action( 'after_setup_theme', array( &$this, 'setup_default_features' ), 9 ); // higher priority to allow feature removal via parent theme functions.php

		/* Priority set to 12 so the callback fires AFTER the supported features have been specified in the extended WPGo_Framework class.
		 * This also allows a call in a child theme such as add_action( 'after_setup_theme', 'child_framework_features', 11 ) to easily remove/redefine added features.
		 */
		add_action( 'after_setup_theme', array( &$this, 'customizer_supported_features' ), 12 );
	}

	/**
	 * Setup class default features.
	 *
	 * @since 0.2.0
	 */
	public function setup_default_features() {

		/* Enable support for footer links in current theme. Can easily be removed via parent/child theme. */
		if ( ! current_theme_supports( 'wpgo-footer-links' ) ) {
			add_theme_support( 'wpgo-footer-links' );
		}
	}

	/**
	 * Customizer footer links.
	 *
	 * @since 0.2.0
	 */
	public function customizer_supported_features() {

		/* Customizer footer links. */
		if ( current_theme_supports( 'wpgo-footer-links' ) ) {

			/* Set footer links customizer defaults. */
			add_action( 'wpgo_theme_customizer_defaults', array( &$this, 'customizer_footer_links_defaults' ) );

			/* Register footer links customizer control. */
			add_action( 'customize_register', array( &$this, 'customizer_footer_links' ) );

			/* Render footer links HTML. */
			add_action( 'wpgo_before_closing_footer_tag', array( &$this, 'render_footer_links' ) );
		}
	}

	/**
	 * Customizer footer links defaults.
	 *
	 * @since 0.1.0
	 */
	public function customizer_footer_links_defaults() {

		global $wpgo_customizer_defaults;

		$footer_link_path = 'http://wordpress.org/themes/'.WPGO_THEME_NAME_H;
		$wpgo_customizer_defaults['wpgo_txtar_footer_links'] = '<div id="site-info"><p class="copyright">Copyright &copy; '.date("Y").'</p><p class="wpgo-link">Powered by <a href="http://wordpress.org/" target="_blank" class="wp-link"><i class="genericon-wordpress"></i></a> and the <a href="' . $footer_link_path . '" target="blank">' . WPGO_THEME_NAME . ' Theme</a>.</p></div><!-- #site-info -->';
	}

	/**
	 * Add a footer links textarea to the theme customizer.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 *
	 * @return void
	 */
	public function customizer_footer_links( $wp_customize ) {

		/* Reference theme customizer option defaults. */
		global $wpgo_customizer_defaults;

		/* Include class to extend the WP_Customize_Image_Control class. */
		if ( file_exists( get_template_directory() . '/api/classes/customizer/subclass-controls/customize-textarea-control.php' ) ) {
			require_once( get_template_directory() . '/api/classes/customizer/subclass-controls/customize-textarea-control.php' );
		} else {
			return;
		}

		$wp_customize->add_section( 'wpgo_footer_links_section', array(
			'title'          => __( 'Footer Links', 'wpgothemes' ),
			'priority'       => 121,
			'theme_supports' => 'wpgo-footer-links'
		) );

		/* Add textarea control to manage custom footer links HTML via the theme customizer. */
		$wp_customize->add_setting( WPGO_CUSTOMIZE_DB_NAME . '[wpgo_txtar_footer_links]', array(
			'default' => $wpgo_customizer_defaults['wpgo_txtar_footer_links'],
			'type'    => 'option'
		) );

		$wp_customize->add_control( new WPGo_Customize_Textarea_Control( $wp_customize, 'wpgo_txtar_footer_links', array(
			'label'    => __( 'Customize Footer Links HTML', 'wpgothemes' ),
			'section'  => 'wpgo_footer_links_section',
			'settings' => WPGO_CUSTOMIZE_DB_NAME . '[wpgo_txtar_footer_links]'
		) ) );
	}

	/**
	 * Add code to footer to render the footer links (year, privacy policy).
	 *
	 * @since 0.1.0
	 */
	public function render_footer_links() {

		$footer_links_html = WPGo_Theme_Customizer::get_customizer_theme_option( 'wpgo_txtar_footer_links' );
		echo do_shortcode( $footer_links_html ); // process any shortcodes in the footer HTML
	}
}