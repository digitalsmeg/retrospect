<div id="buddypress">

	<?php
global $bp;
	/**
	 * Fires before the display of member home content.
	 *
	 * @since BuddyPress (1.2.0)
	 */
	do_action( 'bp_before_member_home_content' ); ?>

	<div id="item-header" role="complementary">

		<?php bp_get_template_part( 'members/single/member-header' ) ?>

	</div><!-- #item-header -->
<?php if ( bp_displayed_user_is_friend() ) : ?>
<!-- friends disabled for now -->
<div class="not_friend_message" style="display:none;">

You must be friends in order to access users' profiles.

</div>
<? if(! bp_loggedin_user_id()){
?>
<div class="not_friend_message">

You must be logged in to view social actions such as 'Friend' and 'Follow'.

</div>
<?
}
?>
<?php else : ?>

<? if(bp_loggedin_user_id() == $bp->displayed_user->id){ ?>
	<div id="item-nav">
		
			

				<?php bp_get_displayed_user_nav(); ?>
<?php

				/**
				 * Fires after the display of member options navigation.
				 *
				 * @since BuddyPress (1.2.4)
				 */
				 
				do_action( 'bp_member_options_nav' ); 
				?>

			
		
	</div>
    
    
    
  

	<div id="item-body">

		<?php

		/**
		 * Fires before the display of member body content.
		 *
		 * @since BuddyPress (1.2.0)
		 */
		do_action( 'bp_before_member_body' );

		if ( bp_is_user_activity() || !bp_current_component() ) :
			bp_get_template_part( 'members/single/activity' );

		elseif ( bp_is_user_blogs() ) :
			bp_get_template_part( 'members/single/blogs'    );

		elseif ( bp_is_user_friends() ) :
			bp_get_template_part( 'members/single/friends'  );

		elseif ( bp_is_user_groups() ) :
			bp_get_template_part( 'members/single/groups'   );

		elseif ( bp_is_user_messages() ) :
			bp_get_template_part( 'members/single/messages' );

		elseif ( bp_is_user_profile() ) :
			bp_get_template_part( 'members/single/profile'  );

		elseif ( bp_is_user_forums() ) :
			bp_get_template_part( 'members/single/forums'   );

		elseif ( bp_is_user_notifications() ) :
			bp_get_template_part( 'members/single/notifications' );

		elseif ( bp_is_user_settings() ) :
			bp_get_template_part( 'members/single/settings' );

		// If nothing sticks, load a generic template
		else :
			bp_get_template_part( 'members/single/plugins'  );

		endif;

		/**
		 * Fires after the display of member body content.
		 *
		 * @since BuddyPress (1.2.0)
		 */
		do_action( 'bp_after_member_body' ); ?>

	</div><!-- #item-body -->
   
    <? } ?>
   

<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_after_profile_loop_content' ); ?>
<? endif ?>

 <?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */

$relationships = ["Single","In a relationship","Engaged","Married","In a civil union","In a domestic partnership","In an open relationship","It's complicated","Separated","Divorced","Widowed"];

$genders = ["","male","female","custom"];

do_action( 'bp_before_profile_loop_content' ); ?>

<?php if ( bp_has_profile() && $bp->loggedin_user->id != $bp->displayed_user->id ) : ?>

	<?php while ( bp_profile_groups() ) : bp_the_profile_group(); ?>

		<?php if ( bp_profile_group_has_fields() ) : ?>
			<?
            global $bp;
			
			$user_id = $bp->displayed_user->id;
			$user = get_userdata($user_id);
            ?>
			<?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
			do_action( 'bp_before_profile_field_content' ); ?>

			<div class="bp-widget <?php bp_the_profile_group_slug(); ?>">

				
<? if(1){ ?>
				<table class="profile-fields">

					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

						<?php if ( bp_field_has_data() ) : ?>
 						
                            <tr class="field_1 field_display-name required-field visibility-public field_type_textbox">
                            <td style="vertical-align:top;" class="label">Display Name</td>
                            <td class="data"><p> <? echo  $user->display_name; ?></p>
                            </td></tr>
                          
                             <? if(!empty(get_user_meta( $user_id, 'occupation', true ))){ ?>
                            <tr class="field_1 field_display-name required-field visibility-public field_type_textbox">
                            <td style="vertical-align:top;" class="label">Occupation</td>
                            <td class="data"><p> <? echo  get_user_meta( $user_id, 'occupation', true ); ?></p>
                            </td></tr>
                             <? } ?>
                             
                             <? if(!empty(get_user_meta( $user_id, 'gender', true )) && get_user_meta( $user_id, 'gender', true ) != "3"){ ?>
                            <tr class="field_1 field_display-name required-field visibility-public field_type_textbox">
                            <td style="vertical-align:top;" class="label">Gender</td>
                            <? $g = get_user_meta( $user_id, 'gender', true ); ?>
                           
                            <? if( $g != 1 && $g != 2){ ?>
                            <td class="data"> <p> <? echo  ucwords(get_user_meta( $user_id, 'gender', true )) ?></p>
                            <? } else { ?>
                            <td class="data"><p><? echo  ucwords($genders[get_user_meta( $user_id, 'gender', true )]); ?></p>
                            <? } ?>
                            </td></tr>
                            <? } ?>
                            
                            <? if(!empty(get_user_meta( $user_id, 'relationship_status', true ))){ ?>
                            <tr class="field_1 field_display-name required-field visibility-public field_type_textbox">
                            <td style="vertical-align:top;" class="label">Relationship Status</td>
                            <td class="data"><p> <? echo  $relationships[get_user_meta( $user_id, 'relationship_status', true ) - 1]; ?></p>
                            </td></tr>
                            <? } ?>
                            
                            <? if(!empty($user->description)){ ?>
                            <tr class="field_1 field_display-name required-field visibility-public field_type_textbox">
                            <td style="vertical-align:top;" class="label">Biographical Info</td>
                            <td class="data"><p> <? echo  nl2br($user->description); ?></p>
                            </td></tr>
<? } ?>
						<?php endif; ?>

						<?php

						/**
						 * Fires after the display of a field table row for profile data.
						 *
						 * @since BuddyPress (1.1.0)
						 */
						do_action( 'bp_profile_field_item' ); ?>

					<?php endwhile; ?>

				</table>
<? } ?>
			</div>

			<?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
			do_action( 'bp_after_profile_field_content' ); ?>

		<?php endif; ?>

	<?php endwhile; ?>

	<?php

	/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
	do_action( 'bp_profile_field_buttons' ); ?>

<?php endif; ?>
	<?php

	/**
	 * Fires after the display of member home content.
	 *
	 * @since BuddyPress (1.2.0)
	 */
	do_action( 'bp_after_member_home_content' ); ?>

</div><!-- #buddypress -->
