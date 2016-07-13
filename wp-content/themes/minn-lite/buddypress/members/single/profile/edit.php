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

<form action="<?php bp_the_profile_group_edit_form_action(); ?>" method="post" id="profile-edit-form" class="standard-form <?php bp_the_profile_group_slug(); ?>">

	<?php

		/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
		do_action( 'bp_before_profile_field_content' ); ?>

		<h4><?php printf( __( "Editing '%s' Profile Group", "buddypress" ), bp_get_the_profile_group_name() ); ?></h4>
Please visit your <a style="color:blue;text-decoration:underline;" href="/wp-admin/profile.php">profile</a> to change your first or last name.
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
		
		$user = wp_get_current_user();
		$user_id = $user->ID;
		
	
		
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
		
		?>
        <p>
         <label for="gender">Gender</label>
 <? $v = get_user_meta( $user_id, 'gender', true ) ; 

 ?>
    <select onchange="jQuery('#gender').val('');" name="gender[0]" required>
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
    <select name="relationship_status" required>
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
                	You have opted to receive the newsletter: <form method="post"><input type="hidden" name="retro_opt" value="0" />  <input type="submit" value="Opt-Out" />    
                <? } else { ?>
                	You are not receiving our newsletter: <form method="post"><input type="hidden" name="retro_opt" value="1" />  <input type="submit" value="Opt-In" />            <? } ?>
                </p>
	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_after_profile_field_content' ); ?>

	<div class="submit">
		<input type="submit" name="profile-group-edit-submit" id="profile-group-edit-submit" value="<?php esc_attr_e( 'Save Changes', 'buddypress' ); ?> " />
	</div>

	<input type="hidden" name="field_ids" id="field_ids" value="<?php bp_the_profile_field_ids(); ?>" />

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