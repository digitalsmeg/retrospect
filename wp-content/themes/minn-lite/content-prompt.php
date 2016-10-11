<?
$user = wp_get_current_user(); 
$currentID = getCurrent();
		$sql = "SELECT * FROM  ".$wpdb->prefix."postmeta WHERE meta_key = 'stories_embargo_until' AND post_id = ". $currentID;
		$result = $wpdb->get_results($sql,ARRAY_A);
		$sas = $result[0];
		$id = $sas[post_id];
		$date = $sas[meta_value];


?>
<div class="<? if(isset($_GET[sort])){ ?>all-prompts <? } ?><? if(isset($_GET[all])){ ?>all-stories <? } ?>writing-prompt storyPromptContainer<? if($post->stories_hide_until > date("Y-m-d") && (get_option('myth_embargo_debug') == "on" && current_user_can('administrator'))){ ?> embargoDebug<? } ?>">
<? 

$sc = storyCountWritingPrompt($post->ID);
if($sc > 0){
	if($sc == 1){
		$s = "Story";
	} else {
		$s = "Stories";	
	}
	$sc = " <span style='font-size:14px;'>($sc $s)</span>";
} else {
	$sc = "";	
}
?>
    <?php the_title( '<h2 class="entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a>'.$sc.'</h2>' ); ?>
 
	<div class="post-meta" >

		<? if(!is_single()){ ?>						
  <? 	if(!isset($_GET[sort])){ ?>
								<p<?php echo $header_align_meta; ?>>
	<time class="date" datetime="<?php echo date("F j, Y",strtotime($post->stories_embargo_until)); ?>"><? if($date < $post->stories_embargo_until){
			?>Goes Live on <? 
			$golive = $post->stories_embargo_until;
			echo date("F j, Y",strtotime($golive));
		} elseif($id == $post->ID){
			?><?
		} else { 
			?><?
		} ?> [<? if($date < $post->stories_embargo_until){
			?>Upcoming<?
		} elseif($id == $post->ID){
			?>Current<?
			$is_current = $post->ID;
		} else { 
			?>Past<?
			
		} ?>]</time> / <span class="author"><?php echo $author_prefix; ?><?php the_author_posts_link(); ?></span> / <span class="categories">Writing Prompts</span> 
        

						
								</p>
                                <? } ?>
                              
            
							</div>
                              <? } ?>
    <div class="entry-content">
   
        <?
		//$image = get_first_image();
		$wpid = get_the_ID();
			
		?>
        <input type="hidden" id="wpid" value="<? echo $wpid; ?>" />
        <? if(isset($_GET[all])){ ?> <input type="hidden" id="allstories" value="true" /><? } else { ?>
        <input type="hidden" id="allstories" value="false" />
        <? } ?>
		<?php if($image != ""){ ?><img style="display:block;margin:0px 0px 10px 0px;" src="<? echo $image; ?>" /> <? } else { 
		if(is_single()){ 
			the_post_thumbnail('medium',array('align'=>'left','style'=>'margin:0px 5px 5px 0px'));
		
			$content = preg_replace('/<img[^>]+./','', get_the_content());
			echo nl2br($content);
			$golive = $post->stories_embargo_until;

			if($user->ID > 0 && (int)$wpid != 5520 && (int)$wpid != 5759){ ?>
       			<a class="more" href="/wp-admin/post-new.php?post_type=stories&prompt=<? echo $post->ID; ?>">Start Writing</a>
       		<? } else if((int)$wpid != 5520 && (int)$wpid != 5759){ ?>
     
       			<a class="more" href="/wp-login.php?redirect_to=<? echo get_permalink(); ?>">Start Writing</a>
       		<? 
	   }
	   ?>
       
     
       <?
		} else { 
		if(!isset($_GET[sort])){
			the_post_thumbnail('thumbnail',array('align'=>'left','style'=>'margin:0px 5px 5px 0px'));
			echo nl2br(strip_tags(get_the_content()));
		}
		}
		
		
		}
		$author_id = get_the_author_id();
		
		?>  
      
     <div class="clear"></div>
   
     <? if(!is_single()){ ?>
     	<? if($date >= $post->stories_embargo_until || $is_current){ ?>
        <a class="more" href="<? echo  get_permalink(); ?>">Read Stories</a>
        <? } ?>
       
    
      <? if($user->ID > 0 && (int)$wpid != 5520 && (int)$wpid != 5759){ ?>
       <a class="more" href="/wp-admin/post-new.php?post_type=stories&prompt=<? echo $post->ID; ?>">Start Writing</a>
       <? } else { ?>
       <? if(!is_single() && (int)$wpid != 5520 && (int)$wpid != 5759){ ?>
       <a class="more" href="/wp-login.php?redirect_to=<? echo get_permalink(); ?>">Start Writing</a>
       <? } ?>
       <? } ?>
        <? if($author_id == $user->ID && !is_single()) { ?> <a class="more right" style="margin-left:5px;" href="/wp-admin/post.php?post=<? echo $post->ID ; ?>&action=edit">Edit Your Prompt</a> 
       <? } ?> 
       <? } ?>
        
    </div>
      <div class="clear"></div> 
      <? if($wpid == $currentID){$is_current = true; } ?>
	  <? if((($single || is_single()) && $date >= $post->stories_embargo_until) || (($single || is_single()) ) || ($is_current)){
			
   
      
		
		$sql = "SELECT * FROM ".$wpdb->prefix."usermeta WHERE meta_key = 'stories_prompted_{$wpid }'"; 
		
		$result = $wpdb->get_results($sql,ARRAY_A);
		$thePosts = array();
		$total = sizeof($result);
		foreach($result as $row){
			$thePosts[$row[meta_value]] = $row[meta_value];
			
		}
	// if(($post->stories_embargo_until <= date("Y-m-d") && is_single())){ 
		?>
        <? if((is_single())){ ?>
        <hr />
        <? if(date("Y-m-d") < $golive) { ?>
         <p>New stories will go live on <? echo date("F j, Y",strtotime($golive)); ?></p>
         <? } ?>
        <h1 class="page-title entry-title" style="background: #FF0F54;
    color: white;
    padding: 5px;display:none;">Related Stories</h1>
      
        <?
		 $word = "stories";
		// not being used due to ajax
		
		if(1){
			wp_reset_query();
			$paged = (get_query_var('page')) ? get_query_var('page') : 1;
			
		
		
			// test -- THIS WORKS!
			
				$loop = new WP_Query( 
					array( 
					'post__in' => $thePosts,
					'post_type' => 'stories', 
					'orderby' => 'date',
					'order' => 'DESC', 
					'posts_per_page' => 10 , 
					'paged' => $paged , 
					'meta_query' => array(
								'relation' => 'OR',
								array(
									'value' => 'funny',
								),
								array(
									'value' => 'moving',
								),
								)
					)
				);
				
				// ive read = key = mark_as_read{userid} = 1
				// havent read  = key = mark_as_read{userid} = null
				// people I follow - first grab array, then array of authors
				// for characerizations, array of meta values equaling characterizations
				
					// THE QUERY
			$loop = new WP_Query( array( 'post__in' => $thePosts,'post_type' => 'stories', 'orderby'=> 'date','order'   => 'DESC', 'posts_per_page' => 10 , 'paged' => $paged) );
			
		?>
         <!-- begin ajax stories -->
        <? showFeatured($wpid); ?>
        <div class="ajaxstories">
      <?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
		<? if(storyHasPermission($post)){  ?>
       <!-- THE LOOP -->
		<? //get_template_part( 'content', 'story' ); ?>
      
   	 <? } ?>
     
      <?php endwhile; ?>
    </div>
    <!--
      <?  $older = $paged + 1;
		  $newer = $paged - 1;  ?>
          <? if($page < $loop->max_num_pages){ ?>
      <span class="next"><a href="/prompts/hair/<? echo $older; ?>/">&lt;&lt; Older Stories</a></span>
      <? } ?>
      <? if($newer > 0){ ?>
	 <span class="prev"><a href="/prompts/hair/<? echo $newer; ?>/">Newer Stories &gt;&gt;</a></span>
     <? } ?>
     -->
    
       <? } else { ?>
       <p>
       There are no stories.
       </p>
	   <? } ?>
         <? } else { ?>
         <? if(! $is_current){ ?>
         <p>
         <p>New stories will go live on <? echo date("F j, Y",strtotime($golive)); ?></p>
         </p>
         <? } ?>
         <? } ?>
        <? } ?>
  
        
         <!-- end ajax stories -->
  </div>

  <? if(is_single()){ ?>
  <aside id="primary-sidebar" class="sidebar-container filter-sidebar" role="complementary" style="display:none;">
 <!-- this container gets moved with jquery to a sidebar position using jQuery(".filter-sidebar").insertAfter("main"); -->
	<div id="primary-post-widget-area" class="widget-area">
    
     <!-- THE FILTER -->
    
     <div style="float:left;width: 100%;" class="filterdiv">
       <strong>Filter By </strong><br><hr />
       <?
       $ch = explode(",",get_option('myth_charaterization'));
		foreach($ch as $key=>$value){
		$value = trim($value);
		?>
  		<label><? echo $value; ?> <input type="checkbox"  class="afilter"   value="<? echo $value; ?>"> </label>
    <? } ?>
   
      
       <label>stories I've read<input type="checkbox"  class="afilter"  value="read"  /> </label> 
       <label>stories I haven't read <input type="checkbox"  class="afilter"  value="notread" /> </label>
       <label>people I follow <input type="checkbox" class="afilter" value="follow" /> </label> 

      
       </div>
       
   
     <!-- THE SORT -->
    <hr />
     <div style="float:left;width: 100%;">
      <strong>Sort By</strong> <select class="sorter">
     <option value="0">newest first</option>
     <option value="1">oldest first</option>
     <option value="2">most reader response first</option>
     <option value="3">surprise me</option>
     </select>
     </div>
      <div class="clear"></div>
	 <? if (!isset($_GET['all']) && !preg_match("/\/prompts\/all\//",$_SERVER['REQUEST_URI']) ){ ?>
        <hr />
        <? if (preg_match("/\/prompts\/featured\//",$_SERVER['REQUEST_URI']) ){ ?>
        <a href="<? echo get_permalink($wpid); ?>?all">See All Featured Stories</a>
         <? } else { ?>
          <a href="<? echo get_permalink($wpid); ?>?all">See All Stories From This Prompt</a>
          <? } ?>
	 <? } ?>
       <hr />
    </div>
    </aside>
     <? } ?>
  
  