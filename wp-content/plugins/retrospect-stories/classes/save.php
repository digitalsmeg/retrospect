<?

/*
 * 
 * 
 *  
 * Story/Prompt Save Functions
 * 
 * 
 *
 */


function profile_save_hook(){
	if($_POST){
		$user_id = bp_displayed_user_id();
		if(sizeof($_POST[gender]) > 0){
			if(!empty($_POST[gender][1])){
				$_POST[gender][0] = $_POST[gender][1];
			}
			update_user_meta( $user_id, 'gender', $_POST[gender][0] ) ;
		}
		if(!empty($_POST[relationship_status])){
			update_user_meta( $user_id, 'relationship_status', $_POST[relationship_status] ) ;
		}
					
	}
}
add_action('init', 'profile_save_hook');

function myplugin_save_meta_box_data( $post_id ) {
	global $wpdb;
	if ( wp_is_post_revision( $post_id ) ){
		return;
	}
	//$sql = "DELETE FROM ".$wpdb->prefix."postmeta WHERE meta_key LIKE '%prompts_%'"; 	
	//$wpdb->query($sql);
	
	//https://codex.buddypress.org/developer/function-examples/bp_activity_add/

	if($_POST[post_type] == "stories"){
		if($_POST[publish] == "Submit for Review"){
			$args = array("id"=>"","action"=>"Submitted a story for review: '".$_POST[post_title]."'","content"=>"You can view this story <a href='".get_permalink($post_id)."'>here</a> when it is ready.","user_id"=>$_POST[post_author],"primary_link"=>"","type"=>"New Story","component"=>"activity");
				
		}
		
		if($_POST[publish] == "Publish"){
			// only post to activity feed if its not anonymous
			if(empty($_POST['is_anonymous'])){
				$args = array("id"=>"","action"=>"Posted a story: '".$_POST[post_title]."'","content"=>"You can view this story <a href='".get_permalink($post_id)."'>here</a>.","user_id"=>$_POST[post_author],"primary_link"=>"","type"=>"Published Story","component"=>"activity");
			}
			
		}
		if($_POST[save] == "Update"){
			// only post to activity feed if its not anonymous
			if(empty($_POST['is_anonymous'])){
			$args = array("id"=>"","action"=>"Updated a story: '".$_POST[post_title]."'","content"=>"You can view this story <a href='".get_permalink($post_id)."'>here</a>.","user_id"=>$_POST[post_author],"primary_link"=>"","type"=>"Updated Story","component"=>"activity");
			}
		}
	}
	
	if($_POST[post_type] == "prompts"){
		if($_POST[publish] == "Submit for Review"){
			$args = array("id"=>"","action"=>"Submitted a writing prompt for review: '".$_POST[post_title]."'","content"=>"You can view this story <a href='".get_permalink($post_id)."'>here</a> when it is ready.","user_id"=>$_POST[post_author],"primary_link"=>"","type"=>"New Prompt","component"=>"activity");
				
		}
		
		if($_POST[publish] == "Publish"){
			//$args = array("id"=>"","action"=>"Posted a writing prompt: '".$_POST[post_title]."'","content"=>"You can view this story <a href='".get_permalink($post_id)."'>here</a>.","user_id"=>$_POST[post_author],"primary_link"=>"","type"=>"Published Prompt","component"=>"activity");
				
		}
		
		if($_POST[save] == "Update"){
			
				//$args = array("id"=>"","action"=>"Updated a writing prompt: '".$_POST[post_title]."'","content"=>"You can view this story <a href='".get_permalink($post_id)."'>here</a>.","user_id"=>$_POST[post_author],"primary_link"=>"","type"=>"Updated Prompt","component"=>"activity");
			
		}
	}
	
	bp_activity_add( $args );
	if( $_POST[post_type] == "prompts" && !empty( $_POST['stories_featured'])){
			query("DELETE FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_featured'");
	}
	
	if($_POST[post_type] == "stories" && isset($_POST[changepromptto])){
	$post = get_post( $post_id);
		$sql = "DELETE FROM ".$wpdb->prefix."usermeta WHERE user_id =  $post->post_author AND meta_key LIKE '%stories_prompted_%' AND meta_value = $post_id"; 	
		$wpdb->query($sql);
		$sql = "INSERT INTO ".$wpdb->prefix."usermeta VALUES ('', $post->post_author, 'stories_prompted_".$_POST[changepromptto]."',$post_id)"; 	
		$wpdb->query($sql);
		//update_user_meta( $post->post_author, 'stories_prompted_'.$_POST[changepromptto], $post_id );		
	}
	$post = get_post( $post_id);
	$permalink = get_permalink($post_id);
	
	if($_POST[post_type] == "stories" || $_POST[post_type] == "prompts"){
		
		if(empty( $_POST['is_anonymous'])){
			 $_POST['is_anonymous'] = 0;	
		} else {
			$admins = getAdmins();
			
			$user = get_user_by( "id", $_POST[post_author] );
			if($_POST[save] == "Update"){
				$arguments = array(
					'sender_id' => $admins[0],
					'thread_id' => false,
					'recipients' => array( $user->ID ),
					'subject' => 'You are Anonymous',
					'content' => 'Your story at '.$permalink.' was set to anonymous. Please check your activity feed at http://'.$_SERVER['HTTP_HOST'].'/members/'.$user->user_nicename.'/activity/ to see if you need to delete any prior entries.',
					'date_sent' => bp_core_current_time()
				 );
			
				$result = messages_new_message( $arguments );
			}
		}
		$stories_tags = sanitize_text_field( $_POST['stories_tags'] );
		$stories_some_setting = ( $_POST['stories_some_setting'] );
		$stories_hide_until = sanitize_text_field( $_POST['stories_hide_until'] );
		$stories_embargo_until = sanitize_text_field( $_POST['stories_embargo_until'] );
		$stories_prompt_id = $_POST['stories_prompt_id'];
		$stories_featured = $_POST['stories_featured'];
		$stories_featured_story = $_POST['stories_featured_story'];
		$stories_is_anonymous = $_POST['is_anonymous'];
		// delete all other featured?
	
		update_post_meta( $post_id, 'stories_tags', $stories_tags );
		//update_post_meta( $post_id, 'stories_featured', $stories_featured );
		if(current_user_can('administrator')){
			if($stories_featured_story == "on"){
				update_post_meta( $_POST[changepromptto], 'stories_featured_story', $post_id );
			} else {
				// if we set a story as featured then store the assigned prompt to var
				$stories_featured_story = 	$_POST[changepromptto];	
				// delete any featured referenced for other stories
				$sql = "DELETE FROM ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_featured_story' AND  meta_value = $post_id"; 
				$wpdb->query($sql);		
			}
		}
		update_post_meta( $post_id, 'stories_is_anonymous', $stories_is_anonymous );
		update_post_meta( $post_id, 'stories_some_setting', $stories_some_setting );
		update_post_meta( $post_id, 'stories_hide_until', $stories_hide_until );
		update_post_meta( $post_id, 'stories_embargo_until', $stories_embargo_until );
		update_post_meta( $post_id, 'stories_prompt_id', $stories_prompt_id );
		if($_POST[post_type] == "stories"){
			
			if(!empty($_POST[stories_response])){
				$p = get_post($_POST[stories_response]);
				$a = get_post($post_id);
				$u = get_userdata($a->post_author);
				$msg = get_option('myth_event_6');
				$msg = str_replace('{$parent}',$p->post_title,$msg);
				if(empty($msg)){
					$msg = $u->display_name." has written a story in response to your story, '".$p->post_title."'";
				}
				$notify = bp_get_user_meta( $p->post_author, 'notification_story_response', true );
				sendNotify( get_option('myth_event_6_s'),$msg,$p->post_author,$post_id, $notify);
				
				update_post_meta( $post_id, 'stories_response', $_POST[stories_response] );
			}
			
			if($_POST[post_status] == "publish" ){
				//update_post_meta( get_the_ID(), 'first_time', $golive  );
				$date = get_post_meta( $_POST[changepromptto] , 'stories_embargo_until' , true );
				$today = date("Y-m-d");
				
				// if its promptless we change to the date it was published
				if(empty($_POST[changepromptto])){
					$date = $post->post_date;	
				}
				
				// but if they change to a prompt we update accodting to that prompt
				// if the go live date is less than today. publish date is today
				if($date < $today){
					$youCanNotify = true;
					update_post_meta( $post_id, 'first_time', $today );
				} else {
					// else its when the prompt goes live
					$youCanNotify = false;
					update_post_meta( $post_id, 'first_time', $date );
					
				}
			}
			
		}
		
		
		
		if(!empty($_POST[first_time])){
			update_post_meta( $post_id, 'first_time', $_POST[first_time] );	
		}
	}
	
	if($_POST[publish] != "Publish"){
		//delete_post_meta( $post_id, 'first_time');
	}
	
	
	if($_POST[post_type] == "votes"){
		
		
		$answer_1 = sanitize_text_field( $_POST['answer_1'] );
		$answer_2 = sanitize_text_field( $_POST['answer_2'] );
		$answer_3 = sanitize_text_field( $_POST['answer_3'] );
		$answer_4 = sanitize_text_field( $_POST['answer_4'] );
		$answer_5 = sanitize_text_field( $_POST['answer_5'] );
		$answer_6 = sanitize_text_field( $_POST['answer_6'] );
	
	
		update_post_meta( $post_id, 'answer_1', $answer_1 );
		update_post_meta( $post_id, 'answer_2', $answer_2 );
		update_post_meta( $post_id, 'answer_3', $answer_3 );
		update_post_meta( $post_id, 'answer_4', $answer_4 );
		update_post_meta( $post_id, 'answer_5', $answer_5 );
		update_post_meta( $post_id, 'answer_6', $answer_6 );
		update_post_meta( $post_id, 'active_prompt', $_POST['active_prompt'] );
		
	}
	
	if ( ! wp_is_post_revision( $post_id ) ){
	
		// unhook this function so it doesn't loop infinitely
		remove_action('save_post', 'myplugin_save_meta_box_data');
	
		if($_POST[post_type] == "stories"){
			$post_name = sanitize_title($_POST[post_title]);
			wp_update_post(array(  'ID'  => $post_id, 'post_name'   => $post_name));
		}

		// re-hook this function
		add_action('save_post', 'myplugin_save_meta_box_data');
	}
	
	
	// NOTIFICATION HOOK myth_event_1 (1st), myth_event_2(5th,10, every 10), myth_event_4 featured
	if($_POST[post_type] == "stories"){
		$current_user = wp_get_current_user(); 
		$p = get_post($post_id);	
		
		$author = get_userdata($p->post_author);
		// measure for being set as featured
		if($stories_featured_story == "on"){
			$msg = get_option('myth_event_4');
			$notify = bp_get_user_meta( $p->post_author, 'notification_story_featured', true ) ;
			sendNotify( get_option('myth_event_4_s'),$msg,$p->post_author,$post_id,$notify);
		}
		
		// message for number of stories
		$sql = "SELECT * FROM  ".$wpdb->prefix."posts WHERE post_author = $p->post_author AND  post_type = 'stories' AND post_status = 'publish'";
		$result = $wpdb->get_results($sql,ARRAY_A);
	 	 $count = sizeof($result);
		
		// count query
		// SELECT Count(*) as count, post_author FROM  wp_posts WHERE post_type = 'stories' AND post_status = 'publish' GROUP BY post_author
		
		
		// shows usermta records for revisions that we need to get rid of by umeta_id
		$sql = "SELECT * 
		FROM  `wp_usermeta` 
		LEFT JOIN wp_posts ON wp_posts.ID = wp_usermeta.meta_value
		WHERE meta_key LIKE  'stories_prompted_%'
		AND post_type !=  'stories'";
		/*
		// deletes all irrelvant usermeta
		$result = $wpdb->get_results($sql,ARRAY_A);
		foreach($result as $row){
			$sql = "DELETE FROM wp_usermeta WHERE umeta_id = $row[umeta_id];<br>";	
			echo $sql;
		}
		*/		
		// message for 5th story only if published by author
		if($p->post_author == 122){
			$count = 80;
		}
		if($current_user->ID == $p->post_author){
		
			
			if($count == 5){
					$count = $count."th";
					$test = get_user_meta($current_user->ID, $count."_story_ever", true);
					if(empty($test)){
						$msg = get_option('myth_event_2');
						$title = get_option('myth_event_2_s');
						
						$msg = str_replace('{$count}',$count,$msg);
						$msg = str_replace('{$title}',$p->post_title,$msg);
						
						$notify = bp_get_user_meta( $p->post_author, 'notification_story_publishes', true ) ;
						
						$title = str_replace('{$count}',$count,$title);
						$title = str_replace('{$title}',$p->title,$title);
						sendNotify($title,$msg,$p->post_author,$post_id, $notify);
						update_user_meta($current_user->ID, $count."_story_ever", 1);
					}
						
					
			}
		}
		// message for divisible by 10th story only if published by author
		if($current_user->ID == $p->post_author){
		
			
			if($count%10 == 0 && $count > 5){
					$count = $count."th";
					$test = get_user_meta($current_user->ID, $count."_story_ever", true);
					if(empty($test)){
						$msg = get_option('myth_event_2');
						$title = get_option('myth_event_2_s');
						
						$msg = str_replace('{$count}',$count,$msg);
						$msg = str_replace('{$title}',$p->post_title,$msg);
						
						$notify = bp_get_user_meta( $p->post_author, 'notification_story_publishes', true ) ;
						
						$title = str_replace('{$count}',$count,$title);
						$title = str_replace('{$title}',$p->title,$title);
						sendNotify($title,$msg,$p->post_author,$post_id, $notify);
						update_user_meta($current_user->ID, $count."_story_ever", 1);
					}
						
					
			}
		}
		
		
		// message for first story only if published by author
		
		if($current_user->ID == $p->post_author && $p->post_status == 'publish' && $_POST[save] != "Update"){
			$first = get_user_meta($p->post_author, "first_story_ever", true);
			if(empty($first)){
					$msg = get_option('myth_event_1');
					$notify = bp_get_user_meta( $p->post_author, 'notification_story_publishes', true ) ;
					sendNotify(get_option('myth_event_1_s'),$msg,$p->post_author,$post_id,$notify);
					update_user_meta( $p->post_author,'first_story_ever',1);	
			}
			
			
			# this function was moved to notifcations.php and runs daily
			/*
			$following = explode(",", bp_get_follower_ids(array( 'user_id' => $p->post_author )));
			foreach($following as $follower){
				$notify = bp_get_user_meta( $follower, 'notification_story_follow_story', true );
				$user= get_userdata($p->post_author);
				$anon = get_post_meta($p->ID, 'stories_is_anonymous', true);
				if(!$anon && $youCanNotify == true){
					sendNotify("Follower Story", $user->display_name." posted a story.",$follower,$post_id,$notify);
				}
			}
			*/
			
		}
			
		
	}
}
add_action( 'save_post', 'myplugin_save_meta_box_data' );


// Saves the newly registered BP user account to the Parse DB.
add_action('bp_core_signup_user', 'saveOptTerms', 10, 5);

function saveOptTerms($user_id, $user_login, $user_password, $user_email, $user_meta) {
	global $wpdb;
	// the terms are already set
	if($_POST[retro_terms]){
		$option[retro_terms] = 1;
	} else {
		$option[retro_terms] = 0;
	}
	if($_POST[retro_privacy]){
		$option[retro_privacy] = 1;
	} else {
		$option[retro_privacy] = 0;
	}
	if($_POST[retro_beta]){
		$option[retro_beta] = 1;
	} else {
		$option[retro_beta] = 0;
	}
	if($_POST[retro_opt]){
		$option[retro_opt] = 1;
	} else {
		$option[retro_opt] = 0;
	}
	
	$option[primary_blog] = 1;
	$user_meta = array_merge($user_meta, $option);
	$query ="UPDATE  ".$wpdb->prefix."signups SET meta = '".serialize($user_meta)."' WHERE user_login = '$user_login' ";
	$wpdb->query($query);

	
	
}

