<?php

get_header();

$user = wp_get_current_user(); 
// prohibit non captains
if(!in_array("team_captain",$user->roles) ){
	//wp_redirect( '/wp-login.php', 301 ); 
	//exit;	
}
?>

	<main class="content">

		<?php WPGo_Hooks::wpgo_after_content_open(); ?>
<?

$curauth = get_userdatabylogin(sanitize_text_field($_GET[s]));


?>
<div class="">
<div class="search">

                    <form id="form1" <? if(!empty($_GET[web_search_submit])){ ?>style="display:none;"<? } ?>role="search" method="get" class="search-form" action="<? echo esc_url( home_url( '/' ) ) ; ?>" >

                        <input style="width:70%" type="search" placeholder="Search Stories" value="<? echo get_search_query(); ?>" name="s">

                        <input type="submit" class="search-submit" value="<? echo  esc_attr(__( 'Search', 'wpgothemes' )); ?> Stories">

                    </form>
                    <form id="form2"  <? if(empty($_GET[web_search_submit])){ ?>style="display:none;"<? } ?> role="search" method="get" class="search-form" action="<? echo esc_url( home_url( '/' ) ) ; ?>" >

                        <input style="width:70%" type="search" placeholder="Search Website" value="<? echo get_search_query(); ?>" name="s">
						<input type="hidden" name="web_search_submit" value="Search" />
                        <input  type="submit" class="search-submit" value="<? echo  esc_attr(__( 'Search', 'wpgothemes' )); ?> Website">

                    </form>
                    
                    <form id="form3" style="display:none;"  role="search" method="get" class="search-form" action="<? echo esc_url( home_url( '/' ) ) ; ?>/members/" >

                        <input style="width:70%" type="search" placeholder="Search Users" value="<? echo get_search_query(); ?>" name="s">
				<input type="hidden" name="members_search_submit" value="Search" />
             <input  type="submit" class="search-submit" value="<? echo  esc_attr(__( 'Search', 'wpgothemes' )); ?> Users">

                    </form>
			<label ><input onclick="jQuery('.search>.search-form').hide();jQuery('#form1').show();" type="radio" value="1" name="sf" <? if(empty($_GET[web_search_submit])){ ?>checked=""<? } ?> > Search Stories </label>
			<label >  <input onclick="jQuery('.search>.search-form').hide();jQuery('#form2').show();" type="radio" value="1" name="sf" <? if(!empty($_GET[web_search_submit])){ ?>checked=""<? } ?>> Search Website</label>
			<label > <input onclick="jQuery('.search>.search-form').hide();jQuery('#form3').show();" type="radio" value="1" name="sf" > Search Members </label>
                </div>
</div>
<? //'author_name' => $_GET[s],  ?>
<? if(empty($_GET[web_search_submit])){ ?>
<h1>Searching Stories</h1>




<?php $loop = new WP_Query( array( 's'=> $_GET[s], 'post_type' => array("stories"), 'posts_per_page' =>  10 , 'paged' => $paged) ); ?>
	



			

<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>
<?  get_template_part( 'content', 'story' ); ?>
<?php endwhile; ?>

<span class="next"><?php next_posts_link( '&lt;&lt; Older posts', $loop->max_num_pages ); ?></span>
<span class="prev"><?php previous_posts_link( 'Newer posts  &gt;&gt;', $loop->max_num_pages ); ?>
<? } else {  
 $not = array(1291,1289,30,28,289,771,1406,1408);

?>
<h1>Searching Website</h1>
<?php 
$loop = new WP_Query( array( 'post__not_in' => $not, 's'=> $_GET[s], 'post_type' => array("page","prompts","post"), 'posts_per_page' =>  10 , 'paged' => $paged) ); 
$loop = new WP_Query( array( 's'=> $_GET[s], 'post_type' => array("stories"), 'posts_per_page' =>  10 , 'paged' => $paged) );
?>
	
<?php WPGo_Hooks::wpgo_after_content_open(); ?>
<?php get_template_part( 'loops/loop', 'search' ); ?>

<? } ?>

<? $total = $loop->post_count; ?>
<? if($total > 0){ ?>
<span class="next"><?php next_posts_link( '&lt;&lt; Older Prompts', $loop->max_num_pages ); ?></span>
<span class="prev"><?php previous_posts_link( 'Newer Prompts  &gt;&gt;', $loop->max_num_pages ); ?>
<? } else { ?>
<div class="entry-content">
<img src="/wp-content/uploads/2015/12/cartoon-writer2527sblock-gina.jpg" style="margin:0px 5px 5px 0px;width:200px" class="attachment-thumb wp-post-image" align="left" alt="Sorry">

Sorry. We couldn't find what you were looking for.</div>
<? } ?>
</main>
<!-- .content -->

<?php get_footer(); ?>