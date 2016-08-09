<?

/*
 * 
 * 
 *  
 * Content Functions
 * 
 * 
 *
 */
 
 function editor_message($atts){
		global $wpdb;
		ob_start();
		//echo nl2br(get_option('myth_editor_message'));
		$cid = getCurrent();
		$l = new WP_Query( array( 'post_type' => 'prompts', 'posts_per_page' => 1, 'p' => $cid) );
		while ( $l->have_posts() ) : $l->the_post();
		?>
<p style="text-align: left;" align="CENTER"><span style="font-size: medium;"><? echo get_the_title(); ?></span></p>
<? echo get_the_content() ; ?>
<?
		endwhile;
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
		
		
	
	
}

add_shortcode("editormessage", "editor_message");


function featured_MostPopular(){
	
	
	global $wpdb, $post, $fid, $popid;
	
	
	
	extract(shortcode_atts(array(), $atts));
	ob_start();
	 
  	?>
<div class="content">
  <?
	$cid  = getCurrent();
	
	 $sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_featured_story' AND  post_id = ".$cid;
		$result = $wpdb->get_results($sql,ARRAY_A);
	
	if($result[0][meta_value] > 0){
	
	$loop = new WP_Query( array( 'post_type' => 'stories', 'p' => $result[0][meta_value], 'post_status' => 'publish'));
	
	while ( $loop->have_posts() ) : $loop->the_post();
  		$pid = $fid = get_the_ID();
  		
		$permalink = get_permalink($pid);
   		?>
  <div class="col6 homeps">
    <h1 style=""> Featured Story </h1>
    <?
	
	 $featured_image = newFeaturedImage($pid );
	 echo $featured_image;
	 /*
	if(!empty($image)){
		?><img width="100"  src="<? echo $image; ?>" class="attachment-100x100 size-100x100 wp-post-image" alt="<? echo esc_attr(get_the_title()); ?>" align="right" style="margin:0px 5px 5px 0px"><?
	
	} else { 
    	the_post_thumbnail(array(100,100),array('align'=>'right','style'=>'margin:0px 5px 5px 0px')); 
    }
	*/
	?>
    <a href="<? echo $permalink; ?>" class="homeLink"> <? echo (get_the_title())?get_the_title():"Untitled"; ?></a>
    <? newCommentCount($pid) ?>
    <div class="author" style="font-size:14px;">
      <?
	  $anon = get_post_meta($pid , 'stories_is_anonymous', true);
	  if($anon){ ?>
      by Anonymous
      <? } else { ?>
      by
      <?php the_author_posts_link(); ?>
      <? } ?>
    </div>
    <div  class="text">
      <?  the_excerpt_max_charlength(130); ?>
    </div>
    <div class="clear-fix"></div>
  </div>
  <?
   	endwhile;
	}
	
	
	readersChoice($cid);
  	$content = ob_get_contents();
	ob_end_clean();
	return $content;
		
		
	
	

	

}

