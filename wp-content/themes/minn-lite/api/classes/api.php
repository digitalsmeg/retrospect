<?php
/*
 *
 * WPGo Theme Framework 0.2.0
 *
*/

/* Load theme framework classes. */
require_once( get_template_directory() . '/api/bootstrap.php' );

/**
 * WPGo theme framework class.
 *
 * @since 0.1.0
 */
class WPGo_Framework {

	/**
	 * Framework class properties.
	 *
	 * @since 0.1.0
	 */

	/* Declare properties of type class. */
	protected $_widgets_admin_class;
	protected $_utility_callbacks_class;
	protected $_ts_utility_callbacks_class;
	protected $_ts_utility_class;
	protected $_meta_boxes_class;
	protected $_theme_options_class;
	protected $_theme_customizer_class;
	protected $_template_parts;
	protected $_deprecated_class;

	/**
	 * Class constructor.
	 *
	 * Loads required framework files in the correct order.
	 *
	 * @since 0.1.0
	 */
	public function __construct( $theme_name ) {

		/* Define framework constants */
		$this->constants( $theme_name );

		/* Core framework classes. */
		$this->_utility_callbacks_class    = new WPGo_Utility_Callbacks();
		$this->_meta_boxes_class           = new WPGo_MetaBoxes();
		$this->_theme_customizer_class     = new WPGo_Theme_Customizer();
		$this->_deprecated_class           = new WPGo_Deprecated();
		$this->_theme_options_class        = new WPGo_Theme_Options();
		$this->_widgets_admin_class        = new WPGo_Widget_Admin();
		$this->_template_parts             = new WPGo_Template_Parts();

		/* Setup theme framework. */
		$this->setup();

		/* Setup class default features. */
		add_action( 'after_setup_theme', array( &$this, 'setup_default_features' ), 9 ); // higher priority to allow feature removal via parent theme functions.php

		/* Enqueue required scripts. */
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_required_scripts' ) );

		/* After theme switched. Good place to run code that only needs executing ONCE after the theme is activated. */
		add_action( 'after_switch_theme', array( &$this, 'after_theme_activated' ) );

		/* Setup the themes text domain and .mo file for translation. */
		add_action( 'after_setup_theme', array( &$this, 'setup_locale' ) );

		/* Load core framework features.
		 *
		 * Priority set to 12 so the 'framework_features' callback fires AFTER the supported features have been specified in the extended WPGo_Framework class.
		 * This also allows a call in a child theme such as: add_action( 'after_setup_theme', 'child_framework_features', 11 ) to easily remove/redefine added features.
		 */
		add_action( 'after_setup_theme', array( &$this, 'framework_features' ), 12 );
	}

	/**
	 * Setup theme framework.
	 *
	 * @since 0.1.0
	 */
	public function setup() {

		/* Enable HTML in taxonomy description box. */
		remove_filter( 'pre_term_description', 'wp_filter_kses' );

		/* Set theme framework global variables. */
		add_action( 'template_redirect', array( &$this->_utility_callbacks_class, 'set_globals' ) );

		/* Get theme template file used to render current page. */
		add_action( 'template_include', array( &$this->_utility_callbacks_class, 'set_current_template' ) );

		/* Enqueue main theme style sheet. */
		add_action( 'wp_enqueue_scripts', array( &$this->_utility_callbacks_class, 'enqueue_main_style_sheet' ), 9 );

		/* Replace the default WordPress search form with our own. */
		add_filter( 'get_search_form', array( &$this, 'custom_search_form' ) );
	}

	/**
	 * Setup class default theme features.
	 *
	 * Enable support for features in current theme. Each of these can easily be removed via parent/child theme.
	 *
	 * @since 0.2.0
	 */
	public function setup_default_features() {

		/** WORDPRESS BUILT-IN SUPPORTED THEME FEATURES **/

		if ( ! current_theme_supports( 'automatic-feed-links' ) ) {
			add_theme_support( 'automatic-feed-links' );
		} // add posts and comments RSS feed links to head

		if ( ! current_theme_supports( 'post-thumbnails' ) ) {
			add_theme_support( 'post-thumbnails' );
		} // use the post thumbnails feature

		if ( ! current_theme_supports( 'html5' ) ) {
			add_theme_support( 'html5', // use core html5 markup for search form, comment form, and comments
				array(
					'search-form',
					'comment-form',
					'comment-list'
				)
			);
		}

		add_editor_style(); // post/page editor style sheet to match site styles

		/** WPGO THEME FRAMEWORK SUPPORTED FEATURES **/

		if ( ! current_theme_supports( 'custom-background' ) ) {
			add_theme_support( 'custom-background' );
		} // site background image uploader
		if ( ! current_theme_supports( 'wpgo-custom-menus' ) ) {
			add_theme_support( 'wpgo-custom-menus' );
		} // single default menu called 'Main Menu'
	}

