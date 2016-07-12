<div id="buddypress">

	<?php

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
	<div id="item-nav">
		
			

				<?php bp_get_displayed_user_nav(); ?>
<?php

				/**
				 * Fires after the display of member options navigation.
				 *
				 * @since BuddyPress (1.2.4)
				 */
				do_action( 'bp_member_options_nav' ); ?>

			
		
	</div><!-- #item-nav -->

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
<? endif ?>
	<?php

	/**
	 * Fires after the display of member home content.
	 *
	 * @since BuddyPress (1.2.0)
	 */
	do_action( 'bp_after_member_home_content' ); ?>

</div><!-- #buddypress -->
