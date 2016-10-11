<?php
/*
Template Name: Prompts Template Page
*/

get_header();
global $embargoed, $embargoedate;
$user = wp_get_current_user(); 
// prohibit non captains
if(!in_array("team_captain",$user->roles) ){
	//wp_redirect( '/wp-login.php', 301 ); 
	//exit;	
}
//mdebug("SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = $post->ID AND meta_key LIKE 'stories_rating_%'");
$bc = get_body_class();
?>
<main class="content">

		<?php WPGo_Hooks::wpgo_after_content_open(); ?>


		<?php if ( have_posts() ) : ?>
			

			<?php $loop = new WP_Query("p=".$post->ID."&post_type=".$post->post_type);  ?>

<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
<? if($post->post_type != 'sponsors' && $post->post_type != 'post'){ ?>
<? if(storyHasPermission($post)){ ?>
<? if($post->post_type == 'prompts'){ ?>
	<? get_template_part( 'content', 'prompt' ); ?>	
    <? } ?>
    <? if($post->post_type == 'stories'){ 
    if(in_array("single-prompts",$bc)){
		
	} else {
		get_template_part( 'content', 'story' );
	}
   
   } else {
	   
	   get_template_part( 'content', '' );
   } ?>
    <? } else { ?>
    <? if($embargoed == true){ ?>
  <p>This story will be released on <? echo date("F j, Y",strtotime($embargoedate)); ?></p>
  ><? } ?>
    <? } ?>
    <? } else { ?>
    <? if($post->post_type == 'sponsors' ){ ?>
    <? get_template_part( 'content', 'sponsor' ); ?>	
    <? } ?>
    <? if($post->post_type == 'post'){ ?>
   <?php get_template_part( 'loops/loop', 'single-post' ); ?>
    <? } ?>
    <? } ?>
<?php endwhile; ?>

<? $count = 0; ?>

 <? if($post->post_type == 'prompts'){ ?>
 
        
  
<? if(0){ ?>
No Stories found.
<? } ?>
   <? }  else { ?>
 <? if(storyHasPermission($post)){ ?>
 <?
 
	
	if(in_array("single-prompts",$bc)){
		
	} else {
		//comments_template();
	}
	?>

<? } ?>
<? } ?>


		<?php else: ?>



			No posts found.



		<?php endif; ?>



	

	

	</main><!-- .content -->
 
    <? if($slug != "terms-of-service"){ 
		get_sidebar(); 
	} ?>

<?php get_footer(); ?>