	/**
	 * Defines the framework constants.
	 *
	 * @since 0.1.0
	 */
	public function constants( $theme_name ) {

		$theme_name = trim( $theme_name );
		if ( ! isset( $theme_name ) || empty( $theme_name ) ) {
			wp_die( 'No theme name specified.' );
		}

		/* Define main theme name label. */
		define( "WPGO_THEME_NAME", $theme_name );
		define( "WPGO_THEME_NAME_U", strtolower( str_replace( " ", "_", WPGO_THEME_NAME ) ) ); // Underscored lower case theme name
		define( "WPGO_THEME_NAME_H", strtolower( str_replace( " ", "-", WPGO_THEME_NAME ) ) ); // Hyphenated lower case theme name
		define( "WPGO_THEME_MENU_SLUG", WPGO_THEME_NAME_U . "_admin_options_menu" );
		define( "WPGO_THEME_NAME_SLUG", WPGO_THEME_NAME_H ); /* Theme name slug label (used mainly in the options pages). */

		/* Theme paths. */
		define( "WPGO_THEME_ROOT_DIR", get_template_directory() );
		define( "WPGO_THEME_ROOT_URI", get_template_directory_uri() );
		define( "WPGO_CHILD_ROOT_DIR", get_stylesheet_directory() );
		define( "WPGO_CHILD_ROOT_URI", get_stylesheet_directory_uri() );

		/* Define theme options constants. */
		define( "WPGO_OPTIONS_DB_NAME", WPGO_THEME_NAME_U . "_theme_options" );
		define( "WPGO_THEME_OPTIONS_GROUP", WPGO_THEME_NAME_U . "_theme_options_group" );
		define( "WPGO_CUSTOMIZE_DB_NAME", WPGO_THEME_NAME_U . "_customize_options" );
	}

	/**
	 * Setup the themes text domain and .mo file for translation.
	 *
	 * If the parent theme is active check '/languages' folder in parent theme for valid .mo file.
	 * If a child theme is active check '/languages' folder in child theme first, then parent theme, for valid .mo file.
	 * If no valid .mo file found in parent or child theme then no translation used.
	 *
	 * @since 0.1.0
	 */
	public function setup_locale() {

		/* Setup locale variables. */
		$locale            = get_locale();
		$locale_mofile_dir = 'languages';
		$locale_filename   = $locale_mofile_dir . '/' . $locale . '.mo';

		/* Set theme text domain and .mo file. */
		if ( file_exists( STYLESHEETPATH . '/' . $locale_filename ) ) {
			load_theme_textdomain( 'wpgothemes', STYLESHEETPATH . '/' . $locale_mofile_dir );
		} else {
			if ( file_exists( TEMPLATEPATH . '/' . $locale_filename ) ) {
				load_theme_textdomain( 'wpgothemes', TEMPLATEPATH . '/' . $locale_mofile_dir );
			}
		}
	}

	/**
	 * Loads the core framework and optional features, specified in the extended class.
	 *
	 * @since 0.1.0
	 */
	public function framework_features() {

		/* Register support for custom navigation menus */
		if ( current_theme_supports( 'wpgo-custom-menus' ) ) {

			$features = get_theme_support( 'wpgo-custom-menus' );

			add_filter( 'wp_nav_menu_args', array( &$this, 'primary_nav_menu_wrapper' ) ); // add a <label> tag before primary menu

			if ( is_array( $features ) ) { // check we have at least one parameter
				$priority = array( 'primary', 'secondary', 'tertiary', 'quaternary', 'quinary', 'senary', 'septenary', 'octonary', 'nonary', 'denary' );
				$i        = 0; /* Counter */
				foreach ( $features[0] as $nav_menu ) {

					/*  If there are more than 10 custom menus defined, revert to a number suffix system. */
					if ( $i >= 10 ) {
						define( "WPGO_CUSTOM_NAV_MENU_" . ( $i + 1 ), WPGO_THEME_NAME_H . "-theme-" . ( $i + 1 ) );
					} else {
						define( "WPGO_CUSTOM_NAV_MENU_" . ( $i + 1 ), WPGO_THEME_NAME_H . "-theme-" . $priority[$i] );
					}

					/*  Register each nav menu. */
					register_nav_menus( array(
						constant( "WPGO_CUSTOM_NAV_MENU_" . ( $i + 1 ) ) => $nav_menu
					) );

					$i ++; // increment counter
				}
			} else {
				/* Defaulting to one nav menu. */
				define( "WPGO_CUSTOM_NAV_MENU_1", WPGO_THEME_NAME_H . "-theme-primary" );
				register_nav_menus( array(
					WPGO_CUSTOM_NAV_MENU_1 => __( 'Primary Navigation', 'wpgothemes' ),
				) );
			}
		}

		/* ADDITIONAL FRAMEWORK FEATURES */

		/* Show theme activation message (via 'theme_activated' callback). */
		global $pagenow;
		if ( is_admin() && isset($_GET['activated']) && $pagenow == "themes.php" ) {

			/* WordPress Administration Widgets API. */
			/* This is only loaded on widgets.php so we need it on themes.php to access certain Widgets API functions that we wouldn't otherwise be able to access. */
			require_once(ABSPATH . 'wp-admin/includes/widgets.php');

			/* Show theme activation message, and setup them option defaults. */
			add_action( 'admin_notices', array( &$this, 'theme_activated' ) );
		}
	}