add_shortcode("homeStories", "home_Stories");
function home_Stories(){
	
	
	global $wpdb, $post, $fid, $popid;
	$cid  = getCurrent();
	
		
		$sql = "SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'stories_prompted_{$cid}' AND meta_value != '$fid' AND meta_value != '$popid'"; 
	
	$result = $wpdb->get_results($sql,ARRAY_A);
	$thePosts = array();
	foreach($result as $row){
		$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = 'stories' AND ID = '$row[meta_value]' "; 
		$r = $wpdb->get_results($sql,ARRAY_A);
		if(sizeof($r) > 0){
			$thePosts[] = $row[meta_value];
		}
	}
	
	extract(shortcode_atts(array(), $atts));
	ob_start();
	$total = sizeof($thePosts);
	$perpage = 6;
  	?>
<div class="content pagedStories" >
  <?
	
	$loop = new WP_Query( array( 'post__in' => $thePosts,'post_type' => 'stories', 'orderby'=> 'date','order'   => 'DESC', 'posts_per_page' => -1) );
	
	$total = $loop->found_posts; // use to be 80
	
	$c = 0;
	
	if($thePosts[0] > 0){
		
	while ( $loop->have_posts() ) : $loop->the_post();
  		$pid = get_the_ID();
		
  		$permalink = get_permalink($pid);
		$featured_image = newFeaturedImage($pid );
	
	   
	    ?>
  <div class="col6 allStories page<? echo floor($c/$perpage); ?> story<? echo $c; ?>"> <a href="<? echo $permalink; ?>">
     <?
   
	echo $featured_image;
  
    ?>
    </a> <a href="<? echo $permalink; ?>" class="homeLink"> <? echo (get_the_title())?get_the_title():"Untitled"; ?></a> <? newCommentCount($pid) ?>
    <div class="author" style="font-size:14px;">
      <?
	   $anon = get_post_meta($post->ID, 'stories_is_anonymous', true);
	  if($anon){ ?>
      by Anonymous
      <? } else { ?>
      by
      <?php the_author_posts_link(); ?>
      <? } ?>
    </div>
    <div class="text">
      <?  the_excerpt_max_charlength(130); ?>
    </div>
    <div class="clear-fix"></div>
  </div>
  <?
  if($c%2 == 1){
	?>
  <div class="clear"></div>
  <?  
  }
		$c++;
	   
		 
		
		
   	endwhile;
	$showNavs = $c;
	?>
  <?
	} else {
		$total = 0;
		$c = 1;	
	}
	$c--;
	
	for($a = 0; $a < $total - $c; $a++){
		?>
  <div class="col6 allStories  page<? echo floor($c/$perpage); ?>">
    <div><img align="center" style="display:none;margin-left:35px;width:100%;" src="/wp-content/plugins/retrospect-stories/images/noThumb.png"></div>
  </div>
  <?	
		/* old block
	?>
  <div class="col4 allStories  page<? echo floor($c/$perpage); ?>">
    <div style="height:100px;"><img align="center" style="margin-left:35px;width:100px;" src="/wp-content/plugins/retrospect-stories/images/noThumb.png"></div>
  </div>
  <?	
  */
		$c++;
	} // end for
	if($c > 0){
		$pages = $total/$perpage;
	} else {
		$pages = 0;
	}
	if($pages > 5) { 
	//$pages = 5; 
	}
	
	?>
</div>
<? 

$pages = ceil($pages);

if($pages > 1 && $showNavs > 0){ ?>
<div class="storyPageContainer"> <a class="pageMore pprev">&lt;</a>
  <?
	
	for($a = 0; $a < $pages; $a++){
		?>
  <a class="pageMore number <? if($a == 0){ ?>selected<? } ?>"><? echo $a + 1; ?></a>
  <? } ?>
  <a class="pageMore nnext">&gt;</a> </div>
<?
	}
	if($c == 0){
		
	?>
    <div id="nootherstories" style="display:none;">
There are no other stories to share for this week's prompt yet.
</div>
<?	
	}
  	$content = ob_get_contents();
	
	ob_end_clean();
	return $content;
		
		
	
	

	
}





