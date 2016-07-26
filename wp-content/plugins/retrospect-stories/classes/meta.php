<?

/*
 * 
 * 
 *  
 * Meta Functions
 * 
 * 
 *
 */
 
 
function showAddThis($post){
	 //if(current_user_can("administrator")){
	 ?><div class="addthis_sharing_toolbox"><p id="lsbuttons"><em>Loading Share Buttons...</em></p></div>
<?	 
	 //}
	 $add_title = $post->post_title;
	 $add_permalink = get_permalink($post->ID);
	 $anon = get_post_meta($post->ID, 'stories_is_anonymous', true);
	 if ($anon) {
		 $author = "Anonymous";
	 } else {
		 $author = strip_tags(get_the_author_posts_link());
	 }
	 ?>
     <script>
	 
jQuery(document).ready(function($){
		var addThisInv = setInterval(function(){
		if(jQuery("a.at-share-btn.at-svc-twitter").length > 0){
				jQuery("a.at-share-btn.at-svc-twitter").after('<div onclick="sendAddEmail()"  tabindex="1" style="display:inline-block;cursor:pointer;" class="at-svc-email"><span class="at4-visually-hidden">Share to Email</span><span class="at-icon-wrapper" style="line-height: 20px; height: 20px; width: 20px; background-color: rgb(132, 132, 132);"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" class="at-icon at-icon-email" style="width: 20px; height: 20px;"><g><g fill-rule="evenodd"></g><path d="M27 22.757c0 1.24-.988 2.243-2.19 2.243H7.19C5.98 25 5 23.994 5 22.757V13.67c0-.556.39-.773.855-.496l8.78 5.238c.782.467 1.95.467 2.73 0l8.78-5.238c.472-.28.855-.063.855.495v9.087z"></path><path d="M27 9.243C27 8.006 26.02 7 24.81 7H7.19C5.988 7 5 8.004 5 9.243v.465c0 .554.385 1.232.857 1.514l9.61 5.733c.267.16.8.16 1.067 0l9.61-5.733c.473-.283.856-.96.856-1.514v-.465z"></path></g></svg></span></div>');
				jQuery("#lsbuttons").remove();
				clearInterval(addThisInv);
		}
	},100);
});

function sendAddEmail(){
	var body = 'Check out this story, <? echo $add_title; ?> by  <? echo $author; ?>, on Retrospect. Retrospect is a new website where baby boomers (and those who love them) can capture and share their stories and memories. \n\n<? echo $add_permalink; ?>';
	body = encodeURIComponent (body);
	window.location='mailto:?subject=<? echo $add_title; ?> | Retrospect&body=' + body;
	return false;
}
</script>
<?
 
}
  function showVotes($post){
		global $wpdb;

		$user = wp_get_current_user(); 
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	wp_enqueue_script('stories-scripts2', $pluginfolder . '/scripts.js');
	?>
<table class="form-table">
  <tbody>
    <tr valign="top">
      <th scope="row"><label for="stories_some_text">Active For Prompt</label></th>
      <td><?
     $loop = new WP_Query( array( 'posts_per_page' => -1, 'post_type' => 'prompts', 'meta_key' => 'stories_embargo_until','orderby'=>'meta_value','order' => 'ASC') );
		$prompt = get_post_meta( $post->ID , 'active_prompt' , true );
		?>
        <select name="active_prompt">
          <option  value="">-- Choose a Prompt --</option>
          <?php 
  $remember = array();
  while ( $loop->have_posts() ) : $loop->the_post();
  	$pid = get_the_ID();
  	$date = get_post_meta( $pid , 'stories_embargo_until' , true );
	
	?>
          <option <? if($pid == $prompt){ ?>selected=""<? } ?> value="<? echo $pid; ?>"><? echo the_title();  ?> - <? echo $date; ?></option>
          <?php 
   endwhile; 
   ?>
        </select></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="stories_some_text">Answer 1</label></th>
      <td><input style="width:100%" type="text" name="answer_1" value="<?php echo esc_attr( get_post_meta( $post->ID , 'answer_1' , true )  ); ?>" required="" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="stories_some_text">Answer 2</label></th>
      <td><input style="width:100%"  type="text" name="answer_2" value="<?php echo esc_attr( get_post_meta( $post->ID , 'answer_2' , true )  ); ?>" required="" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="stories_some_text">Answer 3</label></th>
      <td><input style="width:100%"  type="text" name="answer_3" value="<?php echo esc_attr( get_post_meta( $post->ID , 'answer_3' , true )  ); ?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="stories_some_text">Answer 4</label></th>
      <td><input style="width:100%"  type="text" name="answer_4" value="<?php echo esc_attr( get_post_meta( $post->ID , 'answer_4' , true )  ); ?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="stories_some_text">Answer 5</label></th>
      <td><input style="width:100%"  type="text" name="answer_5" value="<?php echo esc_attr( get_post_meta( $post->ID , 'answer_5' , true )  ); ?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="stories_some_text">Answer 6</label></th>
      <td><input style="width:100%"  type="text" name="answer_6" value="<?php echo esc_attr( get_post_meta( $post->ID , 'answer_6' , true )  ); ?>" /></td>
    </tr>
  </tbody>
</table>
<?
}
 
 
 function likesComments($postid){
	// first we have to get list of stories in the current prmopt
	global $wpdb;
			
			$comments = get_comment_count($postid);
			
			$comments = $comments[approved];
			$sql = "SELECT *, SUM(meta_value) as count FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'fs_votes_".$postid."' AND meta_value = 1";
			$result = $wpdb->get_results($sql,ARRAY_A);
			echo  $comments ;
			
			
		
	
	
}

