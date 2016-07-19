<?php
/* 
	Plugin Name: MyRetrospect Story Plugin
 	Plugin URI: http://www.firespike.org 
	Description: A plugin designed to meet the story writing social content of Retrospect.
	Author: FireSpike LLC
	Version: 2.0
	Author URI: http://www.firespike.com 
*/ 


$site = "beta";



error_reporting(E_ERROR | E_WARNING | E_PARSE);


require_once plugin_dir_path(__FILE__) . 'fpdf/fpdf.php';
require_once plugin_dir_path(__FILE__) . 'classes/prompt.php';
require_once plugin_dir_path(__FILE__) . 'classes/misc.php';
require_once plugin_dir_path(__FILE__) . 'classes/notifications.php';
require_once plugin_dir_path(__FILE__) . 'classes/ajax.php';
require_once plugin_dir_path(__FILE__) . 'classes/meta.php';
require_once plugin_dir_path(__FILE__) . 'classes/save.php';
require_once plugin_dir_path(__FILE__) . 'classes/admin.php';
require_once plugin_dir_path(__FILE__) . 'classes/social.php';
require_once plugin_dir_path(__FILE__) . 'classes/content.php';
require_once plugin_dir_path(__FILE__) . 'classes/story.php';


/*
 * 
 * 
 *  
 * Creates Main Menus
 * 
 * 
 *
 */

function main_menu() {
	global $wpdb;
	if(sizeof(get_plugins('/user-role-editor')) == 0){
		 $message = 'User Role Editor plugin is missing. Story Plugin requires it!!!';
		echo "<div style='background:red;color:white;padding:5px;'>$message</div>";
		return false;
		
	}
	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	
	
	
	 if(!current_user_can( 'administrator' )){
		 global $wpdb,$current_user;
		
		
		 wp_enqueue_script('stories-user-scripts', $pluginfolder . '/user.js?count='.$count);
		 wp_enqueue_style('story-user-admin-styles',$pluginfolder.'/css/user.css');
	}
	wp_enqueue_script('stories-scripts2', $pluginfolder . '/scripts.js');
	$user = wp_get_current_user(); 
	
	global $wpdb;
	global $wp_meta_boxes;
	add_menu_page( 'Stories', 'Stories', 'edit_posts', 'edit.php?post_type=stories', '', 'dashicons-book-alt' ); 
	if(current_user_can("administrator")){
		add_menu_page( 'Writing Prompts', 'Writing Prompts', 'edit_posts', 'edit.php?post_type=prompts', '', 'dashicons-welcome-write-blog' );
		add_menu_page( 'Sponsors', 'Sponsors', 'edit_posts', 'edit.php?post_type=sponsors', '', 'dashicons-groups' ); 
		add_menu_page( 'Votes', 'Votes', 'edit_posts', 'edit.php?post_type=votes', '', 'dashicons-clipboard' ); 
		
	}
	add_options_page('Retrospect Settings', 'Retrospect Settings', 'manage_options', 'retrospect-stories/settings.php', '');
	add_options_page('Retrospect Metrics', 'Retrospect Metrics', 'manage_options', 'retrospect-stories/metrics.php', '');
	
	add_options_page('Export Newsletter CSV', 'Export Newsletter CSV', 'administrator', 'retrospect-stories/export.php', '');
	add_options_page('User Name Changes', 'User Name Changes', 'administrator', 'retrospect-stories/user-name-changes.php', '');
	
 	
	if(!current_user_can( 'install_plugins')){
		remove_menu_page( 'edit.php' );
		//remove_menu_page( 'upload.php' );   
		remove_menu_page( 'tools.php' ); 
		
	}
	 
	
	
	
}

