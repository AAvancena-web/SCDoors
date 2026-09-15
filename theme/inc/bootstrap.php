<?php
/**
 * Bootstrap for the 2026 design.
 *
 * Add one line to the bottom of the existing child theme functions.php:
 *
 *     require_once get_stylesheet_directory() . '/inc/bootstrap.php';
 *
 * Nothing already in that file needs removing.
 *
 * @package SCDoors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * 2026 design bootstrap
 * ---------------------------------------------------------------------- */
require_once get_stylesheet_directory() . '/inc/helpers.php';
require_once get_stylesheet_directory() . '/inc/drawer-walker.php';
require_once get_stylesheet_directory() . '/inc/setup.php';
require_once get_stylesheet_directory() . '/inc/acf-fields.php';
require_once get_stylesheet_directory() . '/inc/seeder.php';

/**
 * Footer menu locations used by the redesigned footer.
 */
function scd_register_menus() {
	register_nav_menus(
		array(
			'footer-quick'    => __( 'Footer: Quick Links', 'siteorigin-corp' ),
			'footer-services' => __( 'Footer: Our Services', 'siteorigin-corp' ),
		)
	);
}
add_action( 'after_setup_theme', 'scd_register_menus' );

/**
 * Mark pages that render the inner banner so the container override applies.
 */
function scd_inner_body_class( $classes ) {
	if ( is_singular() && ! is_page_template( 'page-templates/template-home.php' ) ) {
		$classes[] = 'scd-inner';
	}
	return $classes;
}
add_filter( 'body_class', 'scd_inner_body_class' );

/**
 * The parent theme prints the featured image again inside the entry. The
 * banner already uses it, so suppress the duplicate.
 */
add_filter( 'siteorigin_corp_display_post_thumbnail', '__return_false' );
