<?php
/**
 * Framework utility callbacks class.
 *
 * Contains general WordPress hook callback functions.
 *
 * @since 0.1.0
 */

class WPGo_Utility_Callbacks {

	/**
	 * WPGo_Utility callbacks class constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {

		add_filter( 'wp_page_menu_args', array( &$this, 'theme_page_menu_args' ) );
		add_filter( 'excerpt_more', array( &$this, 'custom_excerpt_more' ) );
		add_action( 'wpgo_before_head', array( &$this, 'wpgo_custom_before_head' ) );
		add_action( 'wpgo_head_top', array( &$this, 'wpgo_theme_title' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_open_sans_font' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_dashicons_font' ) );

		/* Priority set to 11 as it needs to run after all other 'wpgo_before_content_open' hook callbacks. */
		add_action( 'wpgo_before_content_open', array( &$this, 'sidebar_before_content' ), 11 );

		/* Priority set to 11 as it needs to run after all other 'wpgo_after_content_close' hook callbacks. */
		add_action( 'wpgo_after_content_close', array( &$this, 'sidebar_after_content' ), 11 );
	}

	/**
	 * Define a default homepage link for our wp_nav_menu() fallback, wp_page_menu().
	 *
	 * @since 0.1.0
	 */
	public function theme_page_menu_args( $args ) {
		$args['show_home'] = true;

		return $args;
	}

	/**
	 * Replace the [...] after the excerpt, if it exists.
	 *
	 * @since 0.1.0
	 */
	public function custom_excerpt_more( $more ) {
		return '&hellip;';
	}

	/**
	 * Adds HTML5 doctype and tags to the header.
	 *
	 * @since 0.1.0
	 */
	public function wpgo_custom_before_head() {
	?><!doctype html>
	<html <?php language_attributes(); ?>>
	<?php
	}

	/**
	 * Add main theme style sheet to header.
	 *
	 * @since 0.1.0
	 */
	public function enqueue_main_style_sheet() {

		/* Register and enqueue main theme style sheet. */
		wp_register_style( 'wpgo-theme', get_stylesheet_uri() );
		wp_enqueue_style( 'wpgo-theme' );
	}

	/**
	 * Before content sidebar rendering logic.
	 *
	 * Callback function for the 'wpgo_before_content_open' hook.
	 *
	 * @since 0.1.0
	 */
	public function sidebar_before_content() {

		global $wpgo_global_column_layout;

		if ( $wpgo_global_column_layout == "3-col-l" ) {
			get_sidebar(); // primary
			get_sidebar( 'secondary' );
		}

		if ( $wpgo_global_column_layout == "3-col-c" ) {
			get_sidebar(); // primary
		}
	}

	/**
	 * After content sidebar rendering logic.
	 *
	 * Callback function for the 'wpgo_after_content_close' hook.
	 *
	 * @since 0.1.0
	 */
	public function sidebar_after_content() {

		global $wpgo_global_column_layout;

		if ( $wpgo_global_column_layout == "2-col-l" || $wpgo_global_column_layout == "2-col-r" ) {
			get_sidebar(); // primary
		}

		if ( $wpgo_global_column_layout == "3-col-r" ) {
			get_sidebar( 'secondary' );
			get_sidebar(); // primary
		}

		if ( $wpgo_global_column_layout == "3-col-c" ) {
			get_sidebar( 'secondary' );
		}
	}

	/**
	 * Output browser title for the current page.
	 *
	 * @since 0.1.0
	 */
	public function wpgo_theme_title() {

		echo "<title>" . wp_title( '|', false, 'right' ) . "</title>";
	}

	/**
	 * Default theme comments template
	 *
	 * @since 0.1.0
	 */
	public static function theme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	switch ($comment->comment_type) :
	case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, 40 ); ?>
				<?php printf( __( '%s <span class="says">says:</span>', 'wpgothemes' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
			</div>
			<!-- .comment-author .vcard -->
			<?php if ( $comment->comment_approved == '0' ) : ?>
				<em><?php _e( 'Your comment is awaiting moderation.', 'wpgothemes' ); ?></em><br>
			<?php endif; ?>

			<div class="comment-meta commentmetadata">
				<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
					<?php
					/* translators: 1: date, 2: time */
					printf( __( '%1$s at %2$s', 'wpgothemes' ), get_comment_date(), get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'wpgothemes' ), ' ' );
				?>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>
				<!-- .reply -->

			</div>
			<!-- .comment-meta .commentmetadata -->

			<div class="comment-body"><?php comment_text(); ?></div>
		</div>
		<!-- #comment-## -->
		<?php
		break;
		case 'pingback'  :
		case 'trackback' :
		?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'wpgothemes' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'wpgothemes' ), ' ' ); ?></p>
	<?php
	break;
	endswitch;
	}

	/**
	 * Callback function for the 'template_redirect' hook.
	 *
	 * Set some framework global variables.
	 *
	 * @since 0.1.0
	 */
	public function set_globals() {

		/* WordPress global post and wp_query objects. */
		global $post, $wp_query;

		/* Framework globals. */
		global $wpgo_post_object; /* Current post object. */
		global $wpgo_is_front_page; /* Front page status. */
		global $wpgo_home_page; /* Home page status. */
		global $wpgo_post_id; /* Post ID. */
		global $wpgo_show_on_front; /* Store the front page reading setting. */
		global $wpgo_global_column_layout; /* Correct column layout to use for current page. */
		global $wpgo_page_on_front;

		$wpgo_post_object   = $post;
		$wpgo_is_front_page = is_front_page();
		$wpgo_home_page     = is_home();

		/* Test $wp-query->queried_object property, and that it has a valid ID, as it may not always exist (i.e. on 404.php page, or search.php). */
		//$wpgo_post_id = isset( $wp_query->queried_object ) ? $wp_query->queried_object->ID : null;

		// This will be null for 404 or search pages, or of WP_Post, WP_User object, stdClass class type etc.
		$wpgo_queried_object = get_queried_object();

		$wpgo_post_id = isset( $wpgo_queried_object->ID ) ? $wpgo_queried_object->ID : null;

		/* Modify the post ID if necessary.
		 *
		 * i.e. if 'A static page' has been selected on reading settings.
		 * In this case the post ID for the static page set for the blog posts will be invalid.
		 *
		 */
		$wpgo_show_on_front = get_option( 'show_on_front' );
		$wpgo_page_on_front = get_option( 'page_on_front' );
		if ( $wpgo_show_on_front == 'page' ) {
			/* If the 'Posts page'. */
			if ( is_home() ) {
				/* Use the 'page_for_posts' WP option to get the 'correct' ID for this page.
				 * Otherwise all other attempts at getting the post ID results in the first ID
				 * in the posts loop. */
				$wpgo_post_id = get_option( 'page_for_posts' );
			}
		}

		/*
		 * If current page is an archive OR front page with 'Your latest posts' OR front page with reading settings
		 * set to static page but the front page drop down not set, then use the default theme column layout.
		 */
		if ( is_archive() || ( $wpgo_is_front_page && $wpgo_show_on_front == 'posts' ) || ( $wpgo_home_page && $wpgo_page_on_front == 0 ) ) {
			$wpgo_global_column_layout = WPGo_Theme_Customizer::get_customizer_theme_option( 'wpgo_drp_default_layout' );
		} else {
			$wpgo_global_column_layout = get_post_meta( $wpgo_post_id, '_wpgo_column_layout', true );
		}

		if ( empty( $wpgo_global_column_layout ) || $wpgo_global_column_layout == 'default' ) {
			$wpgo_global_column_layout = WPGo_Theme_Customizer::get_customizer_theme_option( 'wpgo_drp_default_layout' );
		}

		/* Set the global WordPress $content_width variable. */
		WPGo_Utility::set_content_width( $wpgo_global_column_layout );
	}

	/**
	 * Callback function for the 'template_include' hook.
	 *
	 * Set theme template used to render the current page.
	 *
	 * @since 0.1.0
	 */
	public function set_current_template( $template ) {

		global $wpgo_template_name;

		/* Store the page template used for the current page. */
		$wpgo_template_name = basename( $template );

		return $template;
	}

	/**
	 * Enqueue Open-Sans font on front end if user not logged in.
	 *
	 * From WordPress 3.8 the Open-Sans font is automatically enqueued on front end when user is logged in. However,
	 * we still need this to be enqueued when not logged in.
	 *
	 * @since 0.2.0
	 */
	public function enqueue_open_sans_font() {

		// only need to enqueue if user NOT logged in
		if ( is_user_logged_in() ) {
			return;
		}

		$open_sans_font_url = '';

		/* translators: If there are characters in your language that are not supported
		 * by Open Sans, translate this to 'off'. Do not translate into your own language.
		 */
		if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'wpgothemes' ) ) {
			$subsets = 'latin,latin-ext';

			/* translators: To add an additional Open Sans character subset specific to your language,
			 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
			 */
			$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'wpgothemes' );

			if ( 'cyrillic' == $subset ) {
				$subsets .= ',cyrillic,cyrillic-ext';
			} elseif ( 'greek' == $subset ) {
				$subsets .= ',greek,greek-ext';
			} elseif ( 'vietnamese' == $subset ) {
				$subsets .= ',vietnamese';
			}

			// Hotlink Open Sans, for now
			$open_sans_font_url = "//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,300,400,600&subset=$subsets";
		}

		wp_register_style( 'wpgo-open-sans-font', $open_sans_font_url );
		wp_enqueue_style( 'wpgo-open-sans-font' );
	}

	/**
	 * Enqueue Dashicons on the front end if user not logged in.
	 *
	 * From WordPress 3.8 the Dashicons font is automatically enqueued on front end when user is logged in. However,
	 * we still need this to be enqueued when not logged in.
	 *
	 * @since 0.2.0
	 */
	public function enqueue_dashicons_font() {

		// enqueue if user NOT logged in, or logged in and we're on the theme customizer
		if ( is_user_logged_in() && ! WPGo_Utility::is_customizer() ) {
			return;
		}

		// Dashicons
		$suffix = SCRIPT_DEBUG ? '' : '.min';
		wp_register_style( 'wpgo-dashicons', "/wp-includes/css/dashicons$suffix.css" );
		wp_enqueue_style( 'wpgo-dashicons' );
	}
}