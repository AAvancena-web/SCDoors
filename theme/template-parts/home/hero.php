<?php
/**
 * Homepage banner.
 *
 * No tint is laid over the photograph. The copy stays readable through text
 * shadows alone, which is why the shadow is cleared again on the buttons.
 *
 * @package SCDoors
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$image  = scd_field( 'hero_image' );
$rating = scd_field( 'hero_rating' );
$form   = scd_form( 'hero' );
?>
<section class="scd-hero">
	<?php if ( ! empty( $image['url'] ) ) : ?>
		<div class="scd-hero__bg" id="scdHeroBg" aria-hidden="true" style="background-image:url('<?php echo esc_url( $image['url'] ); ?>');"></div>
	<?php endif; ?>

	<div class="scd-container">
		<div class="scd-hero__content">

			<?php if ( $rating ) : ?>
				<div class="scd-hero__rating">
					<?php echo scd_stars(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php echo esc_html( $rating ); ?></span>
				</div>
			<?php endif; ?>

			<?php if ( $heading = scd_field( 'hero_heading' ) ) : ?>
				<h1><?php echo scd_highlight( $heading ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
			<?php endif; ?>

			<?php if ( $text = scd_field( 'hero_text' ) ) : ?>
				<p class="scd-hero__text"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>

			<?php if ( have_rows( 'hero_points' ) ) : ?>
				<ul class="scd-hero__list">
					<?php while ( have_rows( 'hero_points' ) ) : the_row(); ?>
						<li>
							<span class="scd-tick"><?php scd_the_icon( 'check' ); ?></span>
							<?php echo esc_html( get_sub_field( 'point' ) ); ?>
						</li>
					<?php endwhile; ?>
				</ul>
			<?php endif; ?>

			<div class="scd-hero__btns">
				<a class="scd-btn scd-btn--primary scd-btn--lg" href="#scd-contact">
					<?php scd_the_icon( 'doc' ); ?>
					<?php esc_html_e( 'Get a Free Quote', 'siteorigin-corp' ); ?>
				</a>
				<a class="scd-btn scd-btn--ghost scd-btn--lg" href="tel:<?php echo esc_attr( scd_phone_link() ); ?>">
					<?php scd_the_icon( 'phone' ); ?>
					<?php echo esc_html( 'Call ' . scd_phone() ); ?>
				</a>
			</div>

			<?php if ( have_rows( 'hero_meta' ) ) : ?>
				<div class="scd-hero__meta">
					<?php while ( have_rows( 'hero_meta' ) ) : the_row(); ?>
						<div>
							<?php scd_the_icon( get_sub_field( 'icon' ) ); ?>
							<?php echo esc_html( get_sub_field( 'label' ) ); ?>
						</div>
					<?php endwhile; ?>
				</div>
			<?php endif; ?>

		</div>

		<?php if ( $form ) : ?>
			<div class="scd-quote-card">
				<div class="scd-quote-card__head">
					<h2><?php esc_html_e( 'Get a Free Quote', 'siteorigin-corp' ); ?></h2>
					<p><?php esc_html_e( 'Tell us what you need and we will get back to you fast with honest, transparent pricing.', 'siteorigin-corp' ); ?></p>
				</div>
				<div class="scd-quote-card__body">
					<?php echo $form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
