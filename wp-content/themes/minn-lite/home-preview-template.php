<?php
/*
Template Name: Home Page Preview  Template 
*/
get_header();
?>

<main class="content homeContent">
  <?php WPGo_Hooks::wpgo_after_content_open(); ?>
  <?

$currentID = getCurrent();

?>
  <?php $loop = new WP_Query( array( 'post_type' => 'prompts', 'posts_per_page' => 1, 'post__in' => array($currentID)) ); ?>
  <div class="cycle-slideshows" data-cycle-timeout="5000" data-cycle-slides="> div" >
    <?php while ( $loop->have_posts() ) : $loop->the_post();



  $pid = get_the_ID();



  $embargo = get_post_meta($post->ID,'stories_embargo_until',true);



 if(1){ ?>
    <div class="slide">
      <? the_post_thumbnail('full',array('style'=>'width: 100%')); ?>
      <? $permalink = get_permalink($pid); ?>
      <div class="headline"><span>This Week&rsquo;s Prompt:</span><span class="title">
        <? the_title(); ?>
        </span><span class="start"><a href="/wp-admin/post-new.php?post_type=stories&prompt=<? echo the_ID(); ?>">Start Writing</a></span> </div>
      <div class="retrospect">
       <p><? echo nl2br(get_option('myth_home_text')); ?></p>
        <a class="more right" href="/about-us">Learn More</a>
        <div class="clear"></div>
      </div>
    </div>
    <? } ?>
    <?php endwhile; ?>
  </div>
  <?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  <article id="post-<?php the_ID(); ?>" <?php post_class( 'singular-page' ); ?>>
    <div class="post-content">
      <?php
 the_content();
 wp_link_pages( array( 'before' => '<div class="page-link">', 'after' => '</div>' ) );
 ?>
    </div>
    
    <!-- .post-content --> 
    
  </article>
  
  <!-- .post -->
  
  <?php endwhile; ?>
 
 

  
  <div class="content">
  
  <div class="content advertisement">
 
  <!--
    <div class="col6">Advertisement</div>
    <div class="col6">Advertisement</div>
    -->
  </div>
  <!--
  <div class="content sponsors">
    <div class="col12">
      <h2>Thanks to Our Sponsors</h2>
    </div>
  </div>
  -->
  <div class="content sponsors">
    <?php $l = new WP_Query( array( 'post_type' => 'sponsors', 'posts_per_page' => 5 ) ); ?>
    <?php while ( $l->have_posts() ){
 $l->the_post(); ?>
    <div class="col2"><a href="<? echo  get_permalink();?>">
      <? the_post_thumbnail('full'); ?>
      </a></div>
    <?php } ?>
  </div>
</main>

<!-- .content -->

<?php get_footer(); ?>