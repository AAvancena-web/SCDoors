<?php
/**
 * Small output helpers shared by the templates.
 *
 * @package SCDoors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read an ACF value with a fallback, safe when ACF is not active.
 */
function scd_field( $name, $post_id = false, $default = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $default;
	}
	$value = get_field( $name, $post_id );
	return ( '' === $value || null === $value || false === $value ) ? $default : $value;
}

/**
 * Site wide option with a hard coded fallback, so the header and footer still
 * render correctly before the settings page has been filled in.
 */
function scd_option( $name, $default = '' ) {
	$value = function_exists( 'get_field' ) ? get_field( $name, 'option' ) : '';
	return ( '' === $value || null === $value || false === $value ) ? $default : $value;
}

function scd_phone()      { return scd_option( 'scd_phone', '0467 674 414' ); }
function scd_phone_link() { return scd_option( 'scd_phone_link', '0467674414' ); }
function scd_email()      { return scd_option( 'scd_email', 'info@scdoors.com.au' ); }
function scd_address()    { return scd_option( 'scd_address', 'Melbourne, VIC 3000' ); }
function scd_map_link()   { return scd_option( 'scd_map_link', 'https://maps.app.goo.gl/5HMPy4gQFPcqFQMe7' ); }

/**
 * Inline SVG icons. Keeping them in PHP avoids an icon font request and lets
 * the icons inherit currentColor.
 */
function scd_icon( $name, $class = '' ) {
	$stroke = 'fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';

	$paths = array(
		'phone'     => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.8a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.8 2.1z"/>',
		'mail'      => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
		'pin'       => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
		'clock'     => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'shield'    => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
		'dollar'    => '<path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>',
		'medal'     => '<circle cx="12" cy="8" r="6"/><path d="m8.2 13.8-1.4 7 5.2-3 5.2 3-1.4-7"/>',
		'tool'      => '<path d="M14.7 6.3a4 4 0 0 0 5 5l-9.2 9.2a2.8 2.8 0 0 1-4-4z"/><path d="m18 2 4 4"/>',
		'case'      => '<path d="M20 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><path d="m9 13 2 2 4-4"/>',
		'arrow'     => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'check'     => '<path d="m5 13 4 4L19 7"/>',
		'chevron'   => '<path d="m6 9 6 6 6-6"/>',
		'plus'      => '<path d="M12 5v14M5 12h14"/>',
		'up'        => '<path d="M12 19V5"/><path d="m5 12 7-7 7 7"/>',
		'lock'      => '<rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
		'doc'       => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M9 15h6"/>',
	);

	$solid = array(
		'star'      => '<path d="m12 2 3 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.9 21l1.2-6.8-5-4.9 6.9-1z"/>',
		'facebook'  => '<path d="M13.5 22v-8h2.7l.4-3.2h-3.1V8.8c0-.9.3-1.5 1.6-1.5h1.6V4.4c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.4-4 4.1v2.4H7.6V14h2.7v8z"/>',
	);

	$attr = $class ? ' class="' . esc_attr( $class ) . '"' : '';

	if ( isset( $solid[ $name ] ) ) {
		return '<svg' . $attr . ' viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' . $solid[ $name ] . '</svg>';
	}

	if ( 'instagram' === $name ) {
		return '<svg' . $attr . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor" stroke="none"/></svg>';
	}

	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}

	return '<svg' . $attr . ' viewBox="0 0 24 24" ' . $stroke . ' aria-hidden="true">' . $paths[ $name ] . '</svg>';
}

function scd_the_icon( $name, $class = '' ) {
	echo scd_icon( $name, $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Five stars.
 */
function scd_stars( $label = '5 out of 5' ) {
	$out = '<span class="scd-stars" aria-label="' . esc_attr( $label ) . '">';
	for ( $i = 0; $i < 5; $i++ ) {
		$out .= scd_icon( 'star' );
	}
	return $out . '</span>';
}

/**
 * Highlight shortcode inside headings: [hl]word[/hl].
 */
function scd_highlight( $text ) {
	$text = esc_html( $text );
	return str_replace(
		array( '[hl]', '[/hl]' ),
		array( '<em>', '</em>' ),
		$text
	);
}

/**
 * Render a Contact Form 7 form by stored id, with a visible notice for editors
 * if the id has not been set yet. Returns an empty string for visitors.
 */
function scd_form( $which = 'hero' ) {
	$id = scd_option( 'scd_cf7_' . $which, '' );

	if ( ! $id || ! shortcode_exists( 'contact-form-7' ) ) {
		if ( current_user_can( 'edit_pages' ) ) {
			return '<p class="scd-form-note">' . esc_html__( 'Set the Contact Form 7 id under SC Doors settings.', 'siteorigin-corp' ) . '</p>';
		}
		return '';
	}

	return do_shortcode( '[contact-form-7 id="' . esc_attr( $id ) . '"]' );
}

/**
 * Section closing CTA, used at the end of several homepage sections.
 */
function scd_section_cta( $text, $label = 'Get a Free Quote' ) {
	if ( ! $text ) {
		return;
	}
	?>
	<div class="scd-section-cta scd-reveal">
		<p class="scd-section-cta__text"><?php echo esc_html( $text ); ?></p>
		<div class="scd-section-cta__btns">
			<div class="scd-cta-glow">
				<a class="scd-btn scd-btn--primary scd-btn--lg" href="#scd-contact">
					<?php echo esc_html( $label ); ?> <?php scd_the_icon( 'arrow', 'scd-cta-arrow' ); ?>
				</a>
			</div>
			<a class="scd-btn scd-btn--call scd-btn--lg" href="tel:<?php echo esc_attr( scd_phone_link() ); ?>">
				<?php scd_the_icon( 'phone' ); ?> <?php echo esc_html( 'Call ' . scd_phone() ); ?>
			</a>
		</div>
	</div>
	<?php
}
