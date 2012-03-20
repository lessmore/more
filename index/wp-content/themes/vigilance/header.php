<?php global $vigilance; ?>
<!DOCTYPE html>
<html <?php language_attributes( 'html' ) ?>>
<head>
	<?php if ( is_front_page() ) : ?>
		<title><?php bloginfo( 'name' ); ?> | <?php bloginfo( 'description' );?></title>
	<?php elseif ( is_404() ) : ?>
		<title><?php _e( 'Page Not Found |', 'vigilance' ); ?> <?php bloginfo( 'name' ); ?></title>
	<?php elseif ( is_search() ) : ?>
		<title><?php printf( __("Search results for '%s'", "vigilance"), get_search_query()); ?> | <?php bloginfo( 'name' ); ?></title>
	<?php else : ?>
		<title><?php wp_title($sep = '' ); ?> | <?php bloginfo( 'name' );?></title>
	<?php endif; ?>

	<!-- Basic Meta Data -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="copyright" content="<?php
		esc_attr( sprintf(
			__( 'Design is copyright %1$s The Theme Foundry', 'vigilance' ),
			date( 'Y' )
		) );
	?>" />

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.ico" />

	<!-- WordPress -->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div class="skip-content"><a href="#content"><?php _e( 'Skip to content', 'vigilance' ); ?></a></div>
	<div id="wrapper">
		<div id="header" class="clear">
			<?php
			$logo_markup = is_home() ? '<h1 id="title"><a href="%1$s">%2$s</a></h1>' : '<div id="title"><a href="%1$s">%2$s</a></div>';
			printf(
				$logo_markup,
				home_url( '/' ),
				get_bloginfo( 'name' )
			);
			?>
			<div id="description">
				<h2><?php bloginfo( 'description' ); ?></h2>
			</div><!--end description-->
			<?php
				wp_nav_menu(
					array(
						'theme_location'  => 'nav-1',
						'container_id'    => 'navigation',
						'menu_class'      => 'nav',
						'fallback_cb'     => array( &$vigilance, 'main_menu_fallback' )
					)
				);
			?>
		</div><!--end header-->
		<div id="content" class="pad">