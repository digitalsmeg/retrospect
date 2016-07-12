<?php

/**
 * Framework utility class. Contains general helper functions which are all static, so they can
 * be referenced without having to instantiate the class.
 *
 * @since 0.1.0
 */
class WPGo_Utility {

	/* Array of valid CSS named colors. */
	static $named_css_colors = array( 'aliceblue', 'antiquewhite', 'aqua', 'aquamarine', 'azure', 'beige', 'bisque', 'black', 'blanchedalmond', 'blue', 'blueviolet', 'brown', 'burlywood', 'cadetblue', 'chartreuse', 'chocolate', 'coral', 'cornflowerblue', 'cornsilk', 'crimson', 'cyan', 'darkblue', 'darkcyan', 'darkgoldenrod', 'darkgray', 'darkgreen', 'darkkhaki', 'darkmagenta', 'darkolivegreen', 'darkorange', 'darkorchid', 'darkred', 'darksalmon', 'darkseagreen', 'darkslateblue', 'darkslategray', 'darkturquoise', 'darkviolet', 'deeppink', 'deepskyblue', 'dimgray', 'dodgerblue', 'firebrick', 'floralwhite', 'forestgreen', 'fuchsia', 'gainsboro', 'ghostwhite', 'gold', 'goldenrod', 'gray', 'green', 'greenyellow', 'honeydew', 'hotpink', 'indianred', 'indigo', 'ivory', 'khaki', 'lavender', 'lavenderblush', 'lawngreen', 'lemonchiffon', 'lightblue', 'lightcoral', 'lightcyan', 'lightgoldenrodyellow', 'lightgreen', 'lightgrey', 'lightpink', 'lightsalmon', 'lightseagreen', 'lightskyblue', 'lightslategray', 'lightsteelblue', 'lightyellow', 'lime', 'limegreen', 'linen', 'magenta', 'maroon', 'mediumaquamarine', 'mediumblue', 'mediumorchid', 'mediumpurple', 'mediumseagreen', 'mediumslateblue', 'mediumspringgreen', 'mediumturquoise', 'mediumvioletred', 'midnightblue', 'mintcream', 'mistyrose', 'moccasin', 'navajowhite', 'navy', 'oldlace', 'olive', 'olivedrab', 'orange', 'orangered', 'orchid', 'palegoldenrod', 'palegreen', 'paleturquoise', 'palevioletred', 'papayawhip', 'peachpuff', 'peru', 'pink', 'plum', 'powderblue', 'purple', 'red', 'rosybrown', 'royalblue', 'saddlebrown', 'salmon', 'sandybrown', 'seagreen', 'seashell', 'sienna', 'silver', 'skyblue', 'slateblue', 'slategray', 'snow', 'springgreen', 'steelblue', 'tan', 'teal', 'thistle', 'tomato', 'turquoise', 'violet', 'wheat', 'white', 'whitesmoke', 'yellow', 'yellowgreen' );

	/**
	 * WPGo_Utility class constructor.
	 *
	 * @since 0.1.0
	 */
	public function __construct() {
	}

	/**
	 * Add theme classes to the WordPress body_class() function.
	 *
	 * @since 0.1.0
	 */
	public static function theme_classes() {

		global $wpgo_global_column_layout;

		/* Replace numbers with text. e.g. '3-col-r' => 'three-col-r' as CSS classes can't start with numbers. */
		$column_layout_str = str_replace( array( '1', '2', '3' ), array( 'one', 'two', 'three' ), $wpgo_global_column_layout );

		return $column_layout_str . ' ' . WPGO_THEME_NAME_SLUG;
	}

	/**
	 * Get featured image, if it exists, else get default.
	 *
	 * @since 0.1.0
	 */
	public static function get_featured_image( $obj, $post_thumb_size, $show_title = true ) {

		if ( $show_title ) {
			$title = "#" . $obj->ID;
		} else {
			$title = "";
		}

		$attr = array(
			'class' => "",
			'alt'   => "",
			'title' => $title
		);

		if ( has_post_thumbnail( $obj->ID ) ) {
			return get_the_post_thumbnail( $obj->ID, $post_thumb_size, $attr );
		}
	}

	/**
	 * Get responsive standard featured image, if it exists.
	 *
	 * Returns a post featured image inside an image tag, or false if one doesn't exist.
	 * No width or height attributes are returned, so the image can be included in a responsive theme design.
	 *
	 * @since 0.1.0
	 */
	public static function get_responsive_standard_featured_image( $post_id, $thumb_size, $featured_image_class = '' ) {

		$post_thumbnail_id      = get_post_thumbnail_id( $post_id );
		$featured_image_src_arr = wp_get_attachment_image_src( $post_thumbnail_id, $thumb_size );
		$featured_image_src     = $featured_image_src_arr[0];

		if ( $featured_image_src_arr ) {
			if ( ! empty( $featured_image_class ) ) {
				if ( $thumb_size == 'post-thumbnail' ) {
					$featured_image_class = 'class="attachment-post-thumbnail wp-post-image" ';
				} // use standard classes for 'post-thumbnail' thumb
				else {
					$featured_image_class = 'class="' . $featured_image_class . '" ';
				}
			}

			return '<img src="' . $featured_image_src . '" ' . $featured_image_class . '/>';
		} else {
			return false;
		}
	}

