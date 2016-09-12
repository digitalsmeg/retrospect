<?php

if ( is_active_sidebar( 'footer-widget-area' ) ) :
	?>
	<section class="footer-widget-container">
		<div id="footer-widget-area" class="inside widget-area">
			<?php dynamic_sidebar( 'footer-widget-area' ); ?>
		</div>
		<!-- .inside -->
	</section><!-- .footer-widget-container -->
<?php
endif;

?>