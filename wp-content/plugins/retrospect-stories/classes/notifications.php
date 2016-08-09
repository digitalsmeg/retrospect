<?
/*
 * 
 * 
 *  
 * Notification Related Functions
 * 
 * 
 *
 */
 
function send_daily_defferred_notifications(){
	global $wpdb;
	
		// daily notifications cron -> 0 22 * * * every day at 10 pm
		
			
			$admins = getAdmins();	
			$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key = 'deferred_notifications' ORDER BY user_id DESC, umeta_id ASC";
			$result = $wpdb->get_results($sql,ARRAY_A);
			if(sizeof($result) > 0){
				foreach($result as $notification){
					$arr = unserialize(unserialize($notification[meta_value]));
					$messages[$notification[user_id]][] = $arr;
				}
				
				$uid = "";
				foreach($messages as $user_id=>$value){
					
					$user = get_userdata($user_id);
					$msg = $user->display_name.", this is your daily summary:\n\n";
					foreach($value as $key){
						$msg .= "Subject: ".$key[subject]."\n";
						$msg .= "Date: ".$key[date_sent]."\n";
						$msg .= "Message: ".$key[content];
						$msg .= "\n_____________________________________________\n\n";
					}
					
					$args = array(
							'sender_id' => $admins[0],
							'thread_id' => false,
							'recipients' => array( $user_id ),
							'subject' => "Daily Summary for ".date("F j, Y"),
							'content' => $msg,
							'date_sent' => bp_core_current_time()
					);
					
					$result = messages_new_message( $args );
					delete_user_meta( $user_id, "deferred_notifications" ) ;
				}
			}
			
	
}


function send_weekly_defferred_notifications(){
	global $wpdb;
	
	//test deferred date
	$sendDate = get_option('myth_deferred_date');
	$interval = get_option('myth_deferred_date_interval');
	$admins = getAdmins();
	
		if(date("Y-m-d") >= $sendDate){
			// weekly notifications (not cron)
			update_option( 'myth_deferred_date', date("Y-m-d",strtotime("+1 week")) );	
			$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key = 'deferred_notifications_weekly' ORDER BY user_id DESC, umeta_id ASC";
			$result = $wpdb->get_results($sql,ARRAY_A);
			if(sizeof($result) > 0){
				foreach($result as $notification){
					$arr = unserialize(unserialize($notification[meta_value]));
					$messages[$notification[user_id]][] = $arr;
				}
				
				$uid = "";
				foreach($messages as $user_id=>$value){
					
					$user = get_userdata($user_id);
					$msg = $user->display_name.", this is your weekly summary:\n\n";
					foreach($value as $key){
						$msg .= "Subject: ".$key[subject]."\n";
						$msg .= "Date: ".$key[date_sent]."\n";
						$msg .= "Message: ".$key[content];
						$msg .= "\n_____________________________________________\n\n";
					}
					
					$args = array(
							'sender_id' => $admins[0],
							'thread_id' => false,
							'recipients' => array( $user_id ),
							'subject' => "Weekly Summary - Week of ".date("F j, Y"),
							'content' => $msg,
							'date_sent' => bp_core_current_time()
					);
					
					$result = messages_new_message( $args );
					delete_user_meta( $user_id, "deferred_notifications_weekly" ) ;
				}
			}
		}
		
	
	
	
	
	
}

add_action( 'init', 'send_weekly_defferred_notifications' , 10 );



