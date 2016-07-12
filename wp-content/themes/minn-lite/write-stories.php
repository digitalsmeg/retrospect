<?php
/*
Template Name: Write Stories Template 
*/
get_header();

$user = wp_get_current_user(); 


$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

?>

	<main class="content">


<?
global $post;

// current
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
        <h1 class="page-title entry-title" style="background:#005672;padding:10px;"><a style="color:#fff;"  href="/the-stories/this-weeks-prompts/">This Week's Prompt</a></h1>
        <?
		// this loop shows the stories of the  current prompt
		$loop = new WP_Query( array( 'post__in' => $thePosts,'post_type' => 'stories', 'orderby'=> 'date','order'   => 'DESC') );
		
		// this loop shows the current prompt
		$loop = new WP_Query( array( 'post_type' => 'prompts', 'p' => $current) );
		
while ( $loop->have_posts() ){ 
	if(storyHasPermission($post)){ 
		$loop->the_post();
		$d = get_post_meta( $post->ID, 'first_time' , true);
		$d = date("Y-m-d",strtotime($d));
		get_template_part( 'content', 'prompt' ); 
	} 
}  


// upcoming

		$currentID = getCurrent();
	 	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_embargo_until' AND post_id = ". $currentID;
	
	  	$word = "prompts";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sas = $result[0];
		$date = $sas[meta_value];
		
	
		?>
        <h1 class="page-title entry-title" style="background:#005672;padding:10px;"><a style="color:#fff;" href="/the-stories/upcoming-prompts/">Upcoming Prompts</a></h1>
        <?
		// this loop shows the upcoming prompts
		$loop = new WP_Query( array( 'post_type' => 'prompts', 'posts_per_page' => 4, 'meta_key' => 'stories_embargo_until', 'meta_value' => $date , meta_compare => '>', 'orderby'=>'meta_value','order' => 'ASC') );
		
		
		

while ( $loop->have_posts() ){ 
	if(storyHasPermission($post)){ 
		$loop->the_post();
		$d = get_post_meta( $post->ID, 'first_time' , true);
		$d = date("Y-m-d",strtotime($d));
		get_template_part( 'content', 'prompt' ); 
	} 
} 


// past	
		$currentID = getCurrent();
	 	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_embargo_until' AND post_id = ". $currentID;
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sas = $result[0];
		
		$date = $sas[meta_value];
		
		?>
       <h1 class="page-title entry-title" style="background:#005672;padding:10px;"><a style="color:#fff;"  href="/the-stories/past-prompts/">Past Prompts</a></h1>
        <?
		
	
		
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;	
	
		// this loops shows past prompts
		$loop = new WP_Query( array( 'post_type' => 'prompts', 'posts_per_page' => 5, 'meta_key' => 'stories_embargo_until', 'meta_value' => $date , meta_compare => '<', 'orderby'=>'meta_value','order' => 'DESC', 'paged' => $paged) );
	
while ( $loop->have_posts() ){ 
	if(storyHasPermission($post)){ 
		$loop->the_post();
		$d = get_post_meta( $post->ID, 'first_time' , true);
		$d = date("Y-m-d",strtotime($d));
		get_template_part( 'content', 'prompt' ); 
	} 
} 	
	
// /the-stories/past-prompts/page/2/
?>
<span class="next"><a href="/the-stories/past-prompts/page/2/">&lt;&lt; Older Prompts</a></span>




</main><!-- .content -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>