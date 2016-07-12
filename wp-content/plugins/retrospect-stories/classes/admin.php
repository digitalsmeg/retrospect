<?

/*
 * 
 * 
 *  
 * Admin Functions
 * 
 * 
 *
 */
 
function init_the_admin() {
	global $wpdb, $post;
	//register our settings
	register_setting( 'mythos-settings-group', 'myth_admin_email' );
	register_setting( 'mythos-settings-group', 'myth_time_travel' );
	register_setting( 'mythos-settings-group', 'myth_stories_lock' );
	register_setting( 'mythos-settings-group', 'myth_prompts_lock' );
	register_setting( 'mythos-settings-group', 'myth_embargo_debug' );
	register_setting( 'mythos-settings-group', 'myth_topic' );
	register_setting( 'mythos-settings-group', 'myth_home_text' );
	register_setting( 'mythos-settings-group', 'myth_editor_message' );
	register_setting( 'mythos-settings-group', 'myth_charaterization' );
	register_setting( 'mythos-settings-group', 'myth_prohibited' );
	register_setting( 'mythos-settings-group', 'myth_event_1' );
	register_setting( 'mythos-settings-group', 'myth_event_2' );
	register_setting( 'mythos-settings-group', 'myth_event_3' );
	register_setting( 'mythos-settings-group', 'myth_event_4' );
	register_setting( 'mythos-settings-group', 'myth_event_5' );
	register_setting( 'mythos-settings-group', 'myth_event_6' );
	register_setting( 'mythos-settings-group', 'myth_event_7' );
	register_setting( 'mythos-settings-group', 'myth_event_8' );
	register_setting( 'mythos-settings-group', 'myth_event_9' );
	register_setting( 'mythos-settings-group', 'myth_event_1_s' );
	register_setting( 'mythos-settings-group', 'myth_event_2_s' );
	register_setting( 'mythos-settings-group', 'myth_event_3_s' );
	register_setting( 'mythos-settings-group', 'myth_event_4_s' );
	register_setting( 'mythos-settings-group', 'myth_event_5_s' );
	register_setting( 'mythos-settings-group', 'myth_event_6_s' );
	register_setting( 'mythos-settings-group', 'myth_event_7_s' );
	register_setting( 'mythos-settings-group', 'myth_event_8_s' );
	register_setting( 'mythos-settings-group', 'myth_event_9_s' );
	register_setting( 'mythos-settings-group', 'myth_deferred_date' );
	register_setting( 'mythos-settings-group', 'myth_deferred_date_interval' );
	
	$role = get_role( 'administrator' );
	if(current_user_can("administrator")){
    	$role->add_cap( 'publish_stories' );
		$role->add_cap( 'delete_stories' );
		$role->add_cap( 'edit_stories' );
		$role->add_cap( 'publish_post' );
		$role->add_cap( 'publish_posts' );
	}
	
	 if(current_user_can("administrator") && !($_POST) && !($_GET)){
		
		  $sql = "SELECT * FROM  ".$wpdb->prefix."posts WHERE post_type='stories' and post_status='pending'";
		 $result = $wpdb->get_results($sql,ARRAY_A);
		   $total = sizeof($result);
	if($total > 0){ 
	?>

<input type="hidden" id="totalStories" value="<? echo $total; ?>">
<?
	}
}
	
	
	
	
	
}
add_action( 'admin_init', 'init_the_admin' ); 






// prevents header output already started
function app_output_buffer() {
	ob_start();
} // soi_output_buffer
add_action('init', 'app_output_buffer');

function footerFunction() {
	ini_set('display_errors',0);
	global $post;
	global $current_user;
   $post = get_post($post_id);
	$slug = $post->post_name;
	
	
	if(!current_user_can("administrator")){
		add_filter('show_admin_bar', '__return_false');
		$signed =  get_user_meta($current_user->ID, "retro_terms", true);
		$signed2 =  get_user_meta($current_user->ID, "retro_privacy", true);
		$signed3 =  get_user_meta($current_user->ID, "retro_beta", true);
		if($slug != "beta-agreement" && $slug != "accept-agreements" && $slug != "terms-of-service" && $slug != "privacy-policy"){
			if(($signed3 == 0 || $signed2 == 0 || $signed == 0) && $current_user->ID >  0 ){
				wp_redirect( '/accept-agreements', 301 ); exit;
			}
		}
	}
}
add_action( 'wp_footer', 'footerFunction' );



add_action( 'admin_menu', 'main_menu' );

