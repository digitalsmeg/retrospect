<?php

/**
 * Main framework hooks class.
 *
 * Hooks that are specific to shortcodes, widgets, and CPT are defined in other classes.
 *
 * @since 0.1.0
 */
class WPGo_Hooks {

	/**
	 * WPGo_Hooks class constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

	}

	/**
	 * Our version of the wp_head hook, but is placed directly after so guaranteed to run after wp_head hooked content.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_head() {
		do_action( 'wpgo_head' );
	}

	/**
	 * Fires inside the <head> tag, but before wpgo_head().
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_head_top() {
		do_action( 'wpgo_head_top' );
	}

	/**
	 * Fires direclty before the opening <head> tag.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_before_head() {
		do_action( 'wpgo_before_head' );
	}

	/**
	 * Fires directly after the closing <header id="header-container"></header> tag.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_after_header_close() {
		do_action( 'wpgo_after_header_close' );
	}

	/**
	 * Fires directly after the opening <footer> tag.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_after_opening_footer_tag() {
		do_action( 'wpgo_after_opening_footer_tag' );
	}

	/**
	 * Fires directly before the closing </footer> tag and after the footer <section></section> tag if defined in the theme.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_before_closing_footer_tag() {
		do_action( 'wpgo_before_closing_footer_tag' );
	}

	/**
	 * Fires directly after the closing </footer> (and #body-container </div> tag)
	 * but directly before the wp_footer() hook.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_after_closing_footer_tag() {
		do_action( 'wpgo_after_closing_footer_tag' );
	}

	/**
	 * Fires directly before the opening <main class="content"> tag.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_before_content_open() {
		do_action( 'wpgo_before_content_open' );
	}

	/**
	 * Fires directly after the opening <main class="content"> tag.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_after_content_open() {
		do_action( 'wpgo_after_content_open' );
	}

	/**
	 * Fires directly after the opening <div id="outer-container"> tag.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_after_outer_container_open() {
		do_action( 'wpgo_after_outer_container_open' );
	}

	/**
	 * Fires directly after the closing </section> tag.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_after_content_close() {
		do_action( 'wpgo_after_content_close' );
	}

	/**
	 * Fires directly after the opening <div class="post-meta"> tag inside the post header.
	 *
	 * @since 0.2.0
	 */
	public static function wpgo_post_meta_header() {
		do_action( 'wpgo_post_meta_header' );
	}

	/**
	 * Fires directly after the opening <div class="post-meta"> tag inside the post archive header.
	 *
	 * @since 0.2.0
	 */
	public static function wpgo_post_archive_meta_header() {
		do_action( 'wpgo_post_archive_meta_header' );
	}

	/**
	 * Fires directly after the opening <div class="post-meta"> tag inside the post footer.
	 *
	 * @since 0.2.0
	 */
	public static function wpgo_post_meta_footer() {
		do_action( 'wpgo_post_meta_footer' );
	}

	/**
	 * Fires directly after the opening <div class="post-meta"> tag inside the post archive footer.
	 *
	 * @since 0.2.0
	 */
	public static function wpgo_post_archive_meta_footer() {
		do_action( 'wpgo_post_archive_meta_footer' );
	}

	/**
	 * Fires directly before the opening <div class="post-content"> tag on all single posts including CPT.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_before_post_content() {
		do_action( 'wpgo_before_post_content' );
	}

	/**
	 * Fires directly before the opening <div class="post-content"> tag on all post archives including CPT archives.
	 *
	 * @since 0.2.0
	 */
	public static function wpgo_before_post_archive_content() {
		do_action( 'wpgo_before_post_archive_content' );
	}

	/**
	 * Fires in theme_options_form.php in the header options section.
	 *
	 * Use it to quickly add theme options that are just a single form field. i.e. check box etc.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_set_header_theme_option_fields() {
		do_action( 'wpgo_set_header_theme_option_fields' );
	}

	/**
	 * Fires in theme-options.php after the theme default settings array has been defined.
	 *
	 * Use it to add custom default theme option settings. i.e. for theme specific options.
	 *
	 * @since 0.2.0
	 */
	public static function wpgo_theme_option_defaults() {
		do_action( 'wpgo_theme_option_defaults' );
	}

	/**
	 * Fires in theme-options.php sanitize_theme_options() function.
	 *
	 * Use it to sanitize theme options via a custom callback function. This is useful because theme options can be extended
	 * and these added newly added options can be easily sanitized via this hook.
	 *
	 * @since 0.2.0
	 */
	public static function wpgo_sanitize_theme_options($input) {
		return apply_filters( 'wpgo_sanitize_theme_options', $input );
	}

	/**
	 * Fires in theme-customizer.php after the theme customizer default settings array has been defined.
	 *
	 * Use it to add customizer default settings for specific controls.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_theme_customizer_defaults() {
		do_action( 'wpgo_theme_customizer_defaults' );
	}

	/**
	 * Fires in customize-theme-colors.php in the define_color_schemes() function.
	 *
	 * Use it to filter theme color schemes.
	 *
	 * @since 0.2.0
	 */
	public static function wpgo_color_scheme_filter( $wpgo_color_schemes ) {
		return apply_filters( 'wpgo_color_scheme_filter', $wpgo_color_schemes );
	}

	/**
	 * This filter hook allows you to add custom primary sidebars for archive pages.
	 *
	 * For example if you have a CPT archive page then you can use this filter to specify a
	 * custom sidebar for that page. Otherwise the default post loop will be used.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_custom_primary_sidebar_archive( $custom_archive_pages ) {
		return apply_filters( 'wpgo_custom_primary_sidebar_archive', $custom_archive_pages );
	}

	/**
	 * This filter hook allows you to add custom primary sidebars for archive custom post types.
	 *
	 * For example if you have a CPT defined then you can use this filter to specify a custom
	 * sidebar for that CPT.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_custom_primary_sidebar_posts( $custom_theme_posts ) {
		return apply_filters( 'wpgo_custom_primary_sidebar_posts', $custom_theme_posts );
	}

	/**
	 * This filter hook allows you to add custom primary sidebars for theme page templates.
	 *
	 * For example if you use specific theme page templates from theme to theme you can use
	 * this hook to easily add a custom sidebar for those page templates.
	 *
	 * @since 0.1.0
	 */
	public static function wpgo_custom_primary_sidebar_pages( $custom_theme_pages ) {
		return apply_filters( 'wpgo_custom_primary_sidebar_pages', $custom_theme_pages );
	}

	/**
	 * Allows you to filter the empty title placeholder in WPGo_Utility::hide_title_header_tag()
	 *
	 * @since 0.2.0
	 */
	public static function wpgo_empty_title_placeholder( $title_placeholder ) {
		return apply_filters( 'wpgo_empty_title_placeholder', $title_placeholder );
	}
}