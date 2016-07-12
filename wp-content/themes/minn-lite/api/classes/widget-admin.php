<?php

/**
 * Registers theme framework sidebars and displays theme icons on widgets.php.
 *
 * @since 0.1.0
 */
class WPGo_Widget_Admin {

	/**
	 * WPGo_Widget_Admin class constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		/* Register the WPGo framework sidebars. */
		add_action( 'widgets_init', array( &$this, 'theme_register_sidebars' ) );
	}

	/**
	 * Register framework widget areas.
	 *
	 * @since 0.1.0
	 */
	public function theme_register_sidebars() {

		/* Primary post widget area. */
		register_sidebar( array(
			'name'          => __( 'Post: Primary', 'wpgothemes' ),
			'id'            => 'primary-post-widget-area',
			'description'   => __( 'The primary single post, and main blog page, widget area', 'wpgothemes' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
			'width'         => 'normal'
		) );

		/* Secondary post widget area. */
		register_sidebar( array(
			'name'          => __( 'Post: Secondary', 'wpgothemes' ),
			'id'            => 'secondary-post-widget-area',
			'description'   => __( 'The secondary single post widget area. Only displayed on three-column post layouts.', 'wpgothemes' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
			'width'         => 'normal'
		) );

		/* Primary page widget area. */
		register_sidebar( array(
			'name'          => __( 'Page: Primary', 'wpgothemes' ),
			'id'            => 'primary-page-widget-area',
			'description'   => __( 'The primary single page widget area', 'wpgothemes' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
			'width'         => 'normal'
		) );

		/* Secondary page widget area. */
		register_sidebar( array(
			'name'          => __( 'Page: Secondary', 'wpgothemes' ),
			'id'            => 'secondary-page-widget-area',
			'description'   => __( 'The secondary single page widget area. Only displayed on three-column page layouts.', 'wpgothemes' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
			'width'         => 'normal'
		) );

		/* Footer widget area. */
		register_sidebar( array(
			'name'          => __( 'Footer', 'wpgothemes' ),
			'id'            => 'footer-widget-area',
			'description'   => __( 'The footer widget area', 'wpgothemes' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
			'width'         => 'normal'
		) );
	}
}