function my_custom_init() {
	/* Stories */
	global $wpdb;
	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	wp_enqueue_style('story-admin-styles',$pluginfolder.'/css/style.css');
	
	$labels = array(
		'name' => _x('Stories', 'post type general name'),
		'singular_name' => _x('Story', 'post type singular name'),
		'add_new' => _x('&nbsp;&nbsp;&nbsp;Add New Story', 'portfolio item'),
		'add_new_item' => __('Add Story'),
		'edit_item' => __('Edit Story'),
		'new_item' => __('New Story'),
		'view_item' => __('View Story'),
		'search_items' => __('Search Stories'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'menu_icon' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' =>  true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive'        => true,
		'hierarchical' => false,
		'menu_position' => null,
		'show_in_menu' => false,
		'supports'      => array( 'excerpt','title', 'editor', 'author',  'revisions', 'comments','thumbnail')
 ); 
 
	register_post_type( 'stories' , $args );
	
	
	if(current_user_can("administrator")){
		
		/* Sponsors */
	
	$labels = array(
		'name' => _x('Sponsors', 'post type general name'),
		'singular_name' => _x('Sponsors', 'post type singular name'),
		'add_new' => _x('Add New Sponsor', 'portfolio item'),
		'add_new_item' => __('Add Sponsor'),
		'edit_item' => __('Edit Sponsor'),
		'new_item' => __('New Sponsor'),
		'view_item' => __('View Sponsor'),
		'search_items' => __('Search Sponsors'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'menu_icon' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'show_in_menu' => false,
		'supports'      => array( 'title', 'editor', 'author','thumbnail')
	  ); 
	
	  
	  register_post_type( 'sponsors' , $args );
		
	/* Prompts */
	
	$labels = array(
		'name' => _x('Writing Prompts', 'post type general name'),
		'singular_name' => _x('Writing Prompt', 'post type singular name'),
		'add_new' => _x('Add New Writing Prompt', 'portfolio item'),
		'add_new_item' => __('Add Writing Prompt'),
		'edit_item' => __('Edit Writing Prompt'),
		'new_item' => __('New Writing Prompt'),
		'view_item' => __('View Writing Prompt'),
		'search_items' => __('Search Writing Prompts'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'menu_icon' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' =>  true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'has_archive'        => true,
		'hierarchical' => false,
		'menu_position' => null,
		'show_in_menu' => false,
		'supports'      => array( 'excerpt','title', 'editor', 'author',  'revisions', 'comments','thumbnail')
	  ); 
	}
	  
	  register_post_type( 'prompts' , $args );
	  
	  
	  // surveys
	  
	  $labels = array(
		'name' => _x('Votes', 'post type general name'),
		'singular_name' => _x('Vote', 'post type singular name'),
		'add_new' => _x('&nbsp;&nbsp;&nbsp;Add New Survey', 'portfolio item'),
		'add_new_item' => __('Add Survey'),
		'edit_item' => __('Edit Survey'),
		'new_item' => __('New Survey'),
		'view_item' => __('View Survey'),
		'search_items' => __('Search Survey'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'menu_icon' => '',
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'show_in_menu' => false,
		'capabilities' => array(
			'edit_post'          => 'update_core',
			'read_post'          => 'update_core',
			'delete_post'        => 'update_core',
			'edit_posts'         => 'update_core',
			'edit_others_posts'  => 'update_core',
			'delete_posts'       => 'update_core',
			'publish_posts'      => 'update_core',
			'read_private_posts' => 'update_core'
		),
		'supports'      => array( 'title', 'editor')
	  ); 
	  
	register_post_type( 'votes' , $args );
	  
	 
	  
	 
	  
		
	
}
add_action('init', 'my_custom_init');

function add_settings_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
 
	if ($file == $this_plugin){
		$settings_link = '<a href="options-general.php?page=retrospect-stories/settings.php">'.__("Settings", "mythos_setting_form").'</a>';
		 array_unshift($links, $settings_link);
	}
	return $links;
}

add_filter('plugin_action_links', 'add_settings_link', 10, 2 );



function mythos_stories_setting_form(){
	$plugininc =  PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	echo $plugininc;
	include($plugininc. "/settings.php"); 
}



function storyCountWritingPrompt($wpid){
	
	global $wpdb;
	
		$sql = "SELECT * FROM ".$wpdb->prefix."usermeta LEFT JOIN ".$wpdb->prefix."posts ON ".$wpdb->prefix."posts.ID = ".$wpdb->prefix."usermeta.meta_value WHERE meta_key = 'stories_prompted_{$wpid}' AND post_type = 'stories'"; 
		$result = $wpdb->get_results($sql);
		
		$thePosts = array(0);
		$total = sizeof($result);
		foreach($result as $row){
			$thePosts[$row->meta_value] = $row->meta_value;
		}
	
	
	
	
	$qarray = array(
					'post__in' => $thePosts,
	 				'post_type' => 'stories', 
					'post_status' => 'publish', 
					'posts_per_page' => -1 );	
					
	$qarray['orderby'] = 'date';
	$qarray['order'] = 'DESC';

$loop2 = new WP_Query($qarray);
		$sql = $loop2->request;
		$result = $wpdb->get_results($sql);
	
$count = 0;
$prompted_post = get_post($wpid);
$golive = $prompted_post->stories_embargo_until;
foreach($result as $row){
	
	$firsttime = get_post_meta( $row->ID, 'first_time' , true);
	if(!empty($firsttime) || $golive <= date("Y-m-d")){ 
		$count++;
	}   
}
  
return $count;
	

}


add_action( 'wp_enqueue_scripts', 'add_frontend_ajax_javascript_file' );
function add_frontend_ajax_javascript_file(){
	  $pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
      wp_enqueue_script('jquery');
	  wp_localize_script( 'frontend-ajax', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
	  wp_enqueue_script('stories-scripts', $pluginfolder . '/frontend.js');
	  wp_enqueue_script('cycle-stories-scripts', $pluginfolder . '/jquery.cycle.js');
	
}


function stop_the_hype( $post ) {
 global $post;
  // almost identical to 'edit page' from
  // http://core.trac.wordpress.org/browser/trunk/wp-includes/capabilities.php?rev=15919#L867


 return false;
 
  /* Return the capabilities required by the user. */
  
}
add_action( 'edit_post', 'stop_the_hype' );

/*
 * 
 * 
 *  
 * show only the users posts when user is logged into back end
 * 
 * 
 *
 */
 

function filter_posts_list($query)
{
    //$pagenow holds the name of the current page being viewed
     global $pagenow;
 		global $my_admin_page;
    //$current_user uses the get_currentuserinfo() method to get the currently logged in user's data
     global $current_user;
     get_currentuserinfo();
     require_once(ABSPATH . 'wp-admin/includes/screen.php');
        //Shouldn't happen for the admin, but for any role with the edit_posts capability and only on the posts list page, that is edit.php
        if(!current_user_can('administrator') && current_user_can('edit_posts') && ('edit.php' == $pagenow)) {
        //global $query's set() method for setting the author as the current user's id
        	$query->set('author', $current_user->ID); 
			$screen = get_current_screen();
 			add_filter('views_'.$screen->id, 'remove_post_counts');
        }
		
}

add_action('pre_get_posts', 'filter_posts_list');
 
 
 function remove_post_counts($posts_count_disp){
    	unset($posts_count_disp['all']);
        unset($posts_count_disp['publish']);
     	
        return $posts_count_disp;
}







 


function story_uninstall(){
	global $wpdb;
	
	
	$sql = "DELETE FROM ".$wpdb->prefix."postmeta WHERE meta_key LIKE '%stories_%' OR meta_key LIKE '%prompt_%'"; 	
	//$wpdb->query($sql);
	$sql = "DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%stories_%' OR meta_key LIKE '%prompt_%'"; 	
	//$wpdb->query($sql);
	
	


}

//register_uninstall_hook( __FILE__, 'story_uninstall' );








/* home made filter */
function registry_restrict_manage_posts() {}















// This hook should run before user email validation
//add_filter( 'pre_user_email', 'skip_email_exist');
/**
 * [skip_email_exist description]
 * @param  [type] $user_email [description]
 * @return [type]             [description]
 */
function skip_email_exist($user_email){
 
    define( 'WP_IMPORTING', 'SKIP_EMAIL_EXIST' );
    return $user_email;
}

/* login redirect if needed */
function my_login_redirect( $redirect_to, $request, $user ) {
	
	
	global $user;

	if(empty($request)){
		if ( isset( $user->roles ) && is_array( $user->roles ) ) {
			//check for admins
			if ( in_array( 'administrator', $user->roles ) ) {
				// redirect them to the default place
				return admin_url();
			} else {
				if(!empty($redirect_to)){
					return $redirect_to;
				} else {
					return home_url();
				}
			}
		} else {
			return $request;
		}
	}
	
}
//add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );



function glue_login_redirect(){
    //using $_REQUEST because when the login form is submitted the value is in the POST
	global $user;
   
		
		if (isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) {
			$redirect_to = admin_url();
		} else {
			if(isset($_GET['redirect_to'])){
        		$redirect_to = $_REQUEST['redirect_to'];
    		} else {
				$redirect_to = home_url();
			}
		}
	
	 return $redirect_to;
}
//add_filter('login_form','glue_login_redirect',999); 

function get_first_image() {
	global $post, $posts;
	$first_img = '';
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img = $matches [1] [0];
	if(empty($first_img)){ //Defines a default image
	$first_img = "";
	}
	return $first_img;
}


add_action( 'wp_trash_post', 'delete_post_function' );
function delete_post_function( $postid ){

    // We check if the global post type isn't ours and just return
    global $post_type, $wpdb;   
			$sql = "DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE 'stories_prompted%' AND meta_value = $postid"; 	
			$wpdb->query($sql);
		// shouldnt be able to delete prompts if a story exists
    	if($post_type == 'stories' ){
			$sql = "DELETE FROM ".$wpdb->prefix."usermeta WHERE meta_key LIKE 'stories_prompted%' AND meta_value = $postid"; 	
			$wpdb->query($sql);
			$post = get_post($postid);
			// if you allow the next line, deletes will be irreversable
			//wp_delete_post( $postid, true ); 
			$args = array("id"=>"","action"=>"Deleted a story: '".$post->post_title."'","content"=>"We are sorry to see it go.","user_id"=>$post->post_author,"primary_link"=>"","type"=>"Deleted Story","component"=>"activity");
			
	
	bp_activity_add( $args );
		}
	
	

    // My custom stuff for deleting my custom post type here
}


/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function theme_name_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}
	
	global $page, $paged;

	// Add the blog name
	$title .= get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary:
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( __( 'Page %s', '_s' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'theme_name_wp_title', 10, 2 );




function mdebug($sql){
	 global $post_type, $wpdb;   
	$result = $wpdb->get_results($sql,ARRAY_A);
	foreach($result as $assoc){
	?>
<pre><? print_r($assoc); ?></pre>
<?	
	}

}





function login_redirect($atts){
	$user = wp_get_current_user(); 
		ob_start();
	if(empty( $user->user_nicename)){

		?>
Trying to view your stories? Click <a style="text-decoration:underline;" href="/wp-login.php?redirect_to=http://<? echo $_SERVER['HTTP_HOST']; ?>/author">here</a> to login first.
<?
	} else {
			
		?>
Trying to view your stories? Redirecting you now... 
<script>
		window.location = '/author/<? echo $user->user_nicename; ?>';
		</script>
<? }
	
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
		
		
	
	
}

add_shortcode("loginredirect", "login_redirect");



function the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '[...]';
	} else {
		echo $excerpt;
	}
}

 
 function getAdmins(){
	global $wpdb;
	$sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key LIKE '%capabilities%' AND meta_value LIKE '%administrator%' ORDER BY user_id ASC";
	$result = $wpdb->get_results($sql,ARRAY_A);
	foreach($result as $assoc){
		$admins[] = $assoc[user_id];	
	}
	return $admins;
}

 

// Filter wp_nav_menu() to add profile link
add_filter( 'wp_nav_menu_items', 'my_nav_menu_profile_link' );
function my_nav_menu_profile_link($menu) { 
$user = wp_get_current_user(); 
			$username = $user->display_name;
			$nicename = $user->user_nicename;
			
	$menu = str_replace("/author/username","/author/".$nicename,$menu);
	if(!preg_match("/account/",$menu)){
		if (!is_user_logged_in()){
			if(preg_match("/about/",$menu)){
				$menu = str_replace('<a href="/members/admin/profile/">My Account</a>','<a href="/wp-login.php">' . __('Sign In &amp; Start Sharing') . '</a>',$menu);	
				return $menu;
			} else {
				$profilelink = '<li class="lastLink"><a href="/wp-login.php">' . __('Sign In &amp; Start Sharing') . '</a></li>';
				$menu = $menu . $profilelink;
				return $menu;
			}
		} else{
			
			if(preg_match("/about/",$menu)){
				// BP my account = bp_loggedin_user_domain( '/' )
				$profilelink = '<a href="/members/'.$nicename.'/profile/">My Account</a>';
				$menu = str_replace('<a href="/members/admin/profile/">My Account</a>',$profilelink,$menu);
				//$menu = $menu . $profilelink;
				return $menu;	
			} else {
				if(current_user_can("administrator")){
					$comments = get_comments( array('status' => 'hold', 'count' => true) );
					$comments = get_comments( array('status' => 'hold','post_author' => $user->ID, 'count' => true) );
				} else {
					$comments = get_comments( array('status' => 'hold','post_author' => $user->ID, 'count' => true) );
				}
				
				
			// BP my account = bp_loggedin_user_domain( '/' )
			$profilelink = '<li class="lastLink menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children"><a href="">Hi '.$username.' </a><a class="ab-item" style="padding:10px 9px 3.4px 0px" href="/wp-admin/edit-comments.php" title="'.$comments.' comments awaiting moderation"><span class="dashicons dashicons-admin-comments"></span> '.$comments.'</a>
			<ul class="sub-menu">
			<li class="lastLink"><a href="/members/'.$nicename.'/profile/">My Profile</a></li>
			<li class="lastLink"><a href="'.wp_logout_url().'">Sign Out</a></li>
			</ul></li>';
			$menu = $menu . $profilelink;
			return $menu;
			}
		}
	} else {
		return $menu;	
	}
}

function email_address_login($username) {
	$user = get_user_by('email',$username);
	
	if(!empty($user->user_login)){
		$username = $user->user_login;
		return $username;
	} else {
		return false;
	}
	
}
add_action('wp_authenticate','email_address_login');





// custom admin login logo
function custom_login_logo() {

	?>
<script>
jQuery(function() {
 jQuery("#nav").prepend('<div class="dont">Don\'t have an account?</div>');
});
</script>
<style type="text/css">

		.fbc{
            
        }
		.login h1 a { 
        	background-image: url(/wp-content/themes/minn-lite/images/RetrospectLogoandtag.png) !important; 
	 		background-size: 245px;
			background-color: #B19E95;
            height: 68px;
            width: 100%;
        }
        
        
        .login #nav {
            margin: 2px 0 0 !important;
        }
         #nav a{
        	
           font-size:14px;
            padding: 4px;
        }
        
        *:focus{
        outline: 0!important;
        border: 0!important;
        }
        
        #nav .dont{
        	display: block;
            font-weight:bold;
            color: #005672;
            margin-top:10px;
        }
	</style>
