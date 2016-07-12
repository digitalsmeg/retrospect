<?php
/*
Template Name: Writing Prompts Template Page
*/

get_header();

$user = wp_get_current_user(); 

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>

	<main class="content">

		<?php WPGo_Hooks::wpgo_after_content_open(); ?>


		<?php if ( have_posts() ) : ?>
        <?

		
		$currentID = getCurrent();
		$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_embargo_until' AND post_id = ". $currentID;
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sas = $result[0];
		$date = $sas[meta_value];
		
		 $loop = new WP_Query( array( 'posts_per_page' => 4, 'post_type' => 'prompts',  'paged' => $paged, 'meta_key' => 'stories_embargo_until', 'meta_value' => $date ,  'orderby'=>'meta_value','order' => 'ASC' ,'meta_compare' => '>=') ); 
		 
		 while ( $loop->have_posts() ) : $loop->the_post(); ?>

<? if(storyHasPermission($post)){ ?>

 <? get_template_part( 'content', 'prompt' ); ?>	
    <? } ?>
<?php endwhile; ?>

<span class="next"><?php next_posts_link( '&lt;&lt; Older Prompts', $loop->max_num_pages ); ?></span>
<span class="prev"><?php previous_posts_link( 'Newer Prompts  &gt;&gt;', $loop->max_num_pages ); ?>



		<?php else: ?>



			No posts found.



		<?php endif; ?>



	

	

	</main><!-- .content -->
<?php get_footer(); ?>