add_shortcode("otherRecent", "otherRecentStories");
function otherRecentStories(){
	global $wpdb, $post, $fid, $popid, $last;
	
	ob_start();
		$cid  = getCurrent();
	
		
		$prompt  = getLast();
		$prompt = $prompt[0];
 
		$pid = $prompt[post_id];
		$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta  WHERE meta_key = 'stories_embargo_until' AND post_id = ". $pid;
	

	
	
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sas = $result[0];
		// get the go live date, we are only looking for past prompts
		$date = $sas[meta_value];
		
		// find all prompts before previous
		$loop = new WP_Query( array( 'post_type' => 'prompts',  'meta_key' => 'stories_embargo_until', 'meta_value' => $date , meta_compare => '<', 'orderby'=>'meta_value','order' => 'DESC') );
		$filter = array($wpdb->prefix."usermeta.meta_key = 'stories_prompted_0'");
		while ( $loop->have_posts() ){
			 $loop->the_post();
			$posts_in[] = $post->ID;
			$filter[]  = "meta_key = 'stories_prompted_".$post->ID."'";	 
		}
		$filter = implode (" OR ",$filter);
		
		// find all stories part of the previous loops prompts
		
		$sql = "SELECT * FROM ".$wpdb->prefix."usermeta WHERE ($filter) AND meta_key != 'stories_prompted_".$pid."' AND meta_key != 'stories_prompted_".$cid."'"; 
		
	
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		$thePosts = array();
		foreach($result as $row){
			$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = 'stories' AND ID = '$row[meta_value]' "; 
				
				$r = $wpdb->get_results($sql,ARRAY_A);
				if(sizeof($r > 0)){
				$thePosts[] = $row[meta_value];
				}
			
		}
		
		
	
			
		$loop = new WP_Query( array( 'post__in' => $thePosts,'post_type' => 'stories', 'meta_key' => 'first_time', 'orderby' => 'meta_value_datetime', 'order'=> 'DESC', 'posts_per_page' => 10) );
		$thePosts = [];
		while ( $loop->have_posts() ){
			 $loop->the_post();
			$thePosts[] = $post->ID;
			
		}
		
		shuffle($thePosts);
		
		
		
			$loop2 = new WP_Query( array( 'post__in' => $thePosts,'post_type' => 'stories', 'orderby'   => 'rand', 'posts_per_page' => 2) );
			
		
  	?>
	 <h1 style="margin:0px;">Other Recent Stories</h1>
   
<div class="content" style="" >
  <?
	//$loop = new WP_Query( array( 'post_type' => 'stories', 'posts_per_page' => 2, 'meta_key' => 'stories_embargo_until', 'first_time' => $golive , meta_compare => '>', 'orderby'=>'rand' ,'meta_type' => 'NUMERIC') );

	
	//$loop = new WP_Query( array( 'post__in' => $thePosts,'post_type' => 'stories', 'orderby'=> 'rand', 'posts_per_page' => 4) );
	
	
	$show = 2;
		
	while ( $loop2->have_posts() ) : $loop2->the_post();
		if(1){
  		$pid = get_the_ID();
		
  		$permalink = get_permalink($pid);
		$featured_image = newFeaturedImage($pid );
		
	   $d = get_post_meta( $post->ID, 'first_time' , true);
	    ?>
  <div class="col6 homeps"> <a href="<? echo $permalink; ?>">
    <? echo $featured_image; ?>
    </a> <a href="<? echo $permalink; ?>" class="homeLink"> <? echo (get_the_title())?get_the_title():"Untitled"; ?></a> 
    <? newCommentCount($pid) ?>
    <div class="author" style="font-size:14px;">
      <?
	   $anon = get_post_meta($post->ID, 'stories_is_anonymous', true);
	  if($anon){ ?>
      by Anonymous
      <? } else { ?>
      by
      <?php the_author_posts_link(); ?>
      <? } ?> <div style="font-size:10px;">Published on <? echo date("F j, Y",strtotime(get_post_meta( $post->ID, 'first_time' , true))); ?></div>
    </div>
    <div class="text">
      <?  the_excerpt_max_charlength(130); ?>
    </div>
    <div class="clear-fix"></div>
  </div>
  <?
  if($c%2 == 1){
	?>
  <div class="clear"></div>
  <?  
  }
		$c++;
	   
		
		}
		$show--; 
   	endwhile;
	$showNavs = $c;
	
	
	?>
</div>

<?
$content = ob_get_contents();
ob_end_clean();
return $content;
	
	
		
		
	
	

	

}