<?
}
add_action('login_head', 'custom_login_logo');


function custom_login_login(){
	facebook('login','wp-login.php');
        
}
add_action( 'login_form', 'custom_login_login' );



// custom admin login logo
function custom_login_form() {
	//jfb_output_facebook_btn();
	//jfb_output_facebook_callback();
	//jfb_output_facebook_init();
	$user_id = get_current_user_id();
 
    if ( is_numeric( $user_id ) ){
		$user = get_userdata( $user_id );
	} else {
        $user = wp_get_current_user();
	}
 	
    if ( !empty( $user ) ){
		?>
<script>
		window.location = '/members/<? echo $user->user_nicename; ?>/profile/';
		</script>
<?
		exit;
	}
}

add_action('login_footer', 'custom_login_form');




add_action('user_profile_update_errors', 'validateProfileUpdate', 10, 3 );
add_filter('registration_errors', 'validateRegistration', 10, 3 );
//add_action('validate_password_reset', 'validatePasswordReset', 10, 2 );

function validateProfileUpdate( WP_Error &$errors, $update, &$user ) {
    return validateComplexPassword( $errors );
}

function validateRegistration( WP_Error &$errors, $sanitized_user_login, $user_email ) {
    return validateComplexPassword( $errors );
}

function validatePasswordReset( $errors, $userData ) {
    return validateComplexPassword( $errors );
}

function validateComplexPassword( $errors ) {

    $password = ( isset( $_POST[ 'pass1' ] ) && trim( $_POST[ 'pass1' ] ) ) ? $_POST[ 'pass1' ] : null;

    if ( empty( $password ) || ( $errors->get_error_data( 'pass' ) ) )
        return $errors;

    $passwordValidation = validatePassword($password);

    if ( $passwordValidation !== true ) {
        $errors->add( "pass", "<strong>ERROR</strong>: " . $passwordValidation . "." );
    }

    return $errors;
}

function validatePassword($Password) {
    //#### Check it's greater than 6 Characters
    if (strlen($Password) < 6) {
        return "Password is too short (" . strlen($Password) . "), please use 6 characters or more.";
    }

    //#### Test password has uppercase and lowercase letters
    if (preg_match("/^(?=.*[a-z])(?=.*[A-Z]).+$/", $Password) !== 1) {
        return "Password does not contain a mix of uppercase & lowercase characters.";
    }

    //#### Test password has mix of letters and numbers
    if (preg_match("/^((?=.*[a-z])|(?=.*[A-Z]))(?=.*\d).+$/", $Password) !== 1) {
        return "Password does not contain a mix of letters and numbers.";
    }

    //#### Password looks good
    return true;
}



function my_login_logo_url() {
	return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'my_login_logo_url' );


// ------- Check if member is a friend ------------- // 
function bp_displayed_user_is_friend() { 
global $bp; 
/* disabled friends for now
also disabled in buyypress/bp-templates\bp-legacy\buddypress\members\single\home.php line 18
'is_friend' != BP_Friends_Friendship::check_is_friend( $bp->loggedin_user->id, $bp->displayed_user->id )) &&  ((
*/
if ( bp_is_profile_component() || bp_is_member() ) { 
	if ( bp_loggedin_user_id() != bp_displayed_user_id() ){
	 if ( !is_super_admin( bp_loggedin_user_id() ) ) return true; 
	 }
} 
}

function mymo_parse_query_useronly( $wp_query ) {
  if(isset($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == 'attachment'){
    if ( !current_user_can( 'level_5' ) ) {
        $wp_query->set( 'author', get_current_user_id() );
    }
  }
}
add_filter('parse_query', 'mymo_parse_query_useronly' );


function get_sponsor(){
	$loop = new WP_Query( array( 'post_type' => 'sponsors','posts_per_page' => 3 , 'orderby'=> 'rand') );
	while ( $loop->have_posts() ) : $loop->the_post();
	?>
<div  class="widget-area">
  <div class="widget widget_meta"> <a href="<? echo  get_permalink();?>">
    <? the_post_thumbnail('full'); ?>
    <br>
    SPONSOR </a> </div>
</div>
<?
	endwhile;
}


add_filter( 'manage_edit-prompts_sortable_columns', 'prompts_table_sorting' );
function prompts_table_sorting( $columns ) {
  $columns['stories_embargo_until'] = 'stories_embargo_until';
  return $columns;
}

add_filter( 'request', 'bs_event_date_column_orderby' );
function bs_event_date_column_orderby( $vars ) {
	if($_GET[post_type] == "prompts"){
    if (!isset( $vars['orderby'] ) ||  (isset( $vars['orderby'] ) && 'stories_embargo_until' == $vars['orderby'] )) {
        $vars = array_merge( $vars, array(
            'meta_key' => 'stories_embargo_until',
            'orderby' => 'meta_value'
        ) );
		
		if(!isset($vars['order']) ){
			$vars['order'] = 'asc';
		}
    }

   
	}
	 return $vars;
}






function set_bp_message_content_type() {
    return 'text/html';
}
 
add_filter( 'bp_core_signup_send_validation_email_message', 'custom_buddypress_activation_message', 10, 3 );
 
function custom_buddypress_activation_message( $message, $user_id, $activate_url ) {
    add_filter( 'wp_mail_content_type', 'set_bp_message_content_type' );
    $user = get_userdata( $user_id );
    return 'Hi <strong>' . $user->user_login . '</strong>,
<br><br>
Thanks for registering! To complete the activation of your account,  <a href="' . $activate_url . '">click here</a>.
<br></br>After activation, you can sign in with your username: '.  $user->user_login  .' <br><br>
Thanks from the Retrospect Team';
}



function my_files_only( $wp_query ) {
    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
        if ( !current_user_can( 'administrator' ) ) {
            global $current_user;
            $wp_query->set( 'author', $current_user->id );
        }
    }
}

