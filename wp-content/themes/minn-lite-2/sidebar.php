<?php global $wpgo_is_front_page, $wpgo_home_page, $wpgo_show_on_front, $wpgo_page_on_front, $wpgo_post_id, $wpgo_template, $wp_registered_sidebars; ?>

<aside id="primary-sidebar" class="sidebar-container" role="complementary">

	<?php

	/* If the page is singular we'll have a valid post/page ID to check for custom widget areas. */
	$primary_custom_widget_areas = is_singular() ? get_post_meta( $wpgo_post_id, '_wpgo_sm_primary_sort', true ) : null;

	/* Update custom widget array. For any custom widget area specified in post meta not found then delete post meta reference to it. */
	$primary_custom_widget_areas = WPGo_Utility::check_cwa_exist( $primary_custom_widget_areas, $wpgo_post_id, '_wpgo_sm_primary_sort' );

	/* FRONT PAGE SIDEBARS. */
	if ( ( $wpgo_is_front_page && $wpgo_show_on_front == 'posts' ) || ( $wpgo_home_page && $wpgo_page_on_front == 0 ) ) {
		/* If 'Your latest posts' OR 'A static page' set in Settings -> Reading (and 'Front page' drop down blank) show default post widget area. */
		WPGo_Utility::render_widget_area( 'primary-post-widget-area', true, true );
	} /* ARCHIVE PAGE SIDEBARS. */
	elseif ( is_archive() ) {
		/* Check for specific archive pages via filter hook. */
		WPGo_Utility::custom_widget_area_loop( 'primary-archive' );
	} /* SINGULAR PAGE SIDEBARS. */
	elseif ( is_singular() ) {
		if ( ! empty( $primary_custom_widget_areas ) ) {
			WPGo_Utility::render_custom_widget_areas( $primary_custom_widget_areas );
		} elseif ( is_single() ) {
			/* Check for custom posts type pages via filter hook. */
			WPGo_Utility::custom_widget_area_loop( 'primary-posts', 'primary-post-widget-area', 'primary_post_generic_default_widgets.php', true );
		} elseif ( is_page() ) {
			/* Check for custom pages via filter hook. */
			WPGo_Utility::custom_widget_area_loop( 'primary-pages', 'primary-page-widget-area', 'primary_page_generic_default_widgets.php', true );
		}
	} /* CATCH-ALL PAGE. */
	else {
		/* Catch all case. Show primary post widget area. */
		WPGo_Utility::render_widget_area( 'primary-post-widget-area', true, true );
	}

	?>

</aside><!-- .sidebar-container -->