function story_notifications() {
	global $wpdb;
	$admins = getAdmins();
	
	// weekly notifications of new prompt  cron -> 0 0 * * 1 every week on monday
	if(isset($_GET[notify])){
		$notified = "true";
	}
	
	if(isset($_GET[daily])){
		send_daily_defferred_notifications("daily");
		/*
		$args = array(
						'sender_id' => $admins[0],
						'thread_id' => false,
						'recipients' => array( 122 ),
						'subject' => 'Testing Cron '.date("i"),
						'content' =>' This is a test '.date("i"),
						'date_sent' => bp_core_current_time()
				);
		$result = messages_new_message( $args );
		*/
		
				
	}
	if(!empty($notified)){
		
		$cid = getCurrent();
		$msg = get_option('myth_event_8');
		$title = get_option('myth_event_8_s');
		$p = get_post($cid);
		
		$title = str_replace('{$title}',$p->post_title,$title);
		$msg = str_replace('{$title}',$p->post_title,$msg);
		$msg = str_replace('{$excerpt}',strip_tags($p->post_content),$msg);
		$msg = nl2br($msg);
		$img =  get_the_post_thumbnail($p->ID,'medium');
		
		$msg = str_replace('{$image}',$img,$msg);
		
		
  		wp_clear_scheduled_hook('new_prompt_event');	
		//delete_post_meta_by_key('prompt_notification' );
		//update_post_meta( $cid, 'prompt_notification', date("Y-m-d") );
		 // get all users
		 //
		 $sql = "SELECT * FROM  ".$wpdb->prefix."users";
		 $result = $wpdb->get_results($sql,ARRAY_A);
		 // 122 masteryoda 63 reddwarfred 123 digitalsmeg
		 foreach($result as $row){
		 	$notify = bp_get_user_meta( $row[ID], 'notification_prompt', true );
			
			if($notify == "yes" || empty($notify)){
				$args = array(
						'sender_id' => $admins[0],
						'thread_id' => false,
						'recipients' => array( $row[ID] ),
						'subject' => $title,
						'content' => $msg.' View the prompt at '.get_permalink($cid),
						'date_sent' => bp_core_current_time()
				);
				
				update_user_meta( $row[ID], 'deferred_prompt_notifications', serialize($args));	
			}
			
		 }
		 if (! wp_next_scheduled ( 'new_prompt_event' )) {
			wp_schedule_event(time(), 'hourly', 'new_prompt_event');
		 }
		
	}
}


add_action('shutdown', 'story_notifications');



function follower_notifications() {
	global $wpdb;
	// daily notifications of new prompt  cron -> 0 0 * * 1 every week on monday
	if(isset($_GET[follower])){
		
	
		// follower function runs only on mondays the same time a new prompt is announced
		
		// find all stories that would go live today
		$sql = "SELECT * FROM ".$wpdb->prefix."postmeta LEFT JOIN  ".$wpdb->prefix."posts ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id  WHERE meta_key = 'first_time' and meta_value = '".date("Y-m-d",strtotime("-1 day"))."' AND post_status = 'publish'";
	
		$result = $wpdb->get_results($sql,ARRAY_A);
		if(sizeof($result) > 0){
			// iterate each post that would go live today
			foreach($result as $p){
			
			// get the list of followers
			$following = explode(",", bp_get_follower_ids(array( 'user_id' => $p[post_author] )));
			$post_id = $p[post_id];
				foreach($following as $follower){
					// see if they want to be notified
					$notify = bp_get_user_meta( $follower, 'notification_story_follow_story', true );
					$user= get_userdata($p[post_author]);
					$anon = get_post_meta($p[post_id], 'stories_is_anonymous', true);
					
					
					$msg = get_option('myth_event_9');
					$title = get_option('myth_event_9_s');
		
					$title = str_replace('{$title}',$p[post_title],$title);
					$title = str_replace('{$author}',$user->display_name,$title);
					$msg = str_replace('{$title}',$p[post_title],$msg);
					$msg = str_replace('{$author}',$user->display_name,$msg);
					
					//if its not set as anon
					if(!$anon){
						sendNotify($title, $msg,$follower,$post_id,$notify);
					}
				}
			}
		}
		
	}
	

	
}


add_action('shutdown', 'follower_notifications');