add_filter('parse_query', 'my_files_only' );


function wps_get_comment_list_by_user($clauses) {
	$temp = $clauses;
	
		
	
	if(current_user_can( 'administrator' )){
		return $temp;
	}
	if (!current_user_can( 'administrator' ) && is_admin()) {
		global $user_ID, $wpdb;
		$clauses['join'] = ", wp_posts";
		$clauses['where'] .= " AND ((wp_comments.user_id = ".$user_ID." AND wp_comments.comment_post_ID = wp_posts.ID) OR (wp_posts.post_author = ".$user_ID." AND wp_comments.comment_post_ID = wp_posts.ID)) ";
		////AND wp_comments.user_id = wp_posts.ID
		return $clauses;
	}
	if(!current_user_can( 'administrator' )){
		unset(	$clauses['join'] );
		unset(	$clauses['where'] );
		return $temp;
	}
	
}

add_filter('comments_clauses', 'wps_get_comment_list_by_user');


// comment header count filter 

add_filter( 'load-edit-comments.php', function() {
    add_filter( 'wp_count_comments', function( $stats, $post_id ){
        global $wpdb,  $current_user;
		static $instance = 0;
		if(!current_user_can( 'administrator' )){
        if(  2 === $instance++ ) {
            $stats = wp_count_comments( $stats, $post_id );
			
		
			
			
            // Set the trash count to 999
            if ( is_object( $stats ) && property_exists( $stats, 'trash' ) )
				$args = array(
					'post_author__in' => array($current_user->ID),
					'status' => 'trash',
					'count' => true //return only the count
				);
				$comments = get_comments($args);
                $stats->trash = $comments; // <-- Adjust this count
			 if ( is_object( $stats ) && property_exists( $stats, 'spam' ) )
			 	$args = array(
					'post_author__in' => array($current_user->ID),
					'status' => 'spam',
					'count' => true //return only the count
				);
				$comments = get_comments($args);
                $stats->spam = $comments; // <-- Adjust this count
			if ( is_object( $stats ) && property_exists( $stats, 'approved' ) )
				$args = array(
					'post_author__in' => array($current_user->ID),
					'status' => 'approve',
					'count' => true //return only the count
				);
				$comments = get_comments($args);
                $stats->approved = $comments; // <-- Adjust this count
			if ( is_object( $stats ) && property_exists( $stats, 'moderated' ) )
				$args = array(
					'post_author__in' => array($current_user->ID),
					'status' => 'hold',
					'count' => true //return only the count
				);
				$comments = get_comments($args);
                $stats->moderated = $comments; // <-- Adjust this count
			if ( is_object( $stats ) && property_exists( $stats, 'all' ) )
				
				$args = array(
					'post_author__in' => array($current_user->ID),
					'count' => true //return only the count
				);
				$comments = get_comments($args);
                $stats->all = $comments; // <-- Adjust this count
        }
		}
        return $stats;
	
    }, 10, 2 );
} );




add_action( 'wpmu_activate_user', 'stories_activate_user', 10, 3);
function stories_activate_user( $user_id, $password, $meta ) {
	
	add_user_meta( $user_id, 'retro_terms', $meta[retro_terms]);
	add_user_meta( $user_id, 'retro_privacy', $meta[retro_privacy]);
	//add_user_meta( $user_id, 'retro_beta', $meta[retro_beta]);
	
	$user = new WP_User( $user_id );
	$user->set_role( 'writer' );
	add_user_meta( $user_id, 'primary_blog', $meta[primary_blog]);
	
	add_user_meta( $user_id, 'retro_terms_date', date("Y-m-d"));
	add_user_meta( $user_id, 'retro_privacy_date', date("Y-m-d"));
	//add_user_meta( $user_id, 'retro_beta_date', date("Y-m-d"));
	
	add_user_meta( $user_id, 'retro_opt', $opt);
	
}
add_action('admin_footer', 'my_user_del_button');
function my_user_del_button() {
    $screen = get_current_screen();
	
	 if(!current_user_can( 'administrator' )){
		 global $wpdb,$current_user;
		 $args = array(
					'post_author__in' => array($current_user->ID),
					'status' => 'hold',
					'count' => true //return only the count
				);
		$count = get_comments($args);
		?>
        <script type="text/javascript">
        commentcount = '<? echo $count; ?>';
        </script>
        <?
	 }
	
    if ( $screen->id != "users" )   // Only add to users.php page
        return;
    ?>
<script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.wrap > h1 > a').after(' <a href="/wp-admin/options-general.php?page=retrospect-stories%2Fexport.php" class="page-title-action" target="_blank">Export Emails for MailChimp</a>      <a href="#" onclick="resetTerms();" class="page-title-action">Clear All Agreements/Terms</a> <a href="#" onclick="setPrivacy();" class="page-title-action">Set All Agreements/Terms</a>');
        });
        
        function resetTerms(){
            if(confirm('Are you sure? This will force every user to re-accept the terms and privacy policy when they sign in next time.')){
                jQuery.post(ajaxurl, {action: 'reset_terms'}, function(response) {
                	alert("Done.");    
                });	
            }
        }
        
         function setOptins(id){
           
                jQuery.post(ajaxurl, {action: 'set_optins', id: id}, function(response) {
                	 
                });	
            
        }
        
         function resetPrivacy(){
            if(confirm('Are you sure? This will force every user to re-accept the terms and privacy policy when they sign in next time.')){
                jQuery.post(ajaxurl, {action: 'reset_privacy'}, function(response) {
                	alert("Done.");    
                });	
            }
        }

		function setPrivacy(){
            if(confirm('Are you sure? This will SET EVERY USER as having accepted the terms and privacy policy.')){
                jQuery.post(ajaxurl, {action: 'set_privacy'}, function(response) {
                	alert("Done.");    
                });	
            }
        }
        
        function exportMailChimp(){
          
                jQuery.post(ajaxurl, {action: 'mailchimpexport'}, function(response) {
                	alert("Done.");    
                });	
           
        }
		
		
		 
		
    </script>
<?php
}

function mailchimpexport(){
	global $wpdb;
	$query ="SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'retro_opt' AND meta_value = 1";
	$output = $wpdb->get_results($query,ARRAY_A);
	
	foreach($output as $row) {
	 	$user =  get_userdata( $row[user_id] );
		
		echo $user->user_email;
	}
	wp_die(); // this is required to terminate immediately and return a proper response
}
add_action( 'wp_ajax_mailchimpexport', 'mailchimpexport' );



add_action( 'post_submitbox_misc_actions', 'anon_publish' );
function anon_publish($post)
{
    global $post;
	$value = get_post_meta($post->ID, 'stories_is_anonymous', true);
	if($post->post_type == "stories") {
    echo '<div class="misc-pub-section misc-pub-section-last">
         <span id="timestamp">'
         . '<label><input type="checkbox"' . (!empty($value) ? ' checked="checked" ' : null) . 'value="1" name="is_anonymous" /> Publish Anonymously</label>'
    .'</span></div>';
	}
}



function displayCharacterizations($post){
	global $wpdb;
	$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key LIKE '%stories_characterized%' AND post_id = $post->ID GROUP BY meta_value";
	
	$char = array();
	$result = $wpdb->get_results($sql,ARRAY_A);
	foreach($result as $assoc){
		$explode = explode(",",trim($assoc[meta_value]));
		foreach($explode as $value){
		$char[trim($value)] = trim($value);	
		}
	}
	asort($char);
	$temp = implode(", ",$char);
	if($temp != ""){ ?>
<div class="storyWidget"> <strong>Characterizations</strong>: <em>
  <? 
		echo $temp;
		?>
  </em> </div>
<? } 
	
}

function displayTags($post){
	if($post->stories_tags != ""){ ?>
<div class="storyWidget"> <strong>Tags</strong>: <em>
  <? 
		$temp = explode(",",$post->stories_tags);
		echo implode(", ",$temp);
		?>
  </em> </div>
<? } 
}

