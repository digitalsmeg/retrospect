<?php
$user = wp_get_current_user();
global $embargoed, $embargoedate;
$exc = get_the_excerpt();
?>
<?php
$bc = get_body_class();

if (in_array("single-prompts", $bc)) {
	$single = false;
}
else {
	$single = true;
}
$author_id = get_the_author_meta('ID');

$currentID = getCurrent();
$sql = "SELECT * FROM  " . $wpdb->prefix . "usermeta WHERE user_id = " . $post->post_author . " AND meta_key LIKE 'stories_prompted_%' AND meta_value = " . $post->ID;
$result = $wpdb->get_results($sql,ARRAY_A);

foreach($result as $prompt){
	$temp = explode("_", $prompt[meta_key]);
	$p = $temp[2];
	$prompted_post = get_post($p);
	$golive = $prompted_post->stories_embargo_until;
	if ($prompted_post->ID == $currentID) {
		$is_current = true;
	}
	else {
		$is_current = false;
	}
	
	
	if ($p > 0) {
		$prompted_by = '<div class="promptedBy">Prompted By <a style="text-decoration:underline;" href="' . get_permalink($prompted_post->ID) . '">' . $prompted_post->post_title . '</a></div>';
	}
}

?>
<?php
$user_id = get_current_user_id();
$read = get_post_meta($post->ID, "mark_as_read" . $user_id, true);
?>
<?php if ($golive <= date("Y-m-d") || $is_current || $user->ID == $post->post_author || current_user_can("administrator")) { ?>
<?
if (current_user_can("administrator") && is_single()){
?>
<div style="background:red;color:white;padding:5px;" class="adminAlert">
You are viewing this page as ADMIN.
</div><?	
}
?>
<div class="storyPromptContainer<?php if ($read) { ?> markedAsRead<?php } ?>">
  <?php if ((current_user_can("administrator") || $user->ID == $post->post_author)) { ?>
  <?php if (0) { ?>
  <p>This story will go live on <?php echo date("F j, Y", strtotime($golive)); ?>. It is hidden from public view. [<?php echo $golive; ?>]</p>
  <?php } ?>
  <?php } ?>
  <h2 class="entry-title"><a href="<?php
	echo get_the_permalink(); ?>" title="<?php
	the_title_attribute('echo=0'); ?>" rel="bookmark">
    <? echo (get_the_title())?get_the_title():"Untitled"; ?>
    </a> <span class="author">by
    <?php
	  $anon = get_post_meta($post->ID, 'stories_is_anonymous', true);
	  $original_post_id = $post->ID;
	if ($anon) { 
	$authorVariable = "Anonymous";
	?>
    Anonymous
    <?php
	}
	else { 
	$authorVariable = get_the_author();
	?>
    <?php
		the_author_posts_link(); 
		$posttemp = $post;
		?>
    <? if(storyCount($author_id)>0){ ?>
    (<? echo storyCount($author_id);  ?>
    <? if (storyCount($author_id) == 1){ ?>
    story
    <? } else { ?>
    stories
    <? } ?>
    )
    <? } ?>
    <?
		wp_reset_query();
		$post = $posttemp;
		
		?>
    <?php
	} ?>
    </span></h2>
  <?php
	
	if (is_single()){
	if (function_exists('bp_follow_add_follow_button')):
		if (bp_loggedin_user_id() && bp_loggedin_user_id() != get_the_author_meta('ID')) {
			if (!$anon) {
				bp_follow_add_follow_button(array(
					'leader_id' => get_the_author_meta('ID') ,
					'follower_id' => bp_loggedin_user_id()
				));
			}
		}

	endif;
	}
?>
  <?php echo ($prompted_by) ? $prompted_by : ""; ?>

  <?php
	if (($single && is_single()) && $post->post_type == "stories") {
		 showNumComments($post);
		 showAddThis($post);
	} ?>
  <p<?php
	echo $header_align_meta; ?>>
    <time class="date" datetime="<?php
	echo date("F j, Y", strtotime($post->post_date)); ?>" pubdate>
      <?php
	  $firsttime = get_post_meta( get_the_ID(), 'first_time' , true);
	  
	  // this means its sans writing prompt (my own topic)
	
	 if(empty($firsttime)){
		 //firsttime = date("Y-m-d",strtotime($post->post_date)); 
		update_post_meta( get_the_ID(), 'first_time',  date("Y-m-d")  );
		 $firsttime = get_post_meta( get_the_ID(), 'first_time' , true);
	 }
	 
	 if(date("F j, Y", strtotime($firsttime)) == "December 31, 1969" || $firsttime > date("Y-m-d")){
		?>Goes Live on <? echo date("F j, Y", strtotime($golive)) ; ?><? 
	 } else {
		echo date("F j, Y", strtotime($firsttime)); 
	
	 }
	 ?>
    </time>
    / <span class="categories">
    <?php
	if ($post->post_type == "stories") { ?>
    Stories
    <?php
	}
	else { ?>
    Writing Prompts
    <?php
	} ?>
    </span>
    <?php
	if ($post->post_status == "draft") { ?>
    | <span class="categories">DRAFT</span>
    <?php
	} ?>
  </p>
    <?
  		$response = get_post_meta( get_the_ID(), 'stories_response', true);
		if(!empty($response)){
			$perm = get_permalink($response);
			$rpost = get_post($response);
			?><div class="promptedBy">Written in response to <a href="<? echo $perm; ?>"><? echo $rpost->post_title; ?></a></div><?
		}

?>
  <div class="post-meta">
    <?php
	$anon = get_post_meta($post->ID, 'stories_is_anonymous', true);
?>
    <div stlye="clear:both;"></div>
  </div>
  <div class="clear"></div>
  <div class="entry-content">
    <?php
	if (!has_post_thumbnail($pid)) {
		$src = getFirstImage($post->post_content);
	}

	if ($src == "" && !has_post_thumbnail($pid)) {
		if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/mythos-stories/images/noThumb.png")) {
			$src = "/wp-content/plugins/mythos-stories/images/noThumb.png";
		}
	}

	if (($single && is_single())) {
		$cropped = newFeaturedImage(get_the_ID(), "none",true);
		if( $cropped ){
			?><img  src="/wp-content/uploads/<? echo $cropped;	?>" class="attachment-large size-large wp-post-image" alt="" align="center" style="margin:0px 5px 5px 0px"><?
			
		} else {
		the_post_thumbnail('large', array(
			'align' => 'center',
			'style' => 'margin:0px 5px 5px 0px'
		));
		}
		the_post_thumbnail_caption();
		$excerpt = get_the_excerpt();
	
		if(!empty($post->post_excerpt)){
			$content = get_the_content_with_formatting();
			$temp = explode("<p>",$content);
			$temp[1] = $temp[1] . "<blockquote class='storycallout'>".$excerpt."</blockquote>";
			$content = implode("<p>",$temp);	
		} else {
			$content = get_the_content_with_formatting();	
		}
		
		echo ($content);
?>
    <!-- RATINGS COMMENTED OUT FOR NOW
	<?php
		if ($post->post_type == "stories") { ?>
	<?php
		if (($single && is_single())) {
			ratingSystem($post->ID, false);
		}
		else {
			ratingSystem($post->ID, true);
		} ?>
  	<?php
    } ?> --> 
    <!-- like link -->
    <?php
	if (($single && is_single()) && $post->post_type == "stories") {
		showLikes($post);
	} ?>
    <!-- read this checkbox -->
    <?php
		if (($single && is_single()) && $post->post_type == "stories") {
			readThis($post->ID);
		} ?>
    <!-- show tags -->
    <?php
		if (($single && is_single()) && $post->post_type == "stories") {
			displayTags($post);
		} ?>
    <!-- show chracterizationss -->
    <?php
		if (($single && is_single()) && $post->post_type == "stories") {
			displayCharacterizations($post);
		} ?>
    <!-- chracterizations checkbox -->
    <?php
		if (($single && is_single()) && $post->post_type == "stories") {
			describeSystem($post->ID);
		} ?>
    <!-- sharing system -->
    <?php
		if (($single && is_single()) && $post->post_type == "stories") {
			shareSystem($post->ID);
		} ?>
    <!-- PDF LINK -->
    <?php
	if ((current_user_can("administrator") || $user->ID == $post->post_author) && ($single && is_single()) && $post->post_type == "stories") {
		?>
    <div stlye="clear:both;"></div>
    <?
	} ?>
    <!-- flag checkbox -->
    <?php
		if (($single && is_single()) && $post->post_type == "stories") {
			reportSystem($post->ID);
		} ?>
    <?php
	}
	else {
		 $featured_image = newFeaturedImage($post->ID,"left",false,true );
		 echo $featured_image;
		 echo $exc;
		
		?>
    <div class="clear"></div>
    <a class="more" href="<?php
		echo get_permalink(); ?>">Read More</a>
    <?php
	} ?>
    <?php
	if ($user->ID == $post->post_author) { ?>
    <a class="more" href="/wp-admin/post.php?post=<?php
		echo $post->ID; ?>&action=edit">Edit Your
    <?php
		if ($post->post_type == "stories") { ?>
    Story
    <?php
		}
		else { ?>
    Prompt
    <?php
		} ?>
    </a>
    <?php
	}  else { ?>
    <? if (($single && is_single()) && $post->post_type == "stories") { ?>
  
    <a class="more" style="background-color: rgb(0, 86, 114);" href="/wp-admin/post-new.php?post_type=stories&prompt=<? echo $prompted_post->ID; ?>&response=<? echo $post->ID; ?>">Write a story in response</a>
     <? } ?>
    <? } ?>
  </div>
