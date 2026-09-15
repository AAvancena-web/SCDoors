<?php
/**
 * Theme setup, asset loading and body classes for the 2026 design.
 *
 * @package SCDoors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Bumping this busts the browser cache for the front end bundle.
 */
if ( ! defined( 'SCD_ASSET_VERSION' ) ) {
	define( 'SCD_ASSET_VERSION', '1.0.0' );
}

/**
 * Front end assets.
 *
 * The bundle is loaded on every page because the header, footer, mobile drawer
 * and inner page banner are global. It is deliberately scoped behind the
 * .scd-scope wrapper so it cannot restyle pages still built in WPBakery.
 */
function scd_enqueue_assets() {
	wp_enqueue_style(
		'scd-fonts',
		'https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700&family=Inter:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'scd-main',
		get_stylesheet_directory_uri() . '/assets/css/scd.css',
		array( 'chld_thm_cfg_child' ),
		SCD_ASSET_VERSION
	);

	wp_enqueue_script(
		'scd-main',
		get_stylesheet_directory_uri() . '/assets/js/scd.js',
		array(),
		SCD_ASSET_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'scd_enqueue_assets', 20 );

/**
 * Body classes.
 *
 * scd-scope carries the base typography for the new design. It is added on
 * every page so the global header and footer are styled, while the element
 * level rules inside the stylesheet stay behind this wrapper.
 */
function scd_body_class( $classes ) {
	/*
	 * scd-scope is deliberately NOT added here. It carries the base element
	 * rules (p, a, h1-h6, img), and on <body> those would cascade into every
	 * legacy WPBakery page. It goes on the individual wrappers instead: the
	 * header, footer, drawer, inner banner and the 2026 page content.
	 */
	if ( is_page_template( 'page-templates/template-home.php' ) ) {
		$classes[] = 'scd-home';
	}

	return $classes;
}
add_filter( 'body_class', 'scd_body_class' );

/**
 * The parent theme prints its own sticky header behaviour and mobile menu.
 * The 2026 header supplies both, so the parent script is left in place but
 * finds no matching elements. Nothing to dequeue, documented here so the next
 * developer does not go looking.
 */
