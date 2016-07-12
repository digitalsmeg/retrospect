<?
$user = wp_get_current_user(); 
?>
<div class="storyPromptContainer">
    <?php the_title( '<h2 class="entry-title"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a></h2>' ); ?>
 
	<div class="post-meta" >

								

								<p<?php echo $header_align_meta; ?>>
	<time class="date" datetime="<?php the_date( 'c' ); ?>" pubdate><?php the_time( get_option( 'date_format' ) ); ?></time> / <span class="categories">Sponsorship</span>

									
								</p>

							</div><!-- .author-bio -->
    <div class="entry-content">
   
       
		<?php if($image != ""){ ?><img style="display:block;margin:0px 0px 10px 0px;" src="<? echo $image; ?>" /> <? } else { 
		the_post_thumbnail('thumbnail',array('align'=>'left','style'=>'margin:0px 5px 5px 0px'));
		the_excerpt();} 
		?>  
     
      
        
    </div>
      <div class="clear"></div>
  </div>