	/**
	 * Get responsive featured image, if it exists.
	 *
	 * Returns a post featured image inside an image tag, or false if one doesn't exist.
	 * No width or height attributes are returned, so the image can be included in a responsive theme design.
	 * This is a generic version of the get_responsive_standard_featured_image() function.
	 *
	 * @since 0.1.0
	 */
	public static function get_responsive_featured_image( $post_id, $thumb_size = 'thumbnail', $args = null ) {

		$post_thumbnail_id      = get_post_thumbnail_id( $post_id );
		$featured_image_src_arr = wp_get_attachment_image_src( $post_thumbnail_id, $thumb_size );
		$featured_image_src     = $featured_image_src_arr[0];

		if ( $featured_image_src_arr ) {

			if ( is_array( $args ) ) {
				foreach ( $args as $attr => $val ) {
					$val        = esc_attr( $val );
					$attributes = ' ' . $attr . '="' . $val . '"';
				}
			} else {
				$attributes = '';
			}

			return '<img src="' . $featured_image_src . '"' . $attributes . ' />';
		} else {
			return false;
		}
	}

	/**
	 * Return N-number of words from a string.
	 *
	 * @since 0.1.0
	 */
	public static function n_words( $text, $maxchar, $end = '...' ) {
		if ( mb_strlen( $text ) > $maxchar ) {
			$words  = explode( " ", $text );
			$output = '';
			$i      = 0;
			while ( 1 ) {
				$length = ( mb_strlen( $output ) + mb_strlen( $words[$i] ) );
				if ( $length > $maxchar ) {
					break;
				} else {
					$output = $output . " " . $words[$i];
					++$i;
				};
			};
		} else {
			$output = $text;
		}

		return $output . $end;
	}

	/**
	 * Get URL of current page.
	 *
	 * @since 0.1.0
	 */
	public static function currURL() {
		$pageURL = 'http';
		if ( isset( $_SERVER["HTTPS"] ) ) {
			if ( $_SERVER["HTTPS"] == "on" ) {
				$pageURL .= "s";
			}
		}
		$pageURL .= "://";
		if ( $_SERVER["SERVER_PORT"] != "80" ) {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}

		return $pageURL;
	}

	/**
	 * Sort an array with the order of another. Use this function if the array to be sorted
	 * is an array of arrays.
	 *
	 * @since 0.1.0
	 */
	public static function sortMultiArrayByArray( $array, $orderArray ) {
		$ordered = array();
		foreach ( $orderArray as $key => $value ) {
			if ( array_key_exists( $key, $array ) ) {
				$ordered[$key] = $array[$key];
				unset( $array[$key] );
			}
		}

		return $ordered + $array;
	}

	/**
	 * Sort an array with the order of another. Use this function if the array to be sorted
	 * is NOT an array of arrays (i.e. just a normal array).
	 *
	 * @since 0.1.0
	 */
	public static function sortArrayByArray( $array, $orderArray ) {
		$ordered = array();
		foreach ( $orderArray as $key ) {
			if ( array_key_exists( $key, $array ) ) {
				$ordered[$key] = $array[$key];
				unset( $array[$key] );
			}
		}

		return $ordered + $array;
	}

	/**
	 * Enqueue a script on a specific CPT editor page.
	 *
	 * @since 0.1.0
	 */
	public static function wp_enqueue_admin_cpt_script( $cpt, $handle, $src = false, $deps = array(), $ver = false, $in_footer = false ) {

		/* Check the admin page we are on. */
		global $pagenow;

		/* Default to null to prevent enqueuing. */
		$enqueue = null;

		/* Enqueue if we are on an 'Add New' type page. */
		if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == $cpt && $pagenow == "post-new.php" ) {
			$enqueue = true;
		}

		/* Enqueue if we are on an 'Edit' type page. */
		if ( isset( $_GET['post'] ) && $pagenow == "post.php" ) {
			/* Check post type. */
			$post_id  = $_GET['post'];
			$post_obj = get_post( $post_id );
			if ( $post_obj->post_type == $cpt ) {
				$enqueue = true;
			}
		}

