<?php



/**

 * Framework template parts class.

 *

 * Contains configurable template parts such as post loops.

 *

 * @since 0.1.0

 */

class WPGo_Template_Parts {



	private $default_loop_args;



	/**

	 * WPGo_Template_Parts class constructor.

	 *

	 * @since 0.1.0

	 */

	public function __construct() {



		/* Initialized here as expressions not allowed in class property declarations. */

		$this->default_loop_args = array( 'more'              => __( 'Read More', 'wpgothemes' ),

										  'next'              => '&laquo;&nbsp;' . __( 'Older Entries', 'wpgothemes' ),

										  'prev'              => __( 'Newer Entries', 'wpgothemes' ) . '&nbsp;&raquo;',

										  'date_sep'          => ' / ',

										  'author_sep'        => ' / ',

										  'tag_sep'           => '',

										  'cat_sep'           => ' / ',

										  'author_prefix'     => __( 'By ', 'wpgothemes' ) . ' ',

										  'tag_prefix'        => __( 'Tags', 'wpgothemes' ) . ': ',

										  'cat_prefix'        => __( 'In', 'wpgothemes' ) . ' ',

										  'render_date'       => true,

										  'render_author'     => true,

										  'render_tags'       => true,

										  'render_cats'       => true,

										  'render_comments'   => true,

										  'header_align_meta' => '',

										  'footer_align_meta' => ''

		);

	}



	/**

	 * Default posts loop template.

	 *

	 * Can be used on archive pages. e.g. index.php, home.php, category.php, tags.php etc.

	 *

	 * This template renders the post meta in the header.

	 *

	 * @since 0.1.0

	 */

	public function loop( $args = array() ) {



		// Add next/prev post link defaults. These can be overidden via the parent/child theme

		if ( ! isset( $args['next'] ) ) {

			$args['next'] = __( 'Next', 'wpgothemes' ) . '&nbsp;<i class="genericon-next"></i>';

		}

		if ( ! isset( $args['prev'] ) ) {

			$args['prev'] = '<i class="genericon-previous"></i>&nbsp;' . __( 'Previous', 'wpgothemes' );

		}



		$args = extract( $this->merge_loop_arguments( $args ) );

		?>



		<?php if ( have_posts() ) : ?>



			<?php while ( have_posts() ) : the_post(); ?>



				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



					<header>

						<?php WPGo_Utility::check_empty_post_title( get_the_ID() ); ?>



						<?php if ( $render_date || $render_author || $render_cats || $render_comments ) : ?>

							<div class="post-meta">

								<?php WPGo_Hooks::wpgo_post_archive_meta_header(); ?>

								<p<?php echo $header_align_meta; ?>>

									<?php if ( $render_date ) : ?>

										<time class="date" datetime="<?php the_date( 'c' ); ?>" pubdate><?php the_time( get_option( 'date_format' ) ); ?></time><?php echo $date_sep; ?><?php endif ?>

									<?php if ( $render_author ) : ?>

										<span class="author"><?php echo $author_prefix; ?><?php the_author_posts_link(); ?></span><?php echo $author_sep; ?><?php endif ?>

									<?php if ( $render_cats ) : ?>

										<span class="categories"><?php echo $cat_prefix; ?><?php the_category( ', ' ) ?></span><?php endif ?>



									<?php if ( $render_comments && comments_open() ) : ?>

										<?php echo $cat_sep; ?>

										<span class="comments"><?php comments_popup_link( __( 'Leave Comment', 'wpgothemes' ), __( '1 Comment', 'wpgothemes' ), __( '% Comments', 'wpgothemes' ), '', '' ); ?></span>

									<?php endif; ?>

								</p>

							</div><!-- .post-meta -->

						<?php endif; ?>

					</header>



					<?php

					/* Show post thumbnail if defined. */

					if ( has_post_thumbnail() ) {

						echo '<p><figure class="post-thumb">';

						the_post_thumbnail();

						echo '</figure><!-- .post-thumb --></p>';

					}

					?>



					<?php WPGo_Hooks::wpgo_before_post_archive_content(); ?>



					<div class="post-content">

						<?php

						/* Display post excerpt if defined, otherwise post content. */

						if ( get_post()->post_excerpt )

							echo '<p>' . get_the_excerpt() . '<br><a href="' . get_permalink() . '#more-' . get_the_ID() . '" class="button more-link">' . $more . '</a></p>';

						else {

							the_content( ' ' . $more );

						}



						wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );

