<?php

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */

$relationships = ["Single","In a relationship","Engaged","Married","In a civil union","In a domestic partnership","In an open relationship","It's complicated","Separated","Divorced","Widowed"];

$genders = ["","male","female","custom"];

do_action( 'bp_before_profile_loop_content' ); ?>

<?php if ( bp_has_profile() ) : ?>

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

				

				<table class="profile-fields">

					<?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

						<?php if ( bp_field_has_data() ) : ?>

							<tr<?php bp_field_css_class(); ?>>

								<td class="label"><?php bp_the_profile_field_name(); ?></td>

								<td class="data"><?php bp_the_profile_field_value(); ?></td>

							</tr>
                             <? if(!empty(get_user_meta( $user_id, 'occupation', true ))){ ?>
                            <tr class="field_1 field_display-name required-field visibility-public field_type_textbox">
                            <td style="vertical-align:top;" class="label">Occupation</td>
                            <td class="data"><p> <? echo  get_user_meta( $user_id, 'occupation', true ); ?></p>
                            </td></tr>
                             <? } ?>
                             
                             <? if(!empty(get_user_meta( $user_id, 'gender', true ))){ ?>
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

/** This action is documented in bp-templates/bp-legacy/buddypress/members/single/profile/profile-wp.php */
do_action( 'bp_after_profile_loop_content' ); ?>