function showNumComments($post){
	global $post;
	?>
<div class="commentButton" style="">
  <?
	comments_popup_link('Be the first to comment!', '1 comment.', '% comments.'); 
	?>
</div>
<?
}


function showLikes($post){
	?>
<br>
<div class="clear"></div>
<!-- rename class fb-like to enable -->
<?
	  $user_id = get_current_user_id();
	  $voted = get_user_meta($user_id, "fs_votes_".$post->ID, $single);
	  if($user_id > 0){
	  ?>
<div class="fs_likebtn_container" id="<? echo $post->ID; ?>" style="" voted="<? echo $voted; ?>"></div>
<?php likebtn_post();  ?>
<div class="fb-likes" data-href="http://<? echo $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>" data-layout="button" data-action="like" data-show-faces="true" data-share="true"></div>
<img src="/wp-content/plugins/retrospect-stories/images/fake-db.png" style="cursor:pointer;display:none;"/>
<?
	  }
}

add_action( 'wp_enqueue_scripts', 'load_dashicons_front_end' );
function load_dashicons_front_end() {
	wp_enqueue_style( 'dashicons' );
}





function makePDF(){
	global $wpdb, $PDFtitle;
	// http://www.fpdf.org/
	$post = get_post($_POST[post_id]);
	
	$filename = $post->post_name . ".pdf";
	$anon = get_post_meta($post->ID, 'stories_is_anonymous', true);
	if($anon){
		$PDFtitle = $post->post_title . " by Anonymous";
	} else {
		$user = get_userdata($post->post_author);
		
		$PDFtitle = $post->post_title . " by " . $user->display_name;
	}
	$pdf=new PDF("P","in","Letter");
 
	$pdf->SetMargins(1,1,1);
	 
	$pdf->AddPage();
	$pdf->SetFont('Times','',12);
	$content = str_replace("[caption","IMAGE REMOVED[caption",$post->post_content);
	$content = $content;
	 
	
		$args = array(
			'post_type' => 'attachment',
			'post_status' => null,
			'post_parent' => $post->ID,
			'include' => $thumb_id,
			'post_mime_type' => 'image/jpeg'
		);
		$thumb_images = get_posts($args);
		foreach($thumb_images as $thumb_image) {

			$matches[0][0] = $thumb_image->guid;
		}
		
	 if($matches[0][0] == ""){
		 $matches = array();
		 preg_match_all('!http://.+\.(?:jpe?g|png|gif)!Ui' , $content , $matches);
	 }
	 $content = strip_shortcodes($content);
	 $content = str_replace("IMAGE REMOVED","[IMAGE REMOVED]",$content);
	$content = strip_tags($content);
	$content = str_replace("&amp;","&",$content);
	$content = str_replace("&nbsp;"," ",cleanInput($content));
	
	$content = str_replace("nbsp;"," ",$content);
	$content = str_replace("nbsp;","",$content);
	$content = str_replace("&amp;","",$content);
	$content = str_replace("‘","'",$content);
	$content = str_replace("’","'",$content);
	$content = str_replace("“",'"',$content);
$content = str_replace("”",'"',$content);
	$height = 1;
	if($matches[0][0] != ""){
		$height =4.4 ;
		$matches[0][0] = str_replace("alpha.","beta.",$matches[0][0]);
		$matches[0][0] = "http://".$_SERVER['HTTP_HOST']."/phpThumb/phpThumb.php?src=".$matches[0][0]."&w=629&h=242&zc=1&f=.jpg";
		
		$pdf->Image($matches[0][0], 1, 1.5);
	} 
	$pdf->SetFillColor(0, 86, 114);
	$pdf->SetFont('Times','B',14);
	  $pdf->SetTextColor(255,255,255);
	//Cell(float w[,float h[,string txt[,mixed border[,
	//int ln[,string align[,boolean fill[,mixed link]]]]]]])
	$pdf->Cell(0, .25, $post->post_title, 1, $height, "C", 1);
	 $pdf->SetFont('Arial','',12); 
	//$pdf->SetFont('Times','',12);
	if($matches[0][0] != ""){
		$pdf->setY($height);
	}
	  $pdf->SetTextColor(0,0,0);
	//$pdf->MultiCell(0, 0.3, $content, 0, "L");
	$content = utf8_decode($content );
	//$content = iconv('UTF-8', 'windows-1252', $str);
	$pdf->Write(.2, $content);
	
	
	
	$pdf->Output("F",$_SERVER['DOCUMENT_ROOT']."/pdfs/".$filename);	
	return $filename;
	
	
}
add_action( 'wp_ajax_makePDF', 'makePDF_callback' );

function cleanInput($data) {
    $data= strip_tags(trim($data));
  $search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
                   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
                   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
                   '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
    ); 
	
   $data = preg_replace($search, '', $data); 
  
   return $data;
}



class PDF extends FPDF {
 
	function Header() {
		global $PDFtitle;
		$this->SetFont('Times','',12);
		$this->SetY(0.25);
		$this->Cell(0, .25,  $PDFtitle. "  : page ".$this->PageNo(), 'T', 2, "R");
		//reset Y
		$this->SetY(1);
	}
	 
	function Footer() {
		//This is the footer; it's repeated on each page.
		//enter filename: phpjabber logo, x position: (page width/2)-half the picture size,
		//y position: rough estimate, width, height, filetype, link: click it!
		
	}
 
}

function storyCount($id){
	global $wpdb;
	
	
	$count = 0;
	
	$currentID = getCurrent();
	
	
		$sql = "SELECT * FROM  " . $wpdb->prefix . "posts WHERE post_author = " . $id . " AND post_status = 'publish' AND post_type = 'stories'";
		
		$lo = $wpdb->get_results($sql,ARRAY_A);
	
		foreach($lo as $pos){
			
			
		$anon = get_post_meta($pos[ID], 'stories_is_anonymous', true);
		$po = get_post($pos[ID]);
		$sql = "SELECT * FROM  " . $wpdb->prefix . "usermeta WHERE user_id = " . $po->post_author . " AND meta_key LIKE 'stories_prompted_%' AND meta_value = " . $po->ID;
		$result = $wpdb->get_results($sql,ARRAY_A);
	
		foreach($result as $prompt){
			$temp = explode("_", $prompt[meta_key]);
			$p = $temp[2];
			$prompted_post = get_post($p);
			$golive = $prompted_post->stories_embargo_until;
		
		}
		
		if(!$anon){
			if ($golive <= date("Y-m-d")){
				$count ++;
			}
		}
		}
	return $count;
}

function replace_howdy( $wp_admin_bar ) {
	$my_account=$wp_admin_bar->get_node('my-account');
	$newtitle = str_replace( 'Howdy,', 'Hi,', $my_account->title );
	$wp_admin_bar->add_node( array(
		'id' => 'my-account',
		'title' => $newtitle,
	) );
}
add_filter( 'admin_bar_menu', 'replace_howdy',25 );


add_action( 'wp_dashboard_setup', 'register_my_dashboard_widget' );
function register_my_dashboard_widget() {
 	global $wp_meta_boxes;

	wp_add_dashboard_widget(
		'my_dashboard_widget',
		'Publication Schedule',
		'my_dashboard_widget_display'
	);

 	$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

	$my_widget = array( 'my_dashboard_widget' => $dashboard['my_dashboard_widget'] );
 	unset( $dashboard['my_dashboard_widget'] );

 	$sorted_dashboard = array_merge( $my_widget, $dashboard );
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}
add_action( 'wp_dashboard_setup', 'register_my_dashboard_widget' );

