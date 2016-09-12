<?php

get_header();

$user = get_userdata(get_query_var('author')); 
// cannot figure out why paginated author pages 2 and over go 404
// so i wrote a replacement
//$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$paged = (get_query_var('page')) ? get_query_var('page') : 1;
$author_name = ($author_name)?$author_name:$user->user_login;
?>

<main class="content">
<a href="/members/<? echo $author_name; ?>">View <? echo $user->display_name; ?>'s profile</a>
		<?php WPGo_Hooks::wpgo_after_content_open(); ?>
<?

$curauth = get_userdatabylogin($author_name);
$curauth = get_user_by( "slug", $author_name) ;
$user_id = get_current_user_id();
if($user_id == $curauth->ID || current_user_can("administrator")){
	$loop = new WP_Query( array( 'post_status' => array('draft','publish'), 'author' => $curauth->ID, 'post_type' => array("stories"), 'posts_per_page' => 10 , 'paged' => $paged) );
} else {
	$loop = new WP_Query( array( 'post_status' => array('publish'), 'author' => $curauth->ID, 'post_type' => array("stories"), 'posts_per_page' => 10 , 'paged' => $paged) );
	
}


$scount = 0;


while ( $loop->have_posts() ) : $loop->the_post(); ?>
<? $anon = get_post_meta($post->ID, 'stories_is_anonymous', true); ?>

<? if(storyHasPermission($post)){ ?>
<? if(!$anon || $user_id == $curauth->ID || current_user_can("administrator")){ ?>
<? $scount ++; ?>

	<? get_template_part( 'content', 'story' ); ?>	
    <? } ?>
    <? } else { ?>
    <? if($embargoed == true && 0){ ?>
  <p>This story will be released on <? echo date("F j, Y",strtotime($embargoedate)); ?></p>
  ><? } ?>
    <? } ?>
<?php endwhile; ?>

<? if($loop->max_num_pages > $paged){ ?>
<span class="next"><a href="/author/<? echo $author_name; ?>/?page=<? echo $paged + 1; ?>">&lt;&lt; Older posts</a></span>
<? } ?>
<? if($paged > 1){ ?>
<? if($paged == 2){ ?>
<span class="prev"><a href="/author/<? echo $author_name; ?>">Newer posts &gt;&gt;</a></span>
<? } else { ?>
<span class="prev"><a href="/author/<? echo $author_name; ?>/?page=<? echo $paged - 1; ?>">Newer posts &gt;&gt;</a></span>
<? } ?>
<? } ?>
<!--
<span class="next"><?php next_posts_link( '&lt;&lt; Older posts', $loop->max_num_pages ); ?></span>
<span class="prev"><?php previous_posts_link( 'Newer posts  &gt;&gt;', $loop->max_num_pages ); ?>
-->


	

	

	</main><!-- .content -->
    <style>
	.myProfileBox{
		border-radius:25px;
		overflow:hidden;
		height:200px;
		width: 80%;	
		border:1px solid black;
	}
	
	.myProfileBox img{
		width: 100%;
		border: none;
		position:relative;
		top:-50px;	
	}
	table{
		  font-size: 10px;
  line-height: 10px;
  background-color: white;
  position: relative;
  bottom: 89px;
  z-index: 2;
	}
	table tbody th{
		
			
	}
	</style>
<aside id="primary-sidebar" class="sidebar-container" role="complementary">
<div  class="widget-area">
<div class="widget widget_meta">
<div class="myProfileBox">
<?
	if ( function_exists( 'bp_core_fetch_avatar' ) ) {
		echo apply_filters( 'bp_post_author_avatar', bp_core_fetch_avatar( array(
			'item_id' => $curauth->ID,
			'type'    => 'full',
			'alt'     => sprintf( __( 'Profile photo of %s', 'buddypress' ), bp_core_get_user_displayname( $post->post_author ) )
		) ) );
		$count = bp_follow_total_follow_counts(array('user_id' => $curauth->ID));
	}
	?>
    <table cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr><td colspan="3" align="center"><span style="color:blue;"><? echo $user->display_name; ?></span></td></tr>
     <tr><th align="center">Stories</th><th align="center">Followers</th><th align="center">Following</th></tr>
     <tr><td align="center"><? echo storyCount($curauth->ID);  ?></td><td align="center"><? echo $count[followers]; ?></td><td align="center"><? echo $count[following]; ?></td></tr>
    </tbody>
    </table>
</div>
</div>
</div>

<?php get_sponsor(); ?>
</aside>
<?php get_sidebar(); ?>
<?php get_footer(); ?>