function twoRandomStories($cid){
	// first we have to get list of stories in the current prmopt
	global $wpdb, $post, $fid, $popid, $last;
	
	// deterine featured
	$sql = "SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'stories_prompted_{$cid}'";
	$result = $wpdb->get_results($sql,ARRAY_A);
	$thePosts = array();
	foreach($result as $row){
		$sql = "SELECT * FROM ".$wpdb->prefix."posts WHERE post_type = 'stories' AND ID = '$row[meta_value]' "; 
		$r = $wpdb->get_results($sql,ARRAY_A);
		if(sizeof($r) > 0){
			$thePosts[] = $row[meta_value];
		}
	}
	
	
	$loop = new WP_Query( array( 'post__in' => $thePosts,'post_type' => 'stories','orderby'   => 'rand', 'posts_per_page' => 2) );
	
	
	?><div class="col6 homeps">
    <?
	while ( $loop->have_posts() ) : $loop->the_post();
  		$pid = get_the_ID();
		$last[] = $pid;
  		$permalink = get_permalink($pid);
		
   		?>
  <div>
    
    <? if(1){ 
	$featured_image = newFeaturedImage($pid );
	echo $featured_image;
	?>
    <a href="<? echo $permalink; ?>" class="homeLink"> <? echo (get_the_title())?get_the_title():"Untitled"; ?></a> 
    <? newCommentCount($pid) ?>
    <div class="author" style="font-size:14px;">
      <?
	  $anon = get_post_meta($post->ID, 'stories_is_anonymous', true);
	  if($anon){ ?>
      by Anonymous
      <? } else { ?>
      by
      <?php the_author_posts_link(); ?>
      <? } ?>
    </div>
    <div  class="text">
      <?  the_excerpt_max_charlength(130); ?>
    </div>
    <div class="clear-fix"></div>
    <? } else {
		?>
    Insufficient data
    <?	
	} ?>
  </div>
  <?
   	endwhile;
	
	?>
    </div>
</div>
<?
}


function readersChoice($cid){
	// first we have to get list of stories in the current prmopt
	global $wpdb, $post, $fid, $popid;
	
	// deterine featured
	 $sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_featured_story' AND  post_id = ".$cid;
	
	$result = $wpdb->get_results($sql,ARRAY_A);
	$featured = $result[0][meta_value];
	
	$sql = "SELECT * FROM ".$wpdb->prefix."usermeta LEFT JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."usermeta.meta_value  WHERE meta_key = 'stories_prompted_$cid' AND post_type = 'stories'";
	
	
	$currs = [];
	$result = $wpdb->get_results($sql,ARRAY_A);
	$id = 0;
	foreach($result as $curr){
			$postid = $curr[meta_value];
			$comments = get_comment_count($postid);
			$comments = $comments[approved];
			
			$sql = "SELECT *, SUM(meta_value) as count FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'fs_votes_".$postid."' AND meta_value = 1";
			
			$result = $wpdb->get_results($sql,ARRAY_A);
			$comments  += $result[0][count];
			if($max == 0 && $comments == 0){
				$id = $popid = $postid;
			}
		
			if($comments > 0 && $postid != $featured){
				if($comments > $max){
					$max = $comments;
					$id = $popid = $postid;
				}
			}
		
	}
	
	
	$loop = new WP_Query( array( 'post_type' => 'stories', 'posts_per_page' => 1, 'p' => $id) );
	
	
	
	while ( $loop->have_posts() ) : $loop->the_post();
  		//$popid = get_the_ID();
		
		// NOTIFICATION HOOK myth_event_5
  		$permalink = get_permalink($pid);
		
   		?>
  <div class="col6 homeps">
    <h1 style="">Readers’ Choice</h1>
    <? if($id > 0){ 
    $featured_image = newFeaturedImage($pid );
	echo $featured_image;
	?>
    <a href="<? echo $permalink; ?>" class="homeLink"> <? echo (get_the_title())?get_the_title():"Untitled"; ?></a> 
	<? newCommentCount($pid) ?>
    <div class="author" style="font-size:14px;">
      <?
	  $anon = get_post_meta($post->ID, 'stories_is_anonymous', true);
	  if($anon){ ?>
      by Anonymous
      <? } else { ?>
      by
      <?php the_author_posts_link(); ?>
      <? } ?>
    </div>
    <div  class="text">
      <?  the_excerpt_max_charlength(130); ?>
    </div>
    <div class="clear-fix"></div>
    <? } else {
		
	} ?>
  </div>
  <?
   	endwhile;
	
	?>
</div>
<?
}
	


add_shortcode("featuredMostPopular", "featured_MostPopular");