	/**
	 * One of the PHP magic methods.
	 *
	 * Used for retrieving values of private and protected variables from outside of the class.
	 *
	 * @since 0.1.0
	 */
	public function __get( $var ) {
		return $this->$var;
	}

	/**
	 * Enqueue required scripts.
	 *
	 * @since 0.1.0
	 */
	public function enqueue_required_scripts() {
		if ( is_singular() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * This function is executed once after theme activation.
	 *
	 * @since 0.1.0
	 */
	public function after_theme_activated() {

		/* Flush permalinks only ONCE after theme activation and custom taxonomies have been registered (these are registered before this function executes). */
		flush_rewrite_rules();
	}

	/**
	 * Process all add_action() calls for a specific theme feature.
	 *
	 * @since 0.1.0
	 */
	public static function add_custom_action_cb_priority( $theme_feature, $params ) {

		/* Access the global theme features array. */
		global $_wp_theme_features;

		foreach ( $params as $key => $param ) {

			/* Merge default params with custom params if specified. */
			if ( isset( $_wp_theme_features[$theme_feature] ) && is_array( $_wp_theme_features[$theme_feature] ) ) {
				if ( array_key_exists( $key, $_wp_theme_features[$theme_feature][0] ) ) {
					$param = $_wp_theme_features[$theme_feature][0][$key] + $param;
				} // union of both arrays
			}

			add_action( $param['hook'], $param['callback'], $param['priority'] );
		}
	}

	/**
	 * Add a <label> tag just before the primary menu for responsive behaviour.
	 *
	 * @since 0.2.0
	 */
	public function primary_nav_menu_wrapper( $args = array() ) {

		if ( $args['theme_location'] == WPGO_THEME_NAME_H . '-theme-primary' ) {
			$args['items_wrap'] = '<label for="respond" class="respond" onclick></label><ul id="%1$s" class="%2$s">%3$s</ul>';
		}

		return $args;
	}

	/**
	 * Add a <label> tag just before the primary menu for responsive behaviour.
	 *
	 * @since 0.2.0
	 */
	public function custom_search_form( $form ) {

		$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
				<label>
					<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', 'wpgothemes' ) . '" value="' . get_search_query() . '" name="s" />
				</label>
				<input type="submit" class="search-submit" value="' . esc_attr_x( 'Search', 'submit button', 'wpgothemes' ) . '" />
			</form>';

		$form = '<div class="search">
                    <form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '" >
                        <input type="search" placeholder="' . __( 'Search...', 'wpgo-ws-plugin' ) . '" value="' . get_search_query() . '" name="s">
                        <input type="submit" class="search-submit" value="' . esc_attr__( 'Search', 'wpgothemes' ) . '">
                    </form>
                </div>';

		return $form;
	}

	public function theme_activated() {

		if ( current_user_can('edit_theme_options') ) {

			/* Get rid of the default WordPress notice upon theme activation. */
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					$('#message2').css('display', 'none');
				});
			</script>

			<?php

			$theme_options_url = 'themes.php?page='.WPGO_THEME_MENU_SLUG;
			$header = 'Congratulations, '.WPGO_THEME_NAME.' successfully activated!';
			$message = 'Why not start by taking a look at the '.WPGO_THEME_NAME.' support page.';
			$buttons = '<span><a style="text-decoration:none;" class="button" href="'.admin_url( $theme_options_url ).'">'.WPGO_THEME_NAME.' Support</a></span>';
			?>
			<div class="updated" style="margin-top: 10px;padding-bottom:10px;">
				<?php echo '<h3 style="margin: 0.7em 0;padding-top: 5px;">'.$header.'</h3>'.$message.'<br /><br />'.$buttons; ?>
			</div>
			<?php
		}
	}
} /* End of class definition */