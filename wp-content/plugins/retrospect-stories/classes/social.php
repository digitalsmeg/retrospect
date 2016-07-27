<?

/*
 * 
 * 
 *  
 * Facebook / Google Functions
 * 
 * 
 *
 */
 
 require_once( __DIR__.'/../google-api-php/src/Google/autoload.php' );
require_once( __DIR__.'/../facebook-php-sdk-v4/autoload.php' );
use Facebooks\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


function initFB(){
	$facebook = new Facebook(array(
	  'appId'  => '160717930948216',
	  'secret' => 'ddbb26c6ab38a080f9b44b5f09562f07'
	));
	return $facebook;

}

function facebook($p,$u){
	
	
	if(!session_id()) {
        session_start();
    }
	if(!empty($_GET['redirect_to'])){
		$_SESSION['redirect_to'] = $_GET['redirect_to'];	
		
	}
	global $wpdb;
	
	error_reporting(E_ERROR);
	ini_set('display_errors', 1);
	try {
			
			
			
			FacebookSession::setDefaultApplication('160717930948216', 'ddbb26c6ab38a080f9b44b5f09562f07');
			$helper = new FacebookRedirectLoginHelper('http://'.$_SERVER['HTTP_HOST']."/wp-login.php");
			
			try {
			  $session = $helper->getSessionFromRedirect();
			} catch( FacebookRequestException $ex ) {
			  // When Facebook returns an error
			} catch( Exception $ex ) {
			  // When validation fails or other local issues
			}
			
			if ( isset( $session ) ) {
				
				$_SESSION[access_token] = $_GET[code] ;
			 	$request = new FacebookRequest( $session, 'GET', '/me?locale=en_US&fields=name,email');
				$response = $request->execute();
				// get response
				$graphObject = $response->getGraphObject();
			
				$name = $graphObject->getProperty('name');
				$id = $graphObject->getProperty('id');
				$email = $graphObject->getProperty('email');
			  	fBcallback($graphObject);
			} else {
				$loginUrl =  $helper->getLoginUrl( array( 'email', 'user_friends','public_profile') );
				if($p == "reg"){ ?>
<div class="fbButton margin-top"> <a href="<? echo $loginUrl ; ?>"  class="button wide minor facebook-button cd-login-button inline-block mufb-login-button J_onClick" data-scope="email,user_friends" data-onlogin="loginByClick"> <span class=" mufb-login-button J_onClick">Register with Facebook</span> </a> </div>
<a class="button googleTie wide" href="<? echo googleSignIn(); ?>"> <span class="google-button-title"> Register with Google </span> </a>
<?
						}
						if($p == "login"){
							
							?>
<p><a href="<? echo googleSignIn(); ?>"><img src="/wp-content/plugins/retrospect-stories/images/google-login.png" /></a></p>
<p> <a href="<? echo $loginUrl ; ?>"><img src="/wp-content/plugins/retrospect-stories/images/fb-login.png" /></a> </p>
<?
						}
			}
			
		}  catch (FacebookRequestException $ex) {
		  echo $ex->getMessage();
		} catch (\Exception $ex) {
		  echo $ex->getMessage();
		}
		
	
	

 
  

	
	
}
function fBcallback($graphObject){
	
		
			global $wpdb;
				$name = $graphObject->getProperty('name');
				$id = $graphObject->getProperty('id');
				$email = $graphObject->getProperty('email');
				$temp = explode("@", $email);
			 	$username =  $temp[0]; 
			  	$query ="SELECT * FROM ".$wpdb->prefix."users WHERE user_email = '$email'";
		
		 $output = $wpdb->get_results($query,ARRAY_A);
	
			foreach($output as $row) {
				
				$user =  get_userdata( $row[ID] );
			}
			if($user->ID > 0){
				// user exists
				$user_id = $user->ID;
				$user_login = $user->user_login;
			} else {
				// create user	
				$password =  wp_hash_password( $id.$email );
				$user_id = wp_create_user( $username, $password, $email );
				$user =  get_userdata( $user_id );
				$user = new WP_User( $user_id );
				$user->set_role( 'writer' );
				$user_login = $user->user_login;
				wp_new_social_user_notification( $user_id, "Facebook", "admin" );
				fix_reg_date($user_id);
			}
			wp_update_user( array( 'ID' => $user_id, 'user_url' => "https://www.facebook.com/".$id ) );
			
			
			wp_set_current_user( $user_id, $user_login );
        	wp_set_auth_cookie( $user_id );
        	do_action( 'wp_login', $user_login );
			//header( 'Location: /members/'.$user->user_nicename.'/profile/');
			if(!empty($_SESSION['redirect_to'])){
				$rd = $_SESSION['redirect_to'];
				unset($_SESSION['redirect_to']);
				wp_redirect( $rd ); exit;
			} else {
				wp_redirect( home_url() ); exit;
			}

	
	
}


add_shortcode("fBcallback", "fBcallback");


