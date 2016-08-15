<? @session_start(); ?>
<h2>Retrospect Settings</h2>
<form method="post" action="options.php">
  <?php settings_fields( 'mythos-settings-group' ); ?>
  <?php do_settings_sections( 'mythos-settings-group' ); ?>
  <table class="form-table">
    <tbody>
      <!--
      <tr valign="top">
        <th scope="row"><label for="myth_admin_email">Admin Email</label></th>
        <td><input type="text" name="myth_admin_email" value="<?php echo esc_attr( get_option('myth_admin_email') ); ?>" /></td>
      </tr>
      -->
      <tr valign="top">
        <th scope="row"><label for="myth_time_travel">Time Travel</label></th>
        <td><input type="text" class="datepicker" name="myth_time_travel" value="<?php echo esc_attr( get_option('myth_time_travel') ); ?>" />
          <div style="color:grey;margin:5px;" id="">To use Time Travel:
            <ol>
              <li>Change the date in the field to the desired date and click "Save Changes" below.</li>
              <li>Click the "Activate" button below and wait for the button text to switch to "Deactivate". You can then visit the front page to see it as if the selected date is today's date.</li>
            </ol>
          </div></td>
      </tr>
      <tr>
    
        <th scope="row"><label for="myth_time_travel">Time Travel Activation</label></th>
        <td id="ttnote"><input type="button" onclick="activatett()" id="att" <? if(!empty($_SESSION[timetravel])){ ?>style="display:none;"<? } ?> value="Activate" />
          <input type="button" onclick="deactivatett()" id="datt" style="background:red;color:white;cursor:pointer;<? if(empty($_SESSION[timetravel])){ ?>display:none;<? } ?>" value="Time Travel is activated for IP <? echo $_SESSION[timetravel]; ?>. Deactivate." />
          <? if(!empty($_SESSION[timetravel])){ ?>
          <? } ?>
          </style>
          
          <!--
       <tr valign="top">
        <th scope="row"><label for="myth_prompts_lock">Lock Stories</label></th>
        <td><input type="checkbox" name="myth_stories_lock" value="on" <?php if(get_option('myth_stories_lock') == "on"){ ?>checked="" <? } ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="myth_prompts_lock">Lock Prompts</</th>
        <td><input type="checkbox" name="myth_prompts_lock" value="on" <?php if(get_option('myth_prompts_lock') == "on"){ ?>checked="" <? } ?>" /></td>
      </tr>
      
       <tr valign="top">
        <th scope="row"><label for="myth_embargo_debug">Debug Embargos</</th>
        <td><input type="checkbox" name="myth_embargo_debug" value="on" <?php if(get_option('myth_embargo_debug') == "on"){ ?>checked="" <? } ?>" /></td>
      </tr>
     
       <tr valign="top">
        <th scope="row"><label for="myth_topic">Suggested Topic:</</th>
      <td><input type="text" name="myth_topic" style="width:100%;" value="<?php echo esc_attr( get_option('myth_topic') ); ?>" /></td>

      </tr>
    
      <tr valign="top" style="display:none;">
        <th scope="row"><label for="myth_editor_message">Message from the Editor:</</th>
      <td><? wp_editor( ( get_option('myth_editor_message') ), "myth_editor_message", array("textarea_name"=>"myth_editor_message")); ?>
      </td>

      </tr>
         -->
        <tr valign="top">
        <th scope="row"><label for="myth_home_text">Home Text:</</th>
      <td><textarea name="myth_home_text" style="margin: 0px; width: 100%; height: 185px;"><? echo get_option('myth_home_text'); ?></textarea>
      </td>

      </tr>
      <tr valign="top">
          <th scope="row">
          <label for="myth_charaterization">
        Characterization Words:</
          </th>
        <td><textarea name="myth_charaterization" style="margin: 0px; width: 100%; height: 185px;"><? echo get_option('myth_charaterization'); ?></textarea>
          <div style="color:grey;">Note: Seperate words with commas</div></td>
      </tr>
      <tr valign="top">
          <th scope="row">
          <label for="myth_prohibited">
        Prohibited Words:</
          </th>
        <td><textarea name="myth_prohibited" style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_prohibited'); ?></textarea>
          <div style="color:grey;">Note: Seperate words with commas</div></td>
      </tr>
      <tr>
        <th><h2>Event Texts</h2></th>
        <td><div style="color:grey;">Text messages sent to target user upon these specific events.</div>
       
        </td>
      </tr>
      <tr valign="top">
        <th scope="row"><label for="myth_deferred_date">Next Summary For Weekly</label></th>
        <td><input type="text" class="datepicker" name="myth_deferred_date" value="<?php echo esc_attr( get_option('myth_deferred_date') ); ?>" /> 
        
       
          </td>
      </tr>
       <tr>
        <th>New Prompt</th>
        <td><input type="text" class="" name="myth_event_8_s" placeholder="Subject" size="60"  value="<?php echo esc_attr( get_option('myth_event_8_s') ); ?>" /><br>
        <textarea name="myth_event_8" placeholder="When a new prompt is available" style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_event_8'); ?></textarea>
         <div style="color:grey;">Note: You can use {$excerpt} and {$title} accordingly.</div>
        </td>
      </tr>
      <tr>
        <th>First Publish</th>
        <td><input type="text" class="" name="myth_event_1_s" placeholder="Subject" size="60"  value="<?php echo esc_attr( get_option('myth_event_1_s') ); ?>" /><br>
        <textarea name="myth_event_1" placeholder="When user publishes their first story" style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_event_1'); ?></textarea>
         <div style="color:grey;">Note: You can use {$author} and {$title} accordingly.</div>
        </td>
      </tr>
      
       <tr>
        <th>Follower Story</th>
        <td><input type="text" class="" name="myth_event_9_s" placeholder="Subject" size="60"  value="<?php echo esc_attr( get_option('myth_event_9_s') ); ?>" /><br>
        <textarea name="myth_event_9" placeholder="When someone you follow publishes a  story" style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_event_9'); ?></textarea>
         <div style="color:grey;">Note: You can use {$author} and {$title} accordingly.</div>
        </td>
      </tr>
      
      <tr>
        <th>Subsequent Publish</th>
        <td><input type="text" class="" name="myth_event_2_s" placeholder="Subject" size="60"  value="<?php echo esc_attr( get_option('myth_event_2_s') ); ?>" /><br><textarea name="myth_event_2" placeholder="When user publishes 5th, 10th, 20th, 30th ..." style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_event_2'); ?></textarea>
        <div style="color:grey;">Note: Use {$count} to specify where the count should go, if used in your message. Example: Congratulations on your {$count} story! The 'th' suffix is added automatically. You can use {$author} and {$title} accordingly </div>
        </td>
      </tr>
      <tr>
        <th>First Like</th>
        <td><input type="text" class="" name="myth_event_3_s" placeholder="Subject" size="60"  value="<?php echo esc_attr( get_option('myth_event_3_s') ); ?>" /><br><textarea name="myth_event_3" placeholder="When user receive their first Like ever" style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_event_3'); ?></textarea>
        <div style="color:grey;">Note: You can use {$author} and {$title} accordingly.</div>
        </td>
      </tr>
       <tr>
        <th>Featured Story</th>
        <td><input type="text" class="" name="myth_event_4_s" placeholder="Subject" size="60"  value="<?php echo esc_attr( get_option('myth_event_4_s') ); ?>" /><br><textarea name="myth_event_4" placeholder="When user story is made featured" style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_event_4'); ?></textarea>
        <div style="color:grey;">Note: You can use {$author} and {$title} accordingly.</div>
        </td>
      </tr>
       <tr>
        <th>Comment Response</th>
        <td><input type="text" class="" name="myth_event_7_s" placeholder="Subject" size="60"  value="<?php echo esc_attr( get_option('myth_event_7_s') ); ?>" /><br><textarea name="myth_event_7" placeholder="When replies to your comment" style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_event_7'); ?></textarea>
        <div style="color:grey;">Note: You can use {$author}, {$title} and {$commenter} accordingly.</div>
        </td>
      </tr>
       <tr>
        <th>Comment</th>
        <td><input type="text" class="" name="myth_event_10_s" placeholder="Subject" size="60"  value="<?php echo esc_attr( get_option('myth_event_10_s') ); ?>" /><br><textarea name="myth_event_10" placeholder="When  comment on story you commented on" style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_event_10'); ?></textarea>
        <div style="color:grey;">Note: You can use {$author}, {$title} and {$commenter} accordingly.</div>
        </td>
      </tr>
      <tr>
        <th>Response Story</th>
        <td><input type="text" class="" name="myth_event_6_s" placeholder="Subject" size="60"  value="<?php echo esc_attr( get_option('myth_event_6_s') ); ?>" /><br><textarea name="myth_event_6" placeholder="When someone writes a response story to your story" style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_event_6'); ?></textarea>
        <div style="color:grey;">Note: You can use {$author} and {$title} accordingly.  You can use {$parent} to refer to the title of the story the response was written to.</div>
        </td>
      </tr>
       <tr style="display:none;">
        <th>Readers' Choice (will Ping Pong - in discussion)</th>
        <td><textarea name="myth_event_5" placeholder="When user story is made Readers' Choice" style="margin: 0px; width: 100%; height: 85px;"><? echo get_option('myth_event_5'); ?></textarea></td>
      </tr>
    </tbody>
  </table>
  <?php submit_button(); ?>
</form>
<script>
jQuery(document).ready(function(){
	jQuery(".datepicker").datepicker({dateFormat:"yy-mm-dd"});
});
 function activatett(){
            if(confirm('Are you sure? You will see the site as if the date specified were today.')){
				jQuery("#ttnote").prepend('<div>Please wait...</div>');
                jQuery.post(ajaxurl, {action: 'activatett'}, function(response) {
					setTimeout(function(){
                	window.location='/wp-admin/options-general.php?page=retrospect-stories%2Fsettings.php';
					},3000);
                });	
            }
        }
		
 function deactivatett(){
	    jQuery("#ttnote").prepend('<div>Please wait...</div>');
           jQuery.post(ajaxurl, {action: 'deactivatett'}, function(response) {
			
                	setTimeout(function(){
                	window.location='/wp-admin/options-general.php?page=retrospect-stories%2Fsettings.php';
					},3000);
                });	
            
        }
</script> 
