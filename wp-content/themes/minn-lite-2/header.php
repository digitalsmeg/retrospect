<?php WPGo_Hooks::wpgo_before_head(); ?>
<head>
	<meta charset="utf-8" />

	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<?php WPGo_Hooks::wpgo_head_top(); ?>

	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<?php wp_head(); ?>
</head>

<body <?php body_class( WPGo_Utility::theme_classes() ); ?>>

<div id="body-container">

	<div id="header-container">

		<header>
			<?php if ( current_theme_supports( 'wpgo-custom-menus' ) && has_nav_menu( WPGO_CUSTOM_NAV_MENU_2 ) ) : ?>
				<nav class="secondary-menu">
					<?php
					$args = array(
						'theme_location' => WPGO_CUSTOM_NAV_MENU_2
						/*'container_class' => 'secondary-menu',
						'menu_class' => ''*/ );
					wp_nav_menu( $args );
					?>
				</nav>
			<?php endif; ?>

			<div id="logo-wrap">
				<?php

				if ( is_front_page() || is_home() || is_archive() ) {
					echo '<h1 id="site-title"><span><a href="' . get_home_url() . '" />' . get_bloginfo( 'name' ) . '</a></span></h1>';
				} else {
					echo '<h2 id="site-title"><span><a href="' . get_home_url() . '" />' . get_bloginfo( 'name' ) . '</a></span></h2>';
				}

				$opt = WPGo_Theme_Customizer::get_customizer_theme_option( 'wpgo_chk_hide_description' );
				if ( empty( $opt ) ) {
					?>
					<div id="site-description"><?php bloginfo( 'description' ); ?></div>
				<?php } ?>

			</div>
			<!-- #logo-wrap -->

		</header>
		<!-- header -->

		<?php if ( current_theme_supports( 'wpgo-custom-menus' ) ) : ?>
			<div class="nav-wrap">
				<nav class="primary-menu">
					<label onclick="" for="nav-respond" id="nav-respond-wrapper"></label>
					<input type="checkbox" name="nav-respond" id="nav-respond" />
					<?php
					$args = array(
						'theme_location' => WPGO_CUSTOM_NAV_MENU_1
						/*'container_class' => 'primary-menu',
						'menu_class' => ''*/ );
					wp_nav_menu( $args );
					?>
				</nav>
			</div><!-- .nav-wrap -->
		<?php endif; ?>
	</div><!-- #header-container -->

<?php WPGo_Hooks::wpgo_after_header_close(); ?>

	<div id="outer-container">

<?php WPGo_Hooks::wpgo_after_outer_container_open(); ?>

	<div id="container">

			<?php WPGo_Hooks::wpgo_before_content_open(); ?>