function sendNotify($title,$msg,$author,$post_id,$notify = "",$stub = 'View the [type] at'){
	$admins = getAdmins();
	
	//$notify = bp_get_user_meta( $p->post_author, 'notification_story_follow_story', true );
	$anon = get_post_meta($post_id, 'stories_is_anonymous', true);
	$post = get_post($post_id);
	$post_author = get_userdata( $post->post_author );
	if(!$anon){
		$dn = $post_author->display_name;
	} else {
		$dn = "Anonymous";	
	}
	$msg = str_replace('{$author}',$dn,$msg);
	$msg = str_replace('{$title}',$post->post_title,$msg);
	
	$instant =  bp_get_user_meta( $author, 'notification_story_comment_reply', true ) ;
	
	$types = ["prompts"=>"prompt","stories"=>"story"];			
	$deferred = bp_get_user_meta( $author, 'notification_story_summary', true );
	
	$type = $types[$post->post_type];
	$stub = str_replace("[type]",$type,$stub);
	if($notify == "yes" || empty($notify)){
		$args = array(
					'sender_id' => $admins[0],
					'thread_id' => false,
					'recipients' => array( $author ),
					'subject' => $title,
					'content' => $msg.' '.$stub.' '.get_permalink($post_id),
					'date_sent' => bp_core_current_time()
		);
		// send instant
		if($deferred != "weekly" && $deferred != "daily"){
			$result = messages_new_message( $args );
		} else {
			// dont forget date_sent when actually firing these off
			if($deferred == "weekly"){
				update_user_meta( $author, 'deferred_notifications_weekly', serialize($args));	
			} else {
				update_user_meta( $author, 'deferred_notifications', serialize($args));	
			}
		}
	}
	
	
	return 1;
	
}

function story_notification_settings() {
	if ( !$notify1 = bp_get_user_meta( bp_displayed_user_id(), 'notification_story_publishes', true ) ){
		$notify1 = 'yes';
	}
	if ( !$notify2 = bp_get_user_meta( bp_displayed_user_id(), 'notification_story_response', true ) ){
		$notify2 = 'yes';
	}
	if ( !$notify3 = bp_get_user_meta( bp_displayed_user_id(), 'notification_story_follow_story', true ) ){
		$notify3 = 'yes';
	}
	if ( !$notify4 = bp_get_user_meta( bp_displayed_user_id(), 'notification_story_comment', true ) ){
		$notify4 = 'yes';
	}
	if ( !$notify5 = bp_get_user_meta( bp_displayed_user_id(), 'notification_story_summary', true ) ){
		$notify5 = bp_get_user_meta( bp_displayed_user_id(), 'notification_story_summary', true );
		
	}
	if ( !$notify6 = bp_get_user_meta( bp_displayed_user_id(), 'notification_story_comment_reply', true ) ){
		$notify6 = 'yes';
	}
	if ( !$notify7 = bp_get_user_meta( bp_displayed_user_id(), 'notification_story_featured', true ) ){
		$notify7 = 'yes';
	}
	if ( !$notify8 = bp_get_user_meta( bp_displayed_user_id(), 'notification_prompt', true ) ){
		$notify8 = 'yes';
	}
	
	
	if($notify5 == ""){
			$notify5 = "instant";
		}
	
?>
	<table class="notification-settings" id="summary-notification-settings">
		<thead>
			<tr>
				<th class="icon"></th>
				<th class="title">Summary</th>
				<th class="yes">Instant</th>
				<th class="no">Weekly</th>
                <th class="no">Daily</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td></td>
				<td>Receive instant notifications or weekly summary on story-related notifications</td>
				<td class="yes"><input type="radio" name="notifications[notification_story_summary]" value="instant" <?php checked( $notify5, 'instant', true ) ?>/></td>
				<td class="no"><input type="radio" name="notifications[notification_story_summary]" value="weekly" <?php checked( $notify5, 'weekly', true ) ?>/></td>
                <td class="no">
                <input type="radio" name="notifications[notification_story_summary]" value="daily" <?php checked( $notify5, 'daily', true ) ?>/></td>
			</tr>
            
            
           
		</tbody>

		
	</table>
    
    <table class="notification-settings" id="summary-notification-settings">
		<thead>
			<tr>
				<th class="icon"></th>
				<th class="title">Prompts</th>
				<th class="yes">Yes</th>
				<th class="no">No</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td></td>
				<td>Receive notifications when a new prompt is available</td>
				<td class="yes"><input type="radio" name="notifications[notification_prompt]" value="yes" <?php checked( $notify8, 'yes', true ) ?>/></td>
				<td class="no"><input type="radio" name="notifications[notification_prompt]" value="no" <?php checked( $notify8, 'no', true ) ?>/></td>
			</tr>
            
           
		</tbody>

		
	</table>
    
	<table class="notification-settings" id="follow-notification-settings">
		<thead>
			<tr>
				<th class="icon"></th>
				<th class="title">Story-Related</th>
				<th class="yes">Yes</th>
				<th class="no">No</th>
			</tr>
		</thead>

		<tbody>
       
			<tr>
				<td></td>
				<td>You reach a publication milestone (1st story, 5th story, 10th story, etc.)</td>
				<td class="yes"><input type="radio" name="notifications[notification_story_publishes]" value="yes" <?php checked( $notify1, 'yes', true ) ?>/></td>
				<td class="no"><input type="radio" name="notifications[notification_story_publishes]" value="no" <?php checked( $notify1, 'no', true ) ?>/></td>
			</tr>
            <tr>
				<td></td>
				<td>Someone writes a story in response to one of your stories</td>
				<td class="yes"><input type="radio" name="notifications[notification_story_response]" value="yes" <?php checked( $notify2, 'yes', true ) ?>/></td>
				<td class="no"><input type="radio" name="notifications[notification_story_response]" value="no" <?php checked( $notify2, 'no', true ) ?>/></td>
			</tr>
            <tr>
				<td></td>
				<td> Someone you follow publishes a story</td>
				<td class="yes"><input type="radio" name="notifications[notification_story_follow_story]" value="yes" <?php checked( $notify3, 'yes', true ) ?>/></td>
				<td class="no"><input type="radio" name="notifications[notification_story_follow_story]" value="no" <?php checked( $notify3, 'no', true ) ?>/></td>
			</tr>
             <tr>
				<td></td>
				<td>Your story is selected as the Featured Story</td>
				<td class="yes"><input type="radio" name="notifications[notification_story_featured]" value="yes" <?php checked( $notify7, 'yes', true ) ?>/></td>
				<td class="no"><input type="radio" name="notifications[notification_story_featured]" value="no" <?php checked( $notify7, 'no', true ) ?>/></td>
			</tr>
     
		</tbody>

		<?php do_action( 'story_notification_settings' ); ?>
	</table>
    <table class="notification-settings" id="summary-notification-settings" >
		<thead>
			<tr >
				<th class="icon"></th>
				<th class="title">Comment-Related</th>
				<th class="yes">Yes</th>
				<th class="no">No</th>
			</tr>
		</thead>

		<tbody>
			<tr >
				<td></td>
				<td>Receive notifications when someone comments on a story I commented on</td>
				<td class="yes"><input type="radio" name="notifications[notification_story_comment]" value="yes" <?php checked( $notify4, 'yes', true ) ?>/></td>
				<td class="no"><input type="radio" name="notifications[notification_story_comment]" value="no" <?php checked( $notify4, 'no', true ) ?>/></td>
			</tr>
            <tr >
				<td></td>
				<td>Receive notifications when someone replies to one of my comments</td>
				<td class="yes"><input type="radio" name="notifications[notification_story_comment_reply]" value="yes" <?php checked( $notify6, 'yes', true ) ?>/></td>
				<td class="no"><input type="radio" name="notifications[notification_story_comment_reply]" value="no" <?php checked( $notify6, 'no', true ) ?>/></td>
			</tr>
            
            
           
		</tbody>

		
	</table>
<?php
}
add_action( 'bp_notification_settings', 'story_notification_settings' );