function ratingsystem($post_id,$single, $inline = false){
	global $wpdb;
	
	$post = get_post($post_id);
	$user_id = get_current_user_id();
	
	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	
	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = $post->ID AND meta_key = 'stories_rating_".$user_id."'";
	$result = $wpdb->get_results($sql,ARRAY_A);
	$assoc = $result[0];
	$myRating = $assoc[meta_value];
	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = $post->ID AND meta_key LIKE 'stories_rating%'";
	$result = $wpdb->get_results($sql,ARRAY_A);
	$totalRating = 0;
	foreach($result as $ratings){
		$totalRating += $ratings[meta_value];
	}
	$total = sizeof($result);
	if($total > 0){
		$totalRating = $totalRating / $total;
	} else {
		$totalRating = 0;
	}
	
	
	?>
<? if(current_user_can("administrator")){ ?>
<div class="theirRating" style="<? if($inline){?>display:inline-block;" <? } ?>">
  <? if($single == false){ ?>
  <? } ?>
  <div id="rate1" class="rating">
    <input type="hidden" class="therating" value="<? echo ceil($totalRating); ?>" />
    <div  class="star"></div>
    <div  class="star"></div>
    <div  class="star"></div>
    <div  class="star"></div>
    <div  class="star"></div>
    <div style="font-size:12px; display:inline-block;">&nbsp;&nbsp;(<? echo $total; ?> Ratings)</div>
  </div>
</div>
<? } ?>
<?
if(empty($user_id)){
		?>
<div class="myRating">You must be logged in to rate.</div>
<?
		return false;	
	}
?>
<? if($myRating == ""){ ?>
<? if($single == false){ ?>
<div class="myRating">Rate This Story
  <div id="rate2" class="rating">
    <input type="hidden" class="therating" value="<? echo $myRating; ?>" />
    <div id="star1" class="star"><a id="1">1</a></div>
    <div id="star2" class="star"><a id="2">2</a></div>
    <div id="star3" class="star"><a id="3">3</a></div>
    <div id="star4" class="star"><a id="4">4</a></div>
    <div id="star5" class="star"><a id="5">5</a></div>
    <input type="hidden" id="rpid" value="<? echo $post->ID; ?>" />
  </div>
  <br>
</div>
<? } ?>
<? } ?>
<input type="hidden" id="rpid" value="<? echo $post->ID; ?>" />
<?
}

function readThis($post_id){
	global $wpdb;
	$post = get_post($post_id);
	$user_id = get_current_user_id();
	$c = get_post_meta($post->ID, "mark_as_read".$user_id,true);
	
	
	if($user_id > 0){
		
		
	?>
<div class="mark_as_read">
  <label>
    <input <? if($c != ""){ ?>checked=""<? } ?> type="checkbox" id="stories_mark_as_read" value="<? echo $post->ID; ?>">
    mark as read </label>
</div>
<?
}
	
}