		/* Only enqueue if editor page is a specific CPT. */
		if ( $enqueue ) {
			wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
		}
	}

	/**
	 * Enqueue a style on a specific CTP editor page.
	 *
	 * @since 0.1.0
	 */
	public static function wp_enqueue_admin_cpt_style( $cpt, $handle, $src = false, $deps = array(), $ver = false, $media = 'all' ) {

		/* Check the admin page we are on. */
		global $pagenow;

		/* Default to null to prevent enqueuing. */
		$enqueue = null;

		/* Enqueue if we are on an 'Add New' type page. */
		if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == $cpt && $pagenow == "post-new.php" ) {
			$enqueue = true;
		}

		/* Enqueue if we are on an 'Edit' type page. */
		if ( isset( $_GET['post'] ) && $pagenow == "post.php" ) {
			/* Check post type. */
			$post_id  = $_GET['post'];
			$post_obj = get_post( $post_id );
			if ( $post_obj->post_type == $cpt ) {
				$enqueue = true;
			}
		}

		/* Only enqueue if editor page is a specific CPT. */
		if ( $enqueue ) {
			wp_enqueue_style( $handle, $src, $deps, $ver, $media );
		}
	}

	/**
	 * Reference a child theme resource (if it exists) that you want to use in preference to the parent resource.
	 *
	 * This function assumes that the parent resource exists, but the associated child resource should be used instead if it has been defined.
	 * If it has NOT been defined in the child theme then use the parent resource. This prevents enforcement of resources expected in child themes,
	 * and makes them optional by providing the parent resource as a fallback.
	 *
	 * Example usage: Define a default image for post thumbnails (parent theme), and add default child thumbnail image to override this image.
	 *
	 * @since 0.1.0
	 *
	 * @parameter $parent_rel is the relative path to the resource from parent root.
	 * @parameter $child_rel is the relative path to the resource from child root.
	 * @parameter $file is the file name of the resource. Can be an array of files, which can be useful if you don't know the exact filename or extension.
	 *
	 * @return bool|string. Path to child resource, if it exists, else path to parent resource.
	 */
	public static function theme_resource_uri( $parent_rel = '', $file, $child_rel = '' ) {

		if ( empty( $file ) || empty( $parent_rel ) ) {
			return false;
		} /* If no file name, or parent directory, specified then just return. */

		if ( empty( $child_rel ) ) {
			$child_rel = $parent_rel;
		} /* If no specific child theme directory then just use parent directory. */

		/* If more than one parent dir specified. */
		if ( is_array( $parent_rel ) ) {
			/* Find the 'first' dir that contains the file and use that as the $parent_rel. */
			foreach ( $parent_rel as $pr ) {
				$parent_resource_dir = trailingslashit( WPGO_THEME_ROOT_DIR ) . trailingslashit( $pr ) . $file;
				$parent_resource_uri = trailingslashit( WPGO_THEME_ROOT_URI ) . trailingslashit( $pr ) . $file;
				if ( file_exists( $parent_resource_dir ) ) {
					$parent_rel = $pr; /* This will change $parent_rel from an array back to a string value. */
					break;
				}
				$parent_rel = ''; /* If file doesn't exist in dir then cast this back to a string. */
			}
		}

		if ( ! is_array( $file ) ) { /* If a single file. */

			$child_resource_uri  = trailingslashit( WPGO_CHILD_ROOT_URI ) . trailingslashit( $child_rel ) . $file;
			$child_resource_dir  = trailingslashit( WPGO_CHILD_ROOT_DIR ) . trailingslashit( $child_rel ) . $file;
			$parent_resource_dir = trailingslashit( WPGO_THEME_ROOT_DIR ) . trailingslashit( $parent_rel ) . $file;
			$parent_resource_uri = trailingslashit( WPGO_THEME_ROOT_URI ) . trailingslashit( $parent_rel ) . $file;

			/* Check if child/parent resource exists, otherwise return false. */
			if ( file_exists( $child_resource_dir ) ) {
				return $child_resource_uri;
			} elseif ( file_exists( $parent_resource_dir ) ) {
				return $parent_resource_uri;
			} else {
				return false; /* No match found. */
			}
		} else {
			/* If an array of files is specified then cylcle through them and return the 'first' one that exists. */
			foreach ( $file as $fl ) {
				$child_resource_uri  = trailingslashit( WPGO_CHILD_ROOT_URI ) . trailingslashit( $child_rel ) . $fl;
				$child_resource_dir  = trailingslashit( WPGO_CHILD_ROOT_DIR ) . trailingslashit( $child_rel ) . $fl;
				$parent_resource_dir = trailingslashit( WPGO_THEME_ROOT_DIR ) . trailingslashit( $parent_rel ) . $fl;
				$parent_resource_uri = trailingslashit( WPGO_THEME_ROOT_URI ) . trailingslashit( $parent_rel ) . $fl;

				if ( file_exists( $child_resource_dir ) ) {
					return $child_resource_uri;
				} elseif ( file_exists( $parent_resource_dir ) ) {
					return $parent_resource_uri;
				}
			}

			return false; /* No matches have been found in the array. */
		}
	}

	/**
	 * Create theme pages.
	 *
	 * General function to create theme pages. Just pass in an array of arrays containing
	 * the new page 'title', 'content', and 'template'.
	 *
	 * @since 0.1.0
	 */
	public static function create_theme_pages( $pages ) {

		/* Create new theme pages, from the array passed in. */
		foreach ( $pages as $page ) {
			$page_check = get_page_by_title( $page['title'] );

			$new_page = array(
				'post_type'    => 'page',
				'post_title'   => $page['title'],
				'post_content' => $page['content'],
				'post_status'  => 'publish',
				'post_author'  => 1
			);

			/* Create new page if one doesn't exist with the new title, and published post status. */
			if ( ! isset( $page_check ) || ( isset( $page_check ) && $page_check->post_status != "publish" ) ) {
				$new_page_id = wp_insert_post( $new_page );
				if ( ! empty( $page['template'] ) ) {
					update_post_meta( $new_page_id, '_wp_page_template', $page['template'] );
				}
			}
		}
	}

	/**
	 * Create new theme navigation menu and add pages to it.
	 *
	 * General function to create theme nav menu.
	 *
	 * @since 0.1.0
	 */
	public static function create_new_theme_nav_menu( $pages, $menu_name = 'Main Menu', $home_link = true, $assign_theme_location = true ) {

		/* Check if menu exists, and create it if not. */
		if ( ! is_nav_menu( $menu_name ) ) {

			/* Create nav menu. */
			$menu_id = wp_create_nav_menu( $menu_name );

			/* Get menu ID. */
			$menu   = wp_get_nav_menu_object( $menu_name );
			$menuID = (int) $menu->term_id;

			/* Optionally add a 'Home' menu item. */
			if ( $home_link ) {
				global $blog_id; /* Needed as get_home_url() returns main network url if multisite activated. */
				$menu1 = array( 'menu-item-status' => 'publish',
								'menu-item-type'   => 'custom',
								'menu-item-url'    => get_home_url( $blog_id ),
								'menu-item-title'  => 'Home'
				);
				wp_update_nav_menu_item( $menuID, 0, $menu1 );
			}

			/* Add a menu item for each page created earlier. */
			foreach ( $pages as $page ) {
				$new_page = get_page_by_title( $page['title'] );
				$menu2    = array( 'menu-item-object-id' => $new_page->ID,
								   'menu-item-parent-id' => 0,
					//'menu-item-position'  => 0,
								   'menu-item-object'    => 'page',
								   'menu-item-type'      => 'post_type',
								   'menu-item-status'    => 'publish',
								   'menu-item-title'     => $new_page->post_title
				);
				wp_update_nav_menu_item( $menuID, 0, $menu2 );
			}

			/* Check for page with 'Members Area' title, and add it to menu if found. */
			$page_check = get_page_by_title( 'Members Area' );
			if ( isset( $page_check ) && $page_check->post_status == "publish" ) {
				$menu3 = array( 'menu-item-object-id' => $page_check->ID,
								'menu-item-parent-id' => 0,
								'menu-item-position'  => 3,
								'menu-item-object'    => 'page',
								'menu-item-type'      => 'post_type',
								'menu-item-status'    => 'publish',
								'menu-item-title'     => $page_check->post_title
				);
				wp_update_nav_menu_item( $menuID, 0, $menu3 );
			}
		} else {
			/* Menu exists, so just get menu ID to assign theme location. */
			$menu   = wp_get_nav_menu_object( $menu_name );
			$menuID = (int) $menu->term_id;
		}

		/* Optionally assign menu location. */
		if ( $assign_theme_location ) {
			$locations                         = get_theme_mod( 'nav_menu_locations' );
			$locations[WPGO_CUSTOM_NAV_MENU_1] = $menuID;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	/**
	 * Add pages to navigation menu.
	 *
	 * Add pages only to an existing nav menu. No new menu is created or any theme locations set.
	 *
	 * @since 0.1.0
	 */
	public static function add_pages_to_nav_menu( $pages, $menu_name = 'Main Menu', $insert_from = 0 ) {

		/* Check if menu exists. */
		if ( is_nav_menu( $menu_name ) ) {

			/* Get menu ID. */
			$menu   = wp_get_nav_menu_object( $menu_name );
			$menuID = (int) $menu->term_id;

			/* Get existing menu items. */
			$menu_items = wp_get_nav_menu_items( $menu_name );

			/* Add a menu item for each page created earlier. */
			foreach ( $pages as $page ) {

				/* An 'add page' flag. */
				$add_page = true;

				/*  Check that a page with the same title doesn't already exist in the menu. */
				foreach ( $menu_items as $menu_item ) {
					if ( $menu_item->title == $page['title'] ) {
						$add_page = false;
						break; /* No point in checking the other menu items for this title. */
					}
				}

				if ( ! $add_page ) {
					continue;
				} /* Skip to the next page to be added. */

				$new_page = get_page_by_title( $page['title'] );
				$menu1    = array( 'menu-item-object-id' => $new_page->ID,
								   'menu-item-parent-id' => 0,
								   'menu-item-position'  => $insert_from,
								   'menu-item-object'    => 'page',
								   'menu-item-type'      => 'post_type',
								   'menu-item-status'    => 'publish',
								   'menu-item-title'     => $new_page->post_title
				);
				wp_update_nav_menu_item( $menuID, 0, $menu1 );
			}
		}
	}

	/**
	 * Optionally hide the title header tag on the front end via CSS but still render it in the DOM for SEO purposes.
	 *
	 * Can be used on single posts/pages including CPT's. Only hide title header tag if checkbox selected on post editor.
	 *
	 * @since 0.1.0
	 */
	public static function hide_title_header_tag( $id, $hd_tag = 'h1', $classes = '', $post_meta_id = '_wpgo_hide_title_header_tag' ) {

		$empty_placeholder = '(' . __( 'no title', 'wpgothemes' ) . ')';
		$empty_placeholder = WPGo_Hooks::wpgo_empty_title_placeholder( $empty_placeholder );

		/* Sanitize CSS class list. */
		$class_list = explode( " ", $classes );
		$classes    = ''; //reset
		foreach ( $class_list as $class ) {
			$classes .= sanitize_html_class( $class ) . ' ';
		}
		$classes = rtrim( $classes ); // get rid of trailing space

		$hide_title_header_tag = get_post_meta( $id, $post_meta_id, true );
		$title                 = get_the_title();

		if ( empty( $title ) ) {
			$title = $empty_placeholder;
		} // if user hasn't entered a title give it a placeholder title

		/* Add classes attribute if not empty. */
		if ( ! empty( $classes ) ) {
			$classes = ' class="' . $classes . '"';
		}

		if ( '1' == $hide_title_header_tag ) {
			echo '<' . $hd_tag . ' style="display:none;"' . $classes . '>' . $title . '</' . $hd_tag . '>';
		} else {
			echo '<' . $hd_tag . $classes . '>' . $title . '</' . $hd_tag . '>';
		}
	}

	/**
	 * Get widget-number, and multi-number for the next widget to be added.
	 *
	 * Gets the widget-number and multi-number for a particular widget (Info Box,
	 * Color Switcher etc.).
	 *
	 * @since 0.1.0
	 */
	public static function get_widget_args( $widget_name ) {

		global $wp_registered_widgets, $wp_registered_widget_controls;

		$sort = $wp_registered_widgets;
		usort( $sort, '_sort_name_callback' );
		$done = array();

		foreach ( $sort as $widget ) {

			if ( in_array( $widget['callback'], $done, true ) ) // We already showed this multi-widget
			{
				continue;
			}

			$done[] = $widget['callback'];

			if ( ! isset( $widget['params'][0] ) ) {
				$widget['params'][0] = array();
			}

			$args = array( 'widget_id' => $widget['id'], 'widget_name' => $widget['name'], '_display' => 'template' );

			$id_base          = $wp_registered_widget_controls[$widget['id']]['id_base'];
			$args['_temp_id'] = "$id_base-__i__";
			$args['_id_base'] = $id_base;
			$args['_add']     = 'multi';

			$widget_id           = $widget['id'];
			$control             = isset( $wp_registered_widget_controls[$widget_id] ) ? $wp_registered_widget_controls[$widget_id] : array();
			$args['_widget_num'] = isset( $control['params'][0]['number'] ) ? $control['params'][0]['number'] : '';

			if ( $widget['name'] == $widget_name ) {
				break;
			}
		}

		return $args;
	}

	/**
	 * Add default widget upon successful theme activation.
	 *
	 * @since 0.1.0
	 */
	public static function add_default_widget( $widget, $overwrite_widgets = false ) {

		global $wpgo_connect_multi_number;
		$multi_number = $wpgo_connect_multi_number;
		$wpgo_connect_multi_number ++;

		$args          = WPGo_UTILITY::get_widget_args( $widget['widget_name'] ); // returns an array
		$id_base       = $args['_id_base'];
		$widget_number = $args['_widget_num'];
		$widget_id     = $id_base . '-' . $multi_number;
		$widget_area   = $widget['widget_area'];

		/* Create widget. */
		$add_new_widget                 = get_option( 'widget_' . $id_base );
		$add_new_widget[$multi_number]  = $widget['default_settings'];
		$add_new_widget['_multiwidget'] = 1;
		update_option( 'widget_' . $id_base, $add_new_widget );

		/* Add to widget area. */
		$add_to_sidebar = get_option( 'sidebars_widgets' );

		/* Overwrite (or just add to) existing widgets in the specified widget area. */
		if ( $overwrite_widgets ) {
			$add_to_sidebar[$widget_area] = array();
		}

		$add_to_sidebar[$widget_area][] = $widget_id;
		wp_set_sidebars_widgets( $add_to_sidebar );
	}

	/**
	 * Tests a string for valid Gravatar e-mail or image URL.
	 *
	 * An <img> tag is returned if a valid image or Gravatar.
	 *
	 * @since 0.1.0
	 */
	public static function validate_image_str( $image, $class = "avatar", $size = "50" ) {

		$class = sanitize_html_class( $class );

		$end = mb_substr( $image, - 4 );
		if ( $class != "" ) {
			$class = "class=\"{$class}\"";
		}

		/* Looks like a direct image URL let's check it is valid. */
		if ( $end == ".jpg" || $end == ".png" || $end == ".gif" ) {
			try {
				if ( $image == "" || ! ( $img_size = @getimagesize( $image ) ) ) {
					throw new Exception( 'Not a valid image.' );
				}
				// Image URL OK so show image icon
				$image = "<img {$class} src=\"{$image}\" width=\"{$size}\" height=\"{$size}\" />";
			} catch ( Exception $e ) {
				$image = ""; // Image URL no good so make sure it's blank and outputs nothing
			}
		} /* Try to get as a gravatar image. */
		else {
			$image = get_avatar( $image, $size );
		}

		return $image;
	}

	/**
	 * Renders the widgets areas for sidebar-xx.php files.
	 *
	 * If $show_default parameter is true then show default text widget if no widgets have been added to the widget area yet.
	 *
	 * @since 0.1.0
	 */
	public static function render_widget_area( $widget_area_name = null, $show_default = false, $check_global = false ) {

		/* Default to post widget area if nothing else specified. */
		if ( empty( $widget_area_name ) ) {
			$widget_area_name = 'primary-post-widget-area';
		}

		if ( is_active_sidebar( $widget_area_name ) ) :
			echo '<div id="' . $widget_area_name . '" class="widget-area">';
			dynamic_sidebar( $widget_area_name );
			echo '</div>';
		else:
			if ( $show_default )
				self::empty_widget_area( $widget_area_name );
		endif;
	}

	/**
	 * Renders the custom widgets areas for sidebar-xx.php files.
	 *
	 * @since 0.1.0
	 */
	public static function render_custom_widget_areas( $custom_widget_areas ) {

		global $wp_registered_sidebars;

		$custom_widget_areas = array_keys( $custom_widget_areas );

		foreach ( $custom_widget_areas as $custom_widget_area ) {

			/* Custom widget areas. */
			if ( is_active_sidebar( $custom_widget_area ) ) : ?>
				<div id="<?php echo $custom_widget_area; ?>" class="widget-area">
					<?php dynamic_sidebar( $custom_widget_area ); ?>
				</div> <?php
			else:
				self::empty_widget_area( $custom_widget_area );
			endif;
		}
	}

	/**
	 * Loop through the specified theme custom page template files.
	 *
	 * @since 0.1.0
	 */
	public static function custom_widget_area_loop( $sidebar_hook = null, $fallback_default_widget_area_name = null, $show_default = false, $check_global = false ) {

		global $wpgo_template;

		/* Default to post widget area if nothing else specified. */
		if ( empty( $fallback_default_widget_area_name ) ) {
			$fallback_default_widget_area_name = 'primary-post-widget-area';
		}

		$custom_pages = array(); /* Initialize to empty array. */

		/* At the moment this feature only supports (i.e. was only needed for) primary sidebars, but it can be easily extended for secondary sidebars. */
		switch ( $sidebar_hook ) {
			case 'primary-archive':
				$custom_pages = WPGo_Hooks::wpgo_custom_primary_sidebar_archive( $custom_pages );
				break;
			case 'primary-pages':
				$custom_pages = WPGo_Hooks::wpgo_custom_primary_sidebar_pages( $custom_pages );
				break;
			case 'primary-posts':
				$custom_pages = WPGo_Hooks::wpgo_custom_primary_sidebar_posts( $custom_pages );
				break;
			default:
				$custom_pages = array();
		}

		$custom_pages_flag = 0;
		foreach ( $custom_pages as $custom_page => $widget_area ) {
			if ( $wpgo_template == $custom_page ) {
				self::render_widget_area( $widget_area, $show_default, $check_global );
				$custom_pages_flag = 1;
				break;
			}
		}
		/* If no custom pages set then show a default widget area. */
		if ( $custom_pages_flag == 0 ) {
			self::render_widget_area( $fallback_default_widget_area_name, true, true );
		}
	}

	/**
	 * Set the global WordPress $content_width variable.
	 *
	 * @since 0.1.0
	 */
	public static function set_content_width( $wpgo_global_column_layout ) {

		global $content_width;

		if ( $wpgo_global_column_layout == "1-col" ) {
			$content_width = 960;
		} elseif ( $wpgo_global_column_layout == "2-col-l" || $wpgo_global_column_layout == "2-col-r" ) {
			$content_width = 650;
		} else /* Assume 3-column layout. */ {
			$content_width = 374;
		}
	}

	/**
	 * Check for empty post titles on archive pages and output placeholder text for empty titles.
	 *
	 * @since 0.1.0
	 */
	public static function check_empty_post_title( $post_id = null, $tag = 'h2', $no_title = '', $tag_class = "entry-title", $rel = "bookmark" ) {

		if ( empty( $no_title ) ) {
			$no_title = __( '(no title)', 'wpgothemes' );
		} // specified here rather than as a default parameter so it's translatable

		/* Return if no post id. */
		if ( empty( $post_id ) ) {
			return;
		}

		$post_title = get_the_title( $post_id );

		if ( ! empty( $post_title ) ) :
			echo '<' . $tag . ' class="' . $tag_class . '"><a href="' . get_permalink( $post_id ) . '" rel="' . $rel . '">' . $post_title . '</a></' . $tag . '>';
		else :
			echo '<' . $tag . ' class="' . $tag_class . '"><a href="' . get_permalink( $post_id ) . '" rel="' . $rel . '">' . $no_title . '</a></' . $tag . '>';
		endif;
	}

	/**
	 * Calculate number of textarea rows, and class for width, depending on content.
	 *
	 * @since 0.1.0
	 */
	public static function get_textarea_rows( $content = null, $min = 3, $max = 25, $default_class = 'gray', $extra_class = 'gray-medium-textarea' ) {

		$res = array(); // Initialize array
		if ( empty( $content ) ) {
			$res['rows']  = $min;
			$res['class'] = $default_class;
		} else {
			$arr          = explode( "\n", $content );
			$rows         = ( count( $arr ) < ( $min + 1 ) ) ? $min : count( $arr ); // Min of 3 lines.
			$rows         = ( count( $arr ) > $max ) ? $max : $rows; // Max of 25 lines.
			$res['rows']  = $rows;
			$res['class'] = $extra_class;
		}

		return $res;
	}

	/**
	 * Check custom widget areas specified in post meta actually exist.
	 *
	 * For each custom widget area not found delete the post meta reference to it.
	 * e.g. A custom widget specified in post meta may not exist if a page has been imported from another site.
	 *
	 * @since 0.1.0
	 */
	public static function check_cwa_exist( $sidebar_custom_widget_areas, $post_id, $meta_key ) {

		/* If a page doesn't use custom widget areas. */
		if ( empty( $sidebar_custom_widget_areas ) ) {
			return $sidebar_custom_widget_areas;
		}

		global $wp_registered_sidebars;

		$wp_registered_sidebar_keys = array_keys( $wp_registered_sidebars );

		foreach ( $sidebar_custom_widget_areas as $key => $value ) {
			if ( ! in_array( $key, $wp_registered_sidebar_keys ) ) {
				unset( $sidebar_custom_widget_areas[$key] );
				update_post_meta( $post_id, $meta_key, $sidebar_custom_widget_areas );
			}
		}

		return $sidebar_custom_widget_areas;
	}

	/**
	 * If a widget area is empty display default a notification in a text widget.
	 *
	 * @since 0.1.0
	 */
	public static function empty_widget_area( $widget_area = null, $title = null, $text = null ) {

		global $wp_registered_sidebars;

		if ( empty( $title ) ) {
			$title = 'No widgets found';
		}

		if ( empty( $text ) ) {
			if ( empty( $widget_area ) ) {
				$text = 'This widget area is currently empty. Why not add some widgets to this area now via your <a href="' . get_admin_url( null, 'widgets.php' ) . '" target="_blank">Admin Widgets</a> page.';
			} else {
				/* Get the widget area name. */
				$widget_area_name = $widget_area;
				if ( in_array( $widget_area, array_keys( $wp_registered_sidebars ) ) ) {
					$widget_area_name = $wp_registered_sidebars[$widget_area]['name'];
				}

				$text = 'The <strong>' . $widget_area_name . '</strong> widget area is currently empty. <a href="' . get_admin_url( null, 'widgets.php' ) . '" target="_blank"><strong>Click here</strong></a> to add widgets.';
			}
		}

		$primary_text_instance = array(
			'title'  => $title,
			'text'   => $text,
			'filter' => ''
		);
		$primary_text_args     = array(
			'before_widget' => '<div class="widget widget_text">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>'
		);

		echo '<div class="widget-area">';
		the_widget( 'WP_Widget_Text', $primary_text_instance, $primary_text_args );
		echo '</div>';
	}

	/**
	 * Validate hexadecimal/named color CSS value.
	 *
	 * Allowed color values are 3 or 6 digit hexadecimal color codes, or valid CSS named color values (e.g. CornflowerBlue).
	 *
	 * @since 0.1.0
	 *
	 * @param string $color Hexadecimal color code. Can be 3 or 6 digits.
	 *
	 * @return string|bool A 3 or 6 digit hex color code with '#' prefix, or false.
	 */
	public static function validate_css_color( $color ) {

		if ( in_array( strtolower( $color ), self::$named_css_colors ) ) {
			return $color;
		} // return named CSS color

		return self::validate_hex_color( $color ); // returns a 3 or 6 digit hex color code, or false
	}

	/**
	 * Validate 3 or 6 digit hex color code.
	 *
	 * Hexadecimal code can be 3 or 6 digits, with or without '#' prefix.
	 *
	 * @since 0.1.0
	 *
	 * @param string $hex Hexadecimal color code, which can be 3 or 6 digits.
	 *
	 * @return string|bool A 3 or 6 digit hex color code, or false.
	 */
	public static function validate_hex_color( $hex ) {

		if ( preg_match( '/^#?([a-f0-9]{6}|[a-f0-9]{3})$/i', $hex ) ) {
			if ( mb_substr( trim( $hex ), 0, 1 ) != '#' ) {
				$hex = '#' . $hex;
			} // add '#' prefix if missing
			return $hex; // return 3 or 6 digit hex color code
		} else {
			return false;
		} // not a valid hex color
	}

	/**
	 * Validate hexadecimal/named color CSS value.
	 *
	 * Allowed color values are 3 or 6 digit hexadecimal color codes, or valid CSS named color values (e.g. CornflowerBlue).
	 *
	 * @since 0.1.0
	 *
	 * @param string $hex  Hexadecimal string to change luminosity of.
	 * @param int    $lum  Change luminosity of $hex by this value (0-255).
	 * @param string $type Use 'lighten' (default) or 'darken' to alter luminosity up or down.
	 *
	 * @return string|bool A 6 digit hex color code, or false.
	 */
	public static function hex_color_luminance( $hex, $lum = 20, $type = 'lighten' ) {

		$hexlen = mb_strlen( $hex );
		$hex    = self::validate_hex_color( $hex ); // returns a 3 or 6 digit hex color code, or false

		if ( false === $hex ) {
			return false;
		} // not a valid hex color value

		$hex = str_replace( '#', '', $hex );

		// Convert hex color code into separate rgb values.
		if ( $hexlen == 3 ) {
			$r = hexdec( mb_substr( $hex, 0, 1 ) . mb_substr( $hex, 0, 1 ) );
			$g = hexdec( mb_substr( $hex, 1, 1 ) . mb_substr( $hex, 1, 1 ) );
			$b = hexdec( mb_substr( $hex, 2, 1 ) . mb_substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( mb_substr( $hex, 0, 2 ) );
			$g = hexdec( mb_substr( $hex, 2, 2 ) );
			$b = hexdec( mb_substr( $hex, 4, 2 ) );
		}

		// Alter the color luminosity.
		if ( $type == 'lighten' ) {
			$r += $lum;
			$g += $lum;
			$b += $lum;
			if ( $r > 255 ) {
				$r = 255;
			}
			if ( $g > 255 ) {
				$g = 255;
			}
			if ( $b > 255 ) {
				$b = 255;
			}
		} elseif ( $type == 'darken' ) {
			$r -= $lum;
			$g -= $lum;
			$b -= $lum;
			if ( $r < 0 ) {
				$r = 0;
			}
			if ( $g < 0 ) {
				$g = 0;
			}
			if ( $b < 0 ) {
				$b = 0;
			}
		}

		// Convert rgb values back into hex color code (6 digit).
		$r_str = dechex( $r );
		$g_str = dechex( $g );
		$b_str = dechex( $b );

		return '#' . $r_str . $g_str . $b_str; // returns hex color code string
	}

	/**
	 * Check if a particular widget is active.
	 *
	 * Searches all registered widget areas for a particular widget ID.
	 *
	 * @since 0.2.0
	 */
	public static function is_widget_active( $widget_id = null ) {

		$sidebars_widgets = wp_get_sidebars_widgets();

		/* If no widget ID or sidebar array then return. */
		if ( empty( $widget_id ) || ! is_array( $sidebars_widgets ) ) {
			return false;
		}

		/* Loop through registered sidebars for a particular widget. */
		foreach ( $sidebars_widgets as $sidebar => $widgets ) {
			if ( 'wp_inactive_widgets' == $sidebar ) {
				continue;
			} // ignore inactive widgets

			if ( is_array( $widgets ) ) {
				foreach ( $widgets as $widget ) {
					$pos = strpos( $widget, $widget_id );
					if ( $pos !== false ) {
						return true;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Output string to the browser console window.
	 *
	 * Only output if browser console object exists.
	 *
	 * @since 0.2.0
	 */
	public static function console( $text, $error = true ) {
		?>
		<script>
			if (typeof console !== "undefined") {
				<?php if($error) : ?>
				console.error('<?php echo $text; ?>');
				<?php else : ?>
				console.log('<?php echo $text; ?>');
				<?php endif; ?>
			}
		</script>
	<?php
	}

	/**
	 * Show paginated links on post archive pages.
	 *
	 * @since 0.2.0
	 */
	public static function paginate_links( $next = null, $prev = null, $show_pages = true ) {

		global $wp_query;

		if ( ! $next ) {
			$next = __( 'Next', 'wpgothemes' ) . '&nbsp;&raquo;';
		}
		if ( ! $prev ) {
			$prev = '&laquo;&nbsp;' . __( 'Previous', 'wpgothemes' );
		}

		if ( $show_pages ) { // show numbered next/previous paginated nav links

			$big = 999999999;

			$paginate_links = paginate_links( array(
				'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $wp_query->max_num_pages,
				'mid_size'  => 1,
				'prev_text' => $prev,
				'next_text' => $next
			) );

			// Display pagination if more than one page found
			if ( $paginate_links ) {
				echo '<nav class="navigation next-prev-post-links pagination">';
				echo $paginate_links;
				echo '</nav>';
			}
		} else { // show text next/previous paginated nav links
			?>

			<nav class="navigation next-prev-post-links">
				<div class="alignleft prev"><?php previous_posts_link( $prev ); ?></div>
				<div class="alignright next"><?php next_posts_link( $next ); ?></div>
			</nav>

		<?php
		}
	}

	/**
	 * See if we are on the customizer.
	 *
	 * @since 0.2.0
	 */
	public static function is_customizer() {

		global $wp_customize;

		return isset( $wp_customize ) ? true : false;
	}
}