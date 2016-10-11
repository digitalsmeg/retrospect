<?php WPGo_Hooks::wpgo_before_head(); ?>
<head>
<meta charset="utf-8" />

<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<?php WPGo_Hooks::wpgo_head_top(); ?>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="icon" type="image/png" href="/favicon.png" />
<?php wp_head(); ?>
<link href='http://fonts.googleapis.com/css?family=Sorts+Mill+Goudy:400,400italic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="/wp-content/themes/minn-lite/font-awesome//css/font-awesome.min.css">
<script>
var addThisInv = 0;
jQuery(document).ready(function($){
	if (window.location.hash && window.location.hash == '#_=_') {
        if (window.history && history.pushState) {
            window.history.pushState("", document.title, window.location.pathname);
        } else {
            // Prevent scrolling by storing the page's current scroll offset
            var scroll = {
                top: document.body.scrollTop,
                left: document.body.scrollLeft
            };
            window.location.hash = '';
            // Restore the scroll offset, should be flicker free
            document.body.scrollTop = scroll.top;
            document.body.scrollLeft = scroll.left;
        }
    }
	
	
	
	
	
	// why did we add this
	//jQuery("iframe").attr("src",jQuery("iframe").attr("src")+"&entry_1195960677=<? echo $_SERVER['HTTP_REFERER']; ?>");



	$(".pageMore.number").on("click",function(){

			$(".pageMore.number").removeClass("selected");

			$(this).addClass("selected");

			var c = jQuery(".pageMore.number.selected").index() - 1;

			

			$(".allStories").hide();

				$(".allStories.page"+c).show();

	});

	$(".pageMore").not(".number").on("click",function(){

			$(this).addClass("selected");

			var th = $(this);

			setTimeout(function(){

				th.removeClass("selected");

				var c = jQuery(".pageMore.number.selected").index();

				if(th.is(".nnext")){

					

					if(c >= jQuery(".pageMore.number").length){

						c = 0;

					}

				} else {

					c = c - 2;

					if(c < 0){

						c = jQuery(".pageMore.number").length - 1;

					}

				}

				

				$(".pageMore.number").removeClass("selected");

				$(".pageMore.number").eq(c).addClass("selected");

				$(".allStories").hide();

				$(".allStories.page"+c).show();

			},100);

			

	});

});

</script>
<script src='//activedemand-static.s3.amazonaws.com/public/javascript/jquery.tracker.compiled.js.gz' type='text/javascript'></script>
</head>

<body <?php body_class( WPGo_Utility::theme_classes() ); ?>>
<div id="fb-root"></div>
<script>(function(d, s, id) {

  var js, fjs = d.getElementsByTagName(s)[0];

  if (d.getElementById(id)) return;

  js = d.createElement(s); js.id = id;

  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=223470507696511";

  fjs.parentNode.insertBefore(js, fjs);

}(document, 'script', 'facebook-jssdk'));</script>
<div id="body-container">

<!-- #header-container -->

<?php WPGo_Hooks::wpgo_after_header_close(); ?>
<div id="outer-container">
<?php WPGo_Hooks::wpgo_after_outer_container_open(); ?>
<div id="container">
<div id="header-container">
  <div id="print-logo" class="print"><img src="/wp-content/themes/minn-lite/images/RetrospectLogoAndTag-Print.png" alt="" /></div>
  <header>
    <div id="logo-wrap">
      <?php







				if ( is_front_page() || is_home() || is_archive() ) {



					//echo '<h1 id="site-title"><span><a href="' . get_home_url() . '" />' . get_bloginfo( 'name' ) . '</a></span></h1>';



				} else {



					//echo '<h2 id="site-title"><span><a href="' . get_home_url() . '" />' . get_bloginfo( 'name' ) . '</a></span></h2>';



				}







				$opt = WPGo_Theme_Customizer::get_customizer_theme_option( 'wpgo_chk_hide_description' );



				if ( 1 ) {



					?>
      <a href="<? echo get_home_url(); ?>" ><img alt="Retrospect. Think Back. Share Forward." class="logo" src="/wp-content/themes/minn-lite/images/RetrospectLogoandtag.png" /></a>
      <h2 id="site-title"><span>
        <?php //bloginfo( 'title' ); ?>
        </span></h2>
      <div id="site-description">
        <?php //bloginfo( 'description' ); ?>
      </div>
      <?php } ?>
    </div>
    
    <!-- #logo-wrap --> 
    
    <script>var menuswitchopen;</script>
    <div id="menuswitch"><a href="javascript:void(0);" onclick='

if(menuswitchopen!=true){

jQuery(".menu-main-menu-container").css("display","inherit");

menuswitchopen=true;

} else {

jQuery(".menu-main-menu-container").css("display","none");

menuswitchopen=false;

}

'><img src="/wp-content/uploads/2015/11/bars.png" width="22" height="16" alt=""/></a></div>
   
<?php



					$args = array(



						'theme_location' => WPGO_CUSTOM_NAV_MENU_1



						/*'container_class' => 'primary-menu',



						'menu_class' => ''*/ );



					wp_nav_menu( $args );



					?>
    <form role="search" id="topSearch" method="get" class="search-form" action="/">
      <input style="padding-left: 10px; margin-bottom: 10px;width: 80%;" type="search" placeholder="Search Stories..." value="<? echo $_GET[s]; ?>" onKeydown="if (event.keyCode==13){jQuery('#topSearch').submit();}" name="s">
      <a class="more" href="javascript:jQuery('#topSearch').submit();" style="display:inline-block;border-radius:0px;height:20px;  height: 26px;

  line-height: 28px;">Go</a>
  <br>
  <a href="/index.php?s= ">Advanced Search</a>
    </form>
  </header>
  
  <!-- header --> 
  
</div> 
<?php WPGo_Hooks::wpgo_before_content_open(); ?>
