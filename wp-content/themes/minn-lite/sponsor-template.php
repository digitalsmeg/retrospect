<?php
/*
Template Name: Sponsorship Template Page
*/

get_header();

$user = wp_get_current_user(); 
// prohibit non captains
if(!in_array("team_captain",$user->roles) ){
	//wp_redirect( '/wp-login.php', 301 ); 
	//exit;	
}


$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

?>

	<main class="content">
    <h1>Sponsorship</h1>
    			<?php $loop = new WP_Query( array( 'page_id' => '248') ); ?>

<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
<? the_content(); ?>
<?php endwhile; ?>
    
<?php if ( have_posts() ) : ?>



			<?php $loop = new WP_Query( array( 'post_type' => 'sponsors') ); ?>

<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>


    <? get_template_part( 'content', 'sponsor' ); ?>	

<?php endwhile; ?>


<span class="next"><?php next_posts_link( '&lt;&lt; Older posts', $loop->max_num_pages ); ?></span>
<span class="prev"><?php previous_posts_link( 'Newer posts  &gt;&gt;', $loop->max_num_pages ); ?>

		<?php else: ?>



			No posts found.



		<?php endif; ?>



	

	

	</main><!-- .content -->

<?php get_footer(); ?>