add_shortcode("lastweekstories", "featured_LastWeek");
function featured_LastWeek(){
	
	
	global $wpdb, $post, $fid, $popid,$lid;
	
	
	
	extract(shortcode_atts(array(), $atts));
	ob_start();
	 
  	?>
<div class="content lastweek">
<h1 style="margin:0px;">Check Out Stories from the Previous Prompt</h1>
<?
	$prompt  = getLast();
	$prompt = $prompt[0];

	$cid = $prompt[post_id];
	
	
	 $sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key='dfiFeatured' and post_id='$cid'";
	 $result = $wpdb->get_results($sql,ARRAY_A);
	 $total = sizeof($result);
	
	 if($total > 0){
		 
		 
		
		 
			$image = unserialize($result[0][meta_value]);
			$image = explode(",",$image[0]);
			$image = $image[1];
			
			if($image){
				$guid = $image;
				$image = "/wp-content/uploads/".$image;	
				$thumbnail_id = $result[0][post_id];
				$q = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."posts WHERE guid LIKE  '%$guid%' AND post_parent = '$thumbnail_id'");
				
				
				 foreach ($q as $a) {
					
					  //if ($a->post_parent == $post->ID) {
					 if(1){
						$caption = '<p class="wp-caption-text" style="text-align:left;margin-top:10px;">'.$a->post_excerpt.'</p>';
					  }
				  }
			} else {
				$image = getFirstImage($cid = $prompt[post_content]);	
			}
		
		
	 } else {
			$image = getFirstImage($cid = $prompt[post_content]);	 
	 }
	
	
	
	
	?>
<div class="col6 homeps">
  <h1 style=""><a href="<?php echo get_permalink($prompt[post_id]); ?>"><? echo $prompt[post_title]; ?></a> </h1>
  <a href="<?php echo get_permalink($prompt[post_id]); ?>"><img style="height: 300px!important;" src="<? echo $image; ?>" /></a><? echo $caption; ?></div>
<?
	
    //readersChoice($prompt[post_id]); 
	twoRandomStories($prompt[post_id]); 
    $content = ob_get_contents();
	ob_end_clean();
	return $content;
		
		
	
	

	
}






add_shortcode("acceptprivacy", "acceptPrivacy");

function acceptPrivacy(){
	
	
	global $wpdb,  $current_user;

	
	
	if($_POST[retro_privacy]){
		delete_user_meta( $current_user->ID, 'retro_privacy');
		delete_user_meta( $current_user->ID, 'retro_privacy_date');
		add_user_meta( $current_user->ID, 'retro_privacy', 1);
		add_user_meta( $current_user->ID, 'retro_privacy_date', date("Y-m-d"));	
	}
	$signed =  get_user_meta($current_user->ID, "retro_privacy", true);
	extract(shortcode_atts(array(), $atts));
	
	ob_start();
	
  	?>
<div class="content" style="width:100%;max-width: 100%;">
  <? if($signed == 0){ ?>
  <form action="" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">
  
    <p> The Privacy Policy has changed. Our records indicate that you have not accepted the latest Privacy Policy.</p>
    <p> You can view the latest Privacy Policy <a target="_blank" href="/privacy-policy/">here</a>.
    <p>The Privacy Policy describes how Retrospect Media Inc. and its affiliates treat information collected or provided in connection with your use of our website.</p>
    <label>
      <input name="retro_privacy" id="retro_privacy" value="I accept Retrospect’s Terms of Use" required="" type="checkbox">
      I accept Retrospect’s <a href="/privacy-policy/">Privacy Policy</a></label>
    <p>
      <input name="signup_submit" style="background: #FF0F54;
  border-radius: 5px; float:left;
  color: white !important;
  font-size: 16px;
  display: inline-block;
  padding: 10px;" id="signup_submit" value="Accept" type="submit">
      <input id="_wpnonce" name="_wpnonce" value="<? echo wp_create_nonce(); ?>" type="hidden">
      <input name="_wp_http_referer" value="<? echo $_SERVER['HTTP_REFERER']; ?>" type="hidden">
      <input value="<? echo $_SERVER['REMOTE_ADDR']; ?>" name="bb2_screener_" type="hidden">
    </p>
  </form>
  <? } else { ?>
  <p>You have signed the latest Privacy Policy. You can browse the site at your leisure.</p>
  <? } ?>
</div>
<?
  	$content = ob_get_contents();
	
	ob_end_clean();
	return $content;
		
		
	
	

	
}

