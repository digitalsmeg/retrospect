<?php

/**
 * Customize Theme Colors Class
 *
 * Manages theme colors and color schemes via the theme customizer.
 *
 * @since 0.2.0
 *
 */
class WPGo_Customize_Theme_Colors {

	/**
	 * Color schemes.
	 *
	 * @since 0.2.0
	 */
	protected $_wpgo_color_schemes = array();

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
	 * Setup class.
	 *
	 * @since 0.2.0
	 */
	public function setup_default_features() {

		/* Enable support for color schemes in current theme. Can easily be removed via parent/child theme. */
		if ( ! current_theme_supports( 'wpgo-color-schemes' ) ) {
			add_theme_support( 'wpgo-color-schemes' );
		}
	}

	/**
	 * Check current theme supports WPGo color schemes.
	 *
	 * @since 0.2.0
	 */
	public function customizer_supported_features() {

		if ( current_theme_supports( 'wpgo-color-schemes' ) ) {
			$this->define_color_pickers(); // define color picker array
			$this->define_color_schemes(); // define color scheme array
			$this->init_color_picker_db_values(); // initialize color picker db values if none exist

			/* Add color schemes to the theme. */
			add_action( 'wpgo_theme_customizer_defaults', array( &$this, 'customizer_color_scheme_defaults' ) );
			add_action( 'customize_register', array( &$this, 'theme_customizer_register_color_schemes' ) );
			add_action( 'customize_register', array( &$this, 'theme_customizer_register_color_pickers' ) );
			add_action( 'wp_head', array( $this, 'add_theme_customizer_colors' ) );
			//add_action( 'customize_save_after', array( &$this, 'customize_save_after') );
			//add_action( 'customize_controls_print_footer_scripts', array( &$this, 'refresh_preview_iframe') );
			add_action( 'customize_controls_print_footer_scripts', array( $this, 'real_time_customize_preview' ) );
		}
	}

	/**
	 * Refresh the customizer preview window after 'Save & Publish' clicked.
	 *
	 * Only do this for a successful save operation.
	 *
	 * @since 0.2.0
	 */
	public function refresh_preview_iframe() {
		?>
		<script defer="defer">
			jQuery(document).ready(function ($) {

				/* Refresh the customizer preview iframe after 'Save & Publish' button clicked */
				$(document).ajaxSuccess(function (event, XMLHttpRequest, ajaxOptions) {

					var request = {}, pairs = ajaxOptions.data.split('&'), i, split;

					for (i in pairs) {
						split = pairs[i].split('=');
						request[decodeURIComponent(split[0])] = decodeURIComponent(split[1]);
					}

					if (request.action && (request.action === 'customize_save')) {
						wp.customize.instance('<?php echo WPGO_THEME_NAME_U; ?>_customize_options[wpgo-links-hover-color]').previewer.refresh();
					}
				});
			});
		</script>
	<?php
	}

	/**
	 * Update color pickers when color scheme drop down changed.
	 *
	 * @since 0.2.0
	 */
	public function real_time_customize_preview() {

		/* Custom background flag set depending on if built-in custom background feature is enabled. */
		$supports_custom_bg = current_theme_supports( 'custom-background' ) ? '1' : '0';

		?>
		<script defer="defer">
			jQuery(window).load(function () {
				jQuery('#customize-control-<?php echo WPGO_THEME_NAME_U; ?>_customize_options-wpgo_drp_color_scheme').change(function (e) {

					// custom background flag
					var supports_custom_bg = <?php echo $supports_custom_bg; ?>;

					// make $this->_wpgo_color_schemes array accessible in JavaScript
					var wpgo_color_schemes = <?php echo json_encode($this->_wpgo_color_schemes); ?>;

					// change color scheme to one selected in drop down
					var new_color_scheme = e.srcElement.value;

					//console.log( wpgo_color_schemes );

					// cycle through color schemes array sent to JavaScript
					for (var index in wpgo_color_schemes) {

						// check for the color scheme we want
						if (wpgo_color_schemes[index]['name'] == new_color_scheme) {

							//console.error( 'new color scheme: ' + new_color_scheme );

							// cycle through each color picker in color scheme
							for (var color_picker in wpgo_color_schemes[index]['default_colors']) {

								// Don't attempt to access the background color picker if not available
								if (color_picker == 'background_color' && supports_custom_bg == 0) continue;

								// Get new default color and color picker setting name
								var new_default_color = wpgo_color_schemes[index]['default_colors'][color_picker];

								if (color_picker == 'background_color')
									color_picker_name = color_picker; // use correct name for built-in WordPress background color picker
								else
									color_picker_name = '<?php echo WPGO_CUSTOMIZE_DB_NAME; ?>[' + color_picker + ']';

								//console.error('dc: ' + new_default_color + ', cp: ' + color_picker + ', cpn: ' + color_picker_name);
								//rcol = '#'+((1<<24)*(Math.random()+1)|0).toString(16).substr(1);

								// Get the color picker control instance
								var wpgo_control = wp.customize.control.instance(color_picker_name); // instance is customizer setting ID not CSS ID
								var wpgo_picker = wpgo_control.container.find('.color-picker-hex');

								//console.error( 'BEFORE > color: ' + wpgo_picker.wpColorPicker('color') + ', old dc: ' + wpgo_picker.wpColorPicker('defaultColor') + ', new dc: ' + new_default_color );

								// If color picker color is the same as the default color then update color picker to new color
								if (wpgo_picker.wpColorPicker('color') == wpgo_picker.wpColorPicker('defaultColor')) {
									//console.log('CHANGING');
									wpgo_control.setting.set(new_default_color); // set color in preview window
									wpgo_picker.wpColorPicker('color', new_default_color); // set color picker to same color as preview window
									//wpgo_picker.wpColorPicker( 'color', wpgo_control.setting() ); // set color picker to same color as preview window

									//wpgo_control.setting.set( rcol ); // set color in preview window
									//wpgo_picker.wpColorPicker( 'color', rcol ); // set color picker to same color as preview window
								}
								//else {
								//	wpgo_control.previewer.refresh();
								//}
								wpgo_picker.wpColorPicker('defaultColor', new_default_color); // always update color picker default color

								//console.error( 'AFTER > color: ' + wpgo_picker.wpColorPicker('color') + ', defaultColor: ' + wpgo_picker.wpColorPicker('defaultColor') );
							}
							break; // don't bother iterating through other color schemes
						}
					}
				});
				return;
			});
		</script>
	<?php
	}