						?>

					</div>

					<!-- .post-content -->



					<?php if ( $render_tags && has_tag( '', get_the_ID() ) ) : ?>

						<footer>

							<div class="post-meta">

								<?php WPGo_Hooks::wpgo_post_archive_meta_footer(); ?>

								<p<?php echo $footer_align_meta; ?>>

									<?php the_tags( '<span class="tags">' . $tag_prefix, ', ', '</span>' . $tag_sep ); ?>

								</p>

							</div>

							<!-- .post-meta -->

						</footer>

					<?php endif; ?>



				</article> <!-- .post -->



			<?php endwhile; // end of the loop. ?>



			<?php WPGo_Utility::paginate_links( $next, $prev ); ?>



		<?php else: ?>



			<?php $this->no_posts_found(); ?>



		<?php endif; ?>



	<?php

	}

	public function stories( $args = array() ) {
	
		if($_GET[post_type] == "stories"){

		wp_redirect("/the-stories/");exit;
		}

		// Add next/prev post link defaults. These can be overidden via the parent/child theme

		if ( ! isset( $args['next'] ) ) {

			$args['next'] = __( 'Next', 'wpgothemes' ) . '&nbsp;<i class="genericon-next"></i>';

		}

		if ( ! isset( $args['prev'] ) ) {

			$args['prev'] = '<i class="genericon-previous"></i>&nbsp;' . __( 'Previous', 'wpgothemes' );

		}



		$args = extract( $this->merge_loop_arguments( $args ) );

		?>



		<?php if ( have_posts() ) : ?>



			<?php while ( have_posts() ) : the_post(); ?>



				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



					<header>

						<?php WPGo_Utility::check_empty_post_title( get_the_ID() ); ?>



						<?php if ( $render_date || $render_author || $render_cats || $render_comments ) : ?>

							<div class="post-meta">

								<?php WPGo_Hooks::wpgo_post_archive_meta_header(); ?>

								<p<?php echo $header_align_meta; ?>>

									<?php if ( $render_date ) : ?>

										<time class="date" datetime="<?php the_date( 'c' ); ?>" pubdate><?php the_time( get_option( 'date_format' ) ); ?></time><?php echo $date_sep; ?><?php endif ?>

									<?php if ( $render_author ) : ?>

										<span class="author"><?php echo $author_prefix; ?><?php the_author_posts_link(); ?></span><?php echo $author_sep; ?><?php endif ?>

									<?php if ( $render_cats ) : ?>

										<span class="categories"><?php echo $cat_prefix; ?><?php the_category( ', ' ) ?></span><?php endif ?>



									<?php if ( $render_comments && comments_open() ) : ?>

										<?php echo $cat_sep; ?>

										<span class="comments"><?php comments_popup_link( __( 'Leave Comment', 'wpgothemes' ), __( '1 Comment', 'wpgothemes' ), __( '% Comments', 'wpgothemes' ), '', '' ); ?></span>

									<?php endif; ?>

								</p>

							</div><!-- .post-meta -->

						<?php endif; ?>

					</header>



					<?php

					/* Show post thumbnail if defined. */

					if ( has_post_thumbnail() ) {

						echo '<p><figure class="post-thumb">';

						the_post_thumbnail();

						echo '</figure><!-- .post-thumb --></p>';

					}

					?>



					<?php WPGo_Hooks::wpgo_before_post_archive_content(); ?>



					<div class="post-content">

						<?php

						/* Display post excerpt if defined, otherwise post content. */

						if ( get_post()->post_excerpt )

							echo '<p>' . get_the_excerpt() . '<br><a href="' . get_permalink() . '#more-' . get_the_ID() . '" class="button more-link">' . $more . '</a></p>';

						else {

							the_content( ' ' . $more );

						}



						wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );

						?>

					</div>

					<!-- .post-content -->



					<?php if ( $render_tags && has_tag( '', get_the_ID() ) ) : ?>

						<footer>

							<div class="post-meta">

								<?php WPGo_Hooks::wpgo_post_archive_meta_footer(); ?>

								<p<?php echo $footer_align_meta; ?>>

									<?php the_tags( '<span class="tags">' . $tag_prefix, ', ', '</span>' . $tag_sep ); ?>

								</p>

							</div>

							<!-- .post-meta -->

						</footer>

					<?php endif; ?>



				</article> <!-- .post -->



			<?php endwhile; // end of the loop. ?>



			<?php WPGo_Utility::paginate_links( $next, $prev ); ?>



		<?php else: ?>



			<?php $this->no_posts_found(); ?>



		<?php endif; ?>



	<?php

	}

	/**

	 * Author archive posts loop template.

	 *

	 * Used to show all posts from an author, including CPT.

	 *

	 * @since 0.1.0

	 */

	public function author_archive_loop( $args = array() ) {



		// Add next/prev post link defaults. These can be overidden via the parent/child theme

		if ( ! isset( $args['next'] ) ) $args['next'] = __( 'Next', 'wpgothemes' ) . '&nbsp;<i class="genericon-next"></i>';

		if ( ! isset( $args['prev'] ) ) $args['prev'] = '<i class="genericon-previous"></i>&nbsp;' . __( 'Previous', 'wpgothemes' );



		$args = extract( $this->merge_loop_arguments( $args ) );

		?>



		<?php if ( have_posts() ) : ?>



			<?php while ( have_posts() ) : the_post(); ?>



				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



					<header>

						<?php WPGo_Utility::check_empty_post_title( get_the_ID() ); ?>



						<?php if ( $render_date || $render_author || $render_cats || $render_comments ) : ?>

							<div class="post-meta">

								<?php WPGo_Hooks::wpgo_post_archive_meta_header(); ?>

								<p<?php echo $header_align_meta; ?>>

									<?php if ( $render_date ) : ?>

										<time class="date" datetime="<?php the_date( 'c' ); ?>" pubdate><?php the_time( get_option( 'date_format' ) ); ?></time><?php echo $date_sep; ?><?php endif ?>

									<?php if ( $render_author ) : ?>

										<span class="author"><?php echo $author_prefix; ?><?php the_author_posts_link(); ?></span><?php echo $author_sep; ?><?php endif ?>

									<?php if ( $render_cats ) : ?>

										<span class="categories"><?php echo $cat_prefix; ?><?php the_category( ', ' ) ?></span><?php endif ?>



									<?php if ( $render_comments && comments_open() ) : ?>

										<?php echo $cat_sep; ?>

										<span class="comments"><?php comments_popup_link( __( 'Leave Comment', 'wpgothemes' ), __( '1 Comment', 'wpgothemes' ), __( '% Comments', 'wpgothemes' ), '', '' ); ?></span>

									<?php endif; ?>

								</p>

							</div><!-- .post-meta -->

						<?php endif; ?>

					</header>



					<?php

					/* Show post thumbnail if defined. */

					if ( has_post_thumbnail() ) {

						echo '<p><figure class="post-thumb">';

						the_post_thumbnail();

						echo '</figure><!-- .post-thumb --></p>';

					}

					?>



					<?php WPGo_Hooks::wpgo_before_post_archive_content(); ?>



					<div class="post-content">

						<?php

						/* Display post excerpt if defined, otherwise post content. */

						if ( get_post()->post_excerpt )

							echo '<p>' . get_the_excerpt() . '<br><a href="' . get_permalink() . '#more-' . get_the_ID() . '" class="button more-link">' . $more . '</a></p>';

						else

							the_content( ' ' . $more );



						wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );

						?>

					</div>

					<!-- .post-content -->



					<?php if ( $render_tags && has_tag( '', get_the_ID() ) ) : ?>

						<footer>

							<div class="post-meta">

								<?php WPGo_Hooks::wpgo_post_archive_meta_footer(); ?>

								<p<?php echo $footer_align_meta; ?>>

									<?php the_tags( '<span class="tags">' . $tag_prefix, ', ', '</span>' . $tag_sep ); ?>

								</p>

							</div>

							<!-- .post-meta -->

						</footer>

					<?php endif; ?>



				</article> <!-- .post -->



			<?php endwhile; // end of the loop. ?>



			<?php WPGo_Utility::paginate_links( $next, $prev ); ?>



		<?php else: ?>



			<?php $this->no_posts_found(); ?>



		<?php endif; ?>



	<?php

	}



	/**

	 * Custom posts loop template.

	 *

	 * Can be used on archive pages. e.g. index.php, home.php, category.php, tags.php etc.

	 *

	 * This loop template renders the meta data in the post footer.

	 *

	 * @since 0.1.0

	 */

	public function loop_meta_bottom( $args = array() ) {



		// Add next/prev post link defaults. These can be overidden via the parent/child theme

		if ( ! isset( $args['next'] ) ) $args['next'] = __( 'Next', 'wpgothemes' ) . '&nbsp;<i class="genericon-next"></i>';

		if ( ! isset( $args['prev'] ) ) $args['prev'] = '<i class="genericon-previous"></i>&nbsp;' . __( 'Previous', 'wpgothemes' );



		$args = extract( $this->merge_loop_arguments( $args ) );

		?>



		<?php if ( have_posts() ) : ?>



			<?php while ( have_posts() ) : the_post(); ?>



				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



					<header>

						<?php WPGo_Utility::check_empty_post_title( get_the_ID() ); ?>

					</header>



					<?php

					/* Show post thumbnail if defined. */

					if ( has_post_thumbnail() ) {

						echo '<figure class="post-thumb">';

						the_post_thumbnail();

						echo '</figure><!-- .post-thumb -->';

					}

					?>



					<?php WPGo_Hooks::wpgo_before_post_archive_content(); ?>



					<div class="post-content">

						<?php

						/* Display post excerpt if defined, otherwise post content. */

						if ( get_post()->post_excerpt )

							echo '<p>' . get_the_excerpt() . '<br><a href="' . get_permalink() . '#more-' . get_the_ID() . '" class="button more-link">' . $more . '</a></p>';

						else

							the_content( ' ' . $more );



						wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );

						?>

					</div>

					<!-- .post-content -->



					<footer>

						<?php if ( $render_date || $render_author || $render_cats || $render_comments ) : ?>

							<div class="post-meta">

								<?php WPGo_Hooks::wpgo_post_archive_meta_footer(); ?>

								<p<?php echo $footer_align_meta; ?>>

									<?php if ( $render_date ) : ?>

										<time class="date" datetime="<?php the_date( 'c' ); ?>" pubdate><?php the_time( get_option( 'date_format' ) ); ?></time><?php echo $date_sep; ?><?php endif ?>

									<?php if ( $render_author ) : ?>

										<span class="author"><?php echo $author_prefix; ?><?php the_author_posts_link(); ?></span><?php echo $author_sep; ?><?php endif ?>

									<?php if ( $render_cats ) : ?>

										<span class="categories"><?php echo $cat_prefix; ?><?php the_category( ', ' ) ?></span><?php echo $cat_sep; ?><?php endif ?>

									<?php if ( $render_tags && has_tag( '', get_the_ID() ) ) : the_tags( '<span class="tags">' . $tag_prefix, ', ', '</span>' ); endif ?>



									<?php if ( $render_comments && comments_open() ) : ?>

										<?php echo $tag_sep; ?>

										<span class="comments"><?php comments_popup_link( __( 'Leave Comment', 'wpgothemes' ), __( '1 Comment', 'wpgothemes' ), __( '% Comments', 'wpgothemes' ), '', '' ); ?></span>

									<?php endif; ?>

								</p>

							</div><!-- .post-meta -->

						<?php endif; ?>

					</footer>



				</article> <!-- .post -->



			<?php endwhile; // end of the loop. ?>



			<?php WPGo_Utility::paginate_links(); ?>



		<?php else: ?>



			<?php $this->no_posts_found(); ?>



		<?php endif; ?>



	<?php

	}



	public function single_post_loop( $args = array() ) {



		$args = extract( $this->merge_loop_arguments( $args ) );

		?>



		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>



			<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>



				<header>

					<?php WPGo_Utility::hide_title_header_tag( get_the_ID(), "h1", "entry-title" ); ?>



					<?php if ( $render_date || $render_author || $render_cats || $render_comments ) : ?>

						<div class="post-meta">

							<?php WPGo_Hooks::wpgo_post_meta_header(); ?>



							<p<?php echo $header_align_meta; ?>>

								<?php if ( $render_date ) : ?>

									<time class="date" datetime="<?php the_date( 'c' ); ?>" pubdate><?php the_time( get_option( 'date_format' ) ); ?></time><?php echo $date_sep; ?><?php endif ?>

								<?php if ( $render_author ) : ?>

									<span class="author"><?php echo $author_prefix; ?><?php the_author_posts_link(); ?></span><?php echo $author_sep; ?><?php endif ?>

								<?php if ( $render_cats ) : ?>

									<span class="categories"><?php echo $cat_prefix; ?><?php the_category( ', ' ) ?></span><?php endif ?>



								<?php if ( $render_comments && comments_open() ) : ?>

									<?php echo $cat_sep; ?>

									<span class="comments"><?php comments_popup_link( __( 'Leave Comment', 'wpgothemes' ), __( '1 Comment', 'wpgothemes' ), __( '% Comments', 'wpgothemes' ), '', '' ); ?></span>

								<?php endif; ?>

							</p>

						</div><!-- .post-meta -->

					<?php endif; ?>

				</header>



				<?php WPGo_Hooks::wpgo_before_post_content(); ?>



				<div class="post-content">

					<?php
					the_post_thumbnail('large', array(
						'align' => 'left',
						'style' => 'margin:0px 5px 5px 0px'
					));
					the_post_thumbnail_caption();
					the_content( '' );

					wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );

					?>

				</div>

				<!-- .post-content -->



				<?php if ( $render_tags && has_tag( '', get_the_ID() ) ) : ?>

					<footer>

						<div class="post-meta">

							<?php WPGo_Hooks::wpgo_post_archive_meta_footer(); ?>

							<p<?php echo $footer_align_meta; ?>>

								<?php the_tags( '<span class="tags">' . $tag_prefix, ', ', '</span>' . $tag_sep ); ?>

							</p>

						</div>

						<!-- .post-meta -->

					</footer>

				<?php endif; ?>



			</article> <!-- .post -->



			<?php edit_post_link( __( 'Edit', 'wpgothemes' ), '<span class="edit-link">', '</span>' ); ?>



			<?php comments_template( '', true ); ?>



		<?php endwhile; // end of the loop. ?>



	<?php

	}



	public function single_attachment_loop( $args = array() ) {



		$args = extract( $this->merge_loop_arguments( $args ) );

		?>



		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>



			<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post' ); ?>>



				<header>

					<?php $wpgo_title_tag = is_front_page() ? 'h2' : 'h1'; ?>

					<?php WPGo_Utility::hide_title_header_tag( get_the_ID(), $wpgo_title_tag, "page-title entry-title" ); ?>

				</header>



				<?php WPGo_Hooks::wpgo_before_post_content(); ?>



				<div class="post-content">



					<?php if ( wp_attachment_is_image( get_the_ID() ) ) :

						$att_image = wp_get_attachment_image_src( get_the_ID(), "medium" ); ?>

						<p class="attachment">

							<a href="<?php echo wp_get_attachment_url( get_the_ID() ); ?>" title="<?php the_title(); ?>" rel="attachment"><img src="<?php echo $att_image[0]; ?>" /></a>

						</p>

					<?php endif; ?>



					<?php

					the_content();

					wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );

					?>



				</div>

				<!-- .post-content -->



			</article> <!-- .post -->



			<?php edit_post_link( __( 'Edit', 'wpgothemes' ), '<span class="edit-link">', '</span>' ); ?>



			<?php comments_template( '', true ); ?>



		<?php endwhile; ?>



	<?php

	}



	public function single_page_loop( $args = array() ) {



		$args = extract( $this->merge_loop_arguments( $args ) );

		?>



		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>



			<article id="post-<?php the_ID(); ?>" <?php post_class( 'singular-page' ); ?>>



				<header>

					<?php $wpgo_title_tag = is_front_page() ? 'h2' : 'h1'; ?>

					<?php WPGo_Utility::hide_title_header_tag( get_the_ID(), $wpgo_title_tag, "page-title entry-title" ); ?>

				</header>



				<?php WPGo_Hooks::wpgo_before_post_content(); ?>



				<div class="post-content">



					<?php

					the_content();

					wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );

					?>



				</div>

				<!-- .post-content -->



			</article> <!-- .post -->



			<?php edit_post_link( __( 'Edit', 'wpgothemes' ), '<span class="edit-link">', '</span>' ); ?>



			<?php comments_template( '', true ); ?>



		<?php endwhile; ?>



	<?php

	}



	public function single_404_loop( $args = array() ) {

		?>



		<article>

			<div>

				<h2 class="page-title"><?php _e( 'Error 404 - Page not found!', 'wpgothemes' ) ?></h2>



				<div>

					<p>

						<?php _e( 'Apologies, but the page you trying to reach does not exist, or has been moved. Why not try going back to the ', 'wpgothemes' ) ?>

						<a href="<?php echo home_url(); ?>"><?php _e( 'home', 'wpgothemes' ) ?></a> page<?php _e( ', using the menus, or searching for something more specific?', 'wpgothemes' ) ?>

					</p>



					<div class="search404"><?php get_search_form(); ?></div>

				</div>

			</div>

		</article>



	<?php

	}



	/**

	 * Search page loop template part.

	 *

	 * Used in theme search.php template file.

	 *

	 * @since 0.1.0

	 */

	public function search_page_loop( $s, $args = array() ) {



		global $wp_query; // used to access total number of search results



		$args          = extract( $this->merge_loop_arguments( $args ) );

		$date_sep      = '';

		$total_results = $wp_query->found_posts;

		?>



		<?php if ( have_posts() ) : ?>



			<h2 id="search-results-header" class="entry-title"><?php _e( 'Search results for', 'wpgothemes' ); ?><?php echo ' \'' . $s . '\''; ?></h2>

			<div id="search-matches"><?php printf( _n( '%d match found', '%d matches found', $total_results, 'wpgothemes' ), $total_results ); ?></div>



			<hr id="search-hr">



			<?php while ( have_posts() ) : the_post(); ?>



				<?php

				// Code to show search terms highlighted

				$keys    = explode( " ", $s );

				$title   = get_the_title();

				$content = WPGo_Utility::n_words( wp_strip_all_tags( get_the_content() ), 300 );



				$title   = preg_replace( '/(' . implode( '|', $keys ) . ')/iu', '<span class="search-results">\0</span>', $title );

				$content = preg_replace( '/(' . implode( '|', $keys ) . ')/iu', '<span class="search-results">\0</span>', $content );

				?>



				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>



					<header>



						<?php $no_title = __( '(no title)', 'wpgothemes' ); ?>



						<?php WPGo_Utility::check_empty_post_title( get_the_ID(), 'h3', $no_title, 'search-loop-header' ); ?>



						<?php if ( $render_date ) : ?>

							<div class="post-meta post-meta-inline">

								<?php WPGo_Hooks::wpgo_post_archive_meta_header(); ?>

								<?php if ( $render_date ) : ?>

									<time class="date" datetime="<?php the_date( 'c' ); ?>" pubdate><?php the_time( get_option( 'date_format' ) ); ?></time><?php echo $date_sep; ?><?php endif ?>

							</div><!-- .post-meta -->

						<?php endif; ?>



					</header>



					<?php WPGo_Hooks::wpgo_before_post_archive_content(); ?>



					<div class="post-content">

						<?php echo $content; ?>

						<?php wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );

						?>

					</div>

					<!-- .post-content -->



				</article> <!-- .post -->



			<?php endwhile; // end of the loop. ?>



			<?php WPGo_Utility::paginate_links(); ?>



		<?php else : ?>



			<div id="post-0" class="post no-results not-found">



				<h2 class="entry-title"><?php _e( 'No search results found...', 'wpgothemes' ); ?></h2>



				<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords. Or, choose from the links below to navigate to another page.', 'wpgothemes' ); ?></p>



				<div style="margin:0 auto;width:300px;"><?php get_search_form(); ?></div>



				<div class="widget" style="width:260px;float:left;">

					<h3 class="widget-title"><?php _e( 'Pages', 'wpgothemes' ); ?></h3>

					<ul>

						<?php wp_list_pages( 'title_li=' ); ?>

					</ul>

				</div>



				<div class="widget" style="width:260px;float:right;">

					<h3 class="widget-title"><?php _e( 'Post Categories', 'wpgothemes' ); ?></h3>

					<ul>

						<?php //wp_list_cats();

						wp_list_categories(); ?>

					</ul>

				</div>



			</div><!-- #post-0 -->



		<?php endif; ?>



	<?php

	}



	/**

	 * Post meta HTML block for single CPT's.

	 *

	 * @since 0.2.0

	 */

	public function single_cpt_meta( $taxonomy, $args = array() ) {



		$args = extract( $this->merge_loop_arguments( $args ) );



		if ( $render_date || $render_author || $render_cats || $render_comments ) :



			$term_list = get_the_term_list( get_the_ID(), $taxonomy, '', ', ', '' );

			if ( empty( $term_list ) ) $render_cats = false;

			?>

			<div class="post-meta">

				<?php WPGo_Hooks::wpgo_post_meta_header(); ?>

				<p<?php echo $header_align_meta; ?>>

					<?php if ( $render_date ) : ?>

						<time class="date" datetime="<?php the_date( 'c' ); ?>" pubdate><?php the_time( get_option( 'date_format' ) ); ?></time><?php echo $date_sep; ?><?php endif ?>

					<?php if ( $render_author ) : ?>

						<span class="author"><?php echo $author_prefix; ?><?php the_author_posts_link(); ?></span><?php endif ?>

					<?php if ( $render_cats ) : ?><?php echo $author_sep; ?>

						<span class="categories"><?php echo $cat_prefix; ?><?php echo $term_list; ?></span><?php endif ?>



					<?php if ( $render_comments && comments_open() ) : ?>

						<?php echo $cat_sep; ?>

						<span class="comments"><?php comments_popup_link( __( 'Leave Comment', 'wpgothemes' ), __( '1 Comment', 'wpgothemes' ), __( '% Comments', 'wpgothemes' ), '', '' ); ?></span>

					<?php endif; ?>

				</p>

			</div><!-- .post-meta -->

		<?php

		endif;

	}



	/**

	 * Merge custom arguments with default arguments.

	 *

	 * If no custom arguments defined then just use default arguments.

	 *

	 * @since 0.2.0

	 */

	public function merge_loop_arguments( $args = array() ) {



		$args = array_merge( $this->default_loop_args, $args );

		if ( ! empty( $args['header_align_meta'] ) ) $args['header_align_meta'] = ' class="' . $args['header_align_meta'] . '"';

		if ( ! empty( $args['footer_align_meta'] ) ) $args['footer_align_meta'] = ' class="' . $args['footer_align_meta'] . '"';



		return $args;

	}



	/**

	 * Display message if no posts found.

	 *

	 * @since 0.2.0

	 */

	public function no_posts_found() {

		?>

		<article>

			<div>

				<h2 class="page-title"><?php _e( 'Page not found!', 'wpgothemes' ) ?></h2>



				<div>

					<p>

						<?php _e( 'Apologies, but the page you trying to reach does not exist, or has been moved. Why not try going back to the ', 'wpgothemes' ) ?>

						<a href="<?php echo home_url(); ?>"><?php _e( 'home', 'wpgothemes' ) ?></a> page<?php _e( ', using the menus, or searching for something more specific?', 'wpgothemes' ) ?>

					</p>



					<div class="search404"><?php get_search_form(); ?></div>

				</div>

			</div>

		</article>

	<?php

	}



}