</div>
<?php
	if (($single && is_single()) && $post->post_type == "stories") { ?>
<?php
		comments_template(); ?>
<?php

		// % is post id of the prompt

		$sql = "SELECT * FROM " . $wpdb->prefix . "usermeta WHERE meta_key LIKE 'stories_prompted_%' AND meta_value = $post->ID and user_id = $author_id ";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$thePosts = array(0);
		foreach($result as $row){
			$temp = explode("_", $row[meta_key]);
			$prompt_id = $temp[2];
		}
		
		$sql = "SELECT * FROM " . $wpdb->prefix . "usermeta WHERE meta_key LIKE 'stories_prompted_{$prompt_id}'";
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		$thePosts = array(0);
		foreach($result as $row){
			if ($post->ID != $row[meta_value]) {
				$thePosts[] = $row[meta_value];
			}
		}

?>
<?php

		$loops = new WP_Query(array(
			'post__in' => $thePosts,
			'post_type' => 'stories',
			'orderby' => 'date',
			'order' => 'DESC'
		));
?>
<?php if ($loops->post_count > 0) { ?>
<div class="storyPromptContainer moreStoriesDiv">
  <h1  class="relatedStores">Read More Stories On This Prompt</h1>
  <?php } ?>
  <?php
  $count = 0;
		while ($loops->have_posts()):
			$loops->the_post(); 
			
			$firsttime = get_post_meta( get_the_ID(), 'first_time' , true);
			if($firsttime <= date("Y-m-d")){
				$count++;
			?>
  <?php
			$count++; ?>
  <?php
			$anon = get_post_meta($post->ID, 'stories_is_anonymous', true);
?>
  <div style="margin-top:10px;">
    <?php
			$variableTitle = get_the_title();
			the_title('<strong><a href="' . get_permalink() . '" title="' . the_title_attribute('echo=0') . '" rel="bookmark">', '</a></strong>'); ?>
    <div>by <span class="author">
      <?php echo $author_prefix; ?>
      <?php if ($anon) { ?>
      Anonymous
      <?php } else { ?>
      <?php the_author_posts_link(); ?>
      <?php } ?>
      </span> </div>
  </div>
  <?php }
		endwhile; ?>
  <?php
  
  
  
  if($count == 0){
	 ?>There are no other live stories written on this prompt.<? 
  }
  		$sql = "SELECT * FROM " . $wpdb->prefix . "postmeta LEFT JOIN " . $wpdb->prefix . "posts ON " . $wpdb->prefix . "posts.ID = " . $wpdb->prefix . "postmeta.post_id  WHERE meta_key = 'stories_response' AND meta_value = $original_post_id AND post_status = 'publish'";
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		$thePosts = [];
		?>
		<?php if (sizeof($result) > 0) { ?>
        <div id="responseStoriesDiv">
        <h1  class="relatedStores">Stories Written in Response</h1><?php 
			foreach($result as $response){
				$thePosts[] = $response[post_id];
			}
		
		$loops = new WP_Query(array(
			'post__in' => $thePosts,
			'post_type' => 'stories',
			'orderby' => 'date',
			'order' => 'DESC'
		));

  $count = 0;
  ?><ul style="padding-left:0px;"><?
  		// depth 1
		while ($loops->have_posts()){			
			$loops->the_post(); 
			
			theDeets($post->ID);
			?>
      <ul>
      <?
	  //depth 2
	  $sql = "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = 'stories_response' AND meta_value = $post->ID";
	  
	  $depth2 = $wpdb->get_results($sql,ARRAY_A);
	  foreach($depth2 as $d2){
		?><li>
        <? echo theDeets($d2[post_id]); ?>
        <ul>
        <?
		 //depth 3
	  $sql = "SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_key = 'stories_response' AND meta_value = $d2[post_id]";
	  $depth3 = $wpdb->get_results($sql,ARRAY_A);
	  foreach($depth3 as $d3){
		?><li>
        <? echo theDeets($d3[post_id]); ?>
     	</li>
        <?  
	  }
	  ?>
      
        </ul>
        </li>
        <?  
	  }
	  ?>
      </ul>
      </li>
      
  <?php }
		

		?></ul></div><?
		} 
		if ($loops->post_count > 0) { ?>
</div>
<?php
		} ?>
<?php
	} ?>
<?php
}
else { ?>
<?php
	if (($single && is_single())) { ?>
<p>This story will go live on
  <?php
		echo date("F j, Y", strtotime($golive)); ?>
</p>
<?php
	} ?>
<?php
} ?>
<script>
//var addthis_share = { email_template: "Check Out on Retrospect" };
//, email_vars: { storytitle: "<? echo addslashes($variableTitle); ?>", author: "<? echo addslashes($authorVariable); ?>}
</script>

<script type="text/javascript">
var addthis_config = addthis_config||{};
    addthis_config.ui_email_note = 'Check out this story on Retrospect. Retrospect is a new website where baby boomers (and those who love them) can capture and share their stories and memories.';
</script>
<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-573e125df1c3ff96"></script>
