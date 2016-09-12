<?php

/**
 * Customize Column Layout Class
 *
 * Manages global column layout via the theme customizer.
 *
 * @since 0.2.0
 *
 */
class WPGo_Customize_Column_Layout {

	/**
	 * Class constructor.
	 *
	 * @since 0.2.0
	 */
	public function __construct() {

		/* Priority set to 12 so the callback fires AFTER the supported features have been specified in the extended WPGo_Framework class.
		 * This allows a call in a child theme such as add_action( 'after_setup_theme', 'child_framework_features', 11 ) to easily remove/redefine added features.
		 */
		add_action( 'after_setup_theme', array( &$this, 'customizer_supported_features' ), 12 );
	}

	/**
	 * Column layouts.
	 *
	 * @since 0.2.0
	 */
	public function customizer_supported_features() {

		/* Add global column layout drop down to the theme customizer. */
		add_action( 'wpgo_theme_customizer_defaults', array( &$this, 'customizer_column_layout_defaults' ) );
		add_action( 'customize_register', array( &$this, 'theme_customizer_register_column_layout' ) );
	}

	/**
	 * Customizer column layout defaults.
	 *
	 * @since 0.1.0
	 */
	public function customizer_column_layout_defaults() {

		global $wpgo_customizer_defaults;
		$wpgo_customizer_defaults['wpgo_drp_default_layout'] = '2-col-r';
	}

	/**
	 * Add a select box drop down to theme customizer to control global column layout.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 *
	 * @return void
	 */
	public function theme_customizer_register_column_layout( $wp_customize ) {

		global $wpgo_customizer_defaults;

		$column_array = array(
			'1-col'   => __( '1-Column (full width)', 'wpgothemes' ),
			'2-col-l' => __( '2-Column Sidebar Left', 'wpgothemes' ),
			'2-col-r' => __( '2-Column Sidebar Right', 'wpgothemes' ),
			'3-col-l' => __( '3-Column Sidebars Left', 'wpgothemes' ),
			'3-col-r' => __( '3-Column Sidebars Right', 'wpgothemes' ),
			'3-col-c' => __( '3-Column Sidebars Center', 'wpgothemes' )
		);

		$wp_customize->add_section( 'wpgo_column_layout', array(
			'title'    => __( 'Column Layout', 'wpgothemes' ),
			'priority' => 41
		) );

		$wp_customize->add_setting( WPGO_CUSTOMIZE_DB_NAME . '[wpgo_drp_default_layout]', array(
			'default' => $wpgo_customizer_defaults['wpgo_drp_default_layout'],
			'type'    => 'option'
		) );

		$wp_customize->add_control( WPGO_CUSTOMIZE_DB_NAME . '[wpgo_drp_default_layout]', array(
				'label'   => __( 'Select Global Column Layout', 'wpgothemes' ),
				'section' => 'wpgo_column_layout',
				'type'    => 'select',
				'choices' => $column_array )
		);
	}
}