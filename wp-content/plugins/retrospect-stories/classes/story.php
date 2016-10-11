<?

/*
 * 
 * 
 *  
 * Story Related Functions
 * 
 * 
 *
 */

function story_add_meta_box(){
 	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	global $post;
	
	if(current_user_can("writer") && ($post->post_type == "post" || $post->post_type == "prompts") && !current_user_can("administrator")){
		wp_redirect( '/wp-admin', 301 ); exit;
	
		
	}
	
	
	$titles = array("stories"=>"Story","prompts"=>"Prompts","votes"=>"Votes");
	$screens = array( 'stories','prompts','votes');
	foreach ( $screens as $screen ) {
		if($screen == "stories"){
			
			
			add_meta_box(
				'story_roster_section',
				__( "Stories", 'myplugin_stories' ),
				'showStories',
				"stories",
				'advanced',
				'default'
			);	
			
	
		}
		
		if($screen == "prompts"){
			add_meta_box(
				'story_roster_section',
				__( "Prompts", 'myplugin_prompts' ),
				'showPrompts',
				$screen,
				'advanced',
				'default'
			);
			
		}	
		
		if($screen == "votes"){
			add_meta_box(
				'vote_roster_section',
				__( "Survey Options", 'myplugin_votes' ),
				'showVotes',
				$screen,
				'advanced',
				'default'
			);
			
		}		
		
		
		
	}
	
}

add_action( 'add_meta_boxes', 'story_add_meta_box' );

 
 
 


function showPrompts($post){
		global $wpdb;

		$user = wp_get_current_user(); 
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	wp_enqueue_script('stories-scripts2', $pluginfolder . '/scripts.js');
	?>
<table class="form-table">
  <tbody>
    <tr valign="top">
   <th scope="row"><label for="stories_some_text">Optional Text</label></th>
      <td>
 
 <? wp_editor( get_post_meta( $post->ID , 'optional_text' , true ), "optional_text", array("textarea_name"=>"optional_text",'teeny' => true,'media_buttons' => false)); ?>
 
 
</td>
</tr>
    <!--
    <tr valign="top">
      <th scope="row"><label for="stories_some_text">Some Text</label></th>
      <td><input type="text" name="stories_some_text" value="<?php echo esc_attr( get_post_meta( $post->ID , 'stories_some_text' , true )  ); ?>" /></td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="stories_some_setting">Some Setting</label></th>
      <td><input type="checkbox" name="stories_some_setting" value="on" <?php if(get_post_meta( $post->ID , 'stories_some_setting' , true ) == "on"){ ?>checked="" <? } ?>" /></td>
    </tr>
  
    <tr valign="top">
      <th scope="row"><label for="stories_some_setting">Featured?</label></th>
      <td><input type="checkbox" name="stories_featured" value="on" <?php if(get_post_meta( $post->ID , 'stories_featured' , true ) == "on"){ ?>checked="" <? } ?>" /></td>
    </tr>
      -->
    <tr valign="top">
      <th scope="row"><label for="stories_embargo_until">Go Live</label></th>
      <td><input type="text" name="stories_embargo_until" class="datepicker dp1" value="<?php echo esc_attr( get_post_meta( $post->ID , 'stories_embargo_until' , true )  ); ?>" required/>
        <input type="button" onclick="jQuery('.dp1').val('');" value="Clear" />
        <div style="color:grey;font-size:12px">
          <ul style="list-style:disc;">
            <li>The <strong style="color:blue;">Current Prompt</strong> will be determined by the Go Live date. When this date arrives, this prompt becomes current until the next Go Live date.</li>
            <li><strong style="color:blue;">Upcoming Prompts</strong> are determined by those prompts whose date is greater than the <strong style="color:blue;">Current Prompt's</strong> 'Go Live' date.</li>
            <li><strong style="color:blue;">Past Prompts</strong> are determined by all prompts older than the <strong style="color:blue;">Current Prompt's</strong> date.</li>
          </ul>
        </div></td>
    </tr>
    <!--
    <tr valign="top">
      <th scope="row"><label for="stories_hide_until">Hide Until</label></th>
      <td><input type="text" name="stories_hide_until" class="datepicker dp2" value="<?php echo esc_attr( get_post_meta( $post->ID , 'stories_hide_until' , true )  ); ?>" />
        <input type="button" onclick="jQuery('.dp2').val('');" value="Clear" /></td>
    </tr>
    -->
  </tbody>
</table>
<?
}


