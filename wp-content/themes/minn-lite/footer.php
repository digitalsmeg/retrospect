<footer id="footer-container">
	<?php
	$args = array( 'menu' => 'Footer Menu', );
	wp_nav_menu( $args );
	?>
    <div class="socialicons">
   <!-- <span class="fa-stack fa-lg">
      <i class="fa fa-circle fa-stack-2x"></i>
      <i class="fa fa-google-plus fa-stack-1x fa-inverse"></i>
    </span> -->
    <a href="https://www.facebook.com/myretrospect/" target="_blank"><span class="fa-stack fa-lg">
      <i class="fa fa-circle fa-stack-2x"></i>
      <i class="fa fa-facebook fa-stack-1x fa-inverse"></i>
    </span></a>
     <!-- <span class="fa-stack fa-lg">
      <i class="fa fa-circle fa-stack-2x"></i>
      <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
    </span>
     <span class="fa-stack fa-lg">
      <i class="fa fa-circle fa-stack-2x"></i>
      <i class="fa fa-linkedin fa-stack-1x fa-inverse"></i>
    </span> -->
</div>
<ul class="submenu">
<li><a href="/terms-of-service">Terms of Service</a></li>
<li><a href="/privacy-policy">Privacy Policy</a></li>
<li><a href="/copyright-dispute">Copyright Dispute</a></li>
<li><a href="/community-guidelines">Community Guidelines</a></li>
<li><a href="/contact">Contact</a></li>
</ul>
<div class="clear"></div>
<div class="copyright" style="font-size:12px; padding:15px;text-align:right;color:#444;">Website ©2015-<? echo date("Y"); ?> Retrospect Media, Inc. Retrospect is a trademark of Retrospect Media, Inc.<br>
All stories are copyright © their individual authors.</div>
</footer>
</div><!-- #container -->
</div><!-- #outer-container -->
</div><!-- #body-container -->
<?php WPGo_Hooks::wpgo_after_closing_footer_tag(); ?>
<?php wp_footer(); ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga' [^]);

  ga('create', 'UA-71276362-2', 'auto');
  ga('send', 'pageview');

</script>
<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
 
fbq('init', '526693500856743');
fbq('track', "PageView");</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=526693500856743&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code —>
</body>
</html>