<?php global $wpgo_is_front_page, $wpgo_home_page, $wpgo_show_on_front, $wpgo_page_on_front, $wpgo_post_id, $wpgo_template, $wp_registered_sidebars; ?>

<aside id="secondary-sidebar" class="sidebar-container" role="complementary">

	<?php
	/* If the page is singular we'll have a valid post/page ID to check for custom widget areas. */
	$secondary_custom_widget_areas = is_singular() ? get_post_meta( $wpgo_post_id, '_wpgo_sm_secondary_sort', true ) : null;

	/* Update custom widget array. For any custom widget area specified in post meta not found then delete post meta reference to it. */
	$secondary_custom_widget_areas = WPGo_Utility::check_cwa_exist( $secondary_custom_widget_areas, $wpgo_post_id, '_wpgo_sm_secondary_sort' );

	/* FRONT PAGE SIDEBARS. */
	if ( ( $wpgo_is_front_page && $wpgo_show_on_front == 'posts' ) || ( $wpgo_home_page && $wpgo_page_on_front == 0 ) ) {
		/* If 'Your latest posts' OR 'A static page'  set in Settings -> Reading (and 'Front page' drop down blank) show default post widget area. */
		WPGo_Utility::render_widget_area( 'secondary-post-widget-area', true, false );
	} /* ARCHIVE PAGE SIDEBARS. */
	elseif ( is_archive() ) {
		WPGo_Utility::render_widget_area( 'secondary-post-widget-area', true, false );
	} /* SINGULAR PAGE SIDEBARS. */
	elseif ( is_singular() ) {
		if ( ! empty( $secondary_custom_widget_areas ) ) {
			WPGo_Utility::render_custom_widget_areas( $secondary_custom_widget_areas );
		} elseif ( is_single() ) {
			WPGo_Utility::render_widget_area( 'secondary-post-widget-area', true, false );
		} elseif ( is_page() ) {
			WPGo_Utility::render_widget_area( 'secondary-page-widget-area', true, false );
		}
	} /* CATCH-ALL PAGE. */
	else {
		/* Catch all case. Show secondary post widget area. */
		WPGo_Utility::render_widget_area( 'secondary-post-widget-area', true, false );
	}
	?>

</aside><!-- .sidebar-container -->