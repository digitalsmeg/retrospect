<?

/*
 * 
 * 
 *  
 * Prompt Functions
 * 
 * 
 *
 */

function getCurrent(){
	global $wpdb; // this is how you get access to the database
	date_default_timezone_set('America/Los_Angeles');
	
	
	$now =  getNow();
	
	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta LEFT JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."posts.ID =  ".$wpdb->prefix."postmeta.post_id  WHERE  ".$wpdb->prefix."posts.post_status = 'publish' AND meta_key = 'stories_embargo_until' and meta_value <= '$now' ORDER BY meta_value DESC LIMIT 1";
	
	
	$result = $wpdb->get_results($sql,ARRAY_A);
	foreach($result as $current){
		$cid = $current[post_id];	
	}
	
	
	
	// notify users of new prompt if it hasnt been ne
	
	
	// thius process shoul;d happen a lot. everytime the current prompt is looked for, any story not set to have been published, will so at this time
	// stories publish dates are set on edit/save
	/*
	$sql = "SELECT * FROM ".$wpdb->prefix."usermeta LEFT JOIN wp_posts ON ".$wpdb->prefix."posts.ID = meta_value WHERE meta_key = 'stories_prompted_{$cid}' AND  post_status = 'publish' AND post_author = user_id";
	
	$result = $wpdb->get_results($sql,ARRAY_A);
	foreach($result as $current){
		$firsttime = get_post_meta( $current[meta_value], 'first_time' , true);
		 if($firsttime == "no" ){
			 
			//update_post_meta( $current[meta_value], 'first_time', date("Y-m-d") );
		 }
	}
	*/
	return $cid;
}

function getNow(){
	if(!session_id()){
		session_start();
	}
	if(!empty($_SESSION[timetravel])){
		$date = get_option('myth_time_travel');
		if($date){
			return $date;	
		} else {
			return date("Y-m-d");
		}
	} else {
		return date("Y-m-d");
	}
}

function getLast(){
	global $wpdb; // this is how you get access to the database
	date_default_timezone_set('America/Los_Angeles');
	$now =  getNow();

	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta LEFT JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."posts.ID =  ".$wpdb->prefix."postmeta.post_id  WHERE  ".$wpdb->prefix."posts.post_status = 'publish' AND meta_key = 'stories_embargo_until' and meta_value <= '$now' ORDER BY meta_value DESC LIMIT 1";
	
	
	$result = $wpdb->get_results($sql,ARRAY_A);
	$now = $result[0][meta_value];
	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta LEFT JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."posts.ID =  ".$wpdb->prefix."postmeta.post_id  WHERE  ".$wpdb->prefix."posts.post_status = 'publish' AND meta_key = 'stories_embargo_until' and meta_value < '$now' ORDER BY meta_value DESC LIMIT 1";
	
	
	$result = $wpdb->get_results($sql,ARRAY_A);
	return $result;
}