<?

/*
 * 
 * 
 *  
 * Ajax Functions
 * 
 * 
 *
 */


 
function admin_date_callback() {
	global $wpdb; // this is how you get access to the database
	update_post_meta( $_POST[post_id], 'stories_embargo_until', $_POST[stories_embargo_until] );
	
}

add_action( 'wp_ajax_admin_date', 'admin_date_callback' );

 
function rate_callback() {
	global $wpdb; // this is how you get access to the database
	$post_id = $_POST[story_id];
	$user_id = get_current_user_id();
	//$sql = "DELETE FROM ".$wpdb->prefix."postmeta WHERE meta_key LIKE 'stories_rating%'"; 	
	//$wpdb->query($sql);
	
	$rating =  get_post_meta( $post_id , "stories_rating_{$user_id}" , true );
	
		update_post_meta( $post_id,  "stories_rating_{$user_id}", $_POST[rating] * 1 );
		$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = $post_id AND meta_key LIKE 'stories_rating%'";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$totalRating = 0;
		foreach($result as $ratings){
			$totalRating += $ratings[meta_value];
		}
		if(sizeof($result) > 0){
			$totalRating = $totalRating / sizeof($result);
		} else {
			$totalRating = 0;
		}
		$post = get_post($post_id);
		$args = array("id"=>"","action"=>"Rated a story: '".$post->post_title."'","content"=>"They gave it a $_POST[rating]. You can view this story <a href='".get_permalink($post->ID)."'>here</a>.","user_id"=>$user_id,"primary_link"=>"","type"=>"Rating","component"=>"activity");
				
	
	
	bp_activity_add( $args );
		
		echo $totalRating;
	
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_rate', 'rate_callback' );



function markasread_callback() {
	global $wpdb; // this is how you get access to the database
	$post = get_post($_POST[story_id]);
	$user = wp_get_current_user(); 
	$c = get_post_meta($post->ID, "mark_as_read".$user->ID,true);
	
	if($c == ""){
		update_post_meta($post->ID, "mark_as_read".$user->ID,date("Y-m-d"));
	} else {
		delete_post_meta($post->ID, "mark_as_read".$user->ID)	;
	}
	
	
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_markasread', 'markasread_callback' );





function report_callback() {
	global $wpdb; // this is how you get access to the database
	$post = get_post($_POST[story_id]);
	$user_id = $post->post_author;
	$user = get_userdata($user_id );
	$cuser = wp_get_current_user();
	//delete_post_meta($post->ID, "stories_reported", $user_id);
	$id = get_post_meta( $post->ID,  "stories_reported_".$cuser->ID, true  );
	
	update_post_meta( $post->ID,  "stories_reported_".$cuser->ID , $_POST[reason]  );
	
	

	if(empty($id)){
		
		$admins = getAdmins();
		
		 $args = array(
			'sender_id' => $admins[0],
			'thread_id' => false,
			'recipients' => array( $user_id ),
			'subject' => 'Your Story Was Reported - Reason: '.$_POST[reason],
			'content' => 'Your story at '.get_permalink($post->ID).' was reported. We will review the report as soon as possible. If the report is unfounded, you will receive no notifcation and no further action will be taken. Otherwise, we will notify you if your story needs to be edited for re-publish.',
			'date_sent' => bp_core_current_time()
		 );
		
		$result = messages_new_message( $args );
		
		
		 $args = array(
			'sender_id' => $user_id,
			'thread_id' => false,
			'recipients' => $admins,
			'subject' => 'My Story Was Reported - Reason: '.$_POST[reason],
			'content' => 'My story at '.get_permalink($post->ID).' was reported. Please review my story as soon as possible. If the report is unfounded, please clear the report. Otherwise, please notify me if the story needs to be edited so you can review it again.',
			'date_sent' => bp_core_current_time()
		 );
		 $result = messages_new_message( $args );
	}
	
	
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_report', 'report_callback' );


function clearreport_callback() {
	global $wpdb; // this is how you get access to the database
	$post = get_post($_POST[story_id]);
	$user_id = $post->post_author;
	$user = get_userdata($user_id );
	$sql = "DELETE FROM ".$wpdb->prefix."postmeta WHERE meta_key LIKE '%stories_reported%' AND post_id = ". $post->ID; 	
	$wpdb->query($sql);
	
	delete_post_meta($post->ID, "stories_reported_reason");
	$my_post = array(
		  'ID'           => $post->ID,
		  'post_status'   => 'publish'
	 );
	wp_update_post( $my_post );
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_clearreport', 'clearreport_callback' );

function requestedit_callback() {
	global $wpdb; // this is how you get access to the database
	$post = get_post($_POST[story_id]);
	$user_id = $post->post_author;
	$user = get_userdata($user_id );
	$reason = get_post_meta( $post->ID,  "stories_report_reason", true  );
	if(empty($reason)){
		update_post_meta($post->ID, "stories_report_reason",$_POST[reason]);
		$my_post = array(
			  'ID'    => $post->ID,
			  'post_status'   => 'pending'
		  );
		wp_update_post( $my_post );
		$admins = getAdmins();
		$args = array(
				'sender_id' => $admins[0],
				'thread_id' => false,
				'recipients' => array( $user_id ),
				'subject' => 'Please Edit Your Story',
				'content' => 'Your story at '.get_permalink($post->ID).' was reported and upon review has been determined to require editing for the following reason:  '.$_POST[reason],
				'date_sent' => bp_core_current_time()
			 );
			
		$result = messages_new_message( $args );
	} else {
		update_post_meta($post->ID, "stories_reported_reason",$_POST[reason]);
		
	}
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_requestedit', 'requestedit_callback' );



function characterizeIt_callback() {
	global $wpdb; // this is how you get access to the database
	$post = get_post($_POST[story_id]);
	$user = wp_get_current_user(); 
	update_post_meta($post->ID, "stories_characterized_".$user->ID,$_POST[c]);
	
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_characterizeit', 'characterizeIt_callback' );


function shareIt_callback() {
	global $wpdb; // this is how you get access to the database
	$post = get_post($_POST[story_id]);
	$user = wp_get_current_user(); 
	$user_id = $user_id = get_current_user_id();
	$group_id = $_POST[group];
	
	
	
	$to = array();
	if($group_id == "friends"){
	 if ( bp_has_members( 'user_id=' . bp_loggedin_user_id() ) ) {
		while ( bp_group_members() ) : bp_group_the_member(); 
			$to[] = bp_get_member_user_id();
		$message = "Shared with friends!";
		endwhile;
		 
	 }
	 $args = array(
				'sender_id' => $user_id,
				'thread_id' => false,
				'recipients' => $to,
				'subject' => 'Shared a Story',
				'content' => 'You can view this story at '.get_permalink($post->ID),
				'date_sent' => bp_core_current_time()
			 );
			
		$result = messages_new_message( $args );
	 
	 	
	} else {
		
	
		
		if ( bp_group_has_members('group_id='.$group_id) ){
			
			while ( bp_group_members() ) : bp_group_the_member(); 
				$to[] = bp_get_group_member_id();
				$message = "Shared with Group!";
			endwhile;
			
			$result = groups_record_activity( array(
				'action' => $user->user_login.' shared a link   ',
				'content' => 'You can view this story at '.get_permalink($post->ID),
				'type' => 'new_group_share',
				'item_id' => $group_id,
				'secondary_item_id' => $user->ID,
				'hide_sitewide' => 0
			) );
			
		}	
	}
	
		update_post_meta( $post->ID, 'shared-with-group-'.$group_id, $user_id  );
	
	
	
	if($result){
		echo $message;
	} else {
		echo  "Nobody in this group other than you.";
	}
	
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_shareit', 'shareIt_callback' );

/*
like button styles
white
lightgray
gray
black
padded
drop
line
github
transparent
youtube
habr
heartcross
plusminus
google
greenred
large
elegant
disk
squarespace
slideshare
baidu
uwhite
ublack
uorange
ublue
ugreen
direct
homeshop
*/

function likebutton_callback(){
	$user_id = get_current_user_id();
	
	$temp = get_vote_counts($_POST[post]);
	$temp = explode(":",$temp);
	$total_likes = $temp[0];
	$total_dislikes = $temp[1];
	$voted = $value = get_user_meta($user_id, "fs_votes_".$_POST[post], true);
	?>
<span class="likebtn-wrapper lb-loaded lb-style-github  lb-popup-position-top lb-popup-style-light" data-lang="en" data-identifier="settings_color_scheme_github" data-theme="github"><span class="likebtn-button lb-like <? if($voted){ ?>lb-voted <? } ?>" id="lb-like-<? echo $_POST[post]; ?>"> <span onclick="LikeBtn.vote(1, <? echo $_POST[post]; ?>);" class="lb-a"><i class="lb-tt lb-tooltip-tt"><i class="lb-tt-lt"></i><i class="lb-tt-rt"></i><i class="lb-tt-m">I like this</i><i class="lb-tt-mu">Unlike</i> <i class="lb-tt-m2"></i> <i class="lb-tt-lb"></i> <i class="lb-tt-rb"></i> <i class="lb-tt-a"></i> </i> <span class="likebtn-icon lb-like-icon">&nbsp;</span> <span class="likebtn-label lb-like-label">Like</span></span> <span class="lb-count" data-count="<? echo $total_likes; ?>"><? echo $total_likes; ?></span></span> 
<!-- 
<span class="likebtn-button lb-dislike " id="lb-dislike-<? echo $_POST[post]; ?>"><span onclick="LikeBtn.vote(-1, <? echo $_POST[post]; ?>);" class="lb-a"><i class="lb-tt lb-tooltip-tt"><i class="lb-tt-lt"></i><i class="lb-tt-rt"></i><i class="lb-tt-m">I dislike this</i><i class="lb-tt-mu">Undislike</i><i class="lb-tt-m2"></i><i class="lb-tt-lb"></i><i class="lb-tt-rb"></i><i class="lb-tt-a"></i></i><span class="likebtn-icon lb-dislike-icon">&nbsp;</span></span><span class="lb-count" data-count="<? echo $total_dislikes; ?>"><? echo $total_dislikes; ?></span>
--> 
</span>
<?
	
	wp_die();
}


add_action( 'wp_ajax_nopriv_likebutton', 'likebutton_callback' );

add_action( 'wp_ajax_likebutton', 'likebutton_callback' );
function fsvote_callback(){
		$user_id = get_current_user_id();
		if($user_id == 0){
			echo "error";
			wp_die();	
		}
		$value = get_user_meta($user_id, "fs_votes_".$_POST[post], true);
		if($value == $_POST[i]){
			delete_user_meta( $user_id, 'fs_votes_'.$_POST[post]);
			$first = 1;
		} else {
			// NOTIFICATION HOOK myth_event_3 - check for first like
			
			$post_id = $_POST[i];
			$first = get_post_meta($post_id, "first_like_ever", true);
			
			update_user_meta( $user_id, 'fs_votes_'.$_POST[post], $_POST[i] );
		}
		
		echo get_vote_counts($_POST[post],$user_id);
		if(empty($first)){
				$msg = get_option('myth_event_3');
				sendNotify(get_option('myth_event_3_s'),$msg,$user_id,$post_id);
				update_post_meta( $post_id,'first_like_ever',1);	
			}
		wp_die();
}

function get_vote_counts($post_id,$user_id){
	 global $wpdb;
	 	
			
		$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key='fs_votes_".$post_id."' AND meta_value = 1";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$likes = sizeof($result);
		$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key='fs_votes_".$post_id."' AND meta_value = -1";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$dislikes = sizeof($result);
		return $likes.":".$dislikes;

}


add_action( 'wp_ajax_nopriv_fsvote', 'fsvote_callback' );
add_action( 'wp_ajax_fsvote', 'fsvote_callback' );



function activeTT(){
		if(!session_id()){
			session_start();
		}
		$_SESSION[timetravel] = $_SERVER['REMOTE_ADDR'];
		wp_die(); // this is required to terminate immediately and return a proper response

}


add_action( 'wp_ajax_activatett', 'activeTT' );

function deactiveTT(){
		if(!session_id()){
			session_start();
		}
		unset($_SESSION[timetravel]);
		wp_die(); // this is required to terminate immediately and return a proper response

}


add_action( 'wp_ajax_deactivatett', 'deactiveTT' );


function resetTerms(){
		 global $wpdb;
	 	$sql = "DELETE FROM  ".$wpdb->prefix."usermeta WHERE meta_key='retro_terms_date' OR  meta_key='retro_terms' ";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sql = "DELETE FROM  ".$wpdb->prefix."usermeta WHERE meta_key='retro_privacy_date' OR  meta_key='retro_privacy' ";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sql = "DELETE FROM  ".$wpdb->prefix."usermeta WHERE meta_key='retro_beta_date' OR  meta_key='retro_beta' ";
		$result = $wpdb->get_results($sql,ARRAY_A);
		wp_die(); // this is required to terminate immediately and return a proper response

}


add_action( 'wp_ajax_reset_terms', 'resetTerms' );

function resetPrivacy(){
		 global $wpdb;
	 	$sql = "DELETE FROM  ".$wpdb->prefix."usermeta WHERE meta_key='retro_privacy_date' OR  meta_key='retro_privacy' ";
		$result = $wpdb->get_results($sql,ARRAY_A);
		wp_die(); // this is required to terminate immediately and return a proper response

}

function setPrivacy(){
		 global $wpdb;
		 $post = get_post($post_id);
	 	 	$sql = "SELECT * FROM  ".$wpdb->prefix."users";
			$result = $wpdb->get_results($sql,ARRAY_A);
			foreach($result as $row){
				update_user_meta( $row[ID], "retro_privacy_date", date("Y-m-d"));
				update_user_meta( $row[ID], "retro_terms_date", date("Y-m-d"));
				update_user_meta( $row[ID], "retro_beta_date", date("Y-m-d"));
				
				update_user_meta( $row[ID], "retro_privacy",1);
				update_user_meta( $row[ID], "retro_terms",1);
				update_user_meta( $row[ID], "retro_beta",1);
				
			}
	 	wp_die();

}

function setOptins(){
		$i = get_user_meta($_POST[id], "retro_opt", true);
		if($i > 0){
			 delete_user_meta($_POST[id], "retro_opt");
		} else {
			update_user_meta($_POST[id], "retro_opt",1);
		}
		wp_die();
}

add_action( 'wp_ajax_set_privacy', 'setPrivacy' );
add_action( 'wp_ajax_reset_privacy', 'resetPrivacy' );
add_action( 'wp_ajax_set_optins', 'setOptins' );

function setExtTermsAgreements(){
	$t = get_user_meta( $_POST[id], "retro_".$_POST[type]."_date", true);
	
	if($t > 0){
		 delete_user_meta($_POST[id], "retro_".$_POST[type]);	
		 delete_user_meta($_POST[id], "retro_".$_POST[type]."_date");	
		 echo " ";
	} else {
		 update_user_meta($_POST[id], "retro_".$_POST[type],1);	
		 update_user_meta($_POST[id], "retro_".$_POST[type]."_date", date("Y-m-d"));	
		 echo date("Y-m-d");
	}
	wp_die();	
}

function setExtMeta(){
	update_user_meta($_POST[id], $_POST[key],$_POST[value]);	
	wp_die();	
}


add_action( 'wp_ajax_setExtTermsAgreements', 'setExtTermsAgreements' );
add_action( 'wp_ajax_setExtMeta', 'setExtMeta' );

function filterStories_callback(){
	global $wpdb;
	$user_id = get_current_user_id();
	if(!empty($_POST[wpid])){
		// if prompt-based filter
		$sql = "SELECT * FROM ".$wpdb->prefix."usermeta LEFT JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."usermeta.meta_value WHERE meta_key = 'stories_prompted_{$_POST[wpid]}' AND post_type = 'stories'"; 
		$result = $wpdb->get_results($sql);
		
		$thePosts = array();
		$total = sizeof($result);
		foreach($result as $row){
			$thePosts[$row->meta_value] = $row->meta_value;
		}
	} else {
		// if shared-based filter
		$thePosts = explode(",",$_POST['post__in']);	
		$total = sizeof($thePosts);
	}
	if($total > 0){
	
	wp_reset_query();
	
	$paged = ($_POST[page]) ? $_POST[page] : 1;
	
	
	$qarray = array(
					'post__in' => $thePosts,
	 				'post_type' => 'stories', 
					'post_status' => 'publish', 
					'posts_per_page' => 5 , 
					'paged' => $paged );	
					
					
	// Sorting
	// 0 = date DESC
	// 1 = date ASC
	// 2 = most popular first
	// 3 = random
	
	
	switch($_POST[sorter]){
		case 0:
			$qarray['orderby'] = 'date';
			$qarray['order'] = 'DESC';
			break;
		case 1:
			$qarray['orderby'] = 'date';
			$qarray['order'] = 'ASC';
			break;
		case 2:
			$qarray['orderby'] = 'comment_count';
			$qarray['order'] = 'DESC';
			break;
		case 3:
			$qarray['orderby'] = 'rand';
			
			break;
		default: 
			$qarray['orderby'] = 'date';
			$qarray['order'] = 'DESC';
			break;
	}
	if(sizeof($_POST[values]) > 0){
		$meta_array = array( 'relation' => 'OR');
		//$meta_array = array();
		$f_array = array();
		$display = array();
		foreach($_POST[values] as $key=>$value){
			switch($value){
				case "read":
					$meta_array[] =  array('value' => "1", 'compare' => '=', 'key' => 'mark_as_read'.$user_id);
					$display[0][] = "<strong>I've read</strong>";
					break;
				case "follow":
					$following = explode(",", bp_get_following_ids(array( 'user_id' => $user_id )));
					$qarray[author__in] = $following; 
					$display[1][] = " <strong>by people I follow</strong>";
					break;
				case "notread":
					// instead of setting post__not_in, we remove what is post__in
					$query ="SELECT * FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'mark_as_read{$user_id}' AND meta_value = 1";
					$output = $wpdb->get_results($query,ARRAY_A);
					foreach($output as $row) {
						unset($qarray[post__in][$row[post_id]]);
					}
					$display[0][] = "<strong>I haven't read</strong>";
					break;
				default:	
					$display[0][] = "<strong>$value</strong>";
					$meta_array[] =  array('value' => $value);
					break;
			}
			
		}
	
		$qarray['meta_query'] =  $meta_array;
		if(sizeof($display[0]) > 0){
			$text = implode(" or ",$display[0]);
		}
		if($display[1][0] != "" && $text != ""){
				$display[1][0] = " AND" . $display[1][0];
		}
		?>
<div class="storyWidget"> Showing posts that are <? echo ($text != "")?$text:""; ?><? echo ($display[1][0] != "")?$display[1][0]:""; ?>. </div>
<?
	
	}
	
	 // http://codex.wordpress.org/Class_Reference/WP_Query
	
	$loop = new WP_Query($qarray);
				
		
				// ive read = key = mark_as_read{userid} = 1
				// havent read  = key = mark_as_read{userid} = null
				// people I follow - first grab array, then array of authors
				// for characerizations, array of meta values equaling characterizations
				
					// THE QUERY
			//$loop = new WP_Query( array( 'post__in' => $thePosts,'post_type' => 'stories', 'orderby'=> 'date','order'   => 'DESC', 'posts_per_page' => 10 , 'paged' => $paged) );
			
			$a = 0;
		
			?>
<?php 
$pagecount = 0;
while ( $loop->have_posts() ){
	$a++;
	$loop->the_post(); 
	$firsttime = get_post_meta( get_the_ID(), 'first_time' , true);
	// was if(storyHasPermission($post){
		
	$sql = "SELECT * FROM  " . $wpdb->prefix . "usermeta WHERE user_id = " . get_the_author_meta('ID') . " AND meta_key LIKE 'stories_prompted_%' AND meta_value = " . get_the_ID();
	
	$result = $wpdb->get_results($sql,ARRAY_A);

	foreach($result as $prompt){
		$temp = explode("_", $prompt[meta_key]);
		$p = $temp[2];
		$prompted_post = get_post($p);
		$golive = $prompted_post->stories_embargo_until;
		
	}
		
	?>
<? if(!empty($firsttime) || $golive <= date("Y-m-d")){ 
	$pagecount++;
 ?>
	<? get_template_part( 'content', 'story' ); ?>
	<? }  ?>
<? } ?>
<? 
	  
	  if($a == 0){
		?>
<div>Sorry. I cannot find what you are looking for.</div>
<?  
	  }
	  
	  $post = get_post($_POST[wpid]);
$slug = $post->post_name;

	 	$older = $paged + 1;
		 $newer = $paged - 1;
		
		  ?>
<? if(($paged < $loop->max_num_pages) && $pagecount >= 5){ ?>
<span class="next"><a href="javascript:apage(<? echo $older; ?>);">&lt;&lt; Older Stories</a></span>
<? } ?>
<? if($newer > 0){ ?>
<span class="prev"><a href="javascript:apage(<? echo $newer; ?>);">Newer Stories &gt;&gt;</a></span>
<? } ?>
<div class="clear"></div>
<? if($loop->max_num_pages > 1 && $pagecount >= 5){ ?>
<span class="page">Page <? echo $paged; ?></span>
<? } ?>
<br>
<? } else { ?>
<p> There are no stories. </p>
<? }  ?>
<?
	   wp_die();
	
}

add_action( 'wp_ajax_nopriv_filterStories', 'filterStories_callback' );
add_action( 'wp_ajax_filterStories', 'filterStories_callback' );

function voteit_callback(){
	global $wpdb;
	$user_id = get_current_user_id();
	$post_id = $_POST[post_id];
	$answer = $_POST[answer_id];
	$voted = get_user_meta( $user_id , 'voted_'.$post_id );
	if(!$voted){
		$votes = get_post_meta( $post_id , 'votes_'.$answer , true );
		$votes ++;
		
		update_post_meta( $post_id, 'votes_'.$answer, $votes );
		update_user_meta( $user_id, 'voted_'.$post_id, "true" );
		echo show_votes("","");
	}
	wp_die();
}

add_action( 'wp_ajax_voteit', 'voteit_callback' );