function showStories($post){
	global $wpdb;

		$user = wp_get_current_user(); 
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	wp_enqueue_script('stories-scripts2', $pluginfolder . '/scripts.js');
	// story based on writing prompt
	if(!empty($_GET[prompt])){
		$p = $_GET[prompt];	
		$sql = "SELECT * FROM  ".$wpdb->prefix."posts WHERE ID = $p";
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		$assoc = $result[0];
		$total = sizeof($result);
		if($total == 0){
			$p = "";
		}
		
	}
	
	
	
	// story based on another story
	if(!empty($_GET[response])){
		if($_GET[response] == "remove"){
			delete_post_meta( $post->ID, 'stories_response');
		} else {
			?><input type="hidden" name="stories_response" value="<? echo $_GET[response]; ?>" /><?
			
		}
	}
	
	$response = get_post_meta( $post->ID, 'stories_response', true);
	if($response > 0){
		$response = get_post($response);
		
	} else {
		if(!empty($_GET[response])){
			$response = get_post($_GET[response]);
		}
	}
	
	
	?>
<script>
    jQuery(document).ready(function(){
    	jQuery("#post").on("submit",function(){
        	if(jQuery(".attachment-post-thumbnail").length === 0){
            	if(confirm("You didn't assign a featured image. Continue?")){
                	return true;
                } else {
                	return false;
                }
                    
            }
        });
    });
    </script>
<table class="form-table">
  <tbody>
  
    <? if(current_user_can('administrator')){ 
	
	
	?>
    
    <tr valign="top">
      <th scope="row" style="vertical-align:top !important;"><strong>Characterizations</strong></th>
      <td><table width="100%">
          <tbody>
            <?
  
  $ch = explode(",",get_option('myth_charaterization'));
	for($a = 0;$a < 1; $a ++){
		// create fake results
	  $sql = "INSERT INTO ".$wpdb->prefix."postmeta VALUES( '',".$post->ID.",'stories_characterized_X','".$ch[floor(rand(0,sizeof($ch)-1))]."');";
	 // echo $sql."<br>";		
	}
  
  $post = get_post($post_id);
  $sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE post_id = ".$post->ID." AND meta_key LIKE 'stories_characterized_%'";
 
  $result = $wpdb->get_results($sql,ARRAY_A);
  $ch = array();
  $total = 0;
 foreach($result as $c){
	  $temp = explode(",",$c[meta_value]);
	   foreach($temp as $value){
	  	$ch[$value] ++;
	  	$total ++;
	  }
  }
  asort($ch);
 foreach($ch as $key=>$value){
		?>
            <tr valign="top">
              <td><div style="width: <? echo ($value / $total) * 100; ?>%;background:hsla(201,94%,61%,1.00);color:white;"><? echo $key; ?> (<? echo $value; ?>)</div></td>
            </tr>
            <? 
 }
 
 
 ?>
          </tbody>
        </table></td>
    </tr>
    <? } ?>
    <tr valign="top">
      <th scope="row"><label for="changepromptto">Assigned Prompt</label></th>
      <td><?
	
	
	 $sql = "SELECT * FROM  ".$wpdb->prefix."usermeta WHERE meta_key LIKE 'stories_prompted_%' AND meta_value = ".$post->ID;
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		foreach($result as $prompt){
			$temp = explode("_",$prompt[meta_key]);
			$p = $temp[2];
		}
	
	
	
	if(!empty($p)){
		$prompt = $p;
	}
	
	//$prompt = $prompt * 1;
	
	
	
	 $sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_featured_story' AND  meta_value = ".$post->ID;
		$feat = false;
		$result = $wpdb->get_results($sql,ARRAY_A);
		foreach($result as $f){
			
			if($prompt == $f[post_id]){
				$feat = true;
			}
		}
	

	 $loop = new WP_Query( array( 'post__not_in'=> array(5520,5759),'posts_per_page' => -1, 'post_type' => 'prompts', 'orderby'=>'date','order' => 'ASC','meta_key' => 'stories_embargo_until' , 'orderby'=>'meta_value','order' => 'ASC') );

		?>
        <style>
		ul.advanced li.selected a{
			display:block;
			background: #005672;
			color: white;
			text-decoration: none;
			padding:5px;	
		}
		
		.curtime #timestamp, .curtime .edit-timestamp {
			padding: 2px 0 1px;
			display: none!important;
			height: auto!important;
		}		
        </style>
        <select name="changepromptto" onchange="if(!confirm('If you change the prompt this story is assigned to you will affect the publication date to effectively be that of the date the Writing Prompt was published, today, or original -- whichever is latest. Click OK to confirm this choice.')){window.location='';}">
          <option  value="">-- Choose a Prompt --</option>
          <option  value="0" <? if($prompt == 0 || $prompt == ""){ ?>selected=""<? } ?>>My Own Topic</option>
          <?php 
  $remember = array();
  while ( $loop->have_posts() ) : $loop->the_post();
  	$pid = get_the_ID();
	$seuNow = get_post_meta( $pid , 'stories_embargo_until' , true );
	if($pid == $prompt){
		$firsttime = get_post_meta( $post->ID , 'first_time' , true);
		$seu = get_post_meta( $pid , 'stories_embargo_until' , true );
		if($firsttime == ""){
			$firsttime = " which publishes on ".date("F j, Y",strtotime($seu));	
		} else { 
			$firsttime = " was seen for the first time on ".date("F j, Y",strtotime($firsttime));	
		}
	}
  	//the_post_thumbnail('thumb',array('style'=>'width: 100%;margin-bottom:10px;')); 
	$permalink = get_permalink($pid); 
	if($pid == $prompt){
	$remember[id] = $pid;
	$remember[permalink] = get_permalink($pid);
	$remember[title] = get_the_title();	
	}
	
	?>
          <option <? if($pid == $prompt){ ?>selected=""<? } ?> value="<? echo $pid; ?>"><? echo the_title();  ?> - publishes on <? echo date("F j, Y",strtotime($seuNow)) ?></option>
          <?php 
   endwhile; 
   ?>
        </select>
        <?
   
   if($prompt > 0){
		?>
        <div class="notice" style="  border-left: 4px solid green;">
          <?
		  if($remember[title]){
			  $promptless = false;
			  $ft = esc_attr( get_post_meta( $post->ID , 'first_time' , true )  );
		  _e( "This story references the Writing Prompt <a href='".$remember[permalink]."' target='_blank'>'".$remember[title]."'</a> ".$firsttime, 'mythos-error' );
		  } else {
			 $promptless = true;
			 $ft = $post->post_date;
			  _e( "This story is an open topic published on ". date("F j, Y",strtotime($post->post_date)), 'mythos-error' );	  
		  }
		  
		  if($response->post_title){
			  ?><br><?
			  
			  $permalink = get_permalink($response);
			_e( "This story is in response to the story at <a href='".$permalink."' target='_blank'>'".$response->post_title."'</a>.<Br>Remove the response by clicking <a href='".$_SERVER['REQUEST_URI']."&response=remove'>here</a>. Warning: This is not reversable.", 'mythos-story-notice' );
		  }
		  ?>
        </div>
        <?	
		} else {
			?>
        <div class="error">
          <?
		  _e( 'This story will be \'My Own Topic\'.', 'mythos-error' );
		  if($response->post_title){
			  ?><br><?
			  
			  $permalink = get_permalink($response);
			_e( "This story is in response to the story at <a href='".$permalink."' target='_blank'>'".$response->post_title."'</a>.<Br>Remove the response by clicking <a href='".$_SERVER['REQUEST_URI']."&response=remove'>here</a>. Warning: This is not reversable.", 'mythos-story-notice' );
		  }
		  ?>
        </div>
        <?
		  $pid = 0;
		 
			
		}
	 
 ?></td>
    </tr>
    <? if(current_user_can('administrator')){ 
	
		
	
	
	?>
    <tr valign="top">
      <th scope="row"><label for="">Publication Date</label></th>
      <td>
      <? if($promptless){ ?>
      
      
      <div style="color:white;background:orange;">Notice: Changing the publication date will not effect 'My Own Topic' stories. These stories will always refer to their actual publication date.</div>
      <? } else { ?>
      <input type="text" name="first_time" class="datepicker"  value="<?php echo get_post_meta( $post->ID , 'first_time' , true ); ?>" />
        <div style="color:grey;">Note: You can alter the publication date. The publication date is the date shown when the story is displayed, but it also determines if the story is visible to the public now, or when the prompt goes live. For example, if you set this stories date to before its selected prompt, it will be visible before the prompt goes live.</div>
        <? } ?>
        </td>
    </tr>
    <tr valign="top">
      <th scope="row"><label for="stories_some_setting">Featured Story For Selected Prompt</label></th>
      <td><input type="checkbox" name="stories_featured_story" value="on" <?php if($feat == true){ ?>checked="" <? } ?>" /></td>
    </tr>
    
   
    <? } ?>
    <tr valign="top">
      <th scope="row"><label for="stories_some_text">Tags</label></th>
      <td><input type="text" name="stories_tags" style="width:100%" value="<?php echo esc_attr( get_post_meta( $post->ID , 'stories_tags' , true )  ); ?>" />
        <div style="color:grey;">Note: Tags are keywords for your story that help others find it. Separate tags with commas.</div></td>
    </tr>
    <? if(current_user_can('administrator')){  ?>
     <tr valign="top">
      <th scope="row"><label for="">Ad Code</label></th>
      <td>
    <textarea  name="ad_code" style="margin: 0px; height: 167px; width: 657px;resize:both;"><? echo get_post_meta($post->ID, "ad_code", true); ?></textarea>
    <br><em>Note: to display this in content use shortcode [retrospectadcode]. You can only user this shortcode on a page that loads either a story OR a author page.</em>
    </td>
    </tr>
    <? } ?>
  </tbody>
</table>
<? if(!current_user_can('administrator')){  ?>
<style>
.wp-switch-editor.switch-html {display: none;}
</style>
<script>
jQuery(document).ready(function(){
setTimeout(function(){
	jQuery(".wp-switch-editor.switch-tmce").trigger("click");
    },800);
});
</script>
<? } ?>
<?
}