add_shortcode("accepterms", "acceptTerms");

function acceptTerms(){
	
	
	global $wpdb,  $current_user;

	
	
	if($_POST[retro_terms]){
		update_user_meta( $current_user->ID, 'retro_terms', 1);
		update_user_meta( $current_user->ID, 'retro_terms_date', date("Y-m-d"));
		
		update_user_meta( $current_user->ID, 'retro_privacy', 1);
		update_user_meta( $current_user->ID, 'retro_privacy_date', date("Y-m-d"));	
		
		update_user_meta( $current_user->ID, 'retro_beta', 1);
		update_user_meta( $current_user->ID, 'retro_beta_date', date("Y-m-d"));	
	}
	
	
	$signed =  get_user_meta($current_user->ID, "retro_terms", true);
	$signed2 =  get_user_meta($current_user->ID, "retro_privacy", true);
	$signed3 =  get_user_meta($current_user->ID, "retro_beta", true);
	extract(shortcode_atts(array(), $atts));
	
	ob_start();
	
  	?>
<div class="content" style="width:100%;max-width: 100%;">
  <? if($signed == 0 || $signed2 == 0 || $signed3 == 0){ ?>
  <p>
  Please check the boxes to signify your acceptance of our latest agreements and policies. You can click on the links to view them before accepting.
  </p>
  <form action="" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">
   <div class="box">
  	<label><strong>Privacy Policy *</strong></label>
  	<p>The Privacy Policy describes how Retrospect Media Inc. and its affiliates treat information collected or provided in connection with your use of our website. </p>
  	<p>
  	  <label>
        <input name="retro_privacy" id="retro_privacy" <? echo ($signed2 == 1)?"checked=''":''; ?> value="I accept Retrospect’s Terms of Use" required="" type="checkbox">
        I accept Retrospect’s <a href="/privacy-policy/" target="_blank">Privacy Policy</a></label>
  </p>
   </div> 
    <!-- <div class="box">
    <p>The Beta Agreement states that you will not disclose proprietary or confidential information about Retrospect that you learn by being a Beta Tester.</p>
    <p> You can view the latest Beta Agreement <a target="_blank" href="/beta-agreement/">here</a>.</p>
    <label>
      <input name="retro_beta" id="retro_beta" <? echo ($signed3 == 1)?"checked=''":''; ?> value="I accept Retrospect’s Beta Agreement" required="" type="checkbox">
      I accept Retrospect’s <a href="/beta-agreement/" target="_blank">Beta Agreement</a></label>
   </div> -->
  <input type="hidden"  name="retro_beta" id="retro_beta" value="1" />
   <div class="box">
    <label><strong>Terms of Use *</strong></label>
    <p>You retain copyright and ownership of your content, and grant us rights to it as explained in the Retrospect Terms of Use. </p>
    <p>
      <label>
        <input name="retro_terms" id="retro_terms" <? echo ($signed == 1)?"checked=''":''; ?>  value="I accept Retrospect’s Terms of Use" required="" type="checkbox">
        I accept Retrospect’s <a href="/terms-of-service/" target="_blank">Terms of Use</a></label>
  </p>
   </div>
   <div class="box">
     <label><strong>Beta Software</strong></label>
     <p>The Retrospect website is currently in beta, pre-release form. Retrospect membership is free during the beta test period. </p>
   </div>
    <p>
      <input name="signup_submit" style="background: #FF0F54;
  border-radius: 5px; float:left;
  color: white !important;
  font-size: 16px;
  display: inline-block;
  padding: 10px;" id="signup_submit" value="Accept" type="submit">
      <input id="_wpnonce" name="_wpnonce" value="<? echo wp_create_nonce(); ?>" type="hidden">
      <input name="_wp_http_referer" value="<? echo $_SERVER['HTTP_REFERER']; ?>" type="hidden">
      <input value="<? echo $_SERVER['REMOTE_ADDR']; ?>" name="bb2_screener_" type="hidden">
    </p>
  </form>
  <? } else { ?>
  <p>You have signed the latest Terms of Use and Privacy Policy. You can browse the site at your leisure.</p>
  <? } ?>
</div>
<?
  	$content = ob_get_contents();
	
	ob_end_clean();
	return $content;
		
		
	
	

	
}


