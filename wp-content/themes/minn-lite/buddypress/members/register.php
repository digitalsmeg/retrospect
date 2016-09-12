<script>
jQuery(document).ready(function(){
	
});

</script>
<style>
.field-visibility-settings-notoggle, .field-visibility-settings-toggle {
	display: none;
}
input[type="checkbox"], input[type="radio"] {
	box-sizing: border-box;
	padding: 0px;
	margin-right: 5px;
}
p.description {
	color: #888;
	font-size: 80%;
	margin: 5px 0px;
}
.two-col-r .content, .two-col-l .content {
	max-width: none;
	width: 100%;
	padding-top: 25px;
}
#signup_email, .password-entry {
	width: 90% !important;
}
#buddypress #signup_form.standard-form div.submit {
	float: left;
	width: 100%;
}
</style>
<div id="buddypress">
  <?php

	/**
	 * Fires at the top of the BuddyPress member registration page template.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_before_register_page' ); ?>
  <div class="page" id="register-page">
    <form action="" name="signup_form" id="signup_form" class="standard-form" method="post" enctype="multipart/form-data">
      <?php if ( 'registration-disabled' == bp_get_current_signup_step() ) : ?>
      <?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
			do_action( 'template_notices' ); ?>
      <?php

			/**
			 * Fires before the display of the registration disabled message.
			 *
			 * @since BuddyPress (1.5.0)
			 */
			do_action( 'bp_before_registration_disabled' ); ?>
      <p>
        <?php _e( 'User registration is currently not allowed.', 'buddypress' ); ?>
      </p>
      <?php

			/**
			 * Fires after the display of the registration disabled message.
			 *
			 * @since BuddyPress (1.5.0)
			 */
			do_action( 'bp_after_registration_disabled' ); ?>
      <?php endif; // registration-disabled signup step ?>
      <?php if ( 'request-details' == bp_get_current_signup_step() ) : ?>
      <?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
			do_action( 'template_notices' ); ?>
      <p>
        <?php _e( 'Registering for <a href="/">Retrospect</a> is easy. Just fill in the fields below, and we\'ll set up a new account for you in no time.', 'buddypress' ); ?>
      </p>
      <?php

			/**
			 * Fires before the display of member registration account details fields.
			 *
			 * @since BuddyPress (1.1.0)
			 */
			do_action( 'bp_before_account_details_fields' ); ?>
      <div class="register-section" id="basic-details-section">
        <?php /***** Basic Account Details ******/ ?>
        <h4>
          <?php _e( 'Account Details', 'buddypress' ); ?>
        </h4>
        <label for="signup_username">
          <?php _e( 'Username', 'buddypress' ); ?>
          <?php _e( '*', 'buddypress' ); ?>
        </label>
        <?php

				/**
				 * Fires and displays any member registration username errors.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_signup_username_errors' ); ?>
        <input type="text" name="signup_username" id="signup_username" value="<?php bp_signup_username_value(); ?>" <?php bp_form_field_attributes( 'username' ); ?>/>
        <p class="description">Your identifier on the site.</p>
        <label for="field_1"> Display Name * </label>
        <? do_action( 'bp_field_1_errors' ); ?>
        <input id="field_1" name="field_1" type="text" value="<? echo $_POST[field_1]; ?>" aria-required="true">
        <p class="description"> Your author byline for stories you write </p>
        <label for="signup_email">
          <?php _e( 'Email Address', 'buddypress' ); ?>
          <?php _e( '*', 'buddypress' ); ?>
        </label>
        <?php

				/**
				 * Fires and displays any member registration email errors.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_signup_email_errors' ); ?>
        <input type="email" name="signup_email" id="signup_email" value="<?php bp_signup_email_value(); ?>" <?php bp_form_field_attributes( 'email' ); ?>/>
        <label for="signup_password">
          <?php _e( 'Choose a Password', 'buddypress' ); ?>
          
          <?php _e( '*', 'buddypress' ); ?>
        </label>
        
        <?php

				/**
				 * Fires and displays any member registration password errors.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_signup_password_errors' ); ?>
        <input type="password" name="signup_password" id="signup_password" value="" class="password-entry" <?php bp_form_field_attributes( 'password' ); ?>/>
        <div id="pass-strength-result"></div>
        <p class="description indicator-hint">Hint:  To strengthen your password, make it longer and use a mix of upper and lowercase letters, numbers, and symbols like ! “ ? $ ^ & ).</p>
        <label for="signup_password_confirm">
          <?php _e( 'Confirm Password', 'buddypress' ); ?>
          <?php _e( '*', 'buddypress' ); ?>
        </label>
        <?php

				/**
				 * Fires and displays any member registration password confirmation errors.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_signup_password_confirm_errors' ); ?>
        <input type="password" name="signup_password_confirm" id="signup_password_confirm" value="" class="password-entry-confirm password-entry" <?php bp_form_field_attributes( 'password' ); ?>/>
        <div class="editfield field_2 field_terms-of-use required-field visibility-public alt field_type_checkbox">
          <div class="checkbox">
            <label for="field_2"> Terms of Use * </label>
            <p > You retain copyright and ownership of your content, and grant us rights to it as explained in the Retrospect Terms of Use. </p>
            <label>
              <input type="checkbox" name="retro_terms" id="retro_terms" value="I accept Retrospect’s Terms of Use" required>
              I accept Retrospect’s <a href="/terms-of-service/">Terms of Use</a></label>
              </div>
               <div class="checkbox">
               <label for="field_2"> Privacy Policy * </label>
            <p > The Privacy Policy describes how Retrospect Media Inc. and its affiliates treat information collected or provided in connection with your use of our website.</p>
              <label>
              <input type="checkbox" name="retro_privacy" id="retro_privacy" value="I accept Retrospect’s Terms of Use" required>
              I accept Retrospect’s <a href="/privacy-policy/">Privacy Policy</a></label>
          </div>
          <!--
          <div class="checkbox">
          <strong><label>Beta Software</label></strong><br>
          The Retrospect website is currently in beta, pre-release form. Retrospect membership is free during the beta test period.
          </div>
         
           <div class="checkbox">
               <label for="field_3"> Beta Service Confidentiality Agreement *</label>
           <p>The Beta Agreement states that you will not disclose proprietary or confidential information about Retrospect that you learn by being a Beta Tester.</p>
          
   
    <label>
      <input name="retro_beta" id="retro_beta" value="I accept Retrospect’s Beta Agreement" required="" type="checkbox">
      I accept Retrospect’s <a href="/beta-agreement/" target="_blank">Beta Agreement</a></label>
      </div>
        
          -->
        </div>
        <div class="editfield field_4 field_newsletter-sign-up optional-field visibility-adminsonly field_type_checkbox">
          <div class="checkbox">
            <label for="field_4"> Newsletter Sign-up </label>
            <label>
              <input checked="checked"  type="checkbox" name="retro_opt" id="retro_opt" value="Send me Retrospect’s weekly newsletter with new prompts, polls, tips, and more">
              Send me Retrospect’s weekly newsletter with new prompts, polls, tips, and more</label>
          </div>
          <p class="description"> </p>
        </div>
        <input type="hidden" name="signup_profile_field_ids" id="signup_profile_field_ids" value="1,2,4">
        <?php

				/**
				 * Fires and displays any extra member registration details fields.
				 *
				 * @since BuddyPress (1.9.0)
				 */
				do_action( 'bp_account_details_fields' ); ?>
      </div>
      <!-- #basic-details-section -->
      
      <?php

			/**
			 * Fires after the display of member registration account details fields.
			 *
			 * @since BuddyPress (1.1.0)
			 */
			do_action( 'bp_after_account_details_fields' ); ?>
      <?php /***** Extra Profile Details ******/ ?>
      <?php if ( bp_get_blog_signup_allowed() ) : ?>
      <?php

				/**
				 * Fires before the display of member registration blog details fields.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_before_blog_details_fields' ); ?>
      <?php /***** Blog Creation Details ******/ ?>
      <div class="register-section" id="blog-details-section">
        <h4>
          <?php _e( 'Blog Details', 'buddypress' ); ?>
        </h4>
        <p>
          <input type="checkbox" name="signup_with_blog" id="signup_with_blog" value="1"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?> checked="checked"<?php endif; ?> />
          <?php _e( 'Yes, I\'d like to create a new site', 'buddypress' ); ?>
        </p>
        <div id="blog-details"<?php if ( (int) bp_get_signup_with_blog_value() ) : ?>class="show"<?php endif; ?>>
          <label for="signup_blog_url">
            <?php _e( 'Blog URL', 'buddypress' ); ?>
            <?php _e( '*', 'buddypress' ); ?>
          </label>
          <?php

						/**
						 * Fires and displays any member registration blog URL errors.
						 *
						 * @since BuddyPress (1.1.0)
						 */
						do_action( 'bp_signup_blog_url_errors' ); ?>
          <?php if ( is_subdomain_install() ) : ?>
          http://
          <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" />
          .
          <?php bp_signup_subdomain_base(); ?>
          <?php else : ?>
          <?php echo home_url( '/' ); ?>
          <input type="text" name="signup_blog_url" id="signup_blog_url" value="<?php bp_signup_blog_url_value(); ?>" />
          <?php endif; ?>
          <label for="signup_blog_title">
            <?php _e( 'Site Title', 'buddypress' ); ?>
            <?php _e( '*', 'buddypress' ); ?>
          </label>
          <?php

						/**
						 * Fires and displays any member registration blog title errors.
						 *
						 * @since BuddyPress (1.1.0)
						 */
						do_action( 'bp_signup_blog_title_errors' ); ?>
          <input type="text" name="signup_blog_title" id="signup_blog_title" value="<?php bp_signup_blog_title_value(); ?>" />
          <span class="label">
          <?php _e( 'I would like my site to appear in search engines, and in public listings around this network.', 'buddypress' ); ?>
          </span>
          <?php

						/**
						 * Fires and displays any member registration blog privacy errors.
						 *
						 * @since BuddyPress (1.1.0)
						 */
						do_action( 'bp_signup_blog_privacy_errors' ); ?>
          <label>
            <input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_public" value="public"<?php if ( 'public' == bp_get_signup_blog_privacy_value() || !bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> />
            <?php _e( 'Yes', 'buddypress' ); ?>
          </label>
          <label>
            <input type="radio" name="signup_blog_privacy" id="signup_blog_privacy_private" value="private"<?php if ( 'private' == bp_get_signup_blog_privacy_value() ) : ?> checked="checked"<?php endif; ?> />
            <?php _e( 'No', 'buddypress' ); ?>
          </label>
          <?php

						/**
						 * Fires and displays any extra member registration blog details fields.
						 *
						 * @since BuddyPress (1.9.0)
						 */
						do_action( 'bp_blog_details_fields' ); ?>
        </div>
      </div>
      <!-- #blog-details-section -->
      
      <?php

				/**
				 * Fires after the display of member registration blog details fields.
				 *
				 * @since BuddyPress (1.1.0)
				 */
				do_action( 'bp_after_blog_details_fields' ); ?>
      <?php endif; ?>
      <?php

			/**
			 * Fires before the display of the registration submit buttons.
			 *
			 * @since BuddyPress (1.1.0)
			 */
			do_action( 'bp_before_registration_submit_buttons' ); ?>
      <div class="submit">
        <input type="submit" name="signup_submit" style="background: #FF0F54;
  border-radius: 5px; float:left;
  color: white !important;
  font-size: 16px;
  display: inline-block;
  padding: 10px;" id="signup_submit" value="<?php esc_attr_e( 'Complete Sign-Up', 'buddypress' ); ?>" />
  <div style="clear:both;"></div>
  <p></p>
  <div id="paneLogin" class="docBox  ">
        <div class="doc-content isFacebook">
          <div class="orbox">
            <div class="orword centered"> or </div>
          </div>
          <div class="clear"></div>
         <? facebook('reg','register'); ?>
        
         
          <p  class="description">* You will be asked to accept Terms of Use after registering with either Facebook or Google.</p>
           <p  class="description">** Registering with your Facebook or Google account will use the email address associated with those accounts.</p>
          </div>
          
        </div>
      </div>
      <?php

			/**
			 * Fires after the display of the registration submit buttons.
			 *
			 * @since BuddyPress (1.1.0)
			 */
			do_action( 'bp_after_registration_submit_buttons' ); ?>
      <?php wp_nonce_field( 'bp_new_signup' ); ?>
      <?php endif; // request-details signup step ?>
      <?php if ( 'completed-confirmation' == bp_get_current_signup_step() ) : ?>
      <?php

			/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
			do_action( 'template_notices' ); ?>
      <?php

			/**
			 * Fires before the display of the registration confirmed messages.
			 *
			 * @since BuddyPress (1.5.0)
			 */
			do_action( 'bp_before_registration_confirmed' ); ?>
      <?php if ( bp_registration_needs_activation() ) : ?>
      <p>
        <?php _e( 'You have successfully created your account! To begin using Retrospect, activate your account by clicking the link in the email we just sent.', 'buddypress' ); ?>
      </p>
      <p>
        <?php _e( 'If you don’t receive the email within a few minutes, check your spam folder and add us to your address book or safe senders list.', 'buddypress' ); ?>
      </p>
      <?php else : ?>
      <p>
        <?php _e( 'You have successfully created your account! Please log in using the username and password you have just created.', 'buddypress' ); ?>
      </p>
      <?php endif; ?>
      <?php

			/**
			 * Fires after the display of the registration confirmed messages.
			 *
			 * @since BuddyPress (1.5.0)
			 */
			do_action( 'bp_after_registration_confirmed' ); ?>
      <?php endif; // completed-confirmation signup step ?>
      <?php

		/**
		 * Fires and displays any custom signup steps.
		 *
		 * @since BuddyPress (1.1.0)
		 */
		do_action( 'bp_custom_signup_steps' ); ?>
    </form>
  </div>
  <?php

	/**
	 * Fires at the bottom of the BuddyPress member registration page template.
	 *
	 * @since BuddyPress (1.1.0)
	 */
	do_action( 'bp_after_register_page' ); ?>
</div>
<!-- #buddypress --> 
