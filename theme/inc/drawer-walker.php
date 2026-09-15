<?php
/**
 * Menu walker for the mobile drawer.
 *
 * Wraps a parent item and its caret in a row so the label still navigates
 * while the caret expands the submenu, and nests the submenu in a single
 * child element so the 0fr grid track can collapse it to exactly zero.
 *
 * @package SCDoors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SCD_Drawer_Walker extends Walker_Nav_Menu {

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '<ul class="scd-m-sub"><li><div class="scd-m-sub-wrap"><ul class="scd-m-sub-list">';
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= '</ul></div></li></ul>';
	}

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$has_children = in_array( 'menu-item-has-children', (array) $item->classes, true );
		$classes      = $has_children && 0 === $depth ? ' class="scd-has-sub"' : '';

		$output .= '<li' . $classes . '>';

		$link = sprintf(
			'<a href="%s">%s</a>',
			esc_url( $item->url ),
			esc_html( $item->title )
		);

		if ( $has_children && 0 === $depth ) {
			$output .= '<div class="scd-m-row">' . $link
				. '<button class="scd-m-toggle" type="button" aria-expanded="false" aria-label="'
				. esc_attr( sprintf( __( 'Show %s submenu', 'siteorigin-corp' ), $item->title ) ) . '">'
				. scd_icon( 'chevron' )
				. '</button></div>';
		} else {
			$output .= $link;
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= '</li>';
	}
}
