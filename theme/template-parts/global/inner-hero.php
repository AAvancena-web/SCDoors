<?php
/**
 * Inner page banner.
 *
 * Same split as the homepage: copy on the left, quote form on the right.
 * Renders on any singular page or post that has a banner heading, a featured
 * image or the banner form switched on.
 *
 * @package SCDoors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_singular() ) {
	return;
}

$scd_sub     = scd_field( 'banner_heading_sub' );
$scd_heading = scd_field( 'banner_heading' );
$scd_intro   = scd_field( 'banner_content' );
$scd_show    = 'hide' !== scd_field( 'banner_form', false, 'show' );
$scd_bg      = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'full' ) : '';

// Nothing configured for this page, fall back to the theme's normal layout.
if ( ! $scd_bg && ! $scd_heading && ! $scd_intro ) {
	return;
}

$scd_form = $scd_show ? scd_form( 'hero' ) : '';
?>
<section class="scd-scope scd-hero scd-inner-hero<?php echo $scd_form ? '' : ' scd-inner-hero--solo'; ?>">
	<?php if ( $scd_bg ) : ?>
		<div class="scd-hero__bg" id="scdHeroBg" aria-hidden="true" style="background-image:url('<?php echo esc_url( $scd_bg ); ?>');"></div>
	<?php endif; ?>

	<div class="scd-container">
		<div class="scd-hero__content">
			<?php if ( $scd_sub ) : ?>
				<span class="scd-eyebrow"><?php echo esc_html( $scd_sub ); ?></span>
			<?php endif; ?>

			<?php
			if ( $scd_heading ) {
				echo '<h1 class="entry-title">' . scd_highlight( $scd_heading ) . '</h1>';
			} else {
				the_title( '<h1 class="entry-title">', '</h1>' );
			}
			?>

			<?php if ( $scd_intro ) : ?>
				<div class="scd-hero__text"><?php echo wp_kses_post( $scd_intro ); ?></div>
			<?php endif; ?>

			<div class="scd-hero__btns">
				<a class="scd-btn scd-btn--primary scd-btn--lg" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">
					<?php scd_the_icon( 'doc' ); ?>
					<?php esc_html_e( 'Get a Free Quote', 'siteorigin-corp' ); ?>
				</a>
				<a class="scd-btn scd-btn--ghost scd-btn--lg" href="tel:<?php echo esc_attr( scd_phone_link() ); ?>">
					<?php scd_the_icon( 'phone' ); ?>
					<?php echo esc_html( 'Call ' . scd_phone() ); ?>
				</a>
			</div>

			<?php
			if ( function_exists( 'get_hansel_and_gretel_breadcrumbs' ) ) {
				echo get_hansel_and_gretel_breadcrumbs(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>
		</div>

		<?php if ( $scd_form ) : ?>
			<div class="scd-quote-card">
				<div class="scd-quote-card__head">
					<h2><?php esc_html_e( 'Get a Free Quote', 'siteorigin-corp' ); ?></h2>
					<p><?php esc_html_e( 'Tell us what you need and we will get back to you fast with honest, transparent pricing.', 'siteorigin-corp' ); ?></p>
				</div>
				<div class="scd-quote-card__body">
					<?php echo $scd_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