function show_votes($admin,$cid){
	
	
	
	global $wpdb, $post, $fid, $popid;
	
	if($cid == ""){
		$cid  = getCurrent();
	}
	
	$user_id = get_current_user_id();
	
	
	ob_start();	
	if($user_id > 0){
		$readonly = "answer";	
	} else {
		$readonly = "answerreadonly";	
	}
	
	
  	?>
<?
	
		
		$loop = new WP_Query( array( 'post_type' => 'votes', 'posts_per_page' => 1, 'meta_key' => 'active_prompt', 'meta_value' => $cid , meta_compare => '=') );
	
	
	
	$total = $loop->found_posts; // use to be 80
	$c = 0;
	
	
		
	while ( $loop->have_posts() ) : $loop->the_post();
	$post_id = get_the_id();
	
	$voted = get_user_meta( $user_id , 'voted_'.$post_id );
	?>
<div class="quiz" >
  <?
	
  		?>
  <h3>
    <? the_content(); ?>
  </h3>
  <?
		
  		$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = ".get_the_id()." AND meta_key LIKE 'answer_%' ORDER BY meta_key ASC ";
	
	$result = $wpdb->get_results($sql,ARRAY_A);
	$a = 1;
	$totalVotes = 0;
	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = ".get_the_id()." AND meta_key LIKE 'votes_%'";
		  $results = $wpdb->get_results($sql,ARRAY_A);
		  foreach($results as $votes){
			$totalVotes += $votes[meta_value];  
		  }
	
	  foreach($result as $answer){
		  
		  if(!empty($answer[meta_value])){
		?>
  <div class="<? echo $readonly; ?>" id="answer_<? echo $answer[post_id]; ?>_<? echo $a; ?>">
    <? if(!$voted){ ?>
    <? if($readonly === "answerreadonly"){ ?>
    <div class="tooltips" onclick="window.location='/wp-login.php';"><span>Click to Login</span>
      <? } ?>
      <div class="circle"></div>
      <? } ?>
      <? echo $answer[meta_value]; ?>
      <? if($readonly === "answerreadonly"){ ?>
    </div>
    <? } ?>
    <?
			if($admin > 0){
				$voted = $admin;	
			}
		?>
    <? if($voted){ 
			$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = ".get_the_id()." AND meta_key = 'votes_$a'";
	
			$results = $wpdb->get_results($sql,ARRAY_A);
			$votes = $results[0][meta_value];
			if($totalVotes > 0){
			$width =  ($votes / $totalVotes) * 100;
			
			?>
    <? if(!$admin){ ?>
    <div class="voteBar" style="width: <? echo $width; ?>%">
      <? if($admin == 1){ ?>
      <? echo $votes; ?>
      <? } ?>
    </div>
    <?
			} else {
			?>
    (<? echo ($votes)?$votes:0; ?>)
    <?	
			}} else {
				$width = 0;
			}
			
		} ?>
  </div>
  <?  
		$a++;
		  }
	  }
	  ?>
</div>
<?
	
	
		
   	endwhile;
	
	$content = ob_get_contents();
	
	
	 
	ob_end_clean();
	return $content;
		
		
	
	

	

	
}



add_shortcode("optionalText", "optional_text");

function optional_text(){
	ob_start();
	$cid  = getCurrent();
	$content = get_post_meta( $cid , 'optional_text' , true );
	ob_end_clean();
	return $content;
}

add_shortcode("showVotes", "show_votes");


