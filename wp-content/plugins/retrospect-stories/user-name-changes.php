<h2>Retrospect - Change User Names</h2>
<? if(!empty($_POST[ID])){ 
if($_POST[ID] > 1){ 
		$sql = "SELECT * FROM  ".$wpdb->prefix."users WHERE ID != $_POST[ID] AND user_login = '$_POST[user_login]'";
		$result = $wpdb->get_results($sql,ARRAY_A);
		foreach($result as $row){
			$errors[] = "This username [".$_POST[user_login]."] is already in use.";	
		}
		$sql = "SELECT * FROM  ".$wpdb->prefix."users WHERE ID != $_POST[ID] AND user_email = '$_POST[user_email]'";
		$result = $wpdb->get_results($sql,ARRAY_A);
		foreach($result as $row){
			$errors[] = "This email [".$_POST[user_email]."] is already in use.";	
		}
	$slug = create_slug($_POST[user_login]);
	if(sizeof($errors) == 0){ 
		$sql = "UPDATE ".$wpdb->prefix."users SET user_login = '$_POST[user_login]' WHERE ID = $_POST[ID]";
		$result = $wpdb->query($sql);
		wp_update_user( array( 
			'ID' =>$_POST[ID], 
			'user_login' => $_POST[user_login],
			'user_email' => $_POST[user_email],  
			'user_nicename' => $slug, 
			'display_name' => $_POST[display_name])
		);
		?><div id="message" class="updated notice is-dismissible"><p>Information has been changed for this user.</p></div><?
	}
 } }
 ?>
<? if(empty($_GET[edit])){ ?>
<div id="post-query-submit"></div>
<div style="clear:both;margin-top:50px;"></div>
<table class="wp-list-table widefat fixed striped users">
  <thead>
    <tr>
      <td class="manage-column"><span>Username</span></td>
      <td class="manage-column"><span>Nicename</span></td>
      <td class="manage-column"><span>Name</span></td>
      <td class="manage-column"><span>Email</span></td>
      <td class="manage-column"><span>Facebook/Goggle</span></td>
      <td class="manage-column"><span>Action</span></td>
    </tr>
  </thead>
  <tbody id="the-list">
    <?
	  $sql = "SELECT * FROM  ".$wpdb->prefix."users WHERE ID > 1";
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		foreach($result as $row){
			$first = get_user_meta($row[ID], 'first_name',true);
			$last = get_user_meta($row[ID], 'last_name',true);
		?>
    <tr valign="top">
      <td><? echo $row[user_login]; ?>
        <div class="row-actions"><span class="edit"><a href="options-general.php?page=mythos-stories%2Fuser-name-changes.php&edit=<? echo $row[ID]; ?>">Edit</a></span></div></td>
      <td><? echo $row[user_nicename]; ?></td>
      <td><? echo $first." ".$last; ?></td>
      <td><? echo $row[user_email]; ?></td>
      <td><?
		$user =  get_userdata( $row[ID] );
		$u = "";
			if(preg_match('/google/',$user->user_url)){
				$u = '<a href="'.$user->user_url.'" target="_blank">Google</a>';
			}
			if(preg_match('/facebook/',$user->user_url)){
				if($u != ""){
					$u .="<br>";	
				}
				$u .= '<a href="'.$user->user_url.'" target="_blank">Facebook</a>';
			}
			echo $u;
			?></td>
      <td><a target="_blank" href="/author/<? echo $row[user_nicename]; ?>">View Author Page</a><br>
        <a target="_blank" href="/members/<? echo $row[user_nicename]; ?>">View Member Page</a><br></td>
    </tr>
    <?	
		}
		?>
  </tbody>
</table>
<? } else { ?>
<?
 $sql = "SELECT * FROM  ".$wpdb->prefix."users WHERE ID > 1 AND ID = $_GET[edit]";
 $result = $wpdb->get_results($sql,ARRAY_A);
		foreach($result as $row){
			$first = get_user_meta($row[ID], 'first_name',true);
			$last = get_user_meta($row[ID], 'last_name',true);
			?>
            <h4>Editing <? echo $first." ".$last; ?></h4>
            
            <? foreach($errors as $value){ ?>
             <div class="error">
          <?
		  _e( $value, 'mythos-error' );
		  ?>
        </div>
        
        <? } ?>
<form method="post" action="/wp-admin/options-general.php?page=mythos-stories%2Fuser-name-changes.php&edit=<? echo $row[ID]; ?>">
<input type="hidden" value="<? echo $row[ID]; ?>" name="ID" />
  <table class="form-table">
    <tbody>
      <tr class="user-user-login-wrap">
        <th><label for="user_login">Username</label></th>
        <td><input type="text" name="user_login" id="user_login" value="<? echo $row[user_login]; ?>"  class="regular-text">
        <span class="description">Changing the username will change the user's login credentials.</span></td>
      </tr>
       <tr class="user-user-login-wrap">
        <th><label for="user_login">Email</label></th>
        <td><input type="text" name="user_email" id="user_email" value="<? echo $row[user_email]; ?>"  class="regular-text"><span class="description">The email address is NOT used for logging in.</span></td>
      </tr>
       <tr class="user-user-login-wrap">
        <th><label for="user_login">Nicename</label></th>
        <td><input type="text" disabled="" id="user_nicename" value="<? echo $row[user_nicename]; ?>"  class="regular-text"><span class="description">The nicename will always be a SEO friendly version of the username.</span></td>
      </tr>
     <tr class="user-user-login-wrap">
        <th><label for="display_name">Display Name</label></th>
        <td><input type="text" name="display_name" id="user_login" value="<? echo $row[display_name]; ?>"  class="regular-text"></td>
      </tr>
    </tbody>
  </table>
  <p>
 <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"> <input type="button" name="back" id="back" class="button button-primary" onclick="window.location='/wp-admin/options-general.php?page=mythos-stories%2Fuser-name-changes.php'" value="Back">
 </p>
</form>
<? } ?>
<? } ?>
<script>
jQuery(document).ready(function(){
	
});
 
</script> 