function my_dashboard_widget_display() {
	if(current_user_can("administrator")){
	global $wpdb;
	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	$ch = explode(",",get_option('myth_charaterization'));
	$data = [];
	foreach($ch as $value){
		$value = trim($value);
		 $sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key LIKE '%stories_characterized%' AND meta_value LIKE '%$value%'";
		 $result = $wpdb->get_results($sql,ARRAY_A);
		   $total = sizeof($result);
		$data[] = [$value,$total];
	}
	
?>
<script type="text/javascript">
jQuery(function() {
	var p = jQuery("#bob-panel").parent().parent();
		jQuery("#bob-panel").insertBefore('#dashboard-widgets-wrap');
		p.remove();
		
		var data = <? echo json_encode($data); ?>;
		jQuery("#chart1").css({height:"200px"});
		jQuery.plot("#chart1", [ data ], {
			series: {
            	color: "#005672",
				bars: {
					show: true,
					barWidth: 0.2,
					align: "center",
                    fillColor: "#005672",
                    dataLabels: true
               }
			},
			xaxis: {
				mode: "categories",
				tickLength: 10
			},
			grid: {
				hoverable: true,
				clickable: true,
                borderColor: "#005672",
                margin: 5,
                hoverable: true
			}
		});
        
        jQuery("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80
		}).appendTo("body");
        
        jQuery("#chart1").bind("plothover", function (event, pos, item) {

			
				if (item) {
					var x = item.datapoint[0],
						y = item.datapoint[1];

					jQuery("#tooltip").html(y + " stories")
						.css({top: item.pageY+5, left: item.pageX+5})
						.fadeIn(200);
				} else {
					$("#tooltip").hide();
				}
			
		});
        
        });
	</script> 
<script language="javascript" type="text/javascript" src="<? echo  $pluginfolder ; ?>/flot/jquery.flot.js"></script> 
<script language="javascript" type="text/javascript" src="<? echo  $pluginfolder ; ?>/flot/jquery.flot.categories.js"></script>
<div id="bob-panel" class="welcome-panel" style="height:300px;">
  <div class="welcome-panel-content">
    <h2>Characterizations</h2>
    <p class="about-description">We’ve compiled a list of popular characterizations:</p>
    <div class="welcome-panel-column-container">
      <div id="chart1" class="chart-placeholder-1"></div>
    </div>
  </div>
</div>
<?
	}
}


/*
 * Function creates post duplicate as a draft and redirects then to the edit post screen
 */
function rd_duplicate_post_as_draft(){
	global $wpdb;
	error_reporting(E_ALL);
	ini_set('display_errors',1);
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No post to duplicate has been supplied!');
	}
 	
	/*
	 * get the original post id
	 */
	$post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);
	/*
	 * and all the original post data then
	 */
	$post = get_post( $post_id );

	/*
	 * if you don't want current user to be the new post author,
	 * then change next couple of lines to this: $new_post_author = $post->post_author;
	 */
	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;
 	
	/*
	 * if post data exists, create the post duplicate
	 */
	if (isset( $post ) && $post != null) {
 
		/*
		 * new post data array
		 */
		$args = array(
			'comment_status' => $post->comment_status,
			'ping_status'    => $post->ping_status,
			'post_author'    => $new_post_author,
			'post_content'   => $post->post_content,
			'post_excerpt'   => $post->post_excerpt,
			'post_name'      => $post->post_name,
			'post_parent'    => $post->post_parent,
			'post_password'  => $post->post_password,
			'post_status'    => 'draft',
			'post_title'     => $post->post_title,
			'post_type'      => $post->post_type,
			'to_ping'        => $post->to_ping,
			'menu_order'     => $post->menu_order
		);

		/*
		 * insert the post by wp_insert_post() function
		 */
		$new_post_id = wp_insert_post( $args );

		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
 
		/*
		 * duplicate all post meta just in two SQL queries
		 */
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
 
 
		/*
		 * finally, redirect to the edit post screen for the new draft
		 */
		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Post creation failed, could not find original post: ' . $post_id);
	}
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );

add_filter( 'post_row_actions', 'remove_row_actions', 10, 2 );

function remove_row_actions( $actions )
{
   global $post;
	if ($post->post_type=='prompts' && current_user_can('edit_posts')) {
		$temp = explode("post=",$actions[edit]);
		$temp = explode("&",$temp[1]);
		$id = $temp[0];
		$permalink = get_permalink($id);
		$view = '<a href="'.$permalink.'">View</a>';
		$actions[view] = $view;
		$actions['duplicate'] = '<a href="admin.php?action=rd_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
		return $actions;
	} else {
		/*
		$temp = explode("post=",$actions[edit]);
		$temp = explode("&",$temp[1]);
		$id = $temp[0];
		$permalink = get_permalink($id);
		$view = '<a href="'.$permalink.'">View</a>';
		$actions[view] = $view; 
		*/
		return $actions;
		
	}
   
	
   
}




add_filter( 'wp_mail_from', 'custom_wp_mail_from' );
function custom_wp_mail_from( $original_email_address ) {
	//Make sure the email is from the same domain 
	//as your website to avoid being marked as spam.
	return 'beta@myretrospect.com';
}


