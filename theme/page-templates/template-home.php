<?php
/**
 * Template Name: SC Homepage 2026
 * Template Post Type: page
 *
 * Assign this to any page. Content comes from the Homepage 2026 field group,
 * so the page editor itself can be left empty.
 *
 * @package SCDoors
 */

get_header();

while ( have_posts() ) :
	the_post();

	$parts = array(
		'hero',
		'trustbar',
		'services',
		'about',
		'products',
		'process',
		'quote-strip',
		'why',
		'testimonials',
		'faq',
		'contact',
	);

	// scd-scope carries the base typography for the 2026 design. Keeping it on
	// this wrapper rather than <body> means the legacy pages are untouched.
	echo '<div class="scd-scope scd-main">';

	foreach ( $parts as $part ) {
		get_template_part( 'template-parts/home/' . $part );
	}

	echo '</div>';

endwhile;

get_footer();