	/**
	 * Check if color picker options are stored in the db. If not then add defaults.
	 *
	 * If no color picker options are stored in the db (i.e. when the theme is first activated) then the color
	 * picker defaults don't update the live preview window. This function initializes color picker db defaults.
	 *
	 * @since 0.2.0
	 */
	public function init_color_picker_db_values() {

	}

	/**
	 * Add the color scheme color picker defaults to the global customizer defaults array.
	 *
	 * @since 0.2.0
	 */
	public function customizer_color_scheme_defaults() {

		global $wpgo_customizer_defaults;

		$wpgo_customizer_defaults['wpgo_drp_color_scheme'] = 'default';

		foreach ( $this->_wpgo_color_schemes as $wpgo_color_scheme ) {
			if ( $wpgo_color_scheme['name'] == 'default' ) {
				foreach ( $wpgo_color_scheme['default_colors'] as $color_picker_name => $color ) {
					$wpgo_customizer_defaults[$color_picker_name] = $color;
				}
				break;
			}
		}
	}

	/**
	 * Add theme specific JS to the previewer frame footer to alter HTML elements.
	 *
	 * @since 0.2.0
	 */
	public function add_theme_specific_footer_js() {

		global $wpgo_color_pickers;

		?>
		<script defer="defer">

			(function ($) {

				<?php

				foreach($wpgo_color_pickers as $color_picker) :

					if(isset($color_picker['transport']) && $color_picker['transport']=='refresh')
						{continue;
} // don't add jQuery to bind elements for refresh

					foreach( $color_picker['css'] as $selector => $css_rule ) : ?>

				wp.customize('<?php echo WPGO_CUSTOMIZE_DB_NAME.'['.$color_picker['setting'].']'; ?>', function (value) {
					value.bind(function (to) {
						$('<?php echo $selector; ?>').css('<?php echo $css_rule; ?>', to);
					});
				});
				<?php

									endforeach;

								endforeach; ?>

			})(jQuery)
		</script>
	<?php
	}

	/**
	 * Define color picker array.
	 *
	 * @since 0.2.0
	 */
	public function define_color_pickers() {

		global $wpgo_color_pickers;

		/* Individual customizer color picker options. */
		$wpgo_color_pickers = array(
			array( 'setting'  => 'wpgo-text-color',
				   'label'    => __( 'Content Text Color', 'wpgothemes' ),
				   'css'      => array( '#container' => 'color' ),
				   'priority' => '24' ),
			array( 'setting'  => 'wpgo-links-color',
				   'label'    => __( 'Content Link Color', 'wpgothemes' ),
				   'css'      => array( '#container a, #container a:link, #container a:visited' => 'color' ),
				   'priority' => '25' )
		);
	}