function remove_dashboard_widgets() {
	global $wp_meta_boxes;
if(!current_user_can( 'install_plugins')){
	
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}

}

add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );


add_action('wp_dashboard_setup', 'remove_dashboard_widgets' ); 

function adding_custom_meta_boxes( $post_type, $post ) {
	
   if(current_user_can( 'edit_stories') && $post_type == "stories"){
		   
	}elseif(current_user_can( 'edit_prompts') && $post_type == "prompts"){
		   
	} else {
		if(!is_admin()){
		wp_redirect( '/wp-admin', 301 ); exit;
		}
	}
}
add_action( 'add_meta_boxes', 'adding_custom_meta_boxes', 10, 2 );

function disable_admin_bar(){
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');
	if(!current_user_can("administrator")){
		add_filter('show_admin_bar', '__return_false');
		
	}
}
add_action( 'init', 'disable_admin_bar' , 9 );


function my_edit(){
	global $post;
	print_r($post);
}
add_action('simple_edit_form','my_edit');
 
function add_stories_columns($columns) {
	
	$column_meta =  array('story_featured' => __('Featured'),'is_response' => __('In Response To'),'story_prompt' => __('Writing Prompt'),'story_likes'=>('Likes/Dislikes'),'story_rating' => __('Approved Comments'),'is_reported' => __('Reported'),'characterized' => __(' #Characterizations'),'anon' => __('Anon'));
	return array_slice( $columns, 0, 2, true ) + $column_meta + array_slice( $columns, 2, NULL, true );
    
}

add_filter( 'manage_stories_posts_columns', 'add_stories_columns');




function add_prompts_columns($columns) {
	
	$column_meta =  array('stories_embargo_until' => __('Go Live'),'story_count' => __('Published Story Count'));
	return array_slice( $columns, 0, 2, true ) + $column_meta + array_slice( $columns, 2, NULL, true );
    
}

add_filter( 'manage_prompts_posts_columns', 'add_prompts_columns');


function add_votes_columns($columns) {
	
	$column_meta =  array('vote_prompt' => __('Will Show With'),'votes_graph' => __('Results'));
	return array_slice( $columns, 0, 2, true ) + $column_meta + array_slice( $columns, 2, NULL, true );
    
}

