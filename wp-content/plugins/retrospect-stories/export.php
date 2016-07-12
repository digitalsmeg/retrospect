<?
global $wpdb;
ob_end_clean();
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=mailchimp.csv");
header("Pragma: no-cache");
header("Expires: 0");
	$query ="SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'retro_opt' AND meta_value = 1";
	$output = $wpdb->get_results($query,ARRAY_A);
	
	foreach($output as $row) {
	 	$user =  get_userdata( $row[user_id] );
		
		echo $user->user_email."\n";
	}
	exit;

?>