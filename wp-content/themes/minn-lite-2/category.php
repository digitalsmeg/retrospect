<?php get_header(); ?>

	<main class="content">

		<?php WPGo_Hooks::wpgo_after_content_open(); ?>

		<?php echo category_description(); ?>

		<?php get_template_part( 'loops/loop', 'category' ); ?>

	</main><!-- .content -->

<?php get_footer(); ?>