add_filter( 'manage_votes_posts_columns', 'add_votes_columns');






 
function custom_columns( $column, $post_id ) {
	global $wpdb;
	$firsttime = get_post_meta( $post_id, 'first_time' , true);
	 if(empty($firsttime)){
		
		 	$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key LIKE 'stories_prompted_%' AND meta_value = ".$post_id;
	
		$result = $wpdb->get_results($sql,ARRAY_A);
		foreach($result as $prompt){
			$temp = explode("_",$prompt[meta_key]);
			$p = $temp[2];
			$sql = "SELECT * FROM  ".$wpdb->prefix."posts WHERE ID = ".$p;
		
			$result2 = $wpdb->get_results($sql,ARRAY_A);
			foreach($result2 as $po){
				if($firsttime == "no"){
					$golive =  get_post_meta( $po[ID] , 'stories_embargo_until' , true );
					//update_post_meta( $post_id,'first_time',$golive); 
				}
		 
	 	}
		}
	 }
	 
	switch ( $column ) {
	 case 'anon':
	 	$anon = get_post_meta($post_id, 'stories_is_anonymous', true);
		if($anon) { echo "True"; }
		
	 break;
	 
	 case 'is_response':
	 	$response = get_post_meta( $post_id, 'stories_response', true);
		if(!empty($response)){
			$perm = get_permalink($response);
		?><a href="<? echo $perm; ?>" target="_blank">View</a><?	
		}
		break;
	 case 'story_likes':
	 	
	 	$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key='fs_votes_".$post_id."' AND meta_value = 1";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$likes = sizeof($result);
		$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key='fs_votes_".$post_id."' AND meta_value = -1";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$dislikes = sizeof($result);
		echo $likes." / ".$dislikes;
	 	break;
		
	 case 'story_count':
	 	
	 	$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta LEFT JOIN $wpdb->posts ON meta_value = ID WHERE meta_key LIKE '%stories_prompted_".$post_id."' AND post_status = 'publish'";
		$result = $wpdb->get_results($sql,ARRAY_A);
		$tot = sizeof($result);
		echo $tot;
		break;
	 
     case 'stories_embargo_until':
		$currentID = getCurrent();
		
	 	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_embargo_until' AND post_id = ". $currentID;
		$sas = $wpdb->get_results($sql,ARRAY_A);
		
	
		
			$date = get_post_meta( $post_id , 'stories_embargo_until' , true );
			?>
<input rel="admin" id="datepicker<? echo $post_id; ?>" class="datepicker" value="<? echo $date; ?>" style="width:100px" />
<?
		if($date < date("Y-m-d") && $currentID != $post_id ){
			?>
<div style="color:white;background: red;padding:2px;display:inline-block;width:70px;text-align:center;">Past</div>
<?
		} elseif($currentID == $post_id){
			?>
<div style="color:white;background: green;padding:2px;display:inline-block;width:70px;text-align:center;"">Current</div>
<?
		}else { 
			?>
<div style="color:white;background: blue;padding:2px;display:inline-block;width:70px;text-align:center;"">Upcoming</div>
<?

		}
        break;
	case 'vote_prompt':
		
			$wp = get_post_meta( $post_id , 'active_prompt' , true );
		$currentID = $wp;
		$post = get_post($wp);
		
	 	if($wp > 0){
			$date = get_post_meta( $wp , 'stories_embargo_until' , true );
			echo $post->post_title . " - " . $date;
		}
        break;
	case 'votes':
		$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = ".$post_id." AND meta_key LIKE 'votes_%'";
		  $results = $wpdb->get_results($sql,ARRAY_A);
		  $totalVotes = 0;
		  foreach($results as $votes){
			$totalVotes += $votes[meta_value];  
		  }
		  echo $totalVotes;
        break;
		case 'votes_graph':
		
		?>
<style>
        .quiz .answer .voteBar{
			background: #005672;
			height:20px;
			color:white;	
			 text-shadow: 1px 1px 1px black;
		}
		</style>
<?
		$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = ".$post_id." AND meta_key = 'active_prompt'";
		
		  $results = $wpdb->get_results($sql,ARRAY_A);
		echo show_votes(1,$results[0][meta_value]);
        break;
	case 'stories_hide_until':
        echo get_post_meta( $post_id , 'stories_hide_until' , true ); 
        break;
	 case 'story_prompt':
	 		$post =  get_post( $post_id  );
			$firsttime = get_post_meta( $post_id, 'first_time' , true);
	$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE user_id = ".$post->post_author." AND meta_key LIKE 'stories_prompted_%' AND meta_value = ".$post_id;
					
		$result = $wpdb->get_results($sql,ARRAY_A);
		foreach($result as $prompt){
			$temp = explode("_",$prompt[meta_key]);
			$p = $temp[2];
			$sql = "SELECT * FROM  ".$wpdb->prefix."posts WHERE ID = ".$p;
		
			$result2 = $wpdb->get_results($sql,ARRAY_A);
			$mot = 0;
			foreach($result2 as $po){
				$mot++;
				
				if($firsttime > date("Y-m-d")){
				
			?>
<a href="<? echo get_permalink($po[ID]); ?>"><? echo $po[post_title]; ?></a><br>
goes live on <? echo $firsttime ; ?>
<?	
				} else {
					
				?>
<a href="<? echo get_permalink($po[ID]); ?>"><? echo $po[post_title]; ?></a><br>
<u><strong>went</strong></u> live on <? echo $firsttime ; ?>
<?		
				}
				
			}
			if($mot == 0){
				?><strong>My Own Topic</strong><?
			}
		}
		
        break;
	  case 'story_rating':
	 	//ratingSystem($post_id,true);
		likesComments($post_id);
        break;
	 case 'is_reported':
	 	
		$reason = get_post_meta( $post_id , 'stories_report_reason' , true );
		
		$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = ".$post_id." AND meta_key LIKE 'stories_reported_%'";
		$resons = array();
		$result = $wpdb->get_results($sql,ARRAY_A);
		$total = sizeof($result);
		foreach($result as $row){
			$reasons[$row[meta_value]]++;	
		}
		
		$post = get_post($post_id);
		if($total > 0){ ?>
<? echo $total; ?> times <br>
<? if(current_user_can("administrator")){ ?>
<input type="button" value="Clear" onclick="clearReport(<? echo $post_id; ?>,jQuery(this))" />
<input type="button" value="<? if(empty($reason)){ ?>Request Edit<? } else { ?>Change Reason<? } ?>" onclick="requestEdit(<? echo $post_id; ?>,jQuery(this))" />
<input type="text" placeholder="Reason for Edit" value="<? echo esc_attr($reason); ?>" />
<?
		foreach($reasons as $key=>$value){
		?>
<div><? echo $key; ?>: <? echo $value; ?></div>
<?	
		}
		?>
<? } ?>
<? if(current_user_can("writer") && $post->post_status == 'publish'){ ?>
Reported Pending Review
<? } ?>
<? if(current_user_can("writer") && $post->post_status == 'pending'){ ?>
This story requires editing: <? echo  $reason; ?>
<? } ?>
<? } ?>
<?
        break;
	 case 'characterized':
	 		$post = get_post($post_id);
	 	 	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = ".$post->ID." AND meta_key LIKE 'stories_characterized_%'";
			$result = $wpdb->get_results($sql,ARRAY_A);
			$c = 0;
			foreach($result as $row){
				$t = explode(",",$row[meta_value]);
				$c += sizeof($t);
			}
			echo $c;
		 break;
	 case 'featured':
	 		$post = get_post($post_id);
	 	 	$f = get_post_meta( $post_id , 'stories_featured' , true );
			if($f == "on"){
			?>
Yes
<?	
			}
		 break;
		  case 'story_featured':
	 		$post = get_post($post_id);
	 	 	 $sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_featured_story' AND  meta_value = ".$post->ID;
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		if($result[0][post_id] > 0){
		?>
Featured
<?	
		}
		 	break;
	}
}
add_action( 'manage_posts_custom_column' , 'custom_columns' , 10, 2 );