function wp_notify_moderator($comment_id) {

	global $wpdb,$current_user;

	$maybe_notify = get_option( 'moderation_notify' );

	/**
	 * Filter whether to send the site moderator email notifications, overriding the site setting.
	 *
	 * @since 4.4.0
	 *
	 * @param bool $maybe_notify Whether to notify blog moderator.
	 * @param int  $comment_ID   The id of the comment for the notification.
	 */
	$maybe_notify = apply_filters( 'notify_moderator', $maybe_notify, $comment_id );

	if ( ! $maybe_notify ) {
		return true;
	}

	$comment = get_comment($comment_id);
	$post = get_post($comment->comment_post_ID);
	$user = get_userdata( $post->post_author );
	// Send to the administration and to the post author if the author can modify the comment.
	$emails = array( get_option( 'admin_email' ) );
	if ( $user && user_can( $user->ID, 'edit_comment', $comment_id ) && ! empty( $user->user_email ) ) {
		if ( 0 !== strcasecmp( $user->user_email, get_option( 'admin_email' ) ) )
			$emails[] = $user->user_email;
	}

	$comment_author_domain = @gethostbyaddr($comment->comment_author_IP);
	$comments_waiting = $wpdb->get_var("SELECT count(comment_ID) FROM $wpdb->comments WHERE comment_approved = '0'");

	// The blogname option is escaped with esc_html on the way into the database in sanitize_option
	// we want to reverse this for the plain text arena of emails.
	$blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
	$comment_content = wp_specialchars_decode( $comment->comment_content );

	switch ( $comment->comment_type ) {
		case 'trackback':
			$notify_message  = sprintf( __('A new trackback on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
			$notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
			/* translators: 1: website name, 2: website IP, 3: website hostname */
			$notify_message .= sprintf( __( 'Website: %1$s (IP: %2$s, %3$s)' ), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			$notify_message .= sprintf( __( 'URL: %s' ), $comment->comment_author_url ) . "\r\n";
			$notify_message .= __('Trackback excerpt: ') . "\r\n" . $comment_content . "\r\n\r\n";
			break;
		case 'pingback':
			$notify_message  = sprintf( __('A new pingback on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
			$notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
			/* translators: 1: website name, 2: website IP, 3: website hostname */
			$notify_message .= sprintf( __( 'Website: %1$s (IP: %2$s, %3$s)' ), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			$notify_message .= sprintf( __( 'URL: %s' ), $comment->comment_author_url ) . "\r\n";
			$notify_message .= __('Pingback excerpt: ') . "\r\n" . $comment_content . "\r\n\r\n";
			break;
		default: // Comments
			$notify_message  = sprintf( __('A new comment on the post "%s" is waiting for your approval'), $post->post_title ) . "\r\n";
			$notify_message .= get_permalink($comment->comment_post_ID) . "\r\n\r\n";
			$notify_message .= sprintf( __( 'Author: %1$s (IP: %2$s, %3$s)' ), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain ) . "\r\n";
			$notify_message .= sprintf( __( 'Email: %s' ), $comment->comment_author_email ) . "\r\n";
			$notify_message .= sprintf( __( 'URL: %s' ), $comment->comment_author_url ) . "\r\n";
			$notify_message .= sprintf( __( 'Comment: %s' ), "\r\n" . $comment_content ) . "\r\n\r\n";
			break;
	}

	$notify_message .= sprintf( __('Approve it: %s'),  admin_url("comment.php?action=approve&c=$comment_id") ) . "\r\n";
	if ( EMPTY_TRASH_DAYS )
		$notify_message .= sprintf( __('Trash it: %s'), admin_url("comment.php?action=trash&c=$comment_id") ) . "\r\n";
	else
		$notify_message .= sprintf( __('Delete it: %s'), admin_url("comment.php?action=delete&c=$comment_id") ) . "\r\n";
	$notify_message .= sprintf( __('Spam it: %s'), admin_url("comment.php?action=spam&c=$comment_id") ) . "\r\n";
	if(!current_user_can( 'administrator' )){
		
		$args = array(
				'post_author__in' => array($user->ID),
				'status' => 'hold',
				'count' => true //return only the count
				);
		$comments_waiting = get_comments($args);
		
	}
	$notify_message .= sprintf( _n('Currently %s comment is waiting for approval. Please visit the moderation panel:',
 		'Currently %s comments are waiting for approval. Please visit the moderation panel:', $comments_waiting), number_format_i18n($comments_waiting) ) . "\r\n";
	$notify_message .= admin_url("edit-comments.php?comment_status=moderated") . "\r\n";

	$subject = sprintf( __('[%1$s] Please moderate: "%2$s"'), $blogname, $post->post_title );
	$message_headers = '';

	/**
	 * Filter the list of recipients for comment moderation emails.
	 *
	 * @since 3.7.0
	 *
	 * @param array $emails     List of email addresses to notify for comment moderation.
	 * @param int   $comment_id Comment ID.
	 */
	$emails = apply_filters( 'comment_moderation_recipients', $emails, $comment_id );

	/**
	 * Filter the comment moderation email text.
	 *
	 * @since 1.5.2
	 *
	 * @param string $notify_message Text of the comment moderation email.
	 * @param int    $comment_id     Comment ID.
	 */
	$notify_message = apply_filters( 'comment_moderation_text', $notify_message, $comment_id );

	/**
	 * Filter the comment moderation email subject.
	 *
	 * @since 1.5.2
	 *
	 * @param string $subject    Subject of the comment moderation email.
	 * @param int    $comment_id Comment ID.
	 */
	$subject = apply_filters( 'comment_moderation_subject', $subject, $comment_id );

	/**
	 * Filter the comment moderation email headers.
	 *
	 * @since 2.8.0
	 *
	 * @param string $message_headers Headers for the comment moderation email.
	 * @param int    $comment_id      Comment ID.
	 */
	$message_headers = apply_filters( 'comment_moderation_headers', $message_headers, $comment_id );

	foreach ( $emails as $email ) {
		@wp_mail( $email, wp_specialchars_decode( $subject ), $notify_message, $message_headers );
	}

	return true;
}





 function create_slug($string){     
        $replace = '-';  
		$string = strtolower($string);     
		
        //replace / and . with white space     
        $string = preg_replace("/[\/\.]/", " ", $string);     
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);     
		
        //remove multiple dashes or whitespaces     
        $string = preg_replace("/[\s-]+/", " ", $string);     
		
        //convert whitespaces and underscore to $replace     
        $string = preg_replace("/[\s_]/", $replace, $string);     
		
        //limit the slug size     
        $string = substr($string, 0, 100);     
		
        //slug is generated     
        return $string; 
    }  
	
	
	add_action( 'password_reset', 'my_password_reset', 10, 2 );

    function my_password_reset( $user, $new_pass ) {
        if(preg_match('/google/',$user->user_url)){
				$u = '<a href="'.$user->user_url.'" target="_blank">Google</a>';
			}
			if(preg_match('/facebook/',$user->user_url)){
				if($u != ""){
					$u .="<br>";	
				}
				$u .= '<a href="'.$user->user_url.'" target="_blank">Facebook</a>';
			}
			if($u != ""){
				?><body class="login login-action-lostpassword wp-core-ui  locale-en-us" cz-shortcut-listen="true">
                
	<div id="login">
		<h1><a href="/" title="Retrospect" tabindex="-1">Retrospect</a></h1>
	
<div id="login_error">	Your account was created using either Facebook or Google. A password change cannot be effected from this site. Please visit Facebook or Google to change your password.<br>
</div>





	<p id="backtoblog"><a href="http://beta.myretrospect.com/" title="Are you lost?">← Back to Retrospect</a></p>
	
	</div>

	
<link rel='stylesheet' id='buttons-css'  href='/wp-includes/css/buttons.min.css?ver=4.4.2' type='text/css' media='all' />

<link rel='stylesheet' id='dashicons-css'  href='/wp-includes/css/dashicons.min.css?ver=4.4.2' type='text/css' media='all' />
<link rel='stylesheet' id='login-css'  href='/wp-admin/css/login.min.css?ver=4.4.2' type='text/css' media='all' />	
<link rel="stylesheet" id="story-admin-styles-css" href="/wp-content/plugins/retrospect-stories/css/style.css?ver=4.4.2" type="text/css" media="all">
<style type="text/css">

		.fbc{
            
        }
		.login h1 a { 
        	background-image: url(/wp-content/themes/minn-lite/images/RetrospectLogoandtag.png) !important; 
	 		background-size: 245px;
			background-color: #B19E95;
            height: 68px;
            width: 100%;
        }
        
        
        .login #nav {
            margin: 2px 0 0 !important;
        }
         #nav a{
        	
           font-size:14px;
            padding: 4px;
        }
        
        *:focus{
        outline: 0!important;
        border: 0!important;
        }
        
        #nav .dont{
        	display: block;
            font-weight:bold;
            color: #005672;
            margin-top:10px;
        }
	</style>
	<div class="clear"></div>
	
	
	</body><?
				exit;
			}
    } 
	
	
	// custom login for theme
function childtheme_custom_login() {
	if($_GET[action] == "rp"){
	?>
    <style>
	.message.reset-pass{
		display:none;	
	}
	</style>
    <script>
	jQuery(document).ready(function(){
		jQuery(".message.reset-pass").after('<div class="message reset-pass2">You can use the complex password provided below or replace it with your own new password. Write it down or save it because you will need it on the next screen. <p></p><p style="margin-top:10px;">Click ‘Reset Password’ to save it and continue.</p></div>');
		
		jQuery(".description.indicator-hint").html('Hint: To strengthen your password, make it longer and use a mix of upper and lowercase letters, numbers, and symbols like ! “ ? $ ^ & ).');
		jQuery(".indicator-hint").append('');
	});
	</script>
    <?	
		
	}
}
 
add_action('login_head', 'childtheme_custom_login');

 
function theDeets($post_id){
	$post = get_post($post_id);
	$firsttime = date("Y-m-d",strtotime(get_post_meta( $post->ID, 'first_time' , true)));
	
	if($firsttime <= date("Y-m-d")){
		$anon = get_post_meta($post->ID, 'stories_is_anonymous', true);
		$author = get_userdata($post->post_author);
		
	?>
 <li style="margin-top:10px;">
    <strong><a href="<? echo get_permalink($post->ID); ?>" title="<? echo esc_attr($post->post_title); ?>" rel="bookmark"><? echo $post->post_title; ?></a></strong>
    <div>by <span class="author">
     
      <?php if ($anon) { ?>
      Anonymous
      <?php } else { ?>
    
      <a href="/author/<?php echo $author->user_nicename; ?>"><?php echo $author->display_name; ?></a>
      <?php } ?>
      </span> </div>
     <?
	}
}

function add_shared_tab() {
	global $bp;
 
	if(bp_is_group()) {
		bp_core_new_subnav_item( 
			array( 
				'name' => 'Shared Stories', 
				'slug' => 'shared', 
				'parent_slug' => $bp->groups->current_group->slug, 
				'parent_url' => bp_get_group_permalink( $bp->groups->current_group ), 
				'position' => 11, 
				'item_css_id' => 'nav-activity',
				'screen_function' => 'bp_custom_shared_nav_item_screen',
				'user_has_access' => 1
			) 
		);
 
		if ( bp_is_current_action( 'activity' ) ) {
			add_action( 'bp_template_content_header', create_function( '', 'echo "' . esc_attr( 'Shared Stories' ) . '";' ) );
			add_action( 'bp_template_title', create_function( '', 'echo "' . esc_attr( 'Shared Stories' ) . '";' ) );
		}
	}
}
 
add_action( 'bp_actions', 'add_shared_tab', 8 );



function bp_custom_shared_nav_item_screen(){
	add_action( 'bp_template_content', 'bp_custom_screen_content' );
    bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'groups/single/shared' ) );
		
}
function bp_custom_screen_content() {
 global $bp,$wpdb;

 $user_id = get_current_user_id();
  $group_id = bp_get_group_id();
 $group_id;
 $sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'shared-with-group-".$group_id."' GROUP BY post_id  ORDER BY meta_id ASC";
?><p><?
 $result = $wpdb->get_results($sql,ARRAY_A);
 
 foreach($result as $row){
	 $post__in[] = $row[post_id];
		$post = get_post($row[post_id]);
		// ?><li type="i" style="display:none;"><a href="<? echo get_permalink($row[post_id]); ?>"><? echo $post->post_title; ?></a></li><?
 }
 ?></p>
  <input type="hidden" id="post__in" value="<? echo implode(",",$post__in); ?>" />
   <div class="<? if($user_id > 0 ){ ?>ajaxstories<? } ?>">
   <? if($user_id ==  0 ){ ?>Login to view shared stories.<? } ?>
   </div>
 <? if($user_id > 0 ){ ?>
 <aside id="primary-sidebar" class="sidebar-container filter-sidebar" role="complementary" style="display:none;">
 <!-- this container gets moved with jquery to a sidebar position using jQuery(".filter-sidebar").insertAfter("main"); -->
	<div id="primary-post-widget-area" class="widget-area">
    
     <!-- THE FILTER -->
    
     <div style="float:left;width: 100%;" class="filterdiv">
       <strong>Filter By </strong><br><hr />
       <?
       $ch = explode(",",get_option('myth_charaterization'));
		foreach($ch as $key=>$value){
		$value = trim($value);
		?>
  		<label><? echo $value; ?> <input type="checkbox"  class="afilter"   value="<? echo $value; ?>"> </label> 
  		<br>
    <? } ?>
   
      
       <label>stories I've read<input type="checkbox"  class="afilter"  value="read"  /> </label> 
       <label>stories I haven't read <input type="checkbox"  class="afilter"  value="notread" /> </label>
       <label>people I follow <input type="checkbox" class="afilter" value="follow" /> </label> 

      
       </div>
       
   
     <!-- THE SORT -->
    <hr />
     <div style="float:left;width: 100%;">
      <strong>Sort By</strong> <select class="sorter">
     <option value="0">newest first</option>
     <option value="1">oldest first</option>
     <option value="2">most popular first</option>
     <option value="3">surprise me</option>
     </select>
     </div>
      <div class="clear"></div>
     <hr />
         
      
    </div>
    </aside>
 <?
 }
  
 
}


