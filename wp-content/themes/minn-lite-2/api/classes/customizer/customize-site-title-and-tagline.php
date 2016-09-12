<?php

/**
 * Customize Site Title and Tagline Class
 *
 * Allows you to edit the site title and taglone in real-time via the theme customizer.
 *
 * @since 0.2.0
 *
 */
class WPGo_Customize_Site_Title {

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
	 * Customizer site title.
	 *
	 * @since 0.2.0
	 */
	public function customizer_supported_features() {

		/* Register framework theme customizer site title and tagline controls. */
		add_action( 'wpgo_theme_customizer_defaults', array( &$this, 'customizer_site_title_defaults' ) );
		add_action( 'customize_register', array( &$this, 'theme_customizer_register_site_title' ) );
	}

	/**
	 * Site title and tagline defaults.
	 *
	 * @since 0.2.0
	 */
	public function customizer_site_title_defaults() {

		global $wpgo_customizer_defaults;
		$wpgo_customizer_defaults['wpgo_chk_hide_description'] = null;
	}

	/**
	 * Enable real-time editing of site title and tagline via the customizer.
	 *
	 * @since 0.1.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 *
	 * @return void
	 */
	public function theme_customizer_register_site_title( $wp_customize ) {

		/* Reference theme customizer option defaults. */
		global $wpgo_customizer_defaults;

		/* Add checkbox to display/hide site tagline. */
		$wp_customize->add_setting( WPGO_CUSTOMIZE_DB_NAME . '[wpgo_chk_hide_description]', array(
			'default' => $wpgo_customizer_defaults['wpgo_chk_hide_description'],
			'type'    => 'option'
		) );
		$wp_customize->add_control( WPGO_CUSTOMIZE_DB_NAME . '[wpgo_chk_hide_description]', array(
			'label'   => __( 'Hide tagline', 'wpgothemes' ),
			'section' => 'title_tagline',
			'type'    => 'checkbox'
		) );

		/* Add JS to footer to make the site title and tagline update in real-time. */
		if ( $wp_customize->is_preview() && ! is_admin() ) {
			add_action( 'wp_footer', array( $this, 'real_time_customize_preview' ), 21 );
		}

		/* Update theme customizer transport setting for site title and tagline so they change in real-time. */
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	}

	/**
	 * Add JavaScript to the previewer frame footer to enable live edit updates.
	 *
	 * @since 0.1.0
	 */
	public function real_time_customize_preview() {
		?>
		<script defer="defer">
			(function ($) {
				wp.customize('blogname', function (value) {
					value.bind(function (to) {
						$('#site-title a').html(to);
					});
				});
				wp.customize('blogdescription', function (value) {
					value.bind(function (to) {
						$('#site-description').html(to);
					});
				});
			})(jQuery)
		</script>
	<?php
	}
}