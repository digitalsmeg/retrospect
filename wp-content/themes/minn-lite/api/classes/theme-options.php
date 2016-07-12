<?php

/**
 * Theme options class.
 *
 * Handles all the functionality for theme options.
 *
 * @since 0.1.0
 */
class WPGo_Theme_Options {

	/* Handle to the theme options page */
	protected $_theme_options_page;

	/**
	 * Theme options class constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		$this->default_theme_options();

		add_action( 'admin_bar_menu', array( &$this, 'add_wp_toolbar_theme_options_link' ) );
		add_action( 'admin_init', array( &$this, 'register_theme_settings' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'theme_admin_init' ) );
		add_action( 'admin_menu', array( &$this, 'theme_options_page_init' ) );
	}

	/**
	 * Register theme options with Settings API.
	 *
	 * @since 0.1.0
	 */
	public function register_theme_settings() {

		/* Register theme options settings. */
		register_setting( WPGO_THEME_OPTIONS_GROUP, WPGO_OPTIONS_DB_NAME, array( $this, 'sanitize_theme_options' ) );

		/* Register theme options section, to add individual fields. */
		add_settings_section( 'wpgo_default', '', '__return_false', WPGO_THEME_MENU_SLUG );

		/* Register theme support fields. */
		add_settings_field(
			'wpgo_support_theme_option',
			__( 'Support and Tutorials', 'wpgothemes' ),
			array( $this, 'render_support_fields' ),
			WPGO_THEME_MENU_SLUG,
			'wpgo_default'
		);

		/* Register theme support fields. */
		add_settings_field(
			'wpgo_newsletter_theme_option',
			__( 'Latest News & Updates', 'wpgothemes' ),
			array( $this, 'render_newsletter_fields' ),
			WPGO_THEME_MENU_SLUG,
			'wpgo_default'
		);

		/* Register theme support fields. */
		add_settings_field(
			'wpgo_offer_theme_option',
			__( 'Upgrade Offer', 'wpgothemes' ),
			array( $this, 'render_offer_fields' ),
			WPGO_THEME_MENU_SLUG,
			'wpgo_default'
		);
	}

	/**
	 * Sanitize theme options.
	 *
	 * Get rid of the local license key status option when adding a new one
	 *
	 * @since 0.1.0
	 */
	public function sanitize_theme_options( $input ) {

		/* Sanitize theme options via this filter hook. */
		return WPGo_Hooks::wpgo_sanitize_theme_options( $input );
	}

	/**
	 * Render support fields.
	 *
	 * @since 0.1.0
	 */
	public function render_support_fields() {
		?>
		<div class="wpgo-buttons">
			<a class="button-secondary wpgo-lower" href="http://wpgothemes.com/free-theme-setup/" target="_blank">Theme Setup Tutorial</a>
			<a class="button-secondary wpgo-lower" href="http://wpgothemes.com/free-theme-support/" target="_blank">Free Theme Support</a>
			<span><a href="https://twitter.com/dgwyer" target="_blank" title="Come join me on Twitter and say hello!"><i class="wpgo-dashicon-font wpgo-twitter-button"></i></a></span>
		</div>
	<?php
	}

	/**
	 * Render newsletter fields.
	 *
	 * @since 0.1.0
	 */
	public function render_newsletter_fields() {
		?>
		<div class="wpgo-buttons">
			<p class="wpgo-newsletter-button"><a class="button-secondary wpgo-lower" href="http://eepurl.com/YApFP" target="_blank">Subscribe Now!</a></p>
			<p class="description">Signup to our newsletter to get the latest news and updates from WPGO themes.</p>
		</div>
	<?php
	}

