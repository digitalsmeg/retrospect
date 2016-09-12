<?php WPGo_Hooks::wpgo_after_content_close(); ?>

</div><!-- #container -->

</div><!-- #outer-container -->

<footer id="footer-container">
	<?php WPGo_Hooks::wpgo_after_opening_footer_tag(); ?>
	<?php get_sidebar( 'footer' ); // Adds support for the four footer widget areas ?>
	<?php WPGo_Hooks::wpgo_before_closing_footer_tag(); ?>
</footer>

</div><!-- #body-container -->

<?php WPGo_Hooks::wpgo_after_closing_footer_tag(); ?>
<?php wp_footer(); ?>

</body>
</html>