function bp_profile_settings_save() {
	
	
}
add_action( 'bp_before_profile_edit_content', 'bp_profile_settings_save' );


add_action('new_prompt_event', 'prompt_notifications');

function prompt_notifications() {
	// do something every hour
	global $wpdb;
	
	$admins = getAdmins();
	 $sql = "SELECT * FROM  ".$wpdb->prefix."users";
	 $result = $wpdb->get_results($sql,ARRAY_A);
	 
	 //split emails into number of users / 6 hours
	 $total = ceil(sizeof($result) / 3);	 
		 
	$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key = 'deferred_prompt_notifications' LIMIT $total";
		$result = $wpdb->get_results($sql,ARRAY_A);
		// if we have any. otherwise cancel 
		if(sizeof($result) > 0){
			foreach($result as $notification){
				$args =unserialize(unserialize($notification[meta_value]));
				$args[date_sent] = bp_core_current_time();
				
				$result = messages_new_message( $args );
				//update_user_meta( $notification[user_id], 'deferred_prompt_sent', date("Y-m-d H:i:s"));	
				delete_user_meta( $notification[user_id], "deferred_prompt_notifications" ) ;
				
			}
			
		} else {
			// if no more to send, we clear it
			wp_clear_scheduled_hook('new_prompt_event');	
		}
}