function reportSystem($post_id){
	global $wpdb;
	$post = get_post($post_id);
	$user_id = get_current_user_id();
	?>
<label class="flaglink"  title="(spam / not a story / libelous / private / offensive)">
  <input type="checkbox" value="<? echo $post->ID; ?>">
  flag as inappropriate </label>
<select id="preason" style="display:none;">
  <option value="">Select a Reason...</option>
  <option value="">Nevermind</option>
  <?
	$ch = explode(",",get_option('myth_prohibited'));
	foreach($ch as $value){
		$value = trim($value);
		?>
  <option value="<? echo $value; ?>"><? echo $value; ?></option>
  <?
	}
	?>
</select>
<?
	
}

function describeSystem($post_id){
	global $wpdb;
	$post = get_post($post_id);
	$user_id = get_current_user_id();
	if(empty($user_id)){
		return false;	
	}
	$c = get_post_meta($post->ID, "stories_characterized_".$user_id,true);
	$c = explode(",",$c);
	
	?>
<div class="storyWidget hideInPrint"><strong>I found this story to be</strong>: <br>
  <?
	$ch = explode(",",get_option('myth_charaterization'));
	foreach($ch as $key=>$value){
		$value = trim($value);
		if(in_array(trim($value),$c)){ 
		
		?>
  <label class="charlabel">
    <input type="checkbox" class="characterize" name="character_<? echo $post_id; ?>_<? echo $value; ?>" checked="" value="<? echo $value; ?>">
    <? echo $value; ?></label>
  <?
		} else {
		?>
  <label  class="charlabel">
    <input type="checkbox"  class="characterize"  name="character_<? echo $post_id; ?>_<? echo $value; ?>" value="<? echo $value; ?>">
    <? echo $value; ?></label>
  <?
		}
	}
	?>
</div>
<?
	
}

function shareSystem($post_id){
	global $wpdb;
	$post = get_post($post_id);
	$user_id = get_current_user_id();
	if(empty($user_id)){
		return false;	
	}
	?>
<?php if ( bp_has_groups('user_id=' . $user_id)){ ?>
<div class="storyWidget hideInPrint">
  <div style="float:left"><strong>Share With</strong>: </div>
  <ul style=" ">
    <li ><br>
    </li>
    <!--
      <li class="sharemes" style="margin-left:61px;display:none;"> <label><input type="checkbox" class="sharecb" value="friends" />&nbsp;My Friends</label></li>-->
    <?php while ( bp_groups() ) : bp_the_group(); ?>
    <li class="sharemes" style="margin-left:61px;">
      <label>
      <?
	  $group_id = bp_get_group_id();
	  $shared = get_post_meta( $post->ID, 'shared-with-group-'.$group_id, true  );
	  if(empty($shared)){
	  ?>
        <input type="checkbox" class="sharecb" value="<?php echo $group_id ?>" />
        &nbsp;
        <?php bp_group_name() ?>
        <? } else { ?>
         <input type="checkbox" class="" checked='' disabled='' value="" />
        Already shared with <?php bp_group_name() ?>
        <? } ?>
      </label>
    </li>
    <?php endwhile; ?>
  </ul>
  <?php do_action( 'bp_after_groups_loop' ) ?>
    <input type="hidden" id="rpid" value="<? echo $post->ID; ?>" />
</div>
<div style="clear:both;"></div>
<?php } else { ?>
<br>
<div class="clear"></div>
<div style="font-size:16px;"> </div>
<div style="clear:both;"></div>
<?php } ?>
<?
	  
	
}

function storyHasPermission($post){
	
	
	
	return true;
	 global $wpdb,  $embargoed, $embargoedate;  
	 $embargoed = false;
	 $embargoedate = "";
	
	
	 $sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key LIKE 'stories_prompted_%' AND meta_value = ".$post->ID;
	 $result = $wpdb->get_results($sql,ARRAY_A);
	 foreach($result as $prompt){
		$temp = explode("_",$prompt[meta_key]);
		$prompt = $temp[2];
		if($prompt){
			$prompt = get_post($prompt);
			$embargoHide = $embargoedate = get_post_meta( $prompt->ID , 'stories_hide_until' , true );  
			break;
		
		}
	 }
	
	
	if($post->stories_hide_until < date("Y-m-d") || (get_option('myth_embargo_debug') == "on" && current_user_can('administrator'))){
		if($embargoHide == ""){
		return true;	
		} else {
			if($embargoHide < date("Y-m-d")){
				if(get_option('myth_embargo_debug') == "on" && current_user_can('administrator')){
					$embargoed = true;
					return true;	
				} else {
					$embargoed = true;
					return false;		
				}
			} else {
				$embargoed = true;
				return false;
			}
		}
	} else {
		return false;
	}
		
}


