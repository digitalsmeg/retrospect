<?php get_header(); ?>
<?
$slug = $post->post_name;
?>
	<main class="content" <? if($slug == "terms-of-service"){ ?>style="width: 100%;max-width: 100%;"<? } ?>>

		<?php WPGo_Hooks::wpgo_after_content_open(); ?>

		<?php get_template_part( 'loops/loop', 'single-page' ); ?>

	</main><!-- .content -->
    <? if($slug != "terms-of-service"){
			get_sidebar(); 
		} ?>
<?php get_footer(); ?>