	/**
	 * Render offer fields.
	 *
	 * @since 0.1.0
	 */
	public function render_offer_fields() {
		?>
		<p><a href="http://wpgothemes.com/<?php echo WPGO_THEME_NAME_H; ?>-offer/" target="_blank"><img class="wpgo-upgrade-image" src="<?php echo WPGO_THEME_ROOT_URI; ?>/images/sale30off-theme.png" /></a></p>
		<div class="wpgo-buttons mg">
			<p class="description">Exclusive offer for our free theme users. Upgrade <?php echo WPGO_THEME_NAME; ?> to the full version <strong>with a 30% discount!</strong></p>
			<p class="wpgo-upgrade-button"><a class="button-secondary wpgo-lower" href="http://wpgothemes.com/<?php echo WPGO_THEME_NAME_H; ?>-offer/" target="_blank"><strong>UPGRADE NOW - <u>30% OFF</u></strong></a>&nbsp;&nbsp;<a class="button-secondary wpgo-lower" href="<?php echo WPGO_PARENT_THEME_DEMO_URL; ?>" target="_blank"><?php echo WPGO_PARENT_THEME_DEMO_LABEL; ?></a></p>
		</div>
	<?php
	}

	/**
	 * Display theme options page.
	 *
	 * @since 0.1.0
	 */
	public function render_theme_form() {
		?>
		<div class="wrap">
			<h2><?php printf( __( '%s Theme Support', 'wpgothemes' ), WPGO_THEME_NAME ); ?></h2>

			<?php
			// Check to see if user clicked on the reset options button
			if ( isset( $_POST['reset_options'] ) ) {
				// Access theme defaults
				global $wpgo_default_options;

				// Reset theme defaults
				update_option( WPGO_OPTIONS_DB_NAME, $wpgo_default_options );

				// Display update notice here
				?>
				<div class="error">
				<p><?php printf( __( '%s theme options have been reset!', 'wpgothemes' ), WPGO_THEME_NAME ); ?></p>
				</div><?php
				$this->wpgo_fadeout_element( '.error' ); // fadeout .updated class
			}

			// Display update notice if theme options reset
			if ( isset( $_GET['settings-updated'] ) && ! isset( $_POST['reset_options'] ) ) {
				?>
				<div class="updated">
				<p><?php printf( __( '%s theme options updated!', 'wpgothemes' ), WPGO_THEME_NAME ); ?></p></div><?php
				$this->wpgo_fadeout_element(); // fadeout .updated class
			}
			?>

			<!-- Start Main Form -->
			<form method="post" action="options.php">
				<?php
				settings_fields( WPGO_THEME_OPTIONS_GROUP );
				do_settings_sections( WPGO_THEME_MENU_SLUG );
				submit_button();
				?>
			</form>
			<!-- main form closing tag -->

			<form action="<?php echo WPGo_Utility::currURL(); // current page url ?>" method="post" id="wpgo-theme-options-reset" style="display:inline;">
				<span class="submit-theme_options-reset">
					<input type="submit" onclick="return confirm('Are you sure? All theme options will be reset to their default settings!');" value="Reset <?php echo WPGO_THEME_NAME; ?> Options" name="wpgo_reset" class="wpgo-reset-link">
					<input type="hidden" name="reset_options" value="true">
				</span>
			</form>
		</div><!-- .wrap -->
	<?php
	}

	/**
	 * Register admin scripts and styles, ready for enqueueing on the theme options page
	 *
	 * @since 0.1.0
	 */
	public function theme_admin_init() {

		// Register theme option style sheets
		wp_register_style( 'theme_admin_stylesheet', WPGO_THEME_ROOT_URI . '/api/css/wpgo-theme-admin.css' );
	}

	/**
	 * Register theme options page, and enqueue scripts/styles.
	 *
	 * @since 0.1.0
	 */
	public function theme_options_page_init() {

		$this->_theme_options_page = add_theme_page(
			WPGO_THEME_NAME . " Support Page",
			WPGO_THEME_NAME . ' Support',
			'edit_theme_options',
			WPGO_THEME_MENU_SLUG,
			array( &$this, 'render_theme_form' )
		);

		/* Enqueue scripts and styles for the theme option page */
		add_action( "admin_print_styles-$this->_theme_options_page", array( &$this, 'theme_admin_styles' ) );
		add_action( "admin_print_scripts-$this->_theme_options_page", array( &$this, 'theme_admin_scripts' ) );
	}

