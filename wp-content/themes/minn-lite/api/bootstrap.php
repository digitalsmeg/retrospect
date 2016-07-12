<?php
/**
 * Bootstrap file to load all classes used by the framework, as well theme specific classes.
 *
 * @since 0.1.0
 */

/* Core theme framework class dependencies. */
require_once( get_template_directory() . '/api/classes/deprecated.php' );
require_once( get_template_directory() . '/api/classes/meta-boxes.php' );
require_once( get_template_directory() . '/api/classes/theme-options.php' );
require_once( get_template_directory() . '/api/classes/utility-callbacks.php' );
require_once( get_template_directory() . '/api/classes/widget-admin.php' );

/* Static classes. */
require_once( get_template_directory() . '/api/classes/hooks.php' );
require_once( get_template_directory() . '/api/classes/template-parts.php' );
require_once( get_template_directory() . '/api/classes/utility.php' );

/* Customizer classes. */
require_once( get_template_directory() . '/api/classes/theme-customizer.php' );
require_once( get_template_directory() . '/api/classes/customizer/customize-footer-links.php' );
require_once( get_template_directory() . '/api/classes/customizer/customize-theme-colors.php' );
require_once( get_template_directory() . '/api/classes/customizer/customize-column-layout.php' );
require_once( get_template_directory() . '/api/classes/customizer/customize-site-title-and-tagline.php' );