# Fire Email when comments is inserted and is already approved.

add_action('wp_insert_comment','retrospect_comment_notification',100,2);



function retrospect_comment_notification($comment_id, $comment_object) {
	global $wpdb;
	// populate comment author email should prevent them from getting an email
	$sent = $comment_object->comment_author_email;
	
	$comment_parent = get_comment($comment_object->comment_parent);
	$headers  = 'MIME-Version: 1.0' . "\r\n";

	$parent_author = $comment_parent->user_id;
	$parent_id = $comment_parent->comment_ID;

	// INSERT NOTIFICATION SETTINGS CHECK HERE
    if ($comment_object->comment_approved) {
		
		echo $_SERVER['DOCUMENT_ROOT'];
		// send to author of comment that was replied to
		if ($parent_id) {	
		
				 // if its a response
				 
				$msg = get_option('myth_event_7');
				 
				$msg .= "\n\nComment:  ".$comment_object->comment_content."\n\n";
				
				$commenter = $comment_object->comment_author;
				$msg = str_replace('{$commenter}',$commenter,$msg);
				
				$notify = bp_get_user_meta( $parent_author, 'notification_story_comment_reply', true ) ;
				
				sendNotify(get_option('myth_event_7_s'),$msg,$parent_author,$comment_object->comment_post_ID, $notify, "View the story and reply to this comment at");
				
				
			}
			
		
	}
	ob_start();
	echo "Comment\n------------\n";
	print_r($comment_object);
	echo "Parent\n------------\n";
	print_r($comment_parent);
	$log = ob_get_contents();
	ob_clean();
	
	$fp = fopen($_SERVER['DOCUMENT_ROOT']."/logs/".date("Y-m-d-H-i-s").".txt","w");
	fwrite($fp,$log);
	fclose($fp);
	
}

# Fire Email when comments gets approved later.
add_action('wp_set_comment_status','retospect_comment_status_changed',99,2);
function retospect_comment_status_changed($comment_id, $comment_status) {
	$comment_object = get_comment( $comment_id );
	if ($comment_status == 'approve' || current_user_can("administrator")) {
		//retrospect_comment_notification($comment_object->comment_ID, $comment_object);        
	} 

}





function list_categories($atts, $content = null) {
	  global $wpdb;
    	ob_start();
		
		$parent = $atts[child_of];	
		global $level;
		$level = 0;
		?><ul><?
		$categories = get_categories('orderby=name&title_li=&use_desc_for_title=1&parent='.$parent); 
		
		foreach ($categories as $cat) {
			subCategoryHelp($cat,$parent);
		}
		?></ul><?
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
  }

function subCategoryHelp($cat,$parent){
	  
	
				if(1){ 
					?><li class="help-cat">
                    <a style="font-weight:bold!important;" href="/category/<?php echo $cat->slug; ?>" id="<?php echo $cat->slug; ?>" data-filter=".<?php echo $cat->slug; ?>"<h2><?= $cat->cat_name; ?></h2></a><ul><?
					$c = get_categories('orderby=name&parent='.$cat->cat_ID); 
				
					$args = array(
							'cat'   => $cat->cat_ID,
							'orderby'          => 'date',
							'order'            => 'DESC',
							'depth' 			=> 1
							
						);
						$pp = get_posts( $args );
						foreach($pp as $p){
							$par = wp_get_post_categories ($p->ID);
							if($par[0] == $cat->cat_ID){
						?><li class="help-post"><a href="<? echo get_permalink($p->ID); ?>"><? echo $p->post_title; ?></a></li><?
						
							}}
					foreach ($c as $sub) {
						subCategoryHelp($sub,$sub->cat_ID);
					}
					?>
				</li></ul></li>
                    <?
				}
				
		
	  
	  
  }

add_shortcode( 'categories', 'list_categories');

function story_edit_user_profile_html($user) {
                
      if(current_user_can("administrator")){  
	  $e =  get_user_meta($user->ID, 'metrics_exclude', true);
?>
        <h3><?php esc_html_e('Metrics', 'user-role-editor'); ?></h3>
        <table class="form-table">
        		<tbody>
                
                
          <tr>
              <th>Exclude from Metrics</th>
              <td>
              <label><input type="checkbox" <? if($e){ ?>checked<? } ?> name="metrics_exclude" value="1" /></label>
                            </td>
          </tr>    
        </tbody></table>
<?php
	  }
        
}

 add_action( 'edit_user_profile', 'story_edit_user_profile_html', 10, 1 );
 
 add_action('edit_user_profile_update', 'update_extra_profile_fields');
 
 function update_extra_profile_fields($user_id) {
    if(current_user_can("administrator")){  
		if(!empty($_POST[metrics_exclude])){
			 update_user_meta($user_id, 'metrics_exclude', 1);
		} else {
			  delete_user_meta($user_id, 'metrics_exclude');
		}
	}
 }
 
 


function disable_self_trackback( &$links ) {
  foreach ( $links as $l => $link )
        if ( 0 === strpos( $link, get_option( 'home' ) ) )
            unset($links[$l]);
}

add_action( 'pre_ping', 'disable_self_trackback' );

function set_content_type($content_type){
	return 'text/html';
}
//add_filter('wp_mail_content_type','set_content_type');


function my_deleted_user($user_id) {
		global $wpdb;
		
		$sql = "INSERT INTO ".$wpdb->prefix."usermeta VALUES ('',2,'metric_user_deleted','".date("Y-m-d")."')";
		$result = $wpdb->get_results($sql,ARRAY_A);
       
}
add_action( 'deleted_user', 'my_deleted_user' );


function get_the_content_with_formatting ($more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
	$content = get_the_content($more_link_text, $stripteaser, $more_file);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}