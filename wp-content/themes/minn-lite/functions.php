<?php

/* Load WPGo theme framework class */
if ( file_exists( get_template_directory() . '/api/classes/api.php' ) ) {
	require_once( get_template_directory() . '/api/classes/api.php' );
}



function theme_name_scripts() {
	wp_enqueue_style( 'responsive-style', get_template_directory_uri() . '/responsive.css' );
	wp_enqueue_style( 'custom-style', get_template_directory_uri() . '/custom.css' );
	wp_enqueue_style( 'custom-style-loaders', get_template_directory_uri() . '/loaders.css' );
	
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );

class WPGo_Main_Theme extends WPGo_Framework {

	/* Class constructor. */
	public function __construct( $theme_name ) {

		define( 'WPGO_PARENT_THEME_DEMO_URL', 'http://demo.wpgothemes.com/minn/' );
		define( 'WPGO_PARENT_THEME_DEMO_LABEL', 'Minn Live Demo' );

		/* Call parent constructor manually to make both constructors fire. */
		parent::__construct( $theme_name );

		/* Add theme support for framework features. */
		add_action( 'after_setup_theme', array( &$this, 'add_theme_features' ), 8 );

		/* Add theme image sizes. */
		add_action( 'after_setup_theme', array( &$this, 'image_sizes' ) );
	}

	/* Add theme support for framework features. */
	public function add_theme_features() {

		/* Add array of menu location labels. Remove to use a single default menu. */
		add_theme_support( 'wpgo-custom-menus', array( 'Main Menu', 'Top Menu' ) );
	}

	/* Add theme image sizes. */
	public function image_sizes() {

		// default size for post thumbnails
		set_post_thumbnail_size( 650, 200, true );
	}

} /* End class definition */

/* Create theme class instance */
global $wpgo_theme_object;
$wpgo_theme_object = new WPGo_Main_Theme( 'Minn Lite' );