add_action('manage_users_custom_column',  'pippin_show_user_id_column_content', 10, 3);
function pippin_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
	
	if ( 'story_count' == $column_name ) {
			$loop = new WP_Query( array( 'post_type' => 'stories',  'author' => $user_id) );
			return $loop->post_count;
	}
	if ( 'terms' == $column_name ) {
			$signed =  get_user_meta($user_id, "retro_terms", true);
			$signed2 =  get_user_meta($user_id, "retro_privacy", true);
			$signed3 =  get_user_meta($user_id, "retro_beta", true);
			if($signed && $signed2 && $signed3){
				$return = date("F j, Y",strtotime(get_user_meta($user_id, "retro_terms_date", true)))."<br>";
			} else {
				if($signed == 0){
					$return =  "Terms Not signed";
				}elseif($signed2 == 0){
					$return =  "Privacy Not signed";
				} else {
					$return =  "Beta Not signed";
				}
			}
			
			return $return;
			
	}
	if ( 'facebook' == $column_name ) {
			$user =  get_userdata( $user_id );
			if(preg_match('/google/',$user->user_url)){
				$u = '<a href="'.$user->user_url.'" target="_blank">Google</a>';
			}
			if(preg_match('/facebook/',$user->user_url)){
				if($u != ""){
					$u .="<br>";	
				}
				$u .= '<a href="'.$user->user_url.'" target="_blank">Facebook</a>';
			}
			return $u;
			
			
	}
	if ( 'newsletter_opt' == $column_name ) {
		
		if(get_user_meta($user_id, "retro_opt", true) == 1){
			return "<input type='checkbox' onclick='setOptins(".$user_id.")' value='".$user_id."' checked/>";
		} else {
			return "<input type='checkbox' onclick='setOptins(".$user_id.")' value='".$user_id."' />";	
		}
		
	}
	if ( 'exclude' == $column_name ) {
		
		if(get_user_meta($user_id, "metrics_exclude", true) == 1){
			return "Yes";
		} else {
			return "";	
		}
			
	}
  
}

add_filter('manage_users_columns', 'add_story_count_column');
function add_story_count_column($columns) {
    $columns['story_count'] = 'Stories';
	$columns['terms'] = 'Agreements Signed';
	$columns['exclude'] = 'Metrics Exclude';
	$columns['facebook'] = 'Facebook/Google';
	$columns['newsletter_opt'] = 'Newsletter Opt';
    return $columns;
	
}



function cancurrentuser_func( $role ) {
	$role = explode(",",$role);
	
	if (check_role($role)) {
	  	return true;
	 } else {
		return false;
	 }
}



function check_role( $role ) {
	
	$user_id = get_current_user_id();
 
    if ( is_numeric( $user_id ) ){
		$user = get_userdata( $user_id );
	} else {
        $user = wp_get_current_user();
	}
 	
    if ( empty( $user ) ){
		return false;
	}
	
 	foreach($role as $key=>$value){
    	if( in_array( trim($value), (array) $user->roles )){
			return true;
		}
	}
}

set_transient( 'shortCodeUsed', false); 
add_shortcode("currentuser", "currentusercan_func",1);