	/**
	 * Enqueue scripts for options page.
	 *
	 * @since 0.1.0
	 */
	public function theme_admin_scripts() {

		/* Scripts for theme options page only. */
	}

	/**
	 * Enqueue styles for theme options page.
	 *
	 * @since 0.1.0
	 */
	public function theme_admin_styles() {

		/* Styles for theme options page only. */
		wp_enqueue_style( 'theme_admin_stylesheet' );
		wp_enqueue_style( 'jquery-ui-base-css' );
	}

	/**
	 * Set default theme options.
	 *
	 * @since 0.1.0
	 */
	public function default_theme_options() {

		/* Define as global to accessible anywhere (i.e. from within hook callbacks). */
		global $wpgo_default_options, $wpgo_default_off_checkboxes;

		/* Initialize to empty arrays. */
		$wpgo_default_options        = array();
		$wpgo_default_off_checkboxes = array();

		/* Load theme option defaults.
		 *
		 * The priority is set to 14 so all the framework add_action calls fire BEFORE do_action calls.
		 * Otherwise framework hooks won't work if this order isn't observed.
		 *
		*/
		add_action( 'after_setup_theme', array( &$this, 'add_theme_option_defaults' ), 14 );
	}

	/**
	 * Hook to allow further theme option defaults to be defined via other framework locations or a Plugin.
	 *
	 * @since 0.1.0
	 */
	public function add_theme_option_defaults() {

		global $wpgo_default_options, $wpgo_default_off_checkboxes;

		/* Add theme specific default settings via this hook. */
		WPGo_Hooks::wpgo_theme_option_defaults();

		$options = get_option( WPGO_OPTIONS_DB_NAME );

		/* Added this here rather inside the same 'if' statement above so we can add extra $wpgo_default_off_checkboxes via a hook. */
		if ( is_array( $options ) ) {
			/* Manually set the checkboxes that have been unchecked, by the user, to zero. */
			$options = array_merge( $wpgo_default_off_checkboxes, $options );
		}

		/* If there are no existing options just use defaults (no merge). */
		if ( ! $options || empty( $options ) ) {
			// Update options in db
			update_option( WPGO_OPTIONS_DB_NAME, $wpgo_default_options );
		} /* Else merge existing options with current ones (new options are added, but none are overwritten). */
		else {
			/* Merge current options with the defaults, i.e. add any new options but don't overwrite existing ones. */
			$result = array_merge( $wpgo_default_options, $options );

			/* Update options in db. */
			update_option( WPGO_OPTIONS_DB_NAME, $result );
		}
	}

	/**
	 * Fade out theme update notices to hide them without having to reload the page.
	 *
	 * @since 0.1.0
	 */
	public function wpgo_fadeout_element( $element = ".updated", $delay = 3000, $fadeout = 1500 ) {
		?>
		<script>
			jQuery(document).ready(function ($) {
				$("<?php echo $element; ?>").delay(<?php echo $delay; ?>).fadeOut(<?php echo $fadeout; ?>);
			});
		</script>
	<?php
	}

	/**
	 * Add link to theme options page on the (front end) WordPress toolbar.
	 *
	 * @since 0.1.0
	 */
	public function add_wp_toolbar_theme_options_link( $wp_admin_bar ) {

		global $wp_admin_bar;
		if ( ! is_super_admin() || ! is_admin_bar_showing() )
			return;

		$href = get_admin_url() . 'themes.php?page=' . WPGO_THEME_MENU_SLUG;

		$args = array( 'parent' => 'appearance',
					   'id'     => 'wpgo-theme-options',
					   'title'  => sprintf( __( '%s Support', 'wpgothemes' ), WPGO_THEME_NAME ),
					   'href'   => $href
		);
		$wp_admin_bar->add_node( $args );
	}
}