	/**
	 * Define default color scheme array.
	 *
	 * @since 0.2.0
	 */
	public function define_color_schemes() {

		$default_colors = array(
			'wpgo-links-color'                    => '#333',
			'wpgo-text-color'                     => '#363636',
		);

		if ( current_theme_supports( 'custom-background' ) ) {
			$custom_bg_args = get_theme_support( 'custom-background' );
			if ( isset( $custom_bg_args[0]['default-color'] ) && ! empty( $custom_bg_args[0]['default-color'] ) ) {
				$default_colors['background_color'] = $custom_bg_args[0]['default-color'];
			} else {
				$default_colors['background_color'] = '#ffffff';
			} // default to white bg color if none set
		}

		/* Individual customizer color picker defaults. */
		$this->_wpgo_color_schemes = array(
			array(
				'label'          => __( 'Default', 'wpgothemes' ),
				'name'           => 'default',
				'default_colors' => $default_colors
			)
		);

		/* Add/remove color schemes via this filter. */
		$this->_wpgo_color_schemes = WPGo_Hooks::wpgo_color_scheme_filter( $this->_wpgo_color_schemes );

		/* Make sure all default hex codes contain lower case characters and are 6-digits in length. */
		$this->validate_color_scheme_hex_codes();
	}

	/**
	 * Add color pickers to theme customizer 'colors' section.
	 *
	 * @since 0.2.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 *
	 * @return void
	 */
	public function theme_customizer_register_color_pickers( $wp_customize ) {

		global $wpgo_customizer_defaults, $wpgo_color_pickers;

		/* Get color scheme from drop down box or use default. */
		$current_color_scheme = WPGo_Theme_Customizer::get_customizer_theme_option( 'wpgo_drp_color_scheme' );

		/* Get the default colors for the currently selected color scheme. */
		foreach ( $this->_wpgo_color_schemes as $wpgo_color_scheme ) {
			if ( $wpgo_color_scheme['name'] == $current_color_scheme ) {
				$color_picker_defaults = $wpgo_color_scheme['default_colors'];
				break;
			}
		}

		/* If no defined color scheme has been found to match the color scheme drop down value then use the core defaults. */
		if ( empty( $color_picker_defaults ) ) {

			/* Get the default colors for the currently selected color scheme. */
			foreach ( $this->_wpgo_color_schemes as $wpgo_color_scheme ) {

				// save default color scheme colors
				if ( $wpgo_color_scheme['name'] == 'default' ) {
					$color_picker_defaults = $wpgo_color_scheme['default_colors'];
					break;
				}
			}
			// update color scheme drop down option to default
			WPGo_Theme_Customizer::set_customizer_theme_option( 'wpgo_drp_color_scheme', 'default' );
		}

		/* Add default background color from color scheme array rather than value specified in functions.php. */
		if ( current_theme_supports( 'custom-background' ) ) {
			$wp_customize->get_setting( 'background_color' )->default = $color_picker_defaults['background_color'];
		}

		foreach ( $wpgo_color_pickers as $color_picker ) {

			$transport = ! isset( $color_picker['transport'] ) ? 'postMessage' : $color_picker['transport'];
			$setting   = WPGO_CUSTOMIZE_DB_NAME . '[' . $color_picker['setting'] . ']';

			// test if color picker has a specified value in the current color scheme, if so use it
			if ( isset( $color_picker_defaults[$color_picker['setting']] ) ) {
				$default = $color_picker_defaults[$color_picker['setting']]; // default color for a particular color scheme, and color picker
			} // otherwise use the default color value for that color picker from the default color scheme
			else {
				// check there actually is a default color specified for the color picker in the default color scheme
				if ( isset( $wpgo_customizer_defaults[$color_picker['setting']] ) ) {
					$default = $wpgo_customizer_defaults[$color_picker['setting']];
				}
				else {
					$default = '#000000';
				}
			}

			/* Add setting. */
			$wp_customize->add_setting( $setting, array(
				'transport'  => $transport,
				'default'    => $default,
				'type'       => 'option',
				//'sanitize_callback' => 'sanitize_hex_color',
				'capability' => 'edit_theme_options',
			) );

			/* Add control. */
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $setting, array(
				'label'    => $color_picker['label'],
				'section'  => 'colors',
				'settings' => $setting,
				'priority' => $color_picker['priority']
			) ) );
		}

		if ( $wp_customize->is_preview() && ! is_admin() ) {
			add_action( 'wp_footer', array( $this, 'add_theme_specific_footer_js' ), 21 );
		}
	}

	/**
	 * Add a drop down control to theme customizer 'colors' section to control color schemes.
	 *
	 * @since 0.2.0
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer object.
	 *
	 * @return void
	 */
	public function theme_customizer_register_color_schemes( $wp_customize ) {

		global $wpgo_customizer_defaults;

		if ( count( $this->_wpgo_color_schemes ) < 2 ) {
			return;
		} // only show drop down for 2 or more color schemes

		$col_scheme_array = array();
		foreach ( $this->_wpgo_color_schemes as $wpgo_color_scheme ) {
			$col_scheme_array[$wpgo_color_scheme['name']] = $wpgo_color_scheme['label'];
		}

		$wp_customize->add_setting( WPGO_CUSTOMIZE_DB_NAME . '[wpgo_drp_color_scheme]', array(
			'default'    => $wpgo_customizer_defaults['wpgo_drp_color_scheme'],
			'type'       => 'option',
			'transport'  => 'postMessage',
			'capability' => 'edit_theme_options'
		) );

		$wp_customize->add_control( WPGO_CUSTOMIZE_DB_NAME . '[wpgo_drp_color_scheme]', array(
				'label'    => __( 'Select Color Scheme', 'wpgothemes' ),
				'section'  => 'colors',
				'type'     => 'select',
				'priority' => 2,
				'choices'  => $col_scheme_array )
		);
	}

	/**
	 * Add theme specific theme customizer color styles via 'wp_head' action hook.
	 *
	 * @since 0.1.0
	 */
	public function add_theme_customizer_colors() {

		global $wpgo_color_pickers, $wpgo_customizer_defaults;

		echo '<!-- ' . WPGO_THEME_NAME . ' customizer styles -->';
		echo '<style type="text/css">';

		/* If theme supports 'custom-background' then use that bg color (if it exists), otherwise use color scheme bg. */
		$bg_default = WPGo_Theme_Customizer::get_customizer_theme_option( 'background_color' );
		if ( current_theme_supports( 'custom-background' ) ) {
			/* If no bg color exists in db then use default from color scheme array. */
			$bg = get_theme_mod( 'background_color' );
			if ( empty( $bg ) ) {
				echo 'body { background-color: ' . $bg_default . ';}';
			}
		} else {
			echo 'body { background-color: ' . $bg_default . ';}';
		}

		foreach ( $wpgo_color_pickers as $wpgo_color_picker ) {

			$col = WPGo_Theme_Customizer::get_customizer_theme_option( $wpgo_color_picker['setting'] );

			foreach ( $wpgo_color_picker['css'] as $css_selector => $css_rule ) {
				echo $css_selector . " {" . $css_rule . ": " . $col . ";}";
			}
		}

		echo "</style>";
		echo "\r\n";
	}

	/**
	 * Append color styles to customize.css.
	 *
	 * @since 0.2.0
	 */
	public function customize_save_after( $wp_customize ) {

		global $wpgo_color_pickers;

		ob_start(); // start recording output

		/* Get the values of the posted customizer controls. */
		$post_values = json_decode( wp_unslash( $_POST['customized'] ), true );

		/* Get posted array keys. */
		$post_values_keys = array_keys( $post_values );

		foreach ( $wpgo_color_pickers as $wpgo_color_picker ) {

			if ( in_array( WPGO_CUSTOMIZE_DB_NAME . '[' . $wpgo_color_picker['setting'] . ']', $post_values_keys ) ) {

				foreach ( $wpgo_color_picker['css'] as $css_selector => $css_rule ) {
					echo $css_selector . " {\r\n" . $css_rule . ": " . $post_values[WPGO_CUSTOMIZE_DB_NAME . '[' . $wpgo_color_picker['setting'] . ']'] . ";\r\n}\r\n\r\n";
				}
			}
		}

		$css = ob_get_contents(); // get output contents
		ob_end_clean(); // end recording output and flush buffer

		/* Update the customize.css version number when customizer options manually saved. */
		WPGo_Theme_Customizer::set_customize_css_version();

		/* Append CSS to customize.css. */
		WPGo_Theme_Customizer::write_customize_css( $css );
	}

	/**
	 * Validate color picker hex values.
	 *
	 * Only run on the customizer. If any color scheme hex value is only 3-digits or contains an uppercase character
	 * it can cause issues when changing color schemes. This function makes sure the hex value is 6-digits and contains
	 * only lowercase characters.
	 *
	 * @since 0.2.0
	 */
	public function validate_color_scheme_hex_codes() {

		/* Exit if not on the Customizer. */
		if ( ! WPGo_Utility::is_customizer() ) {
			return;
		}

		foreach ( $this->_wpgo_color_schemes as $cs_index => $color_scheme ) {
			foreach ( $color_scheme['default_colors'] as $col_index => $default_colors ) {

				$dc = $default_colors; // so we can change it's value
				if ( strlen( $dc ) == 4 ) {
					$dc = $dc[0] . $dc[1] . $dc[1] . $dc[2] . $dc[2] . $dc[3] . $dc[3];
				}
				$this->_wpgo_color_schemes[$cs_index]['default_colors'][$col_index] = strtolower( $dc );
			}
		}
	}
}