function googleCallback(){
	session_start();
	
	global $wpdb;
	
	// google stuff
	$client_id = '855876561036-8l3cmjdi5v03nrc0t8j5cu0529ipvu2t.apps.googleusercontent.com';
	$client_secret = 'eJ3l0F_HUF9NuSEIX3ovmXbF';
	$redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].'/googlecallback';
	$client = new Google_Client();
	$client->setClientId($client_id);
	$client->setClientSecret($client_secret);
	$client->setRedirectUri($redirect_uri);
	try {
	$client->setScopes('email');
	if (isset($_GET['code'])) {
	  $client->authenticate($_GET['code']);
	  $_SESSION['access_token'] = $client->getAccessToken();
	  $client->setAccessToken($_SESSION['access_token']);
	}
	if ($client->getAccessToken()) {
	  $_SESSION['access_token'] = $client->getAccessToken();
	  $token_data = $client->verifyIdToken()->getAttributes();
	}
	
	if (isset($token_data)) {
		if($token_data[payload][email_verified]){
			 $email = $token_data[payload][email];	
			 $temp = explode("@", $email);
			 $username =  $temp[0]; 
			 $id = $token_data[payload][sub];	
			 $query ="SELECT * FROM ".$wpdb->prefix."users WHERE user_email = '$email'";
			 $output = $wpdb->get_results($query,ARRAY_A);
		
				foreach($output as $row) {
					
					$user =  get_userdata( $row[ID] );
				}
				if($user->ID > 0){
					// user exists
					$user_id = $user->ID;
					$user_login = $user->user_login;
				} else {
					// create user	
					$password =  wp_hash_password( $id.$email );
					$user_id = wp_create_user( $username, $password, $email );
					$user =  get_userdata( $user_id );
					$user = new WP_User( $user_id );
					$user->set_role( 'writer' );
					$user_login = $user->user_login;
					wp_new_social_user_notification( $user_id, "Google", "admin" );
					fix_reg_date($user_id);
				}
				wp_update_user( array( 'ID' => $user_id, 'user_url' => "https://plus.google.com/u/0/".$id ) );
				
				
				wp_set_current_user( $user_id, $user_login );
				wp_set_auth_cookie( $user_id );
				do_action( 'wp_login', $user_login );
				//header( 'Location: /members/'.$user->user_nicename.'/profile/'); 
				if(!empty($_SESSION['redirect_to'])){
					$rd = $_SESSION['redirect_to'];
					unset($_SESSION['redirect_to']);
					wp_redirect( $rd ); exit;
				} else {
					wp_redirect( home_url() ); exit;
				}
		}
	  
	}
	} catch (Exception $e) {
		  // The Graph API returned an error
		  echo "$e";
	}
	
	
	
	
	
	
	
}
add_shortcode("googleCallback", "googleCallback");

function googleSignIn(){
	$client_id = '855876561036-8l3cmjdi5v03nrc0t8j5cu0529ipvu2t.apps.googleusercontent.com';
	$client_secret = 'eJ3l0F_HUF9NuSEIX3ovmXbF';
	$redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].'/googlecallback';
	$client = new Google_Client();
	$client->setClientId($client_id);
	$client->setClientSecret($client_secret);
	$client->setRedirectUri($redirect_uri);
	$client->setScopes('email');
	$loginUrl = $client->createAuthUrl();
	return $loginUrl;
}

// https://github.com/google/google-api-php-client/blob/v1-master/examples/idtoken.php



function wp_new_social_user_notification( $user_id, $social, $notify = '' ) {
   
    global $wpdb, $wp_hasher;
    $user = get_userdata( $user_id );
 
    // The blogname option is escaped with esc_html on the way into the database in sanitize_option
    // we want to reverse this for the plain text arena of emails.
    $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
 
    $message  = sprintf(__('New '.$social.'+ user registration on your site %s:'), $blogname) . "\r\n\r\n";
    $message .= sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
    $message .= sprintf(__('Email: %s'), $user->user_email) . "\r\n";
 
    @wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), $blogname), $message);
 
    // `$deprecated was pre-4.3 `$plaintext_pass`. An empty `$plaintext_pass` didn't sent a user notifcation.
    if ( 'admin' === $notify || ( empty( $deprecated ) && empty( $notify ) ) ) {
        return;
    }
 	return;
    // Generate something random for a password reset key.
    $key = wp_generate_password( 20, false );
 
    /** This action is documented in wp-login.php */
    do_action( 'retrieve_password_key', $user->user_login, $key );
 
    // Now insert the key, hashed, into the DB.
    if ( empty( $wp_hasher ) ) {
        require_once ABSPATH . WPINC . '/class-phpass.php';
        $wp_hasher = new PasswordHash( 8, true );
    }
    $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
    $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $user->user_login ) );
 
    $message = sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
    $message .= __('To set your password, visit the following address:') . "\r\n\r\n";
    $message .= '<' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . ">\r\n\r\n";
 
    $message .= wp_login_url() . "\r\n";
 
    wp_mail($user->user_email, sprintf(__('[%s] Your username and password info'), $blogname), $message);
}