function advanced_notice($atts){
	global $wpdb, $post;;
	
	
	
		extract(shortcode_atts(array(), $atts));
		ob_start();
		?>
<h2 class="advancedTitle">Upcoming Prompts</h2>
<?
		$currentID = getCurrent();
	 	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_embargo_until' AND post_id = ". $currentID;
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sas = $wpdb->get_results($sql,ARRAY_A);
		$date = $sas[0][meta_value];
		
		
		$loop = new WP_Query( array( 'post_type' => 'prompts', 'posts_per_page' => 4, 'meta_key' => 'stories_embargo_until', 'meta_value' => $date , meta_compare => '>', 'orderby'=>'meta_value','order' => 'ASC') );
		?>
<ul class="advanced">
  <?php while ( $loop->have_posts() ) : $loop->the_post();
  	$pid = get_the_ID();
  	//the_post_thumbnail('thumb',array('style'=>'width: 100%;margin-bottom:10px;')); 
	$permalink = get_permalink($pid); 
	?>
  <li><a href="<? the_permalink(); ?>"><? echo the_title();  ?></a></li>
  <?php 
   endwhile; 
   ?>
</ul>
<?
   $content = ob_get_contents();
	ob_end_clean();
	return $content;
		
		
	
	
}

add_shortcode("advancednotice", "advanced_notice");

function sugessted_topic($atts){
	global $wpdb;
	
	
	
	extract(shortcode_atts(array(), $atts));
		ob_start();
		echo nl2br(get_option('myth_topic'));
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
		
		
	
	
}

add_shortcode("suggestedtopic", "sugessted_topic");


function newFeaturedImage($post_id, $float = "right", $justFeatured = false, $showno = false){
	//get the featured
	$post = get_post($post_id);
	$post_thumbnail_id  = get_post_thumbnail_id($post);
	$size = apply_filters( 'post_thumbnail_size', array(100,100) );
	do_action( 'begin_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size );
	// get featured image
	$html = wp_get_attachment_image( $post_thumbnail_id, $size, false, array('align'=>$float,'style'=>'margin:0px 5px 5px 0px'));
	do_action( 'end_fetch_post_thumbnail_html', $post->ID, $post_thumbnail_id, $size );
	// look for cropped version
	$cropped = get_post_meta( $post_thumbnail_id , '_wp_attached_file' , true );
	if($justFeatured){
		return $cropped;	
	}
	// if cropped exists use it
	
	if(!empty($cropped)){
		$html = '<img width="100"  src="/wp-content/uploads/'.$cropped .'" class="attachment-100x100 size-100x100 wp-post-image"  align="'.$float.'" style="margin:0px 5px 5px 0px">';
	}
	
	// so if crop and featured are empty we look for inline
	if(empty($html)){
		// else we look for image in content via this function
		$image = getFirstImage($post->post_content);
		
		if(!empty($image)){
		$html = '<img width="100"  src="'.$image .'" class="attachment-100x100 size-100x100 wp-post-image"  align="'.$float.'" style="margin:0px 5px 5px 0px">';	
		}
	}
	
	// if all is empty then generic placeholder
	if(empty($html) && $showno){
		$html = '<img width="100"  src="/wp-content/plugins/retrospect-stories/images/noThumb.png" class="attachment-100x100 size-100x100 wp-post-image"  align="'.$float.'" style="margin:0px 5px 5px 0px">';
	}
	return $html;
	
}
function getFirstImage($html){
	$document = new DOMDocument();
	libxml_use_internal_errors(true);
	if(!empty($html)){
    $document->loadHTML($html);
    libxml_clear_errors();
	$images = array();
	
	foreach($document->getElementsByTagName('img') as $img){
		
		// Extract what we want
		$image = array
		(
			'src' => $img->getAttribute('src')
		);
		
		// Skip images without src
		if( ! $image['src'])
			continue;
	
		// Add to collection. Use src as key to prevent duplicates.
		$images[$image['src']] = $image;
	}
	$images = array_values($images);
	
	if($images[0][src]){
		$images[0][src] = str_replace("alpha.","beta.",$images[0][src]);
		return $images[0][src];
	} else {
		return "";
	}
	} else {
		return "";
	}
}

function the_post_thumbnail_caption() {
  global $post, $wpdb;

  $thumbnail_id    = get_post_thumbnail_id($post->ID);
 
 
 $q = $wpdb->get_results("SELECT * FROM  ".$wpdb->prefix."posts WHERE ID = '$thumbnail_id'");
 foreach ($q as $a) {
	
 	  //if ($a->post_parent == $post->ID) {
	 if(1){
		echo '<p class="wp-caption-text">'.$a->post_excerpt.'</p>';
	  }
  }
}