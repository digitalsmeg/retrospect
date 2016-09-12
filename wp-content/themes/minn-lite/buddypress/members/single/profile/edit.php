<?php

/**
 * Fires after the display of member profile edit content.
 *
 * @since BuddyPress (1.1.0)
 */
 

$relationships = ["Single","In a relationship","Engaged","Married","In a civil union","In a domestic partnership","In an open relationship","It's complicated","Separated","Divorced","Widowed"];

$genders = ["male","female","custom"];
 
do_action( 'bp_before_profile_edit_content' );

if ( bp_has_profile( 'profile_group_id=' . bp_get_current_profile_group_id() ) ) :
	while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

<form action="<?php bp_the_profile_group_edit_form_action(); ?>" autocomplete="off" method="post" id="profile-edit-form" class="standard-form <?php bp_the_profile_group_slug(); ?>">

	<?php

		/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
		do_action( 'bp_before_profile_field_content' ); ?>

		

		<?php if ( bp_profile_has_multiple_groups() ) : ?>
			<ul class="button-nav">

				<?php bp_profile_group_tabs(); ?>

			</ul>
		<?php endif ;?>

		<div class="clear"></div>

		<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

			<div<?php bp_field_css_class( 'editfield' ); ?>>

				<?php
				$field_type = bp_xprofile_create_field_type( bp_get_the_profile_field_type() );
				$field_type->edit_field_html();

				
				?>

				
				
				<?php

				/**
				 * Fires after the visibility options for a field.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_custom_profile_edit_fields' ); ?>

				<p class="description"><?php bp_the_profile_field_description(); ?></p>
			</div>

		<?php endwhile; ?>
        <?
		global $wpdb;
		
	
		
	  global $bp;
			
			$user_id = $bp->displayed_user->id;
			
			$user = get_userdata($user_id);
		
		if($_POST[retro_opt] != ""){
			
			update_user_meta( $user->ID, 'retro_opt', $_POST[retro_opt] );
			$admins = getAdmins();
			
			
			if($_POST[retro_opt] == 0){
				
				$args = array(
					'sender_id' => $user_id,
					'thread_id' => false,
					'recipients' => $admins,
					'subject' => 'Unsubscribe',
					'content' => 'I no longer wish to receive the newsletter. I am opting out via profile panel.',
					'date_sent' => bp_core_current_time()
			 	);
		 		
			} else {
				$args = array(
					'sender_id' => $user_id,
					'thread_id' => false,
					'recipients' => $admins,
					'subject' => 'subscribe',
					'content' => 'I wish to receive the newsletter. I am opting in via profile panel.',
					'date_sent' => bp_core_current_time()
			 	);	
				
			}
			$result = messages_new_message( $args );
			
			$user = wp_get_current_user();
		}
		wp_enqueue_script( 'password-strength-meter' );
		?>
        <link rel='stylesheet' href='/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load%5B%5D=forms,admin-menu,dashboard,list-tables,edit,revisions,media,themes,about,nav-menus,widgets,site-icon,&amp;load%5B%5D=l10n,buttons,wp-auth-check,editor-buttons&amp;ver=4.5.3' type='text/css' media='all' />
        <style>
		.error{
			background:red;
			margin: 2px 0px;	
			padding:2px;
			color:white;
			display:none;
		}
		</style>
        
        <input type="hidden" name="hearth" value="<? echo base64_encode($user_id); ?>" /> 
     
         <p>
        <? if(preg_match("/facebook\.com/",$user->user_url) || preg_match("/google\.com/",$user->user_url)){ ?>
        <em>Note: Your account was registered via social media. </em>
        <? } ?>
        </p>
          <p>
        <label for="fname">First Name</label>
        <input name="fname" id="fname" type="text" value="<? echo  get_user_meta( $user_id, 'first_name', true ); ?>" required />
        </p>
        
          <p>
        <label for="lname">Last Name</label>
        <input name="lname" id="lname" type="text" value="<? echo  get_user_meta( $user_id, 'last_name', true ); ?>"   />
        </p>
         <p>
        <label for="occupation">Occupation</label>
        <input name="occupation" id="occupation" type="text" value="<? echo  get_user_meta( $user_id, 'occupation', true ); ?>"  />
        </p>
         <p>
        <label for="retired"><input name="retired" id="retired" type="checkbox" value="1" <? echo  (get_user_meta( $user_id, 'retired', true ))?'checked=""':''; ?>  /> Retired?</label>
      
        </p>
        <p>
        <label for="uemail">Email</label>
    
        <input name="uemail" id="uemail" type="text" value="<? echo $user->user_email; ?>" required  />
        <div class="error emailused email">Please select another email address.</div>
        <div class="error emailvalid email">Please enter a valid email address.</div>
        </p>
      
        <? if(1 || !preg_match("/facebook\.com/",$user->user_url) && !preg_match("/google\.com/",$user->user_url)){ ?>
          <p>
        <label for="user_email">Change Password</label>
        <em>Note: Only if you want to change your password. Otherwise leave blank.</em><br>
        <input name="upass" id="user_password" type="password" value="" placeholder="new password"   />  <input name="ucpass"  id="confirm_password" type="password" value="" placeholder="confirm password"   />
        <div class="error pwmatch passwords" >Passwords must match.</div>
         <div class="error pwconfirm passwords" >Please confirm password .</div>
         <span id="pass-strength-result"></span><br>
       
        </p>
        <? } ?>
          <p>
        <label for="description">Bio</label>
        <textarea name="description" id="description"  ><? echo$user->description; ?></textarea>
        </p>
        <p>
         <label for="gender">Gender</label>
 <? $v = get_user_meta( $user_id, 'gender', true ) ; 

 ?>
    <select onchange="jQuery('#gender').val('');" name="gender[0]" >
    <option value="">Please choose...</option>
    <? foreach($genders as $key=>$value){ 
	$key = $key + 1;
	if($v != 1 && $v != 2) {
		$custom = true;
	}
	?>
	<option <? if(($v == $key && $v > 0) || ($custom == true && $key == 3)) { ?> selected="" <? } ?> value="<? echo $key; ?>"><? echo $value; ?></option>
    <? } ?>    
	</select>
    
   
    <input name="gender[1]" id="gender" type="text" value="<? if($custom) { ?><? echo $v; ?><? } ?>" placeholder="custom gender" />
   
    
    <label for="relationship_status">Relationship Status</label>
    <? $v = get_user_meta( $user_id, 'relationship_status', true ) ; ?>
    <select name="relationship_status" >
    <option value="">Please choose...</option>
    <? foreach($relationships as $key=>$value){ 
	$key = $key + 1;
	?>
	?>
    <option <? if($v == $key && $v > 0) { ?> selected="" <? } ?> value="<? echo $key; ?>"><? echo $value; ?></option>
    <? } ?>
    </select>
    </p>
        
<p>
                <label for="field_1">Newsletter</label>
                <? if(get_user_meta($user->ID, "retro_opt", true) == 1){ ?>
                	<span id="opt">You have opted to receive the newsletter</span>: <input id="optb"  type="button" onclick="setOptins();" value="Opt-Out" />    
                <? } else { ?>
                	<span id="opt">You are not receiving our newsletter</span>: <input id="optb" type="button" onclick="setOptins();" value="Opt-In" />     <? } ?>
                </p>
	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_after_profile_field_content' ); ?>

	<div class="submit">
		<input type="submit" name="profile-group-edit-submit" id="profile-group-edit-submit" value="<?php esc_attr_e( 'Save Changes', 'buddypress' ); ?> " />
	</div>

	<input type="hidden" name="field_ids" id="field_ids" value="<?php bp_the_profile_field_ids(); ?>" />
<script>

function setOptins(){
	 jQuery.post(ajaxurl, {action: 'set_optins'}, function(response) {
		 if(response.toString() == "1"){
				 jQuery("#opt").html('You have opted to receive the newsletter');
				 jQuery("#optb").val('Opt-Out');
		 } else {
			  jQuery("#opt").html('You are not receiving our newsletter');
				 jQuery("#optb").val('Opt-In');
		 }
	  });	
}

jQuery(document).ready(function(){
	jQuery("#upass").val('');
	jQuery("#profile-edit-form").on("submit",function(){
		var pw1 = jQuery("#user_password").val();
		var pw2 = jQuery("#confirm_password").val();	
		jQuery(".error.passwords").hide();
		if(pw1 && !pw2){
			jQuery(".pwmatch").show();
			return false;
		}
		if(pw1 && pw2 && pw1 != pw2){
			jQuery(".pwmatch").show();
			return false;
		}
	});
	jQuery("#user_password").on("keypress blur change",function(){
		jQuery(".error.passwords").hide();
	});
	jQuery("#confirm_password").on("keypress blur change",function(){
		jQuery(".error.passwords").hide();
	});
	jQuery("#user_email").on("keypress blur change",function(){
		jQuery(".error.email").hide();
		jQuery("#profile-group-edit-submit").attr("disabled",true);
		 jQuery.post(ajaxurl, {action: 'retrospect_check_email', email: jQuery("#user_email").val() }, function(response) {
			 if(response > 0){
				 jQuery(".emailused").show();
			 } else if(!ValidateEmail(jQuery("#user_email").val())){
				  jQuery(".emailvalid").show(); 
			 } else {
				jQuery("#profile-group-edit-submit").attr("disabled",false); 
			 }
           
          });	
		
	});
	function ValidateEmail(inputText) {  
		var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
		if(inputText.match(mailformat)) {  
			return true;  
		} else {  
			return false;  
		}  
	}  
});

function checkPasswordStrength( $pass1,
                                $pass2,
                                $strengthResult,
                                $submitButton,
                                blacklistArray ) {
        var pass1 = $pass1.val();
    var pass2 = $pass2.val();
 
    // Reset the form & meter
    $submitButton.attr( 'disabled', 'disabled' );
        $strengthResult.removeClass( 'short bad good strong' );
 
    // Extend our blacklist array with those from the inputs & site data
    blacklistArray = blacklistArray.concat( wp.passwordStrength.userInputBlacklist() )
 
    // Get the password strength
    var strength = wp.passwordStrength.meter( pass1, blacklistArray, pass2 );
 
    // Add the strength meter results
    switch ( strength ) {
 
        case 2:
            $strengthResult.addClass( 'bad' ).html( pwsL10n.bad );
            break;
 
        case 3:
            $strengthResult.addClass( 'good' ).html( pwsL10n.good );
            break;
 
        case 4:
            $strengthResult.addClass( 'strong' ).html( pwsL10n.strong );
            break;
 
        case 5:
            $strengthResult.addClass( 'short' ).html( pwsL10n.mismatch );
            break;
 
        default:
            $strengthResult.addClass( 'short' ).html( pwsL10n.short );
 
    }
 
    // The meter function returns a result even if pass2 is empty,
    // enable only the submit button if the password is strong and
    // both passwords are filled up
    if ((4 === strength ||  3 === strength ) && '' !== pass2.trim() ) {
        $submitButton.removeAttr( 'disabled' );
    }
	if('' == pass2.trim() && '' == pass1.trim()){
		 $submitButton.removeAttr( 'disabled' );
		  $strengthResult.html('').removeAttr('class');
	}
 
    return strength;
}
 
jQuery( document ).ready( function( $ ) {
    // Binding to trigger checkPasswordStrength
    $( 'body' ).on( 'keyup', 'input[name=upass], input[name=ucpass]',
        function( event ) {
            checkPasswordStrength(
                $('input[name=upass]'),         // First password field
                $('input[name=ucpass]'), // Second password field
                $('#pass-strength-result'),           // Strength meter
                $('#profile-group-edit-submit'),           // Submit button
                ['black', 'listed', 'word']        // Blacklisted words
            );
        }
    );
});

</script>
	<?php wp_nonce_field( 'bp_xprofile_edit' ); ?>

</form>

<?php endwhile; endif; ?>

<?php

/**
 * Fires after the display of member profile edit content.
 *
 * @since BuddyPress (1.1.0)
 */
do_action( 'bp_after_profile_edit_content' ); ?>
