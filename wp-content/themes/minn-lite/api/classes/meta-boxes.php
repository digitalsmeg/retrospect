<?php

/**
 * Framework meta boxes class.
 *
 * @since 0.1.0
 */
class WPGo_MetaBoxes {

	/**
	 * WPGo_MetaBoxes class constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		add_action( 'admin_init', array( &$this, 'theme_meta_box_init' ) );
	}

	/**
	 * Meta box functions for adding the meta box and saving the data.
	 *
	 * @since 0.1.0
	 */
	public function theme_meta_box_init() {

		$options = get_option( WPGO_OPTIONS_DB_NAME );

		/* Add Display Options meta box to Post/Page editor. */
		add_meta_box( 'wpgo-post-display-options-meta', __( 'Post Display Options', 'wpgothemes' ), array( &$this, 'theme_meta_box_display_options' ), 'post', 'side', 'default', array( 'type' => 'post' ) );
		add_meta_box( 'wpgo-page-display-options-meta', __( 'Page Display Options', 'wpgothemes' ), array( &$this, 'theme_meta_box_display_options' ), 'page', 'side', 'default', array( 'type' => 'page' ) );

		/* Hook to save our meta box data when the post is saved. */
		add_action( 'save_post', array( &$this, 'theme_display_options_save_meta_box' ) );
	}

	/**
	 * Display the column layout meta box on post/page editor.
	 *
	 * @since 0.1.0
	 */
	public function theme_meta_box_display_options( $post, $args ) {

		/* Retrieve our custom meta box values. */
		$wpgo_column_layout            = get_post_meta( $post->ID, '_wpgo_column_layout', true );
		$wpgo_theme_column_layout_save = get_post_meta( $post->ID, '_wpgo_column_layout_save', true );
		$hide_title_header_tag         = get_post_meta( $post->ID, '_wpgo_hide_title_header_tag', true );
		?>

		<div class="inside">
			<p><strong><?php _e( 'Column layout', 'wpgo-cpt-plugin' ); ?></strong></p>
			<label class="screen-reader-text" for="wpgo_column_layout"><?php _e( 'Column layout', 'wpgo-cpt-plugin' ); ?></label>

			<p>
				<select name='wpgo_column_layout' class='widefat'>
					<option value='default' <?php selected( 'default', $wpgo_column_layout ); ?>><?php _e( '(Default theme setting)', 'wpgothemes' ); ?></option>
					<option value='1-col' <?php selected( '1-col', $wpgo_column_layout ); ?>><?php _e( '1-Column (full width)', 'wpgothemes' ); ?></option>
					<option value='2-col-l' <?php selected( '2-col-l', $wpgo_column_layout ); ?>><?php _e( '2-Column Sidebar Left', 'wpgothemes' ); ?></option>
					<option value='2-col-r' <?php selected( '2-col-r', $wpgo_column_layout ); ?>><?php _e( '2-Column Sidebar Right', 'wpgothemes' ); ?></option>
					<option value='3-col-l' <?php selected( '3-col-l', $wpgo_column_layout ); ?>><?php _e( '3-Column Sidebars Left', 'wpgothemes' ); ?></option>
					<option value='3-col-r' <?php selected( '3-col-r', $wpgo_column_layout ); ?>><?php _e( '3-Column Sidebars Right', 'wpgothemes' ); ?></option>
					<option value='3-col-c' <?php selected( '3-col-c', $wpgo_column_layout ); ?>><?php _e( '3-Column Content Center', 'wpgothemes' ); ?></option>
				</select>
				<input type="hidden" name="wpgo_column_layout_save" id="wpgo_theme_column_layout_save" value="<?php echo esc_attr( $wpgo_theme_column_layout_save ); ?>">
			</p>
		</div>
		<?php

		$page_type = $args['args']['type'];

		/* Only show the hide title checkbox on supported post types. */
		if ( $page_type == 'page' || $page_type == 'post' ) {
			?>
			<div class="inside">
				<!-- Hide post title checkbox -->
				<label for="hide_title_header_tag" class="selectit" style="vertical-align:top;">
					<input id="hide_title_header_tag" name="wpgo_hide_title_header_tag" type="checkbox" value="1" <?php if ( isset( $hide_title_header_tag ) ) {
						checked( '1', $hide_title_header_tag );
					} ?> />
					Hide single <?php echo ucfirst( $page_type ); ?> title
				</label>
			</div>
		<?php
		}

	}

	/**
	 * Saves the column layout meta box settings.
	 *
	 * @since 0.1.0
	 */
	public function theme_display_options_save_meta_box( $post_id ) {

		/* The 'save_post' action hook seems to be triggered when adding new posts/pages so check for an empty $_POST array. */
		if ( empty( $_POST ) ) {
			return;
		}

		global $typenow;

		/* Just return if we're not on a post, page. */
		if ( $typenow != 'post' && $typenow != 'page' ) {
			return;
		}

		/* Process form data if $_POST is set */
		/* Save the meta box data as post meta, using the post ID as a unique prefix */

		if ( isset( $_POST['wpgo_column_layout_save'] ) ) {
			update_post_meta( $post_id, '_wpgo_column_layout_save', esc_attr( $_POST['wpgo_column_layout_save'] ) );
		}

		if ( isset( $_POST['wpgo_column_layout'] ) ) {
			update_post_meta( $post_id, '_wpgo_column_layout', esc_attr( $_POST['wpgo_column_layout'] ) );
		}

		if ( isset( $_POST['wpgo_hide_title_header_tag'] ) ) {
			update_post_meta( $post_id, '_wpgo_hide_title_header_tag', esc_attr( $_POST['wpgo_hide_title_header_tag'] ) );
		}

		// For a checkbox a further test is needed when it has been unchecked (but previously checked) as the $_POST variable won't be set if unchecked.
		$meta_hide_title_header_tag = get_post_meta( $post_id, '_wpgo_hide_title_header_tag', true );
		if ( ! isset( $_POST['wpgo_hide_title_header_tag'] ) && $meta_hide_title_header_tag == 1 ) {
			update_post_meta( $post_id, '_wpgo_hide_title_header_tag', '' );
		}
	}

}