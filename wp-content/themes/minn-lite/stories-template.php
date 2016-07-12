<?php
/*
Template Name: Stories Template Page
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
<?php if ( have_posts() ) : ?>

<?
global $post;

$timeline = get_post_meta($post->ID, 'timeline', true);
//echo $timeline;
 $word = "stories";
if($timeline == "current"){
		$currentID = getCurrent();
	 	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_embargo_until' AND post_id = ". $currentID;
		 $word = "prompts";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sas = $result[0];
		$current = $sas[post_id];
		$date = $sas[meta_value];
			
		$sql = "SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'stories_prompted_{$current}'"; 
		$result = $wpdb->get_results($sql,ARRAY_A);
		$thePosts = array();
		foreach($result as $row){
			$thePosts[] = $row[meta_value];
		}
		?>
        <h1 class="page-title entry-title">This Week's Prompt</h1>
        <?
		// this loop shows the stories of the  current prompt
		$loop = new WP_Query( array( 'post__in' => $thePosts,'post_type' => 'stories', 'orderby'=> 'date','order'   => 'DESC') );
		
		// this loop shows the current prompt
		$loop = new WP_Query( array( 'post_type' => 'prompts', 'p' => $current) );
		
} elseif($timeline == "upcoming"){
		$currentID = getCurrent();
	 	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_embargo_until' AND post_id = ". $currentID;
	
	  	$word = "prompts";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sas = $result[0];
		$date = $sas[meta_value];
		
	
		?>
        <h1 class="page-title entry-title">Upcoming Prompts</h1>
        <?
		// this loop shows stories for upcoming prompts
		$loop = new WP_Query( array( 'post_type' => 'prompts', 'posts_per_page' => 4, 'meta_key' => 'stories_embargo_until', 'meta_value' => $date , meta_compare => '>', 'orderby'=>'meta_value','order' => 'ASC','paged' => $paged) );
		// this loop shows the upcoming prompts
		$loop = new WP_Query( array( 'post_type' => 'prompts', 'posts_per_page' => 4, 'meta_key' => 'stories_embargo_until', 'meta_value' => $date , meta_compare => '>', 'orderby'=>'meta_value','order' => 'ASC','paged' => $paged) );
		
}elseif($timeline == "past"){
	
		$currentID = getCurrent();
	 	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_embargo_until' AND post_id = ". $currentID;
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sas = $result[0];
		
		$date = $sas[meta_value];
		
		?>
        <h1 class="page-title entry-title">Past Prompts</h1>
        <?
		
	$word = "prompts";
		
		
		// this loops shows past prompts
		$loop = new WP_Query( array( 'post_type' => 'prompts', 'posts_per_page' => 5, 'meta_key' => 'stories_embargo_until', 'meta_value' => $date , meta_compare => '<', 'orderby'=>'meta_value','order' => 'DESC','paged' => $paged) );
		
		
		
} elseif($timeline == "surprise"){
	?>
        <h1 class="page-title entry-title">Suprise Me</h1>
        <?
		$loop = new WP_Query( array( 'post_type' => 'stories', 'posts_per_page' => 10 , 'orderby'=> 'rand','paged' => $paged, 'meta_key' => 'first_time', 'meta_value' => 'no' , meta_compare => '!=' ));
		
		
		
} elseif($timeline == "following"){
	$user = wp_get_current_user(); 
	$thePosts = explode(",",bp_get_following_ids('user_id='.$user->ID));
?>
        <h1 class="page-title entry-title">By People I Follow</h1>
        <?
	$loop = new WP_Query( array( 'author__in' => $thePosts,'post_type' => 'stories', 'orderby'=> 'date','order'   => 'DESC', 'posts_per_page' => 5, 'paged' => $paged, 'meta_key' => 'first_time', 'meta_value' => 'no' , meta_compare => '!=') );
}  elseif($timeline == "library"){
	$user = wp_get_current_user(); 
	$thePosts = explode(",",bp_get_following_ids('user_id='.$user->ID));
?>
        <h1 class="page-title entry-title">My Library</h1>
        <?
	$loop = new WP_Query( array( 'author__in' => $thePosts,'post_type' => 'storiess', 'orderby'=> 'date','order'   => 'DESC', 'posts_per_page' => 0, 'paged' => $paged) );
}  elseif($timeline == "topic"){
	?>     <h1 class="page-title entry-title">My Own Topic</h1><?
	$loop = new WP_Query( array( 'post_type' => 'topic', 'posts_per_page' => 0 , 'paged' => $paged) );
} else {
	
		$loop = new WP_Query( array( 'post_type' => 'stories','posts_per_page' => 10, 'meta_key' => 'first_time', 'orderby' => 'meta_value', 'order'=> 'DESC', 'paged' => $paged) );
		

}


if($loop->post_count == 0){
?>
<div>
				<h2 class="page-title">Sorry</h2>

				<div>
					<p>
						There are no stories to show.
                        </p>
				</div>
			</div>
<?	
}
?>
		

			

<?php while ( $loop->have_posts() ){ ?>
<? if(storyHasPermission($post)){ 

$loop->the_post();
 $d = get_post_meta( $post->ID, 'first_time' , true);
$d = date("Y-m-d",strtotime($d));
?>
	<? if($post->post_type == "stories" ) { ?>
    <? if($d <= date("Y-m-d")){ ?>
    <? get_template_part( 'content', 'story' ); ?>
    <? } ?>
    <? } else { ?>
    
      <? get_template_part( 'content', 'prompt' ); ?>
    <? } ?>	
    <? } ?>
<?php }  ?>

<? if($timeline != "upcoming"){ ?>
<span class="next"><?php next_posts_link( '&lt;&lt; Older '.$word, $loop->max_num_pages ); ?></span>
<span class="prev"><?php previous_posts_link( 'Newer '.$word.'  &gt;&gt;', $loop->max_num_pages ); ?>
<? } ?>
		<?php else: ?>



			No posts found.



		<?php endif; ?>